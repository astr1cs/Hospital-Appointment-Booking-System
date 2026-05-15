<div class="page-header">
    <h1 class="page-title">Bill Details</h1>
    <a href="<?php echo SITE_URL; ?>patient.php?action=billing" class="btn-back">
        <i class="fas fa-arrow-left"></i> Back to Billing
    </a>
</div>

<div class="invoice-container">
    <div class="invoice-card">
        <div class="invoice-header">
            <div class="hospital-info">
                <h2>City Hospital</h2>
                <p>123 Healthcare Ave, Medical District</p>
                <p>Phone: +1 234 567 890 | Email: info@cityhospital.com</p>
            </div>
            <div class="invoice-info">
                <h3>INVOICE</h3>
                <p><strong>Invoice #:</strong> <?php echo str_pad($bill['id'], 6, '0', STR_PAD_LEFT); ?></p>
                <p><strong>Date:</strong> <?php echo date('F d, Y', strtotime($bill['created_at'])); ?></p>
                <p><strong>Status:</strong> 
                    <span class="status-badge status-<?php echo $bill['payment_status']; ?>">
                        <?php echo ucfirst($bill['payment_status']); ?>
                    </span>
                </p>
            </div>
        </div>

        <div class="patient-info">
            <h4>Bill To:</h4>
            <p><strong>Name:</strong> <?php echo htmlspecialchars($_SESSION['user_name']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($_SESSION['user_email']); ?></p>
        </div>

        <div class="invoice-details">
            <table class="invoice-table">
                <thead>
                    <tr class="table-header">
                        <th>Description</th>
                        <th>Details</th>
                        <th class="text-right">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Consultation Fee</td>
                        <td>
                            Dr. <?php echo htmlspecialchars($bill['doctor_name']); ?><br>
                            <small><?php echo htmlspecialchars($bill['specialization_name']); ?></small>
                        </td>
                        <td class="text-right">$<?php echo number_format($bill['amount'], 2); ?></td>
                    </tr>
                    <tr>
                        <td colspan="2">Appointment Date</td>
                        <td class="text-right"><?php echo date('F d, Y', strtotime($bill['appointment_date'])); ?> at <?php echo date('h:i A', strtotime($bill['appointment_time'])); ?></td>
                    </tr>
                    <?php if ($bill['reason']): ?>
                    <tr>
                        <td colspan="2">Reason for Visit</td>
                        <td class="text-right"><?php echo htmlspecialchars(substr($bill['reason'], 0, 50)); ?></td>
                    </tr>
                    <?php endif; ?>
                </tbody>
                <tfoot>
                    <tr class="total-row">
                        <td colspan="2" class="text-right"><strong>Total Amount</strong></td>
                        <td class="text-right"><strong>$<?php echo number_format($bill['amount'], 2); ?></strong></td>
                    </tr>
                    <?php if ($bill['payment_status'] == 'paid' && $bill['paid_at']): ?>
                    <tr>
                        <td colspan="2" class="text-right">Paid On</td>
                        <td class="text-right"><?php echo date('F d, Y h:i A', strtotime($bill['paid_at'])); ?></td>
                    </tr>
                    <?php endif; ?>
                </tfoot>
            </table>
        </div>

        <div class="invoice-footer">
            <?php if ($bill['payment_status'] == 'pending'): ?>
                <a href="<?php echo SITE_URL; ?>patient.php?action=billing&sub=pay&id=<?php echo $bill['id']; ?>" 
                   class="btn-pay" onclick="return confirm('Pay $<?php echo number_format($bill['amount'], 2); ?> for this appointment?')">
                    <i class="fas fa-credit-card"></i> Pay Now
                </a>
            <?php endif; ?>
            <button onclick="window.print();" class="btn-print">
                <i class="fas fa-print"></i> Print Invoice
            </button>
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

.invoice-container {
    max-width: 800px;
    margin: 0 auto;
}

.invoice-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    overflow: hidden;
}

.invoice-header {
    background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
    color: white;
    padding: 30px;
    display: flex;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 20px;
}

.hospital-info h2 {
    margin: 0 0 10px 0;
    font-size: 20px;
    color: #fff;
}

.hospital-info p {
    margin: 3px 0;
    opacity: 0.8;
    font-size: 12px;
}

.invoice-info {
    text-align: right;
}

.invoice-info h3 {
    margin: 0 0 10px 0;
    font-size: 24px;
}

.invoice-info p {
    margin: 5px 0;
    font-size: 13px;
}

.patient-info {
    padding: 20px 30px;
    background: #f8f9fa;
    border-bottom: 1px solid #eee;
}

.patient-info h4 {
    margin: 0 0 10px 0;
    color: #333;
}

.patient-info p {
    margin: 5px 0;
    font-size: 13px;
}

.invoice-details {
    padding: 30px;
}

.invoice-table {
    width: 100%;
    border-collapse: collapse;
}

.invoice-table th,
.invoice-table td {
    padding: 12px;
    border-bottom: 1px solid #eee;
}

.table-header th {
    background: #f8f9fa;
    font-weight: 600;
}

.text-right {
    text-align: right;
}

.total-row {
    background: #f8f9fa;
    font-weight: bold;
}

.total-row td {
    padding: 15px 12px;
    border-top: 2px solid #ddd;
}

.status-badge {
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 11px;
    display: inline-block;
}

.status-paid {
    background: #d4edda;
    color: #155724;
}

.status-pending {
    background: #fff3cd;
    color: #856404;
}

.invoice-footer {
    padding: 20px 30px;
    border-top: 1px solid #eee;
    display: flex;
    gap: 15px;
    justify-content: flex-end;
}

.btn-pay, .btn-print {
    padding: 10px 20px;
    border-radius: 8px;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    border: none;
    cursor: pointer;
}

.btn-pay {
    background: #28a745;
    color: white;
}

.btn-pay:hover {
    background: #218838;
}

.btn-print {
    background: #6c757d;
    color: white;
}

.btn-print:hover {
    background: #5a6268;
}

@media print {
    .page-header,
    .btn-back,
    .invoice-footer,
    .admin-sidebar,
    .patient-sidebar,
    .patient-topbar {
        display: none;
    }
    
    .invoice-card {
        box-shadow: none;
        margin: 0;
        padding: 0;
    }
    
    .invoice-header {
        background: #f8f9fa;
        color: #333;
    }
}
</style>