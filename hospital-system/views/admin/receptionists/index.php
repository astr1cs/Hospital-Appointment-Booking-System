<div class="page-header">
    <h1 class="page-title">Manage Receptionists</h1>
    <a href="<?php echo SITE_URL; ?>admin.php?action=receptionists&sub=create" class="btn-primary">
        <i class="fas fa-plus"></i> Add New Receptionist
    </a>
</div>

<div class="stats-row">
    <div class="stat-mini">
        <span class="stat-label">Total Receptionists</span>
        <span class="stat-number"><?php echo $receptionists->num_rows; ?></span>
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
        <h3><i class="fas fa-user-tie"></i> All Receptionists</h3>
    </div>
    <div class="card-body">
        <?php if ($receptionists->num_rows > 0): ?>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Status</th>
                        <th>Created At</th>
                        <th width="120">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = $receptionists->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td>
                            <strong><?php echo htmlspecialchars($row['name']); ?></strong>
                        </td>
                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                        <td><?php echo htmlspecialchars($row['phone']); ?></td>
                        <td>
                            <?php if ($row['is_active']): ?>
                                <span class="status-active">Active</span>
                            <?php else: ?>
                                <span class="status-inactive">Inactive</span>
                            <?php endif; ?>
                        </td>
                        <td><?php echo date('M d, Y', strtotime($row['created_at'])); ?></td>
                        <td class="actions">
                            <a href="<?php echo SITE_URL; ?>admin.php?action=receptionists&sub=edit&id=<?php echo $row['id']; ?>" 
                               class="btn-icon btn-edit" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <?php if ($row['is_active']): ?>
                                <a href="<?php echo SITE_URL; ?>admin.php?action=receptionists&sub=deactivate&id=<?php echo $row['id']; ?>" 
                                   class="btn-icon btn-deactivate" title="Deactivate"
                                   onclick="return confirm('Deactivate this receptionist?')">
                                    <i class="fas fa-ban"></i>
                                </a>
                            <?php else: ?>
                                <a href="<?php echo SITE_URL; ?>admin.php?action=receptionists&sub=activate&id=<?php echo $row['id']; ?>" 
                                   class="btn-icon btn-activate" title="Activate"
                                   onclick="return confirm('Activate this receptionist?')">
                                    <i class="fas fa-check-circle"></i>
                                </a>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="text-muted text-center">No receptionists found. Click "Add New" to create one.</p>
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

.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 10px 20px;
    border-radius: 8px;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
    color: white;
}

.status-active {
    background: #d4edda;
    color: #155724;
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 11px;
    display: inline-block;
}

.status-inactive {
    background: #f8d7da;
    color: #721c24;
    padding: 4px 8px;
    border-radius: 4px;
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

.btn-edit {
    color: #28a745;
}

.btn-edit:hover {
    background: #28a745;
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
</style>