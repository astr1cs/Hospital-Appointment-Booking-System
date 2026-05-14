<div class="page-header">
    <h1 class="page-title">Manage Specializations</h1>
    <a href="<?php echo SITE_URL; ?>admin.php?action=specializations&sub=create" class="btn btn-primary">
        <i class="fas fa-plus"></i> Add New Specialization
    </a>
</div>

<?php if (isset($success) && $success): ?>
    <div class="alert alert-success">
        <i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($success); ?>
    </div>
<?php endif; ?>

<?php if (isset($error) && $error): ?>
    <div class="alert alert-danger">
        <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error); ?>
    </div>
<?php endif; ?>

<div class="admin-card">
    <div class="card-header">
        <h3><i class="fas fa-tags"></i> All Specializations</h3>
        <span class="badge"><?php echo $specializations->num_rows; ?> total</span>
    </div>
    <div class="card-body">
        <?php if ($specializations->num_rows > 0): ?>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Specialization Name</th>
                        <th>Description</th>
                        <th>Created At</th>
                        <th width="120">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = $specializations->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td>
                            <strong><?php echo htmlspecialchars($row['name']); ?></strong>
                        </td>
                        <td><?php echo htmlspecialchars($row['description'] ?? '-'); ?></td>
                        <td><?php echo date('M d, Y', strtotime($row['created_at'])); ?></td>
                        <td class="actions">
                            <a href="<?php echo SITE_URL; ?>admin.php?action=specializations&sub=edit&id=<?php echo $row['id']; ?>" 
                               class="btn-icon btn-edit" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button type="button" 
                                    class="btn-icon btn-delete" 
                                    title="Delete"
                                    onclick="confirmDelete(<?php echo $row['id']; ?>, '<?php echo htmlspecialchars($row['name']); ?>')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="text-muted text-center">No specializations found. Click "Add New" to create one.</p>
        <?php endif; ?>
    </div>
</div>

<script>
function confirmDelete(id, name) {
    if (confirm('Are you sure you want to delete "' + name + '"? This action cannot be undone.')) {
        window.location.href = '<?php echo SITE_URL; ?>admin.php?action=specializations&sub=delete&id=' + id;
    }
}
</script>

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

.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 10px 20px;
    border-radius: 8px;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: all 0.3s;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
    color: white;
    text-decoration: none;
}

.badge {
    background: #667eea;
    color: white;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 12px;
}

.data-table {
    width: 100%;
    border-collapse: collapse;
}

.data-table th,
.data-table td {
    padding: 12px 15px;
    text-align: left;
    border-bottom: 1px solid #eee;
}

.data-table th {
    background: #f8f9fa;
    font-weight: 600;
    color: #555;
}

.data-table tbody tr:hover {
    background: #f9f9f9;
}

.actions {
    display: flex;
    gap: 10px;
}

.btn-icon {
    background: none;
    border: none;
    cursor: pointer;
    padding: 5px 8px;
    border-radius: 5px;
    transition: all 0.3s;
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

.btn-delete {
    color: #dc3545;
}

.btn-delete:hover {
    background: #dc3545;
    color: white;
}

.text-center {
    text-align: center;
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