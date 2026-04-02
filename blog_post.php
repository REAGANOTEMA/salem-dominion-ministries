<?php
session_start();
require_once 'db.php';

$post_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if (!$post_id) {
    header('Location: blog.php');
    exit;
}

$post = $db->query("SELECT bp.*, u.first_name, u.last_name, u.avatar_url FROM blog_posts bp LEFT JOIN users u ON bp.author_id = u.id WHERE bp.id = $post_id AND bp.status = 'published'")->fetch_assoc();

if (!$post) {
    header('Location: blog.php');
    exit;
}

// Update view count
$db->query("UPDATE blog_posts SET views_count = views_count + 1 WHERE id = $post_id");

// Get related posts
$related_posts = $db->query("SELECT id, title, excerpt, category FROM blog_posts WHERE id != $post_id AND status = 'published' ORDER BY created_at DESC LIMIT 3");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($post['title']); ?> - Salem Dominion Ministries</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .post-image {
            max-height: 400px;
            object-fit: cover;
            width: 100%;
        }
        .avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
        }
        .comment-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            object-fit: cover;
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
                    <li class="nav-item"><a class="nav-link active" href="blog.php">Blog</a></li>
                    <li class="nav-item"><a class="nav-link" href="gallery.php">Gallery</a></li>
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

    <!-- Post Content -->
    <div class="container my-4">
        <?php if (isset($_GET['success']) && $_GET['success'] === 'comment_added'): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                Comment added successfully!
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="row">
            <div class="col-lg-8 mx-auto">
                <!-- Post Header -->
                <div class="card mb-4">
                    <?php if ($post['featured_image_url']): ?>
                    <img src="<?php echo htmlspecialchars($post['featured_image_url']); ?>" class="card-img-top post-image" alt="Post Image">
                    <?php endif; ?>
                    <div class="card-body">
                        <h1 class="card-title"><?php echo htmlspecialchars($post['title']); ?></h1>
                        <div class="d-flex align-items-center mb-3">
                            <?php if ($post['avatar_url']): ?>
                            <img src="<?php echo htmlspecialchars($post['avatar_url']); ?>" alt="Author Avatar" class="avatar me-2">
                            <?php else: ?>
                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 40px; height: 40px;">
                                <i class="fas fa-user"></i>
                            </div>
                            <?php endif; ?>
                            <div>
                                <strong><?php echo htmlspecialchars(($post['first_name'] ?? '') . ' ' . ($post['last_name'] ?? '') ?: 'Church Staff'); ?></strong>
                                <br><small class="text-muted"><?php echo date('F j, Y \a\t g:i A', strtotime($post['published_at'] ?? $post['created_at'])); ?></small>
                            </div>
                        </div>
                        <?php if ($post['category']): ?>
                        <span class="badge bg-primary mb-3"><?php echo htmlspecialchars($post['category']); ?></span>
                        <?php endif; ?>
                        <div class="post-content">
                            <?php echo nl2br(htmlspecialchars($post['content'])); ?>
                        </div>
                        
                        <!-- Share Buttons -->
                        <div class="share-buttons mt-4 pt-4 border-top">
                            <h6 class="mb-3"><i class="fas fa-share-alt"></i> Share this article</h6>
                            <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']); ?>" target="_blank" class="btn btn-outline-primary btn-sm">
                                <i class="fab fa-facebook-f"></i> Facebook
                            </a>
                            <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']); ?>&text=<?php echo urlencode($post['title']); ?>" target="_blank" class="btn btn-outline-info btn-sm">
                                <i class="fab fa-twitter"></i> Twitter
                            </a>
                            <a href="https://wa.me/?text=<?php echo urlencode($post['title'] . ' - ' . 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']); ?>" target="_blank" class="btn btn-outline-success btn-sm">
                                <i class="fab fa-whatsapp"></i> WhatsApp
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Related Posts -->
                <?php if ($related_posts->num_rows > 0): ?>
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-newspaper text-primary"></i> Related Articles</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <?php while ($related = $related_posts->fetch_assoc()): ?>
                            <div class="col-md-4">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <h6 class="card-title">
                                            <a href="blog_post.php?id=<?php echo $related['id']; ?>" class="text-decoration-none">
                                                <?php echo htmlspecialchars($related['title']); ?>
                                            </a>
                                        </h6>
                                        <p class="card-text small text-muted">
                                            <?php echo htmlspecialchars(substr($related['excerpt'] ?: strip_tags($related['content']), 0, 80) . '...'); ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <?php endwhile; ?>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-dark text-light py-4 mt-5">
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
                        <li><a href="contact.php" class="text-light">Contact</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h5>Contact Info</h5>
                    <p><i class="fas fa-envelope"></i> visit@salemdominionministries.com</p>
                </div>
            </div>
            <hr>
            <p class="text-center mb-0">&copy; 2026 Salem Dominion Ministries. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>