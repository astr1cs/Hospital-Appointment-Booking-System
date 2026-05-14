<?php
require_once __DIR__ . '/BaseController.php';

class ReportController extends BaseController {
    public function revenue() { $this->view('reports/revenue', ['title' => 'Revenue Report']); }
    public function volume() { $this->view('reports/volume', ['title' => 'Appointment Volume']); }
    public function performance() { $this->view('reports/performance', ['title' => 'Doctor Performance']); }
    public function export() { $this->view('reports/export', ['title' => 'Export Reports']); }
}