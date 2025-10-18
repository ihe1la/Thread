<?php
class OAuthController {
    private $db;
    private $security;
    
    public function __construct(Database $db, Security $security) {
        $this->db = $db;
        $this->security = $security;
    }
    
    public function handleRequest($method) {
        switch ($method) {
            case 'POST':
                if (strpos($_SERVER['REQUEST_URI'], '/api/oauth/register') !== false) {
                    $this->registerClient();
                } elseif (strpos($_SERVER['REQUEST_URI'], '/api/oauth/authorize') !== false) {
                    $this->authorize();
                }
                break;
            default:
                http_response_code(405);
                echo json_encode(['error' => 'Method not allowed']);
        }
    }
    
    private function registerClient() {
        $data = json_decode(file_get_contents('php://input'), true);
        
        if (!isset($data['redirect_uri'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Missing redirect URI']);
            return;
        }
        
        // Validate redirect URI if strict matching is enabled
        if (!$this->security->validateRedirect($data['redirect_uri'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid redirect URI']);
            return;
        }
        
        $clientId = bin2hex(random_bytes(16));
        $clientSecret = bin2hex(random_bytes(32));
        
        $sql = "INSERT INTO oauth_clients (client_id, client_secret, redirect_uri) 
                VALUES (:client_id, :client_secret, :redirect_uri)";
        
        $this->db->query($sql, [
            'client_id' => $clientId,
            'client_secret' => $clientSecret,
            'redirect_uri' => $data['redirect_uri']
        ]);
        
        echo json_encode([
            'client_id' => $clientId,
            'client_secret' => $clientSecret
        ]);
    }
    
    private function authorize() {
        $data = json_decode(file_get_contents('php://input'), true);
        
        if (!isset($data['client_id']) || !isset($data['redirect_uri'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Missing required fields']);
            return;
        }
        
        // Get client from database
        $stmt = $this->db->query(
            "SELECT * FROM oauth_clients WHERE client_id = :client_id",
            ['client_id' => $data['client_id']]
        );
        
        $client = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$client) {
            http_response_code(401);
            echo json_encode(['error' => 'Invalid client']);
            return;
        }
        
        // Validate redirect URI
        if ($this->security->validateRedirect($data['redirect_uri'])) {
            $code = bin2hex(random_bytes(16));
            echo json_encode(['code' => $code]);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid redirect URI']);
        }
    }
}