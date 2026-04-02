&lt;?php
session_start();
require_once 'db.php';

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

// Handle user actions
$message = '';
$message_type = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];

        if ($action === 'update_role') {
            $user_id = intval($_POST['user_id']);
            $new_role = $_POST['role'];

            if (in_array($new_role, ['admin', 'member'])) {
                $stmt = $db-&gt;prepare("UPDATE users SET role = ? WHERE id = ?");
                $stmt-&gt;bind_param('si', $new_role, $user_id);
                if ($stmt-&gt;execute()) {
                    $message = 'User role updated successfully.';
                    $message_type = 'success';
                } else {
                    $message = 'Error updating user role.';
                    $message_type = 'error';
                }
            }
        } elseif ($action === 'delete_user') {
            $user_id = intval($_POST['user_id']);

            // Don't allow deleting yourself
            if ($user_id !== $_SESSION['user_id']) {
                $stmt = $db-&gt;prepare("DELETE FROM users WHERE id = ?");
                $stmt-&gt;bind_param('i', $user_id);
                if ($stmt-&gt;execute()) {
                    $message = 'User deleted successfully.';
                    $message_type = 'success';
                } else {
                    $message = 'Error deleting user.';
                    $message_type = 'error';
                }
            } else {
                $message = 'You cannot delete your own account.';
                $message_type = 'error';
            }
        } elseif ($action === 'reset_password') {
            $user_id = intval($_POST['user_id']);
            $new_password = password_hash('password123', PASSWORD_DEFAULT);

            $stmt = $db-&gt;prepare("UPDATE users SET password = ? WHERE id = ?");
            $stmt-&gt;bind_param('si', $new_password, $user_id);
            if ($stmt-&gt;execute()) {
                $message = 'Password reset to "password123".';
                $message_type = 'success';
            } else {
                $message = 'Error resetting password.';
                $message_type = 'error';
            }
        }
    }
}

// Get all users with their stats
$users = $db-&gt;query("
    SELECT u.*,
           COUNT(DISTINCT p.id) as post_count,
           COUNT(DISTINCT c.id) as comment_count,
           MAX(p.created_at) as last_post_date
    FROM users u
    LEFT JOIN blog_posts p ON u.id = p.author_id
    LEFT JOIN blog_comments c ON u.id = c.user_id
    GROUP BY u.id
    ORDER BY u.created_at DESC
");
?&gt;
&lt;!DOCTYPE html&gt;
&lt;html lang="en"&gt;
&lt;head&gt;
    &lt;meta charset="UTF-8"&gt;
    &lt;meta name="viewport" content="width=device-width, initial-scale=1.0"&gt;
    &lt;title&gt;User Management - Salem Dominion Ministries&lt;/title&gt;
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
        .user-card {
            transition: transform 0.2s ease;
        }
        .user-card:hover {
            transform: translateY(-2px);
        }
        .role-badge {
            font-size: 0.8em;
        }
        .stats-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
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
                        &lt;a class="nav-link active" href="admin_users.php"&gt;
                            &lt;i class="fas fa-users"&gt;&lt;/i&gt; Users
                        &lt;/a&gt;
                        &lt;a class="nav-link" href="admin_news.php"&gt;
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
                    &lt;h2&gt;&lt;i class="fas fa-users text-primary"&gt;&lt;/i&gt; User Management&lt;/h2&gt;
                    &lt;a href="admin_dashboard.php" class="btn btn-outline-secondary"&gt;
                        &lt;i class="fas fa-arrow-left"&gt;&lt;/i&gt; Back to Dashboard
                    &lt;/a&gt;
                &lt;/div&gt;

                &lt;?php if ($message): ?&gt;
                    &lt;div class="alert alert-&lt;?php echo $message_type === 'success' ? 'success' : 'danger'; ?&gt; alert-dismissible fade show" role="alert"&gt;
                        &lt;?php echo htmlspecialchars($message); ?&gt;
                        &lt;button type="button" class="btn-close" data-bs-dismiss="alert"&gt;&lt;/button&gt;
                    &lt;/div&gt;
                &lt;?php endif; ?&gt;

                &lt;!-- User Statistics --&gt;
                &lt;div class="row mb-4"&gt;
                    &lt;?php
                    $total_users = $users-&gt;num_rows;
                    $admin_count = 0;
                    $member_count = 0;
                    $users-&gt;data_seek(0);
                    while ($user = $users-&gt;fetch_assoc()) {
                        if ($user['role'] === 'admin') $admin_count++;
                        else $member_count++;
                    }
                    $users-&gt;data_seek(0);
                    ?&gt;
                    &lt;div class="col-md-4"&gt;
                        &lt;div class="card stats-card text-white"&gt;
                            &lt;div class="card-body"&gt;
                                &lt;h5 class="card-title"&gt;&lt;i class="fas fa-users"&gt;&lt;/i&gt; Total Users&lt;/h5&gt;
                                &lt;h3&gt;&lt;?php echo $total_users; ?&gt;&lt;/h3&gt;
                            &lt;/div&gt;
                        &lt;/div&gt;
                    &lt;/div&gt;
                    &lt;div class="col-md-4"&gt;
                        &lt;div class="card bg-success text-white"&gt;
                            &lt;div class="card-body"&gt;
                                &lt;h5 class="card-title"&gt;&lt;i class="fas fa-user-shield"&gt;&lt;/i&gt; Admins&lt;/h5&gt;
                                &lt;h3&gt;&lt;?php echo $admin_count; ?&gt;&lt;/h3&gt;
                            &lt;/div&gt;
                        &lt;/div&gt;
                    &lt;/div&gt;
                    &lt;div class="col-md-4"&gt;
                        &lt;div class="card bg-info text-white"&gt;
                            &lt;div class="card-body"&gt;
                                &lt;h5 class="card-title"&gt;&lt;i class="fas fa-user"&gt;&lt;/i&gt; Members&lt;/h5&gt;
                                &lt;h3&gt;&lt;?php echo $member_count; ?&gt;&lt;/h3&gt;
                            &lt;/div&gt;
                        &lt;/div&gt;
                    &lt;/div&gt;
                &lt;/div&gt;

                &lt;!-- Users Table --&gt;
                &lt;div class="card"&gt;
                    &lt;div class="card-header"&gt;
                        &lt;h5 class="mb-0"&gt;&lt;i class="fas fa-list"&gt;&lt;/i&gt; All Users&lt;/h5&gt;
                    &lt;/div&gt;
                    &lt;div class="card-body p-0"&gt;
                        &lt;div class="table-responsive"&gt;
                            &lt;table class="table table-hover mb-0"&gt;
                                &lt;thead class="table-dark"&gt;
                                    &lt;tr&gt;
                                        &lt;th&gt;User&lt;/th&gt;
                                        &lt;th&gt;Role&lt;/th&gt;
                                        &lt;th&gt;Posts&lt;/th&gt;
                                        &lt;th&gt;Comments&lt;/th&gt;
                                        &lt;th&gt;Joined&lt;/th&gt;
                                        &lt;th&gt;Last Post&lt;/th&gt;
                                        &lt;th&gt;Actions&lt;/th&gt;
                                    &lt;/tr&gt;
                                &lt;/thead&gt;
                                &lt;tbody&gt;
                                    &lt;?php while ($user = $users-&gt;fetch_assoc()): ?&gt;
                                        &lt;tr&gt;
                                            &lt;td&gt;
                                                &lt;div class="d-flex align-items-center"&gt;
                                                    &lt;?php if ($user['avatar']): ?&gt;
                                                        &lt;img src="&lt;?php echo htmlspecialchars($user['avatar']); ?&gt;" class="rounded-circle me-3" width="40" height="40" alt="Avatar"&gt;
                                                    &lt;?php else: ?&gt;
                                                        &lt;div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;"&gt;
                                                            &lt;i class="fas fa-user text-white"&gt;&lt;/i&gt;
                                                        &lt;/div&gt;
                                                    &lt;?php endif; ?&gt;
                                                    &lt;div&gt;
                                                        &lt;strong&gt;&lt;?php echo htmlspecialchars($user['username']); ?&gt;&lt;/strong&gt;&lt;br&gt;
                                                        &lt;small class="text-muted"&gt;&lt;?php echo htmlspecialchars($user['email']); ?&gt;&lt;/small&gt;
                                                    &lt;/div&gt;
                                                &lt;/div&gt;
                                            &lt;/td&gt;
                                            &lt;td&gt;
                                                &lt;span class="badge role-badge bg-&lt;?php echo $user['role'] === 'admin' ? 'success' : 'primary'; ?&gt;"&gt;
                                                    &lt;?php echo ucfirst($user['role']); ?&gt;
                                                &lt;/span&gt;
                                            &lt;/td&gt;
                                            &lt;td&gt;&lt;?php echo $user['post_count']; ?&gt;&lt;/td&gt;
                                            &lt;td&gt;&lt;?php echo $user['comment_count']; ?&gt;&lt;/td&gt;
                                            &lt;td&gt;&lt;?php echo date('M j, Y', strtotime($user['created_at'])); ?&gt;&lt;/td&gt;
                                            &lt;td&gt;
                                                &lt;?php echo $user['last_post_date'] ? date('M j, Y', strtotime($user['last_post_date'])) : 'Never'; ?&gt;
                                            &lt;/td&gt;
                                            &lt;td&gt;
                                                &lt;div class="btn-group" role="group"&gt;
                                                    &lt;!-- Role Change --&gt;
                                                    &lt;form method="post" class="d-inline"&gt;
                                                        &lt;input type="hidden" name="action" value="update_role"&gt;
                                                        &lt;input type="hidden" name="user_id" value="&lt;?php echo $user['id']; ?&gt;"&gt;
                                                        &lt;select name="role" class="form-select form-select-sm d-inline-block w-auto me-1" onchange="this.form.submit()"&gt;
                                                            &lt;option value="member" &lt;?php echo $user['role'] === 'member' ? 'selected' : ''; ?&gt;&gt;Member&lt;/option&gt;
                                                            &lt;option value="admin" &lt;?php echo $user['role'] === 'admin' ? 'selected' : ''; ?&gt;&gt;Admin&lt;/option&gt;
                                                        &lt;/select&gt;
                                                    &lt;/form&gt;

                                                    &lt;!-- Reset Password --&gt;
                                                    &lt;form method="post" class="d-inline"&gt;
                                                        &lt;input type="hidden" name="action" value="reset_password"&gt;
                                                        &lt;input type="hidden" name="user_id" value="&lt;?php echo $user['id']; ?&gt;"&gt;
                                                        &lt;button type="submit" class="btn btn-warning btn-sm" title="Reset Password"&gt;
                                                            &lt;i class="fas fa-key"&gt;&lt;/i&gt;
                                                        &lt;/button&gt;
                                                    &lt;/form&gt;

                                                    &lt;!-- Delete User --&gt;
                                                    &lt;?php if ($user['id'] !== $_SESSION['user_id']): ?&gt;
                                                        &lt;form method="post" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this user?')"&gt;
                                                            &lt;input type="hidden" name="action" value="delete_user"&gt;
                                                            &lt;input type="hidden" name="user_id" value="&lt;?php echo $user['id']; ?&gt;"&gt;
                                                            &lt;button type="submit" class="btn btn-danger btn-sm" title="Delete User"&gt;
                                                                &lt;i class="fas fa-trash"&gt;&lt;/i&gt;
                                                            &lt;/button&gt;
                                                        &lt;/form&gt;
                                                    &lt;?php endif; ?&gt;
                                                &lt;/div&gt;
                                            &lt;/td&gt;
                                        &lt;/tr&gt;
                                    &lt;?php endwhile; ?&gt;
                                &lt;/tbody&gt;
                            &lt;/table&gt;
                        &lt;/div&gt;
                    &lt;/div&gt;
                &lt;/div&gt;
            &lt;/div&gt;
        &lt;/div&gt;
    &lt;/div&gt;

    &lt;script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"&gt;&lt;/script&gt;
&lt;/body&gt;
&lt;/html&gt;