<?php
require_once __DIR__ . '/BaseController.php';

class SpecializationController extends BaseController {
    public function index() { $this->view('specializations/index', ['title' => 'Manage Specializations']); }
    public function create() { $this->redirect('admin.php?action=specializations'); }
    public function edit($id) { $this->view('specializations/form', ['title' => 'Edit Specialization', 'id' => $id]); }
    public function delete($id) { $this->redirect('admin.php?action=specializations'); }
}