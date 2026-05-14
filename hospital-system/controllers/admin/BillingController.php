<?php
require_once __DIR__ . '/BaseController.php';

class BillingController extends BaseController {
    public function index() { $this->view('billing/index', ['title' => 'Billing Dashboard']); }
    public function details($id) { $this->view('billing/details', ['title' => 'Bill Details', 'id' => $id]); }
}