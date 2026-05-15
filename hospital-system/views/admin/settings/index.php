<div class="page-header">
    <h1 class="page-title">Global Appointment Policies</h1>
</div>

<?php if (isset($success) && $success): ?>
    <div class="alert alert-success">
        <i class="fas fa-check-circle"></i>
        <?php echo htmlspecialchars($success); ?>
    </div>
<?php endif; ?>

<?php if (isset($error) && $error): ?>
    <div class="alert alert-danger">
        <i class="fas fa-exclamation-circle"></i>
        <?php echo htmlspecialchars($error); ?>
    </div>
<?php endif; ?>

<div class="settings-wrapper">
    <!-- Left Column: Appointment Policies Form -->
    <div class="settings-card">
        <div class="card-header">
            <h3><i class="fas fa-calendar-alt"></i> Appointment Policies</h3>
            <p class="card-desc">Configure global appointment rules and limits</p>
        </div>
        <div class="card-body">
            <form method="POST" action="<?php echo SITE_URL; ?>admin.php?action=settings&sub=updatePolicies">
                <div class="form-group">
                    <label><i class="fas fa-hourglass-half"></i> Minimum Cancellation Notice Period</label>
                    <div class="input-group">
                        <input type="number" name="cancellation_hours" 
                               class="form-control" 
                               value="<?php echo $policies['cancellation_hours']; ?>"
                               min="1" max="72" required>
                        <span class="input-suffix">hours</span>
                    </div>
                    <small><i class="fas fa-info-circle"></i> Patients cannot cancel appointments within this many hours before the appointment time.</small>
                </div>
                
                <div class="form-group">
                    <label><i class="fas fa-calendar-plus"></i> Maximum Advance Booking Window</label>
                    <div class="input-group">
                        <input type="number" name="max_booking_days" 
                               class="form-control" 
                               value="<?php echo $policies['max_booking_days']; ?>"
                               min="1" max="365" required>
                        <span class="input-suffix">days</span>
                    </div>
                    <small><i class="fas fa-info-circle"></i> Patients can book appointments up to this many days in advance.</small>
                </div>
                
                <div class="form-group">
                    <label><i class="fas fa-dollar-sign"></i> Default Consultation Fee</label>
                    <div class="input-group">
                        <span class="input-prefix">$</span>
                        <input type="number" name="default_consultation_fee" 
                               class="form-control" 
                               value="<?php echo $policies['default_consultation_fee']; ?>"
                               min="0" step="0.01" required>
                    </div>
                    <small><i class="fas fa-info-circle"></i> Default fee for doctors who don't set their own consultation fee.</small>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn-submit">
                        <i class="fas fa-save"></i> Save Policies
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Right Column: Current Settings Summary -->
    <div class="summary-card">
        <div class="card-header">
            <h4><i class="fas fa-chart-line"></i> Current Settings</h4>
        </div>
        <div class="summary-stats">
            <div class="summary-grid">
                <div class="summary-item">
                    <span class="summary-label">
                        <i class="fas fa-hourglass-half"></i> Cancellation Notice
                    </span>
                    <span class="summary-value"><?php echo $policies['cancellation_hours']; ?> hours</span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">
                        <i class="fas fa-calendar-week"></i> Max Booking Window
                    </span>
                    <span class="summary-value"><?php echo $policies['max_booking_days']; ?> days</span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">
                        <i class="fas fa-dollar-sign"></i> Default Consultation Fee
                    </span>
                    <span class="summary-value">$<?php echo number_format($policies['default_consultation_fee'], 2); ?></span>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
:root {
    --primary: #4361ee;
    --primary-dark: #3a0ca3;
    --success: #2ec4b2;
    --danger: #e63946;
    --warning: #ff9f1c;
    --dark: #1a1a2e;
    --light: #f8f9fa;
    --gray: #6c757d;
    --border: #e9ecef;
}

.page-header {
    margin-bottom: 30px;
}

.page-title {
    font-size: 28px;
    font-weight: 600;
    margin: 0;
    color: var(--dark);
    position: relative;
    display: inline-block;
}

.page-title:after {
    content: '';
    position: absolute;
    bottom: -8px;
    left: 0;
    width: 50px;
    height: 3px;
    background: linear-gradient(135deg, var(--primary), #7209b7);
    border-radius: 3px;
}

/* Two Column Layout */
.settings-wrapper {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 30px;
    align-items: start;
}

/* Alert Messages */
.alert {
    padding: 14px 20px;
    border-radius: 12px;
    margin-bottom: 25px;
    display: flex;
    align-items: center;
    gap: 12px;
    font-size: 14px;
    animation: slideIn 0.3s ease;
}

.alert i {
    font-size: 18px;
}

.alert-success {
    background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
    color: #065f46;
    border-left: 4px solid #10b981;
}

.alert-danger {
    background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
    color: #991b1b;
    border-left: 4px solid #ef4444;
}

@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Settings Card */
.settings-card {
    background: white;
    border-radius: 20px;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
    overflow: hidden;
    transition: transform 0.2s, box-shadow 0.2s;
    height: 100%;
}

.settings-card:hover {
    box-shadow: 0 20px 50px rgba(0, 0, 0, 0.12);
}

.card-header {
    padding: 24px 28px;
    background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
    color: white;
}

.card-header h3 {
    margin: 0 0 8px 0;
    font-size: 20px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 10px;
    color: white;
}

.card-header h3 i {
    font-size: 24px;
    color: #667eea;
}

.card-desc {
    margin: 0;
    font-size: 13px;
    opacity: 0.8;
    line-height: 1.5;
    color: rgba(255, 255, 255, 0.8);
}

.card-body {
    padding: 28px;
}

/* Form Styles */
.form-group {
    margin-bottom: 24px;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    font-weight: 600;
    color: var(--dark);
    font-size: 14px;
}

.form-group label i {
    margin-right: 8px;
    color: var(--primary);
    width: 18px;
}

.form-control {
    width: 100%;
    padding: 12px 16px;
    border: 2px solid var(--border);
    border-radius: 12px;
    font-size: 14px;
    transition: all 0.2s;
    background: white;
}

.form-control:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 4px rgba(67, 97, 238, 0.1);
}

.input-group {
    display: flex;
    align-items: center;
    gap: 12px;
}

.input-group .form-control {
    flex: 1;
}

.input-prefix {
    background: var(--light);
    padding: 12px 16px;
    border: 2px solid var(--border);
    border-radius: 12px;
    font-weight: 600;
    color: var(--dark);
}

.input-suffix {
    color: var(--gray);
    font-size: 14px;
    font-weight: 500;
}

small {
    display: block;
    margin-top: 8px;
    font-size: 12px;
    color: var(--gray);
    line-height: 1.4;
}

small i {
    margin-right: 4px;
    font-size: 11px;
}

/* Form Actions */
.form-actions {
    margin-top: 32px;
    padding-top: 24px;
    border-top: 1px solid var(--border);
}

.btn-submit {
    background: linear-gradient(135deg, var(--primary) 0%, #7209b7 100%);
    color: white;
    padding: 12px 28px;
    border: none;
    border-radius: 12px;
    cursor: pointer;
    font-size: 14px;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 10px;
    transition: all 0.3s;
}

.btn-submit:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(67, 97, 238, 0.3);
}

.btn-submit:active {
    transform: translateY(0);
}

/* Current Settings Summary */
.summary-card {
    background: white;
    border-radius: 20px;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
    overflow: hidden;
    height: 100%;
}

.summary-card .card-header {
    background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
    padding: 24px 28px;
}

.summary-card .card-header h4 {
    margin: 0;
    color: white;
    font-size: 18px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 10px;
}

.summary-card .card-header h4 i {
    color: #667eea;
    font-size: 20px;
}

.summary-stats {
    padding: 28px;
}

.summary-grid {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.summary-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 16px 20px;
    background: var(--light);
    border-radius: 12px;
    transition: all 0.2s;
}

.summary-item:hover {
    background: linear-gradient(135deg, #f0f0f0 0%, #e8e8e8 100%);
    transform: translateX(4px);
}

.summary-label {
    font-size: 13px;
    font-weight: 600;
    color: var(--gray);
    text-transform: uppercase;
    letter-spacing: 0.5px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.summary-label i {
    color: var(--primary);
    font-size: 14px;
}

.summary-value {
    font-size: 22px;
    font-weight: 700;
    color: var(--primary);
    line-height: 1.2;
}

/* Responsive */
@media (max-width: 1024px) {
    .settings-wrapper {
        gap: 25px;
    }
}

@media (max-width: 768px) {
    .settings-wrapper {
        grid-template-columns: 1fr;
        gap: 20px;
    }
    
    .card-header {
        padding: 20px;
    }
    
    .card-body {
        padding: 20px;
    }
    
    .summary-stats {
        padding: 20px;
    }
    
    .summary-item {
        padding: 12px 16px;
    }
    
    .summary-value {
        font-size: 18px;
    }
    
    .btn-submit {
        width: 100%;
        justify-content: center;
    }
}
</style>