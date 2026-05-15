<div class="page-header">
    <h1 class="page-title">Today's Schedule</h1>
    <p><?php echo date('l, F d, Y'); ?></p>
</div>

<!-- Statistics Summary -->
<div class="stats-row">
    <div class="stat-mini">
        <span class="stat-label">Total</span>
        <span class="stat-number"><?php echo $stats['total']; ?></span>
    </div>
    <div class="stat-mini">
        <span class="stat-label">Pending</span>
        <span class="stat-number"><?php echo $stats['pending']; ?></span>
    </div>
    <div class="stat-mini">
        <span class="stat-label">Confirmed</span>
        <span class="stat-number"><?php echo $stats['confirmed']; ?></span>
    </div>
    <div class="stat-mini">
        <span class="stat-label">Checked In</span>
        <span class="stat-number"><?php echo $stats['checked_in']; ?></span>
    </div>
    <div class="stat-mini">
        <span class="stat-label">Completed</span>
        <span class="stat-number"><?php echo $stats['completed']; ?></span>
    </div>
</div>

<div class="appointments-list">
    <?php if ($appointments->num_rows > 0): ?>
        <?php while($row = $appointments->fetch_assoc()): ?>
        <div class="appointment-card status-<?php echo $row['status']; ?>" id="appointment-<?php echo $row['id']; ?>">
            <div class="appointment-time">
                <?php echo date('h:i A', strtotime($row['appointment_time'])); ?>
            </div>
            <div class="appointment-info">
                <h3><?php echo htmlspecialchars($row['patient_name']); ?></h3>
                <div class="patient-details">
                    <span><i class="fas fa-phone"></i> <?php echo htmlspecialchars($row['patient_phone']); ?></span>
                    <span><i class="fas fa-envelope"></i> <?php echo htmlspecialchars($row['patient_email']); ?></span>
                    <?php if ($row['blood_group']): ?>
                    <span><i class="fas fa-tint"></i> <?php echo $row['blood_group']; ?></span>
                    <?php endif; ?>
                </div>
                <?php if ($row['reason']): ?>
                <div class="reason">
                    <strong>Reason:</strong> <?php echo htmlspecialchars($row['reason']); ?>
                </div>
                <?php endif; ?>
            </div>
            <div class="appointment-actions">
                <?php if ($row['status'] == 'pending'): ?>
                    <a href="<?php echo SITE_URL; ?>doctor.php?action=appointments&sub=confirm&id=<?php echo $row['id']; ?>" 
                       class="btn-confirm">Confirm</a>
                    <a href="<?php echo SITE_URL; ?>doctor.php?action=appointments&sub=reject&id=<?php echo $row['id']; ?>" 
                       class="btn-reject" onclick="return confirm('Reject this appointment?')">Reject</a>
                <?php elseif ($row['status'] == 'confirmed'): ?>
                    <button class="btn-checkin" onclick="checkIn(<?php echo $row['id']; ?>)">
                        <i class="fas fa-user-check"></i> Check In
                    </button>
                <?php elseif ($row['status'] == 'checked_in'): ?>
                    <a href="<?php echo SITE_URL; ?>doctor.php?action=consultation&sub=create&id=<?php echo $row['id']; ?>" 
                       class="btn-consult">Start Consultation</a>
                <?php elseif ($row['status'] == 'completed'): ?>
                    <span class="completed-badge">Completed</span>
                    <a href="<?php echo SITE_URL; ?>doctor.php?action=appointments&sub=view&id=<?php echo $row['id']; ?>" 
                       class="btn-view">View Notes</a>
                <?php endif; ?>
                
                <a href="<?php echo SITE_URL; ?>doctor.php?action=appointments&sub=noshow&id=<?php echo $row['id']; ?>" 
                   class="btn-noshow" onclick="return confirm('Mark as no-show?')">No Show</a>
            </div>
        </div>
        <?php endwhile; ?>
    <?php else: ?>
        <div class="empty-state">
            <i class="fas fa-calendar-day"></i>
            <h3>No Appointments Today</h3>
            <p>You have no appointments scheduled for today.</p>
        </div>
    <?php endif; ?>
</div>

<!-- AJAX Script for Check-In -->
<script>
function checkIn(appointmentId) {
    var xhr = new XMLHttpRequest();
    xhr.open('POST', '<?php echo SITE_URL; ?>doctor.php?action=appointments&sub=checkin', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            var response = JSON.parse(xhr.responseText);
            if (response.success) {
                var card = document.getElementById('appointment-' + appointmentId);
                card.classList.remove('status-confirmed');
                card.classList.add('status-checked_in');
                
                var actionsDiv = card.querySelector('.appointment-actions');
                actionsDiv.innerHTML = '<a href="<?php echo SITE_URL; ?>doctor.php?action=consultation&sub=create&id=' + appointmentId + '" class="btn-consult">Start Consultation</a> <a href="<?php echo SITE_URL; ?>doctor.php?action=appointments&sub=noshow&id=' + appointmentId + '" class="btn-noshow" onclick="return confirm(\'Mark as no-show?\')">No Show</a>';
                
                showMessage(response.message, 'success');
            } else {
                showMessage(response.message, 'error');
            }
        }
    };
    
    xhr.send('appointment_id=' + encodeURIComponent(appointmentId));
}

function showMessage(message, type) {
    var alertDiv = document.createElement('div');
    alertDiv.className = 'alert alert-' + type;
    alertDiv.innerHTML = '<i class="fas fa-' + (type === 'success' ? 'check-circle' : 'exclamation-circle') + '"></i> ' + message;
    
    var container = document.querySelector('.doctor-content');
    container.insertBefore(alertDiv, container.firstChild);
    
    setTimeout(function() {
        alertDiv.remove();
    }, 3000);
}
</script>

<style>
.page-header {
    margin-bottom: 25px;
}
.page-title {
    font-size: 28px;
    margin: 0 0 5px 0;
    color: #333;
}
.stats-row {
    display: flex;
    gap: 15px;
    margin-bottom: 25px;
    flex-wrap: wrap;
}
.stat-mini {
    background: white;
    padding: 10px 20px;
    border-radius: 8px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}
.stat-label {
    font-size: 12px;
    color: #666;
    margin-right: 10px;
}
.stat-number {
    font-size: 20px;
    font-weight: bold;
    color: #667eea;
}
.appointments-list {
    display: flex;
    flex-direction: column;
    gap: 15px;
}
.appointment-card {
    background: white;
    border-radius: 12px;
    padding: 20px;
    display: flex;
    align-items: center;
    gap: 20px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    border-left: 4px solid #ccc;
}
.appointment-card.status-pending { border-left-color: #ffc107; }
.appointment-card.status-confirmed { border-left-color: #28a745; }
.appointment-card.status-checked_in { border-left-color: #17a2b8; background: #f0f8ff; }
.appointment-card.status-completed { border-left-color: #6c757d; opacity: 0.8; }
.appointment-time {
    font-size: 20px;
    font-weight: bold;
    color: #667eea;
    min-width: 100px;
}
.appointment-info {
    flex: 1;
}
.appointment-info h3 {
    margin: 0 0 8px 0;
    font-size: 18px;
}
.patient-details {
    display: flex;
    gap: 15px;
    flex-wrap: wrap;
    font-size: 13px;
    color: #666;
    margin-bottom: 8px;
}
.patient-details i {
    width: 16px;
    margin-right: 4px;
}
.reason {
    font-size: 13px;
    color: #555;
}
.appointment-actions {
    display: flex;
    gap: 10px;
}
.btn-confirm, .btn-reject, .btn-checkin, .btn-consult, .btn-noshow, .btn-view {
    padding: 8px 16px;
    border-radius: 6px;
    text-decoration: none;
    font-size: 13px;
    border: none;
    cursor: pointer;
}
.btn-confirm { background: #28a745; color: white; }
.btn-reject { background: #dc3545; color: white; }
.btn-checkin { background: #17a2b8; color: white; }
.btn-consult { background: #667eea; color: white; }
.btn-noshow { background: #6c757d; color: white; }
.btn-view { background: #e8eaf6; color: #3949ab; }
.completed-badge {
    background: #d4edda;
    color: #155724;
    padding: 6px 12px;
    border-radius: 6px;
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
    .appointment-card {
        flex-direction: column;
        text-align: center;
    }
    .appointment-actions {
        flex-wrap: wrap;
        justify-content: center;
    }
}
</style>