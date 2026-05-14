<?php
// signup.php
require_once 'includes/init.php';

// If already logged in, redirect
if (SessionManager::isLoggedIn()) {
    redirect('dashboard.php');
}

$error = null;
$success = null;
$formData = [];

if (isPostRequest()) {
    $formData = [
        'name' => getPostData('name'),
        'email' => getPostData('email'),
        'phone' => getPostData('phone'),
        'date_of_birth' => getPostData('date_of_birth'),
        'blood_group' => getPostData('blood_group'),
        'gender' => getPostData('gender'),
        'address' => getPostData('address'),
        'emergency_contact_name' => getPostData('emergency_contact_name'),
        'emergency_contact_phone' => getPostData('emergency_contact_phone')
    ];
    
    $data = $formData;
    $data['password'] = getPostData('password');
    $data['confirm_password'] = getPostData('confirm_password');
    
    $result = Auth::registerPatient($data);
    
    if ($result['success']) {
        $success = $result['message'];
    } else {
        $error = $result['message'];
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Patient Registration - Hospital System</title>
    <link rel="stylesheet" href="assets/css/main.css">
    <link rel="stylesheet" href="assets/css/components.css">
    <link rel="stylesheet" href="assets/css/signup.css">
    <link rel="stylesheet" href="assets/css/responsive.css">
</head>
<body>
    <div class="signup-container">
        <div class="signup-header">
            <h2>Patient Registration</h2>
            <p>Create your account to book appointments</p>
        </div>
        
        <?php if (isset($error) && $error): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <?php if (isset($success) && $success): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
            <meta http-equiv="refresh" content="3;url=login.php">
        <?php endif; ?>
        
        <form method="POST" action="">
            <fieldset>
                <legend>Personal Information</legend>
                
                <div class="form-group">
                    <label>Full Name *</label>
                    <input type="text" name="name" required value="<?php echo htmlspecialchars($formData['name'] ?? ''); ?>">
                </div>
                
                <div class="form-group">
                    <label>Email *</label>
                    <input type="email" name="email" required value="<?php echo htmlspecialchars($formData['email'] ?? ''); ?>">
                </div>
                
                <div class="form-group">
                    <label>Password *</label>
                    <input type="password" name="password" required>
                    <small>Minimum 6 characters</small>
                </div>
                
                <div class="form-group">
                    <label>Confirm Password *</label>
                    <input type="password" name="confirm_password" required>
                </div>
                
                <div class="form-group">
                    <label>Phone *</label>
                    <input type="tel" name="phone" required value="<?php echo htmlspecialchars($formData['phone'] ?? ''); ?>">
                </div>
                
                <div class="form-group">
                    <label>Date of Birth</label>
                    <input type="date" name="date_of_birth" value="<?php echo htmlspecialchars($formData['date_of_birth'] ?? ''); ?>">
                </div>
                
                <div class="form-group">
                    <label>Blood Group</label>
                    <select name="blood_group">
                        <option value="">Select</option>
                        <option value="A+" <?php echo ($formData['blood_group'] ?? '') == 'A+' ? 'selected' : ''; ?>>A+</option>
                        <option value="A-" <?php echo ($formData['blood_group'] ?? '') == 'A-' ? 'selected' : ''; ?>>A-</option>
                        <option value="B+" <?php echo ($formData['blood_group'] ?? '') == 'B+' ? 'selected' : ''; ?>>B+</option>
                        <option value="B-" <?php echo ($formData['blood_group'] ?? '') == 'B-' ? 'selected' : ''; ?>>B-</option>
                        <option value="O+" <?php echo ($formData['blood_group'] ?? '') == 'O+' ? 'selected' : ''; ?>>O+</option>
                        <option value="O-" <?php echo ($formData['blood_group'] ?? '') == 'O-' ? 'selected' : ''; ?>>O-</option>
                        <option value="AB+" <?php echo ($formData['blood_group'] ?? '') == 'AB+' ? 'selected' : ''; ?>>AB+</option>
                        <option value="AB-" <?php echo ($formData['blood_group'] ?? '') == 'AB-' ? 'selected' : ''; ?>>AB-</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Gender</label>
                    <select name="gender">
                        <option value="">Select</option>
                        <option value="male" <?php echo ($formData['gender'] ?? '') == 'male' ? 'selected' : ''; ?>>Male</option>
                        <option value="female" <?php echo ($formData['gender'] ?? '') == 'female' ? 'selected' : ''; ?>>Female</option>
                        <option value="other" <?php echo ($formData['gender'] ?? '') == 'other' ? 'selected' : ''; ?>>Other</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Address</label>
                    <textarea name="address" rows="3"><?php echo htmlspecialchars($formData['address'] ?? ''); ?></textarea>
                </div>
            </fieldset>
            
            <fieldset>
                <legend>Emergency Contact</legend>
                
                <div class="form-group">
                    <label>Emergency Contact Name</label>
                    <input type="text" name="emergency_contact_name" value="<?php echo htmlspecialchars($formData['emergency_contact_name'] ?? ''); ?>">
                </div>
                
                <div class="form-group">
                    <label>Emergency Contact Phone</label>
                    <input type="tel" name="emergency_contact_phone" value="<?php echo htmlspecialchars($formData['emergency_contact_phone'] ?? ''); ?>">
                </div>
            </fieldset>
            
            <button type="submit" class="btn btn-primary">Register</button>
        </form>
        
        <p>Already have an account? <a href="login.php">Login</a></p>
    </div>
</body>
</html>