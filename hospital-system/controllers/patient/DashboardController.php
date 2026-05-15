<?php
require_once __DIR__ . '/BaseController.php';

class DashboardController extends PatientBaseController {
    
    public function index() {
        $userId = $this->getUserId();
        
        // Get upcoming appointments count
        $sql = "SELECT COUNT(*) as count FROM appointments 
                WHERE patient_id = ? AND appointment_date >= CURDATE() 
                AND status NOT IN ('cancelled', 'completed')";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $upcomingCount = $stmt->get_result()->fetch_assoc()['count'];
        
        // Get total appointments
        $sql = "SELECT COUNT(*) as count FROM appointments WHERE patient_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $totalAppointments = $stmt->get_result()->fetch_assoc()['count'];
        
        // Get recent appointments
        $sql = "SELECT a.*, d.name as doctor_name 
                FROM appointments a
                JOIN users d ON a.doctor_id = d.id
                WHERE a.patient_id = ?
                ORDER BY a.appointment_date DESC
                LIMIT 5";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $recentAppointments = $stmt->get_result();
        
        // Get announcements
        $sql = "SELECT * FROM announcements WHERE target_role IN ('all', 'patient') 
                ORDER BY published_at DESC LIMIT 3";
        $announcements = $this->db->query($sql);
        
        $data = [
            'title' => 'Dashboard',
            'upcomingCount' => $upcomingCount,
            'totalAppointments' => $totalAppointments,
            'recentAppointments' => $recentAppointments,
            'announcements' => $announcements
        ];
        
        $this->view('dashboard', $data);
    }
}
?>