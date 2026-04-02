&lt;?php
session_start();
require_once 'db.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if (!$id) {
    header('Location: news.php');
    exit;
}

// Get news article
$stmt = $db-&gt;prepare("SELECT n.*, u.username FROM news n LEFT JOIN users u ON n.author_id = u.id WHERE n.id = ?");
$stmt-&gt;bind_param('i', $id);
$stmt-&gt;execute();
$result = $stmt-&gt;get_result();

if ($result-&gt;num_rows === 0) {
    header('Location: news.php');
    exit;
}

$news = $result-&gt;fetch_assoc();

// Update view count
$db-&gt;query("UPDATE news SET views = views + 1 WHERE id = $id");

// Get related news (same category, excluding current)
$related_news = $db-&gt;query("SELECT id, title, excerpt, created_at FROM news WHERE category = '{$news['category']}' AND id != $id ORDER BY created_at DESC LIMIT 3");
?&gt;
&lt;!DOCTYPE html&gt;
&lt;html lang="en"&gt;
&lt;head&gt;
    &lt;meta charset="UTF-8"&gt;
    &lt;meta name="viewport" content="width=device-width, initial-scale=1.0"&gt;
    &lt;title&gt;&lt;?php echo htmlspecialchars($news['title']); ?&gt; - Salem Dominion Ministries&lt;/title&gt;
    &lt;meta name="description" content="&lt;?php echo htmlspecialchars($news['excerpt'] ?: substr(strip_tags($news['content']), 0, 160)); ?&gt;"&gt;
    &lt;link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"&gt;
    &lt;link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet"&gt;
    &lt;style&gt;
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
                    &lt;li class="nav-item"&gt;&lt;a class="nav-link active" href="news.php"&gt;News&lt;/a&gt;&lt;/li&gt;
                    &lt;li class="nav-item"&gt;&lt;a class="nav-link" href="gallery.php"&gt;Gallery&lt;/a&gt;&lt;/li&gt;
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

    &lt;!-- Article Header --&gt;
    &lt;section class="article-header"&gt;
        &lt;div class="container"&gt;
            &lt;div class="row"&gt;
                &lt;div class="col-lg-8 mx-auto text-center"&gt;
                    &lt;span class="badge bg-primary mb-3"&gt;&lt;?php echo ucfirst(htmlspecialchars($news['category'])); ?&gt;&lt;/span&gt;
                    &lt;h1 class="display-5 fw-bold mb-4"&gt;&lt;?php echo htmlspecialchars($news['title']); ?&gt;&lt;/h1&gt;
                    &lt;?php if ($news['excerpt']): ?&gt;
                        &lt;p class="lead"&gt;&lt;?php echo htmlspecialchars($news['excerpt']); ?&gt;&lt;/p&gt;
                    &lt;?php endif; ?&gt;
                &lt;/div&gt;
            &lt;/div&gt;
        &lt;/div&gt;
    &lt;/section&gt;

    &lt;!-- Article Content --&gt;
    &lt;section class="py-5"&gt;
        &lt;div class="container"&gt;
            &lt;div class="row"&gt;
                &lt;div class="col-lg-8 mx-auto"&gt;
                    &lt;div class="card article-content"&gt;
                        &lt;div class="card-body p-5"&gt;
                            &lt;!-- Article Meta --&gt;
                            &lt;div class="article-meta"&gt;
                                &lt;div class="row align-items-center"&gt;
                                    &lt;div class="col-md-6"&gt;
                                        &lt;small class="text-muted"&gt;
                                            &lt;i class="fas fa-user"&gt;&lt;/i&gt; By &lt;?php echo htmlspecialchars($news['username'] ?: 'Church Staff'); ?&gt;&lt;br&gt;
                                            &lt;i class="fas fa-calendar"&gt;&lt;/i&gt; &lt;?php echo date('F j, Y \a\t g:i A', strtotime($news['created_at'])); ?&gt;
                                        &lt;/small&gt;
                                    &lt;/div&gt;
                                    &lt;div class="col-md-6 text-end"&gt;
                                        &lt;small class="text-muted"&gt;
                                            &lt;i class="fas fa-eye"&gt;&lt;/i&gt; &lt;?php echo $news['views']; ?&gt; views
                                        &lt;/small&gt;
                                    &lt;/div&gt;
                                &lt;/div&gt;
                            &lt;/div&gt;

                            &lt;!-- Article Body --&gt;
                            &lt;div class="article-body"&gt;
                                &lt;?php echo nl2br(htmlspecialchars($news['content'])); ?&gt;
                            &lt;/div&gt;

                            &lt;!-- Share Buttons --&gt;
                            &lt;div class="share-buttons mt-4 pt-4 border-top"&gt;
                                &lt;h6 class="mb-3"&gt;&lt;i class="fas fa-share-alt"&gt;&lt;/i&gt; Share this article&lt;/h6&gt;
                                &lt;a href="https://www.facebook.com/sharer/sharer.php?u=&lt;?php echo urlencode('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']); ?&gt;" target="_blank" class="btn btn-outline-primary"&gt;
                                    &lt;i class="fab fa-facebook-f"&gt;&lt;/i&gt; Facebook
                                &lt;/a&gt;
                                &lt;a href="https://twitter.com/intent/tweet?url=&lt;?php echo urlencode('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']); ?&gt;&amp;text=&lt;?php echo urlencode($news['title']); ?&gt;" target="_blank" class="btn btn-outline-info"&gt;
                                    &lt;i class="fab fa-twitter"&gt;&lt;/i&gt; Twitter
                                &lt;/a&gt;
                                &lt;a href="https://wa.me/?text=&lt;?php echo urlencode($news['title'] . ' - ' . 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']); ?&gt;" target="_blank" class="btn btn-outline-success"&gt;
                                    &lt;i class="fab fa-whatsapp"&gt;&lt;/i&gt; WhatsApp
                                &lt;/a&gt;
                                &lt;button onclick="navigator.share({title: '&lt;?php echo htmlspecialchars($news['title']); ?&gt;', url: window.location.href})" class="btn btn-outline-secondary"&gt;
                                    &lt;i class="fas fa-share"&gt;&lt;/i&gt; Share
                                &lt;/button&gt;
                            &lt;/div&gt;
                        &lt;/div&gt;
                    &lt;/div&gt;

                    &lt;!-- Related Articles --&gt;
                    &lt;?php if ($related_news-&gt;num_rows &gt; 0): ?&gt;
                        &lt;div class="mt-5"&gt;
                            &lt;h4 class="mb-4"&gt;&lt;i class="fas fa-newspaper text-primary"&gt;&lt;/i&gt; Related Articles&lt;/h4&gt;
                            &lt;div class="row g-3"&gt;
                                &lt;?php while ($related = $related_news-&gt;fetch_assoc()): ?&gt;
                                    &lt;div class="col-md-4"&gt;
                                        &lt;div class="card related-article h-100"&gt;
                                            &lt;div class="card-body"&gt;
                                                &lt;h6 class="card-title"&gt;
                                                    &lt;a href="news_article.php?id=&lt;?php echo $related['id']; ?&gt;" class="text-decoration-none"&gt;
                                                        &lt;?php echo htmlspecialchars($related['title']); ?&gt;
                                                    &lt;/a&gt;
                                                &lt;/h6&gt;
                                                &lt;p class="card-text small text-muted"&gt;
                                                    &lt;?php echo htmlspecialchars(substr($related['excerpt'] ?: strip_tags($related['content']), 0, 80) . '...'); ?&gt;
                                                &lt;/p&gt;
                                                &lt;small class="text-muted"&gt;
                                                    &lt;i class="fas fa-calendar"&gt;&lt;/i&gt; &lt;?php echo date('M j, Y', strtotime($related['created_at'])); ?&gt;
                                                &lt;/small&gt;
                                            &lt;/div&gt;
                                        &lt;/div&gt;
                                    &lt;/div&gt;
                                &lt;?php endwhile; ?&gt;
                            &lt;/div&gt;
                        &lt;/div&gt;
                    &lt;?php endif; ?&gt;

                    &lt;!-- Back to News --&gt;
                    &lt;div class="text-center mt-4"&gt;
                        &lt;a href="news.php" class="btn btn-outline-primary"&gt;
                            &lt;i class="fas fa-arrow-left"&gt;&lt;/i&gt; Back to News
                        &lt;/a&gt;
                    &lt;/div&gt;
                &lt;/div&gt;
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

    &lt;script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"&gt;&lt;/script&gt;
&lt;/body&gt;
&lt;/html&gt;