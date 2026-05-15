
<div class="page-header">
    <h1 class="page-title">Patient Reviews</h1>
</div>

<!-- Rating Summary -->
<div class="rating-summary">
    <div class="overall-rating">
        <div class="rating-number"><?php echo number_format($ratingStats['avg_rating'], 1); ?></div>
        <div class="rating-stars">
            <?php 
            $fullStars = floor($ratingStats['avg_rating']);
            $halfStar = ($ratingStats['avg_rating'] - $fullStars) >= 0.5;
            for($i = 1; $i <= 5; $i++):
                if($i <= $fullStars):
            ?>
                <i class="fas fa-star"></i>
            <?php elseif($halfStar && $i == $fullStars + 1): ?>
                <i class="fas fa-star-half-alt"></i>
            <?php else: ?>
                <i class="far fa-star"></i>
            <?php endif; ?>
            <?php endfor; ?>
        </div>
        <div class="rating-count">Based on <?php echo $ratingStats['total_reviews']; ?> reviews</div>
    </div>
    
    <div class="rating-breakdown">
        <div class="rating-bar-item">
            <span class="rating-label">5 Star</span>
            <div class="progress-bar">
                <div class="progress" style="width: <?php echo $ratingStats['total_reviews'] > 0 ? ($ratingStats['rating_5'] / $ratingStats['total_reviews']) * 100 : 0; ?>%"></div>
            </div>
            <span class="rating-count"><?php echo $ratingStats['rating_5']; ?></span>
        </div>
        <div class="rating-bar-item">
            <span class="rating-label">4 Star</span>
            <div class="progress-bar">
                <div class="progress" style="width: <?php echo $ratingStats['total_reviews'] > 0 ? ($ratingStats['rating_4'] / $ratingStats['total_reviews']) * 100 : 0; ?>%"></div>
            </div>
            <span class="rating-count"><?php echo $ratingStats['rating_4']; ?></span>
        </div>
        <div class="rating-bar-item">
            <span class="rating-label">3 Star</span>
            <div class="progress-bar">
                <div class="progress" style="width: <?php echo $ratingStats['total_reviews'] > 0 ? ($ratingStats['rating_3'] / $ratingStats['total_reviews']) * 100 : 0; ?>%"></div>
            </div>
            <span class="rating-count"><?php echo $ratingStats['rating_3']; ?></span>
        </div>
        <div class="rating-bar-item">
            <span class="rating-label">2 Star</span>
            <div class="progress-bar">
                <div class="progress" style="width: <?php echo $ratingStats['total_reviews'] > 0 ? ($ratingStats['rating_2'] / $ratingStats['total_reviews']) * 100 : 0; ?>%"></div>
            </div>
            <span class="rating-count"><?php echo $ratingStats['rating_2']; ?></span>
        </div>
        <div class="rating-bar-item">
            <span class="rating-label">1 Star</span>
            <div class="progress-bar">
                <div class="progress" style="width: <?php echo $ratingStats['total_reviews'] > 0 ? ($ratingStats['rating_1'] / $ratingStats['total_reviews']) * 100 : 0; ?>%"></div>
            </div>
            <span class="rating-count"><?php echo $ratingStats['rating_1']; ?></span>
        </div>
    </div>
</div>

<!-- Reviews List -->
<div class="reviews-list">
    <?php if ($reviews->num_rows > 0): ?>
        <?php while($row = $reviews->fetch_assoc()): ?>
        <div class="review-card">
            <div class="review-header">
                <div class="reviewer-info">
                    <i class="fas fa-user-circle"></i>
                    <strong><?php echo htmlspecialchars($row['patient_name']); ?></strong>
                </div>
                <div class="review-rating">
                    <?php for($i = 1; $i <= 5; $i++): ?>
                        <?php if($i <= $row['rating']): ?>
                            <i class="fas fa-star"></i>
                        <?php else: ?>
                            <i class="far fa-star"></i>
                        <?php endif; ?>
                    <?php endfor; ?>
                </div>
            </div>
            <div class="review-body">
                <p><?php echo nl2br(htmlspecialchars($row['review_text'])); ?></p>
            </div>
            <div class="review-footer">
                <span class="review-date">
                    <i class="fas fa-calendar"></i> 
                    Appointment on <?php echo date('M d, Y', strtotime($row['appointment_date'])); ?>
                </span>
                <span class="review-date">
                    <i class="fas fa-clock"></i> 
                    Reviewed on <?php echo date('M d, Y', strtotime($row['created_at'])); ?>
                </span>
            </div>
        </div>
        <?php endwhile; ?>
    <?php else: ?>
        <div class="empty-state">
            <i class="fas fa-star"></i>
            <h3>No Reviews Yet</h3>
            <p>Patients haven't written any reviews yet.</p>
        </div>
    <?php endif; ?>
</div>

<style>
.page-header {
    margin-bottom: 25px;
}
.page-title {
    font-size: 28px;
    margin: 0;
    color: #333;
}
.rating-summary {
    background: white;
    border-radius: 12px;
    padding: 25px;
    display: flex;
    gap: 40px;
    margin-bottom: 30px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}
.overall-rating {
    text-align: center;
    padding-right: 40px;
    border-right: 1px solid #eee;
}
.rating-number {
    font-size: 48px;
    font-weight: bold;
    color: #ffc107;
}
.rating-stars {
    font-size: 18px;
    color: #ffc107;
    margin: 10px 0;
}
.rating-count {
    color: #666;
    font-size: 13px;
}
.rating-breakdown {
    flex: 1;
}
.rating-bar-item {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 8px;
}
.rating-label {
    width: 60px;
    font-size: 13px;
    color: #555;
}
.progress-bar {
    flex: 1;
    height: 8px;
    background: #e9ecef;
    border-radius: 4px;
    overflow: hidden;
}
.progress {
    height: 100%;
    background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%);
    border-radius: 4px;
}
.rating-count {
    width: 30px;
    font-size: 12px;
    text-align: right;
}
.reviews-list {
    display: flex;
    flex-direction: column;
    gap: 20px;
}
.review-card {
    background: white;
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}
.review-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
    padding-bottom: 10px;
    border-bottom: 1px solid #eee;
}
.reviewer-info {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 16px;
}
.reviewer-info i {
    font-size: 32px;
    color: #667eea;
}
.review-rating {
    color: #ffc107;
    font-size: 14px;
}
.review-body {
    margin-bottom: 15px;
    line-height: 1.6;
    color: #555;
}
.review-footer {
    display: flex;
    gap: 20px;
    font-size: 12px;
    color: #999;
}
.empty-state {
    text-align: center;
    padding: 60px;
    background: white;
    border-radius: 12px;
}
.empty-state i {
    font-size: 60px;
    color: #ccc;
    margin-bottom: 20px;
}
@media (max-width: 768px) {
    .rating-summary {
        flex-direction: column;
    }
    .overall-rating {
        border-right: none;
        border-bottom: 1px solid #eee;
        padding-bottom: 20px;
    }
    .review-header {
        flex-direction: column;
        gap: 10px;
    }
}
</style>