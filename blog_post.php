&lt;?php
session_start();
require_once 'db.php';

$post_id = $_GET['id'] ?? 0;
$post = $db-&gt;query("SELECT bp.*, u.first_name, u.last_name, u.avatar_url FROM blog_posts bp JOIN users u ON bp.author_id = u.id WHERE bp.id = $post_id AND bp.status = 'published'")-&gt;fetch_assoc();

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
        $stmt = $db-&gt;prepare("INSERT INTO blog_comments (post_id, user_id, author_name, comment) VALUES (?, ?, ?, ?)");
        $stmt-&gt;bind_param('iiss', $post_id, $user_id, $author_name, $comment);
        $stmt-&gt;execute();
        $stmt-&gt;close();

        header("Location: blog_post.php?id=$post_id&success=comment_added");
        exit;
    }
}

// Get comments
$comments = $db-&gt;query("SELECT bc.*, u.avatar_url FROM blog_comments bc LEFT JOIN users u ON bc.user_id = u.id WHERE bc.post_id = $post_id AND bc.is_approved = 1 ORDER BY bc.created_at DESC");

// Update view count
$db-&gt;query("UPDATE blog_posts SET views_count = views_count + 1 WHERE id = $post_id");
?&gt;
&lt;!DOCTYPE html&gt;
&lt;html lang="en"&gt;
&lt;head&gt;
    &lt;meta charset="UTF-8"&gt;
    &lt;meta name="viewport" content="width=device-width, initial-scale=1.0"&gt;
    &lt;title&gt;&lt;?php echo htmlspecialchars($post['title']); ?&gt; - Salem Dominion Ministries&lt;/title&gt;
    &lt;link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"&gt;
    &lt;link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet"&gt;
    &lt;style&gt;
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
                    &lt;li class="nav-item"&gt;&lt;a class="nav-link" href="news.php"&gt;News&lt;/a&gt;&lt;/li&gt;
                    &lt;li class="nav-item"&gt;&lt;a class="nav-link active" href="blog.php"&gt;Blog&lt;/a&gt;&lt;/li&gt;
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

    &lt;!-- Post Content --&gt;
    &lt;div class="container my-4"&gt;
        &lt;?php if (isset($_GET['success']) && $_GET['success'] === 'comment_added'): ?&gt;
            &lt;div class="alert alert-success alert-dismissible fade show" role="alert"&gt;
                Comment added successfully!
                &lt;button type="button" class="btn-close" data-bs-dismiss="alert"&gt;&lt;/button&gt;
            &lt;/div&gt;
        &lt;?php endif; ?&gt;

        &lt;div class="row"&gt;
            &lt;div class="col-lg-8 mx-auto"&gt;
                &lt;!-- Post Header --&gt;
                &lt;div class="card mb-4"&gt;
                    &lt;?php if ($post['featured_image_url']): ?&gt;
                    &lt;img src="&lt;?php echo htmlspecialchars($post['featured_image_url']); ?&gt;" class="card-img-top post-image" alt="Post Image"&gt;
                    &lt;?php endif; ?&gt;
                    &lt;div class="card-body"&gt;
                        &lt;h1 class="card-title"&gt;&lt;?php echo htmlspecialchars($post['title']); ?&gt;&lt;/h1&gt;
                        &lt;div class="d-flex align-items-center mb-3"&gt;
                            &lt;?php if ($post['avatar_url']): ?&gt;
                            &lt;img src="&lt;?php echo htmlspecialchars($post['avatar_url']); ?&gt;" alt="Author Avatar" class="avatar me-2"&gt;
                            &lt;?php else: ?&gt;
                            &lt;div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 40px; height: 40px;"&gt;
                                &lt;i class="fas fa-user"&gt;&lt;/i&gt;
                            &lt;/div&gt;
                            &lt;?php endif; ?&gt;
                            &lt;div&gt;
                                &lt;strong&gt;&lt;?php echo htmlspecialchars($post['first_name'] . ' ' . $post['last_name']); ?&gt;&lt;/strong&gt;
                                &lt;br&gt;&lt;small class="text-muted"&gt;&lt;?php echo date('F j, Y \a\t g:i A', strtotime($post['created_at'])); ?&gt;&lt;/small&gt;
                            &lt;/div&gt;
                        &lt;/div&gt;
                        &lt;?php if ($post['category']): ?&gt;
                        &lt;span class="badge bg-primary mb-3"&gt;&lt;?php echo htmlspecialchars($post['category']); ?&gt;&lt;/span&gt;
                        &lt;?php endif; ?&gt;
                        &lt;div class="post-content"&gt;
                            &lt;?php echo nl2br(htmlspecialchars($post['content'])); ?&gt;
                        &lt;/div&gt;
                    &lt;/div&gt;
                &lt;/div&gt;

                &lt;!-- Comments Section --&gt;
                &lt;div class="card"&gt;
                    &lt;div class="card-header"&gt;
                        &lt;h5 class="mb-0"&gt;&lt;i class="fas fa-comments"&gt;&lt;/i&gt; Comments (&lt;?php echo $comments-&gt;num_rows; ?&gt;)&lt;/h5&gt;
                    &lt;/div&gt;
                    &lt;div class="card-body"&gt;
                        &lt;?php if (isset($_SESSION['user_id'])): ?&gt;
                        &lt;!-- Comment Form --&gt;
                        &lt;form method="POST" class="mb-4"&gt;
                            &lt;div class="mb-3"&gt;
                                &lt;label class="form-label"&gt;Add a Comment&lt;/label&gt;
                                &lt;textarea class="form-control" name="comment" rows="3" required&gt;&lt;/textarea&gt;
                            &lt;/div&gt;
                            &lt;button type="submit" name="submit_comment" class="btn btn-primary"&gt;&lt;i class="fas fa-paper-plane"&gt;&lt;/i&gt; Post Comment&lt;/button&gt;
                        &lt;/form&gt;
                        &lt;?php else: ?&gt;
                        &lt;div class="alert alert-info"&gt;
                            &lt;i class="fas fa-info-circle"&gt;&lt;/i&gt; &lt;a href="login.php" class="alert-link"&gt;Login&lt;/a&gt; to leave a comment.
                        &lt;/div&gt;
                        &lt;?php endif; ?&gt;

                        &lt;!-- Comments List --&gt;
                        &lt;div class="comments-list"&gt;
                            &lt;?php if ($comments-&gt;num_rows > 0): ?&gt;
                                &lt;?php while ($comment = $comments-&gt;fetch_assoc()): ?&gt;
                                &lt;div class="comment mb-3 p-3 border rounded"&gt;
                                    &lt;div class="d-flex align-items-start"&gt;
                                        &lt;?php if ($comment['avatar_url']): ?&gt;
                                        &lt;img src="&lt;?php echo htmlspecialchars($comment['avatar_url']); ?&gt;" alt="Commenter Avatar" class="comment-avatar me-3"&gt;
                                        &lt;?php else: ?&gt;
                                        &lt;div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 32px; height: 32px;"&gt;
                                            &lt;i class="fas fa-user"&gt;&lt;/i&gt;
                                        &lt;/div&gt;
                                        &lt;?php endif; ?&gt;
                                        &lt;div class="flex-grow-1"&gt;
                                            &lt;strong&gt;&lt;?php echo htmlspecialchars($comment['author_name']); ?&gt;&lt;/strong&gt;
                                            &lt;small class="text-muted ms-2"&gt;&lt;?php echo date('M j, Y \a\t g:i A', strtotime($comment['created_at'])); ?&gt;&lt;/small&gt;
                                            &lt;div class="mt-2"&gt;
                                                &lt;?php echo nl2br(htmlspecialchars($comment['comment'])); ?&gt;
                                            &lt;/div&gt;
                                        &lt;/div&gt;
                                    &lt;/div&gt;
                                &lt;/div&gt;
                                &lt;?php endwhile; ?&gt;
                            &lt;?php else: ?&gt;
                                &lt;p class="text-muted"&gt;No comments yet. Be the first to comment!&lt;/p&gt;
                            &lt;?php endif; ?&gt;
                        &lt;/div&gt;
                    &lt;/div&gt;
                &lt;/div&gt;
            &lt;/div&gt;
        &lt;/div&gt;
    &lt;/div&gt;

    &lt;!-- Footer --&gt;
    &lt;footer class="bg-dark text-light py-4 mt-5"&gt;
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
                        &lt;li&gt;&lt;a href="contact.php" class="text-light"&gt;Contact&lt;/a&gt;&lt;/li&gt;
                    &lt;/ul&gt;
                &lt;/div&gt;
                &lt;div class="col-md-4"&gt;
                    &lt;h5&gt;Contact Info&lt;/h5&gt;
                    &lt;p&gt;&lt;i class="fas fa-envelope"&gt;&lt;/i&gt; visit@salemdominionministries.com&lt;/p&gt;
                &lt;/div&gt;
            &lt;/div&gt;
            &lt;hr&gt;
            &lt;p class="text-center mb-0"&gt;&amp;copy; 2026 Salem Dominion Ministries. All rights reserved.&lt;/p&gt;
        &lt;/div&gt;
    &lt;/footer&gt;

    &lt;script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"&gt;&lt;/script&gt;
&lt;/body&gt;
&lt;/html&gt;