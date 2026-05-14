<?php
require_once __DIR__ . '/BaseController.php';

class ComplaintController extends BaseController {
    public function index() { $this->view('complaints/index', ['title' => 'Patient Complaints']); }
    public function view($id) { $this->view('complaints/view', ['title' => 'Complaint Details', 'id' => $id]); }
    public function resolve($id) { $this->redirect('admin.php?action=complaints'); }
}