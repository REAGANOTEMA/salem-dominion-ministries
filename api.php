<?php
// Direct API endpoint - bypasses all routing
header("Content-Type: application/json");

// Secure CORS policy - restrict to specific domains
$allowed_origins = [
    'http://localhost',
    'https://salemdominionministries.com',
    'https://www.salemdominionministries.com'
];

$origin = $_SERVER['HTTP_ORIGIN'] ?? '';
if (in_array($origin, $allowed_origins)) {
    header("Access-Control-Allow-Origin: " . $origin);
}

header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("X-Content-Type-Options: nosniff");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Include the backend API
require_once __DIR__ . '/backend/api/index.php';