<?php
class Receptionist {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    // Get all receptionists
    public function getAll() {
        $sql = "SELECT u.* 
                FROM users u 
                WHERE u.role = 'receptionist'
                ORDER BY u.created_at DESC";
        
        return $this->db->query($sql);
    }
    
    // Get receptionist by ID
    public function getById($id) {
        $sql = "SELECT * FROM users WHERE id = ? AND role = 'receptionist'";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
    
    // Create receptionist (admin creates)
    public function create($userData) {
        $sql = "INSERT INTO users (name, email, password_hash, phone, role, is_active) 
                VALUES (?, ?, ?, ?, 'receptionist', 1)";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ssss", $userData['name'], $userData['email'], 
                        $userData['password_hash'], $userData['phone']);
        return $stmt->execute();
    }
    
    // Update receptionist
    public function update($id, $userData) {
        $sql = "UPDATE users SET name = ?, email = ?, phone = ? WHERE id = ? AND role = 'receptionist'";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("sssi", $userData['name'], $userData['email'], $userData['phone'], $id);
        return $stmt->execute();
    }
    
    // Deactivate receptionist
    public function deactivate($id) {
        $sql = "UPDATE users SET is_active = 0 WHERE id = ? AND role = 'receptionist'";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
    
    // Activate receptionist
    public function activate($id) {
        $sql = "UPDATE users SET is_active = 1 WHERE id = ? AND role = 'receptionist'";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
    
    // Get statistics
    public function getStats() {
        $sql = "SELECT COUNT(*) as total 
                FROM users 
                WHERE role = 'receptionist' AND is_active = 1";
        
        $result = $this->db->query($sql);
        return $result->fetch_assoc();
    }
}
?>