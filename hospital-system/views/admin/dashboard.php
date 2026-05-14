<div class="dashboard-stats">
    <div class="stat-card">
        <div class="stat-icon"><i class="fas fa-calendar-day"></i></div>
        <div class="stat-info">
            <h3>0</h3>
            <p>Today's Appointments</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon"><i class="fas fa-users"></i></div>
        <div class="stat-info">
            <h3>0</h3>
            <p>Total Patients</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon"><i class="fas fa-user-md"></i></div>
        <div class="stat-info">
            <h3>0</h3>
            <p>Active Doctors</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon"><i class="fas fa-dollar-sign"></i></div>
        <div class="stat-info">
            <h3>$0</h3>
            <p>Pending Billings</p>
        </div>
    </div>
</div>

<div class="admin-grid">
    <div class="admin-card">
        <div class="card-header">
            <h3><i class="fas fa-clock"></i> Recent Appointments</h3>
            <a href="<?php echo SITE_URL; ?>admin.php?action=appointments" class="btn-link">View All →</a>
        </div>
        <div class="card-body">
            <p class="text-muted">No recent appointments found.</p>
        </div>
    </div>

    <div class="admin-card">
        <div class="card-header">
            <h3><i class="fas fa-user-md"></i> Pending Doctor Approvals</h3>
            <a href="<?php echo SITE_URL; ?>admin.php?action=doctors&sub=pending" class="btn-link">View All →</a>
        </div>
        <div class="card-body">
            <p class="text-muted">No pending approvals.</p>
        </div>
    </div>
</div>