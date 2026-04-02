&lt;?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$user = $db-&gt;query("SELECT * FROM users WHERE id = $user_id")-&gt;fetch_assoc();

// Get dashboard stats
$stats = [
    'users' =&gt; $db-&gt;query("SELECT COUNT(*) as count FROM users")-&gt;fetch_assoc()['count'],
    'news' =&gt; $db-&gt;query("SELECT COUNT(*) as count FROM news WHERE status = 'published'")-&gt;fetch_assoc()['count'],
    'events' =&gt; $db-&gt;query("SELECT COUNT(*) as count FROM events WHERE status = 'upcoming'")-&gt;fetch_assoc()['count'],
    'donations' =&gt; $db-&gt;query("SELECT COUNT(*) as count FROM donations WHERE status = 'completed'")-&gt;fetch_assoc()['count'],
    'messages' =&gt; $db-&gt;query("SELECT COUNT(*) as count FROM contact_messages WHERE status = 'unread'")-&gt;fetch_assoc()['count'],
    'prayer_requests' =&gt; $db-&gt;query("SELECT COUNT(*) as count FROM prayer_requests WHERE status = 'pending'")-&gt;fetch_assoc()['count']
];
?&gt;
&lt;!DOCTYPE html&gt;
&lt;html lang="en"&gt;
&lt;head&gt;
    &lt;meta charset="UTF-8"&gt;
    &lt;meta name="viewport" content="width=device-width, initial-scale=1.0"&gt;
    &lt;title&gt;Admin Dashboard - Salem Dominion Ministries&lt;/title&gt;
    &lt;link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"&gt;
    &lt;link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet"&gt;
    &lt;style&gt;
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
    &lt;/style&gt;
&lt;/head&gt;
&lt;body&gt;
    &lt;div class="container-fluid"&gt;
        &lt;div class="row"&gt;
            &lt;!-- Sidebar --&gt;
            &lt;div class="col-md-3 col-lg-2 px-0 sidebar"&gt;
                &lt;div class="d-flex flex-column"&gt;
                    &lt;div class="p-3 border-bottom"&gt;
                        &lt;h5 class="mb-0"&gt;&lt;i class="fas fa-church"&gt;&lt;/i&gt; Admin Panel&lt;/h5&gt;
                        &lt;small&gt;Welcome, &lt;?php echo htmlspecialchars($user['first_name']); ?&gt;&lt;/small&gt;
                    &lt;/div&gt;
                    &lt;nav class="nav flex-column py-3"&gt;
                        &lt;a class="nav-link active" href="admin_dashboard.php"&gt;&lt;i class="fas fa-tachometer-alt"&gt;&lt;/i&gt; Dashboard&lt;/a&gt;
                        &lt;a class="nav-link" href="admin_users.php"&gt;&lt;i class="fas fa-users"&gt;&lt;/i&gt; Users&lt;/a&gt;
                        &lt;a class="nav-link" href="admin_news.php"&gt;&lt;i class="fas fa-newspaper"&gt;&lt;/i&gt; News&lt;/a&gt;
                        &lt;a class="nav-link" href="admin_events.php"&gt;&lt;i class="fas fa-calendar"&gt;&lt;/i&gt; Events&lt;/a&gt;
                        &lt;a class="nav-link" href="admin_sermons.php"&gt;&lt;i class="fas fa-bible"&gt;&lt;/i&gt; Sermons&lt;/a&gt;
                        &lt;a class="nav-link" href="admin_gallery.php"&gt;&lt;i class="fas fa-images"&gt;&lt;/i&gt; Gallery&lt;/a&gt;
                        &lt;a class="nav-link" href="admin_donations.php"&gt;&lt;i class="fas fa-dollar-sign"&gt;&lt;/i&gt; Donations&lt;/a&gt;
                        &lt;a class="nav-link" href="admin_messages.php"&gt;&lt;i class="fas fa-envelope"&gt;&lt;/i&gt; Messages&lt;/a&gt;
                        &lt;a class="nav-link" href="admin_prayer.php"&gt;&lt;i class="fas fa-pray"&gt;&lt;/i&gt; Prayer Requests&lt;/a&gt;
                        &lt;a class="nav-link" href="admin_children.php"&gt;&lt;i class="fas fa-child"&gt;&lt;/i&gt; Children Ministry&lt;/a&gt;
                        &lt;a class="nav-link" href="admin_blog.php"&gt;&lt;i class="fas fa-blog"&gt;&lt;/i&gt; Blog Posts&lt;/a&gt;
                        &lt;a class="nav-link" href="admin_settings.php"&gt;&lt;i class="fas fa-cog"&gt;&lt;/i&gt; Settings&lt;/a&gt;
                    &lt;/nav&gt;
                    &lt;div class="mt-auto p-3"&gt;
                        &lt;a href="logout.php" class="btn btn-outline-light btn-sm w-100"&gt;&lt;i class="fas fa-sign-out-alt"&gt;&lt;/i&gt; Logout&lt;/a&gt;
                    &lt;/div&gt;
                &lt;/div&gt;
            &lt;/div&gt;

            &lt;!-- Main Content --&gt;
            &lt;div class="col-md-9 col-lg-10 px-4 py-4"&gt;
                &lt;div class="d-flex justify-content-between align-items-center mb-4"&gt;
                    &lt;h1&gt;&lt;i class="fas fa-tachometer-alt"&gt;&lt;/i&gt; Admin Dashboard&lt;/h1&gt;
                    &lt;a href="index.php" class="btn btn-outline-primary"&gt;&lt;i class="fas fa-home"&gt;&lt;/i&gt; View Site&lt;/a&gt;
                &lt;/div&gt;

                &lt;!-- Stats Cards --&gt;
                &lt;div class="row mb-4"&gt;
                    &lt;div class="col-md-6 col-lg-3 mb-3"&gt;
                        &lt;div class="card stat-card border-primary"&gt;
                            &lt;div class="card-body text-center"&gt;
                                &lt;i class="fas fa-users fa-2x text-primary mb-2"&gt;&lt;/i&gt;
                                &lt;h4 class="card-title"&gt;&lt;?php echo $stats['users']; ?&gt;&lt;/h4&gt;
                                &lt;p class="card-text text-muted"&gt;Total Users&lt;/p&gt;
                            &lt;/div&gt;
                        &lt;/div&gt;
                    &lt;/div&gt;
                    &lt;div class="col-md-6 col-lg-3 mb-3"&gt;
                        &lt;div class="card stat-card border-success"&gt;
                            &lt;div class="card-body text-center"&gt;
                                &lt;i class="fas fa-newspaper fa-2x text-success mb-2"&gt;&lt;/i&gt;
                                &lt;h4 class="card-title"&gt;&lt;?php echo $stats['news']; ?&gt;&lt;/h4&gt;
                                &lt;p class="card-text text-muted"&gt;Published News&lt;/p&gt;
                            &lt;/div&gt;
                        &lt;/div&gt;
                    &lt;/div&gt;
                    &lt;div class="col-md-6 col-lg-3 mb-3"&gt;
                        &lt;div class="card stat-card border-info"&gt;
                            &lt;div class="card-body text-center"&gt;
                                &lt;i class="fas fa-calendar fa-2x text-info mb-2"&gt;&lt;/i&gt;
                                &lt;h4 class="card-title"&gt;&lt;?php echo $stats['events']; ?&gt;&lt;/h4&gt;
                                &lt;p class="card-text text-muted"&gt;Upcoming Events&lt;/p&gt;
                            &lt;/div&gt;
                        &lt;/div&gt;
                    &lt;/div&gt;
                    &lt;div class="col-md-6 col-lg-3 mb-3"&gt;
                        &lt;div class="card stat-card border-warning"&gt;
                            &lt;div class="card-body text-center"&gt;
                                &lt;i class="fas fa-dollar-sign fa-2x text-warning mb-2"&gt;&lt;/i&gt;
                                &lt;h4 class="card-title"&gt;&lt;?php echo $stats['donations']; ?&gt;&lt;/h4&gt;
                                &lt;p class="card-text text-muted"&gt;Completed Donations&lt;/p&gt;
                            &lt;/div&gt;
                        &lt;/div&gt;
                    &lt;/div&gt;
                &lt;/div&gt;

                &lt;div class="row mb-4"&gt;
                    &lt;div class="col-md-6 col-lg-3 mb-3"&gt;
                        &lt;div class="card stat-card border-danger"&gt;
                            &lt;div class="card-body text-center"&gt;
                                &lt;i class="fas fa-envelope fa-2x text-danger mb-2"&gt;&lt;/i&gt;
                                &lt;h4 class="card-title"&gt;&lt;?php echo $stats['messages']; ?&gt;&lt;/h4&gt;
                                &lt;p class="card-text text-muted"&gt;Unread Messages&lt;/p&gt;
                            &lt;/div&gt;
                        &lt;/div&gt;
                    &lt;/div&gt;
                    &lt;div class="col-md-6 col-lg-3 mb-3"&gt;
                        &lt;div class="card stat-card border-secondary"&gt;
                            &lt;div class="card-body text-center"&gt;
                                &lt;i class="fas fa-pray fa-2x text-secondary mb-2"&gt;&lt;/i&gt;
                                &lt;h4 class="card-title"&gt;&lt;?php echo $stats['prayer_requests']; ?&gt;&lt;/h4&gt;
                                &lt;p class="card-text text-muted"&gt;Pending Prayers&lt;/p&gt;
                            &lt;/div&gt;
                        &lt;/div&gt;
                    &lt;/div&gt;
                &lt;/div&gt;

                &lt;!-- Recent Activity --&gt;
                &lt;div class="row"&gt;
                    &lt;div class="col-md-6"&gt;
                        &lt;div class="card"&gt;
                            &lt;div class="card-header"&gt;
                                &lt;h5 class="mb-0"&gt;&lt;i class="fas fa-clock"&gt;&lt;/i&gt; Recent Messages&lt;/h5&gt;
                            &lt;/div&gt;
                            &lt;div class="card-body"&gt;
                                &lt;?php
                                $messages = $db-&gt;query("SELECT * FROM contact_messages ORDER BY created_at DESC LIMIT 5");
                                if ($messages-&gt;num_rows > 0):
                                    while ($msg = $messages-&gt;fetch_assoc()):
                                ?&gt;
                                &lt;div class="d-flex mb-3"&gt;
                                    &lt;div class="flex-grow-1"&gt;
                                        &lt;strong&gt;&lt;?php echo htmlspecialchars($msg['name']); ?&gt;&lt;/strong&gt;
                                        &lt;br&gt;&lt;small class="text-muted"&gt;&lt;?php echo htmlspecialchars($msg['subject']); ?&gt;&lt;/small&gt;
                                        &lt;br&gt;&lt;small&gt;&lt;?php echo date('M j, Y', strtotime($msg['created_at'])); ?&gt;&lt;/small&gt;
                                    &lt;/div&gt;
                                    &lt;span class="badge bg-&lt;?php echo $msg['status'] === 'unread' ? 'danger' : 'success'; ?&gt;"&gt;&lt;?php echo ucfirst($msg['status']); ?&gt;&lt;/span&gt;
                                &lt;/div&gt;
                                &lt;?php endwhile; else: ?&gt;
                                &lt;p class="text-muted"&gt;No messages yet.&lt;/p&gt;
                                &lt;?php endif; ?&gt;
                            &lt;/div&gt;
                        &lt;/div&gt;
                    &lt;/div&gt;
                    &lt;div class="col-md-6"&gt;
                        &lt;div class="card"&gt;
                            &lt;div class="card-header"&gt;
                                &lt;h5 class="mb-0"&gt;&lt;i class="fas fa-pray"&gt;&lt;/i&gt; Recent Prayer Requests&lt;/h5&gt;
                            &lt;/div&gt;
                            &lt;div class="card-body"&gt;
                                &lt;?php
                                $prayers = $db-&gt;query("SELECT * FROM prayer_requests ORDER BY created_at DESC LIMIT 5");
                                if ($prayers-&gt;num_rows > 0):
                                    while ($prayer = $prayers-&gt;fetch_assoc()):
                                ?&gt;
                                &lt;div class="d-flex mb-3"&gt;
                                    &lt;div class="flex-grow-1"&gt;
                                        &lt;strong&gt;&lt;?php echo htmlspecialchars($prayer['requester_name']); ?&gt;&lt;/strong&gt;
                                        &lt;br&gt;&lt;small class="text-muted"&gt;&lt;?php echo htmlspecialchars($prayer['title']); ?&gt;&lt;/small&gt;
                                        &lt;br&gt;&lt;small&gt;&lt;?php echo date('M j, Y', strtotime($prayer['created_at'])); ?&gt;&lt;/small&gt;
                                    &lt;/div&gt;
                                    &lt;span class="badge bg-&lt;?php echo $prayer['status'] === 'pending' ? 'warning' : 'success'; ?&gt;"&gt;&lt;?php echo ucfirst($prayer['status']); ?&gt;&lt;/span&gt;
                                &lt;/div&gt;
                                &lt;?php endwhile; else: ?&gt;
                                &lt;p class="text-muted"&gt;No prayer requests yet.&lt;/p&gt;
                                &lt;?php endif; ?&gt;
                            &lt;/div&gt;
                        &lt;/div&gt;
                    &lt;/div&gt;
                &lt;/div&gt;
            &lt;/div&gt;
        &lt;/div&gt;
    &lt;/div&gt;

    &lt;script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"&gt;&lt;/script&gt;
&lt;/body&gt;
&lt;/html&gt;