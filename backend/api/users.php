<?php
require_once '../config/database.php';
$db = new Database();

$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true);

switch ($method) {
    case 'GET':
        $id = $_GET['id'] ?? '';
        
        if ($id) {
            $result = $db->query("SELECT id, name, email, role, created_at FROM users WHERE id = ?", [$id]);
            
            if ($result['success'] && count($result['data']) > 0) {
                echo json_encode([
                    'success' => true,
                    'data' => $result['data'][0]
                ]);
            } else {
                http_response_code(404);
                echo json_encode([
                    'success' => false,
                    'message' => 'User not found'
                ]);
            }
        } else {
            $result = $db->query("SELECT id, name, email, role, created_at FROM users ORDER BY created_at DESC");
            
            echo json_encode([
                'success' => true,
                'data' => $result['data']
            ]);
        }
        break;
        
    case 'PUT':
        $id = $_GET['id'] ?? '';
        $name = $input['name'] ?? '';
        $email = $input['email'] ?? '';
        $role = $input['role'] ?? '';
        
        $result = $db->update(
            "UPDATE users SET name = ?, email = ?, role = ?, updated_at = NOW() WHERE id = ?",
            [$name, $email, $role, $id]
        );
        
        if ($result['success']) {
            echo json_encode([
                'success' => true,
                'message' => 'User updated successfully'
            ]);
        } else {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Failed to update user',
                'error' => $result['error']
            ]);
        }
        break;
        
    case 'DELETE':
        $id = $_GET['id'] ?? '';
        
        $result = $db->delete("DELETE FROM users WHERE id = ?", [$id]);
        
        if ($result['success']) {
            echo json_encode([
                'success' => true,
                'message' => 'User deleted successfully'
            ]);
        } else {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Failed to delete user',
                'error' => $result['error']
            ]);
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
?>
