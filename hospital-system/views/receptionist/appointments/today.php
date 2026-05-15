<div class="page-header">
    <h1 class="page-title">Today's Schedule</h1>
    <p><?php echo date('l, F d, Y'); ?></p>
</div>

<!-- Statistics Summary -->
<div class="stats-row">
    <div class="stat-mini">
        <span class="stat-label">Total</span>
        <span class="stat-number"><?php echo $stats['total']; ?></span>
    </div>
    <div class="stat-mini">
        <span class="stat-label">Pending</span>
        <span class="stat-number"><?php echo $stats['pending']; ?></span>
    </div>
    <div class="stat-mini">
        <span class="stat-label">Confirmed</span>
        <span class="stat-number"><?php echo $stats['confirmed']; ?></span>
    </div>
    <div class="stat-mini">
        <span class="stat-label">Checked In</span>
        <span class="stat-number"><?php echo $stats['checked_in']; ?></span>
    </div>
    <div class="stat-mini">
        <span class="stat-label">Completed</span>
        <span class="stat-number"><?php echo $stats['completed']; ?></span>
    </div>
    <div class="stat-mini">
        <span class="stat-label">Cancelled</span>
        <span class="stat-number"><?php echo $stats['cancelled']; ?></span>
    </div>
</div>

<?php if (isset($success) && $success): ?>
<div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
<?php endif; ?>

<?php if (isset($error) && $error): ?>
<div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>

<!-- Schedule by Doctor -->
<div class="schedule-container">
    <?php if (count($grouped) > 0): ?>
    <?php foreach($grouped as $doctorId => $doctor): ?>
    <div class="doctor-schedule-card">
        <div class="doctor-header">
            <h3><i class="fas fa-user-md"></i> Dr. <?php echo htmlspecialchars($doctor['doctor_name']); ?></h3>
            <span class="appointment-count"><?php echo count($doctor['appointments']); ?> appointments</span>
        </div>
        <div class="appointments-list">
            <table class="appointments-table">
                <thead>
                    <tr>
                        <th>Time</th>
                        <th>Patient</th>
                        <th>Contact</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($doctor['appointments'] as $apt): ?>
                    <tr class="status-row status-<?php echo $apt['status']; ?>">
                        <td class="time"><?php echo date('h:i A', strtotime($apt['appointment_time'])); ?></td>
                        <td>
                            <strong><?php echo htmlspecialchars($apt['patient_name']); ?></strong>
                            <?php if ($apt['reason']): ?>
                            <br><small
                                class="reason"><?php echo htmlspecialchars(substr($apt['reason'], 0, 50)); ?></small>
                            <?php endif; ?>
                        </td>
                        <td><?php echo htmlspecialchars($apt['patient_phone']); ?></td>
                        <td>
                            <span class="status-badge status-<?php echo $apt['status']; ?>">
                                <?php echo ucfirst($apt['status']); ?>
                            </span>
                        </td>
                        <td class="actions">
                            <?php if ($apt['status'] == 'pending' || $apt['status'] == 'confirmed'): ?>
                            <a href="<?php echo SITE_URL; ?>receptionist.php?action=appointments&sub=checkin&id=<?php echo $apt['id']; ?>"
                                class="btn-checkin" onclick="return confirm('Check in this patient?')">
                                <i class="fas fa-user-check"></i> Check In
                            </a>
                            <?php endif; ?>
                            <a href="<?php echo SITE_URL; ?>receptionist.php?action=appointments&sub=cancel&id=<?php echo $apt['id']; ?>"
                                class="btn-cancel" onclick="return confirm('Cancel this appointment?')">
                                <i class="fas fa-times"></i> Cancel
                            </a>
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
        <h3>No Appointments Today</h3>
        <p>There are no appointments scheduled for today.</p>
    </div>
    <?php endif; ?>
</div>

<style>
.page-header {
    margin-bottom: 25px;
}

.page-title {
    font-size: 28px;
    margin: 0 0 5px 0;
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
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
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

.doctor-schedule-card {
    background: white;
    border-radius: 12px;
    margin-bottom: 25px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.doctor-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 15px 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.doctor-header h3 {
    margin: 0;
    font-size: 18px;
}

.appointment-count {
    background: rgba(255, 255, 255, 0.2);
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 12px;
}

.appointments-table {
    width: 100%;
    border-collapse: collapse;
}

.appointments-table th,
.appointments-table td {
    padding: 12px 15px;
    text-align: left;
    border-bottom: 1px solid #eee;
}

.appointments-table th {
    background: #f8f9fa;
    font-weight: 600;
}

.status-row:hover {
    background: #f9f9f9;
}

.time {
    font-weight: 600;
    color: #667eea;
}

.reason {
    color: #999;
    font-size: 11px;
}

.status-badge {
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 11px;
}

.status-pending {
    background: #fff3cd;
    color: #856404;
}

.status-confirmed {
    background: #d4edda;
    color: #155724;
}

.status-checked_in {
    background: #cce5ff;
    color: #004085;
}

.status-completed {
    background: #d1ecf1;
    color: #0c5460;
}

.status-cancelled {
    background: #f8d7da;
    color: #721c24;
}

.actions {
    display: flex;
    gap: 8px;
}

.btn-checkin,
.btn-cancel {
    padding: 5px 12px;
    border-radius: 5px;
    text-decoration: none;
    font-size: 11px;
}

.btn-checkin {
    background: #28a745;
    color: white;
}

.btn-cancel {
    background: #dc3545;
    color: white;
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

.alert {
    padding: 12px 20px;
    border-radius: 8px;
    margin-bottom: 20px;
}

.alert-success {
    background: #d4edda;
    color: #155724;
    border-left: 4px solid #28a745;
}

.alert-danger {
    background: #f8d7da;
    color: #721c24;
    border-left: 4px solid #dc3545;
}

@media (max-width: 768px) {

    .appointments-table th,
    .appointments-table td {
        padding: 8px;
        font-size: 12px;
    }

    .actions {
        flex-direction: column;
    }
}
</style>