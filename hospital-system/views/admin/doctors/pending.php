<div class="page-header">
    <h1 class="page-title">Pending Doctor Approvals</h1>
    <a href="<?php echo SITE_URL; ?>admin.php?action=doctors" class="btn-secondary">
        <i class="fas fa-arrow-left"></i> Back to All Doctors
    </a>
</div>

<?php if (isset($success) && $success): ?>
    <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
<?php endif; ?>

<?php if (isset($error) && $error): ?>
    <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>

<div class="admin-card">
    <div class="card-header">
        <h3><i class="fas fa-clock"></i> Pending Approvals</h3>
        <span class="badge"><?php echo $doctors->num_rows; ?> pending</span>
    </div>
    <div class="card-body">
        <?php if ($doctors->num_rows > 0): ?>
            <?php while($row = $doctors->fetch_assoc()): ?>
            <div class="pending-card">
                <div class="pending-info">
                    <div class="pending-avatar">
                        <i class="fas fa-user-md"></i>
                    </div>
                    <div class="pending-details">
                        <h4><?php echo htmlspecialchars($row['name']); ?></h4>
                        <p><i class="fas fa-envelope"></i> <?php echo htmlspecialchars($row['email']); ?></p>
                        <p><i class="fas fa-phone"></i> <?php echo htmlspecialchars($row['phone']); ?></p>
                        <p><i class="fas fa-tag"></i> <?php echo htmlspecialchars($row['specialization_name'] ?? 'Not Assigned'); ?></p>
                        <p><i class="fas fa-dollar-sign"></i> Fee: $<?php echo number_format($row['consultation_fee'], 2); ?></p>
                        <p><i class="fas fa-id-card"></i> License: <?php echo htmlspecialchars($row['license_number'] ?? 'N/A'); ?></p>
                        <p><i class="fas fa-calendar"></i> Experience: <?php echo $row['experience_years']; ?> years</p>
                        <?php if ($row['bio']): ?>
                            <p><i class="fas fa-info-circle"></i> <?php echo nl2br(htmlspecialchars(substr($row['bio'], 0, 200))); ?>...</p>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="pending-actions">
                    <a href="<?php echo SITE_URL; ?>admin.php?action=doctors&sub=approve&id=<?php echo $row['user_id']; ?>" 
                       class="btn-approve" onclick="return confirm('Approve this doctor?')">
                        <i class="fas fa-check"></i> Approve
                    </a>
                    <a href="<?php echo SITE_URL; ?>admin.php?action=doctors&sub=reject&id=<?php echo $row['user_id']; ?>" 
                       class="btn-reject" onclick="return confirm('Reject and remove this doctor? This action cannot be undone.')">
                        <i class="fas fa-times"></i> Reject
                    </a>
                </div>
            </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p class="text-muted text-center">No pending approvals. All doctors are approved.</p>
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

.badge {
    background: #ffc107;
    color: #333;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 12px;
}

.pending-card {
    background: #f9f9f9;
    border: 1px solid #eee;
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 20px;
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    transition: all 0.3s;
}

.pending-card:hover {
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.pending-info {
    display: flex;
    gap: 20px;
    flex: 1;
}

.pending-avatar {
    font-size: 60px;
    color: #667eea;
}

.pending-details h4 {
    margin: 0 0 10px 0;
    color: #333;
}

.pending-details p {
    margin: 5px 0;
    font-size: 13px;
    color: #666;
}

.pending-details p i {
    width: 20px;
    color: #667eea;
}

.pending-actions {
    display: flex;
    gap: 10px;
}

.btn-approve {
    background: #28a745;
    color: white;
    padding: 8px 16px;
    border-radius: 6px;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

.btn-approve:hover {
    background: #218838;
    color: white;
}

.btn-reject {
    background: #dc3545;
    color: white;
    padding: 8px 16px;
    border-radius: 6px;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

.btn-reject:hover {
    background: #c82333;
    color: white;
}

.text-muted {
    text-align: center;
    padding: 40px;
    color: #999;
}

@media (max-width: 768px) {
    .pending-card {
        flex-direction: column;
        gap: 15px;
    }
    
    .pending-info {
        flex-direction: column;
        text-align: center;
    }
    
    .pending-actions {
        width: 100%;
        justify-content: center;
    }
}
</style>