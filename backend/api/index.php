<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

require_once '../config/database.php';

// Health check endpoint
if (($_GET['route'] ?? null === 'health') || (strpos($_SERVER['REQUEST_URI'], 'health') !== false)) {
    echo json_encode([
        'status' => 'OK',
        'message' => 'Salem Dominion Ministries API is running',
        'timestamp' => date('c'),
        'environment' => 'development'
    ]);
    exit;
}

// Parse the request
$request_uri = $_SERVER['REQUEST_URI'];
$path = parse_url($request_uri, PHP_URL_PATH);
$path_parts = explode('/', trim($path, '/'));

// Route handling
$endpoint = $path_parts[1] ?? '';
$id = $path_parts[2] ?? null;

switch ($endpoint) {
    case 'auth':
        if ($id === 'verify') {
            require_once 'auth_verify.php';
        } else {
            require_once 'auth.php';
        }
        break;
    case 'users':
        require_once 'users.php';
        break;
    case 'events':
        require_once 'events.php';
        break;
    case 'sermons':
        require_once 'sermons.php';
        break;
    case 'prayers':
        require_once 'prayers.php';
        break;
    case 'donations':
        require_once 'donations.php';
        break;
    case 'contact':
        require_once 'contact.php';
        break;
    case 'blog':
        require_once 'blog.php';
        break;
    case 'gallery':
        require_once 'gallery.php';
        break;
    case 'news':
        require_once 'news.php';
        break;
    case 'messages':
        require_once 'messages.php';
        break;
    case 'notifications':
        require_once 'notifications.php';
        break;
    default:
        http_response_code(404);
        echo json_encode([
            'success' => false,
            'message' => 'Endpoint not found'
        ]);
        break;
}
?>
