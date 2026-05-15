<div class="page-header">
    <a href="<?php echo SITE_URL; ?>patient.php?action=doctors" class="btn-back">
        <i class="fas fa-arrow-left"></i> Back to Doctors
    </a>
</div>

<div class="doctor-profile">
    <div class="doctor-profile-header">
        <div class="doctor-avatar">
            <?php if ($doctor['profile_pic'] && $doctor['profile_pic'] != 'default.jpg'): ?>
                <img src="<?php echo SITE_URL; ?>assets/uploads/<?php echo $doctor['profile_pic']; ?>" alt="Doctor">
            <?php else: ?>
                <i class="fas fa-user-md"></i>
            <?php endif; ?>
        </div>
        <div class="doctor-header-info">
            <h1>Dr. <?php echo htmlspecialchars($doctor['name']); ?></h1>
            <div class="doctor-specialty-badge">
                <i class="fas fa-stethoscope"></i> <?php echo htmlspecialchars($doctor['specialization_name']); ?>
            </div>
            <div class="doctor-rating-large">
                <div class="stars">
                    <?php 
                    $rating = round($doctor['avg_rating'], 1);
                    $fullStars = floor($rating);
                    $halfStar = ($rating - $fullStars) >= 0.5;
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
                <span class="rating-value"><?php echo number_format($doctor['avg_rating'], 1); ?></span>
                <span class="review-count">(<?php echo $doctor['total_reviews']; ?> patient reviews)</span>
            </div>
        </div>
    </div>

    <div class="doctor-profile-grid">
        <!-- Left Column - Info -->
        <div class="profile-section">
            <div class="info-card">
                <h3><i class="fas fa-info-circle"></i> About Doctor</h3>
                <div class="info-row">
                    <span class="info-label"><i class="fas fa-briefcase"></i> Experience:</span>
                    <span class="info-value"><?php echo $doctor['experience_years']; ?>+ years</span>
                </div>
                <div class="info-row">
                    <span class="info-label"><i class="fas fa-id-card"></i> License:</span>
                    <span class="info-value"><?php echo htmlspecialchars($doctor['license_number']); ?></span>
                </div>
                <div class="info-row">
                    <span class="info-label"><i class="fas fa-dollar-sign"></i> Consultation Fee:</span>
                    <span class="info-value fee">$<?php echo number_format($doctor['consultation_fee'], 2); ?></span>
                </div>
                <div class="info-row bio">
                    <span class="info-label"><i class="fas fa-file-alt"></i> Bio:</span>
                    <span class="info-value"><?php echo nl2br(htmlspecialchars($doctor['bio'] ?? 'No bio available.')); ?></span>
                </div>
            </div>

            <div class="info-card">
                <h3><i class="fas fa-calendar-week"></i> Available Days</h3>
                <?php if ($availability->num_rows > 0): ?>
                    <div class="availability-list">
                        <?php while($avail = $availability->fetch_assoc()): ?>
                        <div class="availability-item">
                            <span class="day"><?php echo $avail['day_of_week']; ?></span>
                            <span class="time">
                                <?php echo date('h:i A', strtotime($avail['start_time'])); ?> - 
                                <?php echo date('h:i A', strtotime($avail['end_time'])); ?>
                            </span>
                            <span class="duration">(<?php echo $avail['slot_duration_minutes']; ?> min slots)</span>
                        </div>
                        <?php endwhile; ?>
                    </div>
                <?php else: ?>
                    <p class="text-muted">Schedule not available</p>
                <?php endif; ?>
            </div>

            <div class="info-card">
                <a href="<?php echo SITE_URL; ?>patient.php?action=appointments&sub=book&doctor_id=<?php echo $doctor['user_id']; ?>" 
                   class="btn-book-appointment">
                    <i class="fas fa-calendar-plus"></i> Book Appointment
                </a>
            </div>
        </div>

        <!-- Right Column - Reviews -->
        <div class="profile-section">
            <div class="info-card">
                <h3><i class="fas fa-star"></i> Patient Reviews</h3>
                <?php if ($reviews->num_rows > 0): ?>
                    <div class="reviews-list">
                        <?php while($review = $reviews->fetch_assoc()): ?>
                        <div class="review-item">
                            <div class="review-header">
                                <span class="reviewer-name">
                                    <i class="fas fa-user-circle"></i> <?php echo htmlspecialchars($review['patient_name']); ?>
                                </span>
                                <div class="review-stars">
                                    <?php for($i = 1; $i <= 5; $i++): ?>
                                        <?php if($i <= $review['rating']): ?>
                                            <i class="fas fa-star"></i>
                                        <?php else: ?>
                                            <i class="far fa-star"></i>
                                        <?php endif; ?>
                                    <?php endfor; ?>
                                </div>
                            </div>
                            <div class="review-text">
                                <?php echo nl2br(htmlspecialchars($review['review_text'])); ?>
                            </div>
                            <div class="review-date">
                                <small><?php echo date('M d, Y', strtotime($review['created_at'])); ?></small>
                            </div>
                        </div>
                        <?php endwhile; ?>
                    </div>
                <?php else: ?>
                    <p class="text-muted">No reviews yet. Be the first to review!</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<style>
.page-header {
    margin-bottom: 20px;
}

.btn-back {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    color: #667eea;
    text-decoration: none;
}

.doctor-profile-header {
    background: white;
    border-radius: 12px;
    padding: 30px;
    display: flex;
    gap: 30px;
    margin-bottom: 30px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.doctor-avatar {
    width: 150px;
    height: 150px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.doctor-avatar i {
    font-size: 80px;
    color: white;
}

.doctor-avatar img {
    width: 100%;
    height: 100%;
    border-radius: 50%;
    object-fit: cover;
}

.doctor-header-info h1 {
    margin: 0 0 10px 0;
    font-size: 32px;
    color: #333;
}

.doctor-specialty-badge {
    display: inline-block;
    background: #e8eaf6;
    color: #3949ab;
    padding: 5px 15px;
    border-radius: 20px;
    font-size: 14px;
    margin-bottom: 15px;
}

.doctor-rating-large {
    display: flex;
    align-items: center;
    gap: 10px;
}

.doctor-rating-large .stars {
    color: #ffc107;
    font-size: 16px;
}

.rating-value {
    font-weight: bold;
    color: #333;
}

.review-count {
    color: #666;
    font-size: 13px;
}

.doctor-profile-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 30px;
}

.info-card {
    background: white;
    border-radius: 12px;
    padding: 25px;
    margin-bottom: 30px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.info-card h3 {
    margin: 0 0 20px 0;
    padding-bottom: 10px;
    border-bottom: 2px solid #f0f0f0;
    color: #333;
}

.info-row {
    display: flex;
    margin-bottom: 15px;
}

.info-label {
    width: 120px;
    font-weight: 600;
    color: #555;
}

.info-value {
    flex: 1;
    color: #333;
}

.info-value.fee {
    font-size: 20px;
    font-weight: bold;
    color: #28a745;
}

.info-row.bio {
    flex-direction: column;
}

.info-row.bio .info-label {
    margin-bottom: 10px;
}

.availability-list {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.availability-item {
    display: flex;
    justify-content: space-between;
    padding: 10px;
    background: #f8f9fa;
    border-radius: 8px;
}

.availability-item .day {
    font-weight: 600;
    color: #667eea;
}

.availability-item .time {
    color: #333;
}

.availability-item .duration {
    color: #666;
    font-size: 12px;
}

.btn-book-appointment {
    display: block;
    text-align: center;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 15px;
    border-radius: 10px;
    text-decoration: none;
    font-weight: 600;
}

.btn-book-appointment:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
}

.reviews-list {
    max-height: 500px;
    overflow-y: auto;
}

.review-item {
    padding: 15px 0;
    border-bottom: 1px solid #eee;
}

.review-item:last-child {
    border-bottom: none;
}

.review-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 10px;
}

.reviewer-name {
    font-weight: 600;
    color: #333;
}

.review-stars {
    color: #ffc107;
    font-size: 12px;
}

.review-text {
    color: #555;
    line-height: 1.5;
    margin-bottom: 8px;
}

.review-date small {
    color: #999;
    font-size: 11px;
}

.text-muted {
    text-align: center;
    padding: 20px;
    color: #999;
}

@media (max-width: 768px) {
    .doctor-profile-header {
        flex-direction: column;
        text-align: center;
    }
    
    .doctor-profile-grid {
        grid-template-columns: 1fr;
    }
    
    .info-row {
        flex-direction: column;
    }
    
    .info-label {
        width: 100%;
        margin-bottom: 5px;
    }
    
    .availability-item {
        flex-direction: column;
        text-align: center;
        gap: 5px;
    }
}
</style>