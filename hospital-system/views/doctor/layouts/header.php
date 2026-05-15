<?php
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'doctor') {
    header('Location: ' . SITE_URL . 'login.php');
    exit();
}

$current_action = $_GET['action'] ?? 'dashboard';
$subAction = $_GET['sub'] ?? '';  // Add this line
$userName = $_SESSION['user_name'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title ?? 'Dashboard'; ?> - Doctor Portal</title>
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>assets/css/main.css">
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>assets/css/components.css">
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>assets/css/doctor.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body>
    <div class="doctor-container">
        <!-- Sidebar -->
        <aside class="doctor-sidebar">
            <div class="sidebar-header">
                <h2><i class="fas fa-user-md"></i> Doctor Panel</h2>
            </div>
           <nav class="sidebar-nav">
    <?php
    // Determine active states for different pages
    $isDashboard = ($current_action == 'dashboard');
    $isTodaySchedule = ($current_action == 'appointments' && ($subAction == 'today' || !isset($_GET['sub'])));
    $isAllAppointments = ($current_action == 'appointments' && $subAction == 'index');
    $isSchedule = ($current_action == 'schedule');
    $isPatients = ($current_action == 'patients');
    $isReports = ($current_action == 'reports');
    $isReviews = ($current_action == 'reviews');
    $isProfile = ($current_action == 'profile');
    ?>
    
    <a href="<?php echo SITE_URL; ?>doctor.php?action=dashboard" class="<?php echo $isDashboard ? 'active' : ''; ?>">
        <i class="fas fa-tachometer-alt"></i> <span>Dashboard</span>
    </a>
    
    <a href="<?php echo SITE_URL; ?>doctor.php?action=appointments&sub=today" class="<?php echo $isTodaySchedule ? 'active' : ''; ?>">
        <i class="fas fa-calendar-day"></i> <span>Today's Schedule</span>
    </a>
    
    <a href="<?php echo SITE_URL; ?>doctor.php?action=appointments&sub=index" class="<?php echo $isAllAppointments ? 'active' : ''; ?>">
        <i class="fas fa-calendar-week"></i> <span>All Appointments</span>
    </a>
    
    <a href="<?php echo SITE_URL; ?>doctor.php?action=schedule" class="<?php echo $isSchedule ? 'active' : ''; ?>">
        <i class="fas fa-clock"></i> <span>My Schedule</span>
    </a>
    
    <a href="<?php echo SITE_URL; ?>doctor.php?action=patients" class="<?php echo $isPatients ? 'active' : ''; ?>">
        <i class="fas fa-users"></i> <span>Patients</span>
    </a>
    
    <a href="<?php echo SITE_URL; ?>doctor.php?action=reports" class="<?php echo $isReports ? 'active' : ''; ?>">
        <i class="fas fa-chart-line"></i> <span>Reports</span>
    </a>
    
    <a href="<?php echo SITE_URL; ?>doctor.php?action=reviews" class="<?php echo $isReviews ? 'active' : ''; ?>">
        <i class="fas fa-star"></i> <span>Reviews</span>
    </a>
    
    <a href="<?php echo SITE_URL; ?>doctor.php?action=profile" class="<?php echo $isProfile ? 'active' : ''; ?>">
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
        <main class="doctor-main">
            <div class="doctor-topbar">
                <div class="welcome-text">
                    <i class="fas fa-user-md"></i> Welcome, <strong>Dr.
                        <?php echo htmlspecialchars($userName); ?></strong>
                </div>
                <div class="topbar-right">
                    <span class="role-badge"><i class="fas fa-stethoscope"></i> Doctor</span>
                </div>
            </div>
            <div class="doctor-content">