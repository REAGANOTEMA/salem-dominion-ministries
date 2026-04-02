<?php
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
                $stmt = $db->prepare("UPDATE users SET role = ? WHERE id = ?");
                $stmt->bind_param('si', $new_role, $user_id);
                if ($stmt->execute()) {
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
                $stmt = $db->prepare("DELETE FROM users WHERE id = ?");
                $stmt->bind_param('i', $user_id);
                if ($stmt->execute()) {
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

            $stmt = $db->prepare("UPDATE users SET password = ? WHERE id = ?");
            $stmt->bind_param('si', $new_password, $user_id);
            if ($stmt->execute()) {
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
$users = $db->query("
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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management - Salem Dominion Ministries</title>
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
                        <a class="nav-link active" href="admin_users.php">
                            <i class="fas fa-users"></i> Users
                        </a>
                        <a class="nav-link" href="admin_news.php">
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
                    <h2><i class="fas fa-users text-primary"></i> User Management</h2>
                    <a href="admin_dashboard.php" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Dashboard
                    </a>
                </div>

                <?php if ($message): ?>
                    <div class="alert alert-<?php echo $message_type === 'success' ? 'success' : 'danger'; ?> alert-dismissible fade show" role="alert">
                        <?php echo htmlspecialchars($message); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <!-- User Statistics -->
                <div class="row mb-4">
                    <?php
                    $total_users = $users->num_rows;
                    $admin_count = 0;
                    $member_count = 0;
                    $users->data_seek(0);
                    while ($user = $users->fetch_assoc()) {
                        if ($user['role'] === 'admin') $admin_count++;
                        else $member_count++;
                    }
                    $users->data_seek(0);
                    ?>
                    <div class="col-md-4">
                        <div class="card stats-card text-white">
                            <div class="card-body">
                                <h5 class="card-title"><i class="fas fa-users"></i> Total Users</h5>
                                <h3><?php echo $total_users; ?></h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-success text-white">
                            <div class="card-body">
                                <h5 class="card-title"><i class="fas fa-user-shield"></i> Admins</h5>
                                <h3><?php echo $admin_count; ?></h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-info text-white">
                            <div class="card-body">
                                <h5 class="card-title"><i class="fas fa-user"></i> Members</h5>
                                <h3><?php echo $member_count; ?></h3>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Users Table -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-list"></i> All Users</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-dark">
                                    <tr>
                                        <th>User</th>
                                        <th>Role</th>
                                        <th>Posts</th>
                                        <th>Comments</th>
                                        <th>Joined</th>
                                        <th>Last Post</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($user = $users->fetch_assoc()): ?>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <?php if ($user['avatar']): ?>
                                                        <img src="<?php echo htmlspecialchars($user['avatar']); ?>" class="rounded-circle me-3" width="40" height="40" alt="Avatar">
                                                    <?php else: ?>
                                                        <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                                            <i class="fas fa-user text-white"></i>
                                                        </div>
                                                    <?php endif; ?>
                                                    <div>
                                                        <strong><?php echo htmlspecialchars($user['username']); ?></strong><br>
                                                        <small class="text-muted"><?php echo htmlspecialchars($user['email']); ?></small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge role-badge bg-<?php echo $user['role'] === 'admin' ? 'success' : 'primary'; ?>">
                                                    <?php echo ucfirst($user['role']); ?>
                                                </span>
                                            </td>
                                            <td><?php echo $user['post_count']; ?></td>
                                            <td><?php echo $user['comment_count']; ?></td>
                                            <td><?php echo date('M j, Y', strtotime($user['created_at'])); ?></td>
                                            <td>
                                                <?php echo $user['last_post_date'] ? date('M j, Y', strtotime($user['last_post_date'])) : 'Never'; ?>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <!-- Role Change -->
                                                    <form method="post" class="d-inline">
                                                        <input type="hidden" name="action" value="update_role">
                                                        <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                                        <select name="role" class="form-select form-select-sm d-inline-block w-auto me-1" onchange="this.form.submit()">
                                                            <option value="member" <?php echo $user['role'] === 'member' ? 'selected' : ''; ?>>Member</option>
                                                            <option value="admin" <?php echo $user['role'] === 'admin' ? 'selected' : ''; ?>>Admin</option>
                                                        </select>
                                                    </form>

                                                    <!-- Reset Password -->
                                                    <form method="post" class="d-inline">
                                                        <input type="hidden" name="action" value="reset_password">
                                                        <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                                        <button type="submit" class="btn btn-warning btn-sm" title="Reset Password">
                                                            <i class="fas fa-key"></i>
                                                        </button>
                                                    </form>

                                                    <!-- Delete User -->
                                                    <?php if ($user['id'] !== $_SESSION['user_id']): ?>
                                                        <form method="post" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this user?')">
                                                            <input type="hidden" name="action" value="delete_user">
                                                            <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                                            <button type="submit" class="btn btn-danger btn-sm" title="Delete User">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>