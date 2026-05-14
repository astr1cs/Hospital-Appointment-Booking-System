<?php
require_once 'includes/session.php';
SessionManager::start();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Unauthorized Access</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="error-container">
        <h1>401 - Unauthorized</h1>
        <p>You do not have permission to access this page.</p>
        <a href="dashboard.php">Go to Dashboard</a>
    </div>
</body>
</html>