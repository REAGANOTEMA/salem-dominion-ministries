&lt;?php
session_start();
require_once 'db.php';

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

// Handle form submissions
$message = '';
$message_type = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];

        if ($action === 'create_news') {
            $title = trim($_POST['title']);
            $excerpt = trim($_POST['excerpt']);
            $content = trim($_POST['content']);
            $category = $_POST['category'];
            $author_id = $_SESSION['user_id'];

            if (empty($title) || empty($content)) {
                $message = 'Title and content are required.';
                $message_type = 'error';
            } else {
                $stmt = $db-&gt;prepare("INSERT INTO news (title, excerpt, content, category, author_id, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
                $stmt-&gt;bind_param('sssss', $title, $excerpt, $content, $category, $author_id);
                if ($stmt-&gt;execute()) {
                    $message = 'News article created successfully.';
                    $message_type = 'success';
                } else {
                    $message = 'Error creating news article.';
                    $message_type = 'error';
                }
            }
        } elseif ($action === 'update_news') {
            $id = intval($_POST['news_id']);
            $title = trim($_POST['title']);
            $excerpt = trim($_POST['excerpt']);
            $content = trim($_POST['content']);
            $category = $_POST['category'];

            if (empty($title) || empty($content)) {
                $message = 'Title and content are required.';
                $message_type = 'error';
            } else {
                $stmt = $db-&gt;prepare("UPDATE news SET title = ?, excerpt = ?, content = ?, category = ? WHERE id = ?");
                $stmt-&gt;bind_param('ssssi', $title, $excerpt, $content, $category, $id);
                if ($stmt-&gt;execute()) {
                    $message = 'News article updated successfully.';
                    $message_type = 'success';
                } else {
                    $message = 'Error updating news article.';
                    $message_type = 'error';
                }
            }
        } elseif ($action === 'delete_news') {
            $id = intval($_POST['news_id']);
            $stmt = $db-&gt;prepare("DELETE FROM news WHERE id = ?");
            $stmt-&gt;bind_param('i', $id);
            if ($stmt-&gt;execute()) {
                $message = 'News article deleted successfully.';
                $message_type = 'success';
            } else {
                $message = 'Error deleting news article.';
                $message_type = 'error';
            }
        }
    }
}

// Get all news articles
$news_result = $db-&gt;query("SELECT n.*, u.username FROM news n LEFT JOIN users u ON n.author_id = u.id ORDER BY n.created_at DESC");
?&gt;
&lt;!DOCTYPE html&gt;
&lt;html lang="en"&gt;
&lt;head&gt;
    &lt;meta charset="UTF-8"&gt;
    &lt;meta name="viewport" content="width=device-width, initial-scale=1.0"&gt;
    &lt;title&gt;News Management - Salem Dominion Ministries&lt;/title&gt;
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
        .news-card {
            transition: transform 0.2s ease;
        }
        .news-card:hover {
            transform: translateY(-2px);
        }
        .modal-lg {
            max-width: 900px;
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
                        &lt;a class="nav-link active" href="admin_news.php"&gt;
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
                        &lt;a class="nav-link" href="admin_gallery.php"&gt;
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
                    &lt;h2&gt;&lt;i class="fas fa-newspaper text-primary"&gt;&lt;/i&gt; News Management&lt;/h2&gt;
                    &lt;button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createNewsModal"&gt;
                        &lt;i class="fas fa-plus"&gt;&lt;/i&gt; Add News Article
                    &lt;/button&gt;
                &lt;/div&gt;

                &lt;?php if ($message): ?&gt;
                    &lt;div class="alert alert-&lt;?php echo $message_type === 'success' ? 'success' : 'danger'; ?&gt; alert-dismissible fade show" role="alert"&gt;
                        &lt;?php echo htmlspecialchars($message); ?&gt;
                        &lt;button type="button" class="btn-close" data-bs-dismiss="alert"&gt;&lt;/button&gt;
                    &lt;/div&gt;
                &lt;?php endif; ?&gt;

                &lt;!-- News Articles --&gt;
                &lt;div class="row"&gt;
                    &lt;?php if ($news_result-&gt;num_rows &gt; 0): ?&gt;
                        &lt;?php while ($news = $news_result-&gt;fetch_assoc()): ?&gt;
                            &lt;div class="col-lg-6 mb-4"&gt;
                                &lt;div class="card news-card h-100"&gt;
                                    &lt;div class="card-body"&gt;
                                        &lt;div class="d-flex justify-content-between align-items-start mb-2"&gt;
                                            &lt;h5 class="card-title mb-0"&gt;&lt;?php echo htmlspecialchars($news['title']); ?&gt;&lt;/h5&gt;
                                            &lt;span class="badge bg-&lt;?php echo $news['category'] === 'announcement' ? 'primary' : ($news['category'] === 'event' ? 'success' : 'info'); ?&gt;"&gt;
                                                &lt;?php echo ucfirst($news['category']); ?&gt;
                                            &lt;/span&gt;
                                        &lt;/div&gt;
                                        &lt;?php if ($news['excerpt']): ?&gt;
                                            &lt;p class="card-text text-muted"&gt;&lt;?php echo htmlspecialchars(substr($news['excerpt'], 0, 100)) . (strlen($news['excerpt']) &gt; 100 ? '...' : ''); ?&gt;&lt;/p&gt;
                                        &lt;?php endif; ?&gt;
                                        &lt;div class="d-flex justify-content-between align-items-center text-muted small mb-3"&gt;
                                            &lt;span&gt;&lt;i class="fas fa-user"&gt;&lt;/i&gt; &lt;?php echo htmlspecialchars($news['username'] ?: 'Church Staff'); ?&gt;&lt;/span&gt;
                                            &lt;span&gt;&lt;i class="fas fa-eye"&gt;&lt;/i&gt; &lt;?php echo $news['views']; ?&gt; views&lt;/span&gt;
                                        &lt;/div&gt;
                                        &lt;div class="d-flex justify-content-between align-items-center"&gt;
                                            &lt;small class="text-muted"&gt;&lt;?php echo date('M j, Y g:i A', strtotime($news['created_at'])); ?&gt;&lt;/small&gt;
                                            &lt;div class="btn-group" role="group"&gt;
                                                &lt;button class="btn btn-outline-primary btn-sm" onclick="editNews(&lt;?php echo $news['id']; ?&gt;, '&lt;?php echo htmlspecialchars(addslashes($news['title'])); ?&gt;', '&lt;?php echo htmlspecialchars(addslashes($news['excerpt'])); ?&gt;', '&lt;?php echo htmlspecialchars(addslashes($news['content'])); ?&gt;', '&lt;?php echo $news['category']; ?&gt;')"&gt;
                                                    &lt;i class="fas fa-edit"&gt;&lt;/i&gt; Edit
                                                &lt;/button&gt;
                                                &lt;form method="post" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this news article?')"&gt;
                                                    &lt;input type="hidden" name="action" value="delete_news"&gt;
                                                    &lt;input type="hidden" name="news_id" value="&lt;?php echo $news['id']; ?&gt;"&gt;
                                                    &lt;button type="submit" class="btn btn-outline-danger btn-sm"&gt;
                                                        &lt;i class="fas fa-trash"&gt;&lt;/i&gt; Delete
                                                    &lt;/button&gt;
                                                &lt;/form&gt;
                                            &lt;/div&gt;
                                        &lt;/div&gt;
                                    &lt;/div&gt;
                                &lt;/div&gt;
                            &lt;/div&gt;
                        &lt;?php endwhile; ?&gt;
                    &lt;?php else: ?&gt;
                        &lt;div class="col-12"&gt;
                            &lt;div class="text-center py-5"&gt;
                                &lt;i class="fas fa-newspaper fa-3x text-muted mb-3"&gt;&lt;/i&gt;
                                &lt;h4 class="text-muted"&gt;No News Articles Yet&lt;/h4&gt;
                                &lt;p class="text-muted"&gt;Create your first news article to get started.&lt;/p&gt;
                            &lt;/div&gt;
                        &lt;/div&gt;
                    &lt;?php endif; ?&gt;
                &lt;/div&gt;
            &lt;/div&gt;
        &lt;/div&gt;
    &lt;/div&gt;

    &lt;!-- Create News Modal --&gt;
    &lt;div class="modal fade" id="createNewsModal" tabindex="-1"&gt;
        &lt;div class="modal-dialog modal-lg"&gt;
            &lt;div class="modal-content"&gt;
                &lt;div class="modal-header"&gt;
                    &lt;h5 class="modal-title"&gt;&lt;i class="fas fa-plus"&gt;&lt;/i&gt; Create News Article&lt;/h5&gt;
                    &lt;button type="button" class="btn-close" data-bs-dismiss="modal"&gt;&lt;/button&gt;
                &lt;/div&gt;
                &lt;form method="post"&gt;
                    &lt;div class="modal-body"&gt;
                        &lt;input type="hidden" name="action" value="create_news"&gt;
                        &lt;div class="mb-3"&gt;
                            &lt;label for="title" class="form-label"&gt;Title *&lt;/label&gt;
                            &lt;input type="text" class="form-control" id="title" name="title" required&gt;
                        &lt;/div&gt;
                        &lt;div class="mb-3"&gt;
                            &lt;label for="excerpt" class="form-label"&gt;Excerpt (Optional)&lt;/label&gt;
                            &lt;textarea class="form-control" id="excerpt" name="excerpt" rows="2"&gt;&lt;/textarea&gt;
                        &lt;/div&gt;
                        &lt;div class="mb-3"&gt;
                            &lt;label for="category" class="form-label"&gt;Category&lt;/label&gt;
                            &lt;select class="form-select" id="category" name="category"&gt;
                                &lt;option value="general"&gt;General&lt;/option&gt;
                                &lt;option value="announcement"&gt;Announcement&lt;/option&gt;
                                &lt;option value="event"&gt;Event&lt;/option&gt;
                                &lt;option value="ministry"&gt;Ministry&lt;/option&gt;
                            &lt;/select&gt;
                        &lt;/div&gt;
                        &lt;div class="mb-3"&gt;
                            &lt;label for="content" class="form-label"&gt;Content *&lt;/label&gt;
                            &lt;textarea class="form-control" id="content" name="content" rows="10" required&gt;&lt;/textarea&gt;
                        &lt;/div&gt;
                    &lt;/div&gt;
                    &lt;div class="modal-footer"&gt;
                        &lt;button type="button" class="btn btn-secondary" data-bs-dismiss="modal"&gt;Cancel&lt;/button&gt;
                        &lt;button type="submit" class="btn btn-primary"&gt;&lt;i class="fas fa-save"&gt;&lt;/i&gt; Create Article&lt;/button&gt;
                    &lt;/div&gt;
                &lt;/form&gt;
            &lt;/div&gt;
        &lt;/div&gt;
    &lt;/div&gt;

    &lt;!-- Edit News Modal --&gt;
    &lt;div class="modal fade" id="editNewsModal" tabindex="-1"&gt;
        &lt;div class="modal-dialog modal-lg"&gt;
            &lt;div class="modal-content"&gt;
                &lt;div class="modal-header"&gt;
                    &lt;h5 class="modal-title"&gt;&lt;i class="fas fa-edit"&gt;&lt;/i&gt; Edit News Article&lt;/h5&gt;
                    &lt;button type="button" class="btn-close" data-bs-dismiss="modal"&gt;&lt;/button&gt;
                &lt;/div&gt;
                &lt;form method="post"&gt;
                    &lt;div class="modal-body"&gt;
                        &lt;input type="hidden" name="action" value="update_news"&gt;
                        &lt;input type="hidden" name="news_id" id="edit_news_id"&gt;
                        &lt;div class="mb-3"&gt;
                            &lt;label for="edit_title" class="form-label"&gt;Title *&lt;/label&gt;
                            &lt;input type="text" class="form-control" id="edit_title" name="title" required&gt;
                        &lt;/div&gt;
                        &lt;div class="mb-3"&gt;
                            &lt;label for="edit_excerpt" class="form-label"&gt;Excerpt (Optional)&lt;/label&gt;
                            &lt;textarea class="form-control" id="edit_excerpt" name="excerpt" rows="2"&gt;&lt;/textarea&gt;
                        &lt;/div&gt;
                        &lt;div class="mb-3"&gt;
                            &lt;label for="edit_category" class="form-label"&gt;Category&lt;/label&gt;
                            &lt;select class="form-select" id="edit_category" name="category"&gt;
                                &lt;option value="general"&gt;General&lt;/option&gt;
                                &lt;option value="announcement"&gt;Announcement&lt;/option&gt;
                                &lt;option value="event"&gt;Event&lt;/option&gt;
                                &lt;option value="ministry"&gt;Ministry&lt;/option&gt;
                            &lt;/select&gt;
                        &lt;/div&gt;
                        &lt;div class="mb-3"&gt;
                            &lt;label for="edit_content" class="form-label"&gt;Content *&lt;/label&gt;
                            &lt;textarea class="form-control" id="edit_content" name="content" rows="10" required&gt;&lt;/textarea&gt;
                        &lt;/div&gt;
                    &lt;/div&gt;
                    &lt;div class="modal-footer"&gt;
                        &lt;button type="button" class="btn btn-secondary" data-bs-dismiss="modal"&gt;Cancel&lt;/button&gt;
                        &lt;button type="submit" class="btn btn-primary"&gt;&lt;i class="fas fa-save"&gt;&lt;/i&gt; Update Article&lt;/button&gt;
                    &lt;/div&gt;
                &lt;/form&gt;
            &lt;/div&gt;
        &lt;/div&gt;
    &lt;/div&gt;

    &lt;script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"&gt;&lt;/script&gt;
    &lt;script&gt;
        function editNews(id, title, excerpt, content, category) {
            document.getElementById('edit_news_id').value = id;
            document.getElementById('edit_title').value = title;
            document.getElementById('edit_excerpt').value = excerpt;
            document.getElementById('edit_content').value = content;
            document.getElementById('edit_category').value = category;
            
            new bootstrap.Modal(document.getElementById('editNewsModal')).show();
        }
    &lt;/script&gt;
&lt;/body&gt;
&lt;/html&gt;