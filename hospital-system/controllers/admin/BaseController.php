<?php
abstract class BaseController {
    protected $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    protected function view($view, $data = []) {
        extract($data);
        
        if (file_exists('views/admin/layouts/header.php')) {
            require_once 'views/admin/layouts/header.php';
        }
        
        $viewPath = 'views/admin/' . $view . '.php';
        if (file_exists($viewPath)) {
            require_once $viewPath;
        } else {
            die('View not found: ' . $viewPath);
        }
        
        if (file_exists('views/admin/layouts/footer.php')) {
            require_once 'views/admin/layouts/footer.php';
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
}