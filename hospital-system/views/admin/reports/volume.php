<div class="page-header">
    <h1 class="page-title">Appointment Volume Report</h1>
    <a href="<?php echo SITE_URL; ?>admin.php?action=reports&sub=export&type=volume" class="btn-export" target="_blank">
        <i class="fas fa-print"></i> Export / Print
    </a>
</div>

<div class="reports-grid">
    <!-- Top Doctors by Volume -->
    <div class="report-card">
        <div class="card-header">
            <h3><i class="fas fa-trophy"></i> Busiest Doctors</h3>
        </div>
        <div class="card-body">
            <?php if ($volumeData->num_rows > 0): ?>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Doctor</th>
                            <th>Specialization</th>
                            <th>Total</th>
                            <th>Completed</th>
                            <th>Cancelled</th>
                            <th>Completion Rate</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = $volumeData->fetch_assoc()): ?>
                        <tr class="<?php echo $row['total_appointments'] > 0 ? 'highlight' : ''; ?>">
                            <td><strong><?php echo htmlspecialchars($row['doctor_name']); ?></strong></td>
                            <td><?php echo htmlspecialchars($row['specialization_name']); ?></td>
                            <td class="text-center"><?php echo $row['total_appointments']; ?></td>
                            <td class="text-center text-success"><?php echo $row['completed']; ?></td>
                            <td class="text-center text-danger"><?php echo $row['cancelled']; ?></td>
                            <td class="text-center"><?php echo $row['completion_rate']; ?>%</td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="text-muted">No appointment data found.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Peak Hours -->
    <div class="report-card">
        <div class="card-header">
            <h3><i class="fas fa-clock"></i> Peak Hours</h3>
        </div>
        <div class="card-body">
            <?php if ($peakHours->num_rows > 0): ?>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Hour</th>
                            <th>Appointments</th>
                            <th>Percentage</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $total = 0;
                        $hours = [];
                        while($row = $peakHours->fetch_assoc()) {
                            $total += $row['appointment_count'];
                            $hours[] = $row;
                        }
                        foreach($hours as $row):
                            $percent = $total > 0 ? round($row['appointment_count'] * 100 / $total, 1) : 0;
                        ?>
                        <tr>
                            <td><?php echo date('g:i A', mktime($row['hour'], 0)); ?> - <?php echo date('g:i A', mktime($row['hour'] + 1, 0)); ?>
                            <td><?php echo $row['appointment_count']; ?></td>
                            <td>
                                <div class="progress-bar">
                                    <div class="progress" style="width: <?php echo $percent; ?>%"></div>
                                    <span class="percent"><?php echo $percent; ?>%</span>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="text-muted">No peak hours data.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Peak Days -->
    <div class="report-card">
        <div class="card-header">
            <h3><i class="fas fa-calendar-week"></i> Busiest Days</h3>
        </div>
        <div class="card-body">
            <?php if ($peakDays->num_rows > 0): ?>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Day</th>
                            <th>Appointments</th>
                            <th>Percentage</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $total = 0;
                        $days = [];
                        while($row = $peakDays->fetch_assoc()) {
                            $total += $row['appointment_count'];
                            $days[] = $row;
                        }
                        foreach($days as $row):
                            $percent = $total > 0 ? round($row['appointment_count'] * 100 / $total, 1) : 0;
                        ?>
                        <tr>
                            <td><?php echo $row['day_name']; ?></td>
                            <td class="text-center"><?php echo $row['appointment_count']; ?></td>
                            <td>
                                <div class="progress-bar">
                                    <div class="progress" style="width: <?php echo $percent; ?>%"></div>
                                    <span class="percent"><?php echo $percent; ?>%</span>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="text-muted">No peak days data.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Top Specializations -->
    <div class="report-card">
        <div class="card-header">
            <h3><i class="fas fa-star"></i> Most In-Demand Specializations</h3>
        </div>
        <div class="card-body">
            <?php if ($topSpecializations->num_rows > 0): ?>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Specialization</th>
                            <th>Appointments</th>
                            <th>Popularity</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $total = 0;
                        $specs = [];
                        while($row = $topSpecializations->fetch_assoc()) {
                            $total += $row['appointment_count'];
                            $specs[] = $row;
                        }
                        foreach($specs as $row):
                            $percent = $total > 0 ? round($row['appointment_count'] * 100 / $total, 1) : 0;
                        ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['specialization_name']); ?></td>
                            <td class="text-center"><?php echo $row['appointment_count']; ?></td>
                            <td>
                                <div class="progress-bar">
                                    <div class="progress" style="width: <?php echo $percent; ?>%"></div>
                                    <span class="percent"><?php echo $percent; ?>%</span>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="text-muted">No specialization data.</p>
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

.highlight {
    background: #e8eaf6;
}

.progress-bar {
    position: relative;
    width: 150px;
    background: #e9ecef;
    border-radius: 10px;
    overflow: hidden;
    display: inline-block;
    vertical-align: middle;
}

.progress {
    height: 20px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 10px;
}

.percent {
    position: absolute;
    left: 50%;
    transform: translateX(-50%);
    font-size: 11px;
    line-height: 20px;
    color: #333;
    font-weight: 500;
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
}
</style>