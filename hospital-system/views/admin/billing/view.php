<div class="page-header">
    <h1 class="page-title">Bill Details</h1>
    <a href="<?php echo SITE_URL; ?>admin.php?action=billing" class="btn-secondary">
        <i class="fas fa-arrow-left"></i> Back to Dashboard
    </a>
</div>

<div class="bill-container">
    <div class="bill-card">
        <div class="bill-header">
            <div class="hospital-info">
                <h2><?php echo htmlspecialchars($bill['patient_name']); ?></h2>
                <p><?php echo htmlspecialchars($bill['patient_email']); ?></p>
                <p><?php echo htmlspecialchars($bill['patient_phone']); ?></p>
            </div>
            <div class="bill-info">
                <h3>INVOICE</h3>
                <p>Bill #<?php echo $bill['id']; ?></p>
                <p>Date: <?php echo date('M d, Y', strtotime($bill['created_at'])); ?></p>
            </div>
        </div>
        
        <div class="bill-body">
            <table class="bill-table">
                <thead>
                    <tr>
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
                            <?php echo htmlspecialchars($bill['specialization_name']); ?><br>
                            Date: <?php echo date('M d, Y', strtotime($bill['appointment_date'])); ?>
                        </td>
                        <td class="text-right">$<?php echo number_format($bill['amount'], 2); ?></td>
                    </tr>
                    <tr class="total-row">
                        <td colspan="2" class="text-right"><strong>Total</strong></td>
                        <td class="text-right"><strong>$<?php echo number_format($bill['amount'], 2); ?></strong></td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <div class="bill-footer">
            <div class="payment-status">
                <strong>Payment Status:</strong>
                <span class="status-badge status-<?php echo $bill['payment_status']; ?>">
                    <?php echo ucfirst($bill['payment_status']); ?>
                </span>
            </div>
            
            <?php if ($bill['payment_status'] == 'pending'): ?>
                <a href="<?php echo SITE_URL; ?>admin.php?action=billing&sub=markPaid&id=<?php echo $bill['id']; ?>" 
                   class="btn-mark-paid" onclick="return confirm('Mark this bill as paid?')">
                    <i class="fas fa-check"></i> Mark as Paid
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
}

.bill-container {
    max-width: 800px;
    margin: 0 auto;
}

.bill-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    overflow: hidden;
}

.bill-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 30px;
    display: flex;
    justify-content: space-between;
}

.hospital-info h2 {
    margin: 0 0 5px 0;
    font-size: 20px;
}

.hospital-info p {
    margin: 3px 0;
    opacity: 0.9;
    font-size: 13px;
}

.bill-info {
    text-align: right;
}

.bill-info h3 {
    margin: 0 0 5px 0;
    font-size: 24px;
}

.bill-info p {
    margin: 3px 0;
    opacity: 0.9;
    font-size: 13px;
}

.bill-body {
    padding: 30px;
}

.bill-table {
    width: 100%;
    border-collapse: collapse;
}

.bill-table th,
.bill-table td {
    padding: 12px;
    border-bottom: 1px solid #eee;
}

.bill-table th {
    background: #f8f9fa;
    font-weight: 600;
    text-align: left;
}

.text-right {
    text-align: right;
}

.total-row {
    background: #f8f9fa;
    font-weight: bold;
}

.bill-footer {
    padding: 20px 30px;
    border-top: 1px solid #eee;
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: #fafafa;
}

.payment-status {
    display: flex;
    align-items: center;
    gap: 10px;
}

.status-badge {
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 500;
}

.status-paid {
    background: #d4edda;
    color: #155724;
}

.status-pending {
    background: #fff3cd;
    color: #856404;
}

.btn-mark-paid {
    background: #28a745;
    color: white;
    padding: 8px 16px;
    border-radius: 6px;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.btn-mark-paid:hover {
    background: #218838;
    color: white;
}

.btn-print {
    background: #6c757d;
    color: white;
    border: none;
    padding: 8px 16px;
    border-radius: 6px;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

@media print {
    .page-header,
    .btn-secondary,
    .btn-mark-paid,
    .btn-print,
    .admin-sidebar,
    .admin-topbar {
        display: none;
    }
    
    .bill-card {
        box-shadow: none;
        margin: 0;
        padding: 0;
    }
    
    .bill-header {
        background: #f8f9fa;
        color: #333;
    }
}
</style>