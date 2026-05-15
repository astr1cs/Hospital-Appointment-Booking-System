<?php
require_once __DIR__ . '/BaseController.php';

class ProfileController extends DoctorBaseController {
    
    // View profile
    public function index() {
        $userId = $this->getUserId();
        
        $sql = "SELECT u.*, d.*, s.name as specialization_name 
                FROM users u 
                JOIN doctors d ON u.id = d.user_id 
                JOIN specializations s ON d.specialization_id = s.id
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
// Update profile
public function update() {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        $this->redirect('doctor.php?action=profile');
        return;
    }
    
    $userId = $this->getUserId();
    
    // Update users table
    $sql = "UPDATE users SET name = ?, email = ?, phone = ? WHERE id = ?";
    $stmt = $this->db->prepare($sql);
    $stmt->bind_param("sssi", $_POST['name'], $_POST['email'], $_POST['phone'], $userId);
    $stmt->execute();
    
    // Update doctors table
    $sql = "UPDATE doctors SET 
            specialization_id = ?, 
            bio = ?, 
            consultation_fee = ?, 
            license_number = ?, 
            experience_years = ?
            WHERE user_id = ?";
    $stmt = $this->db->prepare($sql);
    $stmt->bind_param("isdsii", 
        $_POST['specialization_id'], 
        $_POST['bio'], 
        $_POST['consultation_fee'], 
        $_POST['license_number'], 
        $_POST['experience_years'], 
        $userId
    );
    $stmt->execute();
    
    // Update session name
    $_SESSION['user_name'] = $_POST['name'];
    
    $_SESSION['success'] = 'Profile updated successfully';
    $this->redirect('doctor.php?action=profile');
}
    
    // Change password
  // Change password (supports both AJAX and regular POST)
public function changePassword() {
    $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
              strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
    
    if ($isAjax) {
        header('Content-Type: application/json');
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
        if ($isAjax) {
            echo json_encode(['success' => false, 'message' => 'Current password is incorrect']);
            return;
        } else {
            $_SESSION['error'] = 'Current password is incorrect';
            $this->redirect('doctor.php?action=profile');
            return;
        }
    }
    
    if ($newPassword !== $confirmPassword) {
        if ($isAjax) {
            echo json_encode(['success' => false, 'message' => 'New passwords do not match']);
            return;
        } else {
            $_SESSION['error'] = 'New passwords do not match';
            $this->redirect('doctor.php?action=profile');
            return;
        }
    }
    
    if (strlen($newPassword) < 6) {
        if ($isAjax) {
            echo json_encode(['success' => false, 'message' => 'Password must be at least 6 characters']);
            return;
        } else {
            $_SESSION['error'] = 'Password must be at least 6 characters';
            $this->redirect('doctor.php?action=profile');
            return;
        }
    }
    
    $newHash = password_hash($newPassword, PASSWORD_BCRYPT);
    $sql = "UPDATE users SET password_hash = ? WHERE id = ?";
    $stmt = $this->db->prepare($sql);
    $stmt->bind_param("si", $newHash, $userId);
    
    if ($stmt->execute()) {
        if ($isAjax) {
            echo json_encode(['success' => true, 'message' => 'Password changed successfully']);
        } else {
            $_SESSION['success'] = 'Password changed successfully';
            $this->redirect('doctor.php?action=profile');
        }
    } else {
        if ($isAjax) {
            echo json_encode(['success' => false, 'message' => 'Failed to change password']);
        } else {
            $_SESSION['error'] = 'Failed to change password';
            $this->redirect('doctor.php?action=profile');
        }
    }
}


    // AJAX Update profile
public function ajaxUpdate() {
    header('Content-Type: application/json');
    
    $userId = $this->getUserId();
    
    // Validate required fields
    if (empty($_POST['name']) || empty($_POST['email']) || empty($_POST['phone'])) {
        echo json_encode(['success' => false, 'message' => 'Name, email and phone are required']);
        return;
    }
    
    // Update users table
    $sql = "UPDATE users SET name = ?, email = ?, phone = ? WHERE id = ?";
    $stmt = $this->db->prepare($sql);
    $stmt->bind_param("sssi", $_POST['name'], $_POST['email'], $_POST['phone'], $userId);
    $userUpdated = $stmt->execute();
    
    // Update doctors table
    $sql = "UPDATE doctors SET 
            specialization_id = ?, 
            bio = ?, 
            consultation_fee = ?, 
            license_number = ?, 
            experience_years = ?
            WHERE user_id = ?";
    $stmt = $this->db->prepare($sql);
    $stmt->bind_param("isdsii", 
        $_POST['specialization_id'], 
        $_POST['bio'], 
        $_POST['consultation_fee'], 
        $_POST['license_number'], 
        $_POST['experience_years'], 
        $userId
    );
    $doctorUpdated = $stmt->execute();
    
    if ($userUpdated && $doctorUpdated) {
        // Update session name
        $_SESSION['user_name'] = $_POST['name'];
        
        echo json_encode(['success' => true, 'message' => 'Profile updated successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update profile']);
    }
}


}
?>