<div class="page-header">
    <h1 class="page-title"><?php echo isset($review) ? 'Edit Your Review' : 'Write a Review'; ?></h1>
    <a href="<?php echo SITE_URL; ?>patient.php?action=reviews" class="btn-back">
        <i class="fas fa-arrow-left"></i> Back to Reviews
    </a>
</div>

<div class="review-container">
    <div class="review-card">
        <div class="card-header">
            <h3><i class="fas fa-star"></i> Rate Your Experience</h3>
            <p>Dr. <?php echo htmlspecialchars($appointment['doctor_name'] ?? $review['doctor_name'] ?? ''); ?></p>
            <?php if (isset($appointment)): ?>
                <small>Appointment on <?php echo date('F d, Y', strtotime($appointment['appointment_date'])); ?></small>
            <?php endif; ?>
        </div>
        <div class="card-body">
            <form method="POST" action="<?php echo SITE_URL; ?>patient.php?action=reviews&sub=<?php echo isset($review) ? 'update&id=' . $review['id'] : 'store'; ?>">
                
                <?php if (isset($appointment)): ?>
                    <input type="hidden" name="appointment_id" value="<?php echo $appointment['id']; ?>">
                    <input type="hidden" name="doctor_id" value="<?php echo $appointment['doctor_id']; ?>">
                <?php endif; ?>
                
                <div class="form-group rating-group">
                    <label>Your Rating *</label>
                    <div class="star-rating">
                        <input type="radio" id="star5" name="rating" value="5" <?php echo (isset($review) && $review['rating'] == 5) ? 'checked' : ''; ?>>
                        <label for="star5" title="5 stars"><i class="fas fa-star"></i></label>
                        
                        <input type="radio" id="star4" name="rating" value="4" <?php echo (isset($review) && $review['rating'] == 4) ? 'checked' : ''; ?>>
                        <label for="star4" title="4 stars"><i class="fas fa-star"></i></label>
                        
                        <input type="radio" id="star3" name="rating" value="3" <?php echo (isset($review) && $review['rating'] == 3) ? 'checked' : ''; ?>>
                        <label for="star3" title="3 stars"><i class="fas fa-star"></i></label>
                        
                        <input type="radio" id="star2" name="rating" value="2" <?php echo (isset($review) && $review['rating'] == 2) ? 'checked' : ''; ?>>
                        <label for="star2" title="2 stars"><i class="fas fa-star"></i></label>
                        
                        <input type="radio" id="star1" name="rating" value="1" <?php echo (isset($review) && $review['rating'] == 1) ? 'checked' : ''; ?>>
                        <label for="star1" title="1 star"><i class="fas fa-star"></i></label>
                    </div>
                </div>
                
                <div class="form-group">
                    <label>Your Review</label>
                    <textarea name="review_text" class="form-control" rows="6" 
                              placeholder="Share your experience with this doctor..."><?php 
                        echo htmlspecialchars($review['review_text'] ?? ''); 
                    ?></textarea>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn-submit">
                        <i class="fas fa-paper-plane"></i> <?php echo isset($review) ? 'Update Review' : 'Submit Review'; ?>
                    </button>
                    <a href="<?php echo SITE_URL; ?>patient.php?action=reviews" class="btn-cancel">Cancel</a>
                </div>
            </form>
        </div>
    </div>
    
    <div class="tips-card">
        <h4><i class="fas fa-lightbulb"></i> Review Guidelines</h4>
        <ul>
            <li>Be honest and respectful</li>
            <li>Share specific details about your experience</li>
            <li>Mention waiting time, doctor's communication, etc.</li>
            <li>Avoid sharing personal medical information</li>
            <li>Your feedback helps other patients</li>
        </ul>
    </div>
</div>

<style>
.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
}

.page-title {
    font-size: 28px;
    margin: 0;
    color: #333;
}

.btn-back {
    color: #667eea;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.review-container {
    display: grid;
    grid-template-columns: 1fr 350px;
    gap: 25px;
}

.review-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    overflow: hidden;
}

.card-header {
    padding: 20px 25px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.card-header h3 {
    margin: 0 0 5px 0;
    font-size: 18px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.card-header p {
    margin: 5px 0;
    font-size: 14px;
}

.card-header small {
    font-size: 12px;
    opacity: 0.9;
}

.card-body {
    padding: 25px;
}

.rating-group {
    text-align: center;
}

.star-rating {
    display: flex;
    flex-direction: row-reverse;
    justify-content: center;
    gap: 10px;
    margin: 15px 0;
}

.star-rating input {
    display: none;
}

.star-rating label {
    font-size: 35px;
    color: #ddd;
    cursor: pointer;
    transition: color 0.2s;
}

.star-rating label:hover,
.star-rating label:hover ~ label,
.star-rating input:checked ~ label {
    color: #ffc107;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    margin-bottom: 10px;
    font-weight: 600;
    color: #333;
}

.form-control {
    width: 100%;
    padding: 12px;
    border: 1px solid #ddd;
    border-radius: 8px;
    font-size: 14px;
    font-family: inherit;
    resize: vertical;
}

.form-control:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.form-actions {
    display: flex;
    gap: 15px;
    margin-top: 25px;
}

.btn-submit {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 10px 24px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.btn-submit:hover {
    transform: translateY(-1px);
    box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
}

.btn-cancel {
    background: #6c757d;
    color: white;
    padding: 10px 20px;
    border-radius: 8px;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.tips-card {
    background: white;
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    height: fit-content;
}

.tips-card h4 {
    margin: 0 0 15px 0;
    color: #667eea;
    display: flex;
    align-items: center;
    gap: 10px;
}

.tips-card ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.tips-card li {
    padding: 8px 0;
    color: #555;
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 13px;
    border-bottom: 1px solid #f0f0f0;
}

.tips-card li:last-child {
    border-bottom: none;
}

.tips-card li i {
    width: 20px;
    color: #667eea;
}

@media (max-width: 1024px) {
    .review-container {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 768px) {
    .card-header {
        padding: 15px 20px;
    }
    
    .card-body {
        padding: 20px;
    }
    
    .star-rating label {
        font-size: 28px;
    }
    
    .form-actions {
        flex-direction: column;
    }
    
    .btn-submit, .btn-cancel {
        justify-content: center;
    }
}
</style>