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

require_login();

$user = get_current_user();

// Initialize variables to prevent undefined errors
$communications = [];
$total_communications = 0;
$unread_count = 0;

// Get user's communications with error handling
try {
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
    
} catch (Exception $e) {
    // Set empty arrays if database fails
    $communications = [];
    $total_communications = 0;
    $unread_count = 0;
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
        
        .communication-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            padding: 25px;
            margin-bottom: 20px;
            border-left: 5px solid #0ea5e9;
        }
        
        .communication-card.unread {
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
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(14, 165, 233, 0.3);
        }
        
        .badge-new {
            background: #16a34a;
            color: white;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 600;
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
                        <a href="profile.php" class="btn btn-primary me-2">
                            <i class="fas fa-user-edit"></i> Edit Profile
                        </a>
                        <a href="donations.php" class="btn btn-success me-2">
                            <i class="fas fa-donate"></i> Give
                        </a>
                        <a href="book_pastor.php" class="btn btn-info">
                            <i class="fas fa-calendar"></i> Book Pastor
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics -->
        <div class="row mb-4">
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
                        <?php echo date('d'); ?>
                    </div>
                    <div class="text-muted">Day of Month</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card">
                    <div class="stats-number">
                        <?php echo date('M'); ?>
                    </div>
                    <div class="text-muted">Current Month</div>
                </div>
            </div>
        </div>

        <!-- Communications -->
        <div class="row">
            <div class="col-md-12">
                <h3 class="mb-4"><i class="fas fa-envelope"></i> Church Communications</h3>
                
                <?php if (!empty($communications)): ?>
                    <?php foreach ($communications as $comm): ?>
                        <div class="communication-card <?php echo $comm['status'] === 'read' ? '' : 'unread'; ?>">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h5>
                                            <?php echo htmlspecialchars($comm['title']); ?>
                                            <?php if ($comm['status'] === 'sent'): ?>
                                                <span class="badge-new">NEW</span>
                                            <?php endif; ?>
                                        </h5>
                                        <small class="text-muted">
                                            <?php echo date('M j, Y g:i A', strtotime($comm['sent_at'])); ?>
                                        </small>
                                    </div>
                                    
                                    <div class="mb-2">
                                        <span class="badge bg-<?php echo $comm['priority'] === 'high' ? 'danger' : ($comm['priority'] === 'medium' ? 'warning' : 'info'); ?>">
                                            <?php echo ucfirst($comm['priority']); ?> Priority
                                        </span>
                                        <span class="badge bg-secondary ms-2">
                                            <?php echo ucfirst($comm['message_type']); ?>
                                        </span>
                                    </div>
                                    
                                    <p><?php echo htmlspecialchars(substr($comm['message'], 0, 200)); ?>...</p>
                                    
                                    <?php if ($comm['first_name'] || $comm['last_name']): ?>
                                        <small class="text-muted">
                                            <i class="fas fa-user"></i> From: <?php echo htmlspecialchars($comm['first_name'] . ' ' . $comm['last_name']); ?>
                                        </small>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="text-end">
                                        <small class="text-muted d-block mb-2">
                                            Status: <?php echo ucfirst($comm['status']); ?>
                                        </small>
                                        <?php if ($comm['status'] === 'read'): ?>
                                            <small class="text-muted d-block">
                                                Read: <?php echo $comm['read_at'] ? date('M j, Y g:i A', strtotime($comm['read_at'])) : 'Recently'; ?>
                                            </small>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="text-center py-5">
                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                        <h4 class="text-muted">No communications yet</h4>
                        <p class="text-muted">You haven't received any messages from the church yet.</p>
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
                        <a href="donations.php" class="btn btn-success w-100 mb-3">
                            <i class="fas fa-donate"></i> Make Donation
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="book_pastor.php" class="btn btn-info w-100 mb-3">
                            <i class="fas fa-calendar"></i> Book Pastor
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="gallery.php" class="btn btn-primary w-100 mb-3">
                            <i class="fas fa-images"></i> View Gallery
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="events.php" class="btn btn-warning w-100 mb-3">
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
