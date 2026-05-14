<?php
require_once 'includes/init.php';

SessionManager::start();

// If already logged in, redirect to dashboard
if (SessionManager::isLoggedIn()) {
    redirect('dashboard.php');
}

// Initialize variables
$error = null;  // ← Make sure this is defined BEFORE using it
$email = '';    // ← Also define email for the form

if (isPostRequest()) {
    $email = getPostData('email');
    $password = getPostData('password');
    
    if (empty($email) || empty($password)) {
        $error = 'Please fill in all fields';
    } else {
        $result = Auth::login($email, $password);
        
        if ($result['success']) {
            redirect('dashboard.php');
        } else {
            $error = $result['message'];
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login - Hospital System</title>
    
    <!-- CSS Files -->
    <link rel="stylesheet" href="assets/css/main.css">
    <link rel="stylesheet" href="assets/css/components.css">
    <link rel="stylesheet" href="assets/css/login.css">
    <link rel="stylesheet" href="assets/css/responsive.css">
</head>
<body class="login-page">
    <div class="login-container">
        <div class="login-header">
            <h2>Hospital Management System</h2>
            <h3>Login</h3>
        </div>
        
        <?php if (isset($error) && $error): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email" required value="<?php echo htmlspecialchars($email); ?>">
            </div>
            
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required>
            </div>
            
            <button type="submit" class="btn btn-primary">Login</button>
        </form>
        
        <p>Don't have an account? <a href="signup.php">Register as Patient</a></p>
    </div>
</body>
</html>