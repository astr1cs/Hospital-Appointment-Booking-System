<?php
require_once __DIR__ . '/BaseController.php';

class DoctorController extends BaseController {
    public function index() { $this->view('doctors/index', ['title' => 'Manage Doctors']); }
    public function create() { $this->view('doctors/create', ['title' => 'Add Doctor']); }
    public function edit($id) { $this->view('doctors/edit', ['title' => 'Edit Doctor', 'id' => $id]); }
    public function pending() { $this->view('doctors/pending', ['title' => 'Pending Approvals']); }
    public function approve($id) { $this->redirect('admin.php?action=doctors'); }
    public function reject($id) { $this->redirect('admin.php?action=doctors&sub=pending'); }
    public function deactivate($id) { $this->redirect('admin.php?action=doctors'); }
}