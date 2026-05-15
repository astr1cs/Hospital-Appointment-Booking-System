<div class="page-header">
    <h1 class="page-title">My Reviews</h1>
</div>

<?php if (isset($success) && $success): ?>
    <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
<?php endif; ?>

<?php if (isset($error) && $error): ?>
    <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>

<?php if ($reviews->num_rows > 0): ?>
    <div class="reviews-list">
        <?php while($row = $reviews->fetch_assoc()): ?>
        <div class="review-card">
            <div class="review-header">
                <div class="doctor-info">
                    <i class="fas fa-user-md"></i>
                    <strong>Dr. <?php echo htmlspecialchars($row['doctor_name']); ?></strong>
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
                <div class="review-date">
                    <i class="fas fa-calendar"></i> 
                    Appointment on <?php echo date('M d, Y', strtotime($row['appointment_date'])); ?>
                </div>
                <div class="review-actions">
                    <a href="<?php echo SITE_URL; ?>patient.php?action=reviews&sub=edit&id=<?php echo $row['id']; ?>" 
                       class="btn-edit">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    <a href="<?php echo SITE_URL; ?>patient.php?action=reviews&sub=delete&id=<?php echo $row['id']; ?>" 
                       class="btn-delete" 
                       onclick="return confirm('Are you sure you want to delete this review?')">
                        <i class="fas fa-trash"></i> Delete
                    </a>
                </div>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
<?php else: ?>
    <div class="empty-state">
        <i class="fas fa-star"></i>
        <h3>No Reviews Yet</h3>
        <p>You haven't written any reviews yet. After your appointments, you can rate and review doctors.</p>
        <a href="<?php echo SITE_URL; ?>patient.php?action=appointments" class="btn-primary">
            <i class="fas fa-calendar"></i> View Your Appointments
        </a>
    </div>
<?php endif; ?>

<style>
.page-header {
    margin-bottom: 25px;
}

.page-title {
    font-size: 28px;
    margin: 0;
    color: #333;
}

.reviews-list {
    display: grid;
    grid-template-columns: 1fr;
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

.doctor-info {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 16px;
    color: #333;
}

.doctor-info i {
    color: #667eea;
    font-size: 20px;
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
    justify-content: space-between;
    align-items: center;
    padding-top: 10px;
    border-top: 1px solid #eee;
}

.review-date {
    font-size: 12px;
    color: #999;
}

.review-date i {
    margin-right: 5px;
}

.review-actions {
    display: flex;
    gap: 10px;
}

.btn-edit, .btn-delete {
    padding: 5px 12px;
    border-radius: 5px;
    text-decoration: none;
    font-size: 12px;
    display: inline-flex;
    align-items: center;
    gap: 5px;
}

.btn-edit {
    background: #e8eaf6;
    color: #3949ab;
}

.btn-edit:hover {
    background: #3949ab;
    color: white;
}

.btn-delete {
    background: #fee2e2;
    color: #dc3545;
}

.btn-delete:hover {
    background: #dc3545;
    color: white;
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

.empty-state h3 {
    margin-bottom: 10px;
    color: #333;
}

.empty-state p {
    color: #666;
    margin-bottom: 20px;
}

.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 10px 20px;
    border-radius: 8px;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.alert {
    padding: 12px 20px;
    border-radius: 8px;
    margin-bottom: 20px;
}

.alert-success {
    background: #d4edda;
    color: #155724;
    border-left: 4px solid #28a745;
}

.alert-danger {
    background: #f8d7da;
    color: #721c24;
    border-left: 4px solid #dc3545;
}

@media (max-width: 768px) {
    .review-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
    }
    
    .review-footer {
        flex-direction: column;
        gap: 10px;
        align-items: flex-start;
    }
}
</style>