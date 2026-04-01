<?php
// Main entry point for Salem Dominion Ministries website
// This serves the React frontend and handles API routing

// Set default headers
header('X-Content-Type-Options: nosniff');

// Get the request URI
$request_uri = $_SERVER['REQUEST_URI'];
$path = parse_url($request_uri, PHP_URL_PATH);

// Define the subdirectory
$subdirectory = '/salem-dominion-ministries';

// Check if this is an API request
if (strpos($path, '/api/') !== false || strpos($request_uri, '/api/') !== false) {
    // Set CORS headers for API
    header('Content-Type: application/json');
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type, Authorization');
    
    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        http_response_code(200);
        exit;
    }
    
    // Route to backend API
    require_once __DIR__ . '/backend/api/index.php';
    exit;
}

// Serve manifest.json directly
if ($path === $subdirectory . '/manifest.json' || $path === '/manifest.json') {
    header('Content-Type: application/manifest+json');
    header('Cache-Control: public, max-age=3600');
    readfile(__DIR__ . '/manifest.json');
    exit;
}

// Serve static files from root directory
$root_file = __DIR__ . $path;
if (file_exists($root_file) && is_file($root_file)) {
    $extension = strtolower(pathinfo($root_file, PATHINFO_EXTENSION));
    
    // Set appropriate MIME type
    switch ($extension) {
        case 'css':
            header('Content-Type: text/css');
            break;
        case 'js':
            header('Content-Type: application/javascript');
            break;
        case 'json':
            header('Content-Type: application/json');
            break;
        case 'png':
            header('Content-Type: image/png');
            break;
        case 'jpg':
        case 'jpeg':
            header('Content-Type: image/jpeg');
            break;
        case 'gif':
            header('Content-Type: image/gif');
            break;
        case 'svg':
            header('Content-Type: image/svg+xml');
            break;
        case 'ico':
            header('Content-Type: image/x-icon');
            break;
        case 'woff':
            header('Content-Type: font/woff');
            break;
        case 'woff2':
            header('Content-Type: font/woff2');
            break;
        case 'ttf':
            header('Content-Type: font/ttf');
            break;
        case 'eot':
            header('Content-Type: application/vnd.ms-fontobject');
            break;
        case 'html':
            header('Content-Type: text/html');
            break;
    }
    
    header('Cache-Control: public, max-age=31536000');
    readfile($root_file);
    exit;
}

// Serve static files from frontend/dist directory
$react_build_dir = __DIR__ . '/frontend/dist';
$relative_path = $path;

// Remove subdirectory prefix if present
if (strpos($relative_path, $subdirectory) === 0) {
    $relative_path = substr($relative_path, strlen($subdirectory));
}

// Remove leading slash
$relative_path = ltrim($relative_path, '/');

$requested_file = $react_build_dir . '/' . $relative_path;

if (file_exists($requested_file) && is_file($requested_file)) {
    $extension = strtolower(pathinfo($requested_file, PATHINFO_EXTENSION));
    
    switch ($extension) {
        case 'css':
            header('Content-Type: text/css');
            break;
        case 'js':
            header('Content-Type: application/javascript');
            break;
        case 'json':
            header('Content-Type: application/json');
            break;
        case 'png':
            header('Content-Type: image/png');
            break;
        case 'jpg':
        case 'jpeg':
            header('Content-Type: image/jpeg');
            break;
        case 'gif':
            header('Content-Type: image/gif');
            break;
        case 'svg':
            header('Content-Type: image/svg+xml');
            break;
        case 'ico':
            header('Content-Type: image/x-icon');
            break;
        case 'woff':
            header('Content-Type: font/woff');
            break;
        case 'woff2':
            header('Content-Type: font/woff2');
            break;
        case 'ttf':
            header('Content-Type: font/ttf');
            break;
        case 'eot':
            header('Content-Type: application/vnd.ms-fontobject');
            break;
    }
    
    header('Cache-Control: public, max-age=31536000');
    readfile($requested_file);
    exit;
}

// For all other requests, serve the React app (SPA fallback)
$index_file = $react_build_dir . '/index.html';

if (file_exists($index_file)) {
    header('Content-Type: text/html; charset=utf-8');
    header('Cache-Control: no-cache, no-store, must-revalidate');
    readfile($index_file);
    exit;
}

// If nothing else works, show 404
http_response_code(404);
echo '<h1>404 - Page Not Found</h1>';
echo '<p>The requested resource was not found.</p>';
?>