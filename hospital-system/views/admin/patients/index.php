<div class="page-header">
    <h1 class="page-title">Manage Patients</h1>
</div>

<div class="stats-row">
    <div class="stat-mini">
        <span class="stat-label">Total Patients</span>
        <span class="stat-number"><?php echo $stats['total']; ?></span>
    </div>
    <div class="stat-mini">
        <span class="stat-label">Active</span>
        <span class="stat-number text-success"><?php echo $stats['active']; ?></span>
    </div>
    <div class="stat-mini">
        <span class="stat-label">Inactive</span>
        <span class="stat-number text-danger"><?php echo $stats['inactive']; ?></span>
    </div>
</div>

<!-- Search Bar -->
<div class="search-bar">
    <form method="GET" action="<?php echo SITE_URL; ?>admin.php">
        <input type="hidden" name="action" value="patients">
        <div class="search-wrapper">
            <i class="fas fa-search"></i>
            <input type="text" name="search" class="search-input" placeholder="Search by name, email or phone..."
                value="<?php echo htmlspecialchars($search ?? ''); ?>">
            <button type="submit" class="btn-search">Search</button>
            <?php if ($search): ?>
            <a href="<?php echo SITE_URL; ?>admin.php?action=patients" class="btn-clear">Clear</a>
            <?php endif; ?>
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
        <h3><i class="fas fa-users"></i> All Patients</h3>
        <span class="badge"><?php echo $patients->num_rows; ?> records</span>
    </div>
    <div class="card-body">
        <?php if ($patients->num_rows > 0): ?>
        <table class="data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Blood Group</th>
                    <th>Status</th>
                    <th>Registered</th>
                    <th width="100">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $patients->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td>
                        <strong><?php echo htmlspecialchars($row['name']); ?></strong>
                    </td>
                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                    <td><?php echo htmlspecialchars($row['phone']); ?></td>
                    <td class="text-center">
                        <?php if ($row['blood_group']): ?>
                        <span class="blood-badge"><?php echo $row['blood_group']; ?></span>
                        <?php else: ?>
                        <span class="text-muted">—</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if ($row['is_active']): ?>
                        <span class="status-active">Active</span>
                        <?php else: ?>
                        <span class="status-inactive">Inactive</span>
                        <?php endif; ?>
                    </td>
                    <td><?php echo date('M d, Y', strtotime($row['created_at'])); ?></td>
                    <td class="actions">
                        <!-- Change this line -->
                        <a href="<?php echo SITE_URL; ?>admin.php?action=patients&sub=show&id=<?php echo $row['id']; ?>"
                            class="btn-icon btn-view" title="View Details">
                            <i class="fas fa-eye"></i>
                        </a>
                        <?php if ($row['is_active']): ?>
                        <a href="<?php echo SITE_URL; ?>admin.php?action=patients&sub=deactivate&id=<?php echo $row['id']; ?>"
                            class="btn-icon btn-deactivate" title="Deactivate"
                            onclick="return confirm('Deactivate this patient? They will not be able to login.')">
                            <i class="fas fa-ban"></i>
                        </a>
                        <?php else: ?>
                        <a href="<?php echo SITE_URL; ?>admin.php?action=patients&sub=activate&id=<?php echo $row['id']; ?>"
                            class="btn-icon btn-activate" title="Activate"
                            onclick="return confirm('Activate this patient?')">
                            <i class="fas fa-check-circle"></i>
                        </a>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <?php else: ?>
        <p class="text-muted text-center">
            <?php echo $search ? 'No patients found matching "' . htmlspecialchars($search) . '"' : 'No patients found.'; ?>
        </p>
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
    display: flex;
    gap: 15px;
    margin-bottom: 25px;
}

.stat-mini {
    background: white;
    padding: 12px 25px;
    border-radius: 10px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.stat-label {
    font-size: 12px;
    color: #666;
    margin-right: 10px;
}

.stat-number {
    font-size: 22px;
    font-weight: bold;
    color: #667eea;
}

.text-success {
    color: #28a745;
}

.text-danger {
    color: #dc3545;
}

.search-bar {
    margin-bottom: 25px;
}

.search-wrapper {
    display: flex;
    align-items: center;
    background: white;
    border: 1px solid #ddd;
    border-radius: 8px;
    padding: 5px 15px;
    max-width: 500px;
}

.search-wrapper i {
    color: #999;
}

.search-input {
    flex: 1;
    border: none;
    padding: 12px 10px;
    font-size: 14px;
    outline: none;
}

.btn-search {
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
    padding: 8px 15px;
    border-radius: 6px;
    text-decoration: none;
    margin-left: 8px;
    font-size: 13px;
}

.badge {
    background: #e9ecef;
    color: #495057;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 12px;
}

.blood-badge {
    background: #e8eaf6;
    color: #3949ab;
    padding: 3px 8px;
    border-radius: 12px;
    font-size: 11px;
    font-weight: 500;
}

.status-active {
    background: #d4edda;
    color: #155724;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 11px;
    display: inline-block;
}

.status-inactive {
    background: #f8d7da;
    color: #721c24;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 11px;
    display: inline-block;
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

.data-table {
    width: 100%;
    border-collapse: collapse;
}

.data-table th,
.data-table td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid #eee;
}

.data-table th {
    background: #f8f9fa;
    font-weight: 600;
    font-size: 13px;
}

.text-center {
    text-align: center;
}

.text-muted {
    color: #999;
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
</style>