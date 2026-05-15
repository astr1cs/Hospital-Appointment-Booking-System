<?php
class MedicalHistory {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    // Get medical history notes for a patient
    public function getByPatientId($patientId) {
        $sql = "SELECT medical_history_notes FROM patients WHERE user_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $patientId);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
    
    // Update medical history notes
    public function updateNotes($patientId, $notes) {
        $sql = "UPDATE patients SET medical_history_notes = ? WHERE user_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("si", $notes, $patientId);
        return $stmt->execute();
    }
}
?>