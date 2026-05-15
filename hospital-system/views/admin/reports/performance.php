<div class="page-header">
    <h1 class="page-title">Doctor Performance Report</h1>
    <a href="<?php echo SITE_URL; ?>admin.php?action=reports&sub=export&type=performance" class="btn-export" target="_blank">
        <i class="fas fa-print"></i> Export / Print
    </a>
</div>

<div class="report-card">
    <div class="card-header">
        <h3><i class="fas fa-chart-bar"></i> Doctor Performance Metrics</h3>
    </div>
    <div class="card-body">
        <?php if ($performanceData->num_rows > 0): ?>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Doctor</th>
                        <th>Specialization</th>
                        <th>Total Appointments</th>
                        <th>Completed</th>
                        <th>Cancelled</th>
                        <th>No Show</th>
                        <th>No Show Rate</th>
                        <th>Avg Rating</th>
                        <th>Reviews</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = $performanceData->fetch_assoc()): 
                        $rating_class = '';
                        if ($row['avg_rating'] >= 4.5) $rating_class = 'rating-excellent';
                        elseif ($row['avg_rating'] >= 3.5) $rating_class = 'rating-good';
                        elseif ($row['avg_rating'] >= 2.5) $rating_class = 'rating-average';
                        elseif ($row['avg_rating'] > 0) $rating_class = 'rating-poor';
                        
                        $no_show_class = $row['no_show_rate'] > 10 ? 'text-danger' : ($row['no_show_rate'] > 5 ? 'text-warning' : 'text-success');
                    ?>
                    <tr>
                        <td><strong><?php echo htmlspecialchars($row['doctor_name']); ?></strong></td>
                        <td><?php echo htmlspecialchars($row['specialization_name']); ?></td>
                        <td class="text-center"><?php echo $row['total_appointments']; ?></td>
                        <td class="text-center text-success"><?php echo $row['completed_appointments']; ?></td>
                        <td class="text-center text-danger"><?php echo $row['cancelled_appointments']; ?></td>
                        <td class="text-center text-warning"><?php echo $row['no_show_appointments']; ?></td>
                        <td class="text-center <?php echo $no_show_class; ?>"><?php echo $row['no_show_rate']; ?>%</td>
                        <td class="text-center">
                            <?php if ($row['avg_rating'] > 0): ?>
                                <span class="rating-badge <?php echo $rating_class; ?>">
                                    <?php echo number_format($row['avg_rating'], 1); ?> <i class="fas fa-star"></i>
                                </span>
                            <?php else: ?>
                                <span class="text-muted">No ratings</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-center"><?php echo $row['total_reviews']; ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="text-muted">No performance data found.</p>
        <?php endif; ?>
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
    overflow-x: auto;
}

.data-table {
    width: 100%;
    border-collapse: collapse;
    min-width: 800px;
}

.data-table th,
.data-table td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid #eee;
    font-size: 13px;
}

.data-table th {
    background: #f8f9fa;
    font-weight: 600;
}

.text-center {
    text-align: center;
}

.text-success {
    color: #28a745;
    font-weight: 500;
}

.text-danger {
    color: #dc3545;
    font-weight: 500;
}

.text-warning {
    color: #ffc107;
    font-weight: 500;
}

.rating-badge {
    display: inline-block;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 500;
}

.rating-excellent {
    background: #d4edda;
    color: #155724;
}

.rating-good {
    background: #cce5ff;
    color: #004085;
}

.rating-average {
    background: #fff3cd;
    color: #856404;
}

.rating-poor {
    background: #f8d7da;
    color: #721c24;
}

.text-muted {
    text-align: center;
    padding: 40px;
    color: #999;
}
</style>