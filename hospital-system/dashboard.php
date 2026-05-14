<?php
require_once 'includes/init.php';
SessionManager::start();

if (!SessionManager::isLoggedIn()) {
    header('Location: login.php');
    exit();
}

$role = SessionManager::getUserRole();

switch ($role) {
    case 'patient':
        header('Location: patient/dashboard.php');
        break;
    case 'doctor':
        header('Location: doctor/dashboard.php');
        break;
    case 'receptionist':
        header('Location: receptionist/dashboard.php');
        break;
    case 'admin':
        header('Location: admin.php?action=dashboard');
        break;
    default:
        header('Location: login.php');
        break;
}
exit();