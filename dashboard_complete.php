<?php
require_once 'auth_system.php';
require_once 'db.php';

require_login();

$user = get_current_user();

// Get user's communications
$communications = $db->select("SELECT ac.*, uc.status, uc.received_at, uc.read_at, u.first_name, u.last_name 
                                FROM user_communications uc 
                                JOIN admin_communications ac ON uc.communication_id = ac.id 
                                LEFT JOIN users u ON ac.sent_by = u.id 
                                WHERE uc.user_id = ? 
                                ORDER BY ac.sent_at DESC", [$user['id']]);

// Mark communications as read
foreach ($communications as $comm) {
    if ($comm['status'] === 'sent') {
        $db->query("UPDATE user_communications SET status = 'read', read_at = NOW() WHERE communication_id = ? AND user_id = ?", 
                 [$comm['id'], $user['id']]);
    }
}

// Get user statistics
$total_communications = count($communications);
$unread_count = 0;
foreach ($communications as $comm) {
    if ($comm['status'] === 'sent') {
        $unread_count++;
    }
}
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
            background: #f8fafc;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
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
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            padding: 30px;
            margin-bottom: 30px;
            text-align: center;
        }
        
        .user-avatar {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            border: 4px solid white;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            object-fit: cover;
            margin-bottom: 20px;
        }
        
        .communication-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            padding: 25px;
            margin-bottom: 20px;
            border-left: 5px solid #0ea5e9;
            transition: transform 0.3s ease;
        }
        
        .communication-card:hover {
            transform: translateY(-3px);
        }
        
        .communication-card.unread {
            border-left-color: #dc2626;
            background: linear-gradient(135deg, #fef2f2 0%, #ffffff 100%);
        }
        
        .priority-high {
            border-left-color: #dc2626;
        }
        
        .priority-normal {
            border-left-color: #0ea5e9;
        }
        
        .priority-low {
            border-left-color: #16a34a;
        }
        
        .priority-badge {
            padding: 4px 12px;
            border-radius: 15px;
            font-size: 0.7rem;
            font-weight: 600;
            display: inline-block;
            margin: 2px;
        }
        
        .priority-high .priority-badge {
            background: #dc2626;
            color: white;
        }
        
        .priority-normal .priority-badge {
            background: #0ea5e9;
            color: white;
        }
        
        .priority-low .priority-badge {
            background: #16a34a;
            color: white;
        }
        
        .stats-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            padding: 25px;
            text-align: center;
            margin-bottom: 30px;
        }
        
        .stats-number {
            font-size: 2rem;
            font-weight: bold;
            color: #0ea5e9;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%);
            border: none;
            border-radius: 10px;
            padding: 12px 25px;
            font-weight: 600;
        }
        
        .role-badge {
            background: #16a34a;
            color: white;
            padding: 8px 20px;
            border-radius: 20px;
            font-size: 0.9rem;
            display: inline-block;
        }
        
        .communication-content {
            line-height: 1.6;
            white-space: pre-wrap;
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
                <a class="nav-link active" href="dashboard.php">
                    <i class="fas fa-home"></i> Dashboard
                </a>
                <a class="nav-link" href="profile.php">
                    <i class="fas fa-user"></i> Profile
                </a>
                <a class="nav-link" href="gallery.php">
                    <i class="fas fa-images"></i> Gallery
                </a>
                <?php if (is_admin()): ?>
                    <a class="nav-link" href="admin_dashboard.php">
                        <i class="fas fa-cog"></i> Admin
                    </a>
                <?php endif; ?>
                <a class="nav-link" href="auth_system.php?action=logout">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>
        </div>
    </nav>

    <div class="dashboard-header">
        <div class="container text-center">
            <h1><i class="fas fa-home"></i> Welcome Back, <?php echo htmlspecialchars($user['name']); ?>!</h1>
            <p class="lead">Your church community dashboard</p>
        </div>
    </div>

    <div class="container">
        <!-- Welcome Card -->
        <div class="welcome-card">
            <?php if ($user['avatar'] && file_exists($user['avatar'])): ?>
                <img src="<?php echo htmlspecialchars($user['avatar']); ?>" alt="Avatar" class="user-avatar">
            <?php else: ?>
                <img src="assets/default-avatar.png" alt="Avatar" class="user-avatar">
            <?php endif; ?>
            
            <h3><?php echo htmlspecialchars($user['name']); ?></h3>
            <span class="role-badge">
                <i class="fas fa-user-tag"></i> <?php echo ucfirst($user['role']); ?>
            </span>
            <p class="text-muted mt-2"><?php echo htmlspecialchars($user['email']); ?></p>
            
            <div class="mt-3">
                <a href="profile.php" class="btn btn-primary">
                    <i class="fas fa-edit"></i> Edit Profile
                </a>
                <?php if (is_admin()): ?>
                    <a href="admin_communications.php" class="btn btn-outline-primary">
                        <i class="fas fa-bullhorn"></i> Send Communications
                    </a>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Communications Section -->
        <div class="row">
            <div class="col-md-3">
                <div class="stats-card">
                    <div class="stats-number"><?php echo $total_communications; ?></div>
                    <div class="text-muted">Total Messages</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card">
                    <div class="stats-number"><?php echo $unread_count; ?></div>
                    <div class="text-muted">Unread Messages</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card">
                    <div class="stats-number">
                        <?php 
                        $read_count = $total_communications - $unread_count;
                        echo $read_count; 
                        ?>
                    </div>
                    <div class="text-muted">Read Messages</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card">
                    <div class="stats-number">
                        <?php 
                        $today_count = 0;
                        foreach ($communications as $comm) {
                            if (date('Y-m-d', strtotime($comm['sent_at'])) === date('Y-m-d')) {
                                $today_count++;
                            }
                        }
                        echo $today_count; 
                        ?>
                    </div>
                    <div class="text-muted">Today's Messages</div>
                </div>
            </div>
        </div>
        
        <!-- Communications List -->
        <div class="communication-card">
            <h4><i class="fas fa-envelope"></i> Communications from Church Leadership</h4>
            
            <?php if (empty($communications)): ?>
                <div class="text-center py-4">
                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                    <p class="text-muted">No communications yet. Check back later for messages from church leadership.</p>
                </div>
            <?php else: ?>
                <?php foreach ($communications as $comm): ?>
                    <div class="communication-card priority-<?php echo $comm['priority']; ?> <?php echo $comm['status'] === 'sent' ? 'unread' : ''; ?>">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <h5><?php echo htmlspecialchars($comm['title']); ?></h5>
                                <small class="text-muted">
                                    From <?php echo htmlspecialchars($comm['first_name'] . ' ' . $comm['last_name']); ?>
                                    on <?php echo date('M j, Y g:i A', strtotime($comm['sent_at'])); ?>
                                </small>
                            </div>
                            <div>
                                <?php if ($comm['status'] === 'sent'): ?>
                                    <span class="badge bg-danger">NEW</span>
                                <?php endif; ?>
                                <span class="priority-badge"><?php echo strtoupper($comm['priority']); ?></span>
                            </div>
                        </div>
                        
                        <div class="communication-content">
                            <?php echo htmlspecialchars($comm['content']); ?>
                        </div>
                        
                        <div class="mt-3">
                            <small class="text-muted">
                                <?php if ($comm['read_at']): ?>
                                    <i class="fas fa-check-circle"></i> Read on <?php echo date('M j, Y g:i A', strtotime($comm['read_at'])); ?>
                                <?php else: ?>
                                    <i class="fas fa-envelope"></i> Received on <?php echo date('M j, Y g:i A', strtotime($comm['received_at'])); ?>
                                <?php endif; ?>
                            </small>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        
        <!-- Quick Actions -->
        <div class="row mt-4">
            <div class="col-md-4">
                <div class="stats-card">
                    <h5><i class="fas fa-images"></i> Gallery</h5>
                    <p class="text-muted">View church photos and events</p>
                    <a href="gallery.php" class="btn btn-primary btn-sm">View Gallery</a>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stats-card">
                    <h5><i class="fas fa-calendar"></i> Events</h5>
                    <p class="text-muted">Upcoming church events</p>
                    <a href="events.php" class="btn btn-primary btn-sm">View Events</a>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stats-card">
                    <h5><i class="fas fa-pray"></i> Prayer</h5>
                    <p class="text-muted">Submit prayer requests</p>
                    <a href="prayer.php" class="btn btn-primary btn-sm">Prayer Requests</a>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
