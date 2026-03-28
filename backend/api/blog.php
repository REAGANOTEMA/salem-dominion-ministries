<?php
require_once '../config/database.php';
$db = new Database();

$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true);

switch ($method) {
    case 'GET':
        $id = $_GET['id'] ?? '';
        
        if ($id) {
            $result = $db->query("SELECT * FROM blog_posts WHERE id = ?", [$id]);
            
            if ($result['success'] && count($result['data']) > 0) {
                echo json_encode([
                    'success' => true,
                    'data' => $result['data'][0]
                ]);
            } else {
                http_response_code(404);
                echo json_encode([
                    'success' => false,
                    'message' => 'Blog post not found'
                ]);
            }
        } else {
            $result = $db->query("SELECT * FROM blog_posts ORDER BY created_at DESC");
            
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
            "INSERT INTO blog_posts (title, content, excerpt, author, image_url, status, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())",
            [$title, $content, $excerpt, $author, $image_url, $status]
        );
        
        if ($result['success']) {
            echo json_encode([
                'success' => true,
                'message' => 'Blog post created successfully',
                'post_id' => $result['insert_id']
            ]);
        } else {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Failed to create blog post',
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
            "UPDATE blog_posts SET title = ?, content = ?, excerpt = ?, author = ?, image_url = ?, status = ?, updated_at = NOW() WHERE id = ?",
            [$title, $content, $excerpt, $author, $image_url, $status, $id]
        );
        
        if ($result['success']) {
            echo json_encode([
                'success' => true,
                'message' => 'Blog post updated successfully'
            ]);
        } else {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Failed to update blog post',
                'error' => $result['error']
            ]);
        }
        break;
        
    case 'DELETE':
        $id = $_GET['id'] ?? '';
        
        $result = $db->delete("DELETE FROM blog_posts WHERE id = ?", [$id]);
        
        if ($result['success']) {
            echo json_encode([
                'success' => true,
                'message' => 'Blog post deleted successfully'
            ]);
        } else {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Failed to delete blog post',
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
