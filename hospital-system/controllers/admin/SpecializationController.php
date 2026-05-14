<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../../models/Specialization.php';

class SpecializationController extends BaseController {
    
    // List all specializations
    public function index() {
        $specializationModel = new Specialization();
        $specializations = $specializationModel->getAll();
        
        $data = [
            'title' => 'Manage Specializations',
            'specializations' => $specializations,
            'success' => $_SESSION['success'] ?? null,
            'error' => $_SESSION['error'] ?? null
        ];
        
        // Clear flash messages
        unset($_SESSION['success']);
        unset($_SESSION['error']);
        
        $this->view('specializations/index', $data);
    }
    
    // Show create form
    public function create() {
        $data = [
            'title' => 'Add New Specialization',
            'specialization' => null
        ];
        $this->view('specializations/form', $data);
    }
    
    // Store new specialization
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('admin.php?action=specializations');
            return;
        }
        
        $name = trim($_POST['name'] ?? '');
        $description = trim($_POST['description'] ?? '');
        
        // Validation
        if (empty($name)) {
            $_SESSION['error'] = 'Specialization name is required';
            $this->redirect('admin.php?action=specializations&sub=create');
            return;
        }
        
        $specializationModel = new Specialization();
        
        // Check if name already exists
        if ($specializationModel->nameExists($name)) {
            $_SESSION['error'] = 'Specialization name already exists';
            $this->redirect('admin.php?action=specializations&sub=create');
            return;
        }
        
        // Create specialization
        if ($specializationModel->create($name, $description)) {
            $_SESSION['success'] = 'Specialization added successfully';
        } else {
            $_SESSION['error'] = 'Failed to add specialization';
        }
        
        $this->redirect('admin.php?action=specializations');
    }
    
    // Show edit form
    public function edit($id) {
        $specializationModel = new Specialization();
        $specialization = $specializationModel->getById($id);
        
        if (!$specialization) {
            $_SESSION['error'] = 'Specialization not found';
            $this->redirect('admin.php?action=specializations');
            return;
        }
        
        $data = [
            'title' => 'Edit Specialization',
            'specialization' => $specialization
        ];
        $this->view('specializations/form', $data);
    }
    
    // Update specialization
    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('admin.php?action=specializations');
            return;
        }
        
        $name = trim($_POST['name'] ?? '');
        $description = trim($_POST['description'] ?? '');
        
        // Validation
        if (empty($name)) {
            $_SESSION['error'] = 'Specialization name is required';
            $this->redirect('admin.php?action=specializations&sub=edit&id=' . $id);
            return;
        }
        
        $specializationModel = new Specialization();
        
        // Check if name already exists (excluding current)
        if ($specializationModel->nameExists($name, $id)) {
            $_SESSION['error'] = 'Specialization name already exists';
            $this->redirect('admin.php?action=specializations&sub=edit&id=' . $id);
            return;
        }
        
        // Update specialization
        if ($specializationModel->update($id, $name, $description)) {
            $_SESSION['success'] = 'Specialization updated successfully';
        } else {
            $_SESSION['error'] = 'Failed to update specialization';
        }
        
        $this->redirect('admin.php?action=specializations');
    }
    
    // Delete specialization
    public function delete($id) {
        $specializationModel = new Specialization();
        $result = $specializationModel->delete($id);
        
        if ($result['success']) {
            $_SESSION['success'] = $result['message'];
        } else {
            $_SESSION['error'] = $result['message'];
        }
        
        $this->redirect('admin.php?action=specializations');
    }
}
?>