<?php
require_once __DIR__ . '/BaseController.php';

class AppointmentController extends DoctorBaseController {
    
    // Default - redirect to today's schedule
  // All appointments (not just today)
public function index() {
    $userId = $this->getUserId();
    
    // Get filter parameters
    $status = $_GET['status'] ?? 'all';
    $dateFrom = $_GET['date_from'] ?? null;
    $dateTo = $_GET['date_to'] ?? null;
    
    $sql = "SELECT a.*, u.name as patient_name, u.email as patient_email, u.phone as patient_phone
            FROM appointments a
            JOIN users u ON a.patient_id = u.id
            WHERE a.doctor_id = ?";
    $params = [$userId];
    $types = "i";
    
    if ($status != 'all') {
        $sql .= " AND a.status = ?";
        $params[] = $status;
        $types .= "s";
    }
    
    if ($dateFrom) {
        $sql .= " AND a.appointment_date >= ?";
        $params[] = $dateFrom;
        $types .= "s";
    }
    
    if ($dateTo) {
        $sql .= " AND a.appointment_date <= ?";
        $params[] = $dateTo;
        $types .= "s";
    }
    
    $sql .= " ORDER BY a.appointment_date DESC, a.appointment_time DESC";
    
    $stmt = $this->db->prepare($sql);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $appointments = $stmt->get_result();
    
    // Get statistics
    $statsSql = "SELECT 
                    COUNT(*) as total,
                    SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending,
                    SUM(CASE WHEN status = 'confirmed' THEN 1 ELSE 0 END) as confirmed,
                    SUM(CASE WHEN status = 'checked_in' THEN 1 ELSE 0 END) as checked_in,
                    SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed,
                    SUM(CASE WHEN status = 'cancelled' THEN 1 ELSE 0 END) as cancelled,
                    SUM(CASE WHEN status = 'no_show' THEN 1 ELSE 0 END) as no_show
                FROM appointments
                WHERE doctor_id = ?";
    $stmt = $this->db->prepare($statsSql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $stats = $stmt->get_result()->fetch_assoc();
    
    $data = [
        'title' => 'All Appointments',
        'appointments' => $appointments,
        'stats' => $stats,
        'currentStatus' => $status,
        'dateFrom' => $dateFrom,
        'dateTo' => $dateTo
    ];
    
    $this->view('appointments/all', $data);
}
    
    // Today's schedule
    // Today's schedule
public function today() {
    $userId = $this->getUserId();
    
    $sql = "SELECT a.*, 
                   u.name as patient_name, 
                   u.email as patient_email, 
                   u.phone as patient_phone,
                   p.date_of_birth, 
                   p.blood_group,
                   p.gender
            FROM appointments a
            JOIN users u ON a.patient_id = u.id
            LEFT JOIN patients p ON u.id = p.user_id
            WHERE a.doctor_id = ? AND a.appointment_date = CURDATE()
            ORDER BY a.appointment_time ASC";
    $stmt = $this->db->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $appointments = $stmt->get_result();
    
    $stats = [];
    $sql = "SELECT 
                COUNT(*) as total,
                SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending,
                SUM(CASE WHEN status = 'confirmed' THEN 1 ELSE 0 END) as confirmed,
                SUM(CASE WHEN status = 'checked_in' THEN 1 ELSE 0 END) as checked_in,
                SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed
            FROM appointments
            WHERE doctor_id = ? AND appointment_date = CURDATE()";
    $stmt = $this->db->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $stats = $stmt->get_result()->fetch_assoc();
    
    $data = [
        'title' => 'Today\'s Schedule',
        'appointments' => $appointments,
        'stats' => $stats
    ];
    
    $this->view('appointments/today', $data);
}
    
    // Weekly schedule
   // Weekly schedule
public function week() {
    $userId = $this->getUserId();
    
    $startOfWeek = date('Y-m-d', strtotime('monday this week'));
    $endOfWeek = date('Y-m-d', strtotime('sunday this week'));
    
    $sql = "SELECT a.*, 
                   u.name as patient_name,
                   DAYNAME(a.appointment_date) as day_name,
                   DATE_FORMAT(a.appointment_date, '%a') as day_short
            FROM appointments a
            JOIN users u ON a.patient_id = u.id
            WHERE a.doctor_id = ? AND a.appointment_date BETWEEN ? AND ?
            ORDER BY a.appointment_date ASC, a.appointment_time ASC";
    $stmt = $this->db->prepare($sql);
    $stmt->bind_param("iss", $userId, $startOfWeek, $endOfWeek);
    $stmt->execute();
    $appointments = $stmt->get_result();
    
    $data = [
        'title' => 'Weekly Schedule',
        'appointments' => $appointments,
        'startOfWeek' => $startOfWeek,
        'endOfWeek' => $endOfWeek
    ];
    
    $this->view('appointments/week', $data);
}
    
    // View appointment details
    // View appointment details
public function show($id) {
    $userId = $this->getUserId();
    
    $sql = "SELECT a.*, 
                   u.name as patient_name, 
                   u.email as patient_email, 
                   u.phone as patient_phone,
                   p.date_of_birth, 
                   p.blood_group, 
                   p.gender, 
                   p.address,
                   p.emergency_contact_name, 
                   p.emergency_contact_phone, 
                   p.medical_history_notes,
                   cn.symptoms, 
                   cn.diagnosis, 
                   cn.prescription, 
                   cn.follow_up_date
            FROM appointments a
            JOIN users u ON a.patient_id = u.id
            LEFT JOIN patients p ON u.id = p.user_id
            LEFT JOIN consultation_notes cn ON a.id = cn.appointment_id
            WHERE a.id = ? AND a.doctor_id = ?";
    $stmt = $this->db->prepare($sql);
    $stmt->bind_param("ii", $id, $userId);
    $stmt->execute();
    $appointment = $stmt->get_result()->fetch_assoc();
    
    if (!$appointment) {
        $_SESSION['error'] = 'Appointment not found';
        $this->redirect('doctor.php?action=appointments&sub=today');
        return;
    }
    
    // Set default values for consultation notes if null
    $appointment['symptoms'] = $appointment['symptoms'] ?? '';
    $appointment['diagnosis'] = $appointment['diagnosis'] ?? '';
    $appointment['prescription'] = $appointment['prescription'] ?? '';
    $appointment['follow_up_date'] = $appointment['follow_up_date'] ?? '';
    
    $data = [
        'title' => 'Appointment Details',
        'appointment' => $appointment
    ];
    
    $this->view('appointments/view', $data);
}
    
    // AJAX: Check in patient
    public function checkin() {
        header('Content-Type: application/json');
        
        $appointmentId = $_POST['appointment_id'] ?? null;
        
        if (!$appointmentId) {
            echo json_encode(['success' => false, 'message' => 'Appointment ID required']);
            return;
        }
        
        $sql = "UPDATE appointments SET status = 'checked_in' WHERE id = ? AND status IN ('pending', 'confirmed')";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $appointmentId);
        
        if ($stmt->execute() && $stmt->affected_rows > 0) {
            echo json_encode(['success' => true, 'message' => 'Patient checked in successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to check in patient']);
        }
    }
    
    // Confirm appointment
    public function confirm($id) {
        $userId = $this->getUserId();
        
        $sql = "UPDATE appointments SET status = 'confirmed' WHERE id = ? AND doctor_id = ? AND status = 'pending'";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ii", $id, $userId);
        $stmt->execute();
        
        $_SESSION['success'] = 'Appointment confirmed';
        $this->redirect('doctor.php?action=appointments&sub=today');
    }
    
    // Reject appointment
    public function reject($id) {
        $userId = $this->getUserId();
        
        $sql = "UPDATE appointments SET status = 'cancelled' WHERE id = ? AND doctor_id = ? AND status = 'pending'";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ii", $id, $userId);
        $stmt->execute();
        
        $_SESSION['success'] = 'Appointment rejected';
        $this->redirect('doctor.php?action=appointments&sub=today');
    }
    
    // Mark as no-show
    public function noshow($id) {
        $userId = $this->getUserId();
        
        $sql = "UPDATE appointments SET status = 'no_show' WHERE id = ? AND doctor_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ii", $id, $userId);
        $stmt->execute();
        
        $_SESSION['success'] = 'Patient marked as no-show';
        $this->redirect('doctor.php?action=appointments&sub=today');
    }
    
    // Complete appointment (redirect to consultation)
    public function complete($id) {
        $this->redirect('doctor.php?action=consultation&sub=create&id=' . $id);
    }
}
?>