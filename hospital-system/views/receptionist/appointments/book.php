<div class="page-header">
    <h1 class="page-title">Walk-in Booking</h1>
    <p>Book an appointment for a patient</p>
</div>

<?php if (isset($success) && $success): ?>
    <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
<?php endif; ?>

<?php if (isset($error) && $error): ?>
    <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>

<div class="booking-container">
    <!-- Patient Selection -->
    <div class="booking-card">
        <div class="card-header">
            <h3><i class="fas fa-user"></i> Patient Information</h3>
        </div>
        <div class="card-body">
            <div class="patient-tabs">
                <button class="tab-btn active" onclick="showTab('existing')">Existing Patient</button>
                <button class="tab-btn" onclick="showTab('new')">New Patient</button>
            </div>
            
            <!-- Existing Patient Form -->
            <div id="existingPatient" class="tab-content active">
                <form method="POST" action="<?php echo SITE_URL; ?>receptionist.php?action=appointments&sub=store">
                    <div class="form-group">
                        <label>Select Patient</label>
                        <select name="patient_id" class="form-control" required>
                            <option value="">-- Select Patient --</option>
                            <?php while($row = $patients->fetch_assoc()): ?>
                            <option value="<?php echo $row['id']; ?>">
                                <?php echo htmlspecialchars($row['name']); ?> (<?php echo htmlspecialchars($row['phone']); ?>)
                            </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    
                    <div id="appointmentSection" style="display: none;">
                        <!-- Appointment details will be shown after patient selection -->
                    </div>
                </form>
            </div>
            
            <!-- New Patient Form -->
            <div id="newPatient" class="tab-content">
                <form method="POST" action="<?php echo SITE_URL; ?>receptionist.php?action=appointments&sub=registerPatient">
                    <div class="form-row">
                        <div class="form-group">
                            <label>Full Name *</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Email *</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Phone *</label>
                            <input type="tel" name="phone" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Password</label>
                            <input type="text" name="password" class="form-control" value="password123">
                            <small>Default: password123</small>
                        </div>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn-register">
                            <i class="fas fa-user-plus"></i> Register Patient
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Appointment Details -->
    <div class="booking-card" id="appointmentCard" style="display: none;">
        <div class="card-header">
            <h3><i class="fas fa-calendar-plus"></i> Appointment Details</h3>
        </div>
        <div class="card-body">
            <form id="appointmentForm" method="POST" action="<?php echo SITE_URL; ?>receptionist.php?action=appointments&sub=store">
                <input type="hidden" name="patient_id" id="selectedPatientId">
                
                <div class="form-group">
                    <label>Select Doctor *</label>
                    <select name="doctor_id" id="doctorSelect" class="form-control" required>
                        <option value="">-- Select Doctor --</option>
                        <?php while($row = $doctors->fetch_assoc()): ?>
                        <option value="<?php echo $row['id']; ?>" data-fee="<?php echo $row['consultation_fee']; ?>">
                            Dr. <?php echo htmlspecialchars($row['name']); ?> - <?php echo htmlspecialchars($row['specialization_name']); ?> ($<?php echo $row['consultation_fee']; ?>)
                        </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Appointment Date *</label>
                    <input type="date" name="date" id="appointmentDate" class="form-control" required 
                           min="<?php echo date('Y-m-d'); ?>" 
                           max="<?php echo date('Y-m-d', strtotime('+30 days')); ?>">
                </div>
                
                <div class="form-group">
                    <label>Select Time Slot *</label>
                    <div id="timeSlots" class="time-slots">
                        <p class="text-muted">Select a doctor and date first</p>
                    </div>
                    <input type="hidden" name="time" id="selectedTime" required>
                </div>
                
                <div class="form-group">
                    <label>Reason for Visit</label>
                    <textarea name="reason" class="form-control" rows="3" placeholder="Walk-in appointment">Walk-in appointment</textarea>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn-book" id="bookBtn" disabled>
                        <i class="fas fa-calendar-check"></i> Book Appointment
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function showTab(tab) {
    document.getElementById('existingPatient').classList.remove('active');
    document.getElementById('newPatient').classList.remove('active');
    document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
    
    if (tab === 'existing') {
        document.getElementById('existingPatient').classList.add('active');
        document.querySelector('.tab-btn:first-child').classList.add('active');
    } else {
        document.getElementById('newPatient').classList.add('active');
        document.querySelector('.tab-btn:last-child').classList.add('active');
        document.getElementById('appointmentCard').style.display = 'none';
    }
}

// When patient is selected from dropdown
document.querySelector('select[name="patient_id"]').addEventListener('change', function() {
    var patientId = this.value;
    if (patientId) {
        document.getElementById('selectedPatientId').value = patientId;
        document.getElementById('appointmentCard').style.display = 'block';
    } else {
        document.getElementById('appointmentCard').style.display = 'none';
    }
});

// When doctor is selected
document.getElementById('doctorSelect').addEventListener('change', function() {
    loadSlots();
});

// When date changes
document.getElementById('appointmentDate').addEventListener('change', function() {
    loadSlots();
});

function loadSlots() {
    var doctorId = document.getElementById('doctorSelect').value;
    var date = document.getElementById('appointmentDate').value;
    var slotsDiv = document.getElementById('timeSlots');
    
    if (!doctorId || !date) {
        slotsDiv.innerHTML = '<p class="text-muted">Select a doctor and date first</p>';
        document.getElementById('bookBtn').disabled = true;
        return;
    }
    
    slotsDiv.innerHTML = '<div class="loading-slots"><i class="fas fa-spinner fa-spin"></i> Loading slots...</div>';
    
    var xhr = new XMLHttpRequest();
    xhr.open('POST', '<?php echo SITE_URL; ?>receptionist.php?action=appointments&sub=getSlots', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            try {
                var response = JSON.parse(xhr.responseText);
                if (response.success) {
                    displaySlots(response.slots);
                } else {
                    slotsDiv.innerHTML = '<p class="text-muted">' + response.message + '</p>';
                    document.getElementById('bookBtn').disabled = true;
                }
            } catch(e) {
                slotsDiv.innerHTML = '<p class="text-muted">Error loading slots</p>';
                document.getElementById('bookBtn').disabled = true;
            }
        }
    };
    
    xhr.send('doctor_id=' + encodeURIComponent(doctorId) + '&date=' + encodeURIComponent(date));
}

function displaySlots(slots) {
    var slotsDiv = document.getElementById('timeSlots');
    var bookBtn = document.getElementById('bookBtn');
    
    if (!slots || slots.length === 0) {
        slotsDiv.innerHTML = '<p class="text-muted">No available slots for this date</p>';
        bookBtn.disabled = true;
        return;
    }
    
    var html = '<div class="slots-grid">';
    for (var i = 0; i < slots.length; i++) {
        var slot = slots[i];
        var statusClass = slot.available ? 'slot-available' : 'slot-booked';
        var onclickAttr = slot.available ? 'onclick="selectSlot(this, \'' + slot.time_value + '\')"' : '';
        html += '<div class="slot ' + statusClass + '" ' + onclickAttr + '>' + slot.time + '</div>';
    }
    html += '</div>';
    
    slotsDiv.innerHTML = html;
    bookBtn.disabled = true;
}

function selectSlot(element, timeValue) {
    document.querySelectorAll('.slot').forEach(slot => slot.classList.remove('slot-selected'));
    element.classList.add('slot-selected');
    document.getElementById('selectedTime').value = timeValue;
    document.getElementById('bookBtn').disabled = false;
}

// Handle new patient registration success
<?php if (isset($_SESSION['new_patient_id'])): ?>
    document.getElementById('appointmentCard').style.display = 'block';
    document.getElementById('selectedPatientId').value = '<?php echo $_SESSION['new_patient_id']; ?>';
    <?php unset($_SESSION['new_patient_id']); ?>
<?php endif; ?>
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
.booking-container {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 25px;
}
.booking-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    overflow: hidden;
}
.card-header {
    padding: 18px 20px;
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
.patient-tabs {
    display: flex;
    gap: 10px;
    margin-bottom: 20px;
    border-bottom: 1px solid #eee;
    padding-bottom: 10px;
}
.tab-btn {
    background: none;
    border: none;
    padding: 8px 20px;
    cursor: pointer;
    border-radius: 20px;
    transition: all 0.3s;
}
.tab-btn.active {
    background: #667eea;
    color: white;
}
.tab-content {
    display: none;
}
.tab-content.active {
    display: block;
}
.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
    margin-bottom: 15px;
}
.form-group {
    margin-bottom: 20px;
}
.form-group label {
    display: block;
    margin-bottom: 8px;
    font-weight: 500;
    color: #333;
}
.form-control {
    width: 100%;
    padding: 10px 12px;
    border: 1px solid #ddd;
    border-radius: 8px;
    font-size: 14px;
}
select.form-control {
    cursor: pointer;
}
.time-slots {
    min-height: 120px;
}
.slots-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 10px;
}
.slot {
    padding: 10px;
    text-align: center;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.3s;
}
.slot-available {
    background: #e8f5e9;
    color: #2e7d32;
    border: 1px solid #a5d6a7;
}
.slot-available:hover {
    background: #c8e6c9;
    transform: scale(1.02);
}
.slot-selected {
    background: #667eea;
    color: white;
}
.slot-booked {
    background: #ffebee;
    color: #c62828;
    cursor: not-allowed;
}
.form-actions {
    margin-top: 20px;
}
.btn-register, .btn-book {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    color: white;
    padding: 10px 24px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    width: 100%;
}
.btn-book:disabled {
    opacity: 0.5;
    cursor: not-allowed;
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
    padding: 20px;
    color: #999;
}
small {
    display: block;
    margin-top: 5px;
    font-size: 11px;
    color: #666;
}
@media (max-width: 1024px) {
    .booking-container {
        grid-template-columns: 1fr;
    }
}
@media (max-width: 768px) {
    .form-row {
        grid-template-columns: 1fr;
        gap: 0;
    }
    .slots-grid {
        grid-template-columns: repeat(3, 1fr);
    }
}
</style>