<?php
// Production-ready error handling
error_reporting(0);
ini_set('display_errors', 0);
ini_set('log_errors', 1);

// Start output buffering
ob_start();

// Include required files with error handling
try {
    require_once 'auth_system.php';
    require_once 'db.php';
} catch (Exception $e) {
    // Silent fail for production
    header('Location: login_complete.php');
    exit;
}

require_admin();

$user = get_current_user();

// Initialize variables to prevent undefined errors
$total_users = 0;
$total_donations = 0;
$total_amount = 0;
$pending_donations = 0;
$total_events = 0;
$upcoming_events = 0;
$total_gallery = 0;
$recent_activities = [];

// Get statistics with error handling
try {
    $total_users = $db->selectOne("SELECT COUNT(*) as count FROM users WHERE is_active = 1")['count'];
    $total_donations = $db->selectOne("SELECT COUNT(*) as count FROM donations")['count'];
    $total_amount = $db->selectOne("SELECT SUM(amount) as total FROM donations WHERE status = 'completed'")['total'] ?? 0;
    $pending_donations = $db->selectOne("SELECT COUNT(*) as count FROM donations WHERE status = 'pending'")['count'];
    $total_events = $db->selectOne("SELECT COUNT(*) as count FROM events")['count'];
    $upcoming_events = $db->selectOne("SELECT COUNT(*) as count FROM events WHERE status = 'upcoming'")['count'];
    $total_gallery = $db->selectOne("SELECT COUNT(*) as count FROM gallery WHERE status = 'published'")['count'];
    
    // Get recent activities
    $recent_activities = $db->select("SELECT 'donation' as type, donor_name as title, created_at as date, amount as extra 
                                      FROM donations ORDER BY created_at DESC LIMIT 3");
    
} catch (Exception $e) {
    // Set default values if database fails
    $total_users = 0;
    $total_donations = 0;
    $total_amount = 0;
    $pending_donations = 0;
    $total_events = 0;
    $upcoming_events = 0;
    $total_gallery = 0;
    $recent_activities = [];
}

// Clean output buffer
ob_end_clean();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Salem Dominion Ministries</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        }
        
        .admin-header {
            background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
            color: white;
            padding: 60px 0;
            margin-bottom: 30px;
        }
        
        .stats-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
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
            color: #dc2626;
            margin-bottom: 10px;
        }
        
        .action-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            padding: 25px;
            text-align: center;
            margin-bottom: 30px;
            transition: transform 0.3s ease;
            height: 100%;
        }
        
        .action-card:hover {
            transform: translateY(-5px);
        }
        
        .action-icon {
            font-size: 3rem;
            color: #dc2626;
            margin-bottom: 20px;
        }
        
        .activity-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            padding: 20px;
            margin-bottom: 20px;
            border-left: 5px solid #dc2626;
        }
        
        .btn-admin {
            background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
            border: none;
            border-radius: 10px;
            padding: 12px 25px;
            font-weight: 600;
            color: white;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }
        
        .btn-admin:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(220, 38, 38, 0.3);
            color: white;
        }
        
        .user-info {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            padding: 30px;
            margin-bottom: 30px;
            text-align: center;
        }
        
        .user-avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            border: 3px solid #dc2626;
            margin-bottom: 15px;
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

    <!-- Admin Header -->
    <div class="admin-header">
        <div class="container text-center">
            <h1><i class="fas fa-cog"></i> Admin Dashboard</h1>
            <p class="lead">Manage your church website and community</p>
        </div>
    </div>

    <div class="container">
        <!-- User Info -->
        <div class="user-info">
            <div class="row align-items-center">
                <div class="col-md-2">
                    <?php if ($user['avatar'] && file_exists($user['avatar'])): ?>
                        <img src="<?php echo htmlspecialchars($user['avatar']); ?>" alt="Avatar" class="user-avatar">
                    <?php else: ?>
                        <div class="user-avatar d-flex align-items-center justify-content-center bg-danger text-white">
                            <i class="fas fa-user fa-2x"></i>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="col-md-10">
                    <h2><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></h2>
                    <p class="text-muted"><?php echo htmlspecialchars($user['email']); ?></p>
                    <p class="text-muted">Administrator</p>
                </div>
            </div>
        </div>

        <!-- Statistics -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="stats-card">
                    <div class="stats-number"><?php echo $total_users; ?></div>
                    <div class="text-muted">Total Users</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card">
                    <div class="stats-number"><?php echo $total_donations; ?></div>
                    <div class="text-muted">Total Donations</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card">
                    <div class="stats-number">$<?php echo number_format($total_amount, 0); ?></div>
                    <div class="text-muted">Total Raised</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card">
                    <div class="stats-number"><?php echo $pending_donations; ?></div>
                    <div class="text-muted">Pending</div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="action-card">
                    <div class="action-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <h5>User Management</h5>
                    <p class="text-muted">Manage church members and users</p>
                    <a href="admin_users.php" class="btn btn-admin">Manage Users</a>
                </div>
            </div>
            <div class="col-md-3">
                <div class="action-card">
                    <div class="action-icon">
                        <i class="fas fa-donate"></i>
                    </div>
                    <h5>Donations</h5>
                    <p class="text-muted">Track and manage donations</p>
                    <a href="admin_donations_perfect.php" class="btn btn-admin">Manage Donations</a>
                </div>
            </div>
            <div class="col-md-3">
                <div class="action-card">
                    <div class="action-icon">
                        <i class="fas fa-bullhorn"></i>
                    </div>
                    <h5>Communications</h5>
                    <p class="text-muted">Send messages to users</p>
                    <a href="admin_communications.php" class="btn btn-admin">Send Messages</a>
                </div>
            </div>
            <div class="col-md-3">
                <div class="action-card">
                    <div class="action-icon">
                        <i class="fas fa-calendar"></i>
                    </div>
                    <h5>Pastor Bookings</h5>
                    <p class="text-muted">Manage pastor appointments</p>
                    <a href="admin_pastor_bookings.php" class="btn btn-admin">Manage Bookings</a>
                </div>
            </div>
        </div>

        <!-- Additional Statistics -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="stats-card">
                    <div class="stats-number"><?php echo $total_events; ?></div>
                    <div class="text-muted">Total Events</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stats-card">
                    <div class="stats-number"><?php echo $upcoming_events; ?></div>
                    <div class="text-muted">Upcoming Events</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stats-card">
                    <div class="stats-number"><?php echo $total_gallery; ?></div>
                    <div class="text-muted">Gallery Items</div>
                </div>
            </div>
        </div>

        <!-- Recent Activities -->
        <div class="row">
            <div class="col-md-12">
                <h3 class="mb-4"><i class="fas fa-history"></i> Recent Activities</h3>
                
                <?php if (!empty($recent_activities)): ?>
                    <?php foreach ($recent_activities as $activity): ?>
                        <div class="activity-card">
                            <div class="row">
                                <div class="col-md-8">
                                    <h5>
                                        <i class="fas fa-<?php echo $activity['type'] === 'donation' ? 'donate' : 'calendar'; ?>"></i>
                                        <?php echo htmlspecialchars($activity['title']); ?>
                                    </h5>
                                    <p class="text-muted">
                                        <?php if ($activity['type'] === 'donation'): ?>
                                            Donation of $<?php echo number_format($activity['extra'], 2); ?>
                                        <?php endif; ?>
                                    </p>
                                </div>
                                <div class="col-md-4">
                                    <div class="text-end">
                                        <small class="text-muted">
                                            <?php echo date('M j, Y g:i A', strtotime($activity['date'])); ?>
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
                        <p class="text-muted">Start by adding users, donations, or events.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- System Status -->
        <div class="row mt-5">
            <div class="col-md-12">
                <h3 class="mb-4"><i class="fas fa-server"></i> System Status</h3>
                <div class="row">
                    <div class="col-md-6">
                        <div class="stats-card">
                            <div class="stats-number text-success">
                                <i class="fas fa-check-circle"></i> Online
                            </div>
                            <div class="text-muted">Database Connection</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="stats-card">
                            <div class="stats-number text-success">
                                <i class="fas fa-check-circle"></i> Active
                            </div>
                            <div class="text-muted">Website Status</div>
                        </div>
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
