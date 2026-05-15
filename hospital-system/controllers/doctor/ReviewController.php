
<?php
require_once __DIR__ . '/BaseController.php';

class ReviewController extends DoctorBaseController {
    
    // View all reviews for this doctor
    public function index() {
        $userId = $this->getUserId();
        
        // Get average rating
        $sql = "SELECT 
                    COALESCE(AVG(rating), 0) as avg_rating,
                    COUNT(*) as total_reviews,
                    SUM(CASE WHEN rating = 5 THEN 1 ELSE 0 END) as rating_5,
                    SUM(CASE WHEN rating = 4 THEN 1 ELSE 0 END) as rating_4,
                    SUM(CASE WHEN rating = 3 THEN 1 ELSE 0 END) as rating_3,
                    SUM(CASE WHEN rating = 2 THEN 1 ELSE 0 END) as rating_2,
                    SUM(CASE WHEN rating = 1 THEN 1 ELSE 0 END) as rating_1
                FROM doctor_reviews
                WHERE doctor_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $ratingStats = $stmt->get_result()->fetch_assoc();
        
        // Get all reviews
        $sql = "SELECT r.*, u.name as patient_name, a.appointment_date
                FROM doctor_reviews r
                JOIN users u ON r.patient_id = u.id
                JOIN appointments a ON r.appointment_id = a.id
                WHERE r.doctor_id = ?
                ORDER BY r.created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $reviews = $stmt->get_result();
        
        $data = [
            'title' => 'Patient Reviews',
            'ratingStats' => $ratingStats,
            'reviews' => $reviews
        ];
        
        $this->view('reviews/index', $data);
    }
}
?>