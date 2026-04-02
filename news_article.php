<?php
session_start();
require_once 'db.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if (!$id) {
    header('Location: news.php');
    exit;
}

// Get news article
$stmt = $db->prepare("SELECT n.*, u.username FROM news n LEFT JOIN users u ON n.author_id = u.id WHERE n.id = ?");
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header('Location: news.php');
    exit;
}

$news = $result->fetch_assoc();

// Update view count
$db->query("UPDATE news SET views = views + 1 WHERE id = $id");

// Get related news (same category, excluding current)
$related_news = $db->query("SELECT id, title, excerpt, created_at FROM news WHERE category = '{$news['category']}' AND id != $id ORDER BY created_at DESC LIMIT 3");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($news['title']); ?> - Salem Dominion Ministries</title>
    <meta name="description" content="<?php echo htmlspecialchars($news['excerpt'] ?: substr(strip_tags($news['content']), 0, 160)); ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .navbar-brand {
            font-weight: bold;
            font-size: 1.5rem;
        }
        .article-header {
            background: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)), url('https://images.unsplash.com/photo-1504711434969-e33886168f5c?ixlib=rb-4.0.3');
            background-size: cover;
            background-position: center;
            color: white;
            padding: 80px 0;
        }
        .article-content {
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            border: none;
            border-radius: 15px;
        }
        .article-meta {
            border-bottom: 1px solid #dee2e6;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .related-article {
            transition: transform 0.3s ease;
        }
        .related-article:hover {
            transform: translateY(-5px);
        }
        .share-buttons .btn {
            margin-right: 10px;
            margin-bottom: 10px;
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
                    <li class="nav-item"><a class="nav-link active" href="news.php">News</a></li>
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

    <!-- Article Header -->
    <section class="article-header">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto text-center">
                    <span class="badge bg-primary mb-3"><?php echo ucfirst(htmlspecialchars($news['category'])); ?></span>
                    <h1 class="display-5 fw-bold mb-4"><?php echo htmlspecialchars($news['title']); ?></h1>
                    <?php if ($news['excerpt']): ?>
                        <p class="lead"><?php echo htmlspecialchars($news['excerpt']); ?></p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <!-- Article Content -->
    <section class="py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <div class="card article-content">
                        <div class="card-body p-5">
                            <!-- Article Meta -->
                            <div class="article-meta">
                                <div class="row align-items-center">
                                    <div class="col-md-6">
                                        <small class="text-muted">
                                            <i class="fas fa-user"></i> By <?php echo htmlspecialchars($news['username'] ?: 'Church Staff'); ?><br>
                                            <i class="fas fa-calendar"></i> <?php echo date('F j, Y \a\t g:i A', strtotime($news['created_at'])); ?>
                                        </small>
                                    </div>
                                    <div class="col-md-6 text-end">
                                        <small class="text-muted">
                                            <i class="fas fa-eye"></i> <?php echo $news['views']; ?> views
                                        </small>
                                    </div>
                                </div>
                            </div>

                            <!-- Article Body -->
                            <div class="article-body">
                                <?php echo nl2br(htmlspecialchars($news['content'])); ?>
                            </div>

                            <!-- Share Buttons -->
                            <div class="share-buttons mt-4 pt-4 border-top">
                                <h6 class="mb-3"><i class="fas fa-share-alt"></i> Share this article</h6>
                                <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']); ?>" target="_blank" class="btn btn-outline-primary">
                                    <i class="fab fa-facebook-f"></i> Facebook
                                </a>
                                <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']); ?>&text=<?php echo urlencode($news['title']); ?>" target="_blank" class="btn btn-outline-info">
                                    <i class="fab fa-twitter"></i> Twitter
                                </a>
                                <a href="https://wa.me/?text=<?php echo urlencode($news['title'] . ' - ' . 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']); ?>" target="_blank" class="btn btn-outline-success">
                                    <i class="fab fa-whatsapp"></i> WhatsApp
                                </a>
                                <button onclick="navigator.share({title: '<?php echo htmlspecialchars($news['title']); ?>', url: window.location.href})" class="btn btn-outline-secondary">
                                    <i class="fas fa-share"></i> Share
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Related Articles -->
                    <?php if ($related_news->num_rows > 0): ?>
                        <div class="mt-5">
                            <h4 class="mb-4"><i class="fas fa-newspaper text-primary"></i> Related Articles</h4>
                            <div class="row g-3">
                                <?php while ($related = $related_news->fetch_assoc()): ?>
                                    <div class="col-md-4">
                                        <div class="card related-article h-100">
                                            <div class="card-body">
                                                <h6 class="card-title">
                                                    <a href="news_article.php?id=<?php echo $related['id']; ?>" class="text-decoration-none">
                                                        <?php echo htmlspecialchars($related['title']); ?>
                                                    </a>
                                                </h6>
                                                <p class="card-text small text-muted">
                                                    <?php echo htmlspecialchars(substr($related['excerpt'] ?: strip_tags($related['content']), 0, 80) . '...'); ?>
                                                </p>
                                                <small class="text-muted">
                                                    <i class="fas fa-calendar"></i> <?php echo date('M j, Y', strtotime($related['created_at'])); ?>
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                <?php endwhile; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Back to News -->
                    <div class="text-center mt-4">
                        <a href="news.php" class="btn btn-outline-primary">
                            <i class="fas fa-arrow-left"></i> Back to News
                        </a>
                    </div>
                </div>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>