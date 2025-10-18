<?php
class Database {
    private $pdo;
    
    public function __construct() {
        $host = $_ENV['DB_HOST'];
        $db = $_ENV['DB_NAME'];
        $user = $_ENV['DB_USER'];
        $pass = $_ENV['DB_PASSWORD'];
        
        $dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";
        $this->pdo = new PDO($dsn, $user, $pass);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    
    public function query($sql, $params = []) {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }
    
    public function getSecurityToggles() {
        $stmt = $this->query("SELECT * FROM security_toggles LIMIT 1");
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function updateSecurityToggles($toggles) {
        $sql = "UPDATE security_toggles SET 
                sanitization_level = :sanitization_level,
                allow_svg_upload = :allow_svg_upload,
                allow_external_fetch = :allow_external_fetch,
                csp_enabled = :csp_enabled,
                require_strict_redirect_match = :require_strict_redirect_match";
        
        return $this->query($sql, $toggles);
    }
}