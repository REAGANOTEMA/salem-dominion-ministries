<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$user = $db->query("SELECT * FROM users WHERE id = $user_id")->fetch_assoc();

// Get dashboard stats
$stats = [
    'users' => $db->query("SELECT COUNT(*) as count FROM users")->fetch_assoc()['count'],
    'news' => $db->query("SELECT COUNT(*) as count FROM news WHERE status = 'published'")->fetch_assoc()['count'],
    'events' => $db->query("SELECT COUNT(*) as count FROM events WHERE status = 'upcoming'")->fetch_assoc()['count'],
    'donations' => $db->query("SELECT COUNT(*) as count FROM donations WHERE status = 'completed'")->fetch_assoc()['count'],
    'messages' => $db->query("SELECT COUNT(*) as count FROM contact_messages WHERE status = 'unread'")->fetch_assoc()['count'],
    'prayer_requests' => $db->query("SELECT COUNT(*) as count FROM prayer_requests WHERE status = 'pending'")->fetch_assoc()['count']
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Salem Dominion Ministries</title>
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
        .stat-card {
            transition: transform 0.2s;
        }
        .stat-card:hover {
            transform: translateY(-2px);
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
                        <h5 class="mb-0"><i class="fas fa-church"></i> Admin Panel</h5>
                        <small>Welcome, <?php echo htmlspecialchars($user['first_name']); ?></small>
                    </div>
                    <nav class="nav flex-column py-3">
                        <a class="nav-link active" href="admin_dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
                        <a class="nav-link" href="admin_users.php"><i class="fas fa-users"></i> Users</a>
                        <a class="nav-link" href="admin_news.php"><i class="fas fa-newspaper"></i> News</a>
                        <a class="nav-link" href="admin_events.php"><i class="fas fa-calendar"></i> Events</a>
                        <a class="nav-link" href="admin_sermons.php"><i class="fas fa-bible"></i> Sermons</a>
                        <a class="nav-link" href="admin_gallery.php"><i class="fas fa-images"></i> Gallery</a>
                        <a class="nav-link" href="admin_donations.php"><i class="fas fa-dollar-sign"></i> Donations</a>
                        <a class="nav-link" href="admin_messages.php"><i class="fas fa-envelope"></i> Messages</a>
                        <a class="nav-link" href="admin_prayer.php"><i class="fas fa-pray"></i> Prayer Requests</a>
                        <a class="nav-link" href="admin_children.php"><i class="fas fa-child"></i> Children Ministry</a>
                        <a class="nav-link" href="admin_blog.php"><i class="fas fa-blog"></i> Blog Posts</a>
                        <a class="nav-link" href="admin_settings.php"><i class="fas fa-cog"></i> Settings</a>
                    </nav>
                    <div class="mt-auto p-3">
                        <a href="logout.php" class="btn btn-outline-light btn-sm w-100"><i class="fas fa-sign-out-alt"></i> Logout</a>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 px-4 py-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1><i class="fas fa-tachometer-alt"></i> Admin Dashboard</h1>
                    <a href="index.php" class="btn btn-outline-primary"><i class="fas fa-home"></i> View Site</a>
                </div>

                <!-- Stats Cards -->
                <div class="row mb-4">
                    <div class="col-md-6 col-lg-3 mb-3">
                        <div class="card stat-card border-primary">
                            <div class="card-body text-center">
                                <i class="fas fa-users fa-2x text-primary mb-2"></i>
                                <h4 class="card-title"><?php echo $stats['users']; ?></h4>
                                <p class="card-text text-muted">Total Users</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3 mb-3">
                        <div class="card stat-card border-success">
                            <div class="card-body text-center">
                                <i class="fas fa-newspaper fa-2x text-success mb-2"></i>
                                <h4 class="card-title"><?php echo $stats['news']; ?></h4>
                                <p class="card-text text-muted">Published News</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3 mb-3">
                        <div class="card stat-card border-info">
                            <div class="card-body text-center">
                                <i class="fas fa-calendar fa-2x text-info mb-2"></i>
                                <h4 class="card-title"><?php echo $stats['events']; ?></h4>
                                <p class="card-text text-muted">Upcoming Events</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3 mb-3">
                        <div class="card stat-card border-warning">
                            <div class="card-body text-center">
                                <i class="fas fa-dollar-sign fa-2x text-warning mb-2"></i>
                                <h4 class="card-title"><?php echo $stats['donations']; ?></h4>
                                <p class="card-text text-muted">Completed Donations</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6 col-lg-3 mb-3">
                        <div class="card stat-card border-danger">
                            <div class="card-body text-center">
                                <i class="fas fa-envelope fa-2x text-danger mb-2"></i>
                                <h4 class="card-title"><?php echo $stats['messages']; ?></h4>
                                <p class="card-text text-muted">Unread Messages</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3 mb-3">
                        <div class="card stat-card border-secondary">
                            <div class="card-body text-center">
                                <i class="fas fa-pray fa-2x text-secondary mb-2"></i>
                                <h4 class="card-title"><?php echo $stats['prayer_requests']; ?></h4>
                                <p class="card-text text-muted">Pending Prayers</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="fas fa-clock"></i> Recent Messages</h5>
                            </div>
                            <div class="card-body">
                                <?php
                                $messages = $db->query("SELECT * FROM contact_messages ORDER BY created_at DESC LIMIT 5");
                                if ($messages->num_rows > 0):
                                    while ($msg = $messages->fetch_assoc()):
                                ?>
                                <div class="d-flex mb-3">
                                    <div class="flex-grow-1">
                                        <strong><?php echo htmlspecialchars($msg['name']); ?></strong>
                                        <br><small class="text-muted"><?php echo htmlspecialchars($msg['subject']); ?></small>
                                        <br><small><?php echo date('M j, Y', strtotime($msg['created_at'])); ?></small>
                                    </div>
                                    <span class="badge bg-<?php echo $msg['status'] === 'unread' ? 'danger' : 'success'; ?>"><?php echo ucfirst($msg['status']); ?></span>
                                </div>
                                <?php endwhile; else: ?>
                                <p class="text-muted">No messages yet.</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="fas fa-pray"></i> Recent Prayer Requests</h5>
                            </div>
                            <div class="card-body">
                                <?php
                                $prayers = $db->query("SELECT * FROM prayer_requests ORDER BY created_at DESC LIMIT 5");
                                if ($prayers->num_rows > 0):
                                    while ($prayer = $prayers->fetch_assoc()):
                                ?>
                                <div class="d-flex mb-3">
                                    <div class="flex-grow-1">
                                        <strong><?php echo htmlspecialchars($prayer['requester_name']); ?></strong>
                                        <br><small class="text-muted"><?php echo htmlspecialchars($prayer['title']); ?></small>
                                        <br><small><?php echo date('M j, Y', strtotime($prayer['created_at'])); ?></small>
                                    </div>
                                    <span class="badge bg-<?php echo $prayer['status'] === 'pending' ? 'warning' : 'success'; ?>"><?php echo ucfirst($prayer['status']); ?></span>
                                </div>
                                <?php endwhile; else: ?>
                                <p class="text-muted">No prayer requests yet.</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>