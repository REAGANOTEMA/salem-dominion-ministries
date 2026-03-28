<?php
// Production-ready PHP server with proper MIME types
$host = '0.0.0.0';
$port = 8080;
$docRoot = __DIR__;

// MIME type mapping
$mimeTypes = [
    'html' => 'text/html; charset=utf-8',
    'css' => 'text/css; charset=utf-8',
    'js' => 'application/javascript; charset=utf-8',
    'json' => 'application/json; charset=utf-8',
    'png' => 'image/png',
    'jpg' => 'image/jpeg',
    'jpeg' => 'image/jpeg',
    'svg' => 'image/svg+xml',
    'ico' => 'image/x-icon',
    'woff' => 'font/woff',
    'woff2' => 'font/woff2',
    'ttf' => 'font/ttf'
];

// Set CORS headers
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
header('Access-Control-Allow-Credentials: true');
header('Cross-Origin-Resource-Policy: cross-origin');

// Handle requests
$requestUri = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];

// Remove query string for file serving
$cleanUri = parse_url($requestUri, PHP_URL_PATH);
$filePath = $docRoot . $cleanUri;

// Normalize path
$filePath = realpath($filePath);

// Security check - ensure file is within document root
if ($filePath === false || !str_starts_with($filePath, $docRoot)) {
    http_response_code(403);
    echo 'Forbidden';
    exit;
}

// Handle OPTIONS preflight requests
if ($method === 'OPTIONS') {
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
    header('Content-Length: 0');
    http_response_code(200);
    exit;
}

// API requests - proxy to backend
if (str_starts_with($cleanUri, '/api/')) {
    $apiPath = str_replace('/api/', '/salem-dominion-ministries/api/', $cleanUri);
    $apiUrl = 'http://localhost/salem-dominion-ministries/api';
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $apiUrl . substr($cleanUri, 4));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
    curl_setopt($ch, CURLOPT_POSTFIELDS, file_get_contents('php://input'));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Access-Control-Allow-Origin: *'
    ]);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    header('Content-Type: application/json');
    http_response_code($httpCode);
    echo $response;
    exit;
}

// Static file serving
if (is_file($filePath)) {
    $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
    
    if (isset($mimeTypes[$extension])) {
        header('Content-Type: ' . $mimeTypes[$extension]);
        
        // Add caching for static assets
        if (in_array($extension, ['css', 'js', 'png', 'jpg', 'jpeg', 'svg', 'ico', 'woff', 'woff2'])) {
            $maxAge = 31536000; // 1 year
            header('Cache-Control: public, max-age=' . $maxAge);
            header('Expires: ' . gmdate('D, d M Y H:i:s', time() + $maxAge) . ' GMT');
        }
        
        readfile($filePath);
        exit;
    }
}

// SPA fallback - serve index.html for all other routes
header('Content-Type: text/html; charset=utf-8');
readfile($docRoot . '/index.html');
?>
