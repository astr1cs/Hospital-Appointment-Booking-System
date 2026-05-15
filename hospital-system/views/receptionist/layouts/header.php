<?php
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'receptionist') {
    header('Location: ' . SITE_URL . 'login.php');
    exit();
}

$current_action = $_GET['action'] ?? 'dashboard';
$subAction = $_GET['sub'] ?? '';
$userName = $_SESSION['user_name'];

// Determine active states
$isDashboard = ($current_action == 'dashboard');
$isTodaySchedule = ($current_action == 'appointments' && $subAction == 'today');
$isWalkinBooking = ($current_action == 'appointments' && $subAction == 'book');
$isWaitingQueue = ($current_action == 'appointments' && $subAction == 'queue');
$isSearchPatients = ($current_action == 'patients' && $subAction == 'search');
$isRegisterPatient = ($current_action == 'patients' && $subAction == 'register');
$isPayments = ($current_action == 'payments');
$isDailyReport = ($current_action == 'reports' && $subAction == 'daily');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title ?? 'Dashboard'; ?> - Receptionist Portal</title>
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>assets/css/main.css">
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>assets/css/components.css">
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>assets/css/receptionist.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
<div class="receptionist-container">
    <!-- Sidebar -->
    <aside class="receptionist-sidebar">
        <div class="sidebar-header">
            <h2><i class="fas fa-tty"></i> Reception Desk</h2>
        </div>
        <nav class="sidebar-nav">
            <a href="<?php echo SITE_URL; ?>receptionist.php?action=dashboard" class="<?php echo $isDashboard ? 'active' : ''; ?>">
                <i class="fas fa-tachometer-alt"></i> <span>Dashboard</span>
            </a>
            <a href="<?php echo SITE_URL; ?>receptionist.php?action=appointments&sub=today" class="<?php echo $isTodaySchedule ? 'active' : ''; ?>">
                <i class="fas fa-calendar-day"></i> <span>Today's Schedule</span>
            </a>
            <a href="<?php echo SITE_URL; ?>receptionist.php?action=appointments&sub=book" class="<?php echo $isWalkinBooking ? 'active' : ''; ?>">
                <i class="fas fa-plus-circle"></i> <span>Walk-in Booking</span>
            </a>
            <a href="<?php echo SITE_URL; ?>receptionist.php?action=appointments&sub=queue" class="<?php echo $isWaitingQueue ? 'active' : ''; ?>">
                <i class="fas fa-hourglass-half"></i> <span>Waiting Queue</span>
            </a>
            <a href="<?php echo SITE_URL; ?>receptionist.php?action=patients&sub=search" class="<?php echo $isSearchPatients ? 'active' : ''; ?>">
                <i class="fas fa-search"></i> <span>Search Patients</span>
            </a>
            <a href="<?php echo SITE_URL; ?>receptionist.php?action=patients&sub=register" class="<?php echo $isRegisterPatient ? 'active' : ''; ?>">
                <i class="fas fa-user-plus"></i> <span>Register Patient</span>
            </a>
            <a href="<?php echo SITE_URL; ?>receptionist.php?action=payments" class="<?php echo $isPayments ? 'active' : ''; ?>">
                <i class="fas fa-dollar-sign"></i> <span>Payments</span>
            </a>
            <a href="<?php echo SITE_URL; ?>receptionist.php?action=reports&sub=daily" class="<?php echo $isDailyReport ? 'active' : ''; ?>">
                <i class="fas fa-chart-bar"></i> <span>Daily Report</span>
            </a>
        </nav>
        <div class="sidebar-footer">
            <a href="<?php echo SITE_URL; ?>logout.php">
                <i class="fas fa-sign-out-alt"></i> <span>Logout</span>
            </a>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="receptionist-main">
        <div class="receptionist-topbar">
            <div class="welcome-text">
                <i class="fas fa-tty"></i> Welcome, <strong><?php echo htmlspecialchars($userName); ?></strong>
            </div>
            <div class="topbar-right">
                <span class="role-badge"><i class="fas fa-headset"></i> Receptionist</span>
            </div>
        </div>
        <div class="receptionist-content">