<?php
// Simple PHP server for live deployment
$host = '0.0.0.0';
$port = 8080;
$docRoot = __DIR__;

// Set headers
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Content-Type: text/html; charset=utf-8');

// Handle requests
$requestUri = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];

// API requests to backend
if (strpos($requestUri, '/api/') === 0) {
    // Forward API requests to the actual API
    $apiPath = str_replace('/api/', '/salem-dominion-ministries/api/', $requestUri);
    $apiUrl = 'http://localhost/salem-dominion-ministries/api';
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $apiUrl . str_replace('/api/', '', $requestUri));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
    curl_setopt($ch, CURLOPT_POSTFIELDS, file_get_contents('php://input'));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Access-Control-Allow-Origin: *'
    ]);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    header('Content-Type: application/json');
    http_response_code($httpCode);
    echo $response;
    exit;
}

// Static file serving or SPA fallback
$requestedFile = $docRoot . parse_url($requestUri, PHP_URL_PATH);
$requestedFile = realpath($requestedFile);

if ($requestedFile && is_file($requestedFile) && !in_array($requestedFile, ['server.php'])) {
    $extension = strtolower(pathinfo($requestedFile, PATHINFO_EXTENSION));
    $mimeTypes = [
        'html' => 'text/html',
        'css' => 'text/css',
        'js' => 'application/javascript',
        'json' => 'application/json',
        'png' => 'image/png',
        'jpg' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'svg' => 'image/svg+xml',
        'ico' => 'image/x-icon'
    ];
    
    if (isset($mimeTypes[$extension])) {
        header('Content-Type: ' . $mimeTypes[$extension]);
        readfile($requestedFile);
        exit;
    }
}

// SPA fallback - serve index.html for all other routes
readfile($docRoot . '/index.html');
?>
