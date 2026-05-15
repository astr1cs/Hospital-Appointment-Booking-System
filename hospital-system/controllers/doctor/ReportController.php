<?php
require_once __DIR__ . '/BaseController.php';

class ReportController extends DoctorBaseController {
    public function index() {
    $this->redirect('doctor.php?action=reports&sub=earnings');
}
    // Earnings report
    public function earnings() {
        $userId = $this->getUserId();
        
        $period = $_GET['period'] ?? 'month';
        $startDate = $_GET['start_date'] ?? null;
        $endDate = $_GET['end_date'] ?? null;
        
        // Get earnings data by period
        switch($period) {
            case 'day':
                $groupBy = "DATE(a.appointment_date)";
                break;
            case 'week':
                $groupBy = "YEARWEEK(a.appointment_date)";
                break;
            case 'month':
                $groupBy = "DATE_FORMAT(a.appointment_date, '%Y-%m')";
                break;
            default:
                $groupBy = "DATE_FORMAT(a.appointment_date, '%Y-%m')";
        }
        
        $sql = "SELECT 
                    {$groupBy} as period,
                    COUNT(a.id) as appointments_count,
                    SUM(b.amount) as total_earnings
                FROM appointments a
                JOIN billing b ON a.id = b.appointment_id
                WHERE a.doctor_id = ? AND b.payment_status = 'paid'";
        
        if ($startDate) {
            $sql .= " AND a.appointment_date >= ?";
        }
        if ($endDate) {
            $sql .= " AND a.appointment_date <= ?";
        }
        
        $sql .= " GROUP BY period ORDER BY period DESC";
        
        $stmt = $this->db->prepare($sql);
        if ($startDate && $endDate) {
            $stmt->bind_param("iss", $userId, $startDate, $endDate);
        } elseif ($startDate) {
            $stmt->bind_param("is", $userId, $startDate);
        } elseif ($endDate) {
            $stmt->bind_param("is", $userId, $endDate);
        } else {
            $stmt->bind_param("i", $userId);
        }
        $stmt->execute();
        $earnings = $stmt->get_result();
        
        // Get total summary
        $sql = "SELECT 
                    COUNT(a.id) as total_appointments,
                    SUM(b.amount) as total_earnings,
                    AVG(b.amount) as avg_earning
                FROM appointments a
                JOIN billing b ON a.id = b.appointment_id
                WHERE a.doctor_id = ? AND b.payment_status = 'paid'";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $summary = $stmt->get_result()->fetch_assoc();
        
        // Get monthly stats
        $sql = "SELECT 
                    DATE_FORMAT(a.appointment_date, '%Y-%m') as month,
                    COUNT(a.id) as appointments,
                    SUM(b.amount) as earnings
                FROM appointments a
                JOIN billing b ON a.id = b.appointment_id
                WHERE a.doctor_id = ? AND b.payment_status = 'paid'
                GROUP BY DATE_FORMAT(a.appointment_date, '%Y-%m')
                ORDER BY month DESC
                LIMIT 6";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $monthlyStats = $stmt->get_result();
        
        $data = [
            'title' => 'Earnings Report',
            'earnings' => $earnings,
            'summary' => $summary,
            'monthlyStats' => $monthlyStats,
            'period' => $period,
            'startDate' => $startDate,
            'endDate' => $endDate
        ];
        
        $this->view('reports/earnings', $data);
    }
    
    // Appointment statistics
    public function stats() {
        $userId = $this->getUserId();
        
        // Get appointment stats
        $sql = "SELECT 
                    COUNT(*) as total,
                    SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending,
                    SUM(CASE WHEN status = 'confirmed' THEN 1 ELSE 0 END) as confirmed,
                    SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed,
                    SUM(CASE WHEN status = 'cancelled' THEN 1 ELSE 0 END) as cancelled,
                    SUM(CASE WHEN status = 'no_show' THEN 1 ELSE 0 END) as no_show
                FROM appointments
                WHERE doctor_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $appointmentStats = $stmt->get_result()->fetch_assoc();
        
        // Calculate completion rate
        $totalCompleted = $appointmentStats['completed'];
        $totalAppointments = $appointmentStats['total'] - $appointmentStats['cancelled'];
        $completionRate = $totalAppointments > 0 ? round(($totalCompleted / $totalAppointments) * 100, 1) : 0;
        
        // Get busiest days
        $sql = "SELECT 
                    DAYNAME(appointment_date) as day,
                    COUNT(*) as count
                FROM appointments
                WHERE doctor_id = ? AND status NOT IN ('cancelled', 'no_show')
                GROUP BY DAYNAME(appointment_date)
                ORDER BY FIELD(DAYNAME(appointment_date), 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday')";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $busyDays = $stmt->get_result();
        
        $data = [
            'title' => 'Appointment Statistics',
            'appointmentStats' => $appointmentStats,
            'completionRate' => $completionRate,
            'busyDays' => $busyDays
        ];
        
        $this->view('reports/stats', $data);
    }
}
?>