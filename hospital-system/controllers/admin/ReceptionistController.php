<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../../models/Receptionist.php';
require_once __DIR__ . '/../../models/User.php';

class ReceptionistController extends BaseController {
    
    // List all receptionists
    public function index() {
        $receptionistModel = new Receptionist();
        $receptionists = $receptionistModel->getAll();
        
        $data = [
            'title' => 'Manage Receptionists',
            'receptionists' => $receptionists,
            'success' => $_SESSION['success'] ?? null,
            'error' => $_SESSION['error'] ?? null
        ];
        
        unset($_SESSION['success']);
        unset($_SESSION['error']);
        
        $this->view('receptionists/index', $data);
    }
    
    // Show create form
    public function create() {
        $data = [
            'title' => 'Add New Receptionist',
            'receptionist' => null
        ];
        
        $this->view('receptionists/create', $data);
    }
    
    // Store new receptionist (admin creates)
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('admin.php?action=receptionists');
            return;
        }
        
        // Validate required fields
        $required = ['name', 'email', 'password', 'phone'];
        foreach ($required as $field) {
            if (empty($_POST[$field])) {
                $_SESSION['error'] = ucfirst($field) . ' is required';
                $this->redirect('admin.php?action=receptionists&sub=create');
                return;
            }
        }
        
        // Validate email format
        if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error'] = 'Invalid email format';
            $this->redirect('admin.php?action=receptionists&sub=create');
            return;
        }
        
        // Check if email already exists
        $userModel = new User();
        if ($userModel->emailExists($_POST['email'])) {
            $_SESSION['error'] = 'Email already exists';
            $this->redirect('admin.php?action=receptionists&sub=create');
            return;
        }
        
        // Validate password length
        if (strlen($_POST['password']) < 6) {
            $_SESSION['error'] = 'Password must be at least 6 characters';
            $this->redirect('admin.php?action=receptionists&sub=create');
            return;
        }
        
        $receptionistModel = new Receptionist();
        
        $userData = [
            'name' => $_POST['name'],
            'email' => $_POST['email'],
            'password_hash' => password_hash($_POST['password'], PASSWORD_BCRYPT),
            'phone' => $_POST['phone']
        ];
        
        if ($receptionistModel->create($userData)) {
            $_SESSION['success'] = 'Receptionist created successfully';
        } else {
            $_SESSION['error'] = 'Failed to create receptionist';
        }
        
        $this->redirect('admin.php?action=receptionists');
    }
    
    // Show edit form
    public function edit($id) {
        $receptionistModel = new Receptionist();
        $receptionist = $receptionistModel->getById($id);
        
        if (!$receptionist) {
            $_SESSION['error'] = 'Receptionist not found';
            $this->redirect('admin.php?action=receptionists');
            return;
        }
        
        $data = [
            'title' => 'Edit Receptionist',
            'receptionist' => $receptionist
        ];
        
        $this->view('receptionists/edit', $data);
    }
    
    // Update receptionist
    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('admin.php?action=receptionists');
            return;
        }
        
        $receptionistModel = new Receptionist();
        $receptionist = $receptionistModel->getById($id);
        
        if (!$receptionist) {
            $_SESSION['error'] = 'Receptionist not found';
            $this->redirect('admin.php?action=receptionists');
            return;
        }
        
        $userData = [
            'name' => $_POST['name'],
            'email' => $_POST['email'],
            'phone' => $_POST['phone']
        ];
        
        if ($receptionistModel->update($id, $userData)) {
            $_SESSION['success'] = 'Receptionist updated successfully';
        } else {
            $_SESSION['error'] = 'Failed to update receptionist';
        }
        
        $this->redirect('admin.php?action=receptionists');
    }
    
    // Deactivate receptionist
    public function deactivate($id) {
        $receptionistModel = new Receptionist();
        
        if ($receptionistModel->deactivate($id)) {
            $_SESSION['success'] = 'Receptionist deactivated successfully';
        } else {
            $_SESSION['error'] = 'Failed to deactivate receptionist';
        }
        
        $this->redirect('admin.php?action=receptionists');
    }
    
    // Activate receptionist
    public function activate($id) {
        $receptionistModel = new Receptionist();
        
        if ($receptionistModel->activate($id)) {
            $_SESSION['success'] = 'Receptionist activated successfully';
        } else {
            $_SESSION['error'] = 'Failed to activate receptionist';
        }
        
        $this->redirect('admin.php?action=receptionists');
    }
}
?>