<?php
// Hide all file paths from error messages
require_once 'hide_all_paths.php';

// Buffer output to catch any accidental output
ob_start();

// Include session helper and start session safely
require_once 'session_helper.php';
secure_session_start();

// Include database with error handling
try {
    require_once 'db.php';
} catch (Exception $e) {
    // Silent fail for production
    header('Location: login_complete.php');
    exit;
}

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login_complete.php');
    exit;
}

// Get user information with error handling
$user = null;
try {
    $user = $db->selectOne("SELECT * FROM users WHERE id = ?", [$_SESSION['user_id']]);
} catch (Exception $e) {
    // Handle database error gracefully
    header('Location: login_complete.php');
    exit;
}

if (!$user) {
    header('Location: login_complete.php');
    exit;
}

$user_role = $user['role'] ?? 'member';

// Initialize dashboard data with safe defaults
$dashboard_data = [
    'total_users' => 0,
    'total_events' => 0,
    'total_sermons' => 0,
    'total_donations' => 0,
    'total_bookings' => 0,
    'recent_activities' => [],
    'upcoming_bookings' => [],
    'prayer_requests' => [],
    'upcoming_events' => [],
    'recent_sermons' => [],
    'recent_news' => []
];

$gallery_images = [];
$upcoming_events = [];
$sermons = [];
$news_items = [];
$leadership = [];
$members = [];

// Get dashboard data based on role with comprehensive error handling
try {
    switch ($user_role) {
        case 'admin':
            $dashboard_data['total_users'] = $db->selectOne("SELECT COUNT(*) as count FROM users WHERE is_active = 1")['count'] ?? 0;
            $dashboard_data['total_events'] = $db->selectOne("SELECT COUNT(*) as count FROM events")['count'] ?? 0;
            $dashboard_data['total_sermons'] = $db->selectOne("SELECT COUNT(*) as count FROM sermons")['count'] ?? 0;
            $dashboard_data['total_donations'] = $db->selectOne("SELECT COUNT(*) as count FROM donations")['count'] ?? 0;
            $dashboard_data['total_bookings'] = $db->selectOne("SELECT COUNT(*) as count FROM pastor_bookings")['count'] ?? 0;
            $dashboard_data['recent_activities'] = $db->select("SELECT * FROM activity_logs ORDER BY created_at DESC LIMIT 10") ?? [];
            break;
            
        case 'pastor':
            $dashboard_data['upcoming_bookings'] = $db->select("SELECT * FROM pastor_bookings WHERE status = 'pending' ORDER BY booking_date ASC LIMIT 5") ?? [];
            $dashboard_data['prayer_requests'] = $db->select("SELECT * FROM prayer_requests WHERE status = 'pending' ORDER BY created_at DESC LIMIT 5") ?? [];
            $dashboard_data['recent_activities'] = $db->select("SELECT * FROM activity_logs WHERE user_id = ? ORDER BY created_at DESC LIMIT 5", [$_SESSION['user_id']]) ?? [];
            break;
            
        case 'member':
            $dashboard_data['upcoming_events'] = $db->select("SELECT * FROM events WHERE status = 'upcoming' ORDER BY event_date ASC LIMIT 5") ?? [];
            $dashboard_data['recent_sermons'] = $db->select("SELECT * FROM sermons WHERE status = 'published' ORDER BY sermon_date DESC LIMIT 3") ?? [];
            $dashboard_data['recent_news'] = $db->select("SELECT * FROM news WHERE status = 'published' ORDER BY published_at DESC LIMIT 3") ?? [];
            break;
            
        default:
            $dashboard_data['upcoming_events'] = $db->select("SELECT * FROM events WHERE status = 'upcoming' ORDER BY event_date ASC LIMIT 5") ?? [];
            $dashboard_data['recent_sermons'] = $db->select("SELECT * FROM sermons WHERE status = 'published' ORDER BY sermon_date DESC LIMIT 3") ?? [];
            break;
    }
    
    // Get common data with error handling
    $gallery_images = $db->select("SELECT * FROM gallery WHERE status = 'published' ORDER BY created_at DESC LIMIT 6") ?? [];
    $upcoming_events = $db->select("SELECT * FROM events WHERE status = 'upcoming' ORDER BY event_date ASC LIMIT 3") ?? [];
    
} catch (Exception $e) {
    // Handle database errors gracefully - keep default values
    error_log("Dashboard data query failed");
}

// Clean output buffer
ob_end_clean();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Salem Dominion Ministries</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            margin: 0;
            padding: 0;
        }
        
        .dashboard-header {
            background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%);
            color: white;
            padding: 60px 0;
            margin-bottom: 30px;
        }
        
        .welcome-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            padding: 30px;
            margin-bottom: 30px;
            text-align: center;
        }
        
        .stats-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            padding: 25px;
            text-align: center;
            margin-bottom: 30px;
            transition: transform 0.3s ease;
        }
        
        .stats-card:hover {
            transform: translateY(-5px);
        }
        
        .stats-number {
            font-size: 2.5rem;
            font-weight: bold;
            color: #0ea5e9;
            margin-bottom: 10px;
        }
        
        .activity-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            padding: 25px;
            margin-bottom: 20px;
            border-left: 5px solid #0ea5e9;
        }
        
        .activity-card.unread {
            border-left-color: #16a34a;
            background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
        }
        
        .user-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            border: 4px solid #0ea5e9;
            margin-bottom: 20px;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%);
            border: none;
            border-radius: 10px;
            padding: 10px 20px;
            font-weight: 600;
            transition: all 0.3s ease;
            color: white;
            text-decoration: none;
            display: inline-block;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(14, 165, 233, 0.3);
            color: white;
        }
        
        .btn-success {
            background: linear-gradient(135deg, #16a34a 0%, #15803d 100%);
            border: none;
            border-radius: 10px;
            padding: 10px 20px;
            font-weight: 600;
            transition: all 0.3s ease;
            color: white;
            text-decoration: none;
            display: inline-block;
        }
        
        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(22, 163, 74, 0.3);
            color: white;
        }
        
        .btn-info {
            background: linear-gradient(135deg, #7c3aed 0%, #6d28d0 100%);
            border: none;
            border-radius: 10px;
            padding: 10px 20px;
            font-weight: 600;
            transition: all 0.3s ease;
            color: white;
            text-decoration: none;
            display: inline-block;
        }
        
        .btn-info:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(124, 58, 237, 0.3);
            color: white;
        }
        
        .btn-warning {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            border: none;
            border-radius: 10px;
            padding: 10px 20px;
            font-weight: 600;
            transition: all 0.3s ease;
            color: white;
            text-decoration: none;
            display: inline-block;
        }
        
        .btn-warning:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(245, 158, 11, 0.3);
            color: white;
        }
        
        .badge-new {
            background: #16a34a;
            color: white;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        
        .quick-action-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            padding: 25px;
            text-align: center;
            margin-bottom: 30px;
            transition: transform 0.3s ease;
            height: 100%;
        }
        
        .quick-action-card:hover {
            transform: translateY(-5px);
        }
        
        .action-icon {
            font-size: 3rem;
            color: #0ea5e9;
            margin-bottom: 20px;
        }
        
        @media (max-width: 768px) {
            .stats-card {
                padding: 20px;
            }
            
            .stats-number {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <?php include 'components/universal_nav.php'; ?>

    <!-- Dashboard Header -->
    <div class="dashboard-header">
        <div class="container text-center">
            <h1><i class="fas fa-tachometer-alt"></i> Welcome to Your Dashboard</h1>
            <p class="lead">Manage your profile and stay connected with our church</p>
        </div>
    </div>

    <div class="container">
        <!-- Welcome Section -->
        <div class="welcome-card">
            <div class="row align-items-center">
                <div class="col-md-3">
                    <?php if ($user['avatar'] && file_exists($user['avatar'])): ?>
                        <img src="<?php echo htmlspecialchars($user['avatar']); ?>" alt="Avatar" class="user-avatar">
                    <?php else: ?>
                        <div class="user-avatar d-flex align-items-center justify-content-center bg-primary text-white">
                            <i class="fas fa-user fa-2x"></i>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="col-md-9">
                    <h2>Welcome, <?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?>!</h2>
                    <p class="text-muted"><?php echo htmlspecialchars($user['email']); ?></p>
                    <p class="text-muted">Role: <?php echo ucfirst($user['role']); ?></p>
                    <div class="mt-3">
                        <a href="profile.php" class="btn-primary me-2">
                            <i class="fas fa-user-edit"></i> Edit Profile
                        </a>
                        <a href="donations.php" class="btn-success me-2">
                            <i class="fas fa-donate"></i> Give
                        </a>
                        <a href="book_pastor.php" class="btn-info">
                            <i class="fas fa-calendar"></i> Book Pastor
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Role-based Dashboard Content -->
        <?php if ($user_role === 'admin'): ?>
            <!-- Admin Statistics -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="stats-card">
                        <div class="stats-number"><?php echo $dashboard_data['total_users']; ?></div>
                        <div class="text-muted">Total Users</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stats-card">
                        <div class="stats-number"><?php echo $dashboard_data['total_events']; ?></div>
                        <div class="text-muted">Total Events</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stats-card">
                        <div class="stats-number"><?php echo $dashboard_data['total_donations']; ?></div>
                        <div class="text-muted">Total Donations</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stats-card">
                        <div class="stats-number"><?php echo $dashboard_data['total_bookings']; ?></div>
                        <div class="text-muted">Pastor Bookings</div>
                    </div>
                </div>
            </div>
            
            <!-- Admin Quick Actions -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="quick-action-card">
                        <div class="action-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <h5>User Management</h5>
                        <p class="text-muted">Manage church members</p>
                        <a href="admin_users.php" class="btn-primary">Manage Users</a>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="quick-action-card">
                        <div class="action-icon">
                            <i class="fas fa-donate"></i>
                        </div>
                        <h5>Donations</h5>
                        <p class="text-muted">Track donations</p>
                        <a href="admin_donations_perfect.php" class="btn-primary">Manage Donations</a>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="quick-action-card">
                        <div class="action-icon">
                            <i class="fas fa-bullhorn"></i>
                        </div>
                        <h5>Communications</h5>
                        <p class="text-muted">Send messages</p>
                        <a href="admin_communications.php" class="btn-primary">Send Messages</a>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="quick-action-card">
                        <div class="action-icon">
                            <i class="fas fa-calendar"></i>
                        </div>
                        <h5>Pastor Bookings</h5>
                        <p class="text-muted">Manage appointments</p>
                        <a href="admin_pastor_bookings.php" class="btn-primary">Manage Bookings</a>
                    </div>
                </div>
            </div>
            
        <?php elseif ($user_role === 'pastor'): ?>
            <!-- Pastor Dashboard -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="stats-card">
                        <div class="stats-number"><?php echo count($dashboard_data['upcoming_bookings']); ?></div>
                        <div class="text-muted">Pending Bookings</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="stats-card">
                        <div class="stats-number"><?php echo count($dashboard_data['prayer_requests']); ?></div>
                        <div class="text-muted">Prayer Requests</div>
                    </div>
                </div>
            </div>
            
        <?php else: ?>
            <!-- Member Dashboard -->
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="stats-card">
                        <div class="stats-number"><?php echo count($upcoming_events); ?></div>
                        <div class="text-muted">Upcoming Events</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stats-card">
                        <div class="stats-number"><?php echo count($gallery_images); ?></div>
                        <div class="text-muted">Gallery Items</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stats-card">
                        <div class="stats-number">
                            <?php echo date('d'); ?>
                        </div>
                        <div class="text-muted">Day of Month</div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!-- Recent Activities -->
        <div class="row">
            <div class="col-md-12">
                <h3 class="mb-4"><i class="fas fa-history"></i> Recent Activities</h3>
                
                <?php if (!empty($dashboard_data['recent_activities'])): ?>
                    <?php foreach ($dashboard_data['recent_activities'] as $activity): ?>
                        <div class="activity-card">
                            <div class="row">
                                <div class="col-md-8">
                                    <h5><?php echo htmlspecialchars($activity['activity'] ?? 'System Activity'); ?></h5>
                                    <p class="text-muted">
                                        <?php echo date('M j, Y g:i A', strtotime($activity['created_at'] ?? 'now')); ?>
                                    </p>
                                </div>
                                <div class="col-md-4">
                                    <div class="text-end">
                                        <small class="text-muted">
                                            Status: <?php echo ucfirst($activity['status'] ?? 'completed'); ?>
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="text-center py-5">
                        <i class="fas fa-history fa-3x text-muted mb-3"></i>
                        <h4 class="text-muted">No recent activities</h4>
                        <p class="text-muted">Start exploring our church features!</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="row mt-5">
            <div class="col-md-12">
                <h3 class="mb-4"><i class="fas fa-bolt"></i> Quick Actions</h3>
                <div class="row">
                    <div class="col-md-3">
                        <a href="donations.php" class="btn-success w-100 mb-3">
                            <i class="fas fa-donate"></i> Make Donation
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="book_pastor.php" class="btn-info w-100 mb-3">
                            <i class="fas fa-calendar"></i> Book Pastor
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="gallery.php" class="btn-primary w-100 mb-3">
                            <i class="fas fa-images"></i> View Gallery
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="events.php" class="btn-warning w-100 mb-3">
                            <i class="fas fa-calendar-alt"></i> View Events
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Include Footer -->
    <?php include 'components/ultimate_footer_new.php'; ?>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
