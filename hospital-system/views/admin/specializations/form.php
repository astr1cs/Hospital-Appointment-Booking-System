<div class="page-header">
    <h1 class="page-title"><?php echo $specialization ? 'Edit Specialization' : 'Add New Specialization'; ?></h1>
    <a href="<?php echo SITE_URL; ?>admin.php?action=specializations" class="btn-secondary">
        <i class="fas fa-arrow-left"></i> Back to List
    </a>
</div>

<?php if (isset($error) && $error): ?>
    <div class="alert alert-danger">
        <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error); ?>
    </div>
<?php endif; ?>

<div class="admin-card">
    <div class="card-header">
        <h3><i class="fas fa-tag"></i> Specialization Details</h3>
    </div>
    <div class="card-body">
        <form method="POST" action="<?php echo SITE_URL; ?>admin.php?action=specializations&sub=<?php echo $specialization ? 'update&id=' . $specialization['id'] : 'store'; ?>">
            
            <div class="form-group">
                <label for="name">Specialization Name <span class="required">*</span></label>
                <input type="text" 
                       id="name" 
                       name="name" 
                       class="form-control" 
                       value="<?php echo htmlspecialchars($specialization['name'] ?? ''); ?>" 
                       required 
                       placeholder="e.g., Cardiology, Dermatology, Neurology">
                <small>Enter a unique specialization name.</small>
            </div>
            
            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" 
                          name="description" 
                          class="form-control" 
                          rows="5" 
                          placeholder="Describe what this specialization covers..."><?php echo htmlspecialchars($specialization['description'] ?? ''); ?></textarea>
                <small>Optional: Provide a brief description of this medical specialization.</small>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn-submit">
                    <i class="fas fa-save"></i> <?php echo $specialization ? 'Update Specialization' : 'Save Specialization'; ?>
                </button>
                <a href="<?php echo SITE_URL; ?>admin.php?action=specializations" class="btn-cancel">
                    <i class="fas fa-times"></i> Cancel
                </a>
            </div>
            
        </form>
    </div>
</div>

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
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: all 0.3s;
}

.btn-secondary:hover {
    background: #5a6268;
    color: white;
    text-decoration: none;
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

.required {
    color: #dc3545;
}

.form-control {
    width: 100%;
    padding: 10px 12px;
    border: 1px solid #ddd;
    border-radius: 6px;
    font-size: 14px;
    transition: border-color 0.3s;
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
    font-size: 12px;
    color: #666;
}

.form-actions {
    display: flex;
    gap: 15px;
    margin-top: 25px;
    padding-top: 20px;
    border-top: 1px solid #eee;
}

.btn-submit {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 10px 24px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-size: 14px;
    font-weight: 500;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: all 0.3s;
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
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: all 0.3s;
    border: 1px solid #ddd;
}

.btn-cancel:hover {
    background: #e9ecef;
    color: #333;
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
</style>