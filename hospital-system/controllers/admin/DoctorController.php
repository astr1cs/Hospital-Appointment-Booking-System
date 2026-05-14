<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../../models/Doctor.php';
require_once __DIR__ . '/../../models/Specialization.php';
require_once __DIR__ . '/../../models/User.php';

class DoctorController extends BaseController {
    
    // List all doctors
    public function index() {
        $doctorModel = new Doctor();
        $doctors = $doctorModel->getAll();
        
        $data = [
            'title' => 'Manage Doctors',
            'doctors' => $doctors,
            'success' => $_SESSION['success'] ?? null,
            'error' => $_SESSION['error'] ?? null
        ];
        
        unset($_SESSION['success']);
        unset($_SESSION['error']);
        
        $this->view('doctors/index', $data);
    }
    
    // Show create form
    public function create() {
        $specializationModel = new Specialization();
        $specializations = $specializationModel->getAll();
        
        $data = [
            'title' => 'Add New Doctor',
            'specializations' => $specializations,
            'doctor' => null
        ];
        
        $this->view('doctors/create', $data);
    }
    
    // Store new doctor (admin creates)
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('admin.php?action=doctors');
            return;
        }
        
        // Validate required fields
        $required = ['name', 'email', 'password', 'phone', 'specialization_id', 'consultation_fee'];
        foreach ($required as $field) {
            if (empty($_POST[$field])) {
                $_SESSION['error'] = ucfirst(str_replace('_', ' ', $field)) . ' is required';
                $this->redirect('admin.php?action=doctors&sub=create');
                return;
            }
        }
        
        // Validate email format
        if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error'] = 'Invalid email format';
            $this->redirect('admin.php?action=doctors&sub=create');
            return;
        }
        
        // Check if email already exists
        $userModel = new User();
        if ($userModel->emailExists($_POST['email'])) {
            $_SESSION['error'] = 'Email already exists';
            $this->redirect('admin.php?action=doctors&sub=create');
            return;
        }
        
        // Validate password length
        if (strlen($_POST['password']) < 6) {
            $_SESSION['error'] = 'Password must be at least 6 characters';
            $this->redirect('admin.php?action=doctors&sub=create');
            return;
        }
        
        $doctorModel = new Doctor();
        
        $userData = [
            'name' => $_POST['name'],
            'email' => $_POST['email'],
            'password_hash' => password_hash($_POST['password'], PASSWORD_BCRYPT),
            'phone' => $_POST['phone']
        ];
        
        $doctorData = [
            'specialization_id' => $_POST['specialization_id'],
            'bio' => $_POST['bio'] ?? '',
            'consultation_fee' => $_POST['consultation_fee'],
            'license_number' => $_POST['license_number'] ?? '',
            'experience_years' => $_POST['experience_years'] ?? 0
        ];
        
        $result = $doctorModel->create($userData, $doctorData);
        
        if ($result['success']) {
            $_SESSION['success'] = 'Doctor created successfully';
        } else {
            $_SESSION['error'] = 'Failed to create doctor: ' . ($result['message'] ?? 'Unknown error');
        }
        
        $this->redirect('admin.php?action=doctors');
    }
    
    // Show edit form
    public function edit($id) {
        $doctorModel = new Doctor();
        $doctor = $doctorModel->getById($id);
        
        if (!$doctor) {
            $_SESSION['error'] = 'Doctor not found';
            $this->redirect('admin.php?action=doctors');
            return;
        }
        
        $specializationModel = new Specialization();
        $specializations = $specializationModel->getAll();
        
        $data = [
            'title' => 'Edit Doctor',
            'doctor' => $doctor,
            'specializations' => $specializations
        ];
        
        $this->view('doctors/edit', $data);
    }
    
    // Update doctor
    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('admin.php?action=doctors');
            return;
        }
        
        $doctorModel = new Doctor();
        $doctor = $doctorModel->getById($id);
        
        if (!$doctor) {
            $_SESSION['error'] = 'Doctor not found';
            $this->redirect('admin.php?action=doctors');
            return;
        }
        
        $userData = [
            'name' => $_POST['name'],
            'email' => $_POST['email'],
            'phone' => $_POST['phone']
        ];
        
        $doctorData = [
            'specialization_id' => $_POST['specialization_id'],
            'bio' => $_POST['bio'] ?? '',
            'consultation_fee' => $_POST['consultation_fee'],
            'license_number' => $_POST['license_number'] ?? '',
            'experience_years' => $_POST['experience_years'] ?? 0
        ];
        
        $result = $doctorModel->update($doctor['user_id'], $userData, $doctorData);
        
        if ($result['success']) {
            $_SESSION['success'] = 'Doctor updated successfully';
        } else {
            $_SESSION['error'] = 'Failed to update doctor';
        }
        
        $this->redirect('admin.php?action=doctors');
    }
    
    // Deactivate doctor
    public function deactivate($userId) {
        $doctorModel = new Doctor();
        
        if ($doctorModel->deactivate($userId)) {
            $_SESSION['success'] = 'Doctor deactivated successfully';
        } else {
            $_SESSION['error'] = 'Failed to deactivate doctor';
        }
        
        $this->redirect('admin.php?action=doctors');
    }
    
    // Activate doctor
    public function activate($userId) {
        $doctorModel = new Doctor();
        
        if ($doctorModel->activate($userId)) {
            $_SESSION['success'] = 'Doctor activated successfully';
        } else {
            $_SESSION['error'] = 'Failed to activate doctor';
        }
        
        $this->redirect('admin.php?action=doctors');
    }
}
?>