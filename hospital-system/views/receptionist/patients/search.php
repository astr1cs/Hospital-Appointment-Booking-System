
<div class="page-header">
    <h1 class="page-title">Search Patients</h1>
</div>

<!-- Search Form -->
<div class="search-card">
    <form method="GET" action="<?php echo SITE_URL; ?>receptionist.php">
        <input type="hidden" name="action" value="patients">
        <input type="hidden" name="sub" value="search">
        
        <div class="search-wrapper">
            <i class="fas fa-search"></i>
            <input type="text" name="search" class="search-input" 
                   placeholder="Search by name, email or phone number..."
                   value="<?php echo htmlspecialchars($search); ?>">
            <button type="submit" class="btn-search">Search</button>
        </div>
    </form>
</div>

<?php if (isset($success) && $success): ?>
    <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
<?php endif; ?>

<?php if (isset($error) && $error): ?>
    <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>

<!-- Results -->
<?php if ($search !== ''): ?>
    <div class="results-card">
        <div class="card-header">
            <h3><i class="fas fa-users"></i> Search Results</h3>
            <span><?php echo $patients->num_rows; ?> patients found</span>
        </div>
        <div class="card-body">
            <?php if ($patients->num_rows > 0): ?>
                <div class="patients-list">
                    <?php while($row = $patients->fetch_assoc()): ?>
                    <div class="patient-item">
                        <div class="patient-avatar">
                            <i class="fas fa-user-circle"></i>
                        </div>
                        <div class="patient-info">
                            <h4><?php echo htmlspecialchars($row['name']); ?></h4>
                            <div class="patient-details">
                                <span><i class="fas fa-envelope"></i> <?php echo htmlspecialchars($row['email']); ?></span>
                                <span><i class="fas fa-phone"></i> <?php echo htmlspecialchars($row['phone']); ?></span>
                                <span><i class="fas fa-calendar"></i> Visits: <?php echo $row['total_visits']; ?></span>
                                <?php if ($row['pending_amount'] > 0): ?>
                                <span class="pending-amount"><i class="fas fa-dollar-sign"></i> Pending: $<?php echo number_format($row['pending_amount'], 2); ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="patient-actions">
                            <a href="<?php echo SITE_URL; ?>receptionist.php?action=patients&sub=view&id=<?php echo $row['id']; ?>" 
                               class="btn-view">View Details</a>
                        </div>
                    </div>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <div class="empty-state">
                    <i class="fas fa-search"></i>
                    <p>No patients found matching "<?php echo htmlspecialchars($search); ?>"</p>
                    <a href="<?php echo SITE_URL; ?>receptionist.php?action=patients&sub=register" class="btn-register">
                        <i class="fas fa-user-plus"></i> Register New Patient
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>

<style>
.page-header {
    margin-bottom: 25px;
}
.page-title {
    font-size: 28px;
    margin: 0;
    color: #333;
}
.search-card {
    background: white;
    border-radius: 12px;
    padding: 25px;
    margin-bottom: 25px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}
.search-wrapper {
    display: flex;
    gap: 10px;
    align-items: center;
}
.search-wrapper i {
    color: #999;
}
.search-input {
    flex: 1;
    padding: 12px;
    border: 1px solid #ddd;
    border-radius: 8px;
    font-size: 14px;
}
.btn-search {
    background: #667eea;
    color: white;
    border: none;
    padding: 12px 24px;
    border-radius: 8px;
    cursor: pointer;
}
.results-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    overflow: hidden;
}
.card-header {
    padding: 15px 20px;
    background: #f8f9fa;
    border-bottom: 1px solid #eee;
    display: flex;
    justify-content: space-between;
}
.card-header h3 {
    margin: 0;
    font-size: 16px;
}
.patients-list {
    display: flex;
    flex-direction: column;
}
.patient-item {
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 15px 20px;
    border-bottom: 1px solid #eee;
    transition: background 0.2s;
}
.patient-item:hover {
    background: #f9f9f9;
}
.patient-avatar i {
    font-size: 50px;
    color: #667eea;
}
.patient-info {
    flex: 1;
}
.patient-info h4 {
    margin: 0 0 8px 0;
    font-size: 16px;
}
.patient-details {
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
    font-size: 13px;
    color: #666;
}
.patient-details i {
    width: 14px;
    margin-right: 4px;
}
.pending-amount {
    color: #dc3545;
}
.btn-view {
    background: #e8eaf6;
    color: #3949ab;
    padding: 8px 16px;
    border-radius: 6px;
    text-decoration: none;
    font-size: 13px;
}
.empty-state {
    text-align: center;
    padding: 40px;
}
.empty-state i {
    font-size: 50px;
    color: #ccc;
    margin-bottom: 15px;
}
.btn-register {
    display: inline-block;
    background: #28a745;
    color: white;
    padding: 10px 20px;
    border-radius: 8px;
    text-decoration: none;
    margin-top: 15px;
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
    .search-wrapper {
        flex-direction: column;
    }
    .search-wrapper i {
        display: none;
    }
    .btn-search {
        width: 100%;
    }
    .patient-item {
        flex-direction: column;
        text-align: center;
    }
    .patient-details {
        justify-content: center;
    }
}
</style>