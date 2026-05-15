<div class="page-header">
    <h1 class="page-title">My Profile</h1>
</div>

<?php if (isset($success) && $success): ?>
    <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
<?php endif; ?>

<?php if (isset($error) && $error): ?>
    <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>

<div class="profile-container">
    <!-- Profile Information -->
    <div class="profile-card">
        <div class="card-header">
            <h3><i class="fas fa-user-circle"></i> Personal Information</h3>
        </div>
        <div class="card-body">
            <form method="POST" action="<?php echo SITE_URL; ?>patient.php?action=profile&sub=update">
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
                        <label>Date of Birth</label>
                        <input type="date" name="date_of_birth" class="form-control" 
                               value="<?php echo $profile['date_of_birth']; ?>">
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label>Blood Group</label>
                        <select name="blood_group" class="form-control">
                            <option value="">Select</option>
                            <option value="A+" <?php echo $profile['blood_group'] == 'A+' ? 'selected' : ''; ?>>A+</option>
                            <option value="A-" <?php echo $profile['blood_group'] == 'A-' ? 'selected' : ''; ?>>A-</option>
                            <option value="B+" <?php echo $profile['blood_group'] == 'B+' ? 'selected' : ''; ?>>B+</option>
                            <option value="B-" <?php echo $profile['blood_group'] == 'B-' ? 'selected' : ''; ?>>B-</option>
                            <option value="O+" <?php echo $profile['blood_group'] == 'O+' ? 'selected' : ''; ?>>O+</option>
                            <option value="O-" <?php echo $profile['blood_group'] == 'O-' ? 'selected' : ''; ?>>O-</option>
                            <option value="AB+" <?php echo $profile['blood_group'] == 'AB+' ? 'selected' : ''; ?>>AB+</option>
                            <option value="AB-" <?php echo $profile['blood_group'] == 'AB-' ? 'selected' : ''; ?>>AB-</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Gender</label>
                        <select name="gender" class="form-control">
                            <option value="">Select</option>
                            <option value="male" <?php echo $profile['gender'] == 'male' ? 'selected' : ''; ?>>Male</option>
                            <option value="female" <?php echo $profile['gender'] == 'female' ? 'selected' : ''; ?>>Female</option>
                            <option value="other" <?php echo $profile['gender'] == 'other' ? 'selected' : ''; ?>>Other</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-group">
                    <label>Address</label>
                    <textarea name="address" class="form-control" rows="3"><?php echo htmlspecialchars($profile['address'] ?? ''); ?></textarea>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label>Emergency Contact Name</label>
                        <input type="text" name="emergency_contact_name" class="form-control" 
                               value="<?php echo htmlspecialchars($profile['emergency_contact_name'] ?? ''); ?>">
                    </div>
                    <div class="form-group">
                        <label>Emergency Contact Phone</label>
                        <input type="tel" name="emergency_contact_phone" class="form-control" 
                               value="<?php echo htmlspecialchars($profile['emergency_contact_phone'] ?? ''); ?>">
                    </div>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn-save">
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
            <form method="POST" action="<?php echo SITE_URL; ?>patient.php?action=profile&sub=changePassword">
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
                    <button type="submit" class="btn-save">
                        <i class="fas fa-key"></i> Change Password
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
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
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 10px 24px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.btn-save:hover {
    transform: translateY(-1px);
    box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
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

@media (max-width: 1024px) {
    .profile-container {
        grid-template-columns: 1fr;
        gap: 20px;
    }
}

@media (max-width: 768px) {
    .form-row {
        grid-template-columns: 1fr;
        gap: 0;
    }
    
    .card-body {
        padding: 20px;
    }
}
</style>