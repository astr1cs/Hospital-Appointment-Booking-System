<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../../models/Review.php';

class ReviewController extends PatientBaseController {
    
    // List my reviews
    public function index() {
        $userId = $this->getUserId();
        $reviewModel = new Review();
        
        $reviews = $reviewModel->getByPatientId($userId);
        
        $data = [
            'title' => 'My Reviews',
            'reviews' => $reviews,
            'success' => $_SESSION['success'] ?? null,
            'error' => $_SESSION['error'] ?? null
        ];
        
        unset($_SESSION['success']);
        unset($_SESSION['error']);
        
        $this->view('reviews/index', $data);
    }
    
    // Show review form
    public function create() {
        $appointmentId = $_GET['appointment_id'] ?? null;
        $userId = $this->getUserId();
        
        if (!$appointmentId) {
            $this->redirect('patient.php?action=appointments');
            return;
        }
        
        // Get appointment details
        $sql = "SELECT a.*, d.name as doctor_name, d.id as doctor_id
                FROM appointments a
                JOIN users d ON a.doctor_id = d.id
                WHERE a.id = ? AND a.patient_id = ? AND a.status = 'completed'";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ii", $appointmentId, $userId);
        $stmt->execute();
        $appointment = $stmt->get_result()->fetch_assoc();
        
        if (!$appointment) {
            $_SESSION['error'] = 'Cannot review this appointment';
            $this->redirect('patient.php?action=appointments');
            return;
        }
        
        // Check if already reviewed
        $sql = "SELECT id FROM doctor_reviews WHERE appointment_id = ? AND patient_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ii", $appointmentId, $userId);
        $stmt->execute();
        if ($stmt->get_result()->num_rows > 0) {
            $_SESSION['error'] = 'You have already reviewed this appointment';
            $this->redirect('patient.php?action=appointments');
            return;
        }
        
        $data = [
            'title' => 'Write a Review',
            'appointment' => $appointment
        ];
        
        $this->view('reviews/form', $data);
    }
    
    // Store review
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('patient.php?action=appointments');
            return;
        }
        
        $userId = $this->getUserId();
        $appointmentId = $_POST['appointment_id'] ?? null;
        $doctorId = $_POST['doctor_id'] ?? null;
        $rating = $_POST['rating'] ?? null;
        $reviewText = $_POST['review_text'] ?? '';
        
        if (!$appointmentId || !$doctorId || !$rating) {
            $_SESSION['error'] = 'Please provide a rating';
            $this->redirect('patient.php?action=reviews&sub=create&appointment_id=' . $appointmentId);
            return;
        }
        
        $reviewModel = new Review();
        
        if (!$reviewModel->canReview($userId, $appointmentId)) {
            $_SESSION['error'] = 'Cannot review this appointment';
            $this->redirect('patient.php?action=appointments');
            return;
        }
        
        $data = [
            'appointment_id' => $appointmentId,
            'patient_id' => $userId,
            'doctor_id' => $doctorId,
            'rating' => $rating,
            'review_text' => $reviewText
        ];
        
        if ($reviewModel->add($data)) {
            $_SESSION['success'] = 'Thank you for your review!';
        } else {
            $_SESSION['error'] = 'Failed to submit review';
        }
        
        $this->redirect('patient.php?action=reviews');
    }
    
    // Edit review form
    public function edit($id) {
        $userId = $this->getUserId();
        
        $sql = "SELECT r.*, d.name as doctor_name, a.appointment_date
                FROM doctor_reviews r
                JOIN users d ON r.doctor_id = d.id
                JOIN appointments a ON r.appointment_id = a.id
                WHERE r.id = ? AND r.patient_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ii", $id, $userId);
        $stmt->execute();
        $review = $stmt->get_result()->fetch_assoc();
        
        if (!$review) {
            $_SESSION['error'] = 'Review not found';
            $this->redirect('patient.php?action=reviews');
            return;
        }
        
        $data = [
            'title' => 'Edit Review',
            'review' => $review
        ];
        
        $this->view('reviews/form', $data);
    }
    
    // Update review
    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('patient.php?action=reviews');
            return;
        }
        
        $userId = $this->getUserId();
        $rating = $_POST['rating'] ?? null;
        $reviewText = $_POST['review_text'] ?? '';
        
        if (!$rating) {
            $_SESSION['error'] = 'Please provide a rating';
            $this->redirect('patient.php?action=reviews&sub=edit&id=' . $id);
            return;
        }
        
        $reviewModel = new Review();
        
        if ($reviewModel->update($id, $userId, $rating, $reviewText)) {
            $_SESSION['success'] = 'Review updated successfully';
        } else {
            $_SESSION['error'] = 'Failed to update review';
        }
        
        $this->redirect('patient.php?action=reviews');
    }
    
    // Delete review
    public function delete($id) {
        $userId = $this->getUserId();
        $reviewModel = new Review();
        
        if ($reviewModel->delete($id, $userId)) {
            $_SESSION['success'] = 'Review deleted successfully';
        } else {
            $_SESSION['error'] = 'Failed to delete review';
        }
        
        $this->redirect('patient.php?action=reviews');
    }
}
?>