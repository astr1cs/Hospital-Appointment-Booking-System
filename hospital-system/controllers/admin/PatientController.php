<?php
require_once __DIR__ . '/BaseController.php';

class PatientController extends BaseController {
    public function index() { $this->view('patients/index', ['title' => 'Manage Patients']); }
    public function view($id) { $this->view('patients/view', ['title' => 'Patient Details', 'id' => $id]); }
    public function deactivate($id) { $this->redirect('admin.php?action=patients'); }
}