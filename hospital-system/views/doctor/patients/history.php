<div class="page-header">
    <h1 class="page-title">Patient Medical History</h1>
    <a href="<?php echo SITE_URL; ?>doctor.php?action=patients" class="btn-back">
        <i class="fas fa-arrow-left"></i> Back to Patients
    </a>
</div>

<div class="patient-profile">
    <div class="profile-header">
        <div class="patient-avatar-large">
            <i class="fas fa-user-circle"></i>
        </div>
        <div class="profile-info">
            <h2><?php echo htmlspecialchars($patient['name']); ?></h2>
            <div class="info-grid">
                <span><i class="fas fa-envelope"></i> <?php echo htmlspecialchars($patient['email']); ?></span>
                <span><i class="fas fa-phone"></i> <?php echo htmlspecialchars($patient['phone']); ?></span>
                <?php if ($patient['date_of_birth']): ?>
                <span><i class="fas fa-birthday-cake"></i> <?php echo date('M d, Y', strtotime($patient['date_of_birth'])); ?></span>
                <?php endif; ?>
                <?php if ($patient['blood_group']): ?>
                <span><i class="fas fa-tint"></i> Blood: <?php echo $patient['blood_group']; ?></span>
                <?php endif; ?>
                <?php if ($patient['gender']): ?>
                <span><i class="fas fa-venus-mars"></i> <?php echo ucfirst($patient['gender']); ?></span>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Medical History Notes -->
    <?php if ($patient['medical_history_notes']): ?>
    <div class="medical-history-section">
        <h3><i class="fas fa-notes-medical"></i> Medical History Notes</h3>
        <div class="medical-notes">
            <?php echo nl2br(htmlspecialchars($patient['medical_history_notes'])); ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- Emergency Contact -->
    <?php if ($patient['emergency_contact_name'] || $patient['emergency_contact_phone']): ?>
    <div class="emergency-section">
        <h3><i class="fas fa-phone-alt"></i> Emergency Contact</h3>
        <div class="emergency-info">
            <span><strong>Name:</strong> <?php echo htmlspecialchars($patient['emergency_contact_name']); ?></span>
            <span><strong>Phone:</strong> <?php echo htmlspecialchars($patient['emergency_contact_phone']); ?></span>
        </div>
    </div>
    <?php endif; ?>

    <!-- Appointment History -->
    <div class="appointments-section">
        <h3><i class="fas fa-calendar-alt"></i> Appointment History with You</h3>
        
        <?php if ($appointments->num_rows > 0): ?>
            <div class="appointments-timeline">
                <?php while($row = $appointments->fetch_assoc()): ?>
                <div class="timeline-item">
                    <div class="timeline-date">
                        <?php echo date('M d, Y', strtotime($row['appointment_date'])); ?>
                        <small><?php echo date('h:i A', strtotime($row['appointment_time'])); ?></small>
                    </div>
                    <div class="timeline-content">
                        <div class="appointment-status status-<?php echo $row['status']; ?>">
                            <?php echo ucfirst($row['status']); ?>
                        </div>
                        <?php if ($row['reason']): ?>
                        <p><strong>Reason:</strong> <?php echo htmlspecialchars($row['reason']); ?></p>
                        <?php endif; ?>
                        
                        <?php if ($row['has_notes']): ?>
                            <div class="consultation-notes">
                                <details>
                                    <summary><i class="fas fa-stethoscope"></i> View Consultation Notes</summary>
                                    <?php if ($row['symptoms']): ?>
                                    <p><strong>Symptoms:</strong> <?php echo nl2br(htmlspecialchars($row['symptoms'])); ?></p>
                                    <?php endif; ?>
                                    <?php if ($row['diagnosis']): ?>
                                    <p><strong>Diagnosis:</strong> <?php echo nl2br(htmlspecialchars($row['diagnosis'])); ?></p>
                                    <?php endif; ?>
                                    <?php if ($row['prescription']): ?>
                                    <p><strong>Prescription:</strong> <?php echo nl2br(htmlspecialchars($row['prescription'])); ?></p>
                                    <?php endif; ?>
                                    <?php if ($row['follow_up_date']): ?>
                                    <p><strong>Follow-up:</strong> <?php echo date('M d, Y', strtotime($row['follow_up_date'])); ?></p>
                                    <?php endif; ?>
                                </details>
                            </div>
                        <?php else: ?>
                            <div class="no-notes">
                                <small>No consultation notes recorded</small>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <div class="empty-state-small">
                <i class="fas fa-calendar"></i>
                <p>No previous appointments with this patient.</p>
            </div>
        <?php endif; ?>
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
.patient-profile {
    background: white;
    border-radius: 12px;
    padding: 25px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}
.profile-header {
    display: flex;
    gap: 25px;
    padding-bottom: 20px;
    border-bottom: 1px solid #eee;
    margin-bottom: 20px;
}
.patient-avatar-large i {
    font-size: 80px;
    color: #667eea;
}
.profile-info h2 {
    margin: 0 0 15px 0;
    color: #333;
}
.info-grid {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
}
.info-grid span {
    font-size: 13px;
    color: #555;
}
.info-grid i {
    width: 18px;
    color: #667eea;
    margin-right: 5px;
}
.medical-history-section, .emergency-section, .appointments-section {
    margin-bottom: 25px;
}
.medical-history-section h3, .emergency-section h3, .appointments-section h3 {
    margin: 0 0 15px 0;
    color: #333;
    font-size: 18px;
}
.medical-notes {
    background: #f8f9fa;
    padding: 15px;
    border-radius: 8px;
    line-height: 1.5;
}
.emergency-info {
    background: #fff3cd;
    padding: 15px;
    border-radius: 8px;
    display: flex;
    gap: 20px;
    flex-wrap: wrap;
}
.appointments-timeline {
    display: flex;
    flex-direction: column;
    gap: 15px;
}
.timeline-item {
    display: flex;
    gap: 20px;
    padding: 15px;
    background: #f8f9fa;
    border-radius: 8px;
}
.timeline-date {
    min-width: 100px;
    font-weight: 600;
    color: #667eea;
}
.timeline-date small {
    display: block;
    font-size: 11px;
    color: #666;
}
.timeline-content {
    flex: 1;
}
.appointment-status {
    display: inline-block;
    padding: 2px 8px;
    border-radius: 12px;
    font-size: 11px;
    margin-bottom: 8px;
}
.status-pending { background: #fff3cd; color: #856404; }
.status-confirmed { background: #d4edda; color: #155724; }
.status-completed { background: #cce5ff; color: #004085; }
.consultation-notes details {
    margin-top: 10px;
    padding: 10px;
    background: white;
    border-radius: 6px;
}
.consultation-notes summary {
    cursor: pointer;
    color: #667eea;
    font-weight: 500;
}
.consultation-notes p {
    margin: 8px 0 0 0;
    font-size: 13px;
}
.no-notes small {
    color: #999;
}
.empty-state-small {
    text-align: center;
    padding: 30px;
    color: #999;
}
@media (max-width: 768px) {
    .profile-header {
        flex-direction: column;
        text-align: center;
    }
    .timeline-item {
        flex-direction: column;
    }
    .timeline-date {
        text-align: center;
    }
}
</style>