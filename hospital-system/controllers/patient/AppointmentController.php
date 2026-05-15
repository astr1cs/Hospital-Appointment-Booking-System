<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../../models/Appointment.php';
require_once __DIR__ . '/../../models/Doctor.php';
require_once __DIR__ . '/../../models/Patient.php';

class AppointmentController extends PatientBaseController {
    
    // Show booking form
    public function book() {
        $doctorId = $_GET['doctor_id'] ?? null;
        
        if (!$doctorId) {
            $this->redirect('patient.php?action=doctors');
            return;
        }
        
        $doctorModel = new Doctor();
        $patientModel = new Patient();
        
        $doctor = $doctorModel->getDoctorDetails($doctorId);
        $dependents = $patientModel->getDependents($this->getUserId());
        
        $data = [
            'title' => 'Book Appointment',
            'doctor' => $doctor,
            'dependents' => $dependents,
            'error' => $_SESSION['error'] ?? null
        ];
        
        unset($_SESSION['error']);
        
        $this->view('appointments/book', $data);
    }
    
    // AJAX: Get available slots
    public function getSlots() {
        header('Content-Type: application/json');
        
        $doctorId = $_POST['doctor_id'] ?? null;
        $date = $_POST['date'] ?? null;
        
        if (!$doctorId || !$date) {
            echo json_encode(['success' => false, 'message' => 'Missing required data']);
            return;
        }
        
        $appointmentModel = new Appointment();
        $slots = $appointmentModel->getAvailableSlots($doctorId, $date);
        
        echo json_encode(['success' => true, 'slots' => $slots]);
    }
    
    // Store appointment
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('patient.php?action=doctors');
            return;
        }
        
        $doctorId = $_POST['doctor_id'] ?? null;
        $date = $_POST['date'] ?? null;
        $time = $_POST['time'] ?? null;
        $reason = $_POST['reason'] ?? null;
        $dependentId = $_POST['dependent_id'] ?? null;
        
        // Validate
        if (!$doctorId || !$date || !$time || !$reason) {
            $_SESSION['error'] = 'All fields are required';
            $this->redirect('patient.php?action=appointments&sub=book&doctor_id=' . $doctorId);
            return;
        }
        
        // Check if slot is still available
        $appointmentModel = new Appointment();
        if (!$appointmentModel->isSlotAvailable($doctorId, $date, $time)) {
            $_SESSION['error'] = 'This time slot is no longer available. Please select another.';
            $this->redirect('patient.php?action=appointments&sub=book&doctor_id=' . $doctorId);
            return;
        }
        
        // Book appointment
        $patientId = $this->getUserId();
        if ($appointmentModel->book($patientId, $doctorId, $date, $time, $reason, 'patient', $dependentId)) {
            $_SESSION['success'] = 'Appointment booked successfully!';
            $this->redirect('patient.php?action=appointments');
        } else {
            $_SESSION['error'] = 'Failed to book appointment';
            $this->redirect('patient.php?action=appointments&sub=book&doctor_id=' . $doctorId);
        }
    }
    
    // List appointments
    public function index() {
        $userId = $this->getUserId();
        
        // Upcoming appointments
        $sql = "SELECT a.*, d.name as doctor_name, doc.specialization_id, s.name as specialization_name
                FROM appointments a
                JOIN users d ON a.doctor_id = d.id
                JOIN doctors doc ON d.id = doc.user_id
                JOIN specializations s ON doc.specialization_id = s.id
                WHERE a.patient_id = ? AND a.appointment_date >= CURDATE() AND a.status NOT IN ('cancelled', 'completed')
                ORDER BY a.appointment_date ASC, a.appointment_time ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $upcoming = $stmt->get_result();
        
        // Past appointments
        $sql = "SELECT a.*, d.name as doctor_name, doc.specialization_id, s.name as specialization_name
                FROM appointments a
                JOIN users d ON a.doctor_id = d.id
                JOIN doctors doc ON d.id = doc.user_id
                JOIN specializations s ON doc.specialization_id = s.id
                WHERE a.patient_id = ? AND (a.appointment_date < CURDATE() OR a.status = 'completed')
                ORDER BY a.appointment_date DESC, a.appointment_time DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $past = $stmt->get_result();
        
        $data = [
            'title' => 'My Appointments',
            'upcoming' => $upcoming,
            'past' => $past,
            'success' => $_SESSION['success'] ?? null,
            'error' => $_SESSION['error'] ?? null
        ];
        
        unset($_SESSION['success']);
        unset($_SESSION['error']);
        
        $this->view('appointments/index', $data);
    }
    
    // Cancel appointment
    public function cancel($id) {
        $userId = $this->getUserId();
        
        // Check if appointment belongs to user and can be cancelled
        $sql = "SELECT appointment_date, appointment_time, status FROM appointments WHERE id = ? AND patient_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ii", $id, $userId);
        $stmt->execute();
        $appointment = $stmt->get_result()->fetch_assoc();
        
        if (!$appointment) {
            $_SESSION['error'] = 'Appointment not found';
            $this->redirect('patient.php?action=appointments');
            return;
        }
        
        // Check if already cancelled or completed
        if ($appointment['status'] == 'cancelled') {
            $_SESSION['error'] = 'Appointment already cancelled';
            $this->redirect('patient.php?action=appointments');
            return;
        }
        
        if ($appointment['status'] == 'completed') {
            $_SESSION['error'] = 'Cannot cancel completed appointment';
            $this->redirect('patient.php?action=appointments');
            return;
        }
        
        // Check cancellation policy (2 hours before)
        $appointmentDateTime = new DateTime($appointment['appointment_date'] . ' ' . $appointment['appointment_time']);
        $now = new DateTime();
        $diff = $now->diff($appointmentDateTime);
        $hoursDiff = ($diff->days * 24) + $diff->h;
        
        if ($hoursDiff < 2 && $appointmentDateTime > $now) {
            $_SESSION['error'] = 'Cannot cancel appointment within 2 hours of scheduled time';
            $this->redirect('patient.php?action=appointments');
            return;
        }
        
        // Cancel appointment
        $sql = "UPDATE appointments SET status = 'cancelled' WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $id);
        
        if ($stmt->execute()) {
            $_SESSION['success'] = 'Appointment cancelled successfully';
        } else {
            $_SESSION['error'] = 'Failed to cancel appointment';
        }
        
        $this->redirect('patient.php?action=appointments');
    }
    
    // View appointment details (with consultation notes)
    public function show($id) {
        $userId = $this->getUserId();
        
        $sql = "SELECT a.*, d.name as doctor_name, d.email as doctor_email,
                       doc.specialization_id, s.name as specialization_name,
                       cn.symptoms, cn.diagnosis, cn.prescription, cn.follow_up_date
                FROM appointments a
                JOIN users d ON a.doctor_id = d.id
                JOIN doctors doc ON d.id = doc.user_id
                JOIN specializations s ON doc.specialization_id = s.id
                LEFT JOIN consultation_notes cn ON a.id = cn.appointment_id
                WHERE a.id = ? AND a.patient_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ii", $id, $userId);
        $stmt->execute();
        $appointment = $stmt->get_result()->fetch_assoc();
        
        if (!$appointment) {
            $_SESSION['error'] = 'Appointment not found';
            $this->redirect('patient.php?action=appointments');
            return;
        }
        
        $data = [
            'title' => 'Appointment Details',
            'appointment' => $appointment
        ];
        
        $this->view('appointments/view', $data);
    }
}
?>