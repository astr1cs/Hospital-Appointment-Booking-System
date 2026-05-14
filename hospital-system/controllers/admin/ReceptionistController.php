<?php
require_once __DIR__ . '/BaseController.php';

class ReceptionistController extends BaseController {
    public function index() { $this->view('receptionists/index', ['title' => 'Manage Receptionists']); }
    public function create() { $this->view('receptionists/create', ['title' => 'Add Receptionist']); }
    public function edit($id) { $this->view('receptionists/edit', ['title' => 'Edit Receptionist', 'id' => $id]); }
    public function deactivate($id) { $this->redirect('admin.php?action=receptionists'); }
}