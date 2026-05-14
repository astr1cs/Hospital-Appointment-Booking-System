<div class="page-header">
    <h1 class="page-title">Global Appointment Policies</h1>
</div>

<?php if (isset($success) && $success): ?>
    <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
<?php endif; ?>

<?php if (isset($error) && $error): ?>
    <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>

<div class="settings-card">
    <div class="card-header">
        <h3><i class="fas fa-calendar-alt"></i> Appointment Policies</h3>
        <p class="card-desc">Configure global appointment rules and limits</p>
    </div>
    <div class="card-body">
        <form method="POST" action="<?php echo SITE_URL; ?>admin.php?action=settings&sub=updatePolicies">
            <div class="form-group">
                <label>Minimum Cancellation Notice Period</label>
                <div class="input-group">
                    <input type="number" name="cancellation_hours" 
                           class="form-control" 
                           value="<?php echo $policies['cancellation_hours']; ?>"
                           min="1" max="72" required>
                    <span class="input-suffix">hours</span>
                </div>
                <small>Patients cannot cancel appointments within this many hours before the appointment time.</small>
            </div>
            
            <div class="form-group">
                <label>Maximum Advance Booking Window</label>
                <div class="input-group">
                    <input type="number" name="max_booking_days" 
                           class="form-control" 
                           value="<?php echo $policies['max_booking_days']; ?>"
                           min="1" max="365" required>
                    <span class="input-suffix">days</span>
                </div>
                <small>Patients can book appointments up to this many days in advance.</small>
            </div>
            
            <div class="form-group">
                <label>Default Consultation Fee</label>
                <div class="input-group">
                    <span class="input-prefix">$</span>
                    <input type="number" name="default_consultation_fee" 
                           class="form-control" 
                           value="<?php echo $policies['default_consultation_fee']; ?>"
                           min="0" step="0.01" required>
                </div>
                <small>Default fee for doctors who don't set their own consultation fee.</small>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn-submit">
                    <i class="fas fa-save"></i> Save Policies
                </button>
            </div>
        </form>
    </div>
</div>

<div class="current-settings">
    <div class="summary-card">
        <h4><i class="fas fa-info-circle"></i> Current Settings</h4>
        <div class="summary-grid">
            <div class="summary-item">
                <span class="summary-label">Cancellation Notice:</span>
                <span class="summary-value"><?php echo $policies['cancellation_hours']; ?> hours</span>
            </div>
            <div class="summary-item">
                <span class="summary-label">Max Booking Window:</span>
                <span class="summary-value"><?php echo $policies['max_booking_days']; ?> days</span>
            </div>
            <div class="summary-item">
                <span class="summary-label">Default Fee:</span>
                <span class="summary-value">$<?php echo number_format($policies['default_consultation_fee'], 2); ?></span>
            </div>
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
    font-size: 24px;
    margin: 0;
    color: #333;
}

.settings-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    overflow: hidden;
    max-width: 600px;
    margin-bottom: 25px;
}

.card-header {
    padding: 20px 25px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.card-header h3 {
    margin: 0 0 5px 0;
    font-size: 18px;
}

.card-desc {
    margin: 0;
    font-size: 12px;
    opacity: 0.9;
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
    font-weight: 500;
    color: #333;
    font-size: 14px;
}

.form-control {
    width: 100%;
    padding: 10px 12px;
    border: 1px solid #ddd;
    border-radius: 6px;
    font-size: 14px;
}

.form-control:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.input-group {
    display: flex;
    align-items: center;
    gap: 10px;
}

.input-group .form-control {
    flex: 1;
}

.input-prefix {
    background: #f8f9fa;
    padding: 10px 12px;
    border: 1px solid #ddd;
    border-radius: 6px;
    font-weight: 500;
}

.input-suffix {
    color: #666;
    font-size: 14px;
}

small {
    display: block;
    margin-top: 5px;
    font-size: 11px;
    color: #666;
}

.form-actions {
    margin-top: 25px;
    padding-top: 20px;
    border-top: 1px solid #eee;
}

.btn-submit {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 10px 24px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-size: 14px;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.btn-submit:hover {
    transform: translateY(-1px);
    box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
}

.current-settings {
    max-width: 600px;
}

.summary-card {
    background: white;
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.summary-card h4 {
    margin: 0 0 15px 0;
    color: #333;
    font-size: 16px;
}

.summary-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 15px;
}

.summary-item {
    display: flex;
    flex-direction: column;
    gap: 5px;
}

.summary-label {
    font-size: 11px;
    color: #666;
}

.summary-value {
    font-size: 16px;
    font-weight: 600;
    color: #667eea;
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
    .summary-grid {
        grid-template-columns: 1fr;
        gap: 10px;
    }
}
</style>