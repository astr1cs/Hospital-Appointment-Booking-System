<div class="page-header">
    <h1 class="page-title">All Appointments</h1>
    <a href="<?php echo SITE_URL; ?>admin.php?action=appointments&sub=clear" class="btn-secondary">
        <i class="fas fa-sync-alt"></i> Clear Filters
    </a>
</div>

<!-- Statistics Cards -->
<div class="stats-row">
    <div class="stat-card-mini">
        <div class="stat-value"><?php echo $stats['total']; ?></div>
        <div class="stat-label">Total</div>
    </div>
    <div class="stat-card-mini stat-pending">
        <div class="stat-value"><?php echo $stats['pending']; ?></div>
        <div class="stat-label">Pending</div>
    </div>
    <div class="stat-card-mini stat-confirmed">
        <div class="stat-value"><?php echo $stats['confirmed']; ?></div>
        <div class="stat-label">Confirmed</div>
    </div>
    <div class="stat-card-mini stat-completed">
        <div class="stat-value"><?php echo $stats['completed']; ?></div>
        <div class="stat-label">Completed</div>
    </div>
    <div class="stat-card-mini stat-cancelled">
        <div class="stat-value"><?php echo $stats['cancelled']; ?></div>
        <div class="stat-label">Cancelled</div>
    </div>
</div>

<!-- Filter Bar -->
<div class="filter-bar">
    <form method="GET" action="<?php echo SITE_URL; ?>admin.php">
        <input type="hidden" name="action" value="appointments">

        <div class="filter-row">
            <div class="filter-group">
                <label>Doctor</label>
                <select name="doctor_id" class="filter-select">
                    <option value="">All Doctors</option>
                    <?php while($doctor = $doctors->fetch_assoc()): ?>
                    <option value="<?php echo $doctor['id']; ?>"
                        <?php echo (($filters['doctor_id'] ?? '') == $doctor['id']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($doctor['name']); ?>
                    </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="filter-group">
                <label>Status</label>
                <select name="status" class="filter-select">
                    <option value="">All Status</option>
                    <option value="pending" <?php echo (($filters['status'] ?? '') == 'pending') ? 'selected' : ''; ?>>
                        Pending</option>
                    <option value="confirmed"
                        <?php echo (($filters['status'] ?? '') == 'confirmed') ? 'selected' : ''; ?>>Confirmed</option>
                    <option value="checked_in"
                        <?php echo (($filters['status'] ?? '') == 'checked_in') ? 'selected' : ''; ?>>Checked In
                    </option>
                    <option value="completed"
                        <?php echo (($filters['status'] ?? '') == 'completed') ? 'selected' : ''; ?>>Completed</option>
                    <option value="cancelled"
                        <?php echo (($filters['status'] ?? '') == 'cancelled') ? 'selected' : ''; ?>>Cancelled</option>
                    <option value="no_show" <?php echo (($filters['status'] ?? '') == 'no_show') ? 'selected' : ''; ?>>
                        No Show</option>
                </select>
            </div>

            <div class="filter-group">
                <label>Booking Source</label>
                <select name="booking_source" class="filter-select">
                    <option value="">All Sources</option>
                    <option value="patient"
                        <?php echo (($filters['booking_source'] ?? '') == 'patient') ? 'selected' : ''; ?>>Patient
                    </option>
                    <option value="receptionist"
                        <?php echo (($filters['booking_source'] ?? '') == 'receptionist') ? 'selected' : ''; ?>>
                        Receptionist</option>
                </select>
            </div>

            <div class="filter-group">
                <label>Date From</label>
                <input type="date" name="date_from" class="filter-input"
                    value="<?php echo $filters['date_from'] ?? ''; ?>">
            </div>

            <div class="filter-group">
                <label>Date To</label>
                <input type="date" name="date_to" class="filter-input" value="<?php echo $filters['date_to'] ?? ''; ?>">
            </div>

            <div class="filter-group filter-actions">
                <button type="submit" class="btn-filter">
                    <i class="fas fa-search"></i> Apply Filters
                </button>
            </div>
        </div>
    </form>
</div>

<?php if (isset($success) && $success): ?>
<div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
<?php endif; ?>

<?php if (isset($error) && $error): ?>
<div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>

<div class="admin-card">
    <div class="card-header">
        <h3><i class="fas fa-calendar-alt"></i> Appointments List</h3>
        <span class="badge"><?php echo $appointments->num_rows; ?> appointments</span>
    </div>
    <div class="card-body">
        <?php if ($appointments->num_rows > 0): ?>
        <table class="data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Patient</th>
                    <th>Doctor</th>
                    <th>Specialization</th>
                    <th>Status</th>
                    <th>Booked By</th>
                    <th width="80">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $appointments->fetch_assoc()): ?>
                <tr class="status-row status-<?php echo $row['status']; ?>">
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo date('M d, Y', strtotime($row['appointment_date'])); ?></td>
                    <td><?php echo date('h:i A', strtotime($row['appointment_time'])); ?></td>
                    <td>
                        <strong><?php echo htmlspecialchars($row['patient_name']); ?></strong><br>
                        <small><?php echo htmlspecialchars($row['patient_email']); ?></small>
                    </td>
                    <td>
                        <?php echo htmlspecialchars($row['doctor_name']); ?>
                    </td>
                    <td><?php echo htmlspecialchars($row['specialization_name']); ?></td>
                    <td>
                        <span class="status-badge status-<?php echo $row['status']; ?>">
                            <?php echo ucfirst(str_replace('_', ' ', $row['status'])); ?>
                        </span>
                    </td>
                    <td>
                        <span class="source-badge source-<?php echo $row['booked_by']; ?>">
                            <?php echo ucfirst($row['booked_by']); ?>
                        </span>
                    </td>
                    <td class="actions">
                        <!-- Change this line -->
                        <a href="<?php echo SITE_URL; ?>admin.php?action=appointments&sub=show&id=<?php echo $row['id']; ?>"
                            class="btn-icon btn-view" title="View Details">
                            <i class="fas fa-eye"></i>
                        </a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <?php else: ?>
        <p class="text-muted text-center">No appointments found matching your criteria.</p>
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

.btn-secondary {
    background: #6c757d;
    color: white;
    padding: 8px 16px;
    border-radius: 6px;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.btn-secondary:hover {
    background: #5a6268;
    color: white;
}

/* Statistics Cards */
.stats-row {
    display: flex;
    gap: 15px;
    margin-bottom: 25px;
    flex-wrap: wrap;
}

.stat-card-mini {
    background: white;
    padding: 15px 25px;
    border-radius: 10px;
    text-align: center;
    min-width: 100px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.stat-card-mini .stat-value {
    font-size: 28px;
    font-weight: bold;
    color: #333;
}

.stat-card-mini .stat-label {
    font-size: 12px;
    color: #666;
    margin-top: 5px;
}

.stat-pending .stat-value {
    color: #856404;
}

.stat-confirmed .stat-value {
    color: #155724;
}

.stat-completed .stat-value {
    color: #004085;
}

.stat-cancelled .stat-value {
    color: #721c24;
}

/* Filter Bar */
.filter-bar {
    background: white;
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 25px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
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

.filter-select:focus,
.filter-input:focus {
    outline: none;
    border-color: #667eea;
}

.filter-actions {
    flex: 0 0 auto;
}

.btn-filter {
    background: #667eea;
    color: white;
    border: none;
    padding: 8px 20px;
    border-radius: 6px;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.btn-filter:hover {
    background: #5a67d8;
}

/* Table Styles */
.data-table {
    width: 100%;
    border-collapse: collapse;
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

.status-row:hover {
    background: #f9f9f9;
}

.status-badge {
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 500;
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

.status-no_show {
    background: #e2d5f8;
    color: #4a1d6d;
}

.source-badge {
    padding: 3px 8px;
    border-radius: 12px;
    font-size: 10px;
    font-weight: 500;
}

.source-patient {
    background: #e8eaf6;
    color: #3949ab;
}

.source-receptionist {
    background: #f3e5f5;
    color: #6a1b9a;
}

.actions {
    display: flex;
    gap: 8px;
}

.btn-icon {
    background: none;
    border: none;
    cursor: pointer;
    padding: 5px 8px;
    border-radius: 5px;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
}

.btn-view {
    color: #17a2b8;
}

.btn-view:hover {
    background: #17a2b8;
    color: white;
}

.badge {
    background: #e9ecef;
    color: #495057;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 12px;
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

.text-muted {
    text-align: center;
    padding: 40px;
    color: #999;
}

/* Responsive */
@media (max-width: 1024px) {
    .filter-group {
        min-width: 120px;
    }
}

@media (max-width: 768px) {
    .stats-row {
        gap: 10px;
    }

    .stat-card-mini {
        min-width: 80px;
        padding: 10px 15px;
    }

    .stat-card-mini .stat-value {
        font-size: 20px;
    }

    .filter-row {
        flex-direction: column;
    }

    .filter-group {
        width: 100%;
    }

    .data-table {
        font-size: 12px;
    }

    .data-table th,
    .data-table td {
        padding: 8px;
    }
}
</style>