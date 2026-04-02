&lt;?php
session_start();
require_once 'db.php';

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

// Create uploads directory if it doesn't exist
$gallery_dir = 'uploads/gallery';
if (!is_dir($gallery_dir)) {
    mkdir($gallery_dir, 0755, true);
}

// Handle form submissions
$message = '';
$message_type = '';

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
                        $stmt = $db-&gt;prepare("INSERT INTO gallery (title, description, image_path, category, uploaded_by, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
                        $stmt-&gt;bind_param('ssssi', $title, $description, $file_path, $category, $_SESSION['user_id']);
                        if ($stmt-&gt;execute()) {
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

            $stmt = $db-&gt;prepare("UPDATE gallery SET title = ?, description = ?, category = ? WHERE id = ?");
            $stmt-&gt;bind_param('sssi', $title, $description, $category, $id);
            if ($stmt-&gt;execute()) {
                $message = 'Image updated successfully.';
                $message_type = 'success';
            } else {
                $message = 'Error updating image.';
                $message_type = 'error';
            }
        } elseif ($action === 'delete_image') {
            $id = intval($_POST['image_id']);

            // Get file path before deleting
            $stmt = $db-&gt;prepare("SELECT image_path FROM gallery WHERE id = ?");
            $stmt-&gt;bind_param('i', $id);
            $stmt-&gt;execute();
            $result = $stmt-&gt;get_result();
            $image = $result-&gt;fetch_assoc();

            // Delete from database
            $stmt = $db-&gt;prepare("DELETE FROM gallery WHERE id = ?");
            $stmt-&gt;bind_param('i', $id);
            if ($stmt-&gt;execute()) {
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
$gallery_result = $db-&gt;query("SELECT g.*, u.username FROM gallery g LEFT JOIN users u ON g.uploaded_by = u.id ORDER BY g.created_at DESC");
?&gt;
&lt;!DOCTYPE html&gt;
&lt;html lang="en"&gt;
&lt;head&gt;
    &lt;meta charset="UTF-8"&gt;
    &lt;meta name="viewport" content="width=device-width, initial-scale=1.0"&gt;
    &lt;title&gt;Gallery Management - Salem Dominion Ministries&lt;/title&gt;
    &lt;link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"&gt;
    &lt;link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet"&gt;
    &lt;style&gt;
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
    &lt;/style&gt;
&lt;/head&gt;
&lt;body&gt;
    &lt;!-- Navigation --&gt;
    &lt;nav class="navbar navbar-expand-lg navbar-dark bg-dark"&gt;
        &lt;div class="container"&gt;
            &lt;a class="navbar-brand" href="index.php"&gt;
                &lt;i class="fas fa-church"&gt;&lt;/i&gt; Salem Dominion Ministries
            &lt;/a&gt;
            &lt;button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"&gt;
                &lt;span class="navbar-toggler-icon"&gt;&lt;/span&gt;
            &lt;/button&gt;
            &lt;div class="collapse navbar-collapse" id="navbarNav"&gt;
                &lt;ul class="navbar-nav me-auto"&gt;
                    &lt;li class="nav-item"&gt;&lt;a class="nav-link" href="index.php"&gt;Home&lt;/a&gt;&lt;/li&gt;
                    &lt;li class="nav-item"&gt;&lt;a class="nav-link" href="about.php"&gt;About&lt;/a&gt;&lt;/li&gt;
                    &lt;li class="nav-item"&gt;&lt;a class="nav-link" href="ministries.php"&gt;Ministries&lt;/a&gt;&lt;/li&gt;
                    &lt;li class="nav-item"&gt;&lt;a class="nav-link" href="events.php"&gt;Events&lt;/a&gt;&lt;/li&gt;
                    &lt;li class="nav-item"&gt;&lt;a class="nav-link" href="sermons.php"&gt;Sermons&lt;/a&gt;&lt;/li&gt;
                    &lt;li class="nav-item"&gt;&lt;a class="nav-link" href="news.php"&gt;News&lt;/a&gt;&lt;/li&gt;
                    &lt;li class="nav-item"&gt;&lt;a class="nav-link" href="gallery.php"&gt;Gallery&lt;/a&gt;&lt;/li&gt;
                    &lt;li class="nav-item"&gt;&lt;a class="nav-link" href="contact.php"&gt;Contact&lt;/a&gt;&lt;/li&gt;
                &lt;/ul&gt;
                &lt;ul class="navbar-nav"&gt;
                    &lt;li class="nav-item"&gt;&lt;a class="nav-link" href="dashboard.php"&gt;Dashboard&lt;/a&gt;&lt;/li&gt;
                    &lt;li class="nav-item"&gt;&lt;a class="nav-link" href="logout.php"&gt;Logout&lt;/a&gt;&lt;/li&gt;
                &lt;/ul&gt;
            &lt;/div&gt;
        &lt;/div&gt;
    &lt;/nav&gt;

    &lt;div class="container-fluid"&gt;
        &lt;div class="row"&gt;
            &lt;!-- Sidebar --&gt;
            &lt;div class="col-md-3 col-lg-2 px-0"&gt;
                &lt;div class="sidebar p-3"&gt;
                    &lt;h6 class="text-white-50 mb-3"&gt;ADMIN PANEL&lt;/h6&gt;
                    &lt;nav class="nav flex-column"&gt;
                        &lt;a class="nav-link" href="admin_dashboard.php"&gt;
                            &lt;i class="fas fa-tachometer-alt"&gt;&lt;/i&gt; Dashboard
                        &lt;/a&gt;
                        &lt;a class="nav-link" href="admin_users.php"&gt;
                            &lt;i class="fas fa-users"&gt;&lt;/i&gt; Users
                        &lt;/a&gt;
                        &lt;a class="nav-link" href="admin_news.php"&gt;
                            &lt;i class="fas fa-newspaper"&gt;&lt;/i&gt; News
                        &lt;/a&gt;
                        &lt;a class="nav-link" href="admin_events.php"&gt;
                            &lt;i class="fas fa-calendar"&gt;&lt;/i&gt; Events
                        &lt;/a&gt;
                        &lt;a class="nav-link" href="admin_sermons.php"&gt;
                            &lt;i class="fas fa-microphone"&gt;&lt;/i&gt; Sermons
                        &lt;/a&gt;
                        &lt;a class="nav-link" href="admin_ministries.php"&gt;
                            &lt;i class="fas fa-praying-hands"&gt;&lt;/i&gt; Ministries
                        &lt;/a&gt;
                        &lt;a class="nav-link active" href="admin_gallery.php"&gt;
                            &lt;i class="fas fa-images"&gt;&lt;/i&gt; Gallery
                        &lt;/a&gt;
                        &lt;a class="nav-link" href="admin_donations.php"&gt;
                            &lt;i class="fas fa-hand-holding-heart"&gt;&lt;/i&gt; Donations
                        &lt;/a&gt;
                        &lt;a class="nav-link" href="admin_prayer_requests.php"&gt;
                            &lt;i class="fas fa-pray"&gt;&lt;/i&gt; Prayer Requests
                        &lt;/a&gt;
                    &lt;/nav&gt;
                &lt;/div&gt;
            &lt;/div&gt;

            &lt;!-- Main Content --&gt;
            &lt;div class="col-md-9 col-lg-10 px-4 py-4"&gt;
                &lt;div class="d-flex justify-content-between align-items-center mb-4"&gt;
                    &lt;h2&gt;&lt;i class="fas fa-images text-primary"&gt;&lt;/i&gt; Gallery Management&lt;/h2&gt;
                    &lt;button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#uploadImageModal"&gt;
                        &lt;i class="fas fa-upload"&gt;&lt;/i&gt; Upload Image
                    &lt;/button&gt;
                &lt;/div&gt;

                &lt;?php if ($message): ?&gt;
                    &lt;div class="alert alert-&lt;?php echo $message_type === 'success' ? 'success' : 'danger'; ?&gt; alert-dismissible fade show" role="alert"&gt;
                        &lt;?php echo htmlspecialchars($message); ?&gt;
                        &lt;button type="button" class="btn-close" data-bs-dismiss="alert"&gt;&lt;/button&gt;
                    &lt;/div&gt;
                &lt;?php endif; ?&gt;

                &lt;!-- Gallery Grid --&gt;
                &lt;div class="row g-3"&gt;
                    &lt;?php if ($gallery_result-&gt;num_rows &gt; 0): ?&gt;
                        &lt;?php while ($image = $gallery_result-&gt;fetch_assoc()): ?&gt;
                            &lt;div class="col-md-6 col-lg-4 col-xl-3"&gt;
                                &lt;div class="gallery-item"&gt;
                                    &lt;img src="&lt;?php echo htmlspecialchars($image['image_path']); ?&gt;" alt="&lt;?php echo htmlspecialchars($image['title']); ?&gt;" class="img-fluid"&gt;
                                    &lt;div class="gallery-overlay"&gt;
                                        &lt;div class="text-center"&gt;
                                            &lt;button class="btn btn-light btn-sm me-2" onclick="editImage(&lt;?php echo $image['id']; ?&gt;, '&lt;?php echo htmlspecialchars(addslashes($image['title'])); ?&gt;', '&lt;?php echo htmlspecialchars(addslashes($image['description'])); ?&gt;', '&lt;?php echo $image['category']; ?&gt;')"&gt;
                                                &lt;i class="fas fa-edit"&gt;&lt;/i&gt; Edit
                                            &lt;/button&gt;
                                            &lt;form method="post" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this image?')"&gt;
                                                &lt;input type="hidden" name="action" value="delete_image"&gt;
                                                &lt;input type="hidden" name="image_id" value="&lt;?php echo $image['id']; ?&gt;"&gt;
                                                &lt;button type="submit" class="btn btn-danger btn-sm"&gt;
                                                    &lt;i class="fas fa-trash"&gt;&lt;/i&gt; Delete
                                                &lt;/button&gt;
                                            &lt;/form&gt;
                                        &lt;/div&gt;
                                    &lt;/div&gt;
                                &lt;/div&gt;
                                &lt;div class="mt-2"&gt;
                                    &lt;h6 class="mb-1"&gt;&lt;?php echo htmlspecialchars($image['title']); ?&gt;&lt;/h6&gt;
                                    &lt;div class="d-flex justify-content-between align-items-center text-muted small"&gt;
                                        &lt;span&gt;&lt;i class="fas fa-tag"&gt;&lt;/i&gt; &lt;?php echo ucfirst($image['category']); ?&gt;&lt;/span&gt;
                                        &lt;span&gt;&lt;i class="fas fa-user"&gt;&lt;/i&gt; &lt;?php echo htmlspecialchars($image['username'] ?: 'Unknown'); ?&gt;&lt;/span&gt;
                                    &lt;/div&gt;
                                    &lt;small class="text-muted"&gt;&lt;?php echo date('M j, Y', strtotime($image['created_at'])); ?&gt;&lt;/small&gt;
                                &lt;/div&gt;
                            &lt;/div&gt;
                        &lt;?php endwhile; ?&gt;
                    &lt;?php else: ?&gt;
                        &lt;div class="col-12"&gt;
                            &lt;div class="text-center py-5"&gt;
                                &lt;i class="fas fa-images fa-3x text-muted mb-3"&gt;&lt;/i&gt;
                                &lt;h4 class="text-muted"&gt;No Images Yet&lt;/h4&gt;
                                &lt;p class="text-muted"&gt;Upload your first image to get started.&lt;/p&gt;
                            &lt;/div&gt;
                        &lt;/div&gt;
                    &lt;?php endif; ?&gt;
                &lt;/div&gt;
            &lt;/div&gt;
        &lt;/div&gt;
    &lt;/div&gt;

    &lt;!-- Upload Image Modal --&gt;
    &lt;div class="modal fade" id="uploadImageModal" tabindex="-1"&gt;
        &lt;div class="modal-dialog modal-lg"&gt;
            &lt;div class="modal-content"&gt;
                &lt;div class="modal-header"&gt;
                    &lt;h5 class="modal-title"&gt;&lt;i class="fas fa-upload"&gt;&lt;/i&gt; Upload Image&lt;/h5&gt;
                    &lt;button type="button" class="btn-close" data-bs-dismiss="modal"&gt;&lt;/button&gt;
                &lt;/div&gt;
                &lt;form method="post" enctype="multipart/form-data"&gt;
                    &lt;div class="modal-body"&gt;
                        &lt;input type="hidden" name="action" value="upload_image"&gt;
                        &lt;div class="row"&gt;
                            &lt;div class="col-md-6 mb-3"&gt;
                                &lt;label for="title" class="form-label"&gt;Image Title&lt;/label&gt;
                                &lt;input type="text" class="form-control" id="title" name="title"&gt;
                            &lt;/div&gt;
                            &lt;div class="col-md-6 mb-3"&gt;
                                &lt;label for="category" class="form-label"&gt;Category&lt;/label&gt;
                                &lt;select class="form-select" id="category" name="category"&gt;
                                    &lt;option value="general"&gt;General&lt;/option&gt;
                                    &lt;option value="worship"&gt;Worship&lt;/option&gt;
                                    &lt;option value="events"&gt;Events&lt;/option&gt;
                                    &lt;option value="ministry"&gt;Ministry&lt;/option&gt;
                                    &lt;option value="outreach"&gt;Outreach&lt;/option&gt;
                                &lt;/select&gt;
                            &lt;/div&gt;
                        &lt;/div&gt;
                        &lt;div class="mb-3"&gt;
                            &lt;label for="description" class="form-label"&gt;Description&lt;/label&gt;
                            &lt;textarea class="form-control" id="description" name="description" rows="2"&gt;&lt;/textarea&gt;
                        &lt;/div&gt;
                        &lt;div class="mb-3"&gt;
                            &lt;label class="form-label"&gt;Image File *&lt;/label&gt;
                            &lt;div class="file-upload-area" id="fileUploadArea"&gt;
                                &lt;i class="fas fa-cloud-upload-alt fa-3x text-muted mb-3"&gt;&lt;/i&gt;
                                &lt;p class="mb-1"&gt;Drag &amp; drop an image here&lt;/p&gt;
                                &lt;p class="small text-muted"&gt;or&lt;/p&gt;
                                &lt;input type="file" class="form-control" id="image" name="image" accept="image/*" required style="display: none;"&gt;
                                &lt;button type="button" class="btn btn-outline-primary" onclick="document.getElementById('image').click()"&gt;Choose File&lt;/button&gt;
                                &lt;div id="fileName" class="mt-2 small text-success"&gt;&lt;/div&gt;
                            &lt;/div&gt;
                            &lt;small class="form-text text-muted"&gt;Supported formats: JPEG, PNG, GIF, WebP. Maximum file size: 10MB.&lt;/small&gt;
                        &lt;/div&gt;
                    &lt;/div&gt;
                    &lt;div class="modal-footer"&gt;
                        &lt;button type="button" class="btn btn-secondary" data-bs-dismiss="modal"&gt;Cancel&lt;/button&gt;
                        &lt;button type="submit" class="btn btn-primary"&gt;&lt;i class="fas fa-upload"&gt;&lt;/i&gt; Upload Image&lt;/button&gt;
                    &lt;/div&gt;
                &lt;/form&gt;
            &lt;/div&gt;
        &lt;/div&gt;
    &lt;/div&gt;

    &lt;!-- Edit Image Modal --&gt;
    &lt;div class="modal fade" id="editImageModal" tabindex="-1"&gt;
        &lt;div class="modal-dialog modal-lg"&gt;
            &lt;div class="modal-content"&gt;
                &lt;div class="modal-header"&gt;
                    &lt;h5 class="modal-title"&gt;&lt;i class="fas fa-edit"&gt;&lt;/i&gt; Edit Image&lt;/h5&gt;
                    &lt;button type="button" class="btn-close" data-bs-dismiss="modal"&gt;&lt;/button&gt;
                &lt;/div&gt;
                &lt;form method="post"&gt;
                    &lt;div class="modal-body"&gt;
                        &lt;input type="hidden" name="action" value="update_image"&gt;
                        &lt;input type="hidden" name="image_id" id="edit_image_id"&gt;
                        &lt;div class="row"&gt;
                            &lt;div class="col-md-6 mb-3"&gt;
                                &lt;label for="edit_title" class="form-label"&gt;Image Title&lt;/label&gt;
                                &lt;input type="text" class="form-control" id="edit_title" name="title"&gt;
                            &lt;/div&gt;
                            &lt;div class="col-md-6 mb-3"&gt;
                                &lt;label for="edit_category" class="form-label"&gt;Category&lt;/label&gt;
                                &lt;select class="form-select" id="edit_category" name="category"&gt;
                                    &lt;option value="general"&gt;General&lt;/option&gt;
                                    &lt;option value="worship"&gt;Worship&lt;/option&gt;
                                    &lt;option value="events"&gt;Events&lt;/option&gt;
                                    &lt;option value="ministry"&gt;Ministry&lt;/option&gt;
                                    &lt;option value="outreach"&gt;Outreach&lt;/option&gt;
                                &lt;/select&gt;
                            &lt;/div&gt;
                        &lt;/div&gt;
                        &lt;div class="mb-3"&gt;
                            &lt;label for="edit_description" class="form-label"&gt;Description&lt;/label&gt;
                            &lt;textarea class="form-control" id="edit_description" name="description" rows="2"&gt;&lt;/textarea&gt;
                        &lt;/div&gt;
                    &lt;/div&gt;
                    &lt;div class="modal-footer"&gt;
                        &lt;button type="button" class="btn btn-secondary" data-bs-dismiss="modal"&gt;Cancel&lt;/button&gt;
                        &lt;button type="submit" class="btn btn-primary"&gt;&lt;i class="fas fa-save"&gt;&lt;/i&gt; Update Image&lt;/button&gt;
                    &lt;/div&gt;
                &lt;/form&gt;
            &lt;/div&gt;
        &lt;/div&gt;
    &lt;/div&gt;

    &lt;script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"&gt;&lt;/script&gt;
    &lt;script&gt;
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

        fileUploadArea.addEventListener('dragover', (e) =&gt; {
            e.preventDefault();
            fileUploadArea.classList.add('dragover');
        });

        fileUploadArea.addEventListener('dragleave', () =&gt; {
            fileUploadArea.classList.remove('dragover');
        });

        fileUploadArea.addEventListener('drop', (e) =&gt; {
            e.preventDefault();
            fileUploadArea.classList.remove('dragover');
            const files = e.dataTransfer.files;
            if (files.length &gt; 0) {
                imageInput.files = files;
                fileNameDisplay.textContent = files[0].name;
            }
        });

        imageInput.addEventListener('change', () =&gt; {
            if (imageInput.files.length &gt; 0) {
                fileNameDisplay.textContent = imageInput.files[0].name;
            } else {
                fileNameDisplay.textContent = '';
            }
        });
    &lt;/script&gt;
&lt;/body&gt;
&lt;/html&gt;