<?php
// includes/functions.php

// Redirect to a URL
function redirect($url) {
    header("Location: " . SITE_URL . $url);
    exit();
}

// Display error message
function showError($message) {
    return '<div class="alert alert-danger">' . htmlspecialchars($message) . '</div>';
}

// Display success message
function showSuccess($message) {
    return '<div class="alert alert-success">' . htmlspecialchars($message) . '</div>';
}

// Check if request method is POST
function isPostRequest() {
    return $_SERVER['REQUEST_METHOD'] === 'POST';
}

// Check if request method is GET
function isGetRequest() {
    return $_SERVER['REQUEST_METHOD'] === 'GET';
}

// Get POST data safely
function getPostData($key, $default = null) {
    return isset($_POST[$key]) ? trim($_POST[$key]) : $default;
}

// Get GET data safely
function getQueryParam($key, $default = null) {
    return isset($_GET[$key]) ? trim($_GET[$key]) : $default;
}

// Escape HTML special characters
function escape($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

// Generate CSRF token
function generateCSRFToken() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

// Verify CSRF token
function verifyCSRFToken($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

// Display CSRF token hidden field
function csrfField() {
    $token = generateCSRFToken();
    return '<input type="hidden" name="csrf_token" value="' . $token . '">';
}

// Get current user ID from session
function getCurrentUserId() {
    return isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
}

// Get current user role from session
function getCurrentUserRole() {
    return isset($_SESSION['user_role']) ? $_SESSION['user_role'] : null;
}

// Get current user name from session
function getCurrentUserName() {
    return isset($_SESSION['user_name']) ? $_SESSION['user_name'] : null;
}

// Check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
}

// Require user to be logged in
function requireLogin() {
    if (!isLoggedIn()) {
        redirect('login.php');
    }
}

// Require specific role
function requireRole($role) {
    requireLogin();
    if (getCurrentUserRole() !== $role) {
        redirect('unauthorized.php');
    }
}

// Require any of multiple roles
function requireAnyRole($roles) {
    requireLogin();
    if (!in_array(getCurrentUserRole(), (array)$roles)) {
        redirect('unauthorized.php');
    }
}

// Generate random string
function randomString($length = 32) {
    return bin2hex(random_bytes($length / 2));
}

// Format date
function formatDate($date, $format = 'M d, Y') {
    return date($format, strtotime($date));
}

// Format time
function formatTime($time, $format = 'h:i A') {
    return date($format, strtotime($time));
}

// Get current datetime
function now() {
    return date('Y-m-d H:i:s');
}

// Get today's date
function today() {
    return date('Y-m-d');
}

// Dump and die for debugging
function dd($data) {
    echo '<pre>';
    var_dump($data);
    echo '</pre>';
    die();
}
?>