<?php
// Test PHP functionality
header("Content-Type: application/json");

echo json_encode([
    'status' => 'OK',
    'message' => 'PHP is working',
    'timestamp' => date('c'),
    'php_version' => phpversion(),
    'server_info' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown'
]);
?>
