<div class="page-header">
    <h1 class="page-title">Register New Patient</h1>
</div>

<?php if (isset($error) && $error): ?>
    <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>

<div class="register-card">
    <div class="card-header">
        <h3><i class="fas fa-user-plus"></i> Patient Registration Form</h3>
    </div>
    <div class="card-body">
        <form method="POST" action="<?php echo SITE_URL; ?>receptionist.php?action=patients&sub=store">
            <div class="form-section">
                <h4>Personal Information</h4>
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
                        <small>Default: password123 (patient can change later)</small>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Date of Birth</label>
                        <input type="date" name="date_of_birth" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Blood Group</label>
                        <select name="blood_group" class="form-control">
                            <option value="">Select</option>
                            <option value="A+">A+</option>
                            <option value="A-">A-</option>
                            <option value="B+">B+</option>
                            <option value="B-">B-</option>
                            <option value="O+">O+</option>
                            <option value="O-">O-</option>
                            <option value="AB+">AB+</option>
                            <option value="AB-">AB-</option>
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Gender</label>
                        <select name="gender" class="form-control">
                            <option value="">Select</option>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Address</label>
                        <textarea name="address" class="form-control" rows="2"></textarea>
                    </div>
                </div>
            </div>
            
            <div class="form-section">
                <h4>Emergency Contact</h4>
                <div class="form-row">
                    <div class="form-group">
                        <label>Contact Name</label>
                        <input type="text" name="emergency_contact_name" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Contact Phone</label>
                        <input type="tel" name="emergency_contact_phone" class="form-control">
                    </div>
                </div>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn-register">
                    <i class="fas fa-save"></i> Register Patient
                </button>
                <a href="<?php echo SITE_URL; ?>receptionist.php?action=patients&sub=search" class="btn-cancel">Cancel</a>
            </div>
        </form>
    </div>
</div>

<style>
.page-header {
    margin-bottom: 25px;
}
.page-title {
    font-size: 28px;
    margin: 0;
    color: #333;
}
.register-card {
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
.form-section {
    margin-bottom: 25px;
    padding-bottom: 20px;
    border-bottom: 1px solid #eee;
}
.form-section h4 {
    margin: 0 0 20px 0;
    color: #667eea;
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
}
.form-control {
    width: 100%;
    padding: 10px 12px;
    border: 1px solid #ddd;
    border-radius: 8px;
    font-size: 14px;
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
}
.btn-register {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    color: white;
    padding: 10px 24px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
}
.btn-cancel {
    background: #6c757d;
    color: white;
    padding: 10px 20px;
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
@media (max-width: 768px) {
    .form-row {
        grid-template-columns: 1fr;
        gap: 0;
    }
    .form-actions {
        flex-direction: column;
    }
    .btn-register, .btn-cancel {
        width: 100%;
        text-align: center;
    }
}
</style>