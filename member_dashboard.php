&lt;?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'member') {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$user = $db-&gt;query("SELECT * FROM users WHERE id = $user_id")-&gt;fetch_assoc();

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

        $stmt = $db-&gt;prepare("INSERT INTO blog_posts (title, slug, content, featured_image_url, author_id, category, status) VALUES (?, ?, ?, ?, ?, ?, 'published')");
        $stmt-&gt;bind_param('ssssis', $title, $slug, $content, $image_url, $user_id, $category);
        $stmt-&gt;execute();
        $stmt-&gt;close();

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

        $stmt = $db-&gt;prepare("UPDATE users SET first_name = ?, last_name = ?, phone = ?, avatar_url = ? WHERE id = ?");
        $stmt-&gt;bind_param('ssssi', $first_name, $last_name, $phone, $avatar_url, $user_id);
        $stmt-&gt;execute();
        $stmt-&gt;close();

        $_SESSION['user_name'] = $first_name . ' ' . $last_name;
        header('Location: member_dashboard.php?success=profile_updated');
        exit;
    }
}

// Get user's posts
$user_posts = $db-&gt;query("SELECT * FROM blog_posts WHERE author_id = $user_id ORDER BY created_at DESC");

// Get recent posts for commenting
$recent_posts = $db-&gt;query("SELECT bp.*, u.first_name, u.last_name FROM blog_posts bp JOIN users u ON bp.author_id = u.id WHERE bp.status = 'published' ORDER BY bp.created_at DESC LIMIT 10");
?&gt;
&lt;!DOCTYPE html&gt;
&lt;html lang="en"&gt;
&lt;head&gt;
    &lt;meta charset="UTF-8"&gt;
    &lt;meta name="viewport" content="width=device-width, initial-scale=1.0"&gt;
    &lt;title&gt;Member Dashboard - Salem Dominion Ministries&lt;/title&gt;
    &lt;link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"&gt;
    &lt;link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet"&gt;
    &lt;style&gt;
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
    &lt;/style&gt;
&lt;/head&gt;
&lt;body&gt;
    &lt;div class="container-fluid"&gt;
        &lt;div class="row"&gt;
            &lt;!-- Sidebar --&gt;
            &lt;div class="col-md-3 col-lg-2 px-0 sidebar"&gt;
                &lt;div class="d-flex flex-column"&gt;
                    &lt;div class="p-3 border-bottom"&gt;
                        &lt;h5 class="mb-0"&gt;&lt;i class="fas fa-user"&gt;&lt;/i&gt; Member Panel&lt;/h5&gt;
                        &lt;small&gt;Welcome, &lt;?php echo htmlspecialchars($user['first_name']); ?&gt;&lt;/small&gt;
                    &lt;/div&gt;
                    &lt;nav class="nav flex-column py-3"&gt;
                        &lt;a class="nav-link active" href="member_dashboard.php"&gt;&lt;i class="fas fa-tachometer-alt"&gt;&lt;/i&gt; Dashboard&lt;/a&gt;
                        &lt;a class="nav-link" href="#create-post" data-bs-toggle="modal"&gt;&lt;i class="fas fa-plus"&gt;&lt;/i&gt; Create Post&lt;/a&gt;
                        &lt;a class="nav-link" href="#my-posts" onclick="showSection('my-posts')"&gt;&lt;i class="fas fa-blog"&gt;&lt;/i&gt; My Posts&lt;/a&gt;
                        &lt;a class="nav-link" href="#comment" onclick="showSection('comment')"&gt;&lt;i class="fas fa-comments"&gt;&lt;/i&gt; Comment on Posts&lt;/a&gt;
                        &lt;a class="nav-link" href="#profile" onclick="showSection('profile')"&gt;&lt;i class="fas fa-user-edit"&gt;&lt;/i&gt; Edit Profile&lt;/a&gt;
                        &lt;a class="nav-link" href="events.php"&gt;&lt;i class="fas fa-calendar"&gt;&lt;/i&gt; Events&lt;/a&gt;
                        &lt;a class="nav-link" href="prayer.php"&gt;&lt;i class="fas fa-pray"&gt;&lt;/i&gt; Prayer Requests&lt;/a&gt;
                    &lt;/nav&gt;
                    &lt;div class="mt-auto p-3"&gt;
                        &lt;a href="logout.php" class="btn btn-outline-light btn-sm w-100"&gt;&lt;i class="fas fa-sign-out-alt"&gt;&lt;/i&gt; Logout&lt;/a&gt;
                    &lt;/div&gt;
                &lt;/div&gt;
            &lt;/div&gt;

            &lt;!-- Main Content --&gt;
            &lt;div class="col-md-9 col-lg-10 px-4 py-4"&gt;
                &lt;div class="d-flex justify-content-between align-items-center mb-4"&gt;
                    &lt;h1&gt;&lt;i class="fas fa-tachometer-alt"&gt;&lt;/i&gt; Member Dashboard&lt;/h1&gt;
                    &lt;a href="index.php" class="btn btn-outline-primary"&gt;&lt;i class="fas fa-home"&gt;&lt;/i&gt; View Site&lt;/a&gt;
                &lt;/div&gt;

                &lt;?php if (isset($_GET['success'])): ?&gt;
                    &lt;div class="alert alert-success alert-dismissible fade show" role="alert"&gt;
                        &lt;?php
                        switch ($_GET['success']) {
                            case 'post_created': echo 'Post created successfully!'; break;
                            case 'profile_updated': echo 'Profile updated successfully!'; break;
                        }
                        ?&gt;
                        &lt;button type="button" class="btn-close" data-bs-dismiss="alert"&gt;&lt;/button&gt;
                    &lt;/div&gt;
                &lt;?php endif; ?&gt;

                &lt;!-- Dashboard Content --&gt;
                &lt;div id="dashboard-content"&gt;
                    &lt;div class="row mb-4"&gt;
                        &lt;div class="col-md-4"&gt;
                            &lt;div class="card text-center"&gt;
                                &lt;div class="card-body"&gt;
                                    &lt;i class="fas fa-blog fa-2x text-primary mb-2"&gt;&lt;/i&gt;
                                    &lt;h5&gt;My Posts&lt;/h5&gt;
                                    &lt;p class="text-muted"&gt;&lt;?php echo $user_posts-&gt;num_rows; ?&gt; posts created&lt;/p&gt;
                                &lt;/div&gt;
                            &lt;/div&gt;
                        &lt;/div&gt;
                        &lt;div class="col-md-4"&gt;
                            &lt;div class="card text-center"&gt;
                                &lt;div class="card-body"&gt;
                                    &lt;i class="fas fa-comments fa-2x text-success mb-2"&gt;&lt;/i&gt;
                                    &lt;h5&gt;Comments&lt;/h5&gt;
                                    &lt;p class="text-muted"&gt;Engage with community&lt;/p&gt;
                                &lt;/div&gt;
                            &lt;/div&gt;
                        &lt;/div&gt;
                        &lt;div class="col-md-4"&gt;
                            &lt;div class="card text-center"&gt;
                                &lt;div class="card-body"&gt;
                                    &lt;i class="fas fa-pray fa-2x text-warning mb-2"&gt;&lt;/i&gt;
                                    &lt;h5&gt;Prayer Requests&lt;/h5&gt;
                                    &lt;p class="text-muted"&gt;Share your needs&lt;/p&gt;
                                &lt;/div&gt;
                            &lt;/div&gt;
                        &lt;/div&gt;
                    &lt;/div&gt;

                    &lt;!-- Recent Posts --&gt;
                    &lt;div class="card"&gt;
                        &lt;div class="card-header"&gt;
                            &lt;h5 class="mb-0"&gt;&lt;i class="fas fa-clock"&gt;&lt;/i&gt; Recent Posts&lt;/h5&gt;
                        &lt;/div&gt;
                        &lt;div class="card-body"&gt;
                            &lt;?php if ($recent_posts-&gt;num_rows > 0): ?&gt;
                                &lt;div class="list-group list-group-flush"&gt;
                                    &lt;?php while ($post = $recent_posts-&gt;fetch_assoc()): ?&gt;
                                    &lt;div class="list-group-item px-0"&gt;
                                        &lt;div class="d-flex w-100 justify-content-between"&gt;
                                            &lt;h6 class="mb-1"&gt;&lt;?php echo htmlspecialchars($post['title']); ?&gt;&lt;/h6&gt;
                                            &lt;small&gt;&lt;?php echo date('M j, Y', strtotime($post['created_at'])); ?&gt;&lt;/small&gt;
                                        &lt;/div&gt;
                                        &lt;p class="mb-1"&gt;By &lt;?php echo htmlspecialchars($post['first_name'] . ' ' . $post['last_name']); ?&gt;&lt;/p&gt;
                                        &lt;a href="blog_post.php?id=&lt;?php echo $post['id']; ?&gt;" class="btn btn-sm btn-outline-primary"&gt;Read &amp; Comment&lt;/a&gt;
                                    &lt;/div&gt;
                                    &lt;?php endwhile; ?&gt;
                                &lt;/div&gt;
                            &lt;?php else: ?&gt;
                                &lt;p class="text-muted"&gt;No posts available yet.&lt;/p&gt;
                            &lt;?php endif; ?&gt;
                        &lt;/div&gt;
                    &lt;/div&gt;
                &lt;/div&gt;

                &lt;!-- My Posts Section --&gt;
                &lt;div id="my-posts-section" style="display: none;"&gt;
                    &lt;h3&gt;&lt;i class="fas fa-blog"&gt;&lt;/i&gt; My Posts&lt;/h3&gt;
                    &lt;?php if ($user_posts-&gt;num_rows > 0): ?&gt;
                        &lt;div class="row"&gt;
                            &lt;?php while ($post = $user_posts-&gt;fetch_assoc()): ?&gt;
                            &lt;div class="col-md-6 mb-3"&gt;
                                &lt;div class="card"&gt;
                                    &lt;?php if ($post['featured_image_url']): ?&gt;
                                    &lt;img src="&lt;?php echo htmlspecialchars($post['featured_image_url']); ?&gt;" class="card-img-top" alt="Post Image"&gt;
                                    &lt;?php endif; ?&gt;
                                    &lt;div class="card-body"&gt;
                                        &lt;h5 class="card-title"&gt;&lt;?php echo htmlspecialchars($post['title']); ?&gt;&lt;/h5&gt;
                                        &lt;p class="card-text"&gt;&lt;?php echo substr(strip_tags($post['content']), 0, 100) . '...'; ?&gt;&lt;/p&gt;
                                        &lt;a href="blog_post.php?id=&lt;?php echo $post['id']; ?&gt;" class="btn btn-primary btn-sm"&gt;View Post&lt;/a&gt;
                                    &lt;/div&gt;
                                &lt;/div&gt;
                            &lt;/div&gt;
                            &lt;?php endwhile; ?&gt;
                        &lt;/div&gt;
                    &lt;?php else: ?&gt;
                        &lt;p class="text-muted"&gt;You haven't created any posts yet.&lt;/p&gt;
                    &lt;?php endif; ?&gt;
                &lt;/div&gt;

                &lt;!-- Profile Section --&gt;
                &lt;div id="profile-section" style="display: none;"&gt;
                    &lt;h3&gt;&lt;i class="fas fa-user-edit"&gt;&lt;/i&gt; Edit Profile&lt;/h3&gt;
                    &lt;form method="POST" enctype="multipart/form-data"&gt;
                        &lt;div class="row"&gt;
                            &lt;div class="col-md-6"&gt;
                                &lt;div class="mb-3"&gt;
                                    &lt;label class="form-label"&gt;First Name&lt;/label&gt;
                                    &lt;input type="text" class="form-control" name="first_name" value="&lt;?php echo htmlspecialchars($user['first_name']); ?&gt;" required&gt;
                                &lt;/div&gt;
                                &lt;div class="mb-3"&gt;
                                    &lt;label class="form-label"&gt;Last Name&lt;/label&gt;
                                    &lt;input type="text" class="form-control" name="last_name" value="&lt;?php echo htmlspecialchars($user['last_name']); ?&gt;" required&gt;
                                &lt;/div&gt;
                                &lt;div class="mb-3"&gt;
                                    &lt;label class="form-label"&gt;Phone&lt;/label&gt;
                                    &lt;input type="tel" class="form-control" name="phone" value="&lt;?php echo htmlspecialchars($user['phone'] ?? ''); ?&gt;"&gt;
                                &lt;/div&gt;
                            &lt;/div&gt;
                            &lt;div class="col-md-6"&gt;
                                &lt;div class="mb-3"&gt;
                                    &lt;label class="form-label"&gt;Avatar&lt;/label&gt;
                                    &lt;input type="file" class="form-control" name="avatar" accept="image/*"&gt;
                                    &lt;?php if ($user['avatar_url']): ?&gt;
                                    &lt;img src="&lt;?php echo htmlspecialchars($user['avatar_url']); ?&gt;" alt="Current Avatar" class="mt-2" style="max-width: 100px;"&gt;
                                    &lt;?php endif; ?&gt;
                                &lt;/div&gt;
                            &lt;/div&gt;
                        &lt;/div&gt;
                        &lt;button type="submit" name="update_profile" class="btn btn-primary"&gt;Update Profile&lt;/button&gt;
                    &lt;/form&gt;
                &lt;/div&gt;
            &lt;/div&gt;
        &lt;/div&gt;
    &lt;/div&gt;

    &lt;!-- Create Post Modal --&gt;
    &lt;div class="modal fade" id="createPostModal" tabindex="-1"&gt;
        &lt;div class="modal-dialog modal-lg"&gt;
            &lt;div class="modal-content"&gt;
                &lt;div class="modal-header"&gt;
                    &lt;h5 class="modal-title"&gt;&lt;i class="fas fa-plus"&gt;&lt;/i&gt; Create New Post&lt;/h5&gt;
                    &lt;button type="button" class="btn-close" data-bs-dismiss="modal"&gt;&lt;/button&gt;
                &lt;/div&gt;
                &lt;form method="POST" enctype="multipart/form-data"&gt;
                    &lt;div class="modal-body"&gt;
                        &lt;div class="mb-3"&gt;
                            &lt;label class="form-label"&gt;Title *&lt;/label&gt;
                            &lt;input type="text" class="form-control" name="title" required&gt;
                        &lt;/div&gt;
                        &lt;div class="mb-3"&gt;
                            &lt;label class="form-label"&gt;Content *&lt;/label&gt;
                            &lt;textarea class="form-control" name="content" rows="6" required&gt;&lt;/textarea&gt;
                        &lt;/div&gt;
                        &lt;div class="mb-3"&gt;
                            &lt;label class="form-label"&gt;Category&lt;/label&gt;
                            &lt;select class="form-control" name="category"&gt;
                                &lt;option value="General"&gt;General&lt;/option&gt;
                                &lt;option value="Testimony"&gt;Testimony&lt;/option&gt;
                                &lt;option value="Prayer"&gt;Prayer&lt;/option&gt;
                                &lt;option value="Ministry"&gt;Ministry&lt;/option&gt;
                            &lt;/select&gt;
                        &lt;/div&gt;
                        &lt;div class="mb-3"&gt;
                            &lt;label class="form-label"&gt;Featured Image&lt;/label&gt;
                            &lt;input type="file" class="form-control" name="image" accept="image/*"&gt;
                        &lt;/div&gt;
                    &lt;/div&gt;
                    &lt;div class="modal-footer"&gt;
                        &lt;button type="button" class="btn btn-secondary" data-bs-dismiss="modal"&gt;Cancel&lt;/button&gt;
                        &lt;button type="submit" name="create_post" class="btn btn-primary"&gt;Create Post&lt;/button&gt;
                    &lt;/div&gt;
                &lt;/form&gt;
            &lt;/div&gt;
        &lt;/div&gt;
    &lt;/div&gt;

    &lt;script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"&gt;&lt;/script&gt;
    &lt;script&gt;
        function showSection(sectionId) {
            // Hide all sections
            document.getElementById('dashboard-content').style.display = 'none';
            document.getElementById('my-posts-section').style.display = 'none';
            document.getElementById('profile-section').style.display = 'none';

            // Show selected section
            document.getElementById(sectionId + '-section').style.display = 'block';

            // Update active nav link
            document.querySelectorAll('.nav-link').forEach(link =&gt; link.classList.remove('active'));
            event.target.classList.add('active');
        }
    &lt;/script&gt;
&lt;/body&gt;
&lt;/html&gt;