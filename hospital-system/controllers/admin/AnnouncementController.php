<?php
require_once __DIR__ . '/BaseController.php';

class AnnouncementController extends BaseController {
    public function index() { $this->view('announcements/index', ['title' => 'Announcements']); }
    public function create() { $this->redirect('admin.php?action=announcements'); }
    public function delete($id) { $this->redirect('admin.php?action=announcements'); }
}