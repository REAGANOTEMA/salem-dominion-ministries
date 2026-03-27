<?php
// Main entry point for Salem Dominion Ministries website
// This serves the React frontend and handles API routing

// Set headers
header('Content-Type: text/html; charset=utf-8');

// Check if this is an API request
if (strpos($_SERVER['REQUEST_URI'], '/api/') !== false) {
    // Route to backend API
    require_once 'backend/api/index.php';
    exit;
}

// Serve the React frontend
$react_build_dir = __DIR__ . '/frontend/dist';

// Check if the requested file exists in the build directory
$request_uri = $_SERVER['REQUEST_URI'];
$requested_file = $react_build_dir . parse_url($request_uri, PHP_URL_PATH);

if (file_exists($requested_file) && is_file($requested_file)) {
    // Serve static files with correct MIME types
    $extension = strtolower(pathinfo($requested_file, PATHINFO_EXTENSION));
    
    switch ($extension) {
        case 'css':
            header('Content-Type: text/css');
            break;
        case 'js':
            header('Content-Type: application/javascript');
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
    
    // Enable caching for static assets
    header('Cache-Control: public, max-age=31536000');
    header('Expires: ' . gmdate('D, d M Y H:i:s \G\M\T', time() + 31536000));
    
    readfile($requested_file);
} else {
    // Serve the main React app for all other routes
    $index_file = $react_build_dir . '/index.html';
    
    if (file_exists($index_file)) {
        // Read and modify the index.html to work with subdirectory
        $html_content = file_get_contents($index_file);
        
        // Update asset paths to include the subdirectory
        $html_content = str_replace('href="/', 'href="/salem-dominion-ministries/', $html_content);
        $html_content = str_replace('src="/', 'src="/salem-dominion-ministries/', $html_content);
        $html_content = str_replace('from "/', 'from "/salem-dominion-ministries/', $html_content);
        
        echo $html_content;
    } else {
        http_response_code(404);
        echo '<h1>404 - Page Not Found</h1>';
        echo '<p>The React application has not been built yet.</p>';
        echo '<p>Please run: <code>npm run build</code> in the frontend directory.</p>';
    }
}
?>
