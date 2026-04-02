<?php
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
                $stmt = $db->prepare("INSERT INTO news (title, excerpt, content, category, author_id, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
                $stmt->bind_param('sssss', $title, $excerpt, $content, $category, $author_id);
                if ($stmt->execute()) {
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
                $stmt = $db->prepare("UPDATE news SET title = ?, excerpt = ?, content = ?, category = ? WHERE id = ?");
                $stmt->bind_param('ssssi', $title, $excerpt, $content, $category, $id);
                if ($stmt->execute()) {
                    $message = 'News article updated successfully.';
                    $message_type = 'success';
                } else {
                    $message = 'Error updating news article.';
                    $message_type = 'error';
                }
            }
        } elseif ($action === 'delete_news') {
            $id = intval($_POST['news_id']);
            $stmt = $db->prepare("DELETE FROM news WHERE id = ?");
            $stmt->bind_param('i', $id);
            if ($stmt->execute()) {
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
$news_result = $db->query("SELECT n.*, u.username FROM news n LEFT JOIN users u ON n.author_id = u.id ORDER BY n.created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>News Management - Salem Dominion Ministries</title>
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
        .news-card {
            transition: transform 0.2s ease;
        }
        .news-card:hover {
            transform: translateY(-2px);
        }
        .modal-lg {
            max-width: 900px;
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
                        <a class="nav-link active" href="admin_news.php">
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
                        <a class="nav-link" href="admin_gallery.php">
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
                    <h2><i class="fas fa-newspaper text-primary"></i> News Management</h2>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createNewsModal">
                        <i class="fas fa-plus"></i> Add News Article
                    </button>
                </div>

                <?php if ($message): ?>
                    <div class="alert alert-<?php echo $message_type === 'success' ? 'success' : 'danger'; ?> alert-dismissible fade show" role="alert">
                        <?php echo htmlspecialchars($message); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <!-- News Articles -->
                <div class="row">
                    <?php if ($news_result->num_rows > 0): ?>
                        <?php while ($news = $news_result->fetch_assoc()): ?>
                            <div class="col-lg-6 mb-4">
                                <div class="card news-card h-100">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <h5 class="card-title mb-0"><?php echo htmlspecialchars($news['title']); ?></h5>
                                            <span class="badge bg-<?php echo $news['category'] === 'announcement' ? 'primary' : ($news['category'] === 'event' ? 'success' : 'info'); ?>">
                                                <?php echo ucfirst($news['category']); ?>
                                            </span>
                                        </div>
                                        <?php if ($news['excerpt']): ?>
                                            <p class="card-text text-muted"><?php echo htmlspecialchars(substr($news['excerpt'], 0, 100)) . (strlen($news['excerpt']) > 100 ? '...' : ''); ?></p>
                                        <?php endif; ?>
                                        <div class="d-flex justify-content-between align-items-center text-muted small mb-3">
                                            <span><i class="fas fa-user"></i> <?php echo htmlspecialchars($news['username'] ?: 'Church Staff'); ?></span>
                                            <span><i class="fas fa-eye"></i> <?php echo $news['views']; ?> views</span>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <small class="text-muted"><?php echo date('M j, Y g:i A', strtotime($news['created_at'])); ?></small>
                                            <div class="btn-group" role="group">
                                                <button class="btn btn-outline-primary btn-sm" onclick="editNews(<?php echo $news['id']; ?>, '<?php echo htmlspecialchars(addslashes($news['title'])); ?>', '<?php echo htmlspecialchars(addslashes($news['excerpt'])); ?>', '<?php echo htmlspecialchars(addslashes($news['content'])); ?>', '<?php echo $news['category']; ?>')">
                                                    <i class="fas fa-edit"></i> Edit
                                                </button>
                                                <form method="post" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this news article?')">
                                                    <input type="hidden" name="action" value="delete_news">
                                                    <input type="hidden" name="news_id" value="<?php echo $news['id']; ?>">
                                                    <button type="submit" class="btn btn-outline-danger btn-sm">
                                                        <i class="fas fa-trash"></i> Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <div class="col-12">
                            <div class="text-center py-5">
                                <i class="fas fa-newspaper fa-3x text-muted mb-3"></i>
                                <h4 class="text-muted">No News Articles Yet</h4>
                                <p class="text-muted">Create your first news article to get started.</p>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Create News Modal -->
    <div class="modal fade" id="createNewsModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-plus"></i> Create News Article</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="post">
                    <div class="modal-body">
                        <input type="hidden" name="action" value="create_news">
                        <div class="mb-3">
                            <label for="title" class="form-label">Title *</label>
                            <input type="text" class="form-control" id="title" name="title" required>
                        </div>
                        <div class="mb-3">
                            <label for="excerpt" class="form-label">Excerpt (Optional)</label>
                            <textarea class="form-control" id="excerpt" name="excerpt" rows="2"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="category" class="form-label">Category</label>
                            <select class="form-select" id="category" name="category">
                                <option value="general">General</option>
                                <option value="announcement">Announcement</option>
                                <option value="event">Event</option>
                                <option value="ministry">Ministry</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="content" class="form-label">Content *</label>
                            <textarea class="form-control" id="content" name="content" rows="10" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Create Article</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit News Modal -->
    <div class="modal fade" id="editNewsModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-edit"></i> Edit News Article</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="post">
                    <div class="modal-body">
                        <input type="hidden" name="action" value="update_news">
                        <input type="hidden" name="news_id" id="edit_news_id">
                        <div class="mb-3">
                            <label for="edit_title" class="form-label">Title *</label>
                            <input type="text" class="form-control" id="edit_title" name="title" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_excerpt" class="form-label">Excerpt (Optional)</label>
                            <textarea class="form-control" id="edit_excerpt" name="excerpt" rows="2"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="edit_category" class="form-label">Category</label>
                            <select class="form-select" id="edit_category" name="category">
                                <option value="general">General</option>
                                <option value="announcement">Announcement</option>
                                <option value="event">Event</option>
                                <option value="ministry">Ministry</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="edit_content" class="form-label">Content *</label>
                            <textarea class="form-control" id="edit_content" name="content" rows="10" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Update Article</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function editNews(id, title, excerpt, content, category) {
            document.getElementById('edit_news_id').value = id;
            document.getElementById('edit_title').value = title;
            document.getElementById('edit_excerpt').value = excerpt;
            document.getElementById('edit_content').value = content;
            document.getElementById('edit_category').value = category;
            
            new bootstrap.Modal(document.getElementById('editNewsModal')).show();
        }
    </script>
</body>
</html>