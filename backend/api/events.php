<?php
require_once '../config/database.php';
$db = new Database();

$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true);

switch ($method) {
    case 'GET':
        $id = $_GET['id'] ?? '';
        
        if ($id) {
            $result = $db->query("SELECT * FROM events WHERE id = ?", [$id]);
            
            if ($result['success'] && count($result['data']) > 0) {
                echo json_encode([
                    'success' => true,
                    'data' => $result['data'][0]
                ]);
            } else {
                http_response_code(404);
                echo json_encode([
                    'success' => false,
                    'message' => 'Event not found'
                ]);
            }
        } else {
            $result = $db->query("SELECT * FROM events ORDER BY event_date DESC");
            
            echo json_encode([
                'success' => true,
                'data' => $result['data']
            ]);
        }
        break;
        
    case 'POST':
        $title = $input['title'] ?? '';
        $description = $input['description'] ?? '';
        $event_date = $input['event_date'] ?? '';
        $location = $input['location'] ?? '';
        $image_url = $input['image_url'] ?? '';
        
        $result = $db->insert(
            "INSERT INTO events (title, description, event_date, location, image_url, created_at) VALUES (?, ?, ?, ?, ?, NOW())",
            [$title, $description, $event_date, $location, $image_url]
        );
        
        if ($result['success']) {
            echo json_encode([
                'success' => true,
                'message' => 'Event created successfully',
                'event_id' => $result['insert_id']
            ]);
        } else {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Failed to create event',
                'error' => $result['error']
            ]);
        }
        break;
        
    case 'PUT':
        $id = $_GET['id'] ?? '';
        $title = $input['title'] ?? '';
        $description = $input['description'] ?? '';
        $event_date = $input['event_date'] ?? '';
        $location = $input['location'] ?? '';
        $image_url = $input['image_url'] ?? '';
        
        $result = $db->update(
            "UPDATE events SET title = ?, description = ?, event_date = ?, location = ?, image_url = ?, updated_at = NOW() WHERE id = ?",
            [$title, $description, $event_date, $location, $image_url, $id]
        );
        
        if ($result['success']) {
            echo json_encode([
                'success' => true,
                'message' => 'Event updated successfully'
            ]);
        } else {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Failed to update event',
                'error' => $result['error']
            ]);
        }
        break;
        
    case 'DELETE':
        $id = $_GET['id'] ?? '';
        
        $result = $db->delete("DELETE FROM events WHERE id = ?", [$id]);
        
        if ($result['success']) {
            echo json_encode([
                'success' => true,
                'message' => 'Event deleted successfully'
            ]);
        } else {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Failed to delete event',
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
