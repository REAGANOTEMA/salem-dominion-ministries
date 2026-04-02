&lt;?php
session_start();
require_once 'db.php';

// Handle image upload for members
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id']) && isset($_FILES['gallery_image'])) {
    $user_id = $_SESSION['user_id'];
    $file = $_FILES['gallery_image'];

    if ($file['error'] === UPLOAD_ERR_OK) {
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        if (in_array($file['type'], $allowed_types)) {
            $upload_dir = 'uploads/gallery/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }

            $filename = uniqid() . '_' . basename($file['name']);
            $filepath = $upload_dir . $filename;

            if (move_uploaded_file($file['tmp_name'], $filepath)) {
                $stmt = $db-&gt;prepare("INSERT INTO gallery (user_id, image_path, title, description) VALUES (?, ?, ?, ?)");
                $title = trim($_POST['title'] ?? '');
                $description = trim($_POST['description'] ?? '');
                $stmt-&gt;bind_param('isss', $user_id, $filepath, $title, $description);
                $stmt-&gt;execute();
                $stmt-&gt;close();
                $upload_success = 'Image uploaded successfully!';
            } else {
                $upload_error = 'Failed to upload image.';
            }
        } else {
            $upload_error = 'Invalid file type. Only JPEG, PNG, GIF, and WebP are allowed.';
        }
    } else {
        $upload_error = 'Upload error occurred.';
    }
}

// Get gallery images
$gallery_images = $db-&gt;query("SELECT g.*, u.username, u.avatar FROM gallery g LEFT JOIN users u ON g.user_id = u.id ORDER BY g.created_at DESC");
?&gt;
&lt;!DOCTYPE html&gt;
&lt;html lang="en"&gt;
&lt;head&gt;
    &lt;meta charset="UTF-8"&gt;
    &lt;meta name="viewport" content="width=device-width, initial-scale=1.0"&gt;
    &lt;title&gt;Gallery - Salem Dominion Ministries&lt;/title&gt;
    &lt;link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"&gt;
    &lt;link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet"&gt;
    &lt;style&gt;
        .gallery-hero {
            background: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)), url('https://images.unsplash.com/photo-1511578314322-379afb476865?ixlib=rb-4.0.3');
            background-size: cover;
            background-position: center;
            color: white;
            padding: 80px 0;
        }
        .navbar-brand {
            font-weight: bold;
            font-size: 1.5rem;
        }
        .gallery-item {
            position: relative;
            overflow: hidden;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }
        .gallery-item:hover {
            transform: translateY(-5px);
        }
        .gallery-item img {
            width: 100%;
            height: 250px;
            object-fit: cover;
        }
        .gallery-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: linear-gradient(transparent, rgba(0,0,0,0.8));
            color: white;
            padding: 20px;
            transform: translateY(100%);
            transition: transform 0.3s ease;
        }
        .gallery-item:hover .gallery-overlay {
            transform: translateY(0);
        }
        .upload-card {
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            border: none;
            border-radius: 15px;
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
                    &lt;li class="nav-item"&gt;&lt;a class="nav-link active" href="gallery.php"&gt;Gallery&lt;/a&gt;&lt;/li&gt;
                    &lt;li class="nav-item"&gt;&lt;a class="nav-link" href="contact.php"&gt;Contact&lt;/a&gt;&lt;/li&gt;
                &lt;/ul&gt;
                &lt;ul class="navbar-nav"&gt;
                    &lt;?php if (isset($_SESSION['user_id'])): ?&gt;
                        &lt;li class="nav-item"&gt;&lt;a class="nav-link" href="dashboard.php"&gt;Dashboard&lt;/a&gt;&lt;/li&gt;
                        &lt;li class="nav-item"&gt;&lt;a class="nav-link" href="logout.php"&gt;Logout&lt;/a&gt;&lt;/li&gt;
                    &lt;?php else: ?&gt;
                        &lt;li class="nav-item"&gt;&lt;a class="nav-link" href="login.php"&gt;Login&lt;/a&gt;&lt;/li&gt;
                        &lt;li class="nav-item"&gt;&lt;a class="nav-link" href="register.php"&gt;Register&lt;/a&gt;&lt;/li&gt;
                    &lt;?php endif; ?&gt;
                &lt;/ul&gt;
            &lt;/div&gt;
        &lt;/div&gt;
    &lt;/nav&gt;

    &lt;!-- Hero Section --&gt;
    &lt;section class="gallery-hero"&gt;
        &lt;div class="container text-center"&gt;
            &lt;h1 class="display-4 fw-bold mb-4"&gt;Photo Gallery&lt;/h1&gt;
            &lt;p class="lead mb-4"&gt;Capturing moments of worship, fellowship, and community service.&lt;/p&gt;
        &lt;/div&gt;
    &lt;/section&gt;

    &lt;!-- Gallery Content --&gt;
    &lt;section class="py-5"&gt;
        &lt;div class="container"&gt;
            &lt;!-- Upload Section for Members --&gt;
            &lt;?php if (isset($_SESSION['user_id'])): ?&gt;
                &lt;div class="row mb-5"&gt;
                    &lt;div class="col-lg-6 mx-auto"&gt;
                        &lt;div class="card upload-card"&gt;
                            &lt;div class="card-body p-4"&gt;
                                &lt;h4 class="card-title mb-4"&gt;&lt;i class="fas fa-camera text-primary"&gt;&lt;/i&gt; Share Your Photos&lt;/h4&gt;

                                &lt;?php if (isset($upload_success)): ?&gt;
                                    &lt;div class="alert alert-success"&gt;&lt;?php echo htmlspecialchars($upload_success); ?&gt;&lt;/div&gt;
                                &lt;?php endif; ?&gt;

                                &lt;?php if (isset($upload_error)): ?&gt;
                                    &lt;div class="alert alert-danger"&gt;&lt;?php echo htmlspecialchars($upload_error); ?&gt;&lt;/div&gt;
                                &lt;?php endif; ?&gt;

                                &lt;form method="POST" enctype="multipart/form-data"&gt;
                                    &lt;div class="mb-3"&gt;
                                        &lt;label for="title" class="form-label"&gt;Photo Title&lt;/label&gt;
                                        &lt;input type="text" class="form-control" id="title" name="title" placeholder="Optional title for your photo"&gt;
                                    &lt;/div&gt;
                                    &lt;div class="mb-3"&gt;
                                        &lt;label for="description" class="form-label"&gt;Description&lt;/label&gt;
                                        &lt;textarea class="form-control" id="description" name="description" rows="2" placeholder="Optional description"&gt;&lt;/textarea&gt;
                                    &lt;/div&gt;
                                    &lt;div class="mb-3"&gt;
                                        &lt;label for="gallery_image" class="form-label"&gt;Select Image&lt;/label&gt;
                                        &lt;input type="file" class="form-control" id="gallery_image" name="gallery_image" accept="image/*" required&gt;
                                        &lt;div class="form-text"&gt;Supported formats: JPEG, PNG, GIF, WebP. Max size: 5MB.&lt;/div&gt;
                                    &lt;/div&gt;
                                    &lt;button type="submit" class="btn btn-primary"&gt;
                                        &lt;i class="fas fa-upload"&gt;&lt;/i&gt; Upload Photo
                                    &lt;/button&gt;
                                &lt;/form&gt;
                            &lt;/div&gt;
                        &lt;/div&gt;
                    &lt;/div&gt;
                &lt;/div&gt;
            &lt;?php endif; ?&gt;

            &lt;!-- Gallery Grid --&gt;
            &lt;div class="row g-4"&gt;
                &lt;?php if ($gallery_images-&gt;num_rows &gt; 0): ?&gt;
                    &lt;?php while ($image = $gallery_images-&gt;fetch_assoc()): ?&gt;
                        &lt;div class="col-lg-4 col-md-6"&gt;
                            &lt;div class="gallery-item"&gt;
                                &lt;img src="&lt;?php echo htmlspecialchars($image['image_path']); ?&gt;" alt="&lt;?php echo htmlspecialchars($image['title'] ?: 'Gallery Image'); ?&gt;"&gt;
                                &lt;div class="gallery-overlay"&gt;
                                    &lt;h5&gt;&lt;?php echo htmlspecialchars($image['title'] ?: 'Untitled'); ?&gt;&lt;/h5&gt;
                                    &lt;?php if ($image['description']): ?&gt;
                                        &lt;p class="mb-2"&gt;&lt;?php echo htmlspecialchars($image['description']); ?&gt;&lt;/p&gt;
                                    &lt;?php endif; ?&gt;
                                    &lt;small class="text-muted"&gt;
                                        &lt;i class="fas fa-user"&gt;&lt;/i&gt; &lt;?php echo htmlspecialchars($image['username']); ?&gt; &amp;bull;
                                        &lt;i class="fas fa-calendar"&gt;&lt;/i&gt; &lt;?php echo date('M j, Y', strtotime($image['created_at'])); ?&gt;
                                    &lt;/small&gt;
                                &lt;/div&gt;
                            &lt;/div&gt;
                        &lt;/div&gt;
                    &lt;?php endwhile; ?&gt;
                &lt;?php else: ?&gt;
                    &lt;div class="col-12 text-center py-5"&gt;
                        &lt;i class="fas fa-images fa-3x text-muted mb-3"&gt;&lt;/i&gt;
                        &lt;h4 class="text-muted"&gt;No photos yet&lt;/h4&gt;
                        &lt;p class="text-muted"&gt;Be the first to share a photo from our church activities!&lt;/p&gt;
                        &lt;?php if (!isset($_SESSION['user_id'])): ?&gt;
                            &lt;a href="login.php" class="btn btn-primary"&gt;Login to Upload&lt;/a&gt;
                        &lt;?php endif; ?&gt;
                    &lt;/div&gt;
                &lt;?php endif; ?&gt;
            &lt;/div&gt;
        &lt;/div&gt;
    &lt;/section&gt;

    &lt;!-- Footer --&gt;
    &lt;footer class="bg-dark text-light py-4"&gt;
        &lt;div class="container"&gt;
            &lt;div class="row"&gt;
                &lt;div class="col-md-4"&gt;
                    &lt;h5&gt;Salem Dominion Ministries&lt;/h5&gt;
                    &lt;p&gt;Serving our community with faith, hope, and love.&lt;/p&gt;
                &lt;/div&gt;
                &lt;div class="col-md-4"&gt;
                    &lt;h5&gt;Quick Links&lt;/h5&gt;
                    &lt;ul class="list-unstyled"&gt;
                        &lt;li&gt;&lt;a href="index.php" class="text-light"&gt;Home&lt;/a&gt;&lt;/li&gt;
                        &lt;li&gt;&lt;a href="about.php" class="text-light"&gt;About Us&lt;/a&gt;&lt;/li&gt;
                        &lt;li&gt;&lt;a href="donate.php" class="text-light"&gt;Donate&lt;/a&gt;&lt;/li&gt;
                    &lt;/ul&gt;
                &lt;/div&gt;
                &lt;div class="col-md-4"&gt;
                    &lt;h5&gt;Contact Info&lt;/h5&gt;
                    &lt;p&gt;&lt;i class="fas fa-envelope"&gt;&lt;/i&gt; visit@salemdominionministries.com&lt;/p&gt;
                    &lt;p&gt;&lt;i class="fas fa-phone"&gt;&lt;/i&gt; Contact us for service times&lt;/p&gt;
                &lt;/div&gt;
            &lt;/div&gt;
            &lt;hr&gt;
            &lt;p class="text-center mb-0"&gt;&amp;copy; 2026 Salem Dominion Ministries. All rights reserved.&lt;/p&gt;
        &lt;/div&gt;
    &lt;/footer&gt;

    &lt;!-- Image Modal --&gt;
    &lt;div class="modal fade" id="imageModal" tabindex="-1"&gt;
        &lt;div class="modal-dialog modal-lg"&gt;
            &lt;div class="modal-content"&gt;
                &lt;div class="modal-body p-0"&gt;
                    &lt;img id="modalImage" src="" class="img-fluid w-100" alt=""&gt;
                &lt;/div&gt;
            &lt;/div&gt;
        &lt;/div&gt;
    &lt;/div&gt;

    &lt;script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"&gt;&lt;/script&gt;
    &lt;script&gt;
        // Make gallery images clickable to open in modal
        document.querySelectorAll('.gallery-item img').forEach(img =&gt; {
            img.addEventListener('click', function() {
                const modalImage = document.getElementById('modalImage');
                modalImage.src = this.src;
                modalImage.alt = this.alt;
                new bootstrap.Modal(document.getElementById('imageModal')).show();
            });
        });
    &lt;/script&gt;
&lt;/body&gt;
&lt;/html&gt;