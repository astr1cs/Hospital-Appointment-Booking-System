<div class="page-header">
    <h1 class="page-title">Process Payments</h1>
</div>

<!-- Totals Summary -->
<div class="totals-row">
    <div class="total-card pending">
        <div class="total-icon"><i class="fas fa-clock"></i></div>
        <div class="total-info">
            <h3>$<?php echo number_format($totals['total_pending'], 2); ?></h3>
            <p>Pending Amount (<?php echo $totals['pending_count']; ?> bills)</p>
        </div>
    </div>
    <div class="total-card paid">
        <div class="total-icon"><i class="fas fa-check-circle"></i></div>
        <div class="total-info">
            <h3>$<?php echo number_format($totals['total_paid'], 2); ?></h3>
            <p>Total Collected</p>
        </div>
    </div>
</div>

<?php if (isset($success) && $success): ?>
    <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
<?php endif; ?>

<?php if (isset($error) && $error): ?>
    <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>

<!-- Pending Payments -->
<div class="payments-section">
    <h2><i class="fas fa-hourglass-half"></i> Pending Payments</h2>
    
    <?php if ($pendingBills->num_rows > 0): ?>
        <div class="payments-table-container">
            <table class="payments-table">
                <thead>
                    <tr>
                        <th>Patient</th>
                        <th>Doctor</th>
                        <th>Appointment Date</th>
                        <th>Amount</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = $pendingBills->fetch_assoc()): ?>
                    <tr class="pending-row">
                        <td>
                            <strong><?php echo htmlspecialchars($row['patient_name']); ?></strong><br>
                            <small><?php echo htmlspecialchars($row['patient_phone']); ?></small>
                        </td>
                        <td><?php echo htmlspecialchars($row['doctor_name']); ?></td>
                        <td><?php echo date('M d, Y', strtotime($row['appointment_date'])); ?> <?php echo date('h:i A', strtotime($row['appointment_time'])); ?></td>
                        <td class="amount">$<?php echo number_format($row['amount'], 2); ?></td>
                        <td>
                            <a href="<?php echo SITE_URL; ?>receptionist.php?action=payments&sub=process&id=<?php echo $row['id']; ?>" 
                               class="btn-pay">Process Payment</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="empty-state-small">
            <i class="fas fa-check-circle"></i>
            <p>No pending payments</p>
        </div>
    <?php endif; ?>
</div>

<!-- Recent Payments -->
<div class="payments-section">
    <h2><i class="fas fa-history"></i> Recent Payments</h2>
    
    <?php if ($paidBills->num_rows > 0): ?>
        <div class="payments-table-container">
            <table class="payments-table">
                <thead>
                    <tr>
                        <th>Patient</th>
                        <th>Doctor</th>
                        <th>Amount</th>
                        <th>Method</th>
                        <th>Paid On</th>
                        <th>Receipt</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = $paidBills->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['patient_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['doctor_name']); ?></td>
                        <td class="amount">$<?php echo number_format($row['amount'], 2); ?></td>
                        <td><span class="method-badge"><?php echo ucfirst($row['payment_method']); ?></span></td>
                        <td><?php echo date('M d, Y h:i A', strtotime($row['paid_at'])); ?></td>
                        <td>
                            <a href="<?php echo SITE_URL; ?>receptionist.php?action=payments&sub=receipt&id=<?php echo $row['id']; ?>" 
                               class="btn-receipt" target="_blank">Print Receipt</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="empty-state-small">
            <i class="fas fa-receipt"></i>
            <p>No payment history</p>
        </div>
    <?php endif; ?>
</div>

<style>
.page-header {
    margin-bottom: 25px;
}
.page-title {
    font-size: 28px;
    margin: 0;
    color: #333;
}
.totals-row {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 25px;
    margin-bottom: 30px;
}
.total-card {
    background: white;
    border-radius: 12px;
    padding: 20px;
    display: flex;
    align-items: center;
    gap: 15px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}
.total-card.pending .total-icon {
    background: #ffc107;
}
.total-card.paid .total-icon {
    background: #28a745;
}
.total-icon {
    width: 60px;
    height: 60px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
}
.total-icon i {
    font-size: 28px;
    color: white;
}
.total-info h3 {
    font-size: 28px;
    margin: 0 0 5px 0;
}
.total-info p {
    color: #666;
    margin: 0;
}
.payments-section {
    background: white;
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 25px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}
.payments-section h2 {
    margin: 0 0 20px 0;
    font-size: 18px;
    padding-bottom: 10px;
    border-bottom: 1px solid #eee;
}
.payments-table {
    width: 100%;
    border-collapse: collapse;
}
.payments-table th,
.payments-table td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid #eee;
}
.payments-table th {
    background: #f8f9fa;
    font-weight: 600;
}
.pending-row {
    background: #fffef5;
}
.amount {
    font-weight: 600;
    color: #28a745;
}
.btn-pay {
    background: #28a745;
    color: white;
    padding: 6px 12px;
    border-radius: 5px;
    text-decoration: none;
    font-size: 12px;
}
.btn-receipt {
    background: #667eea;
    color: white;
    padding: 6px 12px;
    border-radius: 5px;
    text-decoration: none;
    font-size: 12px;
}
.method-badge {
    background: #e8eaf6;
    color: #3949ab;
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 11px;
}
.empty-state-small {
    text-align: center;
    padding: 30px;
    color: #999;
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
@media (max-width: 768px) {
    .totals-row {
        grid-template-columns: 1fr;
    }
    .payments-table {
        font-size: 12px;
    }
}
</style>