<?php
require_once __DIR__ . '/src/config.php';
require_once __DIR__ . '/src/Database.php';
require_once __DIR__ . '/src/Security.php';
require_once __DIR__ . '/src/ImageService.php';
require_once __DIR__ . '/src/WebhookService.php';

header('Access-Control-Allow-Origin: ' . $_ENV['ALLOWED_ORIGINS']);
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

try {
    $db = new Database();
    $security = new Security($db);
    
    // Apply security headers based on configuration
    $security->applySecurityHeaders();
    
    // Basic routing
    if (preg_match('/^\/api\/threads/', $uri)) {
        require __DIR__ . '/src/controllers/ThreadController.php';
        $controller = new ThreadController($db, $security);
        $controller->handleRequest($method);
    }
    elseif (preg_match('/^\/api\/users/', $uri)) {
        require __DIR__ . '/src/controllers/UserController.php';
        $controller = new UserController($db, $security);
        $controller->handleRequest($method);
    }
    elseif (preg_match('/^\/api\/admin/', $uri)) {
        require __DIR__ . '/src/controllers/AdminController.php';
        $controller = new AdminController($db, $security);
        $controller->handleRequest($method);
    }
    elseif (preg_match('/^\/api\/oauth/', $uri)) {
        require __DIR__ . '/src/controllers/OAuthController.php';
        $controller = new OAuthController($db, $security);
        $controller->handleRequest($method);
    }
    else {
        http_response_code(404);
        echo json_encode(['error' => 'Not found']);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}