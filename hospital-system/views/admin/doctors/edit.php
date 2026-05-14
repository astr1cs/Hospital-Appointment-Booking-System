<?php if (isset($error) && $error): ?>
    <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>

<div class="page-header">
    <h1 class="page-title">Edit Doctor</h1>
    <a href="<?php echo SITE_URL; ?>admin.php?action=doctors" class="btn-secondary">
        <i class="fas fa-arrow-left"></i> Back to List
    </a>
</div>

<form method="POST" action="<?php echo SITE_URL; ?>admin.php?action=doctors&sub=update&id=<?php echo $doctor['id']; ?>">
    <div class="form-grid">
        <div class="form-section">
            <h3><i class="fas fa-user"></i> Personal Information</h3>
            
            <div class="form-group">
                <label>Full Name <span class="required">*</span></label>
                <input type="text" name="name" class="form-control" required value="<?php echo htmlspecialchars($doctor['name']); ?>">
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label>Email <span class="required">*</span></label>
                    <input type="email" name="email" class="form-control" required value="<?php echo htmlspecialchars($doctor['email']); ?>">
                </div>
                
                <div class="form-group">
                    <label>Phone <span class="required">*</span></label>
                    <input type="tel" name="phone" class="form-control" required value="<?php echo htmlspecialchars($doctor['phone']); ?>">
                </div>
            </div>
            
            <div class="form-group">
                <label>Status</label>
                <div class="status-badge <?php echo $doctor['is_active'] ? 'status-active' : 'status-inactive'; ?>">
                    <?php echo $doctor['is_active'] ? 'Active' : 'Inactive'; ?>
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
                        <option value="<?php echo $spec['id']; ?>" <?php echo ($spec['id'] == $doctor['specialization_id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($spec['name']); ?>
                        </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Consultation Fee ($) <span class="required">*</span></label>
                    <input type="number" name="consultation_fee" class="form-control" required step="0.01" value="<?php echo $doctor['consultation_fee']; ?>">
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label>License Number</label>
                    <input type="text" name="license_number" class="form-control" value="<?php echo htmlspecialchars($doctor['license_number'] ?? ''); ?>">
                </div>
                
                <div class="form-group">
                    <label>Years of Experience</label>
                    <input type="number" name="experience_years" class="form-control" value="<?php echo $doctor['experience_years']; ?>">
                </div>
            </div>
            
            <div class="form-group">
                <label>Bio / About</label>
                <textarea name="bio" class="form-control" rows="4"><?php echo htmlspecialchars($doctor['bio'] ?? ''); ?></textarea>
            </div>
        </div>
    </div>
    
    <div class="form-actions">
        <button type="submit" class="btn-submit">
            <i class="fas fa-save"></i> Update Doctor
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

.btn-secondary {
    background: #6c757d;
    color: white;
    padding: 8px 16px;
    border-radius: 6px;
    text-decoration: none;
}

.btn-secondary:hover {
    background: #5a6268;
    color: white;
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

.status-badge {
    display: inline-block;
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 500;
}

.status-active {
    background: #d4edda;
    color: #155724;
}

.status-inactive {
    background: #f8d7da;
    color: #721c24;
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

.btn-submit:hover {
    transform: translateY(-1px);
    box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
}

.btn-cancel {
    background: #f8f9fa;
    color: #666;
    padding: 10px 20px;
    border-radius: 6px;
    text-decoration: none;
    border: 1px solid #ddd;
}

.btn-cancel:hover {
    background: #e9ecef;
    color: #333;
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