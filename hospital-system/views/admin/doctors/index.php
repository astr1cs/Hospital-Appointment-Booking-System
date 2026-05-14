<div class="page-header">
    <h1 class="page-title">Manage Doctors</h1>
    <a href="<?php echo SITE_URL; ?>admin.php?action=doctors&sub=create" class="btn-primary">
        <i class="fas fa-plus"></i> Add New Doctor
    </a>
</div>

<div class="stats-row">
    <div class="stat-mini">
        <span class="stat-label">Total Doctors</span>
        <span class="stat-number"><?php echo $doctors->num_rows; ?></span>
    </div>
</div>

<?php if (isset($success) && $success): ?>
    <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
<?php endif; ?>

<?php if (isset($error) && $error): ?>
    <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>

<div class="admin-card">
    <div class="card-header">
        <h3><i class="fas fa-user-md"></i> All Doctors</h3>
    </div>
    <div class="card-body">
        <?php if ($doctors->num_rows > 0): ?>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Photo</th>
                        <th>Name</th>
                        <th>Specialization</th>
                        <th>Fee</th>
                        <th>Experience</th>
                        <th>Status</th>
                        <th width="150">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = $doctors->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td class="text-center">
                            <div class="avatar-sm">
                                <i class="fas fa-user-circle"></i>
                            </div>
                        </td>
                        <td>
                            <strong><?php echo htmlspecialchars($row['name']); ?></strong><br>
                            <small><?php echo htmlspecialchars($row['email']); ?></small>
                        </td>
                        <td><?php echo htmlspecialchars($row['specialization_name'] ?? 'Not Assigned'); ?></td>
                        <td>$<?php echo number_format($row['consultation_fee'], 2); ?></td>
                        <td><?php echo $row['experience_years']; ?> years</td>
                        <td>
                            <?php if ($row['is_active'] && $row['is_approved']): ?>
                                <span class="status-active">Active</span>
                            <?php elseif (!$row['is_approved']): ?>
                                <span class="status-pending">Pending</span>
                            <?php else: ?>
                                <span class="status-inactive">Inactive</span>
                            <?php endif; ?>
                        </td>
                        <td class="actions">
                            <a href="<?php echo SITE_URL; ?>admin.php?action=doctors&sub=edit&id=<?php echo $row['id']; ?>" 
                               class="btn-icon btn-edit" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <?php if ($row['is_active']): ?>
                                <a href="<?php echo SITE_URL; ?>admin.php?action=doctors&sub=deactivate&id=<?php echo $row['user_id']; ?>" 
                                   class="btn-icon btn-deactivate" title="Deactivate"
                                   onclick="return confirm('Deactivate this doctor? They will not be able to login.')">
                                    <i class="fas fa-ban"></i>
                                </a>
                            <?php else: ?>
                                <a href="<?php echo SITE_URL; ?>admin.php?action=doctors&sub=activate&id=<?php echo $row['user_id']; ?>" 
                                   class="btn-icon btn-activate" title="Activate"
                                   onclick="return confirm('Activate this doctor?')">
                                    <i class="fas fa-check-circle"></i>
                                </a>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="text-muted text-center">No doctors found. Click "Add New" to create one.</p>
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

.stats-row {
    margin-bottom: 20px;
}

.stat-mini {
    background: white;
    padding: 10px 20px;
    border-radius: 8px;
    display: inline-block;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.stat-label {
    font-size: 12px;
    color: #666;
}

.stat-number {
    font-size: 20px;
    font-weight: bold;
    margin-left: 10px;
    color: #667eea;
}

.avatar-sm {
    font-size: 32px;
    color: #667eea;
}

.text-center {
    text-align: center;
}

.status-active {
    background: #d4edda;
    color: #155724;
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 11px;
}

.status-pending {
    background: #fff3cd;
    color: #856404;
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 11px;
}

.status-inactive {
    background: #f8d7da;
    color: #721c24;
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 11px;
}

.btn-deactivate {
    color: #ffc107;
}

.btn-deactivate:hover {
    background: #ffc107;
    color: white;
}

.btn-activate {
    color: #28a745;
}

.btn-activate:hover {
    background: #28a745;
    color: white;
}
</style>