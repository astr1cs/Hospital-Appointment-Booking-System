<?php
require_once __DIR__ . '/BaseController.php';

class DashboardController extends BaseController {
    
    public function index() {
        $data = [
            'title' => 'Dashboard',
            'page' => 'dashboard'
        ];
        $this->view('dashboard', $data);
    }
}