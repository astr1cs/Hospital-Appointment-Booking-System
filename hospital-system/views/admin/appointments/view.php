<div class="page-header">
    <h1 class="page-title">Appointment Details</h1>
    <a href="<?php echo SITE_URL; ?>admin.php?action=appointments" class="btn-secondary">
        <i class="fas fa-arrow-left"></i> Back to List
    </a>
</div>

<div class="appointment-details">
    <div class="detail-card">
        <div class="card-header">
            <h3><i class="fas fa-calendar-alt"></i> Appointment Information</h3>
        </div>
        <div class="card-body">
            <div class="detail-grid">
                <div class="detail-item">
                    <span class="detail-label">Appointment ID:</span>
                    <span class="detail-value">#<?php echo $appointment['id']; ?></span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Status:</span>
                    <span class="status-badge status-<?php echo $appointment['status']; ?>">
                        <?php echo ucfirst(str_replace('_', ' ', $appointment['status'])); ?>
                    </span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Date:</span>
                    <span class="detail-value"><?php echo date('F d, Y', strtotime($appointment['appointment_date'])); ?></span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Time:</span>
                    <span class="detail-value"><?php echo date('h:i A', strtotime($appointment['appointment_time'])); ?></span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Booked By:</span>
                    <span class="detail-value"><?php echo ucfirst($appointment['booked_by']); ?></span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Created At:</span>
                    <span class="detail-value"><?php echo date('M d, Y h:i A', strtotime($appointment['created_at'])); ?></span>
                </div>
            </div>
        </div>
    </div>

    <div class="detail-card">
        <div class="card-header">
            <h3><i class="fas fa-user"></i> Patient Information</h3>
        </div>
        <div class="card-body">
            <div class="detail-grid">
                <div class="detail-item">
                    <span class="detail-label">Name:</span>
                    <span class="detail-value"><?php echo htmlspecialchars($appointment['patient_name']); ?></span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Email:</span>
                    <span class="detail-value"><?php echo htmlspecialchars($appointment['patient_email']); ?></span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Phone:</span>
                    <span class="detail-value"><?php echo htmlspecialchars($appointment['patient_phone']); ?></span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Action:</span>
                    <span class="detail-value">
                        <a href="<?php echo SITE_URL; ?>admin.php?action=patients&sub=show&id=<?php echo $appointment['patient_id']; ?>" 
                           class="btn-link">View Patient Profile →</a>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="detail-card">
        <div class="card-header">
            <h3><i class="fas fa-user-md"></i> Doctor Information</h3>
        </div>
        <div class="card-body">
            <div class="detail-grid">
                <div class="detail-item">
                    <span class="detail-label">Name:</span>
                    <span class="detail-value"><?php echo htmlspecialchars($appointment['doctor_name']); ?></span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Email:</span>
                    <span class="detail-value"><?php echo htmlspecialchars($appointment['doctor_email']); ?></span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Specialization:</span>
                    <span class="detail-value"><?php echo htmlspecialchars($appointment['specialization_name']); ?></span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Action:</span>
                    <span class="detail-value">
                        <a href="<?php echo SITE_URL; ?>admin.php?action=doctors&sub=edit&id=<?php echo $appointment['doctor_id']; ?>" 
                           class="btn-link">View Doctor Profile →</a>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <?php if ($appointment['reason']): ?>
    <div class="detail-card">
        <div class="card-header">
            <h3><i class="fas fa-stethoscope"></i> Reason for Visit</h3>
        </div>
        <div class="card-body">
            <p><?php echo nl2br(htmlspecialchars($appointment['reason'])); ?></p>
        </div>
    </div>
    <?php endif; ?>
</div>

<style>
.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
}

.page-title {
    font-size: 24px;
    margin: 0;
    color: #333;
}

.btn-secondary {
    background: #6c757d;
    color: white;
    padding: 8px 16px;
    border-radius: 6px;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.appointment-details {
    display: flex;
    flex-direction: column;
    gap: 25px;
}

.detail-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    overflow: hidden;
}

.card-header {
    padding: 15px 20px;
    border-bottom: 1px solid #eee;
    background: #f8f9fa;
}

.card-header h3 {
    margin: 0;
    font-size: 16px;
    color: #333;
}

.card-body {
    padding: 20px;
}

.detail-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 15px;
}

.detail-item {
    display: flex;
    padding: 5px 0;
}

.detail-label {
    width: 120px;
    font-weight: 600;
    color: #555;
    font-size: 13px;
}

.detail-value {
    flex: 1;
    color: #333;
    font-size: 13px;
}

.status-badge {
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 500;
    display: inline-block;
}

.status-pending {
    background: #fff3cd;
    color: #856404;
}

.status-confirmed {
    background: #d4edda;
    color: #155724;
}

.status-checked_in {
    background: #cce5ff;
    color: #004085;
}

.status-completed {
    background: #d1ecf1;
    color: #0c5460;
}

.status-cancelled {
    background: #f8d7da;
    color: #721c24;
}

.btn-link {
    color: #667eea;
    text-decoration: none;
}

.btn-link:hover {
    text-decoration: underline;
}

@media (max-width: 768px) {
    .detail-grid {
        grid-template-columns: 1fr;
        gap: 10px;
    }
    
    .detail-item {
        flex-direction: column;
    }
    
    .detail-label {
        width: 100%;
        margin-bottom: 5px;
    }
}
</style>