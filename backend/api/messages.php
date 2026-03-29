<?php
require_once __DIR__ . '/../config/database.php';
$db = new Database();

$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true);

switch ($method) {
    case 'GET':
        $senderId = $_GET['sender_id'] ?? '';
        $receiverId = $_GET['receiver_id'] ?? '';
        
        if ($senderId) {
            $result = $db->query("SELECT m.*, u.first_name, u.last_name FROM messages m JOIN users u ON m.receiver_id = u.id WHERE m.sender_id = ? ORDER BY m.created_at DESC", [$senderId]);
        } elseif ($receiverId) {
            $result = $db->query("SELECT m.*, u.first_name, u.last_name FROM messages m JOIN users u ON m.sender_id = u.id WHERE m.receiver_id = ? ORDER BY m.created_at DESC", [$receiverId]);
        } else {
            $result = $db->query("SELECT m.*, u.first_name, u.last_name FROM messages m JOIN users u ON m.sender_id = u.id ORDER BY m.created_at DESC");
        }
        
        echo json_encode([
            'success' => true,
            'data' => $result['data']
        ]);
        break;
        
    case 'POST':
        $senderId = $input['senderId'] ?? '';
        $receiverId = $input['receiverId'] ?? '';
        $subject = $input['subject'] ?? '';
        $message = $input['message'] ?? '';
        
        $result = $db->insert(
            "INSERT INTO messages (sender_id, receiver_id, subject, message, created_at) VALUES (?, ?, ?, ?, NOW())",
            [$senderId, $receiverId, $subject, $message]
        );
        
        if ($result['success']) {
            // Create notification for receiver
            $db->insert(
                "INSERT INTO notifications (user_id, title, message, type, related_id, related_type, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())",
                [$receiverId, 'New Message', $subject, 'info', $result['insert_id'], 'message']
            );
            
            echo json_encode([
                'success' => true,
                'message' => 'Message sent successfully',
                'message_id' => $result['insert_id']
            ]);
        } else {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Failed to send message',
                'error' => $result['error']
            ]);
        }
        break;
        
    case 'PUT':
        $id = $_GET['id'] ?? '';
        $action = $_GET['action'] ?? '';
        
        if ($action === 'mark_read') {
            $result = $db->update("UPDATE messages SET is_read = TRUE WHERE id = ?", [$id]);
        } elseif ($action === 'delete') {
            $result = $db->update("UPDATE messages SET is_deleted_by_sender = TRUE WHERE id = ? AND sender_id = ?", [$id, $input['senderId']]);
        }
        
        if ($result['success']) {
            echo json_encode([
                'success' => true,
                'message' => 'Message updated successfully'
            ]);
        } else {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Failed to update message',
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
