<div class="page-header">
    <h1 class="page-title">Process Payment</h1>
    <a href="<?php echo SITE_URL; ?>receptionist.php?action=payments" class="btn-back">
        <i class="fas fa-arrow-left"></i> Back to Payments
    </a>
</div>

<?php if (isset($error) && $error): ?>
    <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>

<?php
// Get bill details
$sql = "SELECT b.*, u.name as patient_name, u.email as patient_email, u.phone as patient_phone,
               a.appointment_date, a.appointment_time, a.reason, d.name as doctor_name
        FROM billing b
        JOIN users u ON b.patient_id = u.id
        JOIN appointments a ON b.appointment_id = a.id
        JOIN users d ON a.doctor_id = d.id
        WHERE b.id = ?";
$stmt = $this->db->prepare($sql);
$stmt->bind_param("i", $billId);
$stmt->execute();
$bill = $stmt->get_result()->fetch_assoc();

if (!$bill):
?>
    <div class="alert alert-danger">Bill not found</div>
<?php else: ?>

<div class="payment-container">
    <!-- Bill Summary -->
    <div class="bill-summary">
        <h3><i class="fas fa-receipt"></i> Bill Summary</h3>
        <div class="summary-details">
            <div class="summary-row">
                <span class="label">Bill #:</span>
                <span class="value"><?php echo str_pad($bill['id'], 6, '0', STR_PAD_LEFT); ?></span>
            </div>
            <div class="summary-row">
                <span class="label">Patient:</span>
                <span class="value"><?php echo htmlspecialchars($bill['patient_name']); ?></span>
            </div>
            <div class="summary-row">
                <span class="label">Doctor:</span>
                <span class="value">Dr. <?php echo htmlspecialchars($bill['doctor_name']); ?></span>
            </div>
            <div class="summary-row">
                <span class="label">Appointment Date:</span>
                <span class="value"><?php echo date('F d, Y', strtotime($bill['appointment_date'])); ?> at <?php echo date('h:i A', strtotime($bill['appointment_time'])); ?></span>
            </div>
            <div class="summary-row">
                <span class="label">Amount Due:</span>
                <span class="value amount">$<?php echo number_format($bill['amount'], 2); ?></span>
            </div>
        </div>
    </div>

    <!-- Payment Form -->
    <div class="payment-form">
        <h3><i class="fas fa-credit-card"></i> Payment Details</h3>
        <form method="POST" action="<?php echo SITE_URL; ?>receptionist.php?action=payments&sub=confirm">
            <input type="hidden" name="bill_id" value="<?php echo $bill['id']; ?>">
            
            <div class="form-group">
                <label>Payment Method *</label>
                <div class="payment-methods">
                    <label class="method-option">
                        <input type="radio" name="payment_method" value="cash" checked>
                        <i class="fas fa-money-bill-wave"></i> Cash
                    </label>
                    <label class="method-option">
                        <input type="radio" name="payment_method" value="card">
                        <i class="fas fa-credit-card"></i> Card
                    </label>
                    <label class="method-option">
                        <input type="radio" name="payment_method" value="insurance">
                        <i class="fas fa-shield-alt"></i> Insurance
                    </label>
                    <label class="method-option">
                        <input type="radio" name="payment_method" value="online">
                        <i class="fas fa-mobile-alt"></i> Online
                    </label>
                </div>
            </div>
            
            <div class="form-group">
                <label>Amount to Pay</label>
                <div class="amount-input">
                    <span class="currency">$</span>
                    <input type="number" name="amount" class="form-control" value="<?php echo $bill['amount']; ?>" step="0.01" readonly>
                </div>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn-confirm" onclick="return confirm('Process payment of $<?php echo number_format($bill['amount'], 2); ?>?')">
                    <i class="fas fa-check-circle"></i> Confirm Payment
                </button>
                <a href="<?php echo SITE_URL; ?>receptionist.php?action=payments" class="btn-cancel">Cancel</a>
            </div>
        </form>
    </div>
</div>

<?php endif; ?>

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
.payment-container {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 30px;
}
.bill-summary, .payment-form {
    background: white;
    border-radius: 12px;
    padding: 25px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}
.bill-summary h3, .payment-form h3 {
    margin: 0 0 20px 0;
    padding-bottom: 10px;
    border-bottom: 2px solid #f0f0f0;
    color: #333;
}
.summary-details {
    display: flex;
    flex-direction: column;
    gap: 12px;
}
.summary-row {
    display: flex;
    justify-content: space-between;
    padding: 8px 0;
    border-bottom: 1px solid #eee;
}
.summary-row .label {
    font-weight: 600;
    color: #555;
}
.summary-row .value {
    color: #333;
}
.summary-row .value.amount {
    font-size: 20px;
    font-weight: bold;
    color: #28a745;
}
.payment-methods {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 15px;
    margin-top: 10px;
}
.method-option {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 12px;
    border: 2px solid #ddd;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.3s;
}
.method-option:hover {
    border-color: #667eea;
    background: #f8f9fa;
}
.method-option input {
    margin: 0;
}
.method-option i {
    font-size: 20px;
    color: #667eea;
}
.amount-input {
    display: flex;
    align-items: center;
    gap: 10px;
}
.currency {
    font-size: 20px;
    font-weight: bold;
    color: #555;
}
.form-control {
    flex: 1;
    padding: 10px 12px;
    border: 1px solid #ddd;
    border-radius: 8px;
    font-size: 18px;
    font-weight: bold;
}
.form-actions {
    display: flex;
    gap: 15px;
    margin-top: 25px;
}
.btn-confirm {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    color: white;
    padding: 12px 24px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-size: 16px;
    flex: 1;
}
.btn-cancel {
    background: #6c757d;
    color: white;
    padding: 12px 24px;
    border-radius: 8px;
    text-decoration: none;
    text-align: center;
}
.alert {
    padding: 12px 20px;
    border-radius: 8px;
    margin-bottom: 20px;
}
.alert-danger {
    background: #f8d7da;
    color: #721c24;
    border-left: 4px solid #dc3545;
}
@media (max-width: 1024px) {
    .payment-container {
        grid-template-columns: 1fr;
    }
}
@media (max-width: 768px) {
    .payment-methods {
        grid-template-columns: 1fr;
    }
    .form-actions {
        flex-direction: column;
    }
}
</style>