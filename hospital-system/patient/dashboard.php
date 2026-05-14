<?php
require_once '../includes/session.php';
require_once '../includes/functions.php';

SessionManager::start();

// Only patient can access
SessionManager::requireRole('patient');

$userName = SessionManager::get('user_name');
?>

<!DOCTYPE html>
<html>
<head>
    <title>Patient Dashboard</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <h1>Welcome, <?php echo escape($userName); ?></h1>
    <p>This is your patient dashboard.</p>
    <a href="../logout.php">Logout</a>
</body>
</html>