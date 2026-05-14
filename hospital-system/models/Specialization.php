<?php
class Specialization {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    // Get all specializations
    public function getAll() {
        $sql = "SELECT * FROM specializations ORDER BY name ASC";
        $result = $this->db->query($sql);
        return $result;
    }
    
    // Get specialization by ID
    public function getById($id) {
        $sql = "SELECT * FROM specializations WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
    
    // Create new specialization
    public function create($name, $description) {
        $sql = "INSERT INTO specializations (name, description) VALUES (?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ss", $name, $description);
        return $stmt->execute();
    }
    
    // Update specialization
    public function update($id, $name, $description) {
        $sql = "UPDATE specializations SET name = ?, description = ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ssi", $name, $description, $id);
        return $stmt->execute();
    }
    
    // Delete specialization
    public function delete($id) {
        // Check if any doctors use this specialization
        $checkSql = "SELECT COUNT(*) as count FROM doctors WHERE specialization_id = ?";
        $checkStmt = $this->db->prepare($checkSql);
        $checkStmt->bind_param("i", $id);
        $checkStmt->execute();
        $result = $checkStmt->get_result()->fetch_assoc();
        
        if ($result['count'] > 0) {
            return ['success' => false, 'message' => 'Cannot delete: This specialization is used by ' . $result['count'] . ' doctor(s)'];
        }
        
        $sql = "DELETE FROM specializations WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $id);
        
        if ($stmt->execute()) {
            return ['success' => true, 'message' => 'Specialization deleted successfully'];
        } else {
            return ['success' => false, 'message' => 'Failed to delete specialization'];
        }
    }
    
    // Check if name exists (for validation)
    public function nameExists($name, $excludeId = null) {
        $sql = "SELECT id FROM specializations WHERE name = ?";
        if ($excludeId) {
            $sql .= " AND id != ?";
        }
        $stmt = $this->db->prepare($sql);
        if ($excludeId) {
            $stmt->bind_param("si", $name, $excludeId);
        } else {
            $stmt->bind_param("s", $name);
        }
        $stmt->execute();
        return $stmt->get_result()->num_rows > 0;
    }
}
?>