<div class="page-header">
    <h1 class="page-title">Earnings Report</h1>
</div>

<!-- Summary Cards -->
<div class="summary-cards">
    <div class="summary-card">
        <div class="summary-icon"><i class="fas fa-dollar-sign"></i></div>
        <div class="summary-info">
            <h3>$<?php echo number_format($summary['total_earnings'] ?? 0, 2); ?></h3>
            <p>Total Earnings</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="summary-icon"><i class="fas fa-calendar-check"></i></div>
        <div class="summary-info">
            <h3><?php echo $summary['total_appointments'] ?? 0; ?></h3>
            <p>Paid Appointments</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="summary-icon"><i class="fas fa-chart-line"></i></div>
        <div class="summary-info">
            <h3>$<?php echo number_format($summary['avg_earning'] ?? 0, 2); ?></h3>
            <p>Average per Visit</p>
        </div>
    </div>
</div>

<!-- Filter Bar -->
<div class="filter-bar">
    <form method="GET" action="<?php echo SITE_URL; ?>doctor.php">
        <input type="hidden" name="action" value="reports">
        <input type="hidden" name="sub" value="earnings">
        
        <div class="filter-row">
            <div class="filter-group">
                <label>Period</label>
                <select name="period" class="filter-select">
                    <option value="day" <?php echo $period == 'day' ? 'selected' : ''; ?>>Daily</option>
                    <option value="week" <?php echo $period == 'week' ? 'selected' : ''; ?>>Weekly</option>
                    <option value="month" <?php echo $period == 'month' ? 'selected' : ''; ?>>Monthly</option>
                </select>
            </div>
            <div class="filter-group">
                <label>Start Date</label>
                <input type="date" name="start_date" class="filter-input" value="<?php echo $startDate; ?>">
            </div>
            <div class="filter-group">
                <label>End Date</label>
                <input type="date" name="end_date" class="filter-input" value="<?php echo $endDate; ?>">
            </div>
            <div class="filter-actions">
                <button type="submit" class="btn-filter">Apply</button>
                <a href="<?php echo SITE_URL; ?>doctor.php?action=reports&sub=earnings" class="btn-clear">Clear</a>
            </div>
        </div>
    </form>
</div>

<!-- Earnings Table -->
<div class="earnings-table-container">
    <?php if ($earnings->num_rows > 0): ?>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Period</th>
                    <th>Appointments</th>
                    <th class="text-right">Earnings</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $totalAppointments = 0;
                $totalEarnings = 0;
                while($row = $earnings->fetch_assoc()): 
                    $totalAppointments += $row['appointments_count'];
                    $totalEarnings += $row['total_earnings'];
                ?>
                <tr>
                    <td><?php echo $row['period']; ?></td>
                    <td class="text-center"><?php echo $row['appointments_count']; ?></td>
                    <td class="text-right earnings">$<?php echo number_format($row['total_earnings'], 2); ?></td>
                </tr>
                <?php endwhile; ?>
                <tr class="total-row">
                    <td><strong>Total</strong></td>
                    <td class="text-center"><strong><?php echo $totalAppointments; ?></strong></td>
                    <td class="text-right"><strong>$<?php echo number_format($totalEarnings, 2); ?></strong></td>
                </tr>
            </tbody>
        </table>
    <?php else: ?>
        <div class="empty-state">
            <i class="fas fa-chart-line"></i>
            <h3>No Earnings Data</h3>
            <p>No paid appointments found for the selected period.</p>
        </div>
    <?php endif; ?>
</div>

<!-- Monthly Stats -->
<div class="monthly-stats">
    <h3>Last 6 Months Performance</h3>
    <div class="stats-grid">
        <?php while($row = $monthlyStats->fetch_assoc()): ?>
        <div class="stat-item">
            <div class="stat-month"><?php echo date('F Y', strtotime($row['month'] . '-01')); ?></div>
            <div class="stat-data">
                <span class="stat-appointments"><?php echo $row['appointments']; ?> appointments</span>
                <span class="stat-earnings">$<?php echo number_format($row['earnings'], 2); ?></span>
            </div>
            <div class="progress-bar">
                <div class="progress" style="width: <?php echo min(100, ($row['earnings'] / max($totalEarnings, 1)) * 100); ?>%"></div>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
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
.summary-cards {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 25px;
    margin-bottom: 30px;
}
.summary-card {
    background: white;
    border-radius: 12px;
    padding: 20px;
    display: flex;
    align-items: center;
    gap: 15px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}
.summary-icon {
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
}
.summary-icon i {
    font-size: 28px;
    color: white;
}
.summary-info h3 {
    font-size: 28px;
    margin: 0 0 5px 0;
}
.summary-info p {
    color: #666;
    margin: 0;
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
.earnings-table-container {
    background: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    margin-bottom: 30px;
}
.data-table {
    width: 100%;
    border-collapse: collapse;
}
.data-table th, .data-table td {
    padding: 12px 15px;
    text-align: left;
    border-bottom: 1px solid #eee;
}
.data-table th {
    background: #f8f9fa;
    font-weight: 600;
}
.text-right {
    text-align: right;
}
.text-center {
    text-align: center;
}
.earnings {
    color: #28a745;
    font-weight: 600;
}
.total-row {
    background: #f8f9fa;
    font-weight: bold;
}
.monthly-stats {
    background: white;
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}
.monthly-stats h3 {
    margin: 0 0 20px 0;
    color: #333;
}
.stats-grid {
    display: flex;
    flex-direction: column;
    gap: 15px;
}
.stat-item {
    padding: 10px;
    border-bottom: 1px solid #eee;
}
.stat-month {
    font-weight: 600;
    color: #667eea;
    margin-bottom: 5px;
}
.stat-data {
    display: flex;
    justify-content: space-between;
    margin-bottom: 8px;
    font-size: 14px;
}
.stat-appointments {
    color: #666;
}
.stat-earnings {
    color: #28a745;
    font-weight: 600;
}
.progress-bar {
    height: 6px;
    background: #e9ecef;
    border-radius: 3px;
    overflow: hidden;
}
.progress {
    height: 100%;
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    border-radius: 3px;
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
@media (max-width: 768px) {
    .summary-cards {
        grid-template-columns: 1fr;
    }
    .filter-row {
        flex-direction: column;
    }
    .filter-group {
        width: 100%;
    }
}
</style>