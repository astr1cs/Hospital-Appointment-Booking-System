<?php
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'patient') {
    header('Location: ' . SITE_URL . 'login.php');
    exit();
}

$current_action = $_GET['action'] ?? 'dashboard';
$userName = $_SESSION['user_name'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title ?? 'Dashboard'; ?> - Patient Portal</title>
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>assets/css/main.css">
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>assets/css/components.css">
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>assets/css/patient.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
<div class="patient-container">
    <!-- Sidebar -->
    <aside class="patient-sidebar">
        <div class="sidebar-header">
            <h2><i class="fas fa-hospital-user"></i> Patient Portal</h2>
        </div>
        <nav class="sidebar-nav">
            <a href="<?php echo SITE_URL; ?>patient.php?action=dashboard" class="<?php echo $current_action == 'dashboard' ? 'active' : ''; ?>">
                <i class="fas fa-tachometer-alt"></i> <span>Dashboard</span>
            </a>
            <a href="<?php echo SITE_URL; ?>patient.php?action=doctors" class="<?php echo $current_action == 'doctors' ? 'active' : ''; ?>">
                <i class="fas fa-user-md"></i> <span>Find Doctors</span>
            </a>
            <a href="<?php echo SITE_URL; ?>patient.php?action=appointments" class="<?php echo $current_action == 'appointments' ? 'active' : ''; ?>">
                <i class="fas fa-calendar-check"></i> <span>My Appointments</span>
            </a>
            <a href="<?php echo SITE_URL; ?>patient.php?action=medical-history" class="<?php echo $current_action == 'medical-history' ? 'active' : ''; ?>">
                <i class="fas fa-notes-medical"></i> <span>Medical History</span>
            </a>
            <a href="<?php echo SITE_URL; ?>patient.php?action=dependents" class="<?php echo $current_action == 'dependents' ? 'active' : ''; ?>">
                <i class="fas fa-users"></i> <span>Dependents</span>
            </a>
            <a href="<?php echo SITE_URL; ?>patient.php?action=billing" class="<?php echo $current_action == 'billing' ? 'active' : ''; ?>">
                <i class="fas fa-dollar-sign"></i> <span>Billing</span>
            </a>
            <a href="<?php echo SITE_URL; ?>patient.php?action=profile" class="<?php echo $current_action == 'profile' ? 'active' : ''; ?>">
                <i class="fas fa-user-circle"></i> <span>My Profile</span>
            </a>
        </nav>
        <div class="sidebar-footer">
            <a href="<?php echo SITE_URL; ?>logout.php">
                <i class="fas fa-sign-out-alt"></i> <span>Logout</span>
            </a>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="patient-main">
        <div class="patient-topbar">
            <div class="welcome-text">
                <i class="fas fa-user"></i> Welcome, <strong><?php echo htmlspecialchars($userName); ?></strong>
            </div>
            <div class="topbar-right">
                <span class="role-badge"><i class="fas fa-user"></i> Patient</span>
            </div>
        </div>
        <div class="patient-content">