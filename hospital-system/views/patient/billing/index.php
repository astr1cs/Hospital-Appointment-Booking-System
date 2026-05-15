<div class="page-header">
    <h1 class="page-title">Billing History</h1>
</div>

<!-- Statistics Cards -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon"><i class="fas fa-dollar-sign"></i></div>
        <div class="stat-info">
            <h3>$<?php echo number_format($stats['total_paid'], 2); ?></h3>
            <p>Total Paid</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon"><i class="fas fa-clock"></i></div>
        <div class="stat-info">
            <h3>$<?php echo number_format($stats['total_pending'], 2); ?></h3>
            <p>Pending Payments</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
        <div class="stat-info">
            <h3><?php echo $stats['paid_count']; ?></h3>
            <p>Paid Bills</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon"><i class="fas fa-hourglass-half"></i></div>
        <div class="stat-info">
            <h3><?php echo $stats['pending_count']; ?></h3>
            <p>Pending Bills</p>
        </div>
    </div>
</div>

<?php if (isset($success) && $success): ?>
    <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
<?php endif; ?>

<?php if (isset($error) && $error): ?>
    <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>

<div class="billing-section">
    <div class="section-header">
        <h3><i class="fas fa-receipt"></i> All Transactions</h3>
    </div>
    
    <?php if ($bills->num_rows > 0): ?>
        <div class="bills-table-container">
            <table class="bills-table">
                <thead>
                    <tr>
                        <th>Invoice #</th>
                        <th>Date</th>
                        <th>Doctor</th>
                        <th>Appointment Date</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = $bills->fetch_assoc()): ?>
                    <tr class="bill-row <?php echo $row['payment_status'] == 'pending' ? 'pending-row' : ''; ?>">
                        <td>#<?php echo $row['id']; ?></td>
                        <td><?php echo date('M d, Y', strtotime($row['created_at'])); ?></td>
                        <td>Dr. <?php echo htmlspecialchars($row['doctor_name']); ?></td>
                        <td><?php echo date('M d, Y', strtotime($row['appointment_date'])); ?> at <?php echo date('h:i A', strtotime($row['appointment_time'])); ?></td>
                        <td class="amount">$<?php echo number_format($row['amount'], 2); ?></td>
                        <td>
                            <span class="status-badge status-<?php echo $row['payment_status']; ?>">
                                <?php echo ucfirst($row['payment_status']); ?>
                            </span>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <a href="<?php echo SITE_URL; ?>patient.php?action=billing&sub=show&id=<?php echo $row['id']; ?>" 
                                   class="btn-view" title="View Details">
                                    <i class="fas fa-eye"></i> View
                                </a>
                                <?php if ($row['payment_status'] == 'pending'): ?>
                                <a href="<?php echo SITE_URL; ?>patient.php?action=billing&sub=pay&id=<?php echo $row['id']; ?>" 
                                   class="btn-pay" title="Pay Now" 
                                   onclick="return confirm('Pay $<?php echo number_format($row['amount'], 2); ?> for this appointment?')">
                                    <i class="fas fa-credit-card"></i> Pay Now
                                </a>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="empty-state">
            <i class="fas fa-receipt"></i>
            <h3>No Billing Records</h3>
            <p>Your billing history will appear here after your appointments.</p>
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

.stats-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
    margin-bottom: 30px;
}

.stat-card {
    background: white;
    border-radius: 12px;
    padding: 20px;
    display: flex;
    align-items: center;
    gap: 15px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.stat-icon {
    width: 55px;
    height: 55px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.stat-icon i {
    font-size: 24px;
    color: white;
}

.stat-info h3 {
    font-size: 24px;
    margin: 0 0 5px 0;
}

.stat-info p {
    color: #666;
    margin: 0;
    font-size: 13px;
}

.billing-section {
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    overflow: hidden;
}

.section-header {
    padding: 18px 20px;
    border-bottom: 1px solid #eee;
    background: #f8f9fa;
}

.section-header h3 {
    margin: 0;
    font-size: 16px;
    color: #333;
}

.bills-table-container {
    overflow-x: auto;
}

.bills-table {
    width: 100%;
    border-collapse: collapse;
}

.bills-table th,
.bills-table td {
    padding: 12px 15px;
    text-align: left;
    border-bottom: 1px solid #eee;
}

.bills-table th {
    background: #f8f9fa;
    font-weight: 600;
    font-size: 13px;
    color: #555;
}

.bill-row:hover {
    background: #f9f9f9;
}

.pending-row {
    background: #fffef5;
}

.amount {
    font-weight: 600;
    color: #28a745;
}

.status-badge {
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 500;
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

.action-buttons {
    display: flex;
    gap: 8px;
}

.btn-view, .btn-pay {
    padding: 5px 10px;
    border-radius: 5px;
    text-decoration: none;
    font-size: 11px;
    display: inline-flex;
    align-items: center;
    gap: 4px;
}

.btn-view {
    background: #e8eaf6;
    color: #3949ab;
}

.btn-view:hover {
    background: #3949ab;
    color: white;
}

.btn-pay {
    background: #d4edda;
    color: #28a745;
}

.btn-pay:hover {
    background: #28a745;
    color: white;
}

.empty-state {
    text-align: center;
    padding: 60px;
}

.empty-state i {
    font-size: 60px;
    color: #ccc;
    margin-bottom: 20px;
}

.empty-state h3 {
    margin-bottom: 10px;
    color: #333;
}

.empty-state p {
    color: #666;
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

@media (max-width: 1024px) {
    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 768px) {
    .stats-grid {
        grid-template-columns: 1fr;
    }
    
    .bills-table th,
    .bills-table td {
        padding: 10px;
        font-size: 12px;
    }
    
    .action-buttons {
        flex-direction: column;
    }
}
</style>