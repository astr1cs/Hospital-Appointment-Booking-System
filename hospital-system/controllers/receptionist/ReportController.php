<?php
require_once __DIR__ . '/BaseController.php';

class ReportController extends ReceptionistBaseController {
    
    // Daily report
    public function daily() {
        $date = $_GET['date'] ?? date('Y-m-d');
        
        // Get appointments for the selected date
        $sql = "SELECT a.*, 
                       d.name as doctor_name,
                       p.name as patient_name,
                       p.phone as patient_phone
                FROM appointments a
                JOIN users d ON a.doctor_id = d.id
                JOIN users p ON a.patient_id = p.id
                WHERE a.appointment_date = ?
                ORDER BY a.appointment_time ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("s", $date);
        $stmt->execute();
        $appointments = $stmt->get_result();
        
        // Get statistics for the day
        $sql = "SELECT 
                    COUNT(*) as total,
                    SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending,
                    SUM(CASE WHEN status = 'confirmed' THEN 1 ELSE 0 END) as confirmed,
                    SUM(CASE WHEN status = 'checked_in' THEN 1 ELSE 0 END) as checked_in,
                    SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed,
                    SUM(CASE WHEN status = 'cancelled' THEN 1 ELSE 0 END) as cancelled,
                    SUM(CASE WHEN status = 'no_show' THEN 1 ELSE 0 END) as no_show
                FROM appointments
                WHERE appointment_date = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("s", $date);
        $stmt->execute();
        $stats = $stmt->get_result()->fetch_assoc();
        
        // Get revenue for the day
        $sql = "SELECT 
                    COALESCE(SUM(b.amount), 0) as total_revenue,
                    COUNT(b.id) as paid_count
                FROM billing b
                JOIN appointments a ON b.appointment_id = a.id
                WHERE a.appointment_date = ? AND b.payment_status = 'paid'";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("s", $date);
        $stmt->execute();
        $revenue = $stmt->get_result()->fetch_assoc();
        
        // Group appointments by doctor
        $grouped = [];
        while ($row = $appointments->fetch_assoc()) {
            $doctorId = $row['doctor_id'];
            if (!isset($grouped[$doctorId])) {
                $grouped[$doctorId] = [
                    'doctor_name' => $row['doctor_name'],
                    'appointments' => []
                ];
            }
            $grouped[$doctorId]['appointments'][] = $row;
        }
        
        $data = [
            'title' => 'Daily Report',
            'date' => $date,
            'grouped' => $grouped,
            'stats' => $stats,
            'revenue' => $revenue,
            'success' => $_SESSION['success'] ?? null,
            'error' => $_SESSION['error'] ?? null
        ];
        
        unset($_SESSION['success']);
        unset($_SESSION['error']);
        
        $this->view('reports/daily', $data);
    }
    
    // Export daily report to PDF (printable)
   // Export daily report to PDF (printable - standalone)
public function export() {
    $date = $_GET['date'] ?? date('Y-m-d');
    
    $sql = "SELECT a.*, 
                   d.name as doctor_name,
                   p.name as patient_name,
                   p.phone as patient_phone
            FROM appointments a
            JOIN users d ON a.doctor_id = d.id
            JOIN users p ON a.patient_id = p.id
            WHERE a.appointment_date = ?
            ORDER BY a.appointment_time ASC";
    $stmt = $this->db->prepare($sql);
    $stmt->bind_param("s", $date);
    $stmt->execute();
    $appointments = $stmt->get_result();
    
    // Get statistics
    $sql = "SELECT 
                COUNT(*) as total,
                SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending,
                SUM(CASE WHEN status = 'confirmed' THEN 1 ELSE 0 END) as confirmed,
                SUM(CASE WHEN status = 'checked_in' THEN 1 ELSE 0 END) as checked_in,
                SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed,
                SUM(CASE WHEN status = 'cancelled' THEN 1 ELSE 0 END) as cancelled
            FROM appointments
            WHERE appointment_date = ?";
    $stmt = $this->db->prepare($sql);
    $stmt->bind_param("s", $date);
    $stmt->execute();
    $stats = $stmt->get_result()->fetch_assoc();
    
    // Get revenue
    $sql = "SELECT COALESCE(SUM(b.amount), 0) as total_revenue
            FROM billing b
            JOIN appointments a ON b.appointment_id = a.id
            WHERE a.appointment_date = ? AND b.payment_status = 'paid'";
    $stmt = $this->db->prepare($sql);
    $stmt->bind_param("s", $date);
    $stmt->execute();
    $revenue = $stmt->get_result()->fetch_assoc();
    
    // Group appointments by doctor
    $grouped = [];
    while ($row = $appointments->fetch_assoc()) {
        $doctorId = $row['doctor_id'];
        if (!isset($grouped[$doctorId])) {
            $grouped[$doctorId] = [
                'doctor_name' => $row['doctor_name'],
                'appointments' => []
            ];
        }
        $grouped[$doctorId]['appointments'][] = $row;
    }
    
    $data = [
        'date' => $date,
        'grouped' => $grouped,
        'stats' => $stats,
        'revenue' => $revenue
    ];
    
    // Use standalone export view without layout
    require_once 'views/receptionist/reports/export.php';
    exit();
}
}
?>