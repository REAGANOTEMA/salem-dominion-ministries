<?php
require_once '../config/database.php';

// Get the token from Authorization header
$headers = getallheaders();
$authHeader = $headers['Authorization'] ?? '';

if (!$authHeader || !preg_match('/Bearer\s+(.*)$/i', $authHeader, $matches)) {
    http_response_code(401);
    echo json_encode([
        'success' => false,
        'message' => 'No token provided'
    ]);
    exit;
}

$token = $matches[1];

// Simple token validation (in production, use proper JWT verification)
try {
    $parts = explode('.', $token);
    if (count($parts) !== 3) {
        throw new Exception('Invalid token format');
    }
    
    $payload = json_decode(base64_decode(str_replace(['-', '_'], ['+', '/'], $parts[1])), true);
    
    if (!$payload || !isset($payload['user_id']) || !isset($payload['exp'])) {
        throw new Exception('Invalid token payload');
    }
    
    if ($payload['exp'] < time()) {
        throw new Exception('Token expired');
    }
    
    // Get user data
    $db = new Database();
    $result = $db->query("SELECT id, first_name, last_name, email, phone, role, profile_image FROM users WHERE id = ?", [$payload['user_id']]);
    
    if ($result['success'] && count($result['data']) > 0) {
        $user = $result['data'][0];
        echo json_encode([
            'success' => true,
            'user' => $user
        ]);
    } else {
        http_response_code(401);
        echo json_encode([
            'success' => false,
            'message' => 'User not found'
        ]);
    }
    
} catch (Exception $e) {
    http_response_code(401);
    echo json_encode([
        'success' => false,
        'message' => 'Invalid token: ' . $e->getMessage()
    ]);
}
?>
