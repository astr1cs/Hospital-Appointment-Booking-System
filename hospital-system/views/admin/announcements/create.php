<div class="page-header">
    <h1 class="page-title">Create New Announcement</h1>
    <a href="<?php echo SITE_URL; ?>admin.php?action=announcements" class="btn-secondary">
        <i class="fas fa-arrow-left"></i> Back to List
    </a>
</div>

<div class="admin-card">
    <div class="card-header">
        <h3><i class="fas fa-edit"></i> Announcement Details</h3>
    </div>
    <div class="card-body">
        <form method="POST" action="<?php echo SITE_URL; ?>admin.php?action=announcements&sub=store">
            
            <div class="form-group">
                <label>Title <span class="required">*</span></label>
                <input type="text" name="title" class="form-control" required 
                       placeholder="Enter announcement title">
            </div>
            
            <div class="form-group">
                <label>Target Audience <span class="required">*</span></label>
                <select name="target_role" class="form-control">
                    <option value="all">All Users</option>
                    <option value="patient">Patients Only</option>
                    <option value="doctor">Doctors Only</option>
                    <option value="receptionist">Receptionists Only</option>
                </select>
                <small>Select who should see this announcement.</small>
            </div>
            
            <div class="form-group">
                <label>Message <span class="required">*</span></label>
                <textarea name="body" class="form-control" rows="8" required 
                          placeholder="Enter announcement message here..."></textarea>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn-submit">
                    <i class="fas fa-paper-plane"></i> Publish Announcement
                </button>
                <a href="<?php echo SITE_URL; ?>admin.php?action=announcements" class="btn-cancel">Cancel</a>
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
}

.form-control:focus {
    outline: none;
    border-color: #667eea;
}

select.form-control {
    cursor: pointer;
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
    display: inline-flex;
    align-items: center;
    gap: 8px;
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
</style>