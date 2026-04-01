<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("X-Content-Type-Options: nosniff");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit(0);
}

// Handle CORS preflight
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

require_once __DIR__ . '/../config/database.php';

// Get the route from query string (for api.php?route=endpoint format)
$route = $_GET['route'] ?? '';

// Also support path-based routing
$request_uri = $_SERVER['REQUEST_URI'];
$path = parse_url($request_uri, PHP_URL_PATH);

// Remove subdirectory from path if present
$base_path = '/salem-dominion-ministries';
if (strpos($path, $base_path) === 0) {
    $path = substr($path, strlen($base_path));
}

// Health check endpoint
if ($route === 'health' || strpos($request_uri, '/health') !== false) {
    echo json_encode([
        'status' => 'OK',
        'message' => 'Salem Dominion Ministries API is running',
        'timestamp' => date('c'),
        'environment' => 'development'
    ]);
    exit;
}

// If using query string routing (?route=endpoint)
if (!empty($route)) {
    // Parse action if present
    $action = $_GET['action'] ?? null;
    
    switch ($route) {
        case 'auth':
            if ($action === 'verify' || strpos($path, '/auth/verify') !== false) {
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
        case 'children_ministry':
            require_once 'children_ministry.php';
            break;
        default:
            http_response_code(404);
            echo json_encode([
                'success' => false,
                'message' => 'Endpoint not found: ' . $route
            ]);
            break;
    }
    exit;
}

// Path-based routing (for /api/endpoint format)
$path_parts = explode('/', trim($path, '/'));

// Route handling - find the first non-empty part
$endpoint = '';
$id = null;

for ($i = 0; $i < count($path_parts); $i++) {
    $part = $path_parts[$i];
    if (!empty($part)) {
        if (empty($endpoint)) {
            // Skip 'api' if it's the first part
            if ($part !== 'api') {
                $endpoint = $part;
            }
        } elseif ($id === null) {
            $id = $part;
        }
    }
}

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
    case 'children_ministry':
        require_once 'children_ministry.php';
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
