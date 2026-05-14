<?php
require_once 'config.php';  // ← ADD THIS LINE
require_once 'db.php';
require_once 'session.php';

class Auth {
    
    // Validate email format
    public static function validateEmail($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }
    
    // Hash password using bcrypt
    public static function hashPassword($password) {
        return password_hash($password, PASSWORD_BCRYPT, ['cost' => PASSWORD_BCRYPT_COST]);
    }
    
    // Verify password
    public static function verifyPassword($password, $hash) {
        return password_verify($password, $hash);
    }
    
    // Check if email already exists
    public static function emailExists($email) {
        $db = Database::getInstance();
        $conn = $db->getConnection();
        
        $sql = "SELECT id FROM users WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        
        $exists = $stmt->num_rows > 0;
        $stmt->close();
        
        return $exists;
    }
    
    // Login user
    public static function login($email, $password) {
        $db = Database::getInstance();
        $conn = $db->getConnection();
        
        // Fetch user by email
        $sql = "SELECT id, name, email, phone, password_hash, role, is_active, profile_pic 
                FROM users 
                WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 0) {
            return ['success' => false, 'message' => 'Email not found'];
        }
        
        $user = $result->fetch_assoc();
        $stmt->close();
        
        // Check if account is active
        if ($user['is_active'] != 1) {
            return ['success' => false, 'message' => 'Account is deactivated. Contact admin.'];
        }
        
        // Verify password
        if (!self::verifyPassword($password, $user['password_hash'])) {
            return ['success' => false, 'message' => 'Incorrect password'];
        }
        
        // Remove sensitive data
        unset($user['password_hash']);
        
        // Set session
        SessionManager::setUser($user);
        SessionManager::regenerateId();
        
        // Log login attempt (optional)
        self::logLoginAttempt($user['id'], true);
        
        return [
            'success' => true, 
            'message' => 'Login successful',
            'role' => $user['role'],
            'user' => $user
        ];
    }
    
    // Register new patient
    public static function registerPatient($data) {
        $db = Database::getInstance();
        $conn = $db->getConnection();
        
        // Validate required fields
        $required = ['name', 'email', 'password', 'confirm_password', 'phone'];
        foreach ($required as $field) {
            if (empty($data[$field])) {
                return ['success' => false, 'message' => ucfirst($field) . ' is required'];
            }
        }
        
        // Validate email format
        if (!self::validateEmail($data['email'])) {
            return ['success' => false, 'message' => 'Invalid email format'];
        }
        
        // Check if email exists
        if (self::emailExists($data['email'])) {
            return ['success' => false, 'message' => 'Email already registered'];
        }
        
        // Validate password match
        if ($data['password'] !== $data['confirm_password']) {
            return ['success' => false, 'message' => 'Passwords do not match'];
        }
        
        // Validate password strength
        if (strlen($data['password']) < 6) {
            return ['success' => false, 'message' => 'Password must be at least 6 characters'];
        }
        
        // Hash password
        $passwordHash = self::hashPassword($data['password']);
        
        // Start transaction
        $conn->begin_transaction();
        
        try {
            // Insert into users table
            $sql = "INSERT INTO users (name, email, password_hash, phone, role, is_active) 
                    VALUES (?, ?, ?, ?, 'patient', 1)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssss", $data['name'], $data['email'], $passwordHash, $data['phone']);
            $stmt->execute();
            
            $userId = $conn->insert_id;
            $stmt->close();
            
            // Insert into patients table (extended info)
            $dob = $data['date_of_birth'] ?? null;
            $bloodGroup = $data['blood_group'] ?? null;
            $gender = $data['gender'] ?? null;
            $address = $data['address'] ?? null;
            $emergencyName = $data['emergency_contact_name'] ?? null;
            $emergencyPhone = $data['emergency_contact_phone'] ?? null;
            
            $sql2 = "INSERT INTO patients (user_id, date_of_birth, blood_group, gender, address, 
                     emergency_contact_name, emergency_contact_phone) 
                     VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt2 = $conn->prepare($sql2);
            $stmt2->bind_param("issssss", $userId, $dob, $bloodGroup, $gender, $address, 
                               $emergencyName, $emergencyPhone);
            $stmt2->execute();
            $stmt2->close();
            
            $conn->commit();
            
            return ['success' => true, 'message' => 'Registration successful. Please login.'];
            
        } catch (Exception $e) {
            $conn->rollback();
            return ['success' => false, 'message' => 'Registration failed: ' . $e->getMessage()];
        }
    }
    
    // Log login attempts
    private static function logLoginAttempt($userId, $success) {
        $db = Database::getInstance();
        $conn = $db->getConnection();
        
        $ip = $_SERVER['REMOTE_ADDR'] ?? null;
        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? null;
        
        $sql = "INSERT INTO user_sessions (user_id, ip_address, user_agent, last_activity) 
                VALUES (?, ?, ?, NOW())";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iss", $userId, $ip, $userAgent);
        $stmt->execute();
        $stmt->close();
    }
    
    // Logout
    public static function logout() {
        SessionManager::destroy();
    }
    
    // Change password
    public static function changePassword($userId, $oldPassword, $newPassword) {
        $db = Database::getInstance();
        $conn = $db->getConnection();
        
        // Get current password hash
        $sql = "SELECT password_hash FROM users WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();
        
        // Verify old password
        if (!self::verifyPassword($oldPassword, $user['password_hash'])) {
            return ['success' => false, 'message' => 'Current password is incorrect'];
        }
        
        // Validate new password strength
        if (strlen($newPassword) < 6) {
            return ['success' => false, 'message' => 'New password must be at least 6 characters'];
        }
        
        // Hash new password
        $newHash = self::hashPassword($newPassword);
        
        // Update password
        $sql = "UPDATE users SET password_hash = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $newHash, $userId);
        $success = $stmt->execute();
        $stmt->close();
        
        if ($success) {
            return ['success' => true, 'message' => 'Password changed successfully'];
        } else {
            return ['success' => false, 'message' => 'Failed to change password'];
        }
    }
}
?>