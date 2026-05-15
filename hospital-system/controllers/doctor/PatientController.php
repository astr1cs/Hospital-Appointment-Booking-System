<?php
require_once __DIR__ . '/BaseController.php';

class PatientController extends DoctorBaseController {
    
    // List all patients who have appointments with this doctor
    public function index() {
        $userId = $this->getUserId();
        
        $search = $_GET['search'] ?? null;
        
        $sql = "SELECT DISTINCT u.id, u.name, u.email, u.phone, 
                       p.date_of_birth, p.blood_group, p.gender,
                       COUNT(a.id) as total_visits,
                       MAX(a.appointment_date) as last_visit
                FROM appointments a
                JOIN users u ON a.patient_id = u.id
                LEFT JOIN patients p ON u.id = p.user_id
                WHERE a.doctor_id = ?";
        
        if ($search) {
            $searchTerm = "%{$search}%";
            $sql .= " AND (u.name LIKE ? OR u.email LIKE ? OR u.phone LIKE ?)";
            $sql .= " GROUP BY u.id ORDER BY last_visit DESC";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("isss", $userId, $searchTerm, $searchTerm, $searchTerm);
        } else {
            $sql .= " GROUP BY u.id ORDER BY last_visit DESC";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("i", $userId);
        }
        
        $stmt->execute();
        $patients = $stmt->get_result();
        
        $data = [
            'title' => 'My Patients',
            'patients' => $patients,
            'search' => $search
        ];
        
        $this->view('patients/index', $data);
    }
    
    // View patient medical history
    public function history($id) {
        $doctorId = $this->getUserId();
        
        // Get patient details
        $sql = "SELECT u.*, p.* 
                FROM users u 
                LEFT JOIN patients p ON u.id = p.user_id
                WHERE u.id = ? AND u.role = 'patient'";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $patient = $stmt->get_result()->fetch_assoc();
        
        if (!$patient) {
            $_SESSION['error'] = 'Patient not found';
            $this->redirect('doctor.php?action=patients');
            return;
        }
        
        // Get appointment history with this doctor
        $sql = "SELECT a.*, 
                       cn.symptoms, cn.diagnosis, cn.prescription, cn.follow_up_date,
                       CASE WHEN cn.id IS NOT NULL THEN 1 ELSE 0 END as has_notes
                FROM appointments a
                LEFT JOIN consultation_notes cn ON a.id = cn.appointment_id
                WHERE a.patient_id = ? AND a.doctor_id = ?
                ORDER BY a.appointment_date DESC, a.appointment_time DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ii", $id, $doctorId);
        $stmt->execute();
        $appointments = $stmt->get_result();
        
        $data = [
            'title' => 'Patient Medical History',
            'patient' => $patient,
            'appointments' => $appointments
        ];
        
        $this->view('patients/history', $data);
    }
}
?>