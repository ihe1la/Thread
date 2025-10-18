<?php
class UserController {
    private $db;
    private $security;
    
    public function __construct(Database $db, Security $security) {
        $this->db = $db;
        $this->security = $security;
    }
    
    public function handleRequest($method) {
        switch ($method) {
            case 'POST':
                if (strpos($_SERVER['REQUEST_URI'], '/api/users/login') !== false) {
                    $this->login();
                }
                break;
            case 'GET':
                $this->getUsers();
                break;
            default:
                http_response_code(405);
                echo json_encode(['error' => 'Method not allowed']);
        }
    }
    
    private function login() {
        $data = json_decode(file_get_contents('php://input'), true);
        
        if (!isset($data['username']) || !isset($data['password'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Missing credentials']);
            return;
        }
        
        $stmt = $this->db->query(
            "SELECT * FROM users WHERE username = :username",
            ['username' => $data['username']]
        );
        
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user && password_verify($data['password'], $user['password'])) {
            // Create a simple JWT-like token
            $token = base64_encode(json_encode([
                'user_id' => $user['id'],
                'username' => $user['username'],
                'is_admin' => $user['is_admin'],
                'exp' => time() + 3600
            ]));
            
            echo json_encode(['token' => $token]);
        } else {
            http_response_code(401);
            echo json_encode(['error' => 'Invalid credentials']);
        }
    }
    
    private function getUsers() {
        $stmt = $this->db->query("SELECT id, username, is_admin FROM users");
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($users);
    }
}