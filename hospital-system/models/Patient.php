<?php
class Patient {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    // Get all patients
    public function getAll($search = null) {
        $sql = "SELECT u.*, p.date_of_birth, p.blood_group, p.gender, p.address, 
                       p.emergency_contact_name, p.emergency_contact_phone
                FROM users u 
                LEFT JOIN patients p ON u.id = p.user_id 
                WHERE u.role = 'patient'";
        
        if ($search) {
            $search = "%{$search}%";
            $sql .= " AND (u.name LIKE ? OR u.email LIKE ? OR u.phone LIKE ?)";
            $sql .= " ORDER BY u.created_at DESC";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("sss", $search, $search, $search);
            $stmt->execute();
            return $stmt->get_result();
        }
        
        $sql .= " ORDER BY u.created_at DESC";
        return $this->db->query($sql);
    }
    
    // Get patient by ID
    public function getById($id) {
        $sql = "SELECT u.*, p.date_of_birth, p.blood_group, p.gender, p.address, 
                       p.emergency_contact_name, p.emergency_contact_phone, p.medical_history_notes
                FROM users u 
                LEFT JOIN patients p ON u.id = p.user_id 
                WHERE u.id = ? AND u.role = 'patient'";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
    
    // Get patient statistics
    public function getStats() {
        $sql = "SELECT 
                    COUNT(*) as total,
                    SUM(CASE WHEN u.is_active = 1 THEN 1 ELSE 0 END) as active,
                    SUM(CASE WHEN u.is_active = 0 THEN 1 ELSE 0 END) as inactive
                FROM users u 
                WHERE u.role = 'patient'";
        
        $result = $this->db->query($sql);
        return $result->fetch_assoc();
    }
    
    // Get patient appointment history
    public function getAppointmentHistory($patientId, $limit = 10) {
        $sql = "SELECT a.*, d.name as doctor_name, doc.specialization_id, s.name as specialization_name
                FROM appointments a
                JOIN users d ON a.doctor_id = d.id
                JOIN doctors doc ON d.id = doc.user_id
                JOIN specializations s ON doc.specialization_id = s.id
                WHERE a.patient_id = ?
                ORDER BY a.appointment_date DESC, a.appointment_time DESC
                LIMIT ?";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ii", $patientId, $limit);
        $stmt->execute();
        return $stmt->get_result();
    }
    
    // Get patient billing history
    public function getBillingHistory($patientId) {
        $sql = "SELECT b.*, a.appointment_date, d.name as doctor_name
                FROM billing b
                JOIN appointments a ON b.appointment_id = a.id
                JOIN users d ON a.doctor_id = d.id
                WHERE b.patient_id = ?
                ORDER BY b.created_at DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $patientId);
        $stmt->execute();
        return $stmt->get_result();
    }
    
    // Deactivate patient
    public function deactivate($id) {
        $sql = "UPDATE users SET is_active = 0 WHERE id = ? AND role = 'patient'";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
    
    // Activate patient
    public function activate($id) {
        $sql = "UPDATE users SET is_active = 1 WHERE id = ? AND role = 'patient'";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
?>