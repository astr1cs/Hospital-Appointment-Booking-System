<?php
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: ' . SITE_URL . 'login.php');
    exit();
}
$current_action = $_GET['action'] ?? 'dashboard';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - <?php echo $title ?? 'Dashboard'; ?> | Hospital System</title>
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>assets/css/main.css">
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>assets/css/components.css">
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>assets/css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
<div class="admin-container">
    <aside class="admin-sidebar">
        <div class="sidebar-header">
            <h2><i class="fas fa-hospital"></i> Admin Panel</h2>
        </div>
        <nav class="sidebar-nav">
            <a href="<?php echo SITE_URL; ?>admin.php?action=dashboard" class="<?php echo $current_action == 'dashboard' ? 'active' : ''; ?>">
                <i class="fas fa-tachometer-alt"></i> <span>Dashboard</span>
            </a>
            <a href="<?php echo SITE_URL; ?>admin.php?action=doctors" class="<?php echo $current_action == 'doctors' ? 'active' : ''; ?>">
                <i class="fas fa-user-md"></i> <span>Doctors</span>
            </a>
            <a href="<?php echo SITE_URL; ?>admin.php?action=specializations" class="<?php echo $current_action == 'specializations' ? 'active' : ''; ?>">
                <i class="fas fa-tags"></i> <span>Specializations</span>
            </a>
            <a href="<?php echo SITE_URL; ?>admin.php?action=receptionists" class="<?php echo $current_action == 'receptionists' ? 'active' : ''; ?>">
                <i class="fas fa-user-tie"></i> <span>Receptionists</span>
            </a>
            <a href="<?php echo SITE_URL; ?>admin.php?action=patients" class="<?php echo $current_action == 'patients' ? 'active' : ''; ?>">
                <i class="fas fa-users"></i> <span>Patients</span>
            </a>
            <a href="<?php echo SITE_URL; ?>admin.php?action=appointments" class="<?php echo $current_action == 'appointments' ? 'active' : ''; ?>">
                <i class="fas fa-calendar-check"></i> <span>Appointments</span>
            </a>
            <a href="<?php echo SITE_URL; ?>admin.php?action=billing" class="<?php echo $current_action == 'billing' ? 'active' : ''; ?>">
                <i class="fas fa-dollar-sign"></i> <span>Billing</span>
            </a>
            <a href="<?php echo SITE_URL; ?>admin.php?action=reports" class="<?php echo $current_action == 'reports' ? 'active' : ''; ?>">
                <i class="fas fa-chart-bar"></i> <span>Reports</span>
            </a>
            <a href="<?php echo SITE_URL; ?>admin.php?action=announcements" class="<?php echo $current_action == 'announcements' ? 'active' : ''; ?>">
                <i class="fas fa-bullhorn"></i> <span>Announcements</span>
            </a>
            <a href="<?php echo SITE_URL; ?>admin.php?action=complaints" class="<?php echo $current_action == 'complaints' ? 'active' : ''; ?>">
                <i class="fas fa-comment-dots"></i> <span>Complaints</span>
            </a>
            <a href="<?php echo SITE_URL; ?>admin.php?action=settings" class="<?php echo $current_action == 'settings' ? 'active' : ''; ?>">
                <i class="fas fa-cog"></i> <span>Settings</span>
            </a>
        </nav>
        <div class="sidebar-footer">
            <a href="<?php echo SITE_URL; ?>logout.php">
                <i class="fas fa-sign-out-alt"></i> <span>Logout</span>
            </a>
        </div>
    </aside>
    <main class="admin-main">
        <div class="admin-topbar">
            <div class="welcome-text">
                <i class="fas fa-user-shield"></i> Welcome, <strong><?php echo htmlspecialchars($_SESSION['user_name']); ?></strong>
            </div>
            <div class="topbar-right">
                <span class="role-badge"><i class="fas fa-crown"></i> Administrator</span>
            </div>
        </div>
        <div class="admin-content">