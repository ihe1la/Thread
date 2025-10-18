<?php
class Security {
    private $db;
    private $toggles;
    
    public function __construct(Database $db) {
        $this->db = $db;
        $this->toggles = $db->getSecurityToggles();
    }
    
    public function sanitizeInput($input) {
        switch ($this->toggles['sanitization_level']) {
            case 'none':
                return $input;
            case 'basic':
                return strip_tags($input);
            case 'strict':
                return htmlspecialchars($input, ENT_QUOTES | ENT_HTML5, 'UTF-8');
            default:
                return htmlspecialchars($input, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        }
    }
    
    public function validateImageUpload($file) {
        if (!isset($file['tmp_name']) || !is_uploaded_file($file['tmp_name'])) {
            throw new Exception('Invalid file upload');
        }
        
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if ($this->toggles['allow_svg_upload']) {
            $allowedTypes[] = 'image/svg+xml';
        }
        
        if (!in_array($file['type'], $allowedTypes)) {
            throw new Exception('Invalid file type');
        }
        
        return true;
    }
    
    public function validateRedirect($url) {
        if ($this->toggles['require_strict_redirect_match']) {
            $allowedDomains = [parse_url($_ENV['ALLOWED_ORIGINS'], PHP_URL_HOST)];
            $redirectDomain = parse_url($url, PHP_URL_HOST);
            return in_array($redirectDomain, $allowedDomains);
        }
        return true;
    }
    
    public function applySecurityHeaders() {
        if ($this->toggles['csp_enabled']) {
            $cspDirectives = [
                "default-src 'self'",
                "img-src 'self' data:",
                "script-src 'self'",
                "style-src 'self' 'unsafe-inline'",
                "frame-ancestors 'none'"
            ];
            header("Content-Security-Policy: " . implode('; ', $cspDirectives));
        }
    }
    
    public function validateExternalFetch($url) {
        if (!$this->toggles['allow_external_fetch']) {
            throw new Exception('External fetch not allowed');
        }
        return true;
    }
}