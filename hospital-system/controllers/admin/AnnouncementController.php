<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../../models/Announcement.php';

class AnnouncementController extends BaseController {
    
    // List all announcements
    public function index() {
        $announcementModel = new Announcement();
        $announcements = $announcementModel->getAll();
        
        $data = [
            'title' => 'Hospital Announcements',
            'announcements' => $announcements,
            'success' => $_SESSION['success'] ?? null,
            'error' => $_SESSION['error'] ?? null
        ];
        
        unset($_SESSION['success']);
        unset($_SESSION['error']);
        
        $this->view('announcements/index', $data);
    }
    
    // Show create form
    public function create() {
        $data = [
            'title' => 'Create Announcement'
        ];
        $this->view('announcements/create', $data);
    }
    
    // Store announcement
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('admin.php?action=announcements');
            return;
        }
        
        $title = trim($_POST['title'] ?? '');
        $body = trim($_POST['body'] ?? '');
        $target_role = $_POST['target_role'] ?? 'all';
        
        if (empty($title)) {
            $_SESSION['error'] = 'Title is required';
            $this->redirect('admin.php?action=announcements&sub=create');
            return;
        }
        
        if (empty($body)) {
            $_SESSION['error'] = 'Message body is required';
            $this->redirect('admin.php?action=announcements&sub=create');
            return;
        }
        
        $announcementModel = new Announcement();
        
        $data = [
            'author_id' => $_SESSION['user_id'],
            'title' => $title,
            'body' => $body,
            'target_role' => $target_role
        ];
        
        if ($announcementModel->create($data)) {
            $_SESSION['success'] = 'Announcement published successfully';
        } else {
            $_SESSION['error'] = 'Failed to publish announcement';
        }
        
        $this->redirect('admin.php?action=announcements');
    }
    
    // AJAX Delete announcement (JSON response)
    public function ajaxDelete() {
        header('Content-Type: application/json');
        
        $id = $_POST['id'] ?? null;
        
        if (!$id) {
            echo json_encode(['success' => false, 'message' => 'Announcement ID required']);
            return;
        }
        
        $announcementModel = new Announcement();
        
        if ($announcementModel->delete($id)) {
            echo json_encode(['success' => true, 'message' => 'Announcement deleted successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to delete announcement']);
        }
    }
}
?>