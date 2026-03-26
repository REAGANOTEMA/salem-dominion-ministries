<?php
require_once '../config/database.php';
$db = new Database();

$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true);

switch ($method) {
    case 'POST':
        $action = $_GET['action'] ?? '';
        
        switch ($action) {
            case 'login':
                $email = $input['email'] ?? '';
                $password = $input['password'] ?? '';
                
                $result = $db->query("SELECT * FROM users WHERE email = ?", [$email]);
                
                if ($result['success'] && count($result['data']) > 0) {
                    $user = $result['data'][0];
                    
                    if (password_verify($password, $user['password'])) {
                        unset($user['password']);
                        $token = generateJWT($user);
                        
                        echo json_encode([
                            'success' => true,
                            'message' => 'Login successful',
                            'token' => $token,
                            'user' => $user
                        ]);
                    } else {
                        http_response_code(401);
                        echo json_encode([
                            'success' => false,
                            'message' => 'Invalid credentials'
                        ]);
                    }
                } else {
                    http_response_code(401);
                    echo json_encode([
                        'success' => false,
                        'message' => 'Invalid credentials'
                    ]);
                }
                break;
                
            case 'register':
                $name = $input['name'] ?? '';
                $email = $input['email'] ?? '';
                $password = $input['password'] ?? '';
                $role = $input['role'] ?? 'user';
                
                // Check if user exists
                $existing = $db->query("SELECT id FROM users WHERE email = ?", [$email]);
                
                if ($existing['success'] && count($existing['data']) > 0) {
                    http_response_code(400);
                    echo json_encode([
                        'success' => false,
                        'message' => 'User already exists'
                    ]);
                    break;
                }
                
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                $result = $db->insert(
                    "INSERT INTO users (name, email, password, role, created_at) VALUES (?, ?, ?, ?, NOW())",
                    [$name, $email, $hashedPassword, $role]
                );
                
                if ($result['success']) {
                    echo json_encode([
                        'success' => true,
                        'message' => 'Registration successful',
                        'user_id' => $result['insert_id']
                    ]);
                } else {
                    http_response_code(500);
                    echo json_encode([
                        'success' => false,
                        'message' => 'Registration failed',
                        'error' => $result['error']
                    ]);
                }
                break;
                
            default:
                http_response_code(404);
                echo json_encode([
                    'success' => false,
                    'message' => 'Action not found'
                ]);
                break;
        }
        break;
        
    default:
        http_response_code(405);
        echo json_encode([
            'success' => false,
            'message' => 'Method not allowed'
        ]);
        break;
}

function generateJWT($user) {
    $header = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);
    $payload = json_encode([
        'user_id' => $user['id'],
        'email' => $user['email'],
        'role' => $user['role'],
        'exp' => time() + (7 * 24 * 60 * 60) // 7 days
    ]);
    
    $base64UrlHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));
    $base64UrlPayload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($payload));
    
    $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, 'your_super_secret_jwt_key_here', true);
    $base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));
    
    return $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;
}
?>
