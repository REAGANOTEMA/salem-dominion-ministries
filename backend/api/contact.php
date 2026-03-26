<?php
require_once '../config/database.php';
$db = new Database();

$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true);

switch ($method) {
    case 'GET':
        $id = $_GET['id'] ?? '';
        
        if ($id) {
            $result = $db->query("SELECT * FROM contact_messages WHERE id = ?", [$id]);
            
            if ($result['success'] && count($result['data']) > 0) {
                echo json_encode([
                    'success' => true,
                    'data' => $result['data'][0]
                ]);
            } else {
                http_response_code(404);
                echo json_encode([
                    'success' => false,
                    'message' => 'Contact message not found'
                ]);
            }
        } else {
            $result = $db->query("SELECT * FROM contact_messages ORDER BY created_at DESC");
            
            echo json_encode([
                'success' => true,
                'data' => $result['data']
            ]);
        }
        break;
        
    case 'POST':
        $name = $input['name'] ?? '';
        $email = $input['email'] ?? '';
        $subject = $input['subject'] ?? '';
        $message = $input['message'] ?? '';
        
        $result = $db->insert(
            "INSERT INTO contact_messages (name, email, subject, message, created_at) VALUES (?, ?, ?, ?, NOW())",
            [$name, $email, $subject, $message]
        );
        
        if ($result['success']) {
            echo json_encode([
                'success' => true,
                'message' => 'Contact message sent successfully',
                'message_id' => $result['insert_id']
            ]);
        } else {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Failed to send contact message',
                'error' => $result['error']
            ]);
        }
        break;
        
    case 'PUT':
        $id = $_GET['id'] ?? '';
        $status = $input['status'] ?? '';
        
        $result = $db->update(
            "UPDATE contact_messages SET status = ?, updated_at = NOW() WHERE id = ?",
            [$status, $id]
        );
        
        if ($result['success']) {
            echo json_encode([
                'success' => true,
                'message' => 'Contact message updated successfully'
            ]);
        } else {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Failed to update contact message',
                'error' => $result['error']
            ]);
        }
        break;
        
    case 'DELETE':
        $id = $_GET['id'] ?? '';
        
        $result = $db->delete("DELETE FROM contact_messages WHERE id = ?", [$id]);
        
        if ($result['success']) {
            echo json_encode([
                'success' => true,
                'message' => 'Contact message deleted successfully'
            ]);
        } else {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Failed to delete contact message',
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
