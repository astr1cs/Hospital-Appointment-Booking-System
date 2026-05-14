<?php
require_once __DIR__ . '/BaseController.php';

class SettingController extends BaseController {
    public function index() { $this->view('settings/index', ['title' => 'Global Settings']); }
    public function update() { $this->redirect('admin.php?action=settings'); }
}