<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../../models/Billing.php';

class BillingController extends BaseController {
    
    // Billing dashboard
    public function index() {
        $billingModel = new Billing();
        
        $stats = $billingModel->getStats();
        $recentTransactions = $billingModel->getRecent(10);
        $pendingBills = $billingModel->getPendingBills();
        $revenueData = $billingModel->getRevenueByPeriod('month');
        
        // Ensure stats has all required keys
        if (!isset($stats['total_revenue'])) {
            $stats['total_revenue'] = 0;
        }
        if (!isset($stats['total_paid'])) {
            $stats['total_paid'] = 0;
        }
        if (!isset($stats['total_pending'])) {
            $stats['total_pending'] = 0;
        }
        if (!isset($stats['count_paid'])) {
            $stats['count_paid'] = 0;
        }
        
        $data = [
            'title' => 'Billing Dashboard',
            'stats' => $stats,
            'recentTransactions' => $recentTransactions,
            'pendingBills' => $pendingBills,
            'revenueData' => $revenueData,
            'success' => isset($_SESSION['success']) ? $_SESSION['success'] : null,
            'error' => isset($_SESSION['error']) ? $_SESSION['error'] : null
        ];
        
        unset($_SESSION['success']);
        unset($_SESSION['error']);
        
        $this->view('billing/index', $data);
    }
    
    // Show bill details (renamed from view to show)
    public function show($id) {
        $db = Database::getInstance()->getConnection();
        
        $sql = "SELECT b.*, u.name as patient_name, u.email as patient_email, u.phone as patient_phone,
                       a.appointment_date, a.appointment_time, a.reason,
                       d.name as doctor_name, doc.specialization_id, s.name as specialization_name
                FROM billing b
                JOIN users u ON b.patient_id = u.id
                JOIN appointments a ON b.appointment_id = a.id
                JOIN users d ON a.doctor_id = d.id
                JOIN doctors doc ON d.id = doc.user_id
                JOIN specializations s ON doc.specialization_id = s.id
                WHERE b.id = ?";
        
        $stmt = $db->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $bill = $stmt->get_result()->fetch_assoc();
        
        if (!$bill) {
            $_SESSION['error'] = 'Bill not found';
            $this->redirect('admin.php?action=billing');
            return;
        }
        
        $data = [
            'title' => 'Bill Details',
            'bill' => $bill
        ];
        
        $this->view('billing/view', $data);
    }
    
    // Mark bill as paid
    public function markPaid($id) {
        $db = Database::getInstance()->getConnection();
        
        $sql = "UPDATE billing SET payment_status = 'paid', paid_at = NOW() WHERE id = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("i", $id);
        
        if ($stmt->execute()) {
            $_SESSION['success'] = 'Bill marked as paid successfully';
        } else {
            $_SESSION['error'] = 'Failed to update bill';
        }
        
        $this->redirect('admin.php?action=billing');
    }
}
?>