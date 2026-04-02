<?php
require_once 'auth_system.php';
require_once 'db.php';

require_admin();

$user = get_current_user();

// Get system statistics
$total_users = $db->selectOne("SELECT COUNT(*) as count FROM users WHERE is_active = 1")['count'];
$total_gallery = $db->selectOne("SELECT COUNT(*) as count FROM gallery WHERE status = 'published'")['count'];
$total_events = $db->selectOne("SELECT COUNT(*) as count FROM events WHERE status = 'upcoming'")['count'];
$total_donations = $db->selectOne("SELECT COUNT(*) as count FROM donations WHERE status = 'completed'")['count'];

// Get recent activities
$recent_users = $db->select("SELECT first_name, last_name, email, created_at FROM users ORDER BY created_at DESC LIMIT 5");
$recent_gallery = $db->select("SELECT title, uploaded_by, created_at FROM gallery ORDER BY created_at DESC LIMIT 5");
$recent_communications = $db->select("SELECT title, sent_at FROM admin_communications ORDER BY sent_at DESC LIMIT 5");

// Get user roles distribution
$role_stats = $db->select("SELECT role, COUNT(*) as count FROM users WHERE is_active = 1 GROUP BY role");
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
            background: #f8fafc;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
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
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            padding: 30px;
            text-align: center;
            margin-bottom: 30px;
            transition: transform 0.3s ease;
            cursor: pointer;
        }
        
        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
        }
        
        .stats-number {
            font-size: 3rem;
            font-weight: bold;
            color: #dc2626;
            margin-bottom: 10px;
        }
        
        .stats-icon {
            font-size: 2.5rem;
            color: #dc2626;
            margin-bottom: 15px;
        }
        
        .activity-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            padding: 25px;
            margin-bottom: 30px;
        }
        
        .activity-item {
            padding: 15px 0;
            border-bottom: 1px solid #e5e7eb;
        }
        
        .activity-item:last-child {
            border-bottom: none;
        }
        
        .btn-admin {
            background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
            border: none;
            border-radius: 10px;
            padding: 12px 25px;
            font-weight: 600;
            color: white;
            transition: all 0.3s ease;
        }
        
        .btn-admin:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(220, 38, 38, 0.3);
            color: white;
        }
        
        .role-badge {
            background: #f3f4f6;
            padding: 4px 12px;
            border-radius: 15px;
            font-size: 0.8rem;
            display: inline-block;
            margin: 2px;
        }
        
        .admin-actions {
            background: linear-gradient(135deg, #fef2f2 0%, #ffffff 100%);
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 30px;
            border: 2px solid #dc2626;
        }
        
        .power-badge {
            background: #dc2626;
            color: white;
            padding: 8px 20px;
            border-radius: 20px;
            font-size: 0.9rem;
            display: inline-block;
            margin-bottom: 20px;
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
            <div class="navbar-nav ms-auto">
                <a class="nav-link active" href="admin_dashboard.php">
                    <i class="fas fa-tachometer-alt"></i> Admin Dashboard
                </a>
                <a class="nav-link" href="admin_communications.php">
                    <i class="fas fa-bullhorn"></i> Communications
                </a>
                <a class="nav-link" href="admin_users.php">
                    <i class="fas fa-users"></i> Users
                </a>
                <a class="nav-link" href="admin_gallery_new.php">
                    <i class="fas fa-images"></i> Gallery
                </a>
                <a class="nav-link" href="admin_events.php">
                    <i class="fas fa-calendar"></i> Events
                </a>
                <a class="nav-link" href="profile.php">
                    <i class="fas fa-user"></i> Profile
                </a>
                <a class="nav-link" href="auth_system.php?action=logout">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>
        </div>
    </nav>

    <div class="admin-header">
        <div class="container text-center">
            <h1><i class="fas fa-crown"></i> Admin Dashboard</h1>
            <p class="lead">Complete control over your church management system</p>
            <span class="power-badge">
                <i class="fas fa-shield-alt"></i> FULL ADMIN POWERS
            </span>
        </div>
    </div>

    <div class="container">
        <!-- Admin Powers Section -->
        <div class="admin-actions">
            <h4><i class="fas fa-bolt"></i> Admin Superpowers</h4>
            <p class="text-muted">You have complete control over the entire system. Manage users, content, communications, and more.</p>
            
            <div class="row">
                <div class="col-md-3 mb-3">
                    <a href="admin_users.php" class="btn btn-admin w-100">
                        <i class="fas fa-users"></i><br>
                        Manage Users
                    </a>
                </div>
                <div class="col-md-3 mb-3">
                    <a href="admin_communications.php" class="btn btn-admin w-100">
                        <i class="fas fa-bullhorn"></i><br>
                        Send Messages
                    </a>
                </div>
                <div class="col-md-3 mb-3">
                    <a href="admin_gallery_new.php" class="btn btn-admin w-100">
                        <i class="fas fa-images"></i><br>
                        Gallery Control
                    </a>
                </div>
                <div class="col-md-3 mb-3">
                    <a href="admin_events.php" class="btn btn-admin w-100">
                        <i class="fas fa-calendar"></i><br>
                        Event Management
                    </a>
                </div>
            </div>
            
            <div class="row mt-3">
                <div class="col-md-3 mb-3">
                    <a href="admin_donations.php" class="btn btn-admin w-100">
                        <i class="fas fa-donate"></i><br>
                        Donations
                    </a>
                </div>
                <div class="col-md-3 mb-3">
                    <a href="admin_prayer_requests.php" class="btn btn-admin w-100">
                        <i class="fas fa-pray"></i><br>
                        Prayer Requests
                    </a>
                </div>
                <div class="col-md-3 mb-3">
                    <a href="admin_sermons.php" class="btn btn-admin w-100">
                        <i class="fas fa-bible"></i><br>
                        Sermons
                    </a>
                </div>
                <div class="col-md-3 mb-3">
                    <a href="admin_news.php" class="btn btn-admin w-100">
                        <i class="fas fa-newspaper"></i><br>
                        News & Updates
                    </a>
                </div>
            </div>
        </div>
        
        <!-- System Statistics -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="stats-card" onclick="window.location.href='admin_users.php'">
                    <div class="stats-icon"><i class="fas fa-users"></i></div>
                    <div class="stats-number"><?php echo $total_users; ?></div>
                    <div class="text-muted">Total Users</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card" onclick="window.location.href='admin_gallery_new.php'">
                    <div class="stats-icon"><i class="fas fa-images"></i></div>
                    <div class="stats-number"><?php echo $total_gallery; ?></div>
                    <div class="text-muted">Gallery Items</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card" onclick="window.location.href='admin_events.php'">
                    <div class="stats-icon"><i class="fas fa-calendar"></i></div>
                    <div class="stats-number"><?php echo $total_events; ?></div>
                    <div class="text-muted">Upcoming Events</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card" onclick="window.location.href='admin_donations.php'">
                    <div class="stats-icon"><i class="fas fa-donate"></i></div>
                    <div class="stats-number"><?php echo $total_donations; ?></div>
                    <div class="text-muted">Donations</div>
                </div>
            </div>
        </div>
        
        <!-- User Roles Distribution -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="activity-card">
                    <h5><i class="fas fa-chart-pie"></i> User Roles Distribution</h5>
                    <?php foreach ($role_stats as $role): ?>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span><?php echo ucfirst($role['role']); ?>s</span>
                            <span class="role-badge"><?php echo $role['count']; ?></span>
                        </div>
                        <div class="progress mb-3" style="height: 8px;">
                            <div class="progress-bar bg-danger" style="width: <?php echo ($role['count'] / $total_users) * 100; ?>%"></div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="activity-card">
                    <h5><i class="fas fa-cog"></i> Quick Actions</h5>
                    <div class="row">
                        <div class="col-6 mb-3">
                            <a href="register.php" class="btn btn-outline-primary w-100">
                                <i class="fas fa-user-plus"></i> Add User
                            </a>
                        </div>
                        <div class="col-6 mb-3">
                            <a href="admin_communications.php" class="btn btn-outline-success w-100">
                                <i class="fas fa-paper-plane"></i> Send Message
                            </a>
                        </div>
                        <div class="col-6 mb-3">
                            <a href="admin_gallery_new.php" class="btn btn-outline-info w-100">
                                <i class="fas fa-upload"></i> Upload Image
                            </a>
                        </div>
                        <div class="col-6 mb-3">
                            <a href="admin_events.php" class="btn btn-outline-warning w-100">
                                <i class="fas fa-plus"></i> Create Event
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Recent Activities -->
        <div class="row">
            <div class="col-md-4">
                <div class="activity-card">
                    <h5><i class="fas fa-user-plus"></i> Recent Users</h5>
                    <?php if (empty($recent_users)): ?>
                        <p class="text-muted">No recent users</p>
                    <?php else: ?>
                        <?php foreach ($recent_users as $recent_user): ?>
                            <div class="activity-item">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <strong><?php echo htmlspecialchars($recent_user['first_name'] . ' ' . $recent_user['last_name']); ?></strong>
                                        <br>
                                        <small class="text-muted"><?php echo htmlspecialchars($recent_user['email']); ?></small>
                                    </div>
                                    <small class="text-muted">
                                        <?php echo date('M j', strtotime($recent_user['created_at'])); ?>
                                    </small>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="activity-card">
                    <h5><i class="fas fa-images"></i> Recent Gallery</h5>
                    <?php if (empty($recent_gallery)): ?>
                        <p class="text-muted">No recent gallery items</p>
                    <?php else: ?>
                        <?php foreach ($recent_gallery as $gallery_item): ?>
                            <div class="activity-item">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <strong><?php echo htmlspecialchars($gallery_item['title']); ?></strong>
                                        <br>
                                        <small class="text-muted">By User ID: <?php echo $gallery_item['uploaded_by']; ?></small>
                                    </div>
                                    <small class="text-muted">
                                        <?php echo date('M j', strtotime($gallery_item['created_at'])); ?>
                                    </small>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="activity-card">
                    <h5><i class="fas fa-bullhorn"></i> Recent Communications</h5>
                    <?php if (empty($recent_communications)): ?>
                        <p class="text-muted">No recent communications</p>
                    <?php else: ?>
                        <?php foreach ($recent_communications as $comm): ?>
                            <div class="activity-item">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <strong><?php echo htmlspecialchars($comm['title']); ?></strong>
                                    </div>
                                    <small class="text-muted">
                                        <?php echo date('M j', strtotime($comm['sent_at'])); ?>
                                    </small>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
