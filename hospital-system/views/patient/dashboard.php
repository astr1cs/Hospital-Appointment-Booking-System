<div class="dashboard-header">
    <h1>My Dashboard</h1>
    <p>Welcome back! Here's an overview of your healthcare journey.</p>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon"><i class="fas fa-calendar-check"></i></div>
        <div class="stat-info">
            <h3><?php echo $upcomingCount; ?></h3>
            <p>Upcoming Appointments</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon"><i class="fas fa-history"></i></div>
        <div class="stat-info">
            <h3><?php echo $totalAppointments; ?></h3>
            <p>Total Appointments</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon"><i class="fas fa-user-md"></i></div>
        <div class="stat-info">
            <h3>50+</h3>
            <p>Expert Doctors</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon"><i class="fas fa-star"></i></div>
        <div class="stat-info">
            <h3>4.8</h3>
            <p>Average Rating</p>
        </div>
    </div>
</div>

<div class="dashboard-grid">
    <!-- Recent Appointments -->
    <div class="dashboard-card">
        <div class="card-header">
            <h3><i class="fas fa-clock"></i> Recent Appointments</h3>
            <a href="<?php echo SITE_URL; ?>patient.php?action=appointments" class="btn-link">View All →</a>
        </div>
        <div class="card-body">
            <?php if ($recentAppointments->num_rows > 0): ?>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Doctor</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = $recentAppointments->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo date('M d, Y', strtotime($row['appointment_date'])); ?></td>
                            <td><?php echo date('h:i A', strtotime($row['appointment_time'])); ?></td>
                            <td><?php echo htmlspecialchars($row['doctor_name']); ?></td>
                            <td>
                                <span class="status-badge status-<?php echo $row['status']; ?>">
                                    <?php echo ucfirst($row['status']); ?>
                                </span>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="text-muted">No appointments yet.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Announcements -->
    <div class="dashboard-card">
        <div class="card-header">
            <h3><i class="fas fa-bullhorn"></i> Announcements</h3>
        </div>
        <div class="card-body">
            <?php if ($announcements->num_rows > 0): ?>
                <?php while($row = $announcements->fetch_assoc()): ?>
                <div class="announcement-item">
                    <div class="announcement-title">
                        <i class="fas fa-bullhorn"></i>
                        <strong><?php echo htmlspecialchars($row['title']); ?></strong>
                    </div>
                    <div class="announcement-body">
                        <?php echo nl2br(htmlspecialchars(substr($row['body'], 0, 150))); ?>
                        <?php if(strlen($row['body']) > 150): ?>...<?php endif; ?>
                    </div>
                    <div class="announcement-date">
                        <small><?php echo date('M d, Y', strtotime($row['published_at'])); ?></small>
                    </div>
                </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p class="text-muted">No announcements at this time.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
.dashboard-header {
    margin-bottom: 30px;
}

.dashboard-header h1 {
    font-size: 28px;
    color: #333;
    margin-bottom: 10px;
}

.dashboard-header p {
    color: #666;
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
    font-size: 13px;
}

.dashboard-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 25px;
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

.btn-link {
    color: #667eea;
    text-decoration: none;
    font-size: 13px;
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

.status-badge {
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 11px;
    display: inline-block;
}

.status-pending {
    background: #fff3cd;
    color: #856404;
}

.status-confirmed {
    background: #d4edda;
    color: #155724;
}

.status-completed {
    background: #cce5ff;
    color: #004085;
}

.status-cancelled {
    background: #f8d7da;
    color: #721c24;
}

.announcement-item {
    padding: 12px 0;
    border-bottom: 1px solid #eee;
}

.announcement-item:last-child {
    border-bottom: none;
}

.announcement-title {
    margin-bottom: 8px;
}

.announcement-title i {
    color: #667eea;
    margin-right: 8px;
}

.announcement-body {
    font-size: 13px;
    color: #555;
    margin-bottom: 8px;
    line-height: 1.5;
}

.announcement-date small {
    color: #999;
    font-size: 11px;
}

.text-muted {
    text-align: center;
    padding: 20px;
    color: #999;
}

@media (max-width: 1024px) {
    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .dashboard-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 768px) {
    .stats-grid {
        grid-template-columns: 1fr;
    }
}
</style>