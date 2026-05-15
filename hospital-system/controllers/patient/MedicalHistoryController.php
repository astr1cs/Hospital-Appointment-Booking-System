<?php
require_once __DIR__ . '/BaseController.php';

class MedicalHistoryController extends PatientBaseController {
    
    public function index() {
        $userId = $this->getUserId();
        
        $sql = "SELECT medical_history_notes FROM patients WHERE user_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $medicalHistory = $result->fetch_assoc();
        
        $data = [
            'title' => 'Medical History',
            'medicalHistory' => $medicalHistory,
            'success' => $_SESSION['success'] ?? null,
            'error' => $_SESSION['error'] ?? null
        ];
        
        unset($_SESSION['success']);
        unset($_SESSION['error']);
        
        $this->view('medical-history/index', $data);
    }
    
    public function update() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('patient.php?action=medical-history');
            return;
        }
        
        $userId = $this->getUserId();
        $notes = $_POST['medical_history_notes'] ?? '';
        
        $sql = "UPDATE patients SET medical_history_notes = ? WHERE user_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("si", $notes, $userId);
        
        if ($stmt->execute()) {
            $_SESSION['success'] = 'Medical history notes updated successfully';
        } else {
            $_SESSION['error'] = 'Failed to update medical history';
        }
        
        $this->redirect('patient.php?action=medical-history');
    }
}
?>