<div class="page-header">
    <h1 class="page-title">My Appointments</h1>
    <a href="<?php echo SITE_URL; ?>patient.php?action=doctors" class="btn-primary">
        <i class="fas fa-calendar-plus"></i> Book New Appointment
    </a>
</div>

<?php if (isset($success) && $success): ?>
    <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
<?php endif; ?>

<?php if (isset($error) && $error): ?>
    <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>

<!-- Upcoming Appointments -->
<div class="appointment-section">
    <h2><i class="fas fa-calendar-alt"></i> Upcoming Appointments</h2>
    
    <?php if ($upcoming->num_rows > 0): ?>
        <div class="appointments-grid">
            <?php while($row = $upcoming->fetch_assoc()): ?>
            <div class="appointment-card status-<?php echo $row['status']; ?>">
                <div class="appointment-header">
                    <div class="doctor-info">
                        <i class="fas fa-user-md"></i>
                        <strong>Dr. <?php echo htmlspecialchars($row['doctor_name']); ?></strong>
                        <span class="specialization">(<?php echo htmlspecialchars($row['specialization_name']); ?>)</span>
                    </div>
                    <div class="status-badge status-<?php echo $row['status']; ?>">
                        <?php echo ucfirst($row['status']); ?>
                    </div>
                </div>
                <div class="appointment-details">
                    <div class="detail">
                        <i class="fas fa-calendar-day"></i>
                        <span><?php echo date('l, F d, Y', strtotime($row['appointment_date'])); ?></span>
                    </div>
                    <div class="detail">
                        <i class="fas fa-clock"></i>
                        <span><?php echo date('h:i A', strtotime($row['appointment_time'])); ?></span>
                    </div>
                    <?php if ($row['reason']): ?>
                    <div class="detail">
                        <i class="fas fa-notes-medical"></i>
                        <span><?php echo htmlspecialchars(substr($row['reason'], 0, 100)); ?></span>
                    </div>
                    <?php endif; ?>
                </div>
                <div class="appointment-actions">
                    <a href="<?php echo SITE_URL; ?>patient.php?action=appointments&sub=show&id=<?php echo $row['id']; ?>" 
                       class="btn-view">
                        <i class="fas fa-eye"></i> View Details
                    </a>
                    <?php if ($row['status'] == 'pending' || $row['status'] == 'confirmed'): ?>
                    <a href="<?php echo SITE_URL; ?>patient.php?action=appointments&sub=cancel&id=<?php echo $row['id']; ?>" 
                       class="btn-cancel" 
                       onclick="return confirm('Are you sure you want to cancel this appointment?')">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                    <?php endif; ?>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <div class="empty-state">
            <i class="fas fa-calendar-check"></i>
            <h3>No Upcoming Appointments</h3>
            <p>You don't have any upcoming appointments. Book your first appointment now!</p>
            <a href="<?php echo SITE_URL; ?>patient.php?action=doctors" class="btn-primary">Find a Doctor</a>
        </div>
    <?php endif; ?>
</div>

<!-- Past Appointments -->
<div class="appointment-section">
    <h2><i class="fas fa-history"></i> Past Appointments</h2>
    
    <?php if ($past->num_rows > 0): ?>
        <div class="appointments-grid">
            <?php while($row = $past->fetch_assoc()): ?>
            <div class="appointment-card past">
                <div class="appointment-header">
                    <div class="doctor-info">
                        <i class="fas fa-user-md"></i>
                        <strong>Dr. <?php echo htmlspecialchars($row['doctor_name']); ?></strong>
                        <span class="specialization">(<?php echo htmlspecialchars($row['specialization_name']); ?>)</span>
                    </div>
                    <div class="status-badge status-<?php echo $row['status']; ?>">
                        <?php echo ucfirst($row['status']); ?>
                    </div>
                </div>
                <div class="appointment-details">
                    <div class="detail">
                        <i class="fas fa-calendar-day"></i>
                        <span><?php echo date('l, F d, Y', strtotime($row['appointment_date'])); ?></span>
                    </div>
                    <div class="detail">
                        <i class="fas fa-clock"></i>
                        <span><?php echo date('h:i A', strtotime($row['appointment_time'])); ?></span>
                    </div>
                </div>
                <div class="appointment-actions">
                    <a href="<?php echo SITE_URL; ?>patient.php?action=appointments&sub=show&id=<?php echo $row['id']; ?>" 
                       class="btn-view">
                        <i class="fas fa-eye"></i> View Details
                    </a>
                    <?php if ($row['status'] == 'completed'): ?>
                    <a href="<?php echo SITE_URL; ?>patient.php?action=reviews&sub=create&appointment_id=<?php echo $row['id']; ?>" 
                       class="btn-review">
                        <i class="fas fa-star"></i> Write a Review
                    </a>
                    <?php endif; ?>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <div class="empty-state">
            <i class="fas fa-history"></i>
            <h3>No Past Appointments</h3>
            <p>Your appointment history will appear here.</p>
        </div>
    <?php endif; ?>
</div>

<style>
.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
}

.page-title {
    font-size: 28px;
    margin: 0;
    color: #333;
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

.appointment-section {
    margin-bottom: 40px;
}

.appointment-section h2 {
    font-size: 20px;
    margin-bottom: 20px;
    color: #333;
    padding-bottom: 10px;
    border-bottom: 2px solid #f0f0f0;
}

.appointments-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(400px, 1fr));
    gap: 20px;
}

.appointment-card {
    background: white;
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    border-left: 4px solid #667eea;
}

.appointment-card.past {
    border-left-color: #6c757d;
    opacity: 0.8;
}

.appointment-card.status-pending {
    border-left-color: #ffc107;
}

.appointment-card.status-confirmed {
    border-left-color: #28a745;
}

.appointment-card.status-completed {
    border-left-color: #17a2b8;
}

.appointment-card.status-cancelled {
    border-left-color: #dc3545;
}

.appointment-header {
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
    flex-wrap: wrap;
}

.doctor-info i {
    color: #667eea;
    font-size: 18px;
}

.specialization {
    color: #666;
    font-size: 12px;
}

.status-badge {
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 500;
}

.status-pending {
    background: #fff3cd;
    color: #856404;
}

.status-confirmed {
    background: #d4edda;
    color: #155724;
}

.status-completed {
    background: #cce5ff;
    color: #004085;
}

.status-cancelled {
    background: #f8d7da;
    color: #721c24;
}

.appointment-details {
    margin-bottom: 15px;
}

.detail {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 8px;
    font-size: 13px;
    color: #555;
}

.detail i {
    width: 20px;
    color: #667eea;
}

.appointment-actions {
    display: flex;
    gap: 10px;
    margin-top: 15px;
    padding-top: 10px;
    border-top: 1px solid #eee;
}

.btn-view, .btn-cancel, .btn-review {
    padding: 6px 12px;
    border-radius: 6px;
    text-decoration: none;
    font-size: 12px;
    display: inline-flex;
    align-items: center;
    gap: 5px;
}

.btn-view {
    background: #e8eaf6;
    color: #3949ab;
}

.btn-cancel {
    background: #fee2e2;
    color: #dc3545;
}

.btn-review {
    background: #fff3cd;
    color: #856404;
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
    .appointments-grid {
        grid-template-columns: 1fr;
    }
    
    .appointment-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
    }
}
</style>