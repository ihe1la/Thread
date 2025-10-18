<?php
class ThreadController {
    private $db;
    private $security;
    
    public function __construct(Database $db, Security $security) {
        $this->db = $db;
        $this->security = $security;
    }
    
    public function handleRequest($method) {
        switch ($method) {
            case 'GET':
                $this->getThreads();
                break;
            case 'POST':
                $this->createThread();
                break;
            default:
                http_response_code(405);
                echo json_encode(['error' => 'Method not allowed']);
        }
    }
    
    private function getThreads() {
        $stmt = $this->db->query("
            SELECT t.*, u.username 
            FROM threads t 
            JOIN users u ON t.user_id = u.id 
            ORDER BY t.created_at DESC
        ");
        
        $threads = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($threads);
    }
    
    private function createThread() {
        $data = json_decode(file_get_contents('php://input'), true);
        
        if (!isset($data['title']) || !isset($data['content'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Missing required fields']);
            return;
        }
        
        $title = $this->security->sanitizeInput($data['title']);
        $content = $this->security->sanitizeInput($data['content']);
        
        $sql = "INSERT INTO threads (user_id, title, content) VALUES (:user_id, :title, :content)";
        $this->db->query($sql, [
            'user_id' => 1, // TODO: Get from authenticated user
            'title' => $title,
            'content' => $content
        ]);
        
        http_response_code(201);
        echo json_encode(['message' => 'Thread created']);
    }
}