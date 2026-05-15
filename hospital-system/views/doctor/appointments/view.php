<div class="page-header">
    <h1 class="page-title">Appointment Details</h1>
    <a href="<?php echo SITE_URL; ?>doctor.php?action=appointments&sub=today" class="btn-back">
        <i class="fas fa-arrow-left"></i> Back to Schedule
    </a>
</div>

<div class="appointment-detail-container">
    <!-- Patient Information Card -->
    <div class="detail-card">
        <div class="card-header">
            <h3><i class="fas fa-user"></i> Patient Information</h3>
        </div>
        <div class="card-body">
            <div class="info-row">
                <span class="label">Name:</span>
                <span class="value"><?php echo htmlspecialchars($appointment['patient_name']); ?></span>
            </div>
            <div class="info-row">
                <span class="label">Email:</span>
                <span class="value"><?php echo htmlspecialchars($appointment['patient_email']); ?></span>
            </div>
            <div class="info-row">
                <span class="label">Phone:</span>
                <span class="value"><?php echo htmlspecialchars($appointment['patient_phone']); ?></span>
            </div>
            <?php if ($appointment['date_of_birth']): ?>
            <div class="info-row">
                <span class="label">Date of Birth:</span>
                <span class="value"><?php echo date('M d, Y', strtotime($appointment['date_of_birth'])); ?></span>
            </div>
            <?php endif; ?>
            <?php if ($appointment['blood_group']): ?>
            <div class="info-row">
                <span class="label">Blood Group:</span>
                <span class="value"><?php echo $appointment['blood_group']; ?></span>
            </div>
            <?php endif; ?>
            <?php if ($appointment['gender']): ?>
            <div class="info-row">
                <span class="label">Gender:</span>
                <span class="value"><?php echo ucfirst($appointment['gender']); ?></span>
            </div>
            <?php endif; ?>
            <?php if ($appointment['address']): ?>
            <div class="info-row">
                <span class="label">Address:</span>
                <span class="value"><?php echo nl2br(htmlspecialchars($appointment['address'])); ?></span>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Emergency Contact Card -->
    <?php if ($appointment['emergency_contact_name'] || $appointment['emergency_contact_phone']): ?>
    <div class="detail-card">
        <div class="card-header">
            <h3><i class="fas fa-phone-alt"></i> Emergency Contact</h3>
        </div>
        <div class="card-body">
            <?php if ($appointment['emergency_contact_name']): ?>
            <div class="info-row">
                <span class="label">Name:</span>
                <span class="value"><?php echo htmlspecialchars($appointment['emergency_contact_name']); ?></span>
            </div>
            <?php endif; ?>
            <?php if ($appointment['emergency_contact_phone']): ?>
            <div class="info-row">
                <span class="label">Phone:</span>
                <span class="value"><?php echo htmlspecialchars($appointment['emergency_contact_phone']); ?></span>
            </div>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- Medical History Card -->
    <?php if ($appointment['medical_history_notes']): ?>
    <div class="detail-card">
        <div class="card-header">
            <h3><i class="fas fa-notes-medical"></i> Medical History</h3>
        </div>
        <div class="card-body">
            <div class="medical-history">
                <?php echo nl2br(htmlspecialchars($appointment['medical_history_notes'])); ?>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Appointment Information Card -->
    <div class="detail-card">
        <div class="card-header">
            <h3><i class="fas fa-calendar-alt"></i> Appointment Information</h3>
        </div>
        <div class="card-body">
            <div class="info-row">
                <span class="label">Date:</span>
                <span class="value"><?php echo date('l, F d, Y', strtotime($appointment['appointment_date'])); ?></span>
            </div>
            <div class="info-row">
                <span class="label">Time:</span>
                <span class="value"><?php echo date('h:i A', strtotime($appointment['appointment_time'])); ?></span>
            </div>
            <div class="info-row">
                <span class="label">Status:</span>
                <span class="value">
                    <span class="status-badge status-<?php echo $appointment['status']; ?>">
                        <?php echo ucfirst($appointment['status']); ?>
                    </span>
                </span>
            </div>
            <?php if ($appointment['reason']): ?>
            <div class="info-row">
                <span class="label">Reason:</span>
                <span class="value"><?php echo nl2br(htmlspecialchars($appointment['reason'])); ?></span>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Consultation Notes (if available) -->
<!-- Consultation Notes (if available) -->
<?php if (!empty($appointment['symptoms']) || !empty($appointment['diagnosis']) || !empty($appointment['prescription'])): ?>
<div class="detail-card consultation-card">
    <div class="card-header">
        <h3><i class="fas fa-stethoscope"></i> Consultation Notes</h3>
    </div>
    <div class="card-body">
        <?php if (!empty($appointment['symptoms'])): ?>
        <div class="info-row">
            <span class="label">Symptoms:</span>
            <span class="value"><?php echo nl2br(htmlspecialchars($appointment['symptoms'])); ?></span>
        </div>
        <?php endif; ?>
        <?php if (!empty($appointment['diagnosis'])): ?>
        <div class="info-row">
            <span class="label">Diagnosis:</span>
            <span class="value"><?php echo nl2br(htmlspecialchars($appointment['diagnosis'])); ?></span>
        </div>
        <?php endif; ?>
        <?php if (!empty($appointment['prescription'])): ?>
        <div class="info-row">
            <span class="label">Prescription:</span>
            <span class="value prescription"><?php echo nl2br(htmlspecialchars($appointment['prescription'])); ?></span>
        </div>
        <?php endif; ?>
        <?php if (!empty($appointment['follow_up_date'])): ?>
        <div class="info-row">
            <span class="label">Follow-up:</span>
            <span class="value"><?php echo date('F d, Y', strtotime($appointment['follow_up_date'])); ?></span>
        </div>
        <?php endif; ?>
    </div>
</div>
<?php endif; ?>

    <!-- Action Buttons -->
    <div class="action-buttons">
        <?php if ($appointment['status'] == 'confirmed'): ?>
            <button class="btn-checkin" onclick="checkIn(<?php echo $appointment['id']; ?>)">
                <i class="fas fa-user-check"></i> Check In
            </button>
        <?php endif; ?>
        
        <?php if ($appointment['status'] == 'checked_in'): ?>
            <a href="<?php echo SITE_URL; ?>doctor.php?action=consultation&sub=create&id=<?php echo $appointment['id']; ?>" 
               class="btn-consult">
                <i class="fas fa-notes-medical"></i> Start Consultation
            </a>
        <?php endif; ?>
        
        <a href="<?php echo SITE_URL; ?>doctor.php?action=appointments&sub=today" class="btn-back">Back</a>
    </div>
</div>

<script>
function checkIn(appointmentId) {
    var xhr = new XMLHttpRequest();
    xhr.open('POST', '<?php echo SITE_URL; ?>doctor.php?action=appointments&sub=checkin', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            var response = JSON.parse(xhr.responseText);
            if (response.success) {
                location.reload();
            } else {
                alert(response.message);
            }
        }
    };
    
    xhr.send('appointment_id=' + encodeURIComponent(appointmentId));
}
</script>

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
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    overflow: hidden;
}
.card-header {
    padding: 15px 20px;
    background: #f8f9fa;
    border-bottom: 1px solid #eee;
}
.card-header h3 {
    margin: 0;
    font-size: 16px;
    display: flex;
    align-items: center;
    gap: 10px;
}
.card-header h3 i {
    color: #667eea;
}
.card-body {
    padding: 20px;
}
.info-row {
    display: flex;
    margin-bottom: 12px;
}
.info-row .label {
    width: 140px;
    font-weight: 600;
    color: #555;
}
.info-row .value {
    flex: 1;
    color: #333;
}
.medical-history {
    background: #f8f9fa;
    padding: 15px;
    border-radius: 8px;
    line-height: 1.5;
}
.consultation-card {
    border-left: 4px solid #28a745;
}
.prescription {
    background: #e8eaf6;
    padding: 10px;
    border-radius: 8px;
    font-family: monospace;
}
.status-badge {
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 12px;
}
.status-pending { background: #fff3cd; color: #856404; }
.status-confirmed { background: #d4edda; color: #155724; }
.status-checked_in { background: #cce5ff; color: #004085; }
.status-completed { background: #d1ecf1; color: #0c5460; }
.status-cancelled { background: #f8d7da; color: #721c24; }
.action-buttons {
    display: flex;
    gap: 15px;
    margin-top: 10px;
}
.btn-checkin, .btn-consult {
    padding: 10px 20px;
    border-radius: 8px;
    text-decoration: none;
    border: none;
    cursor: pointer;
}
.btn-checkin {
    background: #17a2b8;
    color: white;
}
.btn-consult {
    background: #28a745;
    color: white;
}
@media (max-width: 768px) {
    .info-row {
        flex-direction: column;
    }
    .info-row .label {
        width: 100%;
        margin-bottom: 5px;
    }
}
</style>