<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../../models/ConsultationNote.php';

class ConsultationController extends DoctorBaseController {
    
    // Show consultation form
    public function create() {
        $appointmentId = $_GET['id'] ?? null;
        $doctorId = $this->getUserId();
        
        if (!$appointmentId) {
            $this->redirect('doctor.php?action=appointments&sub=today');
            return;
        }
        
        // Get appointment details
        $sql = "SELECT a.*, u.name as patient_name, u.email as patient_email, u.phone as patient_phone,
                       p.date_of_birth, p.blood_group, p.gender, p.medical_history_notes
                FROM appointments a
                JOIN users u ON a.patient_id = u.id
                LEFT JOIN patients p ON u.id = p.user_id
                WHERE a.id = ? AND a.doctor_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ii", $appointmentId, $doctorId);
        $stmt->execute();
        $appointment = $stmt->get_result()->fetch_assoc();
        
        if (!$appointment) {
            $_SESSION['error'] = 'Appointment not found';
            $this->redirect('doctor.php?action=appointments&sub=today');
            return;
        }
        
        // Get existing consultation notes if any
        $consultationModel = new ConsultationNote();
        $notes = $consultationModel->getByAppointmentId($appointmentId);
        
        $data = [
            'title' => 'Consultation Notes',
            'appointment' => $appointment,
            'notes' => $notes
        ];
        
        $this->view('consultation/form', $data);
    }
    
    // Save consultation notes
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('doctor.php?action=appointments&sub=today');
            return;
        }
        
        $appointmentId = $_POST['appointment_id'] ?? null;
        $doctorId = $this->getUserId();
        $patientId = $_POST['patient_id'] ?? null;
        
        $data = [
            'symptoms' => $_POST['symptoms'] ?? '',
            'diagnosis' => $_POST['diagnosis'] ?? '',
            'prescription' => $_POST['prescription'] ?? '',
            'follow_up_date' => $_POST['follow_up_date'] ?? null
        ];
        
        $consultationModel = new ConsultationNote();
        
        if ($consultationModel->save($appointmentId, $doctorId, $patientId, $data)) {
            // Mark appointment as completed
            $consultationModel->completeAppointment($appointmentId);
            $_SESSION['success'] = 'Consultation notes saved and appointment marked as completed';
        } else {
            $_SESSION['error'] = 'Failed to save consultation notes';
        }
        
        $this->redirect('doctor.php?action=appointments&sub=today');
    }
}
?>