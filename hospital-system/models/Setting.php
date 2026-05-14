<?php
class Setting {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    // Get all settings
    public function getAll() {
        $sql = "SELECT * FROM settings ORDER BY setting_key";
        $result = $this->db->query($sql);
        
        $settings = [];
        while ($row = $result->fetch_assoc()) {
            $settings[$row['setting_key']] = $row;
        }
        return $settings;
    }
    
    // Get setting by key
    public function get($key) {
        $sql = "SELECT * FROM settings WHERE setting_key = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("s", $key);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
    
    // Get setting value only
    public function getValue($key, $default = null) {
        $sql = "SELECT setting_value FROM settings WHERE setting_key = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("s", $key);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($row = $result->fetch_assoc()) {
            return $row['setting_value'];
        }
        return $default;
    }
    
    // Update setting
    public function update($key, $value) {
        $sql = "UPDATE settings SET setting_value = ? WHERE setting_key = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ss", $value, $key);
        return $stmt->execute();
    }
    
    // Get appointment policies
    public function getPolicies() {
        $keys = ['cancellation_hours', 'max_booking_days', 'default_consultation_fee'];
        
        $policies = [];
        foreach ($keys as $key) {
            $policies[$key] = $this->getValue($key, '');
        }
        return $policies;
    }
}
?>