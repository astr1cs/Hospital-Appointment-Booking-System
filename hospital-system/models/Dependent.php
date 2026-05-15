<?php
class Dependent {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    // Get all dependents for a patient
    public function getByPatientId($patientId) {
        $sql = "SELECT * FROM dependents WHERE primary_patient_id = ? ORDER BY name";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $patientId);
        $stmt->execute();
        return $stmt->get_result();
    }
    
    // Get dependent by ID
    public function getById($id, $patientId) {
        $sql = "SELECT * FROM dependents WHERE id = ? AND primary_patient_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ii", $id, $patientId);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
    
    // Add dependent
    public function add($patientId, $data) {
        $sql = "INSERT INTO dependents (primary_patient_id, name, date_of_birth, relationship, blood_group) 
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("issss", $patientId, $data['name'], $data['date_of_birth'], $data['relationship'], $data['blood_group']);
        return $stmt->execute();
    }
    
    // Update dependent
    public function update($id, $patientId, $data) {
        $sql = "UPDATE dependents SET name = ?, date_of_birth = ?, relationship = ?, blood_group = ? 
                WHERE id = ? AND primary_patient_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ssssii", $data['name'], $data['date_of_birth'], $data['relationship'], $data['blood_group'], $id, $patientId);
        return $stmt->execute();
    }
    
    // Delete dependent
    public function delete($id, $patientId) {
        $sql = "DELETE FROM dependents WHERE id = ? AND primary_patient_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ii", $id, $patientId);
        return $stmt->execute();
    }
}
?>