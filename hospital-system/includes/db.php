<?php
require_once 'config.php';  // ← ADD THIS LINE

class Database {
    private $connection;
    private static $instance = null;
    
    private function __construct() {
        try {
            $this->connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
            
            if ($this->connection->connect_error) {
                throw new Exception("Connection failed: " . $this->connection->connect_error);
            }
            
            $this->connection->set_charset("utf8mb4");
        } catch (Exception $e) {
            die("Database connection error: " . $e->getMessage());
        }
    }
    
    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }
    
    public function getConnection() {
        return $this->connection;
    }
    
    // Prepare statement wrapper
    public function prepare($sql) {
        return $this->connection->prepare($sql);
    }
    
    // Escape string (fallback only - use prepared statements)
    public function escape($string) {
        return $this->connection->real_escape_string($string);
    }
}
?>