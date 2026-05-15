<div class="page-header">
    <h1 class="page-title">My Dependents</h1>
    <a href="<?php echo SITE_URL; ?>patient.php?action=dependents&sub=create" class="btn-primary">
        <i class="fas fa-plus"></i> Add Dependent
    </a>
</div>

<p class="page-description">Add family members to book appointments on their behalf.</p>

<?php if (isset($success) && $success): ?>
    <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
<?php endif; ?>

<?php if (isset($error) && $error): ?>
    <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>

<?php if ($dependents->num_rows > 0): ?>
    <div class="dependents-grid">
        <?php while($row = $dependents->fetch_assoc()): ?>
        <div class="dependent-card">
            <div class="dependent-avatar">
                <i class="fas fa-user-circle"></i>
            </div>
            <div class="dependent-info">
                <h3><?php echo htmlspecialchars($row['name']); ?></h3>
                <div class="dependent-details">
                    <span class="detail">
                        <i class="fas fa-heartbeat"></i> 
                        <?php echo htmlspecialchars($row['relationship']); ?>
                    </span>
                    <span class="detail">
                        <i class="fas fa-tint"></i> 
                        <?php echo htmlspecialchars($row['blood_group'] ?? 'Not specified'); ?>
                    </span>
                    <span class="detail">
                        <i class="fas fa-birthday-cake"></i> 
                        <?php echo $row['date_of_birth'] ? date('M d, Y', strtotime($row['date_of_birth'])) : 'Not specified'; ?>
                    </span>
                </div>
            </div>
            <div class="dependent-actions">
                <a href="<?php echo SITE_URL; ?>patient.php?action=dependents&sub=edit&id=<?php echo $row['id']; ?>" 
                   class="btn-edit" title="Edit">
                    <i class="fas fa-edit"></i> Edit
                </a>
                <a href="<?php echo SITE_URL; ?>patient.php?action=dependents&sub=delete&id=<?php echo $row['id']; ?>" 
                   class="btn-delete" title="Delete" 
                   onclick="return confirm('Are you sure you want to delete this dependent?')">
                    <i class="fas fa-trash"></i> Delete
                </a>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
<?php else: ?>
    <div class="empty-state">
        <i class="fas fa-users"></i>
        <h3>No Dependents Added</h3>
        <p>Add family members to book appointments on their behalf.</p>
        <a href="<?php echo SITE_URL; ?>patient.php?action=dependents&sub=create" class="btn-primary">
            <i class="fas fa-plus"></i> Add Your First Dependent
        </a>
    </div>
<?php endif; ?>

<style>
.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
}

.page-title {
    font-size: 28px;
    margin: 0;
    color: #333;
}

.page-description {
    color: #666;
    margin-bottom: 25px;
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

.dependents-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    gap: 20px;
}

.dependent-card {
    background: white;
    border-radius: 12px;
    padding: 20px;
    display: flex;
    align-items: center;
    gap: 15px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    transition: transform 0.2s;
}

.dependent-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.dependent-avatar i {
    font-size: 60px;
    color: #667eea;
}

.dependent-info {
    flex: 1;
}

.dependent-info h3 {
    margin: 0 0 8px 0;
    font-size: 18px;
    color: #333;
}

.dependent-details {
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
}

.detail {
    font-size: 12px;
    color: #666;
    display: flex;
    align-items: center;
    gap: 5px;
}

.detail i {
    width: 14px;
    color: #667eea;
}

.dependent-actions {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.btn-edit, .btn-delete {
    padding: 6px 12px;
    border-radius: 6px;
    text-decoration: none;
    font-size: 12px;
    text-align: center;
}

.btn-edit {
    background: #e8eaf6;
    color: #3949ab;
}

.btn-edit:hover {
    background: #3949ab;
    color: white;
}

.btn-delete {
    background: #fee2e2;
    color: #dc3545;
}

.btn-delete:hover {
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

.empty-state h3 {
    margin-bottom: 10px;
    color: #333;
}

.empty-state p {
    color: #666;
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
    .dependents-grid {
        grid-template-columns: 1fr;
    }
    
    .dependent-card {
        flex-direction: column;
        text-align: center;
    }
    
    .dependent-actions {
        flex-direction: row;
        width: 100%;
    }
    
    .btn-edit, .btn-delete {
        flex: 1;
    }
}
</style>