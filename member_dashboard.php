<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'member') {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$user = $db->query("SELECT * FROM users WHERE id = $user_id")->fetch_assoc();

// Handle post creation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_post'])) {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    $category = trim($_POST['category']) ?: 'General';

    if (!empty($title) && !empty($content)) {
        // Handle image upload
        $image_url = null;
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $upload_dir = UPLOAD_DIR . 'blog/';
            if (!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);

            $file_name = uniqid() . '_' . basename($_FILES['image']['name']);
            $target_file = $upload_dir . $file_name;

            if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
                $image_url = 'uploads/blog/' . $file_name;
            }
        }

        $slug = strtolower(str_replace(' ', '-', preg_replace('/[^A-Za-z0-9\-]/', '', $title))) . '-' . time();

        $stmt = $db->prepare("INSERT INTO blog_posts (title, slug, content, featured_image_url, author_id, category, status) VALUES (?, ?, ?, ?, ?, ?, 'published')");
        $stmt->bind_param('ssssis', $title, $slug, $content, $image_url, $user_id, $category);
        $stmt->execute();
        $stmt->close();

        header('Location: member_dashboard.php?success=post_created');
        exit;
    }
}

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $phone = trim($_POST['phone']);

    if (!empty($first_name) && !empty($last_name)) {
        // Handle avatar upload
        $avatar_url = $user['avatar_url'];
        if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
            $upload_dir = UPLOAD_DIR . 'avatars/';
            if (!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);

            $file_name = uniqid() . '_' . basename($_FILES['avatar']['name']);
            $target_file = $upload_dir . $file_name;

            if (move_uploaded_file($_FILES['avatar']['tmp_name'], $target_file)) {
                $avatar_url = 'uploads/avatars/' . $file_name;
            }
        }

        $stmt = $db->prepare("UPDATE users SET first_name = ?, last_name = ?, phone = ?, avatar_url = ? WHERE id = ?");
        $stmt->bind_param('ssssi', $first_name, $last_name, $phone, $avatar_url, $user_id);
        $stmt->execute();
        $stmt->close();

        $_SESSION['user_name'] = $first_name . ' ' . $last_name;
        header('Location: member_dashboard.php?success=profile_updated');
        exit;
    }
}

// Get user's posts
$user_posts = $db->query("SELECT * FROM blog_posts WHERE author_id = $user_id ORDER BY created_at DESC");

// Get recent posts for commenting
$recent_posts = $db->query("SELECT bp.*, u.first_name, u.last_name FROM blog_posts bp JOIN users u ON bp.author_id = u.id WHERE bp.status = 'published' ORDER BY bp.created_at DESC LIMIT 10");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Member Dashboard - Salem Dominion Ministries</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .sidebar {
            min-height: 100vh;
            background: #343a40;
            color: white;
        }
        .sidebar .nav-link {
            color: rgba(255,255,255,.75);
            padding: 0.75rem 1rem;
        }
        .sidebar .nav-link:hover {
            color: white;
            background: rgba(255,255,255,.1);
        }
        .sidebar .nav-link.active {
            color: white;
            background: #0d6efd;
        }
        .avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 px-0 sidebar">
                <div class="d-flex flex-column">
                    <div class="p-3 border-bottom">
                        <h5 class="mb-0"><i class="fas fa-user"></i> Member Panel</h5>
                        <small>Welcome, <?php echo htmlspecialchars($user['first_name']); ?></small>
                    </div>
                    <nav class="nav flex-column py-3">
                        <a class="nav-link active" href="member_dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
                        <a class="nav-link" href="#create-post" data-bs-toggle="modal"><i class="fas fa-plus"></i> Create Post</a>
                        <a class="nav-link" href="#my-posts" onclick="showSection('my-posts')"><i class="fas fa-blog"></i> My Posts</a>
                        <a class="nav-link" href="#comment" onclick="showSection('comment')"><i class="fas fa-comments"></i> Comment on Posts</a>
                        <a class="nav-link" href="#profile" onclick="showSection('profile')"><i class="fas fa-user-edit"></i> Edit Profile</a>
                        <a class="nav-link" href="events.php"><i class="fas fa-calendar"></i> Events</a>
                        <a class="nav-link" href="prayer.php"><i class="fas fa-pray"></i> Prayer Requests</a>
                    </nav>
                    <div class="mt-auto p-3">
                        <a href="logout.php" class="btn btn-outline-light btn-sm w-100"><i class="fas fa-sign-out-alt"></i> Logout</a>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 px-4 py-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1><i class="fas fa-tachometer-alt"></i> Member Dashboard</h1>
                    <a href="index.php" class="btn btn-outline-primary"><i class="fas fa-home"></i> View Site</a>
                </div>

                <?php if (isset($_GET['success'])): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?php
                        switch ($_GET['success']) {
                            case 'post_created': echo 'Post created successfully!'; break;
                            case 'profile_updated': echo 'Profile updated successfully!'; break;
                        }
                        ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <!-- Dashboard Content -->
                <div id="dashboard-content">
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="card text-center">
                                <div class="card-body">
                                    <i class="fas fa-blog fa-2x text-primary mb-2"></i>
                                    <h5>My Posts</h5>
                                    <p class="text-muted"><?php echo $user_posts->num_rows; ?> posts created</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card text-center">
                                <div class="card-body">
                                    <i class="fas fa-comments fa-2x text-success mb-2"></i>
                                    <h5>Comments</h5>
                                    <p class="text-muted">Engage with community</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card text-center">
                                <div class="card-body">
                                    <i class="fas fa-pray fa-2x text-warning mb-2"></i>
                                    <h5>Prayer Requests</h5>
                                    <p class="text-muted">Share your needs</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Posts -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="fas fa-clock"></i> Recent Posts</h5>
                        </div>
                        <div class="card-body">
                            <?php if ($recent_posts->num_rows > 0): ?>
                                <div class="list-group list-group-flush">
                                    <?php while ($post = $recent_posts->fetch_assoc()): ?>
                                    <div class="list-group-item px-0">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1"><?php echo htmlspecialchars($post['title']); ?></h6>
                                            <small><?php echo date('M j, Y', strtotime($post['created_at'])); ?></small>
                                        </div>
                                        <p class="mb-1">By <?php echo htmlspecialchars($post['first_name'] . ' ' . $post['last_name']); ?></p>
                                        <a href="blog_post.php?id=<?php echo $post['id']; ?>" class="btn btn-sm btn-outline-primary">Read & Comment</a>
                                    </div>
                                    <?php endwhile; ?>
                                </div>
                            <?php else: ?>
                                <p class="text-muted">No posts available yet.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- My Posts Section -->
                <div id="my-posts-section" style="display: none;">
                    <h3><i class="fas fa-blog"></i> My Posts</h3>
                    <?php if ($user_posts->num_rows > 0): ?>
                        <div class="row">
                            <?php while ($post = $user_posts->fetch_assoc()): ?>
                            <div class="col-md-6 mb-3">
                                <div class="card">
                                    <?php if ($post['featured_image_url']): ?>
                                    <img src="<?php echo htmlspecialchars($post['featured_image_url']); ?>" class="card-img-top" alt="Post Image">
                                    <?php endif; ?>
                                    <div class="card-body">
                                        <h5 class="card-title"><?php echo htmlspecialchars($post['title']); ?></h5>
                                        <p class="card-text"><?php echo substr(strip_tags($post['content']), 0, 100) . '...'; ?></p>
                                        <a href="blog_post.php?id=<?php echo $post['id']; ?>" class="btn btn-primary btn-sm">View Post</a>
                                    </div>
                                </div>
                            </div>
                            <?php endwhile; ?>
                        </div>
                    <?php else: ?>
                        <p class="text-muted">You haven't created any posts yet.</p>
                    <?php endif; ?>
                </div>

                <!-- Profile Section -->
                <div id="profile-section" style="display: none;">
                    <h3><i class="fas fa-user-edit"></i> Edit Profile</h3>
                    <form method="POST" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">First Name</label>
                                    <input type="text" class="form-control" name="first_name" value="<?php echo htmlspecialchars($user['first_name']); ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Last Name</label>
                                    <input type="text" class="form-control" name="last_name" value="<?php echo htmlspecialchars($user['last_name']); ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Phone</label>
                                    <input type="tel" class="form-control" name="phone" value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Avatar</label>
                                    <input type="file" class="form-control" name="avatar" accept="image/*">
                                    <?php if ($user['avatar_url']): ?>
                                    <img src="<?php echo htmlspecialchars($user['avatar_url']); ?>" alt="Current Avatar" class="mt-2" style="max-width: 100px;">
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <button type="submit" name="update_profile" class="btn btn-primary">Update Profile</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Create Post Modal -->
    <div class="modal fade" id="createPostModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-plus"></i> Create New Post</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Title *</label>
                            <input type="text" class="form-control" name="title" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Content *</label>
                            <textarea class="form-control" name="content" rows="6" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Category</label>
                            <select class="form-control" name="category">
                                <option value="General">General</option>
                                <option value="Testimony">Testimony</option>
                                <option value="Prayer">Prayer</option>
                                <option value="Ministry">Ministry</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Featured Image</label>
                            <input type="file" class="form-control" name="image" accept="image/*">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" name="create_post" class="btn btn-primary">Create Post</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function showSection(sectionId) {
            // Hide all sections
            document.getElementById('dashboard-content').style.display = 'none';
            document.getElementById('my-posts-section').style.display = 'none';
            document.getElementById('profile-section').style.display = 'none';

            // Show selected section
            document.getElementById(sectionId + '-section').style.display = 'block';

            // Update active nav link
            document.querySelectorAll('.nav-link').forEach(link => link.classList.remove('active'));
            event.target.classList.add('active');
        }
    </script>
</body>
</html>