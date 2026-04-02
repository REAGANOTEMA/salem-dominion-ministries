<?php
session_start();
require_once 'db.php';

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

// Handle form submissions
$message = '';
$message_type = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];

        if ($action === 'update_prayer_status') {
            $prayer_id = intval($_POST['prayer_id']);
            $status = $_POST['status'];

            if (in_array($status, ['pending', 'prayed', 'answered'])) {
                $stmt = $db->prepare("UPDATE prayer_requests SET status = ? WHERE id = ?");
                $stmt->bind_param('si', $status, $prayer_id);
                if ($stmt->execute()) {
                    $message = 'Prayer request status updated successfully.';
                    $message_type = 'success';
                } else {
                    $message = 'Error updating prayer request status.';
                    $message_type = 'error';
                }
            }
        } elseif ($action === 'delete_prayer') {
            $prayer_id = intval($_POST['prayer_id']);
            $stmt = $db->prepare("DELETE FROM prayer_requests WHERE id = ?");
            $stmt->bind_param('i', $prayer_id);
            if ($stmt->execute()) {
                $message = 'Prayer request deleted successfully.';
                $message_type = 'success';
            } else {
                $message = 'Error deleting prayer request.';
                $message_type = 'error';
            }
        } elseif ($action === 'add_admin_response') {
            $prayer_id = intval($_POST['prayer_id']);
            $response = trim($_POST['response']);

            if (!empty($response)) {
                $stmt = $db->prepare("UPDATE prayer_requests SET admin_response = ?, admin_response_date = NOW() WHERE id = ?");
                $stmt->bind_param('si', $response, $prayer_id);
                if ($stmt->execute()) {
                    $message = 'Admin response added successfully.';
                    $message_type = 'success';
                } else {
                    $message = 'Error adding admin response.';
                    $message_type = 'error';
                }
            }
        }
    }
}

// Get prayer request statistics
$stats = $db->query("
    SELECT 
        COUNT(*) as total_requests,
        COUNT(CASE WHEN status = 'pending' THEN 1 END) as pending_count,
        COUNT(CASE WHEN status = 'prayed' THEN 1 END) as prayed_count,
        COUNT(CASE WHEN status = 'answered' THEN 1 END) as answered_count,
        COUNT(CASE WHEN is_public = 1 THEN 1 END) as public_count
    FROM prayer_requests
")->fetch_assoc();

// Get all prayer requests with user info
$prayers_result = $db->query("
    SELECT p.*, u.username, u.email 
    FROM prayer_requests p 
    LEFT JOIN users u ON p.user_id = u.id 
    ORDER BY p.created_at DESC
");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prayer Requests Management - Salem Dominion Ministries</title>
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
        .stats-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        .prayer-card {
            transition: transform 0.2s ease;
        }
        .prayer-card:hover {
            transform: translateY(-2px);
        }
        .status-badge {
            font-size: 0.8em;
        }
        .prayer-content {
            max-height: 100px;
            overflow-y: auto;
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
                        <a class="nav-link" href="admin_users.php">
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
                        <a class="nav-link active" href="admin_prayer_requests.php">
                            <i class="fas fa-pray"></i> Prayer Requests
                        </a>
                    </nav>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 px-4 py-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2><i class="fas fa-pray text-primary"></i> Prayer Requests Management</h2>
                    <a href="prayer_requests.php" class="btn btn-outline-primary">
                        <i class="fas fa-external-link-alt"></i> View Public Page
                    </a>
                </div>

                <?php if ($message): ?>
                    <div class="alert alert-<?php echo $message_type === 'success' ? 'success' : 'danger'; ?> alert-dismissible fade show" role="alert">
                        <?php echo htmlspecialchars($message); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <!-- Statistics Cards -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="card stats-card text-white">
                            <div class="card-body">
                                <h5 class="card-title"><i class="fas fa-pray"></i> Total Requests</h5>
                                <h3><?php echo $stats['total_requests']; ?></h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-warning text-white">
                            <div class="card-body">
                                <h5 class="card-title"><i class="fas fa-clock"></i> Pending</h5>
                                <h3><?php echo $stats['pending_count']; ?></h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-info text-white">
                            <div class="card-body">
                                <h5 class="card-title"><i class="fas fa-hands"></i> Prayed</h5>
                                <h3><?php echo $stats['prayed_count']; ?></h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-success text-white">
                            <div class="card-body">
                                <h5 class="card-title"><i class="fas fa-check"></i> Answered</h5>
                                <h3><?php echo $stats['answered_count']; ?></h3>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Prayer Requests List -->
                <div class="row">
                    <?php while ($prayer = $prayers_result->fetch_assoc()): ?>
                        <div class="col-lg-6 mb-4">
                            <div class="card prayer-card h-100">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong><?php echo htmlspecialchars($prayer['requester_name'] ?: ($prayer['username'] ?: 'Anonymous')); ?></strong>
                                        <?php if ($prayer['is_public']): ?>
                                            <span class="badge bg-success ms-2">Public</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary ms-2">Private</span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="btn-group" role="group">
                                        <button class="btn btn-outline-primary btn-sm" onclick="addResponse(<?php echo $prayer['id']; ?>, '<?php echo htmlspecialchars(addslashes($prayer['admin_response'] ?: '')); ?>')">
                                            <i class="fas fa-reply"></i>
                                        </button>
                                        <form method="post" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this prayer request?')">
                                            <input type="hidden" name="action" value="delete_prayer">
                                            <input type="hidden" name="prayer_id" value="<?php echo $prayer['id']; ?>">
                                            <button type="submit" class="btn btn-outline-danger btn-sm">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="prayer-content mb-3">
                                        <p class="mb-2"><?php echo nl2br(htmlspecialchars($prayer['request_text'])); ?></p>
                                    </div>
                                    <div class="row text-muted small mb-3">
                                        <div class="col-6">
                                            <i class="fas fa-calendar"></i> <?php echo date('M j, Y', strtotime($prayer['created_at'])); ?><br>
                                            <i class="fas fa-envelope"></i> <?php echo htmlspecialchars($prayer['email']); ?>
                                        </div>
                                        <div class="col-6 text-end">
                                            <!-- Status Update -->
                                            <form method="post" class="d-inline-block mb-2">
                                                <input type="hidden" name="action" value="update_prayer_status">
                                                <input type="hidden" name="prayer_id" value="<?php echo $prayer['id']; ?>">
                                                <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                                                    <option value="pending" <?php echo $prayer['status'] === 'pending' ? 'selected' : ''; ?>>Pending</option>
                                                    <option value="prayed" <?php echo $prayer['status'] === 'prayed' ? 'selected' : ''; ?>>Prayed</option>
                                                    <option value="answered" <?php echo $prayer['status'] === 'answered' ? 'selected' : ''; ?>>Answered</option>
                                                </select>
                                            </form>
                                            <br>
                                            <span class="badge status-badge bg-<?php 
                                                echo $prayer['status'] === 'answered' ? 'success' : 
                                                     ($prayer['status'] === 'prayed' ? 'info' : 'warning'); 
                                            ?>">
                                                <?php echo ucfirst($prayer['status']); ?>
                                            </span>
                                        </div>
                                    </div>
                                    <?php if ($prayer['admin_response']): ?>
                                        <div class="alert alert-info">
                                            <strong>Admin Response:</strong><br>
                                            <?php echo nl2br(htmlspecialchars($prayer['admin_response'])); ?>
                                            <br><small class="text-muted"><?php echo $prayer['admin_response_date'] ? date('M j, Y g:i A', strtotime($prayer['admin_response_date'])) : ''; ?></small>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Response Modal -->
    <div class="modal fade" id="responseModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-reply"></i> Add Admin Response</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="post">
                    <div class="modal-body">
                        <input type="hidden" name="action" value="add_admin_response">
                        <input type="hidden" name="prayer_id" id="response_prayer_id">
                        <div class="mb-3">
                            <label for="response" class="form-label">Your Response</label>
                            <textarea class="form-control" id="response" name="response" rows="4" placeholder="Enter your response to this prayer request..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-paper-plane"></i> Send Response</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function addResponse(prayerId, existingResponse) {
            document.getElementById('response_prayer_id').value = prayerId;
            document.getElementById('response').value = existingResponse;
            
            new bootstrap.Modal(document.getElementById('responseModal')).show();
        }
    </script>
</body>
</html>