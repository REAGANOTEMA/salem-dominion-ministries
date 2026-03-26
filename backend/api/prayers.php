<?php
require_once '../config/database.php';
$db = new Database();

$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true);

switch ($method) {
    case 'GET':
        $id = $_GET['id'] ?? '';
        
        if ($id) {
            $result = $db->query("SELECT * FROM prayer_requests WHERE id = ?", [$id]);
            
            if ($result['success'] && count($result['data']) > 0) {
                echo json_encode([
                    'success' => true,
                    'data' => $result['data'][0]
                ]);
            } else {
                http_response_code(404);
                echo json_encode([
                    'success' => false,
                    'message' => 'Prayer request not found'
                ]);
            }
        } else {
            $result = $db->query("SELECT * FROM prayer_requests ORDER BY created_at DESC");
            
            echo json_encode([
                'success' => true,
                'data' => $result['data']
            ]);
        }
        break;
        
    case 'POST':
        $name = $input['name'] ?? '';
        $email = $input['email'] ?? '';
        $request = $input['request'] ?? '';
        $is_private = $input['is_private'] ?? 0;
        
        $result = $db->insert(
            "INSERT INTO prayer_requests (name, email, request, is_private, created_at) VALUES (?, ?, ?, ?, NOW())",
            [$name, $email, $request, $is_private]
        );
        
        if ($result['success']) {
            echo json_encode([
                'success' => true,
                'message' => 'Prayer request submitted successfully',
                'prayer_id' => $result['insert_id']
            ]);
        } else {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Failed to submit prayer request',
                'error' => $result['error']
            ]);
        }
        break;
        
    case 'PUT':
        $id = $_GET['id'] ?? '';
        $status = $input['status'] ?? '';
        
        $result = $db->update(
            "UPDATE prayer_requests SET status = ?, updated_at = NOW() WHERE id = ?",
            [$status, $id]
        );
        
        if ($result['success']) {
            echo json_encode([
                'success' => true,
                'message' => 'Prayer request updated successfully'
            ]);
        } else {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Failed to update prayer request',
                'error' => $result['error']
            ]);
        }
        break;
        
    case 'DELETE':
        $id = $_GET['id'] ?? '';
        
        $result = $db->delete("DELETE FROM prayer_requests WHERE id = ?", [$id]);
        
        if ($result['success']) {
            echo json_encode([
                'success' => true,
                'message' => 'Prayer request deleted successfully'
            ]);
        } else {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Failed to delete prayer request',
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
