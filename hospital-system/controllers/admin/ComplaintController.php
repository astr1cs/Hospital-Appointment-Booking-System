<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../../models/Complaint.php';

class ComplaintController extends BaseController {
    
    // List all complaints
    public function index() {
        $complaintModel = new Complaint();
        
        $status = $_GET['status'] ?? 'all';
        $complaints = $complaintModel->getAll($status);
        $stats = $complaintModel->getStats();
        
        $data = [
            'title' => 'Patient Complaints',
            'complaints' => $complaints,
            'stats' => $stats,
            'currentStatus' => $status,
            'success' => $_SESSION['success'] ?? null,
            'error' => $_SESSION['error'] ?? null
        ];
        
        unset($_SESSION['success']);
        unset($_SESSION['error']);
        
        $this->view('complaints/index', $data);
    }
    
    // Show complaint details (renamed from view to show)
    public function show($id) {
        $complaintModel = new Complaint();
        $complaint = $complaintModel->getById($id);
        
        if (!$complaint) {
            $_SESSION['error'] = 'Complaint not found';
            $this->redirect('admin.php?action=complaints');
            return;
        }
        
        $data = [
            'title' => 'Complaint Details',
            'complaint' => $complaint
        ];
        
        $this->view('complaints/view', $data);
    }
    
    // Resolve complaint with response
    public function resolve($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('admin.php?action=complaints');
            return;
        }
        
        $adminResponse = trim($_POST['admin_response'] ?? '');
        
        if (empty($adminResponse)) {
            $_SESSION['error'] = 'Admin response is required';
            $this->redirect('admin.php?action=complaints&sub=show&id=' . $id);
            return;
        }
        
        $complaintModel = new Complaint();
        
        if ($complaintModel->resolve($id, $adminResponse)) {
            $_SESSION['success'] = 'Complaint resolved successfully';
        } else {
            $_SESSION['error'] = 'Failed to resolve complaint';
        }
        
        $this->redirect('admin.php?action=complaints');
    }
    
    // Reject complaint
    public function reject($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('admin.php?action=complaints');
            return;
        }
        
        $adminResponse = trim($_POST['admin_response'] ?? '');
        
        if (empty($adminResponse)) {
            $_SESSION['error'] = 'Admin response is required';
            $this->redirect('admin.php?action=complaints&sub=show&id=' . $id);
            return;
        }
        
        $complaintModel = new Complaint();
        
        if ($complaintModel->reject($id, $adminResponse)) {
            $_SESSION['success'] = 'Complaint rejected';
        } else {
            $_SESSION['error'] = 'Failed to reject complaint';
        }
        
        $this->redirect('admin.php?action=complaints');
    }
    
    // AJAX Delete complaint
    public function ajaxDelete() {
        header('Content-Type: application/json');
        
        $id = $_POST['id'] ?? null;
        
        if (!$id) {
            echo json_encode(['success' => false, 'message' => 'Complaint ID required']);
            return;
        }
        
        $complaintModel = new Complaint();
        
        if ($complaintModel->delete($id)) {
            echo json_encode(['success' => true, 'message' => 'Complaint deleted successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to delete complaint']);
        }
    }
}
?>