<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'pastor') {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$user = $db->query("SELECT * FROM users WHERE id = $user_id")->fetch_assoc();

// Get dashboard stats
$stats = [
    'sermons' => $db->query("SELECT COUNT(*) as count FROM sermons WHERE created_by = $user_id")->fetch_assoc()['count'],
    'upcoming_events' => $db->query("SELECT COUNT(*) as count FROM events WHERE event_date >= CURDATE()")->fetch_assoc()['count'],
    'prayer_requests' => $db->query("SELECT COUNT(*) as count FROM prayer_requests WHERE status = 'pending'")->fetch_assoc()['count'],
    'members' => $db->query("SELECT COUNT(*) as count FROM users WHERE role = 'member'")->fetch_assoc()['count']
];

// Get recent sermons
$recent_sermons = $db->query("SELECT * FROM sermons WHERE created_by = $user_id ORDER BY sermon_date DESC LIMIT 5");

// Get upcoming events
$upcoming_events = $db->query("SELECT * FROM events WHERE event_date >= CURDATE() ORDER BY event_date LIMIT 5");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pastor Dashboard - Salem Dominion Ministries</title>
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
                        <h5 class="mb-0"><i class="fas fa-cross"></i> Pastor Panel</h5>
                        <small>Welcome, <?php echo htmlspecialchars($user['first_name']); ?></small>
                    </div>
                    <nav class="nav flex-column py-3">
                        <a class="nav-link active" href="pastor_dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
                        <a class="nav-link" href="admin_sermons.php"><i class="fas fa-bible"></i> Sermons</a>
                        <a class="nav-link" href="admin_events.php"><i class="fas fa-calendar"></i> Events</a>
                        <a class="nav-link" href="admin_prayer_requests.php"><i class="fas fa-pray"></i> Prayer Requests</a>
                        <a class="nav-link" href="admin_users.php"><i class="fas fa-users"></i> Members</a>
                        <a class="nav-link" href="member_dashboard.php"><i class="fas fa-user-edit"></i> My Profile</a>
                    </nav>
                    <div class="mt-auto p-3">
                        <a href="logout.php" class="btn btn-outline-light btn-sm w-100"><i class="fas fa-sign-out-alt"></i> Logout</a>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 px-4 py-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1><i class="fas fa-cross"></i> Pastor Dashboard</h1>
                    <a href="index.php" class="btn btn-outline-primary"><i class="fas fa-home"></i> View Site</a>
                </div>

                <!-- Stats Cards -->
                <div class="row mb-4">
                    <div class="col-md-6 col-lg-3 mb-3">
                        <div class="card stat-card border-primary">
                            <div class="card-body text-center">
                                <i class="fas fa-bible fa-2x text-primary mb-2"></i>
                                <h4 class="card-title"><?php echo $stats['sermons']; ?></h4>
                                <p class="card-text text-muted">My Sermons</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3 mb-3">
                        <div class="card stat-card border-info">
                            <div class="card-body text-center">
                                <i class="fas fa-calendar fa-2x text-info mb-2"></i>
                                <h4 class="card-title"><?php echo $stats['upcoming_events']; ?></h4>
                                <p class="card-text text-muted">Upcoming Events</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3 mb-3">
                        <div class="card stat-card border-warning">
                            <div class="card-body text-center">
                                <i class="fas fa-pray fa-2x text-warning mb-2"></i>
                                <h4 class="card-title"><?php echo $stats['prayer_requests']; ?></h4>
                                <p class="card-text text-muted">Pending Prayers</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3 mb-3">
                        <div class="card stat-card border-success">
                            <div class="card-body text-center">
                                <i class="fas fa-users fa-2x text-success mb-2"></i>
                                <h4 class="card-title"><?php echo $stats['members']; ?></h4>
                                <p class="card-text text-muted">Church Members</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <!-- Recent Sermons -->
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="fas fa-bible"></i> Recent Sermons</h5>
                            </div>
                            <div class="card-body">
                                <?php if ($recent_sermons->num_rows > 0): ?>
                                    <div class="list-group list-group-flush">
                                        <?php while ($sermon = $recent_sermons->fetch_assoc()): ?>
                                        <div class="list-group-item px-0">
                                            <div class="d-flex w-100 justify-content-between">
                                                <h6 class="mb-1"><?php echo htmlspecialchars($sermon['title']); ?></h6>
                                                <small><?php echo date('M j, Y', strtotime($sermon['sermon_date'])); ?></small>
                                            </div>
                                            <small class="text-muted">
                                                <i class="fas fa-book"></i> <?php echo htmlspecialchars($sermon['bible_reference'] ?? 'No reference'); ?>
                                            </small>
                                            <span class="badge bg-<?php echo $sermon['status'] === 'published' ? 'success' : 'warning'; ?> ms-2">
                                                <?php echo ucfirst($sermon['status']); ?>
                                            </span>
                                        </div>
                                        <?php endwhile; ?>
                                    </div>
                                <?php else: ?>
                                    <p class="text-muted">No sermons yet.</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Upcoming Events -->
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="fas fa-calendar"></i> Upcoming Events</h5>
                            </div>
                            <div class="card-body">
                                <?php if ($upcoming_events->num_rows > 0): ?>
                                    <div class="list-group list-group-flush">
                                        <?php while ($event = $upcoming_events->fetch_assoc()): ?>
                                        <div class="list-group-item px-0">
                                            <div class="d-flex w-100 justify-content-between">
                                                <h6 class="mb-1"><?php echo htmlspecialchars($event['title']); ?></h6>
                                                <small><?php echo date('M j, Y', strtotime($event['event_date'])); ?></small>
                                            </div>
                                            <small class="text-muted">
                                                <i class="fas fa-clock"></i> <?php echo date('g:i A', strtotime($event['event_date'])); ?>
                                            </small>
                                            <span class="badge bg-<?php echo $event['status'] === 'upcoming' ? 'info' : 'secondary'; ?> ms-2">
                                                <?php echo ucfirst($event['status']); ?>
                                            </span>
                                        </div>
                                        <?php endwhile; ?>
                                    </div>
                                <?php else: ?>
                                    <p class="text-muted">No upcoming events.</p>
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