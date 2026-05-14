<div class="page-header">
    <h1 class="page-title">Add New Doctor</h1>
    <a href="<?php echo SITE_URL; ?>admin.php?action=doctors" class="btn-secondary">
        <i class="fas fa-arrow-left"></i> Back to List
    </a>
</div>

<?php if (isset($error) && $error): ?>
    <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>

<form method="POST" action="<?php echo SITE_URL; ?>admin.php?action=doctors&sub=store">
    <div class="form-grid">
        <div class="form-section">
            <h3><i class="fas fa-user"></i> Personal Information</h3>
            
            <div class="form-group">
                <label>Full Name <span class="required">*</span></label>
                <input type="text" name="name" class="form-control" required placeholder="Dr. John Doe">
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label>Email <span class="required">*</span></label>
                    <input type="email" name="email" class="form-control" required placeholder="doctor@hospital.com">
                </div>
                
                <div class="form-group">
                    <label>Phone <span class="required">*</span></label>
                    <input type="tel" name="phone" class="form-control" required placeholder="+1234567890">
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label>Password <span class="required">*</span></label>
                    <input type="password" name="password" class="form-control" required>
                    <small>Minimum 6 characters</small>
                </div>
                
                <div class="form-group">
                    <label>Confirm Password</label>
                    <input type="password" name="confirm_password" class="form-control">
                </div>
            </div>
        </div>
        
        <div class="form-section">
            <h3><i class="fas fa-stethoscope"></i> Professional Information</h3>
            
            <div class="form-row">
                <div class="form-group">
                    <label>Specialization <span class="required">*</span></label>
                    <select name="specialization_id" class="form-control" required>
                        <option value="">Select Specialization</option>
                        <?php while($spec = $specializations->fetch_assoc()): ?>
                        <option value="<?php echo $spec['id']; ?>"><?php echo htmlspecialchars($spec['name']); ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Consultation Fee ($) <span class="required">*</span></label>
                    <input type="number" name="consultation_fee" class="form-control" required step="0.01" placeholder="100.00">
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label>License Number</label>
                    <input type="text" name="license_number" class="form-control" placeholder="LIC123456">
                </div>
                
                <div class="form-group">
                    <label>Years of Experience</label>
                    <input type="number" name="experience_years" class="form-control" placeholder="0">
                </div>
            </div>
            
            <div class="form-group">
                <label>Bio / About</label>
                <textarea name="bio" class="form-control" rows="4" placeholder="Doctor's biography, education, achievements..."></textarea>
            </div>
        </div>
    </div>
    
    <div class="form-actions">
        <button type="submit" class="btn-submit">
            <i class="fas fa-save"></i> Create Doctor
        </button>
        <a href="<?php echo SITE_URL; ?>admin.php?action=doctors" class="btn-cancel">Cancel</a>
    </div>
</form>

<style>
.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
}

.page-title {
    font-size: 24px;
    margin: 0;
    color: #333;
}

.form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 30px;
}

.form-section {
    background: white;
    border-radius: 12px;
    padding: 25px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.form-section h3 {
    margin-top: 0;
    margin-bottom: 20px;
    padding-bottom: 10px;
    border-bottom: 2px solid #f0f0f0;
    color: #667eea;
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 15px;
}

.form-group {
    margin-bottom: 15px;
}

.form-group label {
    display: block;
    margin-bottom: 5px;
    font-weight: 500;
    font-size: 13px;
    color: #333;
}

.required {
    color: #dc3545;
}

.form-control {
    width: 100%;
    padding: 8px 12px;
    border: 1px solid #ddd;
    border-radius: 6px;
    font-size: 14px;
}

.form-control:focus {
    outline: none;
    border-color: #667eea;
}

small {
    font-size: 11px;
    color: #666;
    display: block;
    margin-top: 4px;
}

.form-actions {
    margin-top: 30px;
    display: flex;
    gap: 15px;
    justify-content: flex-end;
}

.btn-submit {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 10px 24px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
}

.btn-secondary {
    background: #6c757d;
    color: white;
    padding: 8px 16px;
    border-radius: 6px;
    text-decoration: none;
}

.btn-cancel {
    background: #f8f9fa;
    color: #666;
    padding: 10px 20px;
    border-radius: 6px;
    text-decoration: none;
    border: 1px solid #ddd;
}

@media (max-width: 768px) {
    .form-grid {
        grid-template-columns: 1fr;
        gap: 20px;
    }
    
    .form-row {
        grid-template-columns: 1fr;
        gap: 10px;
    }
}
</style>