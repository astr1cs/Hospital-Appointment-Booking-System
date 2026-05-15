<div class="page-header">
    <h1 class="page-title"><?php echo $dependent ? 'Edit Dependent' : 'Add Dependent'; ?></h1>
    <a href="<?php echo SITE_URL; ?>patient.php?action=dependents" class="btn-back">
        <i class="fas fa-arrow-left"></i> Back to Dependents
    </a>
</div>

<div class="form-container">
    <div class="form-card">
        <div class="card-header">
            <h3><i class="fas fa-user-friends"></i> Dependent Information</h3>
        </div>
        <div class="card-body">
            <form method="POST" action="<?php echo SITE_URL; ?>patient.php?action=dependents&sub=<?php echo $dependent ? 'update&id=' . $dependent['id'] : 'store'; ?>">
                
                <div class="form-group">
                    <label>Full Name *</label>
                    <input type="text" name="name" class="form-control" required 
                           value="<?php echo htmlspecialchars($dependent['name'] ?? ''); ?>"
                           placeholder="Enter dependent's full name">
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label>Relationship *</label>
                        <select name="relationship" class="form-control" required>
                            <option value="">Select Relationship</option>
                            <option value="Son" <?php echo ($dependent['relationship'] ?? '') == 'Son' ? 'selected' : ''; ?>>Son</option>
                            <option value="Daughter" <?php echo ($dependent['relationship'] ?? '') == 'Daughter' ? 'selected' : ''; ?>>Daughter</option>
                            <option value="Spouse" <?php echo ($dependent['relationship'] ?? '') == 'Spouse' ? 'selected' : ''; ?>>Spouse</option>
                            <option value="Parent" <?php echo ($dependent['relationship'] ?? '') == 'Parent' ? 'selected' : ''; ?>>Parent</option>
                            <option value="Sibling" <?php echo ($dependent['relationship'] ?? '') == 'Sibling' ? 'selected' : ''; ?>>Sibling</option>
                            <option value="Grandparent" <?php echo ($dependent['relationship'] ?? '') == 'Grandparent' ? 'selected' : ''; ?>>Grandparent</option>
                            <option value="Other" <?php echo ($dependent['relationship'] ?? '') == 'Other' ? 'selected' : ''; ?>>Other</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>Date of Birth</label>
                        <input type="date" name="date_of_birth" class="form-control" 
                               value="<?php echo $dependent['date_of_birth'] ?? ''; ?>">
                    </div>
                </div>
                
                <div class="form-group">
                    <label>Blood Group</label>
                    <select name="blood_group" class="form-control">
                        <option value="">Select Blood Group</option>
                        <option value="A+" <?php echo ($dependent['blood_group'] ?? '') == 'A+' ? 'selected' : ''; ?>>A+</option>
                        <option value="A-" <?php echo ($dependent['blood_group'] ?? '') == 'A-' ? 'selected' : ''; ?>>A-</option>
                        <option value="B+" <?php echo ($dependent['blood_group'] ?? '') == 'B+' ? 'selected' : ''; ?>>B+</option>
                        <option value="B-" <?php echo ($dependent['blood_group'] ?? '') == 'B-' ? 'selected' : ''; ?>>B-</option>
                        <option value="O+" <?php echo ($dependent['blood_group'] ?? '') == 'O+' ? 'selected' : ''; ?>>O+</option>
                        <option value="O-" <?php echo ($dependent['blood_group'] ?? '') == 'O-' ? 'selected' : ''; ?>>O-</option>
                        <option value="AB+" <?php echo ($dependent['blood_group'] ?? '') == 'AB+' ? 'selected' : ''; ?>>AB+</option>
                        <option value="AB-" <?php echo ($dependent['blood_group'] ?? '') == 'AB-' ? 'selected' : ''; ?>>AB-</option>
                    </select>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn-submit">
                        <i class="fas fa-save"></i> <?php echo $dependent ? 'Update Dependent' : 'Add Dependent'; ?>
                    </button>
                    <a href="<?php echo SITE_URL; ?>patient.php?action=dependents" class="btn-cancel">Cancel</a>
                </div>
            </form>
        </div>
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
    font-size: 28px;
    margin: 0;
    color: #333;
}

.btn-back {
    color: #667eea;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.form-container {
    max-width: 600px;
}

.form-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    overflow: hidden;
}

.card-header {
    padding: 20px 25px;
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
}

.form-group {
    margin-bottom: 20px;
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

select.form-control {
    cursor: pointer;
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
    border-radius: 8px;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.btn-submit:hover {
    transform: translateY(-1px);
    box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
}

.btn-cancel {
    background: #6c757d;
    color: white;
    padding: 10px 20px;
    border-radius: 8px;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

@media (max-width: 768px) {
    .form-row {
        grid-template-columns: 1fr;
        gap: 0;
    }
    
    .form-actions {
        flex-direction: column;
    }
    
    .btn-submit, .btn-cancel {
        justify-content: center;
    }
}
</style>