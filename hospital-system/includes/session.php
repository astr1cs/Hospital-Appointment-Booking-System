<?php
require_once 'config.php';  // ← ADD THIS LINE

class SessionManager {
    
    public static function start() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }
    
    public static function set($key, $value) {
        $_SESSION[$key] = $value;
    }
    
    public static function get($key, $default = null) {
        return $_SESSION[$key] ?? $default;
    }
    
    public static function has($key) {
        return isset($_SESSION[$key]);
    }
    
    public static function remove($key) {
        unset($_SESSION[$key]);
    }
    
    public static function destroy() {
        $_SESSION = array();
        
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        
        session_destroy();
    }
    
    public static function setUser($userData) {
        self::set('user_id', $userData['id']);
        self::set('user_name', $userData['name']);
        self::set('user_email', $userData['email']);
        self::set('user_role', $userData['role']);
        self::set('user_phone', $userData['phone']);
        self::set('logged_in', true);
        self::set('login_time', time());
    }
    
    public static function isLoggedIn() {
        return self::get('logged_in') === true;
    }
    
    public static function getUserRole() {
        return self::get('user_role');
    }
    
    public static function getUserId() {
        return self::get('user_id');
    }
    
    public static function requireLogin() {
        if (!self::isLoggedIn()) {
            header('Location: ' . SITE_URL . 'login.php?error=please_login');
            exit();
        }
    }
    
    public static function requireRole($allowedRoles) {
        self::requireLogin();
        
        $userRole = self::getUserRole();
        
        if (!in_array($userRole, (array)$allowedRoles)) {
            header('Location: ' . SITE_URL . 'unauthorized.php');
            exit();
        }
    }
    
    public static function regenerateId() {
        session_regenerate_id(true);
    }
}
?>