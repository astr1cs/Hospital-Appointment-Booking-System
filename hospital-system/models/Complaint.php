<?php
class Complaint {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    // Get all complaints
    public function getAll($status = null) {
        $sql = "SELECT c.*, u.name as patient_name, u.email as patient_email, u.phone as patient_phone,
                       a.appointment_date, d.name as doctor_name
                FROM complaints c
                JOIN users u ON c.patient_id = u.id
                LEFT JOIN appointments a ON c.appointment_id = a.id
                LEFT JOIN users d ON a.doctor_id = d.id";
        
        if ($status && $status != 'all') {
            $sql .= " WHERE c.status = ?";
            $sql .= " ORDER BY c.created_at DESC";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("s", $status);
            $stmt->execute();
            return $stmt->get_result();
        }
        
        $sql .= " ORDER BY c.created_at DESC";
        return $this->db->query($sql);
    }
    
    // Get complaint by ID
    public function getById($id) {
        $sql = "SELECT c.*, u.name as patient_name, u.email as patient_email, u.phone as patient_phone,
                       a.appointment_date, d.name as doctor_name
                FROM complaints c
                JOIN users u ON c.patient_id = u.id
                LEFT JOIN appointments a ON c.appointment_id = a.id
                LEFT JOIN users d ON a.doctor_id = d.id
                WHERE c.id = ?";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
    
    // Resolve complaint with admin response
    public function resolve($id, $adminResponse) {
        $sql = "UPDATE complaints SET status = 'resolved', admin_response = ?, resolved_at = NOW() WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("si", $adminResponse, $id);
        return $stmt->execute();
    }
    
    // Reject complaint
    public function reject($id, $adminResponse) {
        $sql = "UPDATE complaints SET status = 'rejected', admin_response = ?, resolved_at = NOW() WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("si", $adminResponse, $id);
        return $stmt->execute();
    }
    
    // Delete complaint (AJAX)
    public function delete($id) {
        $sql = "DELETE FROM complaints WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
    
    // Get statistics
    public function getStats() {
        $stats = [];
        
        $queries = [
            'total' => "SELECT COUNT(*) as count FROM complaints",
            'pending' => "SELECT COUNT(*) as count FROM complaints WHERE status = 'pending'",
            'resolved' => "SELECT COUNT(*) as count FROM complaints WHERE status = 'resolved'",
            'rejected' => "SELECT COUNT(*) as count FROM complaints WHERE status = 'rejected'"
        ];
        
        foreach ($queries as $key => $sql) {
            $result = $this->db->query($sql);
            $stats[$key] = $result->fetch_assoc()['count'];
        }
        
        return $stats;
    }
}
?>