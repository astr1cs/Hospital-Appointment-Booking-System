<?php
require_once 'includes/db.php';
require_once 'includes/auth.php';

// Only run this script once, then delete it
// or protect with a secret key

$db = Database::getInstance();
$conn = $db->getConnection();

// Check if admin exists
$sql = "SELECT id FROM users WHERE role = 'admin' LIMIT 1";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    $name = 'System Administrator';
    $email = 'admin@hospital.com';
    $password = Auth::hashPassword('admin123');
    $phone = '0000000000';
    
    $sql = "INSERT INTO users (name, email, password_hash, phone, role, is_active) 
            VALUES (?, ?, ?, ?, 'admin', 1)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $name, $email, $password, $phone);
    $stmt->execute();
    
    echo "Admin created. Email: admin@hospital.com, Password: admin123";
} else {
    echo "Admin already exists";
}
?>