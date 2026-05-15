<div class="page-header">
    <h1 class="page-title">My Patients</h1>
</div>

<!-- Search Bar -->
<div class="search-bar">
    <form method="GET" action="<?php echo SITE_URL; ?>doctor.php">
        <input type="hidden" name="action" value="patients">
        <div class="search-wrapper">
            <i class="fas fa-search"></i>
            <input type="text" name="search" class="search-input" 
                   placeholder="Search by name, email or phone..." 
                   value="<?php echo htmlspecialchars($search ?? ''); ?>">
            <button type="submit" class="btn-search">Search</button>
            <?php if ($search): ?>
                <a href="<?php echo SITE_URL; ?>doctor.php?action=patients" class="btn-clear">Clear</a>
            <?php endif; ?>
        </div>
    </form>
</div>

<div class="patients-list">
    <?php if ($patients->num_rows > 0): ?>
        <div class="patients-grid">
            <?php while($row = $patients->fetch_assoc()): ?>
            <div class="patient-card">
                <div class="patient-avatar">
                    <i class="fas fa-user-circle"></i>
                </div>
                <div class="patient-info">
                    <h3><?php echo htmlspecialchars($row['name']); ?></h3>
                    <div class="patient-details">
                        <span><i class="fas fa-envelope"></i> <?php echo htmlspecialchars($row['email']); ?></span>
                        <span><i class="fas fa-phone"></i> <?php echo htmlspecialchars($row['phone']); ?></span>
                        <?php if ($row['blood_group']): ?>
                        <span><i class="fas fa-tint"></i> <?php echo $row['blood_group']; ?></span>
                        <?php endif; ?>
                        <span><i class="fas fa-calendar"></i> Last visit: <?php echo date('M d, Y', strtotime($row['last_visit'])); ?></span>
                        <span><i class="fas fa-stethoscope"></i> Total visits: <?php echo $row['total_visits']; ?></span>
                    </div>
                </div>
                <div class="patient-actions">
                    <a href="<?php echo SITE_URL; ?>doctor.php?action=patients&sub=history&id=<?php echo $row['id']; ?>" 
                       class="btn-view">
                        <i class="fas fa-notes-medical"></i> View Medical History
                    </a>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <div class="empty-state">
            <i class="fas fa-users"></i>
            <h3>No Patients Found</h3>
            <p><?php echo $search ? 'No patients match your search criteria.' : 'You have no patients yet.'; ?></p>
        </div>
    <?php endif; ?>
</div>

<style>
.page-header {
    margin-bottom: 25px;
}
.page-title {
    font-size: 28px;
    margin: 0;
    color: #333;
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
    padding: 10px;
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
}
.patients-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(400px, 1fr));
    gap: 20px;
}
.patient-card {
    background: white;
    border-radius: 12px;
    padding: 20px;
    display: flex;
    gap: 15px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    transition: transform 0.2s;
}
.patient-card:hover {
    transform: translateY(-2px);
}
.patient-avatar i {
    font-size: 60px;
    color: #667eea;
}
.patient-info {
    flex: 1;
}
.patient-info h3 {
    margin: 0 0 10px 0;
    font-size: 18px;
}
.patient-details {
    display: flex;
    flex-direction: column;
    gap: 5px;
}
.patient-details span {
    font-size: 12px;
    color: #666;
}
.patient-details i {
    width: 16px;
    margin-right: 5px;
    color: #667eea;
}
.patient-actions {
    display: flex;
    align-items: center;
}
.btn-view {
    background: #e8eaf6;
    color: #3949ab;
    padding: 8px 15px;
    border-radius: 6px;
    text-decoration: none;
    font-size: 13px;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}
.btn-view:hover {
    background: #3949ab;
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
@media (max-width: 768px) {
    .patients-grid {
        grid-template-columns: 1fr;
    }
    .patient-card {
        flex-direction: column;
        text-align: center;
    }
    .patient-actions {
        justify-content: center;
    }
}
</style>