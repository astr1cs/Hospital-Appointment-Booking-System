<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../../models/Dependent.php';

class DependentController extends PatientBaseController {
    
    // List dependents
    public function index() {
        $userId = $this->getUserId();
        $dependentModel = new Dependent();
        
        $dependents = $dependentModel->getByPatientId($userId);
        
        $data = [
            'title' => 'My Dependents',
            'dependents' => $dependents,
            'success' => $_SESSION['success'] ?? null,
            'error' => $_SESSION['error'] ?? null
        ];
        
        unset($_SESSION['success']);
        unset($_SESSION['error']);
        
        $this->view('dependents/index', $data);
    }
    
    // Show add form
    public function create() {
        $data = [
            'title' => 'Add Dependent',
            'dependent' => null
        ];
        
        $this->view('dependents/form', $data);
    }
    
    // Store dependent
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('patient.php?action=dependents');
            return;
        }
        
        $userId = $this->getUserId();
        
        $data = [
            'name' => $_POST['name'] ?? '',
            'date_of_birth' => $_POST['date_of_birth'] ?? null,
            'relationship' => $_POST['relationship'] ?? '',
            'blood_group' => $_POST['blood_group'] ?? null
        ];
        
        if (empty($data['name'])) {
            $_SESSION['error'] = 'Name is required';
            $this->redirect('patient.php?action=dependents&sub=create');
            return;
        }
        
        $dependentModel = new Dependent();
        
        if ($dependentModel->add($userId, $data)) {
            $_SESSION['success'] = 'Dependent added successfully';
        } else {
            $_SESSION['error'] = 'Failed to add dependent';
        }
        
        $this->redirect('patient.php?action=dependents');
    }
    
    // Show edit form
    public function edit($id) {
        $userId = $this->getUserId();
        $dependentModel = new Dependent();
        
        $dependent = $dependentModel->getById($id, $userId);
        
        if (!$dependent) {
            $_SESSION['error'] = 'Dependent not found';
            $this->redirect('patient.php?action=dependents');
            return;
        }
        
        $data = [
            'title' => 'Edit Dependent',
            'dependent' => $dependent
        ];
        
        $this->view('dependents/form', $data);
    }
    
    // Update dependent
    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('patient.php?action=dependents');
            return;
        }
        
        $userId = $this->getUserId();
        
        $data = [
            'name' => $_POST['name'] ?? '',
            'date_of_birth' => $_POST['date_of_birth'] ?? null,
            'relationship' => $_POST['relationship'] ?? '',
            'blood_group' => $_POST['blood_group'] ?? null
        ];
        
        if (empty($data['name'])) {
            $_SESSION['error'] = 'Name is required';
            $this->redirect('patient.php?action=dependents&sub=edit&id=' . $id);
            return;
        }
        
        $dependentModel = new Dependent();
        
        if ($dependentModel->update($id, $userId, $data)) {
            $_SESSION['success'] = 'Dependent updated successfully';
        } else {
            $_SESSION['error'] = 'Failed to update dependent';
        }
        
        $this->redirect('patient.php?action=dependents');
    }
    
    // Delete dependent
    public function delete($id) {
        $userId = $this->getUserId();
        $dependentModel = new Dependent();
        
        if ($dependentModel->delete($id, $userId)) {
            $_SESSION['success'] = 'Dependent deleted successfully';
        } else {
            $_SESSION['error'] = 'Failed to delete dependent';
        }
        
        $this->redirect('patient.php?action=dependents');
    }
}
?>