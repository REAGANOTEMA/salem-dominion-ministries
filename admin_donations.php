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

        if ($action === 'update_donation_status') {
            $donation_id = intval($_POST['donation_id']);
            $status = $_POST['status'];

            if (in_array($status, ['pending', 'completed', 'cancelled'])) {
                $stmt = $db-&gt;prepare("UPDATE donations SET status = ? WHERE id = ?");
                $stmt-&gt;bind_param('si', $status, $donation_id);
                if ($stmt-&gt;execute()) {
                    $message = 'Donation status updated successfully.';
                    $message_type = 'success';
                } else {
                    $message = 'Error updating donation status.';
                    $message_type = 'error';
                }
            }
        } elseif ($action === 'delete_donation') {
            $donation_id = intval($_POST['donation_id']);
            $stmt = $db-&gt;prepare("DELETE FROM donations WHERE id = ?");
            $stmt-&gt;bind_param('i', $donation_id);
            if ($stmt-&gt;execute()) {
                $message = 'Donation deleted successfully.';
                $message_type = 'success';
            } else {
                $message = 'Error deleting donation.';
                $message_type = 'error';
            }
        }
    }
}

// Get donation statistics
$stats = $db-&gt;query("
    SELECT 
        COUNT(*) as total_donations,
        SUM(CASE WHEN status = 'completed' THEN amount ELSE 0 END) as total_amount,
        COUNT(CASE WHEN status = 'pending' THEN 1 END) as pending_count,
        COUNT(CASE WHEN status = 'completed' THEN 1 END) as completed_count
    FROM donations
")-&gt;fetch_assoc();

// Get all donations with user info
$donations_result = $db-&gt;query("
    SELECT d.*, u.username, u.email 
    FROM donations d 
    LEFT JOIN users u ON d.user_id = u.id 
    ORDER BY d.created_at DESC
");
?&gt;
&lt;!DOCTYPE html&gt;
&lt;html lang="en"&gt;
&lt;head&gt;
    &lt;meta charset="UTF-8"&gt;
    &lt;meta name="viewport" content="width=device-width, initial-scale=1.0"&gt;
    &lt;title&gt;Donations Management - Salem Dominion Ministries&lt;/title&gt;
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
        .donation-card {
            transition: transform 0.2s ease;
        }
        .donation-card:hover {
            transform: translateY(-2px);
        }
        .status-badge {
            font-size: 0.8em;
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
                        &lt;a class="nav-link active" href="admin_donations.php"&gt;
                            &lt;i class="fas fa-hand-holding-heart"&gt;&lt;/i&gt; Donations
                        &lt;/a&gt;
                        &lt;a class="nav-link" href="admin_prayer_requests.php"&gt;
                            &lt;i class="fas fa-pray"&gt;&lt;/i&gt; Prayer Requests
                        &lt;/a&gt;
                    &lt;/nav&gt;
                &lt;/div&gt;
            &lt;/div&gt;

            &lt;!-- Main Content --&gt;
            &lt;div class="col-md-9 col-lg-10 px-4 py-4"&gt;
                &lt;div class="d-flex justify-content-between align-items-center mb-4"&gt;
                    &lt;h2&gt;&lt;i class="fas fa-hand-holding-heart text-primary"&gt;&lt;/i&gt; Donations Management&lt;/h2&gt;
                    &lt;a href="donate.php" class="btn btn-outline-primary"&gt;
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
                                &lt;h5 class="card-title"&gt;&lt;i class="fas fa-hand-holding-heart"&gt;&lt;/i&gt; Total Donations&lt;/h5&gt;
                                &lt;h3&gt;&lt;?php echo $stats['total_donations']; ?&gt;&lt;/h3&gt;
                            &lt;/div&gt;
                        &lt;/div&gt;
                    &lt;/div&gt;
                    &lt;div class="col-md-3"&gt;
                        &lt;div class="card bg-success text-white"&gt;
                            &lt;div class="card-body"&gt;
                                &lt;h5 class="card-title"&gt;&lt;i class="fas fa-dollar-sign"&gt;&lt;/i&gt; Total Amount&lt;/h5&gt;
                                &lt;h3&gt;$&lt;?php echo number_format($stats['total_amount'], 2); ?&gt;&lt;/h3&gt;
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
                                &lt;h5 class="card-title"&gt;&lt;i class="fas fa-check"&gt;&lt;/i&gt; Completed&lt;/h5&gt;
                                &lt;h3&gt;&lt;?php echo $stats['completed_count']; ?&gt;&lt;/h3&gt;
                            &lt;/div&gt;
                        &lt;/div&gt;
                    &lt;/div&gt;
                &lt;/div&gt;

                &lt;!-- Donations Table --&gt;
                &lt;div class="card"&gt;
                    &lt;div class="card-header"&gt;
                        &lt;h5 class="mb-0"&gt;&lt;i class="fas fa-list"&gt;&lt;/i&gt; All Donations&lt;/h5&gt;
                    &lt;/div&gt;
                    &lt;div class="card-body p-0"&gt;
                        &lt;div class="table-responsive"&gt;
                            &lt;table class="table table-hover mb-0"&gt;
                                &lt;thead class="table-dark"&gt;
                                    &lt;tr&gt;
                                        &lt;th&gt;Donor&lt;/th&gt;
                                        &lt;th&gt;Amount&lt;/th&gt;
                                        &lt;th&gt;Purpose&lt;/th&gt;
                                        &lt;th&gt;Status&lt;/th&gt;
                                        &lt;th&gt;Date&lt;/th&gt;
                                        &lt;th&gt;Actions&lt;/th&gt;
                                    &lt;/tr&gt;
                                &lt;/thead&gt;
                                &lt;tbody&gt;
                                    &lt;?php while ($donation = $donations_result-&gt;fetch_assoc()): ?&gt;
                                        &lt;tr&gt;
                                            &lt;td&gt;
                                                &lt;div&gt;
                                                    &lt;strong&gt;&lt;?php echo htmlspecialchars($donation['donor_name'] ?: ($donation['username'] ?: 'Anonymous')); ?&gt;&lt;/strong&gt;&lt;br&gt;
                                                    &lt;small class="text-muted"&gt;&lt;?php echo htmlspecialchars($donation['email']); ?&gt;&lt;/small&gt;
                                                &lt;/div&gt;
                                            &lt;/td&gt;
                                            &lt;td&gt;
                                                &lt;strong class="text-success"&gt;$&lt;?php echo number_format($donation['amount'], 2); ?&gt;&lt;/strong&gt;
                                            &lt;/td&gt;
                                            &lt;td&gt;&lt;?php echo htmlspecialchars($donation['purpose'] ?: 'General'); ?&gt;&lt;/td&gt;
                                            &lt;td&gt;
                                                &lt;span class="badge status-badge bg-&lt;?php 
                                                    echo $donation['status'] === 'completed' ? 'success' : 
                                                         ($donation['status'] === 'pending' ? 'warning' : 'danger'); 
                                                ?&gt;"&gt;
                                                    &lt;?php echo ucfirst($donation['status']); ?&gt;
                                                &lt;/span&gt;
                                            &lt;/td&gt;
                                            &lt;td&gt;&lt;?php echo date('M j, Y g:i A', strtotime($donation['created_at'])); ?&gt;&lt;/td&gt;
                                            &lt;td&gt;
                                                &lt;div class="btn-group" role="group"&gt;
                                                    &lt;!-- Status Update --&gt;
                                                    &lt;form method="post" class="d-inline"&gt;
                                                        &lt;input type="hidden" name="action" value="update_donation_status"&gt;
                                                        &lt;input type="hidden" name="donation_id" value="&lt;?php echo $donation['id']; ?&gt;"&gt;
                                                        &lt;select name="status" class="form-select form-select-sm d-inline-block w-auto me-1" onchange="this.form.submit()"&gt;
                                                            &lt;option value="pending" &lt;?php echo $donation['status'] === 'pending' ? 'selected' : ''; ?&gt;&gt;Pending&lt;/option&gt;
                                                            &lt;option value="completed" &lt;?php echo $donation['status'] === 'completed' ? 'selected' : ''; ?&gt;&gt;Completed&lt;/option&gt;
                                                            &lt;option value="cancelled" &lt;?php echo $donation['status'] === 'cancelled' ? 'selected' : ''; ?&gt;&gt;Cancelled&lt;/option&gt;
                                                        &lt;/select&gt;
                                                    &lt;/form&gt;

                                                    &lt;!-- Delete Donation --&gt;
                                                    &lt;form method="post" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this donation?')"&gt;
                                                        &lt;input type="hidden" name="action" value="delete_donation"&gt;
                                                        &lt;input type="hidden" name="donation_id" value="&lt;?php echo $donation['id']; ?&gt;"&gt;
                                                        &lt;button type="submit" class="btn btn-danger btn-sm"&gt;
                                                            &lt;i class="fas fa-trash"&gt;&lt;/i&gt;
                                                        &lt;/button&gt;
                                                    &lt;/form&gt;
                                                &lt;/div&gt;
                                            &lt;/td&gt;
                                        &lt;/tr&gt;
                                    &lt;?php endwhile; ?&gt;
                                &lt;/tbody&gt;
                            &lt;/table&gt;
                        &lt;/div&gt;
                    &lt;/div&gt;
                &lt;/div&gt;
            &lt;/div&gt;
        &lt;/div&gt;
    &lt;/div&gt;

    &lt;script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"&gt;&lt;/script&gt;
&lt;/body&gt;
&lt;/html&gt;