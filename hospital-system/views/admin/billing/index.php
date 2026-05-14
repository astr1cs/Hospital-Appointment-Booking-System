<div class="page-header">
    <h1 class="page-title">Billing Dashboard</h1>
</div>

<?php if (isset($success) && $success): ?>
    <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
<?php endif; ?>

<?php if (isset($error) && $error): ?>
    <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>

<div class="stats-row">
    <div class="stat-card">
        <div class="stat-icon"><i class="fas fa-dollar-sign"></i></div>
        <div class="stat-info">
            <h3>$<?php echo number_format($stats['total_revenue'], 2); ?></h3>
            <p>Total Revenue</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
        <div class="stat-info">
            <h3>$<?php echo number_format($stats['total_paid'], 2); ?></h3>
            <p>Paid Amount</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon"><i class="fas fa-clock"></i></div>
        <div class="stat-info">
            <h3>$<?php echo number_format($stats['total_pending'], 2); ?></h3>
            <p>Pending Amount</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon"><i class="fas fa-chart-line"></i></div>
        <div class="stat-info">
            <h3><?php echo $stats['count_paid']; ?></h3>
            <p>Paid Transactions</p>
        </div>
    </div>
</div>

<div class="billing-grid">
    <div class="admin-card">
        <div class="card-header">
            <h3><i class="fas fa-hourglass-half"></i> Pending Bills</h3>
            <span class="badge"><?php echo $pendingBills->num_rows; ?> pending</span>
        </div>
        <div class="card-body">
            <?php if ($pendingBills->num_rows > 0): ?>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Patient</th>
                            <th>Doctor</th>
                            <th>Date</th>
                            <th>Amount</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = $pendingBills->fetch_assoc()): ?>
                        <tr>
                            <td>
                                <strong><?php echo htmlspecialchars($row['patient_name']); ?></strong><br>
                                <small><?php echo htmlspecialchars($row['patient_email']); ?></small>
                            </td>
                            <td><?php echo htmlspecialchars($row['doctor_name']); ?></td>
                            <td><?php echo date('M d, Y', strtotime($row['appointment_date'])); ?></td>
                            <td class="amount">$<?php echo number_format($row['amount'], 2); ?></td>
                            <td>
                                <a href="<?php echo SITE_URL; ?>admin.php?action=billing&sub=markPaid&id=<?php echo $row['id']; ?>" 
                                   class="btn-sm btn-success" 
                                   onclick="return confirm('Mark this bill as paid?')">
                                    <i class="fas fa-check"></i> Mark Paid
                                </a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="text-muted">No pending bills.</p>
            <?php endif; ?>
        </div>
    </div>

    <div class="admin-card">
        <div class="card-header">
            <h3><i class="fas fa-history"></i> Recent Transactions</h3>
        </div>
        <div class="card-body">
            <?php if ($recentTransactions->num_rows > 0): ?>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Patient</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = $recentTransactions->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['patient_name']); ?></td>
                            <td class="amount">$<?php echo number_format($row['amount'], 2); ?></td>
                            <td>
                                <span class="status-badge status-<?php echo $row['payment_status']; ?>">
                                    <?php echo ucfirst($row['payment_status']); ?>
                                </span>
                            </td>
                            <td><?php echo date('M d, Y', strtotime($row['created_at'])); ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="text-muted">No transactions found.</p>
            <?php endif; ?>
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

.stats-row {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 25px;
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
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.stat-icon i {
    font-size: 28px;
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

.billing-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 25px;
}

.admin-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    overflow: hidden;
}

.card-header {
    padding: 15px 20px;
    border-bottom: 1px solid #eee;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.card-header h3 {
    margin: 0;
    font-size: 16px;
}

.card-body {
    padding: 20px;
}

.data-table {
    width: 100%;
    border-collapse: collapse;
}

.data-table th,
.data-table td {
    padding: 10px;
    text-align: left;
    border-bottom: 1px solid #eee;
    font-size: 13px;
}

.data-table th {
    background: #f8f9fa;
    font-weight: 600;
}

.amount {
    font-weight: 600;
    color: #28a745;
}

.badge {
    background: #e9ecef;
    color: #495057;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 12px;
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

.btn-sm {
    padding: 5px 12px;
    border-radius: 4px;
    text-decoration: none;
    font-size: 11px;
    display: inline-block;
}

.btn-success {
    background: #28a745;
    color: white;
}

.btn-success:hover {
    background: #218838;
    color: white;
}

.text-muted {
    text-align: center;
    padding: 20px;
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

.alert-danger {
    background: #f8d7da;
    color: #721c24;
    border-left: 4px solid #dc3545;
}

@media (max-width: 1024px) {
    .stats-row {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .billing-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 768px) {
    .stats-row {
        grid-template-columns: 1fr;
    }
}
</style>