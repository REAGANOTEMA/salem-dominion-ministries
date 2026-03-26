<?php
require_once '../config/database.php';
$db = new Database();

$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true);

switch ($method) {
    case 'GET':
        $id = $_GET['id'] ?? '';
        $status = $_GET['status'] ?? '';
        $limit = $_GET['limit'] ?? '';
        $breaking = $_GET['breaking'] ?? '';
        
        // Check if this is a breaking news request via URL path
        if ($id === 'breaking' || $breaking) {
            $query = "SELECT * FROM news WHERE status = 'published' AND is_breaking = 1 ORDER BY created_at DESC LIMIT 1";
            $result = $db->query($query);
            
            echo json_encode([
                'success' => true,
                'data' => $result['data']
            ]);
            break;
        }
        
        if ($id) {
            $result = $db->query("SELECT * FROM news WHERE id = ?", [$id]);
            
            if ($result['success'] && count($result['data']) > 0) {
                echo json_encode([
                    'success' => true,
                    'data' => $result['data'][0]
                ]);
            } else {
                http_response_code(404);
                echo json_encode([
                    'success' => false,
                    'message' => 'News article not found'
                ]);
            }
        } else {
            $query = "SELECT * FROM news";
            $params = [];
            
            if ($status) {
                $query .= " WHERE status = ?";
                $params[] = $status;
            }
            
            $query .= " ORDER BY created_at DESC";
            
            if ($limit && is_numeric($limit)) {
                $query .= " LIMIT ?";
                $params[] = (int)$limit;
            }
            
            $result = $db->query($query, $params);
            
            echo json_encode([
                'success' => true,
                'data' => $result['data']
            ]);
        }
        break;
        
    case 'POST':
        $title = $input['title'] ?? '';
        $content = $input['content'] ?? '';
        $excerpt = $input['excerpt'] ?? '';
        $author = $input['author'] ?? '';
        $image_url = $input['image_url'] ?? '';
        $status = $input['status'] ?? 'draft';
        
        $result = $db->insert(
            "INSERT INTO news (title, content, excerpt, author, image_url, status, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())",
            [$title, $content, $excerpt, $author, $image_url, $status]
        );
        
        if ($result['success']) {
            echo json_encode([
                'success' => true,
                'message' => 'News article created successfully',
                'news_id' => $result['insert_id']
            ]);
        } else {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Failed to create news article',
                'error' => $result['error']
            ]);
        }
        break;
        
    case 'PUT':
        $id = $_GET['id'] ?? '';
        $title = $input['title'] ?? '';
        $content = $input['content'] ?? '';
        $excerpt = $input['excerpt'] ?? '';
        $author = $input['author'] ?? '';
        $image_url = $input['image_url'] ?? '';
        $status = $input['status'] ?? '';
        
        $result = $db->update(
            "UPDATE news SET title = ?, content = ?, excerpt = ?, author = ?, image_url = ?, status = ?, updated_at = NOW() WHERE id = ?",
            [$title, $content, $excerpt, $author, $image_url, $status, $id]
        );
        
        if ($result['success']) {
            echo json_encode([
                'success' => true,
                'message' => 'News article updated successfully'
            ]);
        } else {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Failed to update news article',
                'error' => $result['error']
            ]);
        }
        break;
        
    case 'DELETE':
        $id = $_GET['id'] ?? '';
        
        $result = $db->delete("DELETE FROM news WHERE id = ?", [$id]);
        
        if ($result['success']) {
            echo json_encode([
                'success' => true,
                'message' => 'News article deleted successfully'
            ]);
        } else {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Failed to delete news article',
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
