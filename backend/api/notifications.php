<?php
require_once '../config/database.php';
$db = new Database();

$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true);

switch ($method) {
    case 'GET':
        $userId = $_GET['user_id'] ?? '';
        $isRead = $_GET['is_read'] ?? '';
        
        $sql = "SELECT * FROM notifications";
        $params = [];
        
        if ($userId) {
            $sql .= " WHERE user_id = ?";
            $params[] = $userId;
        }
        
        if ($isRead !== '') {
            $sql .= ($userId ? " AND" : " WHERE") . " is_read = ?";
            $params[] = $isRead;
        }
        
        $sql .= " ORDER BY created_at DESC";
        
        $result = $db->query($sql, $params);
        
        echo json_encode([
            'success' => true,
            'data' => $result['data']
        ]);
        break;
        
    case 'POST':
        $userId = $input['user_id'] ?? '';
        $title = $input['title'] ?? '';
        $message = $input['message'] ?? '';
        $type = $input['type'] ?? 'info';
        $relatedId = $input['related_id'] ?? null;
        $relatedType = $input['related_type'] ?? null;
        
        $result = $db->insert(
            "INSERT INTO notifications (user_id, title, message, type, related_id, related_type, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())",
            [$userId, $title, $message, $type, $relatedId, $relatedType]
        );
        
        if ($result['success']) {
            echo json_encode([
                'success' => true,
                'message' => 'Notification created successfully',
                'notification_id' => $result['insert_id']
            ]);
        } else {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Failed to create notification',
                'error' => $result['error']
            ]);
        }
        break;
        
    case 'PUT':
        $id = $_GET['id'] ?? '';
        $action = $_GET['action'] ?? '';
        
        if ($action === 'mark_read') {
            $result = $db->update("UPDATE notifications SET is_read = TRUE WHERE id = ?", [$id]);
        } elseif ($action === 'mark_all_read') {
            $userId = $input['user_id'] ?? '';
            $result = $db->update("UPDATE notifications SET is_read = TRUE WHERE user_id = ?", [$userId]);
        }
        
        if ($result['success']) {
            echo json_encode([
                'success' => true,
                'message' => 'Notifications updated successfully'
            ]);
        } else {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Failed to update notifications',
                'error' => $result['error']
            ]);
        }
        break;
        
    case 'DELETE':
        $id = $_GET['id'] ?? '';
        
        $result = $db->delete("DELETE FROM notifications WHERE id = ?", [$id]);
        
        if ($result['success']) {
            echo json_encode([
                'success' => true,
                'message' => 'Notification deleted successfully'
            ]);
        } else {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Failed to delete notification',
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
