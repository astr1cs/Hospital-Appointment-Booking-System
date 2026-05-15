<?php
require_once __DIR__ . '/BaseController.php';

class ProfileController extends PatientBaseController {
    
    // View profile
    public function index() {
        $userId = $this->getUserId();
        
        $sql = "SELECT u.*, p.* 
                FROM users u 
                LEFT JOIN patients p ON u.id = p.user_id 
                WHERE u.id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $profile = $stmt->get_result()->fetch_assoc();
        
        $data = [
            'title' => 'My Profile',
            'profile' => $profile,
            'success' => $_SESSION['success'] ?? null,
            'error' => $_SESSION['error'] ?? null
        ];
        
        unset($_SESSION['success']);
        unset($_SESSION['error']);
        
        $this->view('profile/index', $data);
    }
    
    // Update profile
    public function update() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('patient.php?action=profile');
            return;
        }
        
        $userId = $this->getUserId();
        
        // Update users table
        $sql = "UPDATE users SET name = ?, email = ?, phone = ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("sssi", $_POST['name'], $_POST['email'], $_POST['phone'], $userId);
        $stmt->execute();
        
        // Update patients table
        $sql = "UPDATE patients SET 
                date_of_birth = ?, 
                blood_group = ?, 
                gender = ?, 
                address = ?,
                emergency_contact_name = ?,
                emergency_contact_phone = ?
                WHERE user_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ssssssi", 
            $_POST['date_of_birth'], 
            $_POST['blood_group'], 
            $_POST['gender'], 
            $_POST['address'],
            $_POST['emergency_contact_name'],
            $_POST['emergency_contact_phone'],
            $userId
        );
        $stmt->execute();
        
        $_SESSION['success'] = 'Profile updated successfully';
        $this->redirect('patient.php?action=profile');
    }
    
    // Change password
    public function changePassword() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('patient.php?action=profile');
            return;
        }
        
        $userId = $this->getUserId();
        $currentPassword = $_POST['current_password'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';
        
        // Get current password hash
        $sql = "SELECT password_hash FROM users WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();
        
        if (!password_verify($currentPassword, $user['password_hash'])) {
            $_SESSION['error'] = 'Current password is incorrect';
            $this->redirect('patient.php?action=profile');
            return;
        }
        
        if ($newPassword !== $confirmPassword) {
            $_SESSION['error'] = 'New passwords do not match';
            $this->redirect('patient.php?action=profile');
            return;
        }
        
        if (strlen($newPassword) < 6) {
            $_SESSION['error'] = 'Password must be at least 6 characters';
            $this->redirect('patient.php?action=profile');
            return;
        }
        
        $newHash = password_hash($newPassword, PASSWORD_BCRYPT);
        $sql = "UPDATE users SET password_hash = ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("si", $newHash, $userId);
        
        if ($stmt->execute()) {
            $_SESSION['success'] = 'Password changed successfully';
        } else {
            $_SESSION['error'] = 'Failed to change password';
        }
        
        $this->redirect('patient.php?action=profile');
    }
}
?>