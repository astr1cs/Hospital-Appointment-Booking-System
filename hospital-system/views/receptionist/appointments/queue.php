<div class="page-header">
    <h1 class="page-title">Waiting Queue</h1>
    <p>Patients waiting to see doctors</p>
</div>

<div class="queue-container">
    <?php if ($queue->num_rows > 0): ?>
        <div class="queue-list">
            <?php while($row = $queue->fetch_assoc()): ?>
            <div class="queue-card">
                <div class="queue-position">
                    <i class="fas fa-hourglass-half"></i>
                </div>
                <div class="queue-info">
                    <h3><?php echo htmlspecialchars($row['patient_name']); ?></h3>
                    <div class="details">
                        <span><i class="fas fa-user-md"></i> Dr. <?php echo htmlspecialchars($row['doctor_name']); ?></span>
                        <span><i class="fas fa-clock"></i> <?php echo date('h:i A', strtotime($row['appointment_time'])); ?></span>
                        <span><i class="fas fa-phone"></i> <?php echo htmlspecialchars($row['patient_phone']); ?></span>
                    </div>
                </div>
                <div class="queue-actions">
                    <span class="waiting-badge">Waiting</span>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <div class="empty-state">
            <i class="fas fa-hourglass"></i>
            <h3>Queue is Empty</h3>
            <p>No patients are currently waiting.</p>
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
.queue-list {
    display: flex;
    flex-direction: column;
    gap: 15px;
}
.queue-card {
    background: white;
    border-radius: 12px;
    padding: 20px;
    display: flex;
    align-items: center;
    gap: 20px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    border-left: 4px solid #ffc107;
}
.queue-position i {
    font-size: 40px;
    color: #ffc107;
}
.queue-info {
    flex: 1;
}
.queue-info h3 {
    margin: 0 0 8px 0;
    font-size: 18px;
}
.details {
    display: flex;
    gap: 20px;
    flex-wrap: wrap;
}
.details span {
    font-size: 13px;
    color: #666;
}
.details i {
    width: 16px;
    margin-right: 4px;
    color: #667eea;
}
.waiting-badge {
    background: #fff3cd;
    color: #856404;
    padding: 5px 15px;
    border-radius: 20px;
    font-size: 12px;
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
    .queue-card {
        flex-direction: column;
        text-align: center;
    }
    .details {
        justify-content: center;
    }
}
</style>