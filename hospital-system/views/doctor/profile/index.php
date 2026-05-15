<div class="page-header">
    <h1 class="page-title">My Profile</h1>
</div>

<div id="alertContainer"></div>

<div class="profile-container">
    <!-- Personal Information -->
    <div class="profile-card">
        <div class="card-header">
            <h3><i class="fas fa-user-md"></i> Personal Information</h3>
        </div>
        <div class="card-body">
            <form id="profileForm">
                <div class="form-row">
                    <div class="form-group">
                        <label>Full Name *</label>
                        <input type="text" name="name" class="form-control" required 
                               value="<?php echo htmlspecialchars($profile['name']); ?>">
                    </div>
                    <div class="form-group">
                        <label>Email *</label>
                        <input type="email" name="email" class="form-control" required 
                               value="<?php echo htmlspecialchars($profile['email']); ?>">
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label>Phone *</label>
                        <input type="tel" name="phone" class="form-control" required 
                               value="<?php echo htmlspecialchars($profile['phone']); ?>">
                    </div>
                    <div class="form-group">
                        <label>License Number</label>
                        <input type="text" name="license_number" class="form-control" 
                               value="<?php echo htmlspecialchars($profile['license_number'] ?? ''); ?>">
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label>Specialization</label>
                        <select name="specialization_id" class="form-control" required>
                            <?php
                            $specSql = "SELECT id, name FROM specializations ORDER BY name";
                            $specResult = $this->db->query($specSql);
                            while($spec = $specResult->fetch_assoc()):
                            ?>
                            <option value="<?php echo $spec['id']; ?>" 
                                <?php echo ($profile['specialization_id'] == $spec['id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($spec['name']); ?>
                            </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Experience (Years)</label>
                        <input type="number" name="experience_years" class="form-control" 
                               value="<?php echo $profile['experience_years']; ?>">
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label>Consultation Fee ($)</label>
                        <input type="number" name="consultation_fee" class="form-control" step="0.01" 
                               value="<?php echo $profile['consultation_fee']; ?>">
                    </div>
                </div>
                
                <div class="form-group">
                    <label>Bio / About</label>
                    <textarea name="bio" class="form-control" rows="5" 
                              placeholder="Tell patients about your education, experience, specialties..."><?php 
                        echo htmlspecialchars($profile['bio'] ?? ''); 
                    ?></textarea>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn-save" id="saveBtn">
                        <i class="fas fa-save"></i> Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Change Password -->
    <div class="profile-card">
        <div class="card-header">
            <h3><i class="fas fa-lock"></i> Change Password</h3>
        </div>
        <div class="card-body">
            <form id="passwordForm">
                <div class="form-group">
                    <label>Current Password *</label>
                    <input type="password" name="current_password" class="form-control" required>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>New Password *</label>
                        <input type="password" name="new_password" class="form-control" required>
                        <small>Minimum 6 characters</small>
                    </div>
                    <div class="form-group">
                        <label>Confirm New Password *</label>
                        <input type="password" name="confirm_password" class="form-control" required>
                    </div>
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn-save" id="passwordBtn">
                        <i class="fas fa-key"></i> Change Password
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// AJAX for Profile Update
document.getElementById('profileForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    var formData = new FormData(this);
    var saveBtn = document.getElementById('saveBtn');
    var originalText = saveBtn.innerHTML;
    
    saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';
    saveBtn.disabled = true;
    
    var xhr = new XMLHttpRequest();
    xhr.open('POST', '<?php echo SITE_URL; ?>doctor.php?action=profile&sub=ajaxUpdate', true);
    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
    
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4) {
            saveBtn.innerHTML = originalText;
            saveBtn.disabled = false;
            
            if (xhr.status === 200) {
                try {
                    var response = JSON.parse(xhr.responseText);
                    showMessage(response.message, response.success ? 'success' : 'danger');
                    
                    if (response.success) {
                        // Update header name if changed
                        var nameField = document.querySelector('input[name="name"]');
                        if (nameField) {
                            var headerName = document.querySelector('.welcome-text strong');
                            if (headerName) {
                                headerName.textContent = nameField.value;
                            }
                        }
                    }
                } catch(e) {
                    showMessage('Error parsing response', 'danger');
                }
            } else {
                showMessage('Server error', 'danger');
            }
        }
    };
    
    xhr.send(formData);
});

// AJAX for Password Change
document.getElementById('passwordForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    var formData = new FormData(this);
    var passwordBtn = document.getElementById('passwordBtn');
    var originalText = passwordBtn.innerHTML;
    
    passwordBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Changing...';
    passwordBtn.disabled = true;
    
    var xhr = new XMLHttpRequest();
    xhr.open('POST', '<?php echo SITE_URL; ?>doctor.php?action=profile&sub=changePassword', true);
    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
    
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4) {
            passwordBtn.innerHTML = originalText;
            passwordBtn.disabled = false;
            
            if (xhr.status === 200) {
                try {
                    var response = JSON.parse(xhr.responseText);
                    showMessage(response.message, response.success ? 'success' : 'danger');
                    
                    if (response.success) {
                        document.getElementById('passwordForm').reset();
                    }
                } catch(e) {
                    showMessage('Error parsing response', 'danger');
                }
            } else {
                showMessage('Server error', 'danger');
            }
        }
    };
    
    xhr.send(formData);
});

function showMessage(message, type) {
    var container = document.getElementById('alertContainer');
    var alertDiv = document.createElement('div');
    alertDiv.className = 'alert alert-' + type;
    alertDiv.innerHTML = '<i class="fas fa-' + (type === 'success' ? 'check-circle' : 'exclamation-circle') + '"></i> ' + message;
    
    container.innerHTML = '';
    container.appendChild(alertDiv);
    
    setTimeout(function() {
        alertDiv.style.opacity = '0';
        setTimeout(function() {
            alertDiv.remove();
        }, 300);
    }, 3000);
}
</script>

<style>
/* Same styles as before */
.page-header {
    margin-bottom: 30px;
}
.page-title {
    font-size: 28px;
    margin: 0;
    color: #333;
}
.profile-container {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 25px;
}
.profile-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    overflow: hidden;
}
.card-header {
    padding: 20px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}
.card-header h3 {
    margin: 0;
    font-size: 18px;
    display: flex;
    align-items: center;
    gap: 10px;
}
.card-body {
    padding: 25px;
}
.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
    margin-bottom: 15px;
}
.form-group {
    margin-bottom: 15px;
}
.form-group label {
    display: block;
    margin-bottom: 8px;
    font-weight: 500;
    color: #333;
    font-size: 14px;
}
.form-control {
    width: 100%;
    padding: 10px 12px;
    border: 1px solid #ddd;
    border-radius: 8px;
    font-size: 14px;
}
.form-control:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}
textarea.form-control {
    resize: vertical;
}
small {
    display: block;
    margin-top: 5px;
    font-size: 11px;
    color: #666;
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
    display: inline-flex;
    align-items: center;
    gap: 8px;
}
.btn-save:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}
.alert {
    padding: 12px 20px;
    border-radius: 8px;
    margin-bottom: 20px;
    animation: slideIn 0.3s ease;
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
@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
@media (max-width: 1024px) {
    .profile-container {
        grid-template-columns: 1fr;
    }
}
@media (max-width: 768px) {
    .form-row {
        grid-template-columns: 1fr;
        gap: 0;
    }
    .form-actions {
        text-align: center;
    }
    .btn-save {
        width: 100%;
        justify-content: center;
    }
}
</style>