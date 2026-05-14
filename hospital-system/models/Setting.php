<?php
class Billing {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    // Get all billing records
    public function getAll() {
        $sql = "SELECT b.*, u.name as patient_name, a.appointment_date, d.name as doctor_name
                FROM billing b
                JOIN users u ON b.patient_id = u.id
                JOIN appointments a ON b.appointment_id = a.id
                JOIN users d ON a.doctor_id = d.id
                ORDER BY b.created_at DESC";
        return $this->db->query($sql);
    }
    
    // Get billing statistics
    public function getStats() {
        $stats = [];
        
        $queries = [
            'total_paid' => "SELECT COALESCE(SUM(amount), 0) as total FROM billing WHERE payment_status = 'paid'",
            'total_pending' => "SELECT COALESCE(SUM(amount), 0) as total FROM billing WHERE payment_status = 'pending'",
            'count_paid' => "SELECT COUNT(*) as count FROM billing WHERE payment_status = 'paid'",
            'count_pending' => "SELECT COUNT(*) as count FROM billing WHERE payment_status = 'pending'",
            'total_revenue' => "SELECT COALESCE(SUM(amount), 0) as total FROM billing WHERE payment_status = 'paid'"
        ];
        
        foreach ($queries as $key => $sql) {
            $result = $this->db->query($sql);
            $row = $result->fetch_assoc();
            $stats[$key] = $row['total'] ?? $row['count'] ?? 0;
        }
        
        return $stats;
    }
    
    // Get revenue by period
    public function getRevenueByPeriod($period = 'month') {
        switch($period) {
            case 'week':
                $groupBy = "YEARWEEK(created_at)";
                break;
            case 'month':
                $groupBy = "DATE_FORMAT(created_at, '%Y-%m')";
                break;
            case 'day':
                $groupBy = "DATE(created_at)";
                break;
            default:
                $groupBy = "DATE_FORMAT(created_at, '%Y-%m')";
        }
        
        $sql = "SELECT 
                    {$groupBy} as period,
                    COALESCE(SUM(amount), 0) as revenue,
                    COUNT(*) as transaction_count
                FROM billing 
                WHERE payment_status = 'paid'
                GROUP BY period
                ORDER BY period DESC
                LIMIT 12";
        return $this->db->query($sql);
    }
    
    // Get recent transactions
    public function getRecent($limit = 10) {
        $sql = "SELECT b.*, u.name as patient_name, a.appointment_date
                FROM billing b
                JOIN users u ON b.patient_id = u.id
                LEFT JOIN appointments a ON b.appointment_id = a.id
                ORDER BY b.created_at DESC
                LIMIT ?";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $limit);
        $stmt->execute();
        return $stmt->get_result();
    }
    
    // Get pending bills
    public function getPendingBills() {
        $sql = "SELECT b.*, u.name as patient_name, u.email as patient_email, u.phone as patient_phone,
                       a.appointment_date, d.name as doctor_name
                FROM billing b
                JOIN users u ON b.patient_id = u.id
                JOIN appointments a ON b.appointment_id = a.id
                JOIN users d ON a.doctor_id = d.id
                WHERE b.payment_status = 'pending'
                ORDER BY b.created_at DESC";
        return $this->db->query($sql);
    }
}
?>