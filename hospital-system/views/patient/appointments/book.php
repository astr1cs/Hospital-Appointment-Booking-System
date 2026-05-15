<div class="page-header">
    <h1 class="page-title">Book Appointment</h1>
    <p>with Dr. <?php echo htmlspecialchars($doctor['name']); ?></p>
</div>

<?php if (isset($error) && $error): ?>
<div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>

<div class="booking-container">
    <div class="booking-form">
        <form id="bookingForm" method="POST" action="<?php echo SITE_URL; ?>patient.php?action=appointments&sub=store">
            <input type="hidden" name="doctor_id" value="<?php echo $doctor['user_id']; ?>">

            <div class="form-group">
                <label><i class="fas fa-calendar"></i> Select Date</label>
               <input type="date" name="date" id="appointmentDate" class="form-control" required 
       min="<?php echo date('Y-m-d'); ?>" 
       max="<?php echo date('Y-m-d', strtotime('+30 days')); ?>">
                <small>Select a date to see available time slots</small>
            </div>

            <div class="form-group">
                <label><i class="fas fa-clock"></i> Select Time Slot</label>
                <div id="timeSlots" class="time-slots">
                    <p class="text-muted">Please select a date first</p>
                </div>
                <input type="hidden" name="time" id="selectedTime" required>
            </div>

            <div class="form-group">
                <label><i class="fas fa-user-friends"></i> Book For</label>
                <select name="dependent_id" class="form-control">
                    <option value="">Myself</option>
                    <?php while($dependent = $dependents->fetch_assoc()): ?>
                    <option value="<?php echo $dependent['id']; ?>">
                        <?php echo htmlspecialchars($dependent['name']); ?>
                        (<?php echo htmlspecialchars($dependent['relationship']); ?>)
                    </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="form-group">
                <label><i class="fas fa-notes-medical"></i> Reason for Visit</label>
                <textarea name="reason" class="form-control" rows="4" required
                    placeholder="Describe your symptoms or reason for consultation..."></textarea>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-submit" id="submitBtn" disabled>
                    <i class="fas fa-calendar-check"></i> Confirm Booking
                </button>
                <a href="<?php echo SITE_URL; ?>patient.php?action=doctors&sub=show&id=<?php echo $doctor['user_id']; ?>"
                    class="btn-cancel">Cancel</a>
            </div>
        </form>
    </div>

    <div class="booking-info">
        <div class="info-card">
            <h3><i class="fas fa-info-circle"></i> Appointment Info</h3>
            <p><strong>Doctor:</strong> Dr. <?php echo htmlspecialchars($doctor['name']); ?></p>
            <p><strong>Specialization:</strong> <?php echo htmlspecialchars($doctor['specialization_name']); ?></p>
            <p><strong>Consultation Fee:</strong> $<?php echo number_format($doctor['consultation_fee'], 2); ?></p>
            <p><strong>Experience:</strong> <?php echo $doctor['experience_years']; ?>+ years</p>
        </div>

        <div class="info-card">
            <h3><i class="fas fa-clock"></i> Cancellation Policy</h3>
            <p>You can cancel your appointment up to <strong>2 hours</strong> before the scheduled time.</p>
            <p>Cancellations after that will be considered as no-show.</p>
        </div>
    </div>
</div>

<script>
document.getElementById('appointmentDate').addEventListener('change', function() {
    var date = this.value;
    var doctorId = <?php echo $doctor['user_id']; ?>;
    var slotsDiv = document.getElementById('timeSlots');

    if (date) {
        // Show loading
        slotsDiv.innerHTML =
            '<div class="loading-slots"><i class="fas fa-spinner fa-spin"></i> Loading available slots...</div>';

        // AJAX request
        var xhr = new XMLHttpRequest();
        xhr.open('POST', '<?php echo SITE_URL; ?>patient.php?action=appointments&sub=getSlots', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                // Remove BOM character and any leading invisible characters
                var responseText = xhr.responseText.replace(/^\uFEFF/, '').trim();
                console.log('Response:', responseText);
                try {
                    var response = JSON.parse(responseText);
                    if (response.success) {
                        if (response.slots && response.slots.length > 0) {
                            displaySlots(response.slots);
                        } else {
                            slotsDiv.innerHTML =
                                '<p class="text-muted">No available slots for this date. Please select another date.</p>';
                            document.getElementById('submitBtn').disabled = true;
                        }
                    } else {
                        slotsDiv.innerHTML = '<p class="text-muted">' + (response.message ||
                            'Error loading slots') + '</p>';
                        document.getElementById('submitBtn').disabled = true;
                    }
                } catch (e) {
                    console.error('Error:', e);
                    slotsDiv.innerHTML = '<p class="text-muted">Error loading slots. Please try again.</p>';
                    document.getElementById('submitBtn').disabled = true;
                }
            }
        };

        xhr.send('doctor_id=' + encodeURIComponent(doctorId) + '&date=' + encodeURIComponent(date));
    }
});

function displaySlots(slots) {
    var slotsDiv = document.getElementById('timeSlots');
    var selectedTimeInput = document.getElementById('selectedTime');

    if (!slots || slots.length === 0) {
        slotsDiv.innerHTML = '<p class="text-muted">No available time slots for this date.</p>';
        document.getElementById('submitBtn').disabled = true;
        return;
    }

    var html = '<div class="slots-grid">';
    for (var i = 0; i < slots.length; i++) {
        var slot = slots[i];
        var statusClass = slot.available ? 'slot-available' : 'slot-booked';
        var onclickAttr = slot.available ? 'onclick="selectSlot(this, \'' + slot.time_value + '\')"' : '';
        html += '<div class="slot ' + statusClass + '" ' + onclickAttr + ' data-time="' + slot.time_value + '">' + slot
            .time + '</div>';
    }
    html += '</div>';

    slotsDiv.innerHTML = html;
    document.getElementById('submitBtn').disabled = true;
}

function selectSlot(element, timeValue) {
    // Remove selected class from all slots
    var slots = document.querySelectorAll('.slot');
    for (var i = 0; i < slots.length; i++) {
        slots[i].classList.remove('slot-selected');
    }

    // Add selected class to clicked slot
    element.classList.add('slot-selected');

    // Set hidden input value
    document.getElementById('selectedTime').value = timeValue;

    // Enable submit button
    document.getElementById('submitBtn').disabled = false;
}

// Make sure the hidden input exists
if (!document.getElementById('selectedTime')) {
    var hiddenInput = document.createElement('input');
    hiddenInput.type = 'hidden';
    hiddenInput.name = 'time';
    hiddenInput.id = 'selectedTime';
    document.querySelector('#bookingForm').appendChild(hiddenInput);
}
</script>



<style>
.page-header {
    margin-bottom: 30px;
}

.page-title {
    font-size: 28px;
    margin: 0 0 5px 0;
    color: #333;
}

.booking-container {
    display: grid;
    grid-template-columns: 1fr 350px;
    gap: 30px;
}

.booking-form {
    background: white;
    border-radius: 12px;
    padding: 30px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.form-group {
    margin-bottom: 25px;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    font-weight: 600;
    color: #333;
}

.form-control {
    width: 100%;
    padding: 12px;
    border: 1px solid #ddd;
    border-radius: 8px;
    font-size: 14px;
}

.form-control:focus {
    outline: none;
    border-color: #667eea;
}

textarea.form-control {
    resize: vertical;
}

.time-slots {
    min-height: 150px;
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

.slot-booked {
    background: #ffebee;
    color: #c62828;
    border: 1px solid #ef9a9a;
    cursor: not-allowed;
}

.slot-selected {
    background: #667eea;
    color: white;
    border-color: #667eea;
}

.loading-slots {
    text-align: center;
    padding: 30px;
    color: #666;
}

.booking-info {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.info-card {
    background: white;
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.info-card h3 {
    margin: 0 0 15px 0;
    padding-bottom: 10px;
    border-bottom: 2px solid #f0f0f0;
    color: #667eea;
}

.info-card p {
    margin: 10px 0;
    color: #555;
}

.form-actions {
    display: flex;
    gap: 15px;
    margin-top: 30px;
}

.btn-submit {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 12px 30px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-size: 16px;
}

.btn-submit:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.btn-cancel {
    background: #6c757d;
    color: white;
    padding: 12px 30px;
    border-radius: 8px;
    text-decoration: none;
}

.alert {
    padding: 12px 20px;
    border-radius: 8px;
    margin-bottom: 20px;
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
    font-size: 12px;
    color: #666;
}

@media (max-width: 768px) {
    .booking-container {
        grid-template-columns: 1fr;
    }

    .slots-grid {
        grid-template-columns: repeat(3, 1fr);
    }
}
</style>