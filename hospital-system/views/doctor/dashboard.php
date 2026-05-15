<div class="dashboard-header">
    <h1>Doctor Dashboard</h1>
    <p>Welcome to your practice management portal</p>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon"><i class="fas fa-calendar-day"></i></div>
        <div class="stat-info">
            <h3><?php echo $todayCount; ?></h3>
            <p>Today's Appointments</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon"><i class="fas fa-users"></i></div>
        <div class="stat-info">
            <h3><?php echo $totalPatients; ?></h3>
            <p>Total Patients</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
        <div class="stat-info">
            <h3><?php echo $completedCount; ?></h3>
            <p>Completed</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon"><i class="fas fa-dollar-sign"></i></div>
        <div class="stat-info">
            <h3>$<?php echo number_format($totalEarnings, 2); ?></h3>
            <p>Total Earnings</p>
        </div>
    </div>
</div>

<div class="dashboard-card">
    <div class="card-header">
        <h3><i class="fas fa-clock"></i> Recent Appointments</h3>
        <a href="<?php echo SITE_URL; ?>doctor.php?action=appointments" class="btn-link">View All →</a>
    </div>
    <div class="card-body">
        <?php if ($recentAppointments->num_rows > 0): ?>
            <table class="data-table">
                <thead>
                    <tr><th>Patient</th><th>Date</th><th>Time</th><th>Status</th><th>Action</th></tr>
                </thead>
                <tbody>
                    <?php while($row = $recentAppointments->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['patient_name']); ?></td>
                        <td><?php echo date('M d, Y', strtotime($row['appointment_date'])); ?></td>
                        <td><?php echo date('h:i A', strtotime($row['appointment_time'])); ?></td>
                        <td><span class="status-badge status-<?php echo $row['status']; ?>"><?php echo ucfirst($row['status']); ?></span></td>
                        <td><a href="<?php echo SITE_URL; ?>doctor.php?action=appointments&sub=view&id=<?php echo $row['id']; ?>" class="btn-sm">View</a></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="text-muted">No recent appointments</p>
        <?php endif; ?>
    </div>
</div>

<style>
.dashboard-header {
    margin-bottom: 30px;
}
.dashboard-header h1 {
    font-size: 28px;
    margin: 0 0 10px 0;
    color: #333;
}
.stats-grid {
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
    font-size: 28px;
    margin: 0 0 5px 0;
}
.stat-info p {
    color: #666;
    margin: 0;
}
.dashboard-card {
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
.data-table {
    width: 100%;
    border-collapse: collapse;
}
.data-table th, .data-table td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid #eee;
}
.status-badge {
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 11px;
}
.status-pending { background: #fff3cd; color: #856404; }
.status-confirmed { background: #d4edda; color: #155724; }
.status-completed { background: #cce5ff; color: #004085; }
.btn-sm {
    background: #667eea;
    color: white;
    padding: 4px 10px;
    border-radius: 4px;
    text-decoration: none;
    font-size: 12px;
}
@media (max-width: 1024px) {
    .stats-grid { grid-template-columns: repeat(2, 1fr); }
}
@media (max-width: 768px) {
    .stats-grid { grid-template-columns: 1fr; }
}
</style>