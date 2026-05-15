<div class="page-header">
    <h1 class="page-title">Follow-up Appointments</h1>
    <p>Patients who need follow-up care</p>
</div>

<!-- Upcoming Follow-ups -->
<div class="section">
    <h2><i class="fas fa-calendar-alt"></i> Upcoming Follow-ups</h2>
    
    <?php if ($followups->num_rows > 0): ?>
        <div class="followups-grid">
            <?php while($row = $followups->fetch_assoc()): ?>
            <div class="followup-card upcoming">
                <div class="followup-date">
                    <div class="date-day"><?php echo date('d', strtotime($row['follow_up_date'])); ?></div>
                    <div class="date-month"><?php echo date('M', strtotime($row['follow_up_date'])); ?></div>
                </div>
                <div class="followup-info">
                    <h3><?php echo htmlspecialchars($row['patient_name']); ?></h3>
                    <div class="patient-details">
                        <span><i class="fas fa-phone"></i> <?php echo htmlspecialchars($row['patient_phone']); ?></span>
                        <span><i class="fas fa-envelope"></i> <?php echo htmlspecialchars($row['patient_email']); ?></span>
                    </div>
                    <?php if ($row['diagnosis']): ?>
                    <div class="diagnosis">
                        <strong>Previous Diagnosis:</strong> <?php echo htmlspecialchars(substr($row['diagnosis'], 0, 100)); ?>
                    </div>
                    <?php endif; ?>
                    <div class="original-appointment">
                        <i class="fas fa-calendar"></i> Original: <?php echo date('M d, Y', strtotime($row['appointment_date'])); ?>
                    </div>
                </div>
                <div class="followup-actions">
                    <a href="<?php echo SITE_URL; ?>doctor.php?action=patients&sub=history&id=<?php echo $row['patient_id']; ?>" 
                       class="btn-view">View Patient</a>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <div class="empty-state-small">
            <i class="fas fa-check-circle"></i>
            <p>No upcoming follow-up appointments.</p>
        </div>
    <?php endif; ?>
</div>

<!-- Past Follow-ups -->
<div class="section">
    <h2><i class="fas fa-history"></i> Past Follow-ups</h2>
    
    <?php if ($pastFollowups->num_rows > 0): ?>
        <div class="followups-list">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Patient</th>
                        <th>Contact</th>
                        <th>Follow-up Date</th>
                        <th>Original Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = $pastFollowups->fetch_assoc()): ?>
                    <tr class="past-row">
                        <td><strong><?php echo htmlspecialchars($row['patient_name']); ?></strong></td>
                        <td>
                            <?php echo htmlspecialchars($row['patient_phone']); ?><br>
                            <small><?php echo htmlspecialchars($row['patient_email']); ?></small>
                        </td>
                        <td><?php echo date('M d, Y', strtotime($row['follow_up_date'])); ?></td>
                        <td><?php echo date('M d, Y', strtotime($row['appointment_date'])); ?></td>
                        <td>
                            <a href="<?php echo SITE_URL; ?>doctor.php?action=patients&sub=history&id=<?php echo $row['patient_id']; ?>" 
                               class="btn-sm">View History</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="empty-state-small">
            <i class="fas fa-calendar"></i>
            <p>No past follow-up records.</p>
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
.section {
    background: white;
    border-radius: 12px;
    padding: 25px;
    margin-bottom: 30px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}
.section h2 {
    margin: 0 0 20px 0;
    font-size: 18px;
    color: #333;
    padding-bottom: 10px;
    border-bottom: 2px solid #f0f0f0;
}
.section h2 i {
    color: #667eea;
    margin-right: 8px;
}
.followups-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(400px, 1fr));
    gap: 20px;
}
.followup-card {
    background: #f8f9fa;
    border-radius: 12px;
    padding: 20px;
    display: flex;
    gap: 20px;
    transition: transform 0.2s;
    border-left: 4px solid #28a745;
}
.followup-card.upcoming {
    border-left-color: #28a745;
}
.followup-date {
    text-align: center;
    min-width: 60px;
    padding: 10px;
    background: white;
    border-radius: 8px;
}
.date-day {
    font-size: 28px;
    font-weight: bold;
    color: #28a745;
}
.date-month {
    font-size: 12px;
    color: #666;
}
.followup-info {
    flex: 1;
}
.followup-info h3 {
    margin: 0 0 8px 0;
    font-size: 18px;
}
.patient-details {
    display: flex;
    gap: 15px;
    margin-bottom: 8px;
    font-size: 12px;
    color: #666;
}
.patient-details i {
    width: 14px;
}
.diagnosis {
    font-size: 13px;
    color: #555;
    margin-bottom: 8px;
}
.original-appointment {
    font-size: 11px;
    color: #999;
}
.followup-actions {
    display: flex;
    align-items: center;
}
.btn-view {
    background: #667eea;
    color: white;
    padding: 8px 16px;
    border-radius: 6px;
    text-decoration: none;
    font-size: 13px;
}
.btn-view:hover {
    background: #5a67d8;
}
.followups-list {
    overflow-x: auto;
}
.data-table {
    width: 100%;
    border-collapse: collapse;
}
.data-table th, .data-table td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid #eee;
}
.data-table th {
    background: #f8f9fa;
    font-weight: 600;
}
.past-row {
    opacity: 0.8;
}
.btn-sm {
    background: #e8eaf6;
    color: #3949ab;
    padding: 5px 12px;
    border-radius: 5px;
    text-decoration: none;
    font-size: 12px;
}
.empty-state-small {
    text-align: center;
    padding: 40px;
    color: #999;
}
.empty-state-small i {
    font-size: 40px;
    margin-bottom: 10px;
}
@media (max-width: 768px) {
    .followups-grid {
        grid-template-columns: 1fr;
    }
    .followup-card {
        flex-direction: column;
        text-align: center;
    }
    .patient-details {
        justify-content: center;
    }
    .data-table {
        font-size: 12px;
    }
}
</style>