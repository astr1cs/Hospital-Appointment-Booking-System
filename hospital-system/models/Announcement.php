<?php
class Announcement {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    // Get all announcements
    public function getAll() {
        $sql = "SELECT a.*, u.name as author_name 
                FROM announcements a
                JOIN users u ON a.author_id = u.id
                ORDER BY a.published_at DESC";
        return $this->db->query($sql);
    }
    
    // Get announcements by target role
    public function getByTarget($role = 'all') {
        $sql = "SELECT a.*, u.name as author_name 
                FROM announcements a
                JOIN users u ON a.author_id = u.id
                WHERE a.target_role = ? OR a.target_role = 'all'
                ORDER BY a.published_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("s", $role);
        $stmt->execute();
        return $stmt->get_result();
    }
    
    // Get announcement by ID
    public function getById($id) {
        $sql = "SELECT * FROM announcements WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
    
    // Create announcement
    public function create($data) {
        $sql = "INSERT INTO announcements (author_id, title, body, target_role) 
                VALUES (?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("isss", $data['author_id'], $data['title'], $data['body'], $data['target_role']);
        return $stmt->execute();
    }
    
    // Delete announcement (AJAX)
    public function delete($id) {
        $sql = "DELETE FROM announcements WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
    
    // Get count
    public function getCount() {
        $sql = "SELECT COUNT(*) as count FROM announcements";
        $result = $this->db->query($sql);
        return $result->fetch_assoc()['count'];
    }
}
?>