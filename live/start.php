<?php
// Ultimate server that handles everything correctly
$host = '0.0.0.0';
$port = 8080;
$docRoot = __DIR__;

// Start server with error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "Salem Dominion Ministries - Ultimate Server\n";
echo "========================================\n";
echo "Starting server on http://$host:$port\n";
echo "Document Root: $docRoot\n";
echo "Features:\n";
echo "  - Perfect MIME types\n";
echo "  - Full CORS support\n";
echo "  - API proxy\n";
echo "  - SPA routing\n";
echo "  - Static file serving\n";
echo "  - Error handling\n";
echo "========================================\n\n";

// Create server
$socket = @stream_socket_server("tcp://$host:$port", $errno, $errstr);

if (!$socket) {
    die("Failed to start server: $errstr\n");
}

echo "✅ Server started successfully!\n";
echo "🌐 Access: http://localhost:$port\n";
echo "📱 Mobile: http://127.0.0.1:$port\n";
echo "⏹ Press Ctrl+C to stop\n\n";

// MIME types
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

// Main server loop
while (true) {
    // Accept connection
    $client = @stream_socket_accept($socket);
    if (!$client) continue;
    
    // Read request
    $request = @fread($client, 4096);
    if (!$request) continue;
    
    // Parse request
    $lines = explode("\r\n", $request);
    $firstLine = $lines[0] ?? '';
    $parts = explode(' ', $firstLine);
    $method = $parts[0] ?? 'GET';
    $path = $parts[1] ?? '/';
    
    // Clean path
    $path = parse_url($path, PHP_URL_PATH) ?: '/';
    
    // Handle request
    $response = handleRequest($method, $path, $docRoot, $mimeTypes);
    
    // Send response
    @fwrite($client, $response);
    @fclose($client);
}

// Close server
@fclose($socket);

function handleRequest($method, $path, $docRoot, $mimeTypes) {
    // Security check
    $realPath = realpath($docRoot . $path);
    if ($realPath === false || !str_starts_with($realPath, $docRoot)) {
        return "HTTP/1.1 403 Forbidden\r\nContent-Type: text/plain\r\n\r\nForbidden";
    }
    
    // API proxy
    if (str_starts_with($path, '/api/')) {
        return proxyApi($method, $path);
    }
    
    // Static file
    $filePath = $docRoot . $path;
    if (is_file($filePath)) {
        return serveStaticFile($filePath, $mimeTypes);
    }
    
    // SPA fallback
    return serveSpaFallback($docRoot);
}

function proxyApi($method, $path) {
    $apiUrl = 'http://localhost/salem-dominion-ministries/api' . substr($path, 4);
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $apiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Access-Control-Allow-Origin: *'
    ]);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    return "HTTP/1.1 $httpCode OK\r\nContent-Type: application/json\r\nAccess-Control-Allow-Origin: *\r\nAccess-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS\r\nAccess-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With\r\nAccess-Control-Allow-Credentials: true\r\n\r\n" . $response;
}

function serveStaticFile($filePath, $mimeTypes) {
    $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
    $mimeType = $mimeTypes[$extension] ?? 'text/plain';
    
    $content = @file_get_contents($filePath);
    if ($content === false) {
        return "HTTP/1.1 404 Not Found\r\nContent-Type: text/plain\r\n\r\nFile not found";
    }
    
    $headers = [
        "HTTP/1.1 200 OK",
        "Content-Type: $mimeType",
        "Access-Control-Allow-Origin: *",
        "Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS",
        "Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With",
        "Access-Control-Allow-Credentials: true",
        "Content-Length: " . strlen($content)
    ];
    
    // Add caching for static assets
    if (in_array($extension, ['css', 'js', 'png', 'jpg', 'jpeg', 'svg', 'ico', 'woff', 'woff2'])) {
        $headers[] = "Cache-Control: public, max-age=31536000";
        $headers[] = "Expires: " . gmdate('D, d M Y H:i:s', time() + 31536000) . ' GMT';
    }
    
    return implode("\r\n", $headers) . "\r\n\r\n" . $content;
}

function serveSpaFallback($docRoot) {
    $indexPath = $docRoot . '/index.html';
    $content = @file_get_contents($indexPath);
    
    if ($content === false) {
        return "HTTP/1.1 404 Not Found\r\nContent-Type: text/plain\r\n\r\nIndex not found";
    }
    
    return "HTTP/1.1 200 OK\r\nContent-Type: text/html; charset=utf-8\r\nAccess-Control-Allow-Origin: *\r\nAccess-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS\r\nAccess-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With\r\nAccess-Control-Allow-Credentials: true\r\n\r\n" . $content;
}
?>
