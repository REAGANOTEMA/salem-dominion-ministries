<?php
require_once __DIR__ . '/../config/database.php';
$db = new Database();

$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true);

switch ($method) {
    case 'GET':
        $id = $_GET['id'] ?? '';
        $status = $_GET['status'] ?? '';
        $limit = $_GET['limit'] ?? '';
        
        if ($id) {
            $result = $db->query("SELECT * FROM gallery WHERE id = ?", [$id]);
            
            if ($result['success'] && count($result['data']) > 0) {
                echo json_encode([
                    'success' => true,
                    'data' => $result['data'][0]
                ]);
            } else {
                http_response_code(404);
                echo json_encode([
                    'success' => false,
                    'message' => 'Gallery item not found'
                ]);
            }
        } else {
            $query = "SELECT * FROM gallery";
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
        $description = $input['description'] ?? '';
        $file_url = $input['file_url'] ?? '';
        $category = $input['category'] ?? '';
        
        $result = $db->insert(
            "INSERT INTO gallery (title, description, file_url, category, created_at) VALUES (?, ?, ?, ?, NOW())",
            [$title, $description, $file_url, $category]
        );
        
        if ($result['success']) {
            echo json_encode([
                'success' => true,
                'message' => 'Gallery item created successfully',
                'gallery_id' => $result['insert_id']
            ]);
        } else {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Failed to create gallery item',
                'error' => $result['error']
            ]);
        }
        break;
        
    case 'PUT':
        $id = $_GET['id'] ?? '';
        $title = $input['title'] ?? '';
        $description = $input['description'] ?? '';
        $file_url = $input['file_url'] ?? '';
        $category = $input['category'] ?? '';
        
        $result = $db->update(
            "UPDATE gallery SET title = ?, description = ?, file_url = ?, category = ?, updated_at = NOW() WHERE id = ?",
            [$title, $description, $file_url, $category, $id]
        );
        
        if ($result['success']) {
            echo json_encode([
                'success' => true,
                'message' => 'Gallery item updated successfully'
            ]);
        } else {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Failed to update gallery item',
                'error' => $result['error']
            ]);
        }
        break;
        
    case 'DELETE':
        $id = $_GET['id'] ?? '';
        
        $result = $db->delete("DELETE FROM gallery WHERE id = ?", [$id]);
        
        if ($result['success']) {
            echo json_encode([
                'success' => true,
                'message' => 'Gallery item deleted successfully'
            ]);
        } else {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Failed to delete gallery item',
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
