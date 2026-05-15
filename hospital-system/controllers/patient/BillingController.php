<?php
require_once __DIR__ . '/BaseController.php';

class BillingController extends PatientBaseController {
    
    // View billing history
    public function index() {
        $userId = $this->getUserId();
        
        // Get all billing records for the patient
        $sql = "SELECT b.*, a.appointment_date, a.appointment_time,
                       d.name as doctor_name, doc.specialization_id, s.name as specialization_name
                FROM billing b
                JOIN appointments a ON b.appointment_id = a.id
                JOIN users d ON a.doctor_id = d.id
                JOIN doctors doc ON d.id = doc.user_id
                JOIN specializations s ON doc.specialization_id = s.id
                WHERE b.patient_id = ?
                ORDER BY b.created_at DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $bills = $stmt->get_result();
        
        // Get statistics
        $sql = "SELECT 
                    COALESCE(SUM(CASE WHEN payment_status = 'paid' THEN amount ELSE 0 END), 0) as total_paid,
                    COALESCE(SUM(CASE WHEN payment_status = 'pending' THEN amount ELSE 0 END), 0) as total_pending,
                    COUNT(CASE WHEN payment_status = 'paid' THEN 1 END) as paid_count,
                    COUNT(CASE WHEN payment_status = 'pending' THEN 1 END) as pending_count
                FROM billing
                WHERE patient_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $stats = $stmt->get_result()->fetch_assoc();
        
        $data = [
            'title' => 'Billing History',
            'bills' => $bills,
            'stats' => $stats,
            'success' => $_SESSION['success'] ?? null,
            'error' => $_SESSION['error'] ?? null
        ];
        
        unset($_SESSION['success']);
        unset($_SESSION['error']);
        
        $this->view('billing/index', $data);
    }
    
    // View bill details
    public function show($id) {
        $userId = $this->getUserId();
        
        $sql = "SELECT b.*, a.appointment_date, a.appointment_time, a.reason,
                       d.name as doctor_name, d.email as doctor_email,
                       doc.specialization_id, s.name as specialization_name,
                       doc.consultation_fee, doc.experience_years, doc.license_number
                FROM billing b
                JOIN appointments a ON b.appointment_id = a.id
                JOIN users d ON a.doctor_id = d.id
                JOIN doctors doc ON d.id = doc.user_id
                JOIN specializations s ON doc.specialization_id = s.id
                WHERE b.id = ? AND b.patient_id = ?";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ii", $id, $userId);
        $stmt->execute();
        $bill = $stmt->get_result()->fetch_assoc();
        
        if (!$bill) {
            $_SESSION['error'] = 'Bill not found';
            $this->redirect('patient.php?action=billing');
            return;
        }
        
        $data = [
            'title' => 'Bill Details',
            'bill' => $bill
        ];
        
        $this->view('billing/view', $data);
    }
    
    // Pay bill (simulate payment)
    public function pay($id) {
        $userId = $this->getUserId();
        
        // Check if bill exists and is pending
        $sql = "SELECT id, amount FROM billing WHERE id = ? AND patient_id = ? AND payment_status = 'pending'";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ii", $id, $userId);
        $stmt->execute();
        $bill = $stmt->get_result()->fetch_assoc();
        
        if (!$bill) {
            $_SESSION['error'] = 'Bill not found or already paid';
            $this->redirect('patient.php?action=billing');
            return;
        }
        
        // Update payment status
        $sql = "UPDATE billing SET payment_status = 'paid', paid_at = NOW() WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $id);
        
        if ($stmt->execute()) {
            $_SESSION['success'] = 'Payment successful! Amount: $' . number_format($bill['amount'], 2);
        } else {
            $_SESSION['error'] = 'Payment failed. Please try again.';
        }
        
        $this->redirect('patient.php?action=billing');
    }
}
?>