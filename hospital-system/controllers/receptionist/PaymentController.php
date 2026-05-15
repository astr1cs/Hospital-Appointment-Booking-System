<?php
require_once __DIR__ . '/BaseController.php';

class PaymentController extends ReceptionistBaseController {
    
    // View pending payments
    public function index() {
        // Get all pending payments
        $sql = "SELECT b.*, u.name as patient_name, u.email as patient_email, u.phone as patient_phone,
                       a.appointment_date, a.appointment_time, d.name as doctor_name
                FROM billing b
                JOIN users u ON b.patient_id = u.id
                JOIN appointments a ON b.appointment_id = a.id
                JOIN users d ON a.doctor_id = d.id
                WHERE b.payment_status = 'pending'
                ORDER BY a.appointment_date ASC";
        $result = $this->db->query($sql);
        $pendingBills = $result;
        
        // Get paid payments
        $sql = "SELECT b.*, u.name as patient_name, u.email as patient_email, u.phone as patient_phone,
                       a.appointment_date, a.appointment_time, d.name as doctor_name
                FROM billing b
                JOIN users u ON b.patient_id = u.id
                JOIN appointments a ON b.appointment_id = a.id
                JOIN users d ON a.doctor_id = d.id
                WHERE b.payment_status = 'paid'
                ORDER BY b.paid_at DESC
                LIMIT 20";
        $result = $this->db->query($sql);
        $paidBills = $result;
        
        // Get totals
        $sql = "SELECT 
                    SUM(CASE WHEN payment_status = 'pending' THEN amount ELSE 0 END) as total_pending,
                    SUM(CASE WHEN payment_status = 'paid' THEN amount ELSE 0 END) as total_paid,
                    COUNT(CASE WHEN payment_status = 'pending' THEN 1 END) as pending_count,
                    COUNT(CASE WHEN payment_status = 'paid' THEN 1 END) as paid_count
                FROM billing";
        $result = $this->db->query($sql);
        $totals = $result->fetch_assoc();
        
        $data = [
            'title' => 'Process Payments',
            'pendingBills' => $pendingBills,
            'paidBills' => $paidBills,
            'totals' => $totals,
            'success' => $_SESSION['success'] ?? null,
            'error' => $_SESSION['error'] ?? null
        ];
        
        unset($_SESSION['success']);
        unset($_SESSION['error']);
        
        $this->view('payments/index', $data);
    }
    
    // Process payment for a bill
    public function process() {
        $billId = $_GET['id'] ?? null;
        
        if (!$billId) {
            $_SESSION['error'] = 'Bill ID required';
            $this->redirect('receptionist.php?action=payments');
            return;
        }
        
        $data = [
            'title' => 'Process Payment',
            'billId' => $billId,
            'error' => $_SESSION['error'] ?? null
        ];
        
        unset($_SESSION['error']);
        
        $this->view('payments/process', $data);
    }
    
    // Confirm payment
    public function confirm() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('receptionist.php?action=payments');
            return;
        }
        
        $billId = $_POST['bill_id'] ?? null;
        $paymentMethod = $_POST['payment_method'] ?? 'cash';
        
        if (!$billId) {
            $_SESSION['error'] = 'Bill ID required';
            $this->redirect('receptionist.php?action=payments');
            return;
        }
        
        // Update payment status
        $sql = "UPDATE billing SET payment_status = 'paid', payment_method = ?, paid_at = NOW() WHERE id = ? AND payment_status = 'pending'";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("si", $paymentMethod, $billId);
        
        if ($stmt->execute() && $stmt->affected_rows > 0) {
            $_SESSION['success'] = 'Payment processed successfully';
        } else {
            $_SESSION['error'] = 'Failed to process payment';
        }
        
        $this->redirect('receptionist.php?action=payments');
    }
    
    // Print receipt
    public function receipt($id) {
        $sql = "SELECT b.*, u.name as patient_name, u.email as patient_email, u.phone as patient_phone,
                       a.appointment_date, a.appointment_time, a.reason, d.name as doctor_name
                FROM billing b
                JOIN users u ON b.patient_id = u.id
                JOIN appointments a ON b.appointment_id = a.id
                JOIN users d ON a.doctor_id = d.id
                WHERE b.id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $bill = $stmt->get_result()->fetch_assoc();
        
        if (!$bill) {
            $_SESSION['error'] = 'Bill not found';
            $this->redirect('receptionist.php?action=payments');
            return;
        }
        
        $data = [
            'title' => 'Payment Receipt',
            'bill' => $bill
        ];
        
        $this->view('payments/receipt', $data);
    }
}
?>