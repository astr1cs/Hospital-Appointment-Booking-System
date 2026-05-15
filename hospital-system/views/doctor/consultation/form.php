<div class="page-header">
    <h1 class="page-title">Consultation Notes</h1>
    <p>Patient: <strong><?php echo htmlspecialchars($appointment['patient_name']); ?></strong></p>
    <p>Appointment Date: <?php echo date('F d, Y h:i A', strtotime($appointment['appointment_date'] . ' ' . $appointment['appointment_time'])); ?></p>
</div>

<div class="consultation-container">
    <div class="consultation-form">
        <div class="form-card">
            <div class="card-header">
                <h3><i class="fas fa-notes-medical"></i> Medical Record</h3>
            </div>
            <div class="card-body">
                <form method="POST" action="<?php echo SITE_URL; ?>doctor.php?action=consultation&sub=store">
                    <input type="hidden" name="appointment_id" value="<?php echo $appointment['id']; ?>">
                    <input type="hidden" name="patient_id" value="<?php echo $appointment['patient_id']; ?>">
                    
                    <div class="form-group">
                        <label><i class="fas fa-stethoscope"></i> Symptoms</label>
                        <textarea name="symptoms" class="form-control" rows="4" 
                                  placeholder="Describe patient's symptoms..."><?php echo htmlspecialchars($notes['symptoms'] ?? ''); ?></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label><i class="fas fa-diagnoses"></i> Diagnosis</label>
                        <textarea name="diagnosis" class="form-control" rows="4" 
                                  placeholder="Enter diagnosis..."><?php echo htmlspecialchars($notes['diagnosis'] ?? ''); ?></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label><i class="fas fa-prescription-bottle"></i> Prescription</label>
                        <textarea name="prescription" class="form-control" rows="6" 
                                  placeholder="Medications with dosage, instructions..."><?php echo htmlspecialchars($notes['prescription'] ?? ''); ?></textarea>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label><i class="fas fa-calendar-alt"></i> Follow-up Date</label>
                            <input type="date" name="follow_up_date" class="form-control" 
                                   value="<?php echo $notes['follow_up_date'] ?? ''; ?>"
                                   min="<?php echo date('Y-m-d'); ?>">
                        </div>
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" class="btn-submit">
                            <i class="fas fa-save"></i> Save & Complete
                        </button>
                        <a href="<?php echo SITE_URL; ?>doctor.php?action=appointments&sub=today" class="btn-cancel">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="patient-info-sidebar">
        <div class="info-card">
            <h4><i class="fas fa-user"></i> Patient Information</h4>
            <div class="info-row">
                <span class="label">Name:</span>
                <span><?php echo htmlspecialchars($appointment['patient_name']); ?></span>
            </div>
            <div class="info-row">
                <span class="label">Email:</span>
                <span><?php echo htmlspecialchars($appointment['patient_email']); ?></span>
            </div>
            <div class="info-row">
                <span class="label">Phone:</span>
                <span><?php echo htmlspecialchars($appointment['patient_phone']); ?></span>
            </div>
            <?php if ($appointment['date_of_birth']): ?>
            <div class="info-row">
                <span class="label">DOB:</span>
                <span><?php echo date('M d, Y', strtotime($appointment['date_of_birth'])); ?></span>
            </div>
            <?php endif; ?>
            <?php if ($appointment['blood_group']): ?>
            <div class="info-row">
                <span class="label">Blood Group:</span>
                <span><?php echo $appointment['blood_group']; ?></span>
            </div>
            <?php endif; ?>
        </div>
        
        <?php if ($appointment['medical_history_notes']): ?>
        <div class="info-card">
            <h4><i class="fas fa-history"></i> Medical History</h4>
            <div class="medical-history">
                <?php echo nl2br(htmlspecialchars($appointment['medical_history_notes'])); ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<style>
.page-header {
    margin-bottom: 25px;
}
.page-title {
    font-size: 28px;
    margin: 0 0 10px 0;
    color: #333;
}
.consultation-container {
    display: grid;
    grid-template-columns: 1fr 350px;
    gap: 25px;
}
.consultation-form {
    grid-column: 1;
}
.form-card, .info-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    overflow: hidden;
    margin-bottom: 20px;
}
.card-header {
    padding: 18px 25px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}
.card-header h3 {
    margin: 0;
    font-size: 18px;
    display: flex;
    align-items: center;
    gap: 10px;
}
.card-body {
    padding: 25px;
}
.form-group {
    margin-bottom: 20px;
}
.form-group label {
    display: block;
    margin-bottom: 8px;
    font-weight: 600;
    color: #333;
}
.form-control {
    width: 100%;
    padding: 10px 12px;
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
.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
}
.form-actions {
    display: flex;
    gap: 15px;
    margin-top: 25px;
    padding-top: 20px;
    border-top: 1px solid #eee;
}
.btn-submit {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    color: white;
    padding: 10px 24px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}
.btn-cancel {
    background: #6c757d;
    color: white;
    padding: 10px 20px;
    border-radius: 8px;
    text-decoration: none;
}
.patient-info-sidebar {
    grid-column: 2;
}
.info-card {
    margin-bottom: 20px;
}
.info-card h4 {
    margin: 0 0 15px 0;
    padding-bottom: 10px;
    border-bottom: 1px solid #eee;
    color: #667eea;
}
.info-row {
    display: flex;
    margin-bottom: 10px;
}
.info-row .label {
    width: 100px;
    font-weight: 600;
    color: #555;
}
.medical-history {
    background: #f8f9fa;
    padding: 12px;
    border-radius: 8px;
    font-size: 13px;
    line-height: 1.5;
    color: #555;
}
@media (max-width: 1024px) {
    .consultation-container {
        grid-template-columns: 1fr;
    }
    .patient-info-sidebar {
        grid-column: 1;
    }
}
@media (max-width: 768px) {
    .form-row {
        grid-template-columns: 1fr;
        gap: 0;
    }
    .form-actions {
        flex-direction: column;
    }
    .btn-submit, .btn-cancel {
        justify-content: center;
    }
}
</style>