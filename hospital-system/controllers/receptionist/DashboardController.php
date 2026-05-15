<?php
require_once __DIR__ . '/BaseController.php';

class DashboardController extends ReceptionistBaseController {
    
    public function index() {
        $today = date('Y-m-d');
        
        // Today's appointments count
        $sql = "SELECT COUNT(*) as count FROM appointments WHERE appointment_date = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("s", $today);
        $stmt->execute();
        $todayCount = $stmt->get_result()->fetch_assoc()['count'];
        
        // Checked-in patients count
        $sql = "SELECT COUNT(*) as count FROM appointments WHERE appointment_date = ? AND status = 'checked_in'";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("s", $today);
        $stmt->execute();
        $checkedInCount = $stmt->get_result()->fetch_assoc()['count'];
        
        // Completed appointments today
        $sql = "SELECT COUNT(*) as count FROM appointments WHERE appointment_date = ? AND status = 'completed'";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("s", $today);
        $stmt->execute();
        $completedCount = $stmt->get_result()->fetch_assoc()['count'];
        
        // Pending payments
        $sql = "SELECT COUNT(*) as count FROM billing WHERE payment_status = 'pending'";
        $result = $this->db->query($sql);
        $pendingPayments = $result->fetch_assoc()['count'];
        
        $data = [
            'title' => 'Dashboard',
            'todayCount' => $todayCount,
            'checkedInCount' => $checkedInCount,
            'completedCount' => $completedCount,
            'pendingPayments' => $pendingPayments
        ];
        
        $this->view('dashboard', $data);
    }
}
?>