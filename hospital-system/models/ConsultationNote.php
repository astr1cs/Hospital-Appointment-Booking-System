<?php
class ConsultationNote {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    // Get consultation notes by appointment ID
    public function getByAppointmentId($appointmentId) {
        $sql = "SELECT * FROM consultation_notes WHERE appointment_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $appointmentId);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
    
    // Create or update consultation notes
    public function save($appointmentId, $doctorId, $patientId, $data) {
        // Check if notes already exist
        $existing = $this->getByAppointmentId($appointmentId);
        
        if ($existing) {
            // Update
            $sql = "UPDATE consultation_notes 
                    SET symptoms = ?, diagnosis = ?, prescription = ?, follow_up_date = ? 
                    WHERE appointment_id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("ssssi", 
                $data['symptoms'], 
                $data['diagnosis'], 
                $data['prescription'], 
                $data['follow_up_date'],
                $appointmentId
            );
        } else {
            // Insert
            $sql = "INSERT INTO consultation_notes (appointment_id, doctor_id, patient_id, symptoms, diagnosis, prescription, follow_up_date) 
                    VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("iiissss", 
                $appointmentId, 
                $doctorId, 
                $patientId,
                $data['symptoms'], 
                $data['diagnosis'], 
                $data['prescription'], 
                $data['follow_up_date']
            );
        }
        
        return $stmt->execute();
    }
    
    // Mark appointment as completed
    public function completeAppointment($appointmentId) {
        $sql = "UPDATE appointments SET status = 'completed' WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $appointmentId);
        return $stmt->execute();
    }
}
?>