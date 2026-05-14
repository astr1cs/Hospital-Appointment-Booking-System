<?php
class Appointment {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    // Get count of appointments by date
    public function getCountByDate($date) {
        $sql = "SELECT COUNT(*) as count FROM appointments WHERE appointment_date = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("s", $date);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc()['count'];
    }
    
    // Get recent appointments
    public function getRecent($limit = 5) {
        $sql = "SELECT a.*, 
                p.name as patient_name, 
                d.name as doctor_name
                FROM appointments a
                JOIN users p ON a.patient_id = p.id
                JOIN users d ON a.doctor_id = d.id
                ORDER BY a.created_at DESC
                LIMIT ?";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $limit);
        $stmt->execute();
        return $stmt->get_result();
    }
    
    // Get all appointments with filters
    public function getAll($filters = []) {
        $sql = "SELECT a.*, 
                p.name as patient_name, 
                d.name as doctor_name,
                doc.specialization_id,
                s.name as specialization_name
                FROM appointments a
                JOIN users p ON a.patient_id = p.id
                JOIN users d ON a.doctor_id = d.id
                JOIN doctors doc ON d.id = doc.user_id
                JOIN specializations s ON doc.specialization_id = s.id
                WHERE 1=1";
        
        $params = [];
        $types = "";
        
        if (!empty($filters['doctor_id'])) {
            $sql .= " AND a.doctor_id = ?";
            $params[] = $filters['doctor_id'];
            $types .= "i";
        }
        
        if (!empty($filters['status'])) {
            $sql .= " AND a.status = ?";
            $params[] = $filters['status'];
            $types .= "s";
        }
        
        if (!empty($filters['date_from'])) {
            $sql .= " AND a.appointment_date >= ?";
            $params[] = $filters['date_from'];
            $types .= "s";
        }
        
        if (!empty($filters['date_to'])) {
            $sql .= " AND a.appointment_date <= ?";
            $params[] = $filters['date_to'];
            $types .= "s";
        }
        
        $sql .= " ORDER BY a.appointment_date DESC, a.appointment_time DESC LIMIT 50";
        
        $stmt = $this->db->prepare($sql);
        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }
        $stmt->execute();
        return $stmt->get_result();
    }
}
?>