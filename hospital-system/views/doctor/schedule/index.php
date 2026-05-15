<div class="page-header">
    <h1 class="page-title">My Schedule</h1>
    <p>Set your weekly availability and leave dates</p>
</div>

<?php if (isset($success) && $success): ?>
    <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
<?php endif; ?>

<?php if (isset($error) && $error): ?>
    <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>

<div class="schedule-container">
    <!-- Weekly Availability Form -->
    <div class="schedule-card">
        <div class="card-header">
            <h3><i class="fas fa-calendar-week"></i> Weekly Availability</h3>
        </div>
        <div class="card-body">
            <form method="POST" action="<?php echo SITE_URL; ?>doctor.php?action=schedule&sub=update">
                <table class="schedule-table">
                    <thead>
                        <tr>
                            <th>Day</th>
                            <th>Available</th>
                            <th>Start Time</th>
                            <th>End Time</th>
                            <th>Slot Duration</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
                        $existing = [];
                        while($row = $availability->fetch_assoc()) {
                            $existing[$row['day_of_week']] = $row;
                        }
                        
                        foreach($days as $day):
                            $avail = $existing[$day] ?? null;
                            $checked = $avail && $avail['is_available'] ? 'checked' : '';
                            $startTime = $avail ? substr($avail['start_time'], 0, 5) : '09:00';
                            $endTime = $avail ? substr($avail['end_time'], 0, 5) : '17:00';
                            $duration = $avail ? $avail['slot_duration_minutes'] : 30;
                        ?>
                        <tr>
                            <td><strong><?php echo $day; ?></strong></td>
                            <td><input type="checkbox" name="available_<?php echo $day; ?>" value="1" <?php echo $checked; ?>></td>
                            <td><input type="time" name="start_time_<?php echo $day; ?>" class="time-input" value="<?php echo $startTime; ?>"></td>
                            <td><input type="time" name="end_time_<?php echo $day; ?>" class="time-input" value="<?php echo $endTime; ?>"></td>
                            <td>
                                <select name="slot_duration_<?php echo $day; ?>" class="duration-select">
                                    <option value="15" <?php echo $duration == 15 ? 'selected' : ''; ?>>15 min</option>
                                    <option value="30" <?php echo $duration == 30 ? 'selected' : ''; ?>>30 min</option>
                                    <option value="45" <?php echo $duration == 45 ? 'selected' : ''; ?>>45 min</option>
                                    <option value="60" <?php echo $duration == 60 ? 'selected' : ''; ?>>60 min</option>
                                </select>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                
                <div class="form-actions">
                    <button type="submit" class="btn-save">
                        <i class="fas fa-save"></i> Save Schedule
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Leave Dates -->
    <div class="schedule-card">
        <div class="card-header">
            <h3><i class="fas fa-umbrella-beach"></i> Leave Dates</h3>
        </div>
        <div class="card-body">
            <form method="POST" action="<?php echo SITE_URL; ?>doctor.php?action=schedule&sub=addLeave" class="leave-form">
                <div class="leave-input-group">
                    <input type="date" name="leave_date" class="form-control" required min="<?php echo date('Y-m-d'); ?>">
                    <input type="text" name="reason" class="form-control" placeholder="Reason (optional)">
                    <button type="submit" class="btn-add">Add Leave</button>
                </div>
            </form>
            
            <?php if ($leaves->num_rows > 0): ?>
                <table class="leaves-table">
                    <thead>
                        <tr><th>Date</th><th>Reason</th><th>Action</th></tr>
                    </thead>
                    <tbody>
                        <?php while($row = $leaves->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo date('M d, Y', strtotime($row['leave_date'])); ?></td>
                            <td><?php echo htmlspecialchars($row['reason'] ?: '-'); ?></td>
                            <td>
                                <a href="<?php echo SITE_URL; ?>doctor.php?action=schedule&sub=removeLeave&id=<?php echo $row['id']; ?>" 
                                   class="btn-remove" onclick="return confirm('Remove this leave date?')">Remove</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="text-muted">No leave dates scheduled.</p>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Info Box -->
    <div class="info-card">
        <h4><i class="fas fa-info-circle"></i> How it works</h4>
        <ul>
            <li>Set your working hours for each day of the week</li>
            <li>Slot duration determines how often patients can book</li>
            <li>Add leave dates to block appointments on specific days</li>
            <li>Patients will only see available slots based on your schedule</li>
        </ul>
    </div>
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
.schedule-container {
    display: flex;
    flex-direction: column;
    gap: 25px;
}
.schedule-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    overflow: hidden;
}
.card-header {
    padding: 18px 25px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}
.card-header h3 {
    margin: 0;
    font-size: 18px;
}
.card-body {
    padding: 25px;
}
.schedule-table {
    width: 100%;
    border-collapse: collapse;
}
.schedule-table th, .schedule-table td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid #eee;
}
.schedule-table th {
    background: #f8f9fa;
    font-weight: 600;
}
.time-input {
    padding: 6px 10px;
    border: 1px solid #ddd;
    border-radius: 6px;
    width: 100px;
}
.duration-select {
    padding: 6px 10px;
    border: 1px solid #ddd;
    border-radius: 6px;
}
.form-actions {
    margin-top: 20px;
    text-align: right;
}
.btn-save {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    color: white;
    padding: 10px 24px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
}
.leave-form {
    margin-bottom: 20px;
}
.leave-input-group {
    display: flex;
    gap: 10px;
}
.form-control {
    padding: 10px 12px;
    border: 1px solid #ddd;
    border-radius: 6px;
    flex: 1;
}
.btn-add {
    background: #667eea;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
}
.leaves-table {
    width: 100%;
    border-collapse: collapse;
}
.leaves-table th, .leaves-table td {
    padding: 10px;
    text-align: left;
    border-bottom: 1px solid #eee;
}
.btn-remove {
    background: #dc3545;
    color: white;
    padding: 4px 10px;
    border-radius: 4px;
    text-decoration: none;
    font-size: 12px;
}
.info-card {
    background: #e8eaf6;
    border-radius: 12px;
    padding: 20px;
}
.info-card h4 {
    margin: 0 0 15px 0;
    color: #3949ab;
}
.info-card ul {
    margin: 0;
    padding-left: 20px;
}
.info-card li {
    margin: 8px 0;
    color: #555;
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
    color: #999;
    text-align: center;
    padding: 20px;
}
@media (max-width: 768px) {
    .schedule-table, .schedule-table tbody, .schedule-table tr, .schedule-table td {
        display: block;
    }
    .schedule-table thead {
        display: none;
    }
    .schedule-table td {
        padding: 8px;
        border-bottom: none;
    }
    .leave-input-group {
        flex-direction: column;
    }
}
</style>