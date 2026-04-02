<?php
session_start();
require_once 'db.php';

$post_id = $_GET['id'] ?? 0;
$post = $db->query("SELECT bp.*, u.first_name, u.last_name, u.avatar_url FROM blog_posts bp JOIN users u ON bp.author_id = u.id WHERE bp.id = $post_id AND bp.status = 'published'")->fetch_assoc();

if (!$post) {
    header('Location: index.php');
    exit;
}

// Handle comment submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_comment'])) {
    if (!isset($_SESSION['user_id'])) {
        header('Location: login.php');
        exit;
    }

    $comment = trim($_POST['comment']);
    $user_id = $_SESSION['user_id'];
    $author_name = $_SESSION['user_name'];

    if (!empty($comment)) {
        $stmt = $db->prepare("INSERT INTO blog_comments (post_id, user_id, author_name, comment) VALUES (?, ?, ?, ?)");
        $stmt->bind_param('iiss', $post_id, $user_id, $author_name, $comment);
        $stmt->execute();
        $stmt->close();

        header("Location: blog_post.php?id=$post_id&success=comment_added");
        exit;
    }
}

// Get comments
$comments = $db->query("SELECT bc.*, u.avatar_url FROM blog_comments bc LEFT JOIN users u ON bc.user_id = u.id WHERE bc.post_id = $post_id AND bc.is_approved = 1 ORDER BY bc.created_at DESC");

// Update view count
$db->query("UPDATE blog_posts SET views_count = views_count + 1 WHERE id = $post_id");
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
                    <li class="nav-item"><a class="nav-link" href="news.php">News</a></li>
                    <li class="nav-item"><a class="nav-link active" href="blog.php">Blog</a></li>
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
                                <strong><?php echo htmlspecialchars($post['first_name'] . ' ' . $post['last_name']); ?></strong>
                                <br><small class="text-muted"><?php echo date('F j, Y \a\t g:i A', strtotime($post['created_at'])); ?></small>
                            </div>
                        </div>
                        <?php if ($post['category']): ?>
                        <span class="badge bg-primary mb-3"><?php echo htmlspecialchars($post['category']); ?></span>
                        <?php endif; ?>
                        <div class="post-content">
                            <?php echo nl2br(htmlspecialchars($post['content'])); ?>
                        </div>
                    </div>
                </div>

                <!-- Comments Section -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-comments"></i> Comments (<?php echo $comments->num_rows; ?>)</h5>
                    </div>
                    <div class="card-body">
                        <?php if (isset($_SESSION['user_id'])): ?>
                        <!-- Comment Form -->
                        <form method="POST" class="mb-4">
                            <div class="mb-3">
                                <label class="form-label">Add a Comment</label>
                                <textarea class="form-control" name="comment" rows="3" required></textarea>
                            </div>
                            <button type="submit" name="submit_comment" class="btn btn-primary"><i class="fas fa-paper-plane"></i> Post Comment</button>
                        </form>
                        <?php else: ?>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> <a href="login.php" class="alert-link">Login</a> to leave a comment.
                        </div>
                        <?php endif; ?>

                        <!-- Comments List -->
                        <div class="comments-list">
                            <?php if ($comments->num_rows > 0): ?>
                                <?php while ($comment = $comments->fetch_assoc()): ?>
                                <div class="comment mb-3 p-3 border rounded">
                                    <div class="d-flex align-items-start">
                                        <?php if ($comment['avatar_url']): ?>
                                        <img src="<?php echo htmlspecialchars($comment['avatar_url']); ?>" alt="Commenter Avatar" class="comment-avatar me-3">
                                        <?php else: ?>
                                        <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 32px; height: 32px;">
                                            <i class="fas fa-user"></i>
                                        </div>
                                        <?php endif; ?>
                                        <div class="flex-grow-1">
                                            <strong><?php echo htmlspecialchars($comment['author_name']); ?></strong>
                                            <small class="text-muted ms-2"><?php echo date('M j, Y \a\t g:i A', strtotime($comment['created_at'])); ?></small>
                                            <div class="mt-2">
                                                <?php echo nl2br(htmlspecialchars($comment['comment'])); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <p class="text-muted">No comments yet. Be the first to comment!</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
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