<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../../models/Setting.php';

class SettingController extends BaseController {
    
    // Show settings page
    public function index() {
        $settingModel = new Setting();
        
        $policies = $settingModel->getPolicies();
        
        $data = [
            'title' => 'Global Appointment Policies',
            'policies' => $policies,
            'success' => $_SESSION['success'] ?? null,
            'error' => $_SESSION['error'] ?? null
        ];
        
        unset($_SESSION['success']);
        unset($_SESSION['error']);
        
        $this->view('settings/index', $data);
    }
    
    // Update policies
    public function updatePolicies() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('admin.php?action=settings');
            return;
        }
        
        $settingModel = new Setting();
        
        $policies = [
            'cancellation_hours' => $_POST['cancellation_hours'] ?? 2,
            'max_booking_days' => $_POST['max_booking_days'] ?? 30,
            'default_consultation_fee' => $_POST['default_consultation_fee'] ?? 50
        ];
        
        // Validate
        if ($policies['cancellation_hours'] < 1 || $policies['cancellation_hours'] > 72) {
            $_SESSION['error'] = 'Cancellation hours must be between 1 and 72';
            $this->redirect('admin.php?action=settings');
            return;
        }
        
        if ($policies['max_booking_days'] < 1 || $policies['max_booking_days'] > 365) {
            $_SESSION['error'] = 'Max booking days must be between 1 and 365';
            $this->redirect('admin.php?action=settings');
            return;
        }
        
        if ($policies['default_consultation_fee'] < 0) {
            $_SESSION['error'] = 'Consultation fee cannot be negative';
            $this->redirect('admin.php?action=settings');
            return;
        }
        
        $success = true;
        foreach ($policies as $key => $value) {
            if (!$settingModel->update($key, $value)) {
                $success = false;
                break;
            }
        }
        
        if ($success) {
            $_SESSION['success'] = 'Appointment policies updated successfully';
        } else {
            $_SESSION['error'] = 'Failed to update policies';
        }
        
        $this->redirect('admin.php?action=settings');
    }
}
?>