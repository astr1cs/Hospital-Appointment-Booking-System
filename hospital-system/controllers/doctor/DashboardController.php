<?php
require_once __DIR__ . '/BaseController.php';

class DashboardController extends DoctorBaseController {
    
    public function index() {
        $userId = $this->getUserId();
        $today = date('Y-m-d');
        
        // Today's appointments count
        $sql = "SELECT COUNT(*) as count FROM appointments 
                WHERE doctor_id = ? AND appointment_date = ? 
                AND status NOT IN ('cancelled', 'completed')";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("is", $userId, $today);
        $stmt->execute();
        $todayCount = $stmt->get_result()->fetch_assoc()['count'];
        
        // Total patients
        $sql = "SELECT COUNT(DISTINCT patient_id) as count FROM appointments WHERE doctor_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $totalPatients = $stmt->get_result()->fetch_assoc()['count'];
        
        // Completed appointments
        $sql = "SELECT COUNT(*) as count FROM appointments 
                WHERE doctor_id = ? AND status = 'completed'";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $completedCount = $stmt->get_result()->fetch_assoc()['count'];
        
        // Total earnings
        $sql = "SELECT SUM(b.amount) as total FROM billing b
                JOIN appointments a ON b.appointment_id = a.id
                WHERE a.doctor_id = ? AND b.payment_status = 'paid'";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $totalEarnings = $stmt->get_result()->fetch_assoc()['total'] ?? 0;
        
        // Recent appointments
        $sql = "SELECT a.*, p.name as patient_name 
                FROM appointments a
                JOIN users p ON a.patient_id = p.id
                WHERE a.doctor_id = ?
                ORDER BY a.appointment_date DESC
                LIMIT 5";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $recentAppointments = $stmt->get_result();
        
        $data = [
            'title' => 'Dashboard',
            'todayCount' => $todayCount,
            'totalPatients' => $totalPatients,
            'completedCount' => $completedCount,
            'totalEarnings' => $totalEarnings,
            'recentAppointments' => $recentAppointments
        ];
        
        $this->view('dashboard', $data);
    }
}
?>