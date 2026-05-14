<div class="page-header">
    <h1 class="page-title">Revenue Report</h1>
    <div class="header-actions">
        <a href="<?php echo SITE_URL; ?>admin.php?action=reports&sub=export&type=revenue&period=<?php echo $period; ?>" 
           class="btn-export" target="_blank">
            <i class="fas fa-print"></i> Export / Print
        </a>
    </div>
</div>

<!-- Filter Form -->
<div class="filter-bar">
    <form method="GET" action="<?php echo SITE_URL; ?>admin.php">
        <input type="hidden" name="action" value="reports">
        <input type="hidden" name="sub" value="revenue">
        
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
                <button type="submit" class="btn-filter"><i class="fas fa-search"></i> Apply</button>
                <a href="<?php echo SITE_URL; ?>admin.php?action=reports&sub=revenue" class="btn-clear">Clear</a>
            </div>
        </div>
    </form>
</div>

<div class="reports-grid">
    <!-- Revenue Over Time -->
    <div class="report-card">
        <div class="card-header">
            <h3><i class="fas fa-chart-line"></i> Revenue Over Time</h3>
        </div>
        <div class="card-body">
            <?php if ($revenueData->num_rows > 0): ?>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Period</th>
                            <th>Invoices</th>
                            <th class="text-right">Revenue</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $total_revenue = 0;
                        while($row = $revenueData->fetch_assoc()): 
                            $total_revenue += $row['total_revenue'];
                        ?>
                        <tr>
                            <td><?php echo $row['period_label'] ?? $row['period']; ?></td>
                            <td class="text-center"><?php echo $row['invoice_count']; ?></td>
                            <td class="text-right revenue">$<?php echo number_format($row['total_revenue'], 2); ?></td>
                        </tr>
                        <?php endwhile; ?>
                        <tr class="total-row">
                            <td colspan="2"><strong>Total Revenue</strong></td>
                            <td class="text-right"><strong>$<?php echo number_format($total_revenue, 2); ?></strong></td>
                        </tr>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="text-muted">No revenue data found.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Revenue by Doctor -->
    <div class="report-card">
        <div class="card-header">
            <h3><i class="fas fa-user-md"></i> Revenue by Doctor</h3>
        </div>
        <div class="card-body">
            <?php if ($revenueByDoctor->num_rows > 0): ?>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Doctor</th>
                            <th>Specialization</th>
                            <th>Invoices</th>
                            <th class="text-right">Revenue</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = $revenueByDoctor->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['doctor_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['specialization_name']); ?></td>
                            <td class="text-center"><?php echo $row['invoice_count']; ?></td>
                            <td class="text-right">$<?php echo number_format($row['total_revenue'], 2); ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="text-muted">No revenue data by doctor.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Revenue by Specialization -->
    <div class="report-card">
        <div class="card-header">
            <h3><i class="fas fa-tags"></i> Revenue by Specialization</h3>
        </div>
        <div class="card-body">
            <?php if ($revenueBySpecialization->num_rows > 0): ?>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Specialization</th>
                            <th>Invoices</th>
                            <th class="text-right">Revenue</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = $revenueBySpecialization->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['specialization_name']); ?></td>
                            <td class="text-center"><?php echo $row['invoice_count']; ?></td>
                            <td class="text-right">$<?php echo number_format($row['total_revenue'], 2); ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="text-muted">No revenue data by specialization.</p>
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

.header-actions {
    display: flex;
    gap: 10px;
}

.btn-export {
    background: #28a745;
    color: white;
    padding: 8px 16px;
    border-radius: 6px;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
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

.filter-select,
.filter-input {
    width: 100%;
    padding: 8px 12px;
    border: 1px solid #ddd;
    border-radius: 6px;
    font-size: 13px;
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

.reports-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 25px;
}

.report-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    overflow: hidden;
}

.card-header {
    padding: 15px 20px;
    border-bottom: 1px solid #eee;
    background: #f8f9fa;
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

.text-right {
    text-align: right;
}

.text-center {
    text-align: center;
}

.revenue {
    font-weight: 600;
    color: #28a745;
}

.total-row {
    background: #f8f9fa;
    font-weight: bold;
}

.total-row td {
    padding: 12px 10px;
}

.text-muted {
    text-align: center;
    padding: 40px;
    color: #999;
}

@media (max-width: 1024px) {
    .reports-grid {
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