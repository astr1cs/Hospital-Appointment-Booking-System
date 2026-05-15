<div class="page-header">
    <h1 class="page-title">Daily Report</h1>
</div>

<!-- Date Selection -->
<div class="date-filter">
    <form method="GET" action="<?php echo SITE_URL; ?>receptionist.php">
        <input type="hidden" name="action" value="reports">
        <input type="hidden" name="sub" value="daily">
        
        <div class="filter-row">
            <div class="filter-group">
                <label>Select Date</label>
                <input type="date" name="date" class="filter-input" value="<?php echo $date; ?>">
            </div>
            <div class="filter-actions">
                <button type="submit" class="btn-view">View Report</button>
                <a href="<?php echo SITE_URL; ?>receptionist.php?action=reports&sub=export&date=<?php echo $date; ?>" 
                   class="btn-export" target="_blank">Export / Print</a>
            </div>
        </div>
    </form>
</div>

<!-- Summary Cards -->
<div class="summary-cards">
    <div class="summary-card">
        <div class="summary-icon"><i class="fas fa-calendar-check"></i></div>
        <div class="summary-info">
            <h3><?php echo $stats['total']; ?></h3>
            <p>Total Appointments</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="summary-icon"><i class="fas fa-user-check"></i></div>
        <div class="summary-info">
            <h3><?php echo $stats['confirmed']; ?></h3>
            <p>Confirmed</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="summary-icon"><i class="fas fa-hourglass-half"></i></div>
        <div class="summary-info">
            <h3><?php echo $stats['pending']; ?></h3>
            <p>Pending</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="summary-icon"><i class="fas fa-check-double"></i></div>
        <div class="summary-info">
            <h3><?php echo $stats['completed']; ?></h3>
            <p>Completed</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="summary-icon"><i class="fas fa-times-circle"></i></div>
        <div class="summary-info">
            <h3><?php echo $stats['cancelled']; ?></h3>
            <p>Cancelled</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="summary-icon"><i class="fas fa-dollar-sign"></i></div>
        <div class="summary-info">
            <h3>$<?php echo number_format($revenue['total_revenue'], 2); ?></h3>
            <p>Total Revenue</p>
        </div>
    </div>
</div>

<!-- Appointments by Doctor -->
<div class="report-section">
    <h2><i class="fas fa-user-md"></i> Appointments by Doctor</h2>
    
    <?php if (count($grouped) > 0): ?>
        <?php foreach($grouped as $doctorId => $doctor): ?>
        <div class="doctor-report-card">
            <div class="doctor-report-header">
                <h3>Dr. <?php echo htmlspecialchars($doctor['doctor_name']); ?></h3>
                <span class="badge"><?php echo count($doctor['appointments']); ?> appointments</span>
            </div>
            <div class="report-table-container">
                <table class="report-table">
                    <thead>
                        <tr>
                            <th>Time</th>
                            <th>Patient</th>
                            <th>Phone</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($doctor['appointments'] as $apt): ?>
                        <tr>
                            <td><?php echo date('h:i A', strtotime($apt['appointment_time'])); ?></td>
                            <td><?php echo htmlspecialchars($apt['patient_name']); ?></td>
                            <td><?php echo htmlspecialchars($apt['patient_phone']); ?></td>
                            <td>
                                <span class="status-badge status-<?php echo $apt['status']; ?>">
                                    <?php echo ucfirst($apt['status']); ?>
                                </span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="empty-state">
            <i class="fas fa-calendar-day"></i>
            <p>No appointments found for <?php echo date('F d, Y', strtotime($date)); ?></p>
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
.date-filter {
    background: white;
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 25px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}
.filter-row {
    display: flex;
    gap: 15px;
    align-items: flex-end;
    flex-wrap: wrap;
}
.filter-group {
    flex: 1;
    min-width: 200px;
}
.filter-group label {
    display: block;
    margin-bottom: 5px;
    font-size: 12px;
    font-weight: 500;
    color: #555;
}
.filter-input {
    width: 100%;
    padding: 10px 12px;
    border: 1px solid #ddd;
    border-radius: 8px;
}
.btn-view, .btn-export {
    padding: 10px 24px;
    border-radius: 8px;
    text-decoration: none;
    display: inline-block;
}
.btn-view {
    background: #667eea;
    color: white;
    border: none;
    cursor: pointer;
}
.btn-export {
    background: #28a745;
    color: white;
}
.summary-cards {
    display: grid;
    grid-template-columns: repeat(6, 1fr);
    gap: 15px;
    margin-bottom: 30px;
}
.summary-card {
    background: white;
    border-radius: 10px;
    padding: 15px;
    text-align: center;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}
.summary-icon {
    font-size: 28px;
    color: #667eea;
    margin-bottom: 8px;
}
.summary-info h3 {
    font-size: 24px;
    margin: 0 0 5px 0;
}
.summary-info p {
    color: #666;
    margin: 0;
    font-size: 11px;
}
.report-section {
    background: white;
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}
.report-section h2 {
    margin: 0 0 20px 0;
    font-size: 18px;
    padding-bottom: 10px;
    border-bottom: 1px solid #eee;
}
.doctor-report-card {
    margin-bottom: 25px;
    border: 1px solid #eee;
    border-radius: 8px;
    overflow: hidden;
}
.doctor-report-header {
    background: #f8f9fa;
    padding: 12px 15px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 1px solid #eee;
}
.doctor-report-header h3 {
    margin: 0;
    font-size: 16px;
}
.badge {
    background: #667eea;
    color: white;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 11px;
}
.report-table {
    width: 100%;
    border-collapse: collapse;
}
.report-table th,
.report-table td {
    padding: 10px;
    text-align: left;
    border-bottom: 1px solid #eee;
}
.report-table th {
    background: #f8f9fa;
    font-weight: 600;
}
.status-badge {
    padding: 3px 8px;
    border-radius: 12px;
    font-size: 11px;
}
.status-pending { background: #fff3cd; color: #856404; }
.status-confirmed { background: #d4edda; color: #155724; }
.status-checked_in { background: #cce5ff; color: #004085; }
.status-completed { background: #d1ecf1; color: #0c5460; }
.status-cancelled { background: #f8d7da; color: #721c24; }
.empty-state {
    text-align: center;
    padding: 40px;
    color: #999;
}
@media (max-width: 1024px) {
    .summary-cards {
        grid-template-columns: repeat(3, 1fr);
    }
}
@media (max-width: 768px) {
    .summary-cards {
        grid-template-columns: repeat(2, 1fr);
    }
    .filter-row {
        flex-direction: column;
    }
    .btn-view, .btn-export {
        width: 100%;
        text-align: center;
    }
}
</style>