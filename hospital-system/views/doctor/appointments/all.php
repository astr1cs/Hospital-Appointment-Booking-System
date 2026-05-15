
<div class="page-header">
    <h1 class="page-title">All Appointments</h1>
</div>

<!-- Statistics -->
<div class="stats-row">
    <div class="stat-mini"><span class="stat-label">Total</span><span class="stat-number"><?php echo $stats['total']; ?></span></div>
    <div class="stat-mini"><span class="stat-label">Pending</span><span class="stat-number"><?php echo $stats['pending']; ?></span></div>
    <div class="stat-mini"><span class="stat-label">Confirmed</span><span class="stat-number"><?php echo $stats['confirmed']; ?></span></div>
    <div class="stat-mini"><span class="stat-label">Checked In</span><span class="stat-number"><?php echo $stats['checked_in']; ?></span></div>
    <div class="stat-mini"><span class="stat-label">Completed</span><span class="stat-number"><?php echo $stats['completed']; ?></span></div>
    <div class="stat-mini"><span class="stat-label">Cancelled</span><span class="stat-number"><?php echo $stats['cancelled']; ?></span></div>
    <div class="stat-mini"><span class="stat-label">No Show</span><span class="stat-number"><?php echo $stats['no_show']; ?></span></div>
</div>

<!-- Filter Bar -->
<div class="filter-bar">
    <form method="GET" action="<?php echo SITE_URL; ?>doctor.php">
        <input type="hidden" name="action" value="appointments">
        <input type="hidden" name="sub" value="index">
        
        <div class="filter-row">
            <div class="filter-group">
                <label>Status</label>
                <select name="status" class="filter-select">
                    <option value="all" <?php echo $currentStatus == 'all' ? 'selected' : ''; ?>>All</option>
                    <option value="pending" <?php echo $currentStatus == 'pending' ? 'selected' : ''; ?>>Pending</option>
                    <option value="confirmed" <?php echo $currentStatus == 'confirmed' ? 'selected' : ''; ?>>Confirmed</option>
                    <option value="checked_in" <?php echo $currentStatus == 'checked_in' ? 'selected' : ''; ?>>Checked In</option>
                    <option value="completed" <?php echo $currentStatus == 'completed' ? 'selected' : ''; ?>>Completed</option>
                    <option value="cancelled" <?php echo $currentStatus == 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                    <option value="no_show" <?php echo $currentStatus == 'no_show' ? 'selected' : ''; ?>>No Show</option>
                </select>
            </div>
            <div class="filter-group">
                <label>From Date</label>
                <input type="date" name="date_from" class="filter-input" value="<?php echo $dateFrom; ?>">
            </div>
            <div class="filter-group">
                <label>To Date</label>
                <input type="date" name="date_to" class="filter-input" value="<?php echo $dateTo; ?>">
            </div>
            <div class="filter-actions">
                <button type="submit" class="btn-filter">Apply Filters</button>
                <a href="<?php echo SITE_URL; ?>doctor.php?action=appointments&sub=index" class="btn-clear">Clear</a>
            </div>
        </div>
    </form>
</div>

<div class="appointments-table-container">
    <?php if ($appointments->num_rows > 0): ?>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Patient</th>
                    <th>Contact</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $appointments->fetch_assoc()): ?>
                <tr>
                    <td><?php echo date('M d, Y', strtotime($row['appointment_date'])); ?></td>
                    <td><?php echo date('h:i A', strtotime($row['appointment_time'])); ?></td>
                    <td><strong><?php echo htmlspecialchars($row['patient_name']); ?></strong></td>
                    <td><?php echo htmlspecialchars($row['patient_phone']); ?></td>
                    <td><span class="status-badge status-<?php echo $row['status']; ?>"><?php echo ucfirst($row['status']); ?></span></td>
                    <td>
                        <a href="<?php echo SITE_URL; ?>doctor.php?action=appointments&sub=show&id=<?php echo $row['id']; ?>" class="btn-view">View</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="empty-state">
            <i class="fas fa-calendar"></i>
            <h3>No Appointments Found</h3>
            <p>No appointments match your criteria.</p>
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
.stats-row {
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
    margin-bottom: 25px;
}
.stat-mini {
    background: white;
    padding: 10px 20px;
    border-radius: 8px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}
.stat-label {
    font-size: 12px;
    color: #666;
    margin-right: 10px;
}
.stat-number {
    font-size: 20px;
    font-weight: bold;
    color: #667eea;
}
.filter-bar {
    background: white;
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 25px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}
.filter-row {
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
    align-items: flex-end;
}
.filter-group {
    flex: 1;
    min-width: 150px;
}
.filter-group label {
    display: block;
    margin-bottom: 5px;
    font-size: 12px;
    font-weight: 500;
    color: #555;
}
.filter-select, .filter-input {
    width: 100%;
    padding: 8px 12px;
    border: 1px solid #ddd;
    border-radius: 6px;
}
.btn-filter {
    background: #667eea;
    color: white;
    border: none;
    padding: 8px 20px;
    border-radius: 6px;
    cursor: pointer;
}
.btn-clear {
    background: #6c757d;
    color: white;
    padding: 8px 16px;
    border-radius: 6px;
    text-decoration: none;
    display: inline-block;
}
.data-table {
    width: 100%;
    border-collapse: collapse;
    background: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}
.data-table th, .data-table td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid #eee;
}
.data-table th {
    background: #f8f9fa;
    font-weight: 600;
}
.status-badge {
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 11px;
}
.status-pending { background: #fff3cd; color: #856404; }
.status-confirmed { background: #d4edda; color: #155724; }
.status-checked_in { background: #cce5ff; color: #004085; }
.status-completed { background: #d1ecf1; color: #0c5460; }
.status-cancelled { background: #f8d7da; color: #721c24; }
.status-no_show { background: #e2d5f8; color: #4a1d6d; }
.btn-view {
    background: #e8eaf6;
    color: #3949ab;
    padding: 5px 12px;
    border-radius: 5px;
    text-decoration: none;
    font-size: 12px;
}
.empty-state {
    text-align: center;
    padding: 60px;
    background: white;
    border-radius: 12px;
}
.empty-state i {
    font-size: 60px;
    color: #ccc;
    margin-bottom: 20px;
}
@media (max-width: 768px) {
    .filter-row { flex-direction: column; }
    .data-table { font-size: 12px; }
    .data-table th, .data-table td { padding: 8px; }
}
</style>