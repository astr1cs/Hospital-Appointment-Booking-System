<div class="dashboard-header">
    <h1>Reception Dashboard</h1>
    <p>Welcome to the front desk management portal</p>
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
        <div class="stat-icon"><i class="fas fa-user-check"></i></div>
        <div class="stat-info">
            <h3><?php echo $checkedInCount; ?></h3>
            <p>Checked In</p>
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
            <h3><?php echo $pendingPayments; ?></h3>
            <p>Pending Payments</p>
        </div>
    </div>
</div>

<div class="quick-actions">
    <div class="action-card">
        <i class="fas fa-user-plus"></i>
        <h3>Register New Patient</h3>
        <p>Add a new patient to the system</p>
        <a href="<?php echo SITE_URL; ?>receptionist.php?action=patients&sub=register" class="btn-action">Register</a>
    </div>
    <div class="action-card">
        <i class="fas fa-calendar-plus"></i>
        <h3>Walk-in Booking</h3>
        <p>Book appointment for walk-in patient</p>
        <a href="<?php echo SITE_URL; ?>receptionist.php?action=appointments&sub=book" class="btn-action">Book Now</a>
    </div>
    <div class="action-card">
        <i class="fas fa-search"></i>
        <h3>Search Patient</h3>
        <p>Find patient records</p>
        <a href="<?php echo SITE_URL; ?>receptionist.php?action=patients&sub=search" class="btn-action">Search</a>
    </div>
    <div class="action-card">
        <i class="fas fa-chart-line"></i>
        <h3>Daily Report</h3>
        <p>View today's summary report</p>
        <a href="<?php echo SITE_URL; ?>receptionist.php?action=reports&sub=daily" class="btn-action">View Report</a>
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
    margin-bottom: 40px;
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
.quick-actions {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 25px;
}
.action-card {
    background: white;
    border-radius: 12px;
    padding: 25px;
    text-align: center;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    transition: transform 0.2s;
}
.action-card:hover {
    transform: translateY(-3px);
}
.action-card i {
    font-size: 40px;
    color: #667eea;
    margin-bottom: 15px;
}
.action-card h3 {
    margin: 0 0 10px 0;
    font-size: 18px;
}
.action-card p {
    color: #666;
    font-size: 13px;
    margin-bottom: 15px;
}
.btn-action {
    display: inline-block;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 8px 20px;
    border-radius: 6px;
    text-decoration: none;
    font-size: 13px;
}
@media (max-width: 1024px) {
    .stats-grid, .quick-actions {
        grid-template-columns: repeat(2, 1fr);
    }
}
@media (max-width: 768px) {
    .stats-grid, .quick-actions {
        grid-template-columns: 1fr;
    }
}
</style>