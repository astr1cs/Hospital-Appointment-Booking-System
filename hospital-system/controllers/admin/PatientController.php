<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../../models/Patient.php';

class PatientController extends BaseController {
    
    // List all patients
    public function index() {
        $patientModel = new Patient();
        
        $search = $_GET['search'] ?? null;
        $patients = $patientModel->getAll($search);
        $stats = $patientModel->getStats();
        
        $data = [
            'title' => 'Manage Patients',
            'patients' => $patients,
            'stats' => $stats,
            'search' => $search,
            'success' => $_SESSION['success'] ?? null,
            'error' => $_SESSION['error'] ?? null
        ];
        
        unset($_SESSION['success']);
        unset($_SESSION['error']);
        
        $this->view('patients/index', $data);
    }
    
    // Show patient details (renamed from view to show)
    public function show($id) {
        $patientModel = new Patient();
        $patient = $patientModel->getById($id);
        
        if (!$patient) {
            $_SESSION['error'] = 'Patient not found';
            $this->redirect('admin.php?action=patients');
            return;
        }
        
        $appointments = $patientModel->getAppointmentHistory($id);
        $billings = $patientModel->getBillingHistory($id);
        
        $data = [
            'title' => 'Patient Details',
            'patient' => $patient,
            'appointments' => $appointments,
            'billings' => $billings
        ];
        
        $this->view('patients/view', $data);
    }
    
    // Deactivate patient
    public function deactivate($id) {
        $patientModel = new Patient();
        
        if ($patientModel->deactivate($id)) {
            $_SESSION['success'] = 'Patient deactivated successfully';
        } else {
            $_SESSION['error'] = 'Failed to deactivate patient';
        }
        
        $this->redirect('admin.php?action=patients');
    }
    
    // Activate patient
    public function activate($id) {
        $patientModel = new Patient();
        
        if ($patientModel->activate($id)) {
            $_SESSION['success'] = 'Patient activated successfully';
        } else {
            $_SESSION['error'] = 'Failed to activate patient';
        }
        
        $this->redirect('admin.php?action=patients');
    }
}
?>