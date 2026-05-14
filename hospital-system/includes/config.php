<?php
// Database configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'hospital_system');
define('DB_USER', 'root');      // XAMPP default
define('DB_PASS', '');          // XAMPP default (empty)

// Application configuration
define('SITE_NAME', 'Hospital Appointment System');
define('SITE_URL', 'http://localhost/hospital-system/');  // IMPORTANT: trailing slash

// Session configuration
define('SESSION_LIFETIME', 3600);

// Security
define('PASSWORD_BCRYPT_COST', 12);

// Timezone
date_default_timezone_set('Asia/Dhaka');

// Error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>