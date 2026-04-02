<?php
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
                $stmt = $db->prepare("INSERT INTO gallery (user_id, image_path, title, description) VALUES (?, ?, ?, ?)");
                $title = trim($_POST['title'] ?? '');
                $description = trim($_POST['description'] ?? '');
                $stmt->bind_param('isss', $user_id, $filepath, $title, $description);
                $stmt->execute();
                $stmt->close();
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
$gallery_images = $db->query("SELECT g.*, u.username, u.avatar FROM gallery g LEFT JOIN users u ON g.user_id = u.id ORDER BY g.created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gallery - Salem Dominion Ministries</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
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
                    <li class="nav-item"><a class="nav-link active" href="gallery.php">Gallery</a></li>
                    <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
                </ul>
                <ul class="navbar-nav">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li class="nav-item"><a class="nav-link" href="dashboard.php">Dashboard</a></li>
                        <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
                    <?php else: ?>
                        <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
                        <li class="nav-item"><a class="nav-link" href="register.php">Register</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="gallery-hero">
        <div class="container text-center">
            <h1 class="display-4 fw-bold mb-4">Photo Gallery</h1>
            <p class="lead mb-4">Capturing moments of worship, fellowship, and community service.</p>
        </div>
    </section>

    <!-- Gallery Content -->
    <section class="py-5">
        <div class="container">
            <!-- Upload Section for Members -->
            <?php if (isset($_SESSION['user_id'])): ?>
                <div class="row mb-5">
                    <div class="col-lg-6 mx-auto">
                        <div class="card upload-card">
                            <div class="card-body p-4">
                                <h4 class="card-title mb-4"><i class="fas fa-camera text-primary"></i> Share Your Photos</h4>

                                <?php if (isset($upload_success)): ?>
                                    <div class="alert alert-success"><?php echo htmlspecialchars($upload_success); ?></div>
                                <?php endif; ?>

                                <?php if (isset($upload_error)): ?>
                                    <div class="alert alert-danger"><?php echo htmlspecialchars($upload_error); ?></div>
                                <?php endif; ?>

                                <form method="POST" enctype="multipart/form-data">
                                    <div class="mb-3">
                                        <label for="title" class="form-label">Photo Title</label>
                                        <input type="text" class="form-control" id="title" name="title" placeholder="Optional title for your photo">
                                    </div>
                                    <div class="mb-3">
                                        <label for="description" class="form-label">Description</label>
                                        <textarea class="form-control" id="description" name="description" rows="2" placeholder="Optional description"></textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label for="gallery_image" class="form-label">Select Image</label>
                                        <input type="file" class="form-control" id="gallery_image" name="gallery_image" accept="image/*" required>
                                        <div class="form-text">Supported formats: JPEG, PNG, GIF, WebP. Max size: 5MB.</div>
                                    </div>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-upload"></i> Upload Photo
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Gallery Grid -->
            <div class="row g-4">
                <?php if ($gallery_images->num_rows > 0): ?>
                    <?php while ($image = $gallery_images->fetch_assoc()): ?>
                        <div class="col-lg-4 col-md-6">
                            <div class="gallery-item">
                                <img src="<?php echo htmlspecialchars($image['image_path']); ?>" alt="<?php echo htmlspecialchars($image['title'] ?: 'Gallery Image'); ?>">
                                <div class="gallery-overlay">
                                    <h5><?php echo htmlspecialchars($image['title'] ?: 'Untitled'); ?></h5>
                                    <?php if ($image['description']): ?>
                                        <p class="mb-2"><?php echo htmlspecialchars($image['description']); ?></p>
                                    <?php endif; ?>
                                    <small class="text-muted">
                                        <i class="fas fa-user"></i> <?php echo htmlspecialchars($image['username']); ?> &bull;
                                        <i class="fas fa-calendar"></i> <?php echo date('M j, Y', strtotime($image['created_at'])); ?>
                                    </small>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="col-12 text-center py-5">
                        <i class="fas fa-images fa-3x text-muted mb-3"></i>
                        <h4 class="text-muted">No photos yet</h4>
                        <p class="text-muted">Be the first to share a photo from our church activities!</p>
                        <?php if (!isset($_SESSION['user_id'])): ?>
                            <a href="login.php" class="btn btn-primary">Login to Upload</a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-light py-4">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h5>Salem Dominion Ministries</h5>
                    <p>Serving our community with faith, hope, and love.</p>
                </div>
                <div class="col-md-4">
                    <h5>Quick Links</h5>
                    <ul class="list-unstyled">
                        <li><a href="index.php" class="text-light">Home</a></li>
                        <li><a href="about.php" class="text-light">About Us</a></li>
                        <li><a href="donate.php" class="text-light">Donate</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h5>Contact Info</h5>
                    <p><i class="fas fa-envelope"></i> visit@salemdominionministries.com</p>
                    <p><i class="fas fa-phone"></i> Contact us for service times</p>
                </div>
            </div>
            <hr>
            <p class="text-center mb-0">&copy; 2026 Salem Dominion Ministries. All rights reserved.</p>
        </div>
    </footer>

    <!-- Image Modal -->
    <div class="modal fade" id="imageModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-body p-0">
                    <img id="modalImage" src="" class="img-fluid w-100" alt="">
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Make gallery images clickable to open in modal
        document.querySelectorAll('.gallery-item img').forEach(img => {
            img.addEventListener('click', function() {
                const modalImage = document.getElementById('modalImage');
                modalImage.src = this.src;
                modalImage.alt = this.alt;
                new bootstrap.Modal(document.getElementById('imageModal')).show();
            });
        });
    </script>
</body>
</html>