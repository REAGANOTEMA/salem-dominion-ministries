<?php
require_once __DIR__ . '/../config/database.php';
$db = new Database();

$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true);

switch ($method) {
    case 'POST':
        $action = $input['action'] ?? $_GET['action'] ?? '';
        
        switch ($action) {
            case 'login':
                $email = $input['email'] ?? '';
                $password = $input['password'] ?? '';
                
                $result = $db->query("SELECT * FROM users WHERE email = ?", [$email]);
                
                if ($result['success'] && count($result['data']) > 0) {
                    $user = $result['data'][0];
                    
                    if (password_verify($password, $user['password_hash'])) {
                        unset($user['password_hash']);
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
                $first_name = $input['first_name'] ?? '';
                $last_name = $input['last_name'] ?? '';
                $email = $input['email'] ?? '';
                $phone = $input['phone'] ?? '';
                $password = $input['password'] ?? '';
                $role = $input['role'] ?? 'member';
                
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
                    "INSERT INTO users (first_name, last_name, email, phone, password_hash, role, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())",
                    [$first_name, $last_name, $email, $phone, $hashedPassword, $role]
                );
                
                if ($result['success']) {
                    echo json_encode([
                        'success' => true,
                        'message' => 'Account created successfully',
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
                
            case 'upload_profile':
                // Handle profile image upload
                if (!isset($_FILES['profile_image'])) {
                    http_response_code(400);
                    echo json_encode([
                        'success' => false,
                        'message' => 'No image file provided'
                    ]);
                    break;
                }
                
                $userId = $input['user_id'] ?? '';
                $file = $_FILES['profile_image'];
                
                // Validate file
                $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
                $maxSize = 5 * 1024 * 1024; // 5MB
                
                if (!in_array($file['type'], $allowedTypes)) {
                    http_response_code(400);
                    echo json_encode([
                        'success' => false,
                        'message' => 'Invalid file type. Only JPEG, PNG, and GIF are allowed.'
                    ]);
                    break;
                }
                
                if ($file['size'] > $maxSize) {
                    http_response_code(400);
                    echo json_encode([
                        'success' => false,
                        'message' => 'File too large. Maximum size is 5MB.'
                    ]);
                    break;
                }
                
                // Create upload directory if it doesn't exist
                $uploadDir = '../uploads/profiles/';
                if (!file_exists($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                
                // Generate unique filename
                $filename = uniqid() . '_' . basename($file['name']);
                $filepath = $uploadDir . $filename;
                
                // Move file
                if (move_uploaded_file($file['tmp_name'], $filepath)) {
                    // Update user profile
                    $result = $db->update(
                        "UPDATE users SET profile_image = ?, updated_at = NOW() WHERE id = ?",
                        [$filename, $userId]
                    );
                    
                    if ($result['success']) {
                        echo json_encode([
                            'success' => true,
                            'message' => 'Profile image uploaded successfully',
                            'filename' => $filename
                        ]);
                    } else {
                        echo json_encode([
                            'success' => false,
                            'message' => 'Failed to update profile',
                            'error' => $result['error']
                        ]);
                    }
                } else {
                    http_response_code(500);
                    echo json_encode([
                        'success' => false,
                        'message' => 'Failed to upload file'
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
