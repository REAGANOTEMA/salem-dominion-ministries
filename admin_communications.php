<?php
// Admin Communications System
require_once 'auth_system.php';
require_once 'db.php';

require_admin();

$user = get_current_user();
$errors = [];
$success = '';

// Handle communication actions
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];
    
    if ($action === 'send_communication') {
        $title = trim($_POST['title'] ?? '');
        $content = trim($_POST['content'] ?? '');
        $target_audience = $_POST['target_audience'] ?? 'all';
        $priority = $_POST['priority'] ?? 'normal';
        
        if (empty($title) || empty($content)) {
            $errors[] = 'Title and content are required.';
        }
        
        if (empty($errors)) {
            try {
                // Insert communication
                $db->query("INSERT INTO admin_communications (title, content, target_audience, priority, sent_by, sent_at) VALUES (?, ?, ?, ?, ?, NOW())", 
                         [$title, $content, $target_audience, $priority, $user['id']]);
                
                $communication_id = $db->getConnection()->insert_id;
                
                // Send to target users
                $target_users = [];
                if ($target_audience === 'all') {
                    $target_users = $db->select("SELECT id FROM users WHERE is_active = 1");
                } elseif ($target_audience === 'pastors') {
                    $target_users = $db->select("SELECT id FROM users WHERE role = 'pastor' AND is_active = 1");
                } elseif ($target_audience === 'members') {
                    $target_users = $db->select("SELECT id FROM users WHERE role = 'member' AND is_active = 1");
                } elseif ($target_audience === 'admins') {
                    $target_users = $db->select("SELECT id FROM users WHERE role = 'admin' AND is_active = 1");
                }
                
                foreach ($target_users as $target_user) {
                    $db->query("INSERT INTO user_communications (communication_id, user_id, status, received_at) VALUES (?, ?, 'sent', NOW())", 
                             [$communication_id, $target_user['id']]);
                }
                
                $success = 'Communication sent successfully to ' . count($target_users) . ' users!';
            } catch (Exception $e) {
                $errors[] = 'Failed to send communication: ' . $e->getMessage();
            }
        }
    }
    
    if ($action === 'delete_communication') {
        $comm_id = intval($_POST['communication_id']);
        try {
            $db->query("DELETE FROM user_communications WHERE communication_id = ?", [$comm_id]);
            $db->query("DELETE FROM admin_communications WHERE id = ?", [$comm_id]);
            $success = 'Communication deleted successfully!';
        } catch (Exception $e) {
            $errors[] = 'Failed to delete communication.';
        }
    }
}

// Get communications
$communications = $db->select("SELECT ac.*, u.first_name, u.last_name, COUNT(uc.user_id) as recipient_count 
                                FROM admin_communications ac 
                                LEFT JOIN users u ON ac.sent_by = u.id 
                                LEFT JOIN user_communications uc ON ac.id = uc.communication_id 
                                GROUP BY ac.id 
                                ORDER BY ac.sent_at DESC");

// Get user statistics
$total_users = $db->selectOne("SELECT COUNT(*) as count FROM users WHERE is_active = 1")['count'];
$admin_users = $db->selectOne("SELECT COUNT(*) as count FROM users WHERE role = 'admin' AND is_active = 1")['count'];
$pastor_users = $db->selectOne("SELECT COUNT(*) as count FROM users WHERE role = 'pastor' AND is_active = 1")['count'];
$member_users = $db->selectOne("SELECT COUNT(*) as count FROM users WHERE role = 'member' AND is_active = 1")['count'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Communications - Salem Dominion Ministries</title>
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
            padding: 40px 0;
            margin-bottom: 30px;
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
            color: #dc2626;
        }
        
        .communication-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            padding: 25px;
            margin-bottom: 20px;
            border-left: 5px solid #dc2626;
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
        
        .btn-admin {
            background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
            border: none;
            border-radius: 10px;
            padding: 12px 25px;
            font-weight: 600;
            color: white;
        }
        
        .btn-admin:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(220, 38, 38, 0.3);
            color: white;
        }
        
        .form-control, .form-select {
            border-radius: 10px;
            border: 2px solid #e5e7eb;
            padding: 12px 20px;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: #dc2626;
            box-shadow: 0 0 0 0.2rem rgba(220, 38, 38, 0.25);
        }
        
        .alert {
            border-radius: 10px;
            border: none;
        }
        
        .audience-badge {
            background: #f3f4f6;
            padding: 4px 12px;
            border-radius: 15px;
            font-size: 0.8rem;
            display: inline-block;
            margin: 2px;
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
                <a class="nav-link" href="admin_dashboard.php">
                    <i class="fas fa-tachometer-alt"></i> Admin Dashboard
                </a>
                <a class="nav-link active" href="admin_communications.php">
                    <i class="fas fa-bullhorn"></i> Communications
                </a>
                <a class="nav-link" href="admin_users.php">
                    <i class="fas fa-users"></i> Users
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
            <h1><i class="fas fa-bullhorn"></i> Admin Communications</h1>
            <p class="lead">Send important messages to your church community</p>
        </div>
    </div>

    <div class="container">
        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <?php foreach ($errors as $error): ?>
                    <div><?php echo htmlspecialchars($error); ?></div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="alert alert-success">
                <?php echo htmlspecialchars($success); ?>
            </div>
        <?php endif; ?>
        
        <!-- User Statistics -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="stats-card">
                    <div class="stats-number"><?php echo $total_users; ?></div>
                    <div class="text-muted">Total Users</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card">
                    <div class="stats-number"><?php echo $admin_users; ?></div>
                    <div class="text-muted">Admins</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card">
                    <div class="stats-number"><?php echo $pastor_users; ?></div>
                    <div class="text-muted">Pastors</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card">
                    <div class="stats-number"><?php echo $member_users; ?></div>
                    <div class="text-muted">Members</div>
                </div>
            </div>
        </div>
        
        <!-- Send Communication Form -->
        <div class="row">
            <div class="col-md-6">
                <div class="communication-card">
                    <h4><i class="fas fa-paper-plane"></i> Send New Communication</h4>
                    
                    <form method="POST" action="admin_communications.php">
                        <input type="hidden" name="action" value="send_communication">
                        
                        <div class="mb-3">
                            <label for="title" class="form-label">Subject</label>
                            <input type="text" class="form-control" id="title" name="title" 
                                   placeholder="Enter communication subject" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="target_audience" class="form-label">Target Audience</label>
                            <select class="form-select" id="target_audience" name="target_audience">
                                <option value="all">All Users (<?php echo $total_users; ?>)</option>
                                <option value="admins">Admins Only (<?php echo $admin_users; ?>)</option>
                                <option value="pastors">Pastors Only (<?php echo $pastor_users; ?>)</option>
                                <option value="members">Members Only (<?php echo $member_users; ?>)</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label for="priority" class="form-label">Priority</label>
                            <select class="form-select" id="priority" name="priority">
                                <option value="normal">Normal</option>
                                <option value="high">High Priority</option>
                                <option value="low">Low Priority</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label for="content" class="form-label">Message</label>
                            <textarea class="form-control" id="content" name="content" rows="6" 
                                      placeholder="Enter your message here..." required></textarea>
                        </div>
                        
                        <button type="submit" class="btn btn-admin">
                            <i class="fas fa-paper-plane"></i> Send Communication
                        </button>
                    </form>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="communication-card">
                    <h4><i class="fas fa-history"></i> Recent Communications</h4>
                    
                    <?php if (empty($communications)): ?>
                        <p class="text-muted">No communications sent yet.</p>
                    <?php else: ?>
                        <?php foreach (array_slice($communications, 0, 5) as $comm): ?>
                            <div class="communication-card priority-<?php echo $comm['priority']; ?>">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6><?php echo htmlspecialchars($comm['title']); ?></h6>
                                        <small class="text-muted">
                                            Sent by <?php echo htmlspecialchars($comm['first_name'] . ' ' . $comm['last_name']); ?>
                                            on <?php echo date('M j, Y g:i A', strtotime($comm['sent_at'])); ?>
                                        </small>
                                    </div>
                                    <form method="POST" action="admin_communications.php" style="display: inline;">
                                        <input type="hidden" name="action" value="delete_communication">
                                        <input type="hidden" name="communication_id" value="<?php echo $comm['id']; ?>">
                                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                                <div class="mt-2">
                                    <span class="audience-badge">
                                        <i class="fas fa-users"></i> <?php echo ucfirst($comm['target_audience']); ?>
                                    </span>
                                    <span class="audience-badge">
                                        <i class="fas fa-flag"></i> <?php echo ucfirst($comm['priority']); ?>
                                    </span>
                                    <span class="audience-badge">
                                        <i class="fas fa-envelope"></i> <?php echo $comm['recipient_count']; ?> recipients
                                    </span>
                                </div>
                                <div class="mt-2">
                                    <small><?php echo substr(strip_tags($comm['content']), 0, 100); ?>...</small>
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
