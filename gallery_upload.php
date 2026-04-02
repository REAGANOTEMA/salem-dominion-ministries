<?php
// Enhanced Gallery Upload Handler with Writings and Auto-Expiration
require_once 'config.php';
require_once 'db.php';

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'You must be logged in to upload content.']);
    exit;
}

// Handle different content types
$content_type = $_POST['content_type'] ?? 'image';
$title = trim($_POST['title'] ?? '');
$description = trim($_POST['description'] ?? '');
$category = $_POST['category'] ?? 'general';
$is_featured = isset($_POST['is_featured']) ? 1 : 0;
$auto_expire = isset($_POST['auto_expire']) ? 1 : 0;

// Writing-specific fields
$writing_content = trim($_POST['writing_content'] ?? '');
$writing_author = trim($_POST['writing_author'] ?? '');
$writing_category = $_POST['writing_category'] ?? null;

// Validate required fields
if (empty($title)) {
    echo json_encode(['success' => false, 'message' => 'Title is required.']);
    exit;
}

// Set expiration time if auto-expire is enabled
$expires_at = null;
if ($auto_expire) {
    $expires_at = date('Y-m-d H:i:s', strtotime('+24 hours'));
}

try {
    if ($content_type === 'writing') {
        // Handle writing-only content
        if (empty($writing_content)) {
            echo json_encode(['success' => false, 'message' => 'Writing content is required for writing posts.']);
            exit;
        }
        
        // Insert writing content
        $result = $db->insert(
            "INSERT INTO gallery (title, description, writing_content, content_type, file_url, file_type, category, is_featured, status, uploaded_by, writing_author, writing_category, expires_at, auto_expire) VALUES (?, ?, ?, 'writing', '', 'text', ?, ?, 'published', ?, ?, ?, ?, ?)",
            [$title, $description, $writing_content, $category, $is_featured, $_SESSION['user_id'], $writing_author, $writing_category, $expires_at, $auto_expire]
        );
        
        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Writing published successfully!', 'expires_at' => $expires_at]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to save writing.']);
        }
        
    } elseif ($content_type === 'image') {
        // Handle image upload
        if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
            echo json_encode(['success' => false, 'message' => 'Please select an image to upload.']);
            exit;
        }
        
        $file = $_FILES['image'];
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        
        $file_extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        
        if (!in_array($file['type'], $allowed_types) || !in_array($file_extension, $allowed_extensions)) {
            echo json_encode(['success' => false, 'message' => 'Invalid file type. Only JPEG, PNG, GIF, and WebP images are allowed.']);
            exit;
        }
        
        if ($file['size'] > 10 * 1024 * 1024) { // 10MB limit
            echo json_encode(['success' => false, 'message' => 'File size too large. Maximum size is 10MB.']);
            exit;
        }
        
        // Create upload directory
        $upload_dir = 'uploads/gallery/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }
        
        // Generate unique filename
        $filename = uniqid() . '_' . basename($file['name']);
        $filepath = $upload_dir . $filename;
        
        // Get image dimensions
        $image_info = getimagesize($file['tmp_name']);
        $dimensions = $image_info ? $image_info[0] . 'x' . $image_info[1] : null;
        
        if (move_uploaded_file($file['tmp_name'], $filepath)) {
            $result = $db->insert(
                "INSERT INTO gallery (title, description, file_url, file_type, file_size, dimensions, content_type, category, is_featured, status, uploaded_by, expires_at, auto_expire) VALUES (?, ?, ?, 'image', ?, ?, 'image', ?, ?, 'published', ?, ?, ?)",
                [$title, $description, $filepath, $file['size'], $dimensions, $category, $is_featured, $_SESSION['user_id'], $expires_at, $auto_expire]
            );
            
            if ($result) {
                echo json_encode(['success' => true, 'message' => 'Image uploaded successfully!', 'expires_at' => $expires_at]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to save image to database.']);
                unlink($filepath); // Clean up uploaded file
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to upload image file.']);
        }
        
    } elseif ($content_type === 'mixed') {
        // Handle mixed content (image + writing)
        if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
            echo json_encode(['success' => false, 'message' => 'Please select an image to upload.']);
            exit;
        }
        
        if (empty($writing_content)) {
            echo json_encode(['success' => false, 'message' => 'Writing content is required for mixed posts.']);
            exit;
        }
        
        // Validate image (same as above)
        $file = $_FILES['image'];
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        
        $file_extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        
        if (!in_array($file['type'], $allowed_types) || !in_array($file_extension, $allowed_extensions)) {
            echo json_encode(['success' => false, 'message' => 'Invalid file type. Only JPEG, PNG, GIF, and WebP images are allowed.']);
            exit;
        }
        
        if ($file['size'] > 10 * 1024 * 1024) {
            echo json_encode(['success' => false, 'message' => 'File size too large. Maximum size is 10MB.']);
            exit;
        }
        
        // Upload image
        $upload_dir = 'uploads/gallery/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }
        
        $filename = uniqid() . '_' . basename($file['name']);
        $filepath = $upload_dir . $filename;
        
        $image_info = getimagesize($file['tmp_name']);
        $dimensions = $image_info ? $image_info[0] . 'x' . $image_info[1] : null;
        
        if (move_uploaded_file($file['tmp_name'], $filepath)) {
            $result = $db->insert(
                "INSERT INTO gallery (title, description, writing_content, file_url, file_type, file_size, dimensions, content_type, category, is_featured, status, uploaded_by, writing_author, writing_category, expires_at, auto_expire) VALUES (?, ?, ?, ?, 'image', ?, ?, 'mixed', ?, ?, 'published', ?, ?, ?, ?, ?)",
                [$title, $description, $writing_content, $filepath, $file['size'], $dimensions, $category, $is_featured, $_SESSION['user_id'], $writing_author, $writing_category, $expires_at, $auto_expire]
            );
            
            if ($result) {
                echo json_encode(['success' => true, 'message' => 'Mixed content published successfully!', 'expires_at' => $expires_at]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to save content.']);
                unlink($filepath);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to upload image file.']);
        }
    }
    

} catch (Exception $e) {
    // Clean up uploaded file if database insert failed
    if (isset($uploadPath) && file_exists($uploadPath)) {
        unlink($uploadPath);
    }

    header('Content-Type: application/json');
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>
