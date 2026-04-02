&lt;?php
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
                $stmt = $db-&gt;prepare("UPDATE prayer_requests SET status = ? WHERE id = ?");
                $stmt-&gt;bind_param('si', $status, $prayer_id);
                if ($stmt-&gt;execute()) {
                    $message = 'Prayer request status updated successfully.';
                    $message_type = 'success';
                } else {
                    $message = 'Error updating prayer request status.';
                    $message_type = 'error';
                }
            }
        } elseif ($action === 'delete_prayer') {
            $prayer_id = intval($_POST['prayer_id']);
            $stmt = $db-&gt;prepare("DELETE FROM prayer_requests WHERE id = ?");
            $stmt-&gt;bind_param('i', $prayer_id);
            if ($stmt-&gt;execute()) {
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
                $stmt = $db-&gt;prepare("UPDATE prayer_requests SET admin_response = ?, admin_response_date = NOW() WHERE id = ?");
                $stmt-&gt;bind_param('si', $response, $prayer_id);
                if ($stmt-&gt;execute()) {
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
$stats = $db-&gt;query("
    SELECT 
        COUNT(*) as total_requests,
        COUNT(CASE WHEN status = 'pending' THEN 1 END) as pending_count,
        COUNT(CASE WHEN status = 'prayed' THEN 1 END) as prayed_count,
        COUNT(CASE WHEN status = 'answered' THEN 1 END) as answered_count,
        COUNT(CASE WHEN is_public = 1 THEN 1 END) as public_count
    FROM prayer_requests
")-&gt;fetch_assoc();

// Get all prayer requests with user info
$prayers_result = $db-&gt;query("
    SELECT p.*, u.username, u.email 
    FROM prayer_requests p 
    LEFT JOIN users u ON p.user_id = u.id 
    ORDER BY p.created_at DESC
");
?&gt;
&lt;!DOCTYPE html&gt;
&lt;html lang="en"&gt;
&lt;head&gt;
    &lt;meta charset="UTF-8"&gt;
    &lt;meta name="viewport" content="width=device-width, initial-scale=1.0"&gt;
    &lt;title&gt;Prayer Requests Management - Salem Dominion Ministries&lt;/title&gt;
    &lt;link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"&gt;
    &lt;link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet"&gt;
    &lt;style&gt;
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
    &lt;/style&gt;
&lt;/head&gt;
&lt;body&gt;
    &lt;!-- Navigation --&gt;
    &lt;nav class="navbar navbar-expand-lg navbar-dark bg-dark"&gt;
        &lt;div class="container"&gt;
            &lt;a class="navbar-brand" href="index.php"&gt;
                &lt;i class="fas fa-church"&gt;&lt;/i&gt; Salem Dominion Ministries
            &lt;/a&gt;
            &lt;button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"&gt;
                &lt;span class="navbar-toggler-icon"&gt;&lt;/span&gt;
            &lt;/button&gt;
            &lt;div class="collapse navbar-collapse" id="navbarNav"&gt;
                &lt;ul class="navbar-nav me-auto"&gt;
                    &lt;li class="nav-item"&gt;&lt;a class="nav-link" href="index.php"&gt;Home&lt;/a&gt;&lt;/li&gt;
                    &lt;li class="nav-item"&gt;&lt;a class="nav-link" href="about.php"&gt;About&lt;/a&gt;&lt;/li&gt;
                    &lt;li class="nav-item"&gt;&lt;a class="nav-link" href="ministries.php"&gt;Ministries&lt;/a&gt;&lt;/li&gt;
                    &lt;li class="nav-item"&gt;&lt;a class="nav-link" href="events.php"&gt;Events&lt;/a&gt;&lt;/li&gt;
                    &lt;li class="nav-item"&gt;&lt;a class="nav-link" href="sermons.php"&gt;Sermons&lt;/a&gt;&lt;/li&gt;
                    &lt;li class="nav-item"&gt;&lt;a class="nav-link" href="news.php"&gt;News&lt;/a&gt;&lt;/li&gt;
                    &lt;li class="nav-item"&gt;&lt;a class="nav-link" href="gallery.php"&gt;Gallery&lt;/a&gt;&lt;/li&gt;
                    &lt;li class="nav-item"&gt;&lt;a class="nav-link" href="contact.php"&gt;Contact&lt;/a&gt;&lt;/li&gt;
                &lt;/ul&gt;
                &lt;ul class="navbar-nav"&gt;
                    &lt;li class="nav-item"&gt;&lt;a class="nav-link" href="dashboard.php"&gt;Dashboard&lt;/a&gt;&lt;/li&gt;
                    &lt;li class="nav-item"&gt;&lt;a class="nav-link" href="logout.php"&gt;Logout&lt;/a&gt;&lt;/li&gt;
                &lt;/ul&gt;
            &lt;/div&gt;
        &lt;/div&gt;
    &lt;/nav&gt;

    &lt;div class="container-fluid"&gt;
        &lt;div class="row"&gt;
            &lt;!-- Sidebar --&gt;
            &lt;div class="col-md-3 col-lg-2 px-0"&gt;
                &lt;div class="sidebar p-3"&gt;
                    &lt;h6 class="text-white-50 mb-3"&gt;ADMIN PANEL&lt;/h6&gt;
                    &lt;nav class="nav flex-column"&gt;
                        &lt;a class="nav-link" href="admin_dashboard.php"&gt;
                            &lt;i class="fas fa-tachometer-alt"&gt;&lt;/i&gt; Dashboard
                        &lt;/a&gt;
                        &lt;a class="nav-link" href="admin_users.php"&gt;
                            &lt;i class="fas fa-users"&gt;&lt;/i&gt; Users
                        &lt;/a&gt;
                        &lt;a class="nav-link" href="admin_news.php"&gt;
                            &lt;i class="fas fa-newspaper"&gt;&lt;/i&gt; News
                        &lt;/a&gt;
                        &lt;a class="nav-link" href="admin_events.php"&gt;
                            &lt;i class="fas fa-calendar"&gt;&lt;/i&gt; Events
                        &lt;/a&gt;
                        &lt;a class="nav-link" href="admin_sermons.php"&gt;
                            &lt;i class="fas fa-microphone"&gt;&lt;/i&gt; Sermons
                        &lt;/a&gt;
                        &lt;a class="nav-link" href="admin_ministries.php"&gt;
                            &lt;i class="fas fa-praying-hands"&gt;&lt;/i&gt; Ministries
                        &lt;/a&gt;
                        &lt;a class="nav-link" href="admin_gallery.php"&gt;
                            &lt;i class="fas fa-images"&gt;&lt;/i&gt; Gallery
                        &lt;/a&gt;
                        &lt;a class="nav-link" href="admin_donations.php"&gt;
                            &lt;i class="fas fa-hand-holding-heart"&gt;&lt;/i&gt; Donations
                        &lt;/a&gt;
                        &lt;a class="nav-link active" href="admin_prayer_requests.php"&gt;
                            &lt;i class="fas fa-pray"&gt;&lt;/i&gt; Prayer Requests
                        &lt;/a&gt;
                    &lt;/nav&gt;
                &lt;/div&gt;
            &lt;/div&gt;

            &lt;!-- Main Content --&gt;
            &lt;div class="col-md-9 col-lg-10 px-4 py-4"&gt;
                &lt;div class="d-flex justify-content-between align-items-center mb-4"&gt;
                    &lt;h2&gt;&lt;i class="fas fa-pray text-primary"&gt;&lt;/i&gt; Prayer Requests Management&lt;/h2&gt;
                    &lt;a href="prayer_requests.php" class="btn btn-outline-primary"&gt;
                        &lt;i class="fas fa-external-link-alt"&gt;&lt;/i&gt; View Public Page
                    &lt;/a&gt;
                &lt;/div&gt;

                &lt;?php if ($message): ?&gt;
                    &lt;div class="alert alert-&lt;?php echo $message_type === 'success' ? 'success' : 'danger'; ?&gt; alert-dismissible fade show" role="alert"&gt;
                        &lt;?php echo htmlspecialchars($message); ?&gt;
                        &lt;button type="button" class="btn-close" data-bs-dismiss="alert"&gt;&lt;/button&gt;
                    &lt;/div&gt;
                &lt;?php endif; ?&gt;

                &lt;!-- Statistics Cards --&gt;
                &lt;div class="row mb-4"&gt;
                    &lt;div class="col-md-3"&gt;
                        &lt;div class="card stats-card text-white"&gt;
                            &lt;div class="card-body"&gt;
                                &lt;h5 class="card-title"&gt;&lt;i class="fas fa-pray"&gt;&lt;/i&gt; Total Requests&lt;/h5&gt;
                                &lt;h3&gt;&lt;?php echo $stats['total_requests']; ?&gt;&lt;/h3&gt;
                            &lt;/div&gt;
                        &lt;/div&gt;
                    &lt;/div&gt;
                    &lt;div class="col-md-3"&gt;
                        &lt;div class="card bg-warning text-white"&gt;
                            &lt;div class="card-body"&gt;
                                &lt;h5 class="card-title"&gt;&lt;i class="fas fa-clock"&gt;&lt;/i&gt; Pending&lt;/h5&gt;
                                &lt;h3&gt;&lt;?php echo $stats['pending_count']; ?&gt;&lt;/h3&gt;
                            &lt;/div&gt;
                        &lt;/div&gt;
                    &lt;/div&gt;
                    &lt;div class="col-md-3"&gt;
                        &lt;div class="card bg-info text-white"&gt;
                            &lt;div class="card-body"&gt;
                                &lt;h5 class="card-title"&gt;&lt;i class="fas fa-hands"&gt;&lt;/i&gt; Prayed&lt;/h5&gt;
                                &lt;h3&gt;&lt;?php echo $stats['prayed_count']; ?&gt;&lt;/h3&gt;
                            &lt;/div&gt;
                        &lt;/div&gt;
                    &lt;/div&gt;
                    &lt;div class="col-md-3"&gt;
                        &lt;div class="card bg-success text-white"&gt;
                            &lt;div class="card-body"&gt;
                                &lt;h5 class="card-title"&gt;&lt;i class="fas fa-check"&gt;&lt;/i&gt; Answered&lt;/h5&gt;
                                &lt;h3&gt;&lt;?php echo $stats['answered_count']; ?&gt;&lt;/h3&gt;
                            &lt;/div&gt;
                        &lt;/div&gt;
                    &lt;/div&gt;
                &lt;/div&gt;

                &lt;!-- Prayer Requests List --&gt;
                &lt;div class="row"&gt;
                    &lt;?php while ($prayer = $prayers_result-&gt;fetch_assoc()): ?&gt;
                        &lt;div class="col-lg-6 mb-4"&gt;
                            &lt;div class="card prayer-card h-100"&gt;
                                &lt;div class="card-header d-flex justify-content-between align-items-center"&gt;
                                    &lt;div&gt;
                                        &lt;strong&gt;&lt;?php echo htmlspecialchars($prayer['requester_name'] ?: ($prayer['username'] ?: 'Anonymous')); ?&gt;&lt;/strong&gt;
                                        &lt;?php if ($prayer['is_public']): ?&gt;
                                            &lt;span class="badge bg-success ms-2"&gt;Public&lt;/span&gt;
                                        &lt;?php else: ?&gt;
                                            &lt;span class="badge bg-secondary ms-2"&gt;Private&lt;/span&gt;
                                        &lt;?php endif; ?&gt;
                                    &lt;/div&gt;
                                    &lt;div class="btn-group" role="group"&gt;
                                        &lt;button class="btn btn-outline-primary btn-sm" onclick="addResponse(&lt;?php echo $prayer['id']; ?&gt;, '&lt;?php echo htmlspecialchars(addslashes($prayer['admin_response'] ?: '')); ?&gt;')"&gt;
                                            &lt;i class="fas fa-reply"&gt;&lt;/i&gt;
                                        &lt;/button&gt;
                                        &lt;form method="post" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this prayer request?')"&gt;
                                            &lt;input type="hidden" name="action" value="delete_prayer"&gt;
                                            &lt;input type="hidden" name="prayer_id" value="&lt;?php echo $prayer['id']; ?&gt;"&gt;
                                            &lt;button type="submit" class="btn btn-outline-danger btn-sm"&gt;
                                                &lt;i class="fas fa-trash"&gt;&lt;/i&gt;
                                            &lt;/button&gt;
                                        &lt;/form&gt;
                                    &lt;/div&gt;
                                &lt;/div&gt;
                                &lt;div class="card-body"&gt;
                                    &lt;div class="prayer-content mb-3"&gt;
                                        &lt;p class="mb-2"&gt;&lt;?php echo nl2br(htmlspecialchars($prayer['request_text'])); ?&gt;&lt;/p&gt;
                                    &lt;/div&gt;
                                    &lt;div class="row text-muted small mb-3"&gt;
                                        &lt;div class="col-6"&gt;
                                            &lt;i class="fas fa-calendar"&gt;&lt;/i&gt; &lt;?php echo date('M j, Y', strtotime($prayer['created_at'])); ?&gt;&lt;br&gt;
                                            &lt;i class="fas fa-envelope"&gt;&lt;/i&gt; &lt;?php echo htmlspecialchars($prayer['email']); ?&gt;
                                        &lt;/div&gt;
                                        &lt;div class="col-6 text-end"&gt;
                                            &lt;!-- Status Update --&gt;
                                            &lt;form method="post" class="d-inline-block mb-2"&gt;
                                                &lt;input type="hidden" name="action" value="update_prayer_status"&gt;
                                                &lt;input type="hidden" name="prayer_id" value="&lt;?php echo $prayer['id']; ?&gt;"&gt;
                                                &lt;select name="status" class="form-select form-select-sm" onchange="this.form.submit()"&gt;
                                                    &lt;option value="pending" &lt;?php echo $prayer['status'] === 'pending' ? 'selected' : ''; ?&gt;&gt;Pending&lt;/option&gt;
                                                    &lt;option value="prayed" &lt;?php echo $prayer['status'] === 'prayed' ? 'selected' : ''; ?&gt;&gt;Prayed&lt;/option&gt;
                                                    &lt;option value="answered" &lt;?php echo $prayer['status'] === 'answered' ? 'selected' : ''; ?&gt;&gt;Answered&lt;/option&gt;
                                                &lt;/select&gt;
                                            &lt;/form&gt;
                                            &lt;br&gt;
                                            &lt;span class="badge status-badge bg-&lt;?php 
                                                echo $prayer['status'] === 'answered' ? 'success' : 
                                                     ($prayer['status'] === 'prayed' ? 'info' : 'warning'); 
                                            ?&gt;"&gt;
                                                &lt;?php echo ucfirst($prayer['status']); ?&gt;
                                            &lt;/span&gt;
                                        &lt;/div&gt;
                                    &lt;/div&gt;
                                    &lt;?php if ($prayer['admin_response']): ?&gt;
                                        &lt;div class="alert alert-info"&gt;
                                            &lt;strong&gt;Admin Response:&lt;/strong&gt;&lt;br&gt;
                                            &lt;?php echo nl2br(htmlspecialchars($prayer['admin_response'])); ?&gt;
                                            &lt;br&gt;&lt;small class="text-muted"&gt;&lt;?php echo $prayer['admin_response_date'] ? date('M j, Y g:i A', strtotime($prayer['admin_response_date'])) : ''; ?&gt;&lt;/small&gt;
                                        &lt;/div&gt;
                                    &lt;?php endif; ?&gt;
                                &lt;/div&gt;
                            &lt;/div&gt;
                        &lt;/div&gt;
                    &lt;?php endwhile; ?&gt;
                &lt;/div&gt;
            &lt;/div&gt;
        &lt;/div&gt;
    &lt;/div&gt;

    &lt;!-- Add Response Modal --&gt;
    &lt;div class="modal fade" id="responseModal" tabindex="-1"&gt;
        &lt;div class="modal-dialog modal-lg"&gt;
            &lt;div class="modal-content"&gt;
                &lt;div class="modal-header"&gt;
                    &lt;h5 class="modal-title"&gt;&lt;i class="fas fa-reply"&gt;&lt;/i&gt; Add Admin Response&lt;/h5&gt;
                    &lt;button type="button" class="btn-close" data-bs-dismiss="modal"&gt;&lt;/button&gt;
                &lt;/div&gt;
                &lt;form method="post"&gt;
                    &lt;div class="modal-body"&gt;
                        &lt;input type="hidden" name="action" value="add_admin_response"&gt;
                        &lt;input type="hidden" name="prayer_id" id="response_prayer_id"&gt;
                        &lt;div class="mb-3"&gt;
                            &lt;label for="response" class="form-label"&gt;Your Response&lt;/label&gt;
                            &lt;textarea class="form-control" id="response" name="response" rows="4" placeholder="Enter your response to this prayer request..."&gt;&lt;/textarea&gt;
                        &lt;/div&gt;
                    &lt;/div&gt;
                    &lt;div class="modal-footer"&gt;
                        &lt;button type="button" class="btn btn-secondary" data-bs-dismiss="modal"&gt;Cancel&lt;/button&gt;
                        &lt;button type="submit" class="btn btn-primary"&gt;&lt;i class="fas fa-paper-plane"&gt;&lt;/i&gt; Send Response&lt;/button&gt;
                    &lt;/div&gt;
                &lt;/form&gt;
            &lt;/div&gt;
        &lt;/div&gt;
    &lt;/div&gt;

    &lt;script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"&gt;&lt;/script&gt;
    &lt;script&gt;
        function addResponse(prayerId, existingResponse) {
            document.getElementById('response_prayer_id').value = prayerId;
            document.getElementById('response').value = existingResponse;
            
            new bootstrap.Modal(document.getElementById('responseModal')).show();
        }
    &lt;/script&gt;
&lt;/body&gt;
&lt;/html&gt;