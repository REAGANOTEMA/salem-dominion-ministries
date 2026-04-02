<?php
// Admin Gallery Management
require_once 'config.php';
require_once 'db.php';

// Check if user is logged in and is admin/pastor
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login_perfect.php');
    exit;
}

$user = $db->selectOne("SELECT role FROM users WHERE id = ?", [$_SESSION['user_id']]);
if (!$user || !in_array($user['role'], ['admin', 'pastor'])) {
    header('Location: dashboard.php');
    exit;
}

// Handle gallery operations
$message = '';
$message_type = '';

// Handle status update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $image_id = intval($_POST['image_id']);
    $new_status = $_POST['status'];
    
    try {
        $result = $db->update("UPDATE gallery SET status = ? WHERE id = ?", [$new_status, $image_id]);
        if ($result > 0) {
            $message = 'Image status updated successfully!';
            $message_type = 'success';
        }
    } catch (Exception $e) {
        $message = 'Failed to update image status: ' . $e->getMessage();
        $message_type = 'error';
    }
}

// Handle featured toggle
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['toggle_featured'])) {
    $image_id = intval($_POST['image_id']);
    $is_featured = isset($_POST['is_featured']) ? 1 : 0;
    
    try {
        $result = $db->update("UPDATE gallery SET is_featured = ? WHERE id = ?", [$is_featured, $image_id]);
        if ($result > 0) {
            $message = 'Image featured status updated successfully!';
            $message_type = 'success';
        }
    } catch (Exception $e) {
        $message = 'Failed to update featured status: ' . $e->getMessage();
        $message_type = 'error';
    }
}

// Get all gallery images with pagination
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$per_page = 20;
$offset = ($page - 1) * $per_page;

$status_filter = isset($_GET['status']) ? $_GET['status'] : 'all';

// Build query
$where_clause = "1=1";
$params = [];

if ($status_filter !== 'all') {
    $where_clause = "status = ?";
    $params[] = $status_filter;
}

// Get total count
$total_result = $db->select("SELECT COUNT(*) as total FROM gallery WHERE $where_clause", $params);
$total_images = $total_result[0]['total'];
$total_pages = ceil($total_images / $per_page);

// Get images
$images = $db->select("SELECT * FROM gallery WHERE $where_clause ORDER BY created_at DESC LIMIT ? OFFSET ?", 
                      array_merge($params, [$per_page, $offset]));

// Clean any buffered output
ob_end_clean();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];

        if ($action === 'upload_image') {
            $title = trim($_POST['title']);
            $description = trim($_POST['description']);
            $category = $_POST['category'];

            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
                $file_type = $_FILES['image']['type'];

                if (in_array($file_type, $allowed_types)) {
                    $file_name = time() . '_' . basename($_FILES['image']['name']);
                    $file_path = $gallery_dir . '/' . $file_name;

                    if (move_uploaded_file($_FILES['image']['tmp_name'], $file_path)) {
                        $stmt = $db->prepare("INSERT INTO gallery (title, description, image_path, category, uploaded_by, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
                        $stmt->bind_param('ssssi', $title, $description, $file_path, $category, $_SESSION['user_id']);
                        if ($stmt->execute()) {
                            $message = 'Image uploaded successfully.';
                            $message_type = 'success';
                        } else {
                            $message = 'Error saving image to database.';
                            $message_type = 'error';
                            // Delete uploaded file if database insert failed
                            unlink($file_path);
                        }
                    } else {
                        $message = 'Error uploading image file.';
                        $message_type = 'error';
                    }
                } else {
                    $message = 'Invalid file type. Only JPEG, PNG, GIF, and WebP images are allowed.';
                    $message_type = 'error';
                }
            } else {
                $message = 'Please select an image to upload.';
                $message_type = 'error';
            }
        } elseif ($action === 'update_image') {
            $id = intval($_POST['image_id']);
            $title = trim($_POST['title']);
            $description = trim($_POST['description']);
            $category = $_POST['category'];

            $stmt = $db->prepare("UPDATE gallery SET title = ?, description = ?, category = ? WHERE id = ?");
            $stmt->bind_param('sssi', $title, $description, $category, $id);
            if ($stmt->execute()) {
                $message = 'Image updated successfully.';
                $message_type = 'success';
            } else {
                $message = 'Error updating image.';
                $message_type = 'error';
            }
        } elseif ($action === 'delete_image') {
            $id = intval($_POST['image_id']);

            // Get file path before deleting
            $stmt = $db->prepare("SELECT image_path FROM gallery WHERE id = ?");
            $stmt->bind_param('i', $id);
            $stmt->execute();
            $result = $stmt->get_result();
            $image = $result->fetch_assoc();

            // Delete from database
            $stmt = $db->prepare("DELETE FROM gallery WHERE id = ?");
            $stmt->bind_param('i', $id);
            if ($stmt->execute()) {
                // Delete file if it exists
                if ($image['image_path'] && file_exists($image['image_path'])) {
                    unlink($image['image_path']);
                }
                $message = 'Image deleted successfully.';
                $message_type = 'success';
            } else {
                $message = 'Error deleting image.';
                $message_type = 'error';
            }
        }
    }
}

// Get all gallery images
$gallery_result = $db->query("SELECT g.*, u.username FROM gallery g LEFT JOIN users u ON g.uploaded_by = u.id ORDER BY g.created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gallery Management - Salem Dominion Ministries</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .sidebar {
            min-height: calc(100vh - 76px);
            background: #343a40;
        }
        .sidebar .nav-link {
            color: rgba(255,255,255,.75);
            padding: 12px 20px;
            border-radius: 5px;
            margin-bottom: 5px;
        }
        .sidebar .nav-link:hover {
            color: white;
            background: rgba(255,255,255,.1);
        }
        .sidebar .nav-link.active {
            color: white;
            background: #0d6efd;
        }
        .gallery-item {
            position: relative;
            overflow: hidden;
            border-radius: 10px;
            transition: transform 0.3s ease;
        }
        .gallery-item:hover {
            transform: scale(1.02);
        }
        .gallery-item img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }
        .gallery-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.7);
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        .gallery-item:hover .gallery-overlay {
            opacity: 1;
        }
        .file-upload-area {
            border: 2px dashed #dee2e6;
            border-radius: 10px;
            padding: 40px;
            text-align: center;
            transition: border-color 0.3s ease;
            background: #f8f9fa;
        }
        .file-upload-area:hover {
            border-color: #0d6efd;
        }
        .file-upload-area.dragover {
            border-color: #0d6efd;
            background: #e3f2fd;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <i class="fas fa-church"></i> Salem Dominion Ministries
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="about.php">About</a></li>
                    <li class="nav-item"><a class="nav-link" href="ministries.php">Ministries</a></li>
                    <li class="nav-item"><a class="nav-link" href="events.php">Events</a></li>
                    <li class="nav-item"><a class="nav-link" href="sermons.php">Sermons</a></li>
                    <li class="nav-item"><a class="nav-link" href="news.php">News</a></li>
                    <li class="nav-item"><a class="nav-link" href="gallery.php">Gallery</a></li>
                    <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
                </ul>
                <ul class="navbar-nav">
                    <li class="nav-item"><a class="nav-link" href="dashboard.php">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 px-0">
                <div class="sidebar p-3">
                    <h6 class="text-white-50 mb-3">ADMIN PANEL</h6>
                    <nav class="nav flex-column">
                        <a class="nav-link" href="admin_dashboard.php">
                            <i class="fas fa-tachometer-alt"></i> Dashboard
                        </a>
                        <a class="nav-link" href="admin_users.php">
                            <i class="fas fa-users"></i> Users
                        </a>
                        <a class="nav-link" href="admin_news.php">
                            <i class="fas fa-newspaper"></i> News
                        </a>
                        <a class="nav-link" href="admin_events.php">
                            <i class="fas fa-calendar"></i> Events
                        </a>
                        <a class="nav-link" href="admin_sermons.php">
                            <i class="fas fa-microphone"></i> Sermons
                        </a>
                        <a class="nav-link" href="admin_ministries.php">
                            <i class="fas fa-praying-hands"></i> Ministries
                        </a>
                        <a class="nav-link active" href="admin_gallery.php">
                            <i class="fas fa-images"></i> Gallery
                        </a>
                        <a class="nav-link" href="admin_donations.php">
                            <i class="fas fa-hand-holding-heart"></i> Donations
                        </a>
                        <a class="nav-link" href="admin_prayer_requests.php">
                            <i class="fas fa-pray"></i> Prayer Requests
                        </a>
                    </nav>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 px-4 py-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2><i class="fas fa-images text-primary"></i> Gallery Management</h2>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#uploadImageModal">
                        <i class="fas fa-upload"></i> Upload Image
                    </button>
                </div>

                <?php if ($message): ?>
                    <div class="alert alert-<?php echo $message_type === 'success' ? 'success' : 'danger'; ?> alert-dismissible fade show" role="alert">
                        <?php echo htmlspecialchars($message); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <!-- Gallery Grid -->
                <div class="row g-3">
                    <?php if ($gallery_result->num_rows > 0): ?>
                        <?php while ($image = $gallery_result->fetch_assoc()): ?>
                            <div class="col-md-6 col-lg-4 col-xl-3">
                                <div class="gallery-item">
                                    <img src="<?php echo htmlspecialchars($image['image_path']); ?>" alt="<?php echo htmlspecialchars($image['title']); ?>" class="img-fluid">
                                    <div class="gallery-overlay">
                                        <div class="text-center">
                                            <button class="btn btn-light btn-sm me-2" onclick="editImage(<?php echo $image['id']; ?>, '<?php echo htmlspecialchars(addslashes($image['title'])); ?>', '<?php echo htmlspecialchars(addslashes($image['description'])); ?>', '<?php echo $image['category']; ?>')">
                                                <i class="fas fa-edit"></i> Edit
                                            </button>
                                            <form method="post" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this image?')">
                                                <input type="hidden" name="action" value="delete_image">
                                                <input type="hidden" name="image_id" value="<?php echo $image['id']; ?>">
                                                <button type="submit" class="btn btn-danger btn-sm">
                                                    <i class="fas fa-trash"></i> Delete
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-2">
                                    <h6 class="mb-1"><?php echo htmlspecialchars($image['title']); ?></h6>
                                    <div class="d-flex justify-content-between align-items-center text-muted small">
                                        <span><i class="fas fa-tag"></i> <?php echo ucfirst($image['category']); ?></span>
                                        <span><i class="fas fa-user"></i> <?php echo htmlspecialchars($image['username'] ?: 'Unknown'); ?></span>
                                    </div>
                                    <small class="text-muted"><?php echo date('M j, Y', strtotime($image['created_at'])); ?></small>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <div class="col-12">
                            <div class="text-center py-5">
                                <i class="fas fa-images fa-3x text-muted mb-3"></i>
                                <h4 class="text-muted">No Images Yet</h4>
                                <p class="text-muted">Upload your first image to get started.</p>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Upload Image Modal -->
    <div class="modal fade" id="uploadImageModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-upload"></i> Upload Image</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="post" enctype="multipart/form-data">
                    <div class="modal-body">
                        <input type="hidden" name="action" value="upload_image">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="title" class="form-label">Image Title</label>
                                <input type="text" class="form-control" id="title" name="title">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="category" class="form-label">Category</label>
                                <select class="form-select" id="category" name="category">
                                    <option value="general">General</option>
                                    <option value="worship">Worship</option>
                                    <option value="events">Events</option>
                                    <option value="ministry">Ministry</option>
                                    <option value="outreach">Outreach</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="2"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Image File *</label>
                            <div class="file-upload-area" id="fileUploadArea">
                                <i class="fas fa-cloud-upload-alt fa-3x text-muted mb-3"></i>
                                <p class="mb-1">Drag & drop an image here</p>
                                <p class="small text-muted">or</p>
                                <input type="file" class="form-control" id="image" name="image" accept="image/*" required style="display: none;">
                                <button type="button" class="btn btn-outline-primary" onclick="document.getElementById('image').click()">Choose File</button>
                                <div id="fileName" class="mt-2 small text-success"></div>
                            </div>
                            <small class="form-text text-muted">Supported formats: JPEG, PNG, GIF, WebP. Maximum file size: 10MB.</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-upload"></i> Upload Image</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Image Modal -->
    <div class="modal fade" id="editImageModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-edit"></i> Edit Image</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="post">
                    <div class="modal-body">
                        <input type="hidden" name="action" value="update_image">
                        <input type="hidden" name="image_id" id="edit_image_id">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="edit_title" class="form-label">Image Title</label>
                                <input type="text" class="form-control" id="edit_title" name="title">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="edit_category" class="form-label">Category</label>
                                <select class="form-select" id="edit_category" name="category">
                                    <option value="general">General</option>
                                    <option value="worship">Worship</option>
                                    <option value="events">Events</option>
                                    <option value="ministry">Ministry</option>
                                    <option value="outreach">Outreach</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="edit_description" class="form-label">Description</label>
                            <textarea class="form-control" id="edit_description" name="description" rows="2"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Update Image</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function editImage(id, title, description, category) {
            document.getElementById('edit_image_id').value = id;
            document.getElementById('edit_title').value = title;
            document.getElementById('edit_description').value = description;
            document.getElementById('edit_category').value = category;
            
            new bootstrap.Modal(document.getElementById('editImageModal')).show();
        }

        // File upload drag and drop functionality
        const fileUploadArea = document.getElementById('fileUploadArea');
        const imageInput = document.getElementById('image');
        const fileNameDisplay = document.getElementById('fileName');

        fileUploadArea.addEventListener('dragover', (e) => {
            e.preventDefault();
            fileUploadArea.classList.add('dragover');
        });

        fileUploadArea.addEventListener('dragleave', () => {
            fileUploadArea.classList.remove('dragover');
        });

        fileUploadArea.addEventListener('drop', (e) => {
            e.preventDefault();
            fileUploadArea.classList.remove('dragover');
            const files = e.dataTransfer.files;
            if (files.length > 0) {
                imageInput.files = files;
                fileNameDisplay.textContent = files[0].name;
            }
        });

        imageInput.addEventListener('change', () => {
            if (imageInput.files.length > 0) {
                fileNameDisplay.textContent = imageInput.files[0].name;
            } else {
                fileNameDisplay.textContent = '';
            }
        });
    </script>
</body>
</html>