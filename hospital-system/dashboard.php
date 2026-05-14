<?php
// dashboard.php
require_once 'includes/init.php';
SessionManager::start();

// Check if user is logged in
if (!SessionManager::isLoggedIn()) {
    redirect('login.php');
}

$role = SessionManager::getUserRole();

// Redirect based on role
switch ($role) {
    case 'patient':
        redirect('patient/dashboard.php');
        break;
    case 'doctor':
        redirect('doctor/dashboard.php');
        break;
    case 'receptionist':
        redirect('receptionist/dashboard.php');
        break;
    case 'admin':
        redirect('admin/dashboard.php');
        break;
    default:
        redirect('login.php');
        break;
}
?>