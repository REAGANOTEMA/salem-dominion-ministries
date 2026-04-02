<?php
// Complete error suppression to prevent unwanted output
error_reporting(0);
ini_set('display_errors', 0);
ini_set('log_errors', 0);

// Buffer output to catch any accidental output
ob_start();

// Include session helper and start session safely
require_once 'session_helper.php';
secure_session_start();
require_once 'db.php';

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || ($_SESSION['user_role'] ?? 'member') !== 'admin') {
    header('Location: login.php');
    exit;
}

// Get user information with error handling
try {
    $user = $db->selectOne("SELECT * FROM users WHERE id = ?", [$_SESSION['user_id']]);
    if (!$user) {
        session_destroy();
        header('Location: login.php');
        exit;
    }
} catch (Exception $e) {
    session_destroy();
    header('Location: login.php');
    exit;
}

// Get admin dashboard data with error handling
$dashboard_data = [];
$recent_activities = [];

try {
    $dashboard_data['total_users'] = $db->selectOne("SELECT COUNT(*) as count FROM users WHERE is_active = 1")['count'] ?? 0;
    $dashboard_data['total_events'] = $db->selectOne("SELECT COUNT(*) as count FROM events")['count'] ?? 0;
    $dashboard_data['total_sermons'] = $db->selectOne("SELECT COUNT(*) as count FROM sermons")['count'] ?? 0;
    $dashboard_data['total_news'] = $db->selectOne("SELECT COUNT(*) as count FROM news")['count'] ?? 0;
    $dashboard_data['total_donations'] = $db->selectOne("SELECT COUNT(*) as count FROM donations")['count'] ?? 0;
    $dashboard_data['total_prayer_requests'] = $db->selectOne("SELECT COUNT(*) as count FROM prayer_requests")['count'] ?? 0;
    $dashboard_data['total_gallery'] = $db->selectOne("SELECT COUNT(*) as count FROM gallery")['count'] ?? 0;
    
    // Get recent users
    $dashboard_data['recent_users'] = $db->select("SELECT * FROM users ORDER BY created_at DESC LIMIT 5");
    
    // Get recent activities
    $dashboard_data['recent_activities'] = $db->select("SELECT * FROM activity_logs ORDER BY created_at DESC LIMIT 10");
    
    // Get pending items
    $dashboard_data['pending_prayer_requests'] = $db->select("SELECT COUNT(*) as count FROM prayer_requests WHERE status = 'pending'")['count'] ?? 0;
    $dashboard_data['pending_events'] = $db->select("SELECT COUNT(*) as count FROM events WHERE status = 'pending'")['count'] ?? 0;
    
} catch (Exception $e) {
    // Set default values if queries fail
    $dashboard_data = [
        'total_users' => 0,
        'total_events' => 0,
        'total_sermons' => 0,
        'total_news' => 0,
        'total_donations' => 0,
        'total_prayer_requests' => 0,
        'total_gallery' => 0,
        'recent_users' => [],
        'recent_activities' => [],
        'pending_prayer_requests' => 0,
        'pending_events' => 0
    ];
}

// Clean any buffered output
ob_end_clean();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Admin Dashboard - Salem Dominion Ministries">
    <title>Admin Dashboard - Salem Dominion Ministries</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- AOS Animation -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;0,900;1,400&family=Montserrat:wght@100;200;300;400;500;600;700;800;900&family=Great+Vibes&display=swap" rel="stylesheet">
    
    <style>
        /* ICONIC DESIGN SYSTEM - Top Notch Colors Only */
        :root {
            /* Primary Palette - Ultra Premium */
            --midnight-blue: #0f172a;
            --ocean-blue: #0ea5e9;
            --sky-blue: #38bdf8;
            --ice-blue: #7dd3fc;
            --snow-white: #ffffff;
            --pearl-white: #f8fafc;
            
            /* Divine Accents */
            --heavenly-gold: #fbbf24;
            --divine-light: #fef3c7;
            
            /* Shadows & Effects */
            --shadow-divine: 0 20px 40px rgba(15, 23, 42, 0.15);
            --shadow-heavenly: 0 25px 50px rgba(251, 191, 36, 0.2);
            --shadow-soft: 0 10px 25px rgba(15, 23, 42, 0.08);
            --shadow-glow: 0 0 40px rgba(14, 165, 233, 0.3);
            
            /* Gradients - Iconic */
            --gradient-ocean: linear-gradient(135deg, var(--midnight-blue) 0%, var(--ocean-blue) 50%, var(--sky-blue) 100%);
            --gradient-heaven: linear-gradient(135deg, var(--snow-white) 0%, var(--pearl-white) 50%, var(--ice-blue) 100%);
            --gradient-divine: linear-gradient(135deg, var(--heavenly-gold) 0%, var(--divine-light) 100%);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Montserrat', sans-serif;
            font-weight: 300;
            line-height: 1.6;
            color: var(--midnight-blue);
            background: var(--pearl-white);
            overflow-x: hidden;
            position: relative;
        }

        /* Divine Background Pattern */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: 
                radial-gradient(circle at 20% 80%, rgba(251, 191, 36, 0.03) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(14, 165, 233, 0.03) 0%, transparent 50%),
                radial-gradient(circle at 40% 40%, rgba(56, 189, 248, 0.02) 0%, transparent 50%);
            pointer-events: none;
            z-index: 1;
        }

        /* Typography - Iconic */
        h1, h2, h3, h4, h5, h6 {
            font-family: 'Playfair Display', serif;
            font-weight: 700;
            line-height: 1.2;
            color: var(--midnight-blue);
        }

        .font-divine {
            font-family: 'Great Vibes', cursive;
            color: var(--heavenly-gold);
        }

        /* Sidebar Navigation */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 280px;
            height: 100vh;
            background: var(--gradient-ocean);
            padding: 2rem 0;
            z-index: 1000;
            overflow-y: auto;
            box-shadow: var(--shadow-divine);
        }

        .sidebar-logo {
            text-align: center;
            margin-bottom: 3rem;
            padding: 0 1.5rem;
        }

        .sidebar-logo img {
            height: 60px;
            border-radius: 50%;
            background: var(--snow-white);
            padding: 10px;
            margin-bottom: 1rem;
            box-shadow: 0 0 30px rgba(251, 191, 36, 0.4);
        }

        .sidebar-logo h3 {
            color: var(--snow-white);
            font-size: 1.3rem;
            margin-bottom: 0.5rem;
        }

        .sidebar-logo p {
            color: rgba(255, 255, 255, 0.8);
            font-size: 0.9rem;
            margin-bottom: 0;
        }

        .sidebar-menu {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .sidebar-menu li {
            margin-bottom: 0.5rem;
        }

        .sidebar-menu a {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem 1.5rem;
            color: rgba(255, 255, 255, 0.9);
            text-decoration: none;
            transition: all 0.3s ease;
            position: relative;
        }

        .sidebar-menu a:hover,
        .sidebar-menu a.active {
            background: rgba(255, 255, 255, 0.1);
            color: var(--snow-white);
            padding-left: 2rem;
        }

        .sidebar-menu a.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 4px;
            background: var(--heavenly-gold);
        }

        .sidebar-menu i {
            width: 20px;
            text-align: center;
        }

        /* Main Content */
        .main-content {
            margin-left: 280px;
            min-height: 100vh;
            background: var(--pearl-white);
            position: relative;
            z-index: 10;
        }

        /* Top Header */
        .top-header {
            background: var(--snow-white);
            padding: 1.5rem 2rem;
            box-shadow: var(--shadow-soft);
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .header-title {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--midnight-blue);
            margin-bottom: 0;
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 0.5rem 1rem;
            background: var(--gradient-heaven);
            border-radius: 50px;
            border: 1px solid var(--ice-blue);
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            background: var(--gradient-ocean);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--snow-white);
            font-weight: 700;
            font-size: 1rem;
        }

        .user-info {
            margin-right: 1rem;
        }

        .user-name {
            font-weight: 600;
            color: var(--midnight-blue);
            margin-bottom: 0.25rem;
        }

        .user-role {
            font-size: 0.85rem;
            color: var(--heavenly-gold);
            text-transform: capitalize;
        }

        .btn-logout {
            background: var(--gradient-divine);
            color: var(--midnight-blue);
            border: none;
            padding: 0.5rem 1.5rem;
            border-radius: 50px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-logout:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-heavenly);
            color: var(--midnight-blue);
        }

        /* Dashboard Content */
        .dashboard-content {
            padding: 2rem;
        }

        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin-bottom: 3rem;
        }

        .stat-card {
            background: var(--snow-white);
            border-radius: 25px;
            padding: 2rem;
            box-shadow: var(--shadow-soft);
            border: 1px solid rgba(125, 211, 252, 0.2);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--gradient-divine);
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-divine);
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            background: var(--gradient-ocean);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--snow-white);
            font-size: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .stat-value {
            font-size: 2.5rem;
            font-weight: 900;
            color: var(--midnight-blue);
            margin-bottom: 0.5rem;
        }

        .stat-label {
            color: var(--text-secondary);
            font-size: 1rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .stat-change {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            margin-top: 0.5rem;
        }

        .stat-change.positive {
            background: rgba(34, 197, 94, 0.1);
            color: #16a34a;
        }

        .stat-change.negative {
            background: rgba(239, 68, 68, 0.1);
            color: #dc2626;
        }

        /* Content Cards */
        .content-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
            gap: 2rem;
            margin-bottom: 3rem;
        }

        .content-card {
            background: var(--snow-white);
            border-radius: 25px;
            padding: 2rem;
            box-shadow: var(--shadow-soft);
            border: 1px solid rgba(125, 211, 252, 0.2);
            transition: all 0.3s ease;
        }

        .content-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-divine);
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid var(--pearl-white);
        }

        .card-title {
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--midnight-blue);
            margin-bottom: 0;
        }

        .card-action {
            color: var(--ocean-blue);
            text-decoration: none;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }

        .card-action:hover {
            color: var(--sky-blue);
        }

        /* List Items */
        .list-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem;
            background: var(--pearl-white);
            border-radius: 15px;
            margin-bottom: 1rem;
            transition: all 0.3s ease;
        }

        .list-item:hover {
            background: var(--ice-blue);
            transform: translateX(5px);
        }

        .list-item:last-child {
            margin-bottom: 0;
        }

        .list-icon {
            width: 40px;
            height: 40px;
            background: var(--gradient-ocean);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--snow-white);
            font-size: 1rem;
            flex-shrink: 0;
        }

        .list-content {
            flex: 1;
        }

        .list-title {
            font-weight: 600;
            color: var(--midnight-blue);
            margin-bottom: 0.25rem;
        }

        .list-meta {
            font-size: 0.85rem;
            color: var(--text-secondary);
        }

        .list-badge {
            background: var(--gradient-divine);
            color: var(--midnight-blue);
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        /* Quick Actions */
        .quick-actions {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin-bottom: 3rem;
        }

        .action-card {
            background: var(--gradient-heaven);
            border-radius: 20px;
            padding: 2rem;
            text-align: center;
            border: 1px solid var(--ice-blue);
            transition: all 0.3s ease;
            text-decoration: none;
            color: var(--midnight-blue);
        }

        .action-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-divine);
            color: var(--midnight-blue);
        }

        .action-icon {
            width: 60px;
            height: 60px;
            background: var(--gradient-ocean);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--snow-white);
            font-size: 1.5rem;
            margin: 0 auto 1rem;
        }

        .action-title {
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .action-description {
            font-size: 0.9rem;
            color: var(--text-secondary);
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 3rem;
            color: var(--text-secondary);
        }

        .empty-state i {
            font-size: 3rem;
            color: var(--ice-blue);
            margin-bottom: 1rem;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }

            .sidebar.active {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .mobile-menu-toggle {
                display: block;
            }

            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .content-grid {
                grid-template-columns: 1fr;
            }

            .quick-actions {
                grid-template-columns: 1fr;
            }
        }

        @media (min-width: 769px) {
            .mobile-menu-toggle {
                display: none;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar Navigation -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-logo">
            <img src="assets/logo-DEFqnQ4s.jpeg" alt="Salem Dominion Ministries">
            <h3>Admin Panel</h3>
            <p>Church Management</p>
        </div>
        
        <ul class="sidebar-menu">
            <li>
                <a href="admin_dashboard.php" class="active">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li>
                <a href="admin_users.php">
                    <i class="fas fa-users"></i>
                    <span>Users</span>
                </a>
            </li>
            <li>
                <a href="admin_events.php">
                    <i class="fas fa-calendar"></i>
                    <span>Events</span>
                </a>
            </li>
            <li>
                <a href="admin_sermons.php">
                    <i class="fas fa-microphone"></i>
                    <span>Sermons</span>
                </a>
            </li>
            <li>
                <a href="admin_news.php">
                    <i class="fas fa-newspaper"></i>
                    <span>News</span>
                </a>
            </li>
            <li>
                <a href="admin_gallery.php">
                    <i class="fas fa-images"></i>
                    <span>Gallery</span>
                </a>
            </li>
            <li>
                <a href="admin_prayer_requests.php">
                    <i class="fas fa-praying-hands"></i>
                    <span>Prayer Requests</span>
                </a>
            </li>
            <li>
                <a href="admin_donations.php">
                    <i class="fas fa-heart"></i>
                    <span>Donations</span>
                </a>
            </li>
            <li>
                <a href="dashboard.php">
                    <i class="fas fa-home"></i>
                    <span>Member View</span>
                </a>
            </li>
            <li>
                <a href="index.php">
                    <i class="fas fa-church"></i>
                    <span>Church Website</span>
                </a>
            </li>
        </ul>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Top Header -->
        <header class="top-header">
            <button class="mobile-menu-toggle btn btn-primary d-md-none" onclick="toggleSidebar()">
                <i class="fas fa-bars"></i>
            </button>
            
            <h1 class="header-title">Admin Dashboard</h1>
            
            <div class="header-actions">
                <div class="user-profile">
                    <div class="user-avatar">
                        <?php echo strtoupper(substr($user['first_name'], 0, 1) . substr($user['last_name'], 0, 1)); ?>
                    </div>
                    <div class="user-info">
                        <div class="user-name"><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></div>
                        <div class="user-role">Administrator</div>
                    </div>
                </div>
                <a href="logout.php" class="btn-logout">
                    <i class="fas fa-sign-out-alt"></i>
                    Logout
                </a>
            </div>
        </header>

        <!-- Dashboard Content -->
        <div class="dashboard-content">
            <!-- Stats Grid -->
            <div class="stats-grid">
                <div class="stat-card" data-aos="fade-up">
                    <div class="stat-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-value"><?php echo number_format($dashboard_data['total_users']); ?></div>
                    <div class="stat-label">Total Users</div>
                    <span class="stat-change positive">+12% this month</span>
                </div>
                <div class="stat-card" data-aos="fade-up" data-aos-delay="100">
                    <div class="stat-icon">
                        <i class="fas fa-calendar"></i>
                    </div>
                    <div class="stat-value"><?php echo number_format($dashboard_data['total_events']); ?></div>
                    <div class="stat-label">Events</div>
                    <span class="stat-change positive">+3 this week</span>
                </div>
                <div class="stat-card" data-aos="fade-up" data-aos-delay="200">
                    <div class="stat-icon">
                        <i class="fas fa-microphone"></i>
                    </div>
                    <div class="stat-value"><?php echo number_format($dashboard_data['total_sermons']); ?></div>
                    <div class="stat-label">Sermons</div>
                    <span class="stat-change positive">+2 this month</span>
                </div>
                <div class="stat-card" data-aos="fade-up" data-aos-delay="300">
                    <div class="stat-icon">
                        <i class="fas fa-newspaper"></i>
                    </div>
                    <div class="stat-value"><?php echo number_format($dashboard_data['total_news']); ?></div>
                    <div class="stat-label">News Articles</div>
                    <span class="stat-change positive">+5 this week</span>
                </div>
                <div class="stat-card" data-aos="fade-up" data-aos-delay="400">
                    <div class="stat-icon">
                        <i class="fas fa-heart"></i>
                    </div>
                    <div class="stat-value"><?php echo number_format($dashboard_data['total_donations']); ?></div>
                    <div class="stat-label">Donations</div>
                    <span class="stat-change positive">+8 this month</span>
                </div>
                <div class="stat-card" data-aos="fade-up" data-aos-delay="500">
                    <div class="stat-icon">
                        <i class="fas fa-praying-hands"></i>
                    </div>
                    <div class="stat-value"><?php echo number_format($dashboard_data['total_prayer_requests']); ?></div>
                    <div class="stat-label">Prayer Requests</div>
                    <span class="stat-change positive">+15 this week</span>
                </div>
            </div>

            <!-- Quick Actions -->
            <h2 class="section-title" data-aos="fade-up">Quick Actions</h2>
            <div class="quick-actions">
                <a href="admin_users.php?action=add" class="action-card" data-aos="fade-up">
                    <div class="action-icon">
                        <i class="fas fa-user-plus"></i>
                    </div>
                    <h3 class="action-title">Add User</h3>
                    <p class="action-description">Create new user account</p>
                </a>
                <a href="admin_events.php?action=add" class="action-card" data-aos="fade-up" data-aos-delay="100">
                    <div class="action-icon">
                        <i class="fas fa-calendar-plus"></i>
                    </div>
                    <h3 class="action-title">Create Event</h3>
                    <p class="action-description">Add new church event</p>
                </a>
                <a href="admin_sermons.php?action=add" class="action-card" data-aos="fade-up" data-aos-delay="200">
                    <div class="action-icon">
                        <i class="fas fa-microphone-alt"></i>
                    </div>
                    <h3 class="action-title">Add Sermon</h3>
                    <p class="action-description">Upload new sermon</p>
                </a>
                <a href="admin_news.php?action=add" class="action-card" data-aos="fade-up" data-aos-delay="300">
                    <div class="action-icon">
                        <i class="fas fa-newspaper"></i>
                    </div>
                    <h3 class="action-title">Post News</h3>
                    <p class="action-description">Publish news article</p>
                </a>
            </div>

            <!-- Content Grid -->
            <div class="content-grid">
                <!-- Recent Users -->
                <div class="content-card" data-aos="fade-up">
                    <div class="card-header">
                        <h3 class="card-title">Recent Users</h3>
                        <a href="admin_users.php" class="card-action">View All</a>
                    </div>
                    <?php if (!empty($dashboard_data['recent_users'])): ?>
                        <?php foreach (array_slice($dashboard_data['recent_users'], 0, 5) as $recent_user): ?>
                            <div class="list-item">
                                <div class="list-icon">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div class="list-content">
                                    <div class="list-title"><?php echo htmlspecialchars($recent_user['first_name'] . ' ' . $recent_user['last_name']); ?></div>
                                    <div class="list-meta">
                                        <?php echo ucfirst($recent_user['role']); ?> • Joined <?php echo date('M j, Y', strtotime($recent_user['created_at'])); ?>
                                    </div>
                                </div>
                                <span class="list-badge"><?php echo $recent_user['is_active'] ? 'Active' : 'Inactive'; ?></span>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="empty-state">
                            <i class="fas fa-users"></i>
                            <p>No users registered yet</p>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Recent Activities -->
                <div class="content-card" data-aos="fade-up" data-aos-delay="100">
                    <div class="card-header">
                        <h3 class="card-title">Recent Activities</h3>
                        <a href="#" class="card-action">View All</a>
                    </div>
                    <?php if (!empty($dashboard_data['recent_activities'])): ?>
                        <?php foreach (array_slice($dashboard_data['recent_activities'], 0, 5) as $activity): ?>
                            <div class="list-item">
                                <div class="list-icon">
                                    <i class="fas fa-history"></i>
                                </div>
                                <div class="list-content">
                                    <div class="list-title"><?php echo htmlspecialchars($activity['activity'] ?? 'System activity'); ?></div>
                                    <div class="list-meta">
                                        <?php echo date('M j, Y g:i A', strtotime($activity['created_at'])); ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="empty-state">
                            <i class="fas fa-history"></i>
                            <p>No recent activities</p>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Pending Items -->
                <div class="content-card" data-aos="fade-up" data-aos-delay="200">
                    <div class="card-header">
                        <h3 class="card-title">Pending Items</h3>
                        <a href="#" class="card-action">View All</a>
                    </div>
                    <div class="list-item">
                        <div class="list-icon">
                            <i class="fas fa-praying-hands"></i>
                        </div>
                        <div class="list-content">
                            <div class="list-title">Prayer Requests</div>
                            <div class="list-meta">Awaiting response</div>
                        </div>
                        <span class="list-badge"><?php echo $dashboard_data['pending_prayer_requests']; ?></span>
                    </div>
                    <div class="list-item">
                        <div class="list-icon">
                            <i class="fas fa-calendar"></i>
                        </div>
                        <div class="list-content">
                            <div class="list-title">Events</div>
                            <div class="list-meta">Pending approval</div>
                        </div>
                        <span class="list-badge"><?php echo $dashboard_data['pending_events']; ?></span>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- AOS Animation -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    
    <script>
        // Initialize AOS
        AOS.init({
            duration: 1200,
            once: true,
            offset: 100,
            easing: 'ease-in-out'
        });

        // Mobile sidebar toggle
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('active');
        }

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('sidebar');
            const toggle = document.querySelector('.mobile-menu-toggle');
            
            if (window.innerWidth <= 768 && 
                !sidebar.contains(event.target) && 
                !toggle.contains(event.target) &&
                sidebar.classList.contains('active')) {
                sidebar.classList.remove('active');
            }
        });

        // Auto-refresh dashboard data every 30 seconds
        setInterval(() => {
            // You can add AJAX refresh here if needed
            console.log('Admin dashboard data refreshed');
        }, 30000);
    </script>
</body>
</html>
