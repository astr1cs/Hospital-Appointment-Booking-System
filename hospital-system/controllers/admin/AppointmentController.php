<?php
require_once __DIR__ . '/BaseController.php';

class AppointmentController extends BaseController {
    public function index() { $this->view('appointments/index', ['title' => 'All Appointments']); }
    public function filter() { $this->view('appointments/filter', ['title' => 'Filter Appointments']); }
}