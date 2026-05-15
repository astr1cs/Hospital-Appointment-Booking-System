<?php
abstract class ReceptionistBaseController {
    protected $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    protected function view($view, $data = []) {
        extract($data);
        
        if (file_exists('views/receptionist/layouts/header.php')) {
            require_once 'views/receptionist/layouts/header.php';
        }
        
        $viewPath = 'views/receptionist/' . $view . '.php';
        if (file_exists($viewPath)) {
            require_once $viewPath;
        } else {
            die('View not found: ' . $viewPath);
        }
        
        if (file_exists('views/receptionist/layouts/footer.php')) {
            require_once 'views/receptionist/layouts/footer.php';
        }
    }
    
    protected function redirect($url) {
        header('Location: ' . SITE_URL . $url);
        exit();
    }
    
    protected function json($data) {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit();
    }
    
    protected function getUserId() {
        return $_SESSION['user_id'];
    }
}
?>