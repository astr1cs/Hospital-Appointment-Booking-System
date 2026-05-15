<div class="page-header">
    <h1 class="page-title">Appointment Details</h1>
    <a href="<?php echo SITE_URL; ?>patient.php?action=appointments" class="btn-back">
        <i class="fas fa-arrow-left"></i> Back to Appointments
    </a>
</div>

<div class="appointment-detail-container">
    <!-- Appointment Status Card -->
    <div class="detail-card status-card status-<?php echo $appointment['status']; ?>">
        <div class="status-icon">
            <?php if ($appointment['status'] == 'pending'): ?>
                <i class="fas fa-clock"></i>
            <?php elseif ($appointment['status'] == 'confirmed'): ?>
                <i class="fas fa-check-circle"></i>
            <?php elseif ($appointment['status'] == 'completed'): ?>
                <i class="fas fa-check-double"></i>
            <?php elseif ($appointment['status'] == 'cancelled'): ?>
                <i class="fas fa-times-circle"></i>
            <?php else: ?>
                <i class="fas fa-calendar"></i>
            <?php endif; ?>
        </div>
        <div class="status-info">
            <h3><?php echo ucfirst($appointment['status']); ?></h3>
            <p>Appointment #<?php echo $appointment['id']; ?></p>
        </div>
    </div>

    <!-- Doctor Information -->
    <div class="detail-card">
        <h3><i class="fas fa-user-md"></i> Doctor Information</h3>
        <div class="info-row">
            <span class="label">Doctor Name:</span>
            <span class="value">Dr. <?php echo htmlspecialchars($appointment['doctor_name']); ?></span>
        </div>
        <div class="info-row">
            <span class="label">Specialization:</span>
            <span class="value"><?php echo htmlspecialchars($appointment['specialization_name']); ?></span>
        </div>
        <div class="info-row">
            <span class="label">Email:</span>
            <span class="value"><?php echo htmlspecialchars($appointment['doctor_email']); ?></span>
        </div>
    </div>

    <!-- Appointment Information -->
    <div class="detail-card">
        <h3><i class="fas fa-calendar-alt"></i> Appointment Information</h3>
        <div class="info-row">
            <span class="label">Date:</span>
            <span class="value"><?php echo date('l, F d, Y', strtotime($appointment['appointment_date'])); ?></span>
        </div>
        <div class="info-row">
            <span class="label">Time:</span>
            <span class="value"><?php echo date('h:i A', strtotime($appointment['appointment_time'])); ?></span>
        </div>
        <div class="info-row">
            <span class="label">Reason for Visit:</span>
            <span class="value"><?php echo nl2br(htmlspecialchars($appointment['reason'])); ?></span>
        </div>
    </div>

    <!-- Consultation Notes (only if appointment is completed) -->
    <?php if ($appointment['status'] == 'completed' && ($appointment['symptoms'] || $appointment['diagnosis'] || $appointment['prescription'])): ?>
    <div class="detail-card consultation-notes">
        <h3><i class="fas fa-notes-medical"></i> Consultation Notes</h3>
        
        <?php if ($appointment['symptoms']): ?>
        <div class="info-row">
            <span class="label">Symptoms:</span>
            <span class="value"><?php echo nl2br(htmlspecialchars($appointment['symptoms'])); ?></span>
        </div>
        <?php endif; ?>
        
        <?php if ($appointment['diagnosis']): ?>
        <div class="info-row">
            <span class="label">Diagnosis:</span>
            <span class="value"><?php echo nl2br(htmlspecialchars($appointment['diagnosis'])); ?></span>
        </div>
        <?php endif; ?>
        
        <?php if ($appointment['prescription']): ?>
        <div class="info-row">
            <span class="label">Prescription:</span>
            <span class="value prescription"><?php echo nl2br(htmlspecialchars($appointment['prescription'])); ?></span>
        </div>
        <?php endif; ?>
        
        <?php if ($appointment['follow_up_date']): ?>
        <div class="info-row">
            <span class="label">Follow-up Date:</span>
            <span class="value"><?php echo date('F d, Y', strtotime($appointment['follow_up_date'])); ?></span>
        </div>
        <?php endif; ?>
    </div>
    <?php elseif ($appointment['status'] == 'completed'): ?>
    <div class="detail-card">
        <h3><i class="fas fa-notes-medical"></i> Consultation Notes</h3>
        <p class="text-muted">No consultation notes available yet. The doctor will add notes after your visit.</p>
    </div>
    <?php endif; ?>

    <!-- Actions -->
    <div class="detail-card actions-card">
        <div class="action-buttons">
            <?php if ($appointment['status'] == 'pending' || $appointment['status'] == 'confirmed'): ?>
            <a href="<?php echo SITE_URL; ?>patient.php?action=appointments&sub=cancel&id=<?php echo $appointment['id']; ?>" 
               class="btn-cancel-appointment" 
               onclick="return confirm('Are you sure you want to cancel this appointment?')">
                <i class="fas fa-times"></i> Cancel Appointment
            </a>
            <?php endif; ?>
            
            <?php if ($appointment['status'] == 'completed'): ?>
            <a href="<?php echo SITE_URL; ?>patient.php?action=reviews&sub=create&appointment_id=<?php echo $appointment['id']; ?>" 
               class="btn-review">
                <i class="fas fa-star"></i> Write a Review
            </a>
            <?php endif; ?>
            
            <a href="<?php echo SITE_URL; ?>patient.php?action=doctors&sub=show&id=<?php echo $appointment['doctor_id']; ?>" 
               class="btn-profile">
                <i class="fas fa-user-md"></i> View Doctor Profile
            </a>
        </div>
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

.appointment-detail-container {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.detail-card {
    background: white;
    border-radius: 12px;
    padding: 25px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.detail-card h3 {
    margin: 0 0 20px 0;
    padding-bottom: 10px;
    border-bottom: 2px solid #f0f0f0;
    color: #333;
    display: flex;
    align-items: center;
    gap: 10px;
}

.detail-card h3 i {
    color: #667eea;
}

.info-row {
    display: flex;
    margin-bottom: 15px;
}

.info-row .label {
    width: 150px;
    font-weight: 600;
    color: #555;
}

.info-row .value {
    flex: 1;
    color: #333;
}

.info-row .value.prescription {
    background: #e8eaf6;
    padding: 10px;
    border-radius: 8px;
    font-family: monospace;
}

.status-card {
    display: flex;
    align-items: center;
    gap: 20px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.status-card.status-pending {
    background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%);
}

.status-card.status-confirmed {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
}

.status-card.status-completed {
    background: linear-gradient(135deg, #17a2b8 0%, #0dcaf0 100%);
}

.status-card.status-cancelled {
    background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
}

.status-icon i {
    font-size: 48px;
}

.status-info h3 {
    margin: 0 0 5px 0;
    font-size: 24px;
}

.status-info p {
    margin: 0;
    opacity: 0.9;
}

.consultation-notes {
    background: #f8f9fa;
    border-left: 4px solid #28a745;
}

.actions-card {
    background: #f8f9fa;
}

.action-buttons {
    display: flex;
    gap: 15px;
    flex-wrap: wrap;
}

.btn-cancel-appointment, .btn-review, .btn-profile {
    padding: 10px 20px;
    border-radius: 8px;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.btn-cancel-appointment {
    background: #dc3545;
    color: white;
}

.btn-review {
    background: #ffc107;
    color: #333;
}

.btn-profile {
    background: #6c757d;
    color: white;
}

.text-muted {
    text-align: center;
    padding: 20px;
    color: #999;
}

@media (max-width: 768px) {
    .info-row {
        flex-direction: column;
    }
    
    .info-row .label {
        width: 100%;
        margin-bottom: 5px;
    }
    
    .status-card {
        flex-direction: column;
        text-align: center;
    }
}
</style>