<?php
require_once '../config/database.php';
$db = new Database();

$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true);

switch ($method) {
    case 'GET':
        $id = $_GET['id'] ?? '';
        
        if ($id) {
            $result = $db->query("SELECT * FROM sermons WHERE id = ?", [$id]);
            
            if ($result['success'] && count($result['data']) > 0) {
                echo json_encode([
                    'success' => true,
                    'data' => $result['data'][0]
                ]);
            } else {
                http_response_code(404);
                echo json_encode([
                    'success' => false,
                    'message' => 'Sermon not found'
                ]);
            }
        } else {
            $result = $db->query("SELECT * FROM sermons ORDER BY sermon_date DESC");
            
            echo json_encode([
                'success' => true,
                'data' => $result['data']
            ]);
        }
        break;
        
    case 'POST':
        $title = $input['title'] ?? '';
        $description = $input['description'] ?? '';
        $sermon_date = $input['sermon_date'] ?? '';
        $speaker = $input['speaker'] ?? '';
        $video_url = $input['video_url'] ?? '';
        $audio_url = $input['audio_url'] ?? '';
        $image_url = $input['image_url'] ?? '';
        
        $result = $db->insert(
            "INSERT INTO sermons (title, description, sermon_date, speaker, video_url, audio_url, image_url, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())",
            [$title, $description, $sermon_date, $speaker, $video_url, $audio_url, $image_url]
        );
        
        if ($result['success']) {
            echo json_encode([
                'success' => true,
                'message' => 'Sermon created successfully',
                'sermon_id' => $result['insert_id']
            ]);
        } else {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Failed to create sermon',
                'error' => $result['error']
            ]);
        }
        break;
        
    case 'PUT':
        $id = $_GET['id'] ?? '';
        $title = $input['title'] ?? '';
        $description = $input['description'] ?? '';
        $sermon_date = $input['sermon_date'] ?? '';
        $speaker = $input['speaker'] ?? '';
        $video_url = $input['video_url'] ?? '';
        $audio_url = $input['audio_url'] ?? '';
        $image_url = $input['image_url'] ?? '';
        
        $result = $db->update(
            "UPDATE sermons SET title = ?, description = ?, sermon_date = ?, speaker = ?, video_url = ?, audio_url = ?, image_url = ?, updated_at = NOW() WHERE id = ?",
            [$title, $description, $sermon_date, $speaker, $video_url, $audio_url, $image_url, $id]
        );
        
        if ($result['success']) {
            echo json_encode([
                'success' => true,
                'message' => 'Sermon updated successfully'
            ]);
        } else {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Failed to update sermon',
                'error' => $result['error']
            ]);
        }
        break;
        
    case 'DELETE':
        $id = $_GET['id'] ?? '';
        
        $result = $db->delete("DELETE FROM sermons WHERE id = ?", [$id]);
        
        if ($result['success']) {
            echo json_encode([
                'success' => true,
                'message' => 'Sermon deleted successfully'
            ]);
        } else {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Failed to delete sermon',
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
