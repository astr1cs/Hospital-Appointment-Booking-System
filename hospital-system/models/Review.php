<?php
class Review {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    // Check if patient can review (completed appointment)
    public function canReview($patientId, $appointmentId) {
        $sql = "SELECT id FROM appointments 
                WHERE id = ? AND patient_id = ? AND status = 'completed' 
                AND NOT EXISTS (SELECT 1 FROM doctor_reviews WHERE appointment_id = ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("iii", $appointmentId, $patientId, $appointmentId);
        $stmt->execute();
        return $stmt->get_result()->num_rows > 0;
    }
    
    // Add review
    public function add($data) {
        $sql = "INSERT INTO doctor_reviews (appointment_id, patient_id, doctor_id, rating, review_text) 
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("iiids", $data['appointment_id'], $data['patient_id'], 
                          $data['doctor_id'], $data['rating'], $data['review_text']);
        return $stmt->execute();
    }
    
    // Get patient's reviews
    public function getByPatientId($patientId) {
        $sql = "SELECT r.*, d.name as doctor_name, a.appointment_date
                FROM doctor_reviews r
                JOIN users d ON r.doctor_id = d.id
                JOIN appointments a ON r.appointment_id = a.id
                WHERE r.patient_id = ?
                ORDER BY r.created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $patientId);
        $stmt->execute();
        return $stmt->get_result();
    }
    
    // Update review
    public function update($id, $patientId, $rating, $reviewText) {
        $sql = "UPDATE doctor_reviews SET rating = ?, review_text = ? 
                WHERE id = ? AND patient_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("dsii", $rating, $reviewText, $id, $patientId);
        return $stmt->execute();
    }
    
    // Delete review
    public function delete($id, $patientId) {
        $sql = "DELETE FROM doctor_reviews WHERE id = ? AND patient_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ii", $id, $patientId);
        return $stmt->execute();
    }
}
?>