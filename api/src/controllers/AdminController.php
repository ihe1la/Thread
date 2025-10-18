<?php
class AdminController {
    private $db;
    private $security;
    
    public function __construct(Database $db, Security $security) {
        $this->db = $db;
        $this->security = $security;
    }
    
    public function handleRequest($method) {
        switch ($method) {
            case 'GET':
                $this->getSecurityToggles();
                break;
            case 'PUT':
                $this->updateSecurityToggles();
                break;
            default:
                http_response_code(405);
                echo json_encode(['error' => 'Method not allowed']);
        }
    }
    
    private function getSecurityToggles() {
        $toggles = $this->db->getSecurityToggles();
        echo json_encode($toggles);
    }
    
    private function updateSecurityToggles() {
        $data = json_decode(file_get_contents('php://input'), true);
        
        // Validate toggle values
        $toggles = [
            'sanitization_level' => in_array($data['sanitization_level'], ['none', 'basic', 'strict']) 
                ? $data['sanitization_level'] : 'strict',
            'allow_svg_upload' => (bool)$data['allow_svg_upload'],
            'allow_external_fetch' => (bool)$data['allow_external_fetch'],
            'csp_enabled' => (bool)$data['csp_enabled'],
            'require_strict_redirect_match' => (bool)$data['require_strict_redirect_match']
        ];
        
        $this->db->updateSecurityToggles($toggles);
        echo json_encode(['message' => 'Security toggles updated']);
    }
}