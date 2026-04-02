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

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Get user information
$user = $db->selectOne("SELECT * FROM users WHERE id = ?", [$_SESSION['user_id']]);
$user_role = $user['role'] ?? 'member';

// Get dashboard data based on role
$dashboard_data = [];
$gallery_images = [];
$upcoming_events = [];
$sermons = [];
$news_items = [];
$leadership = [];
$members = [];

try {
    switch ($user_role) {
        case 'admin':
            $dashboard_data['total_users'] = $db->selectOne("SELECT COUNT(*) as count FROM users WHERE is_active = 1")['count'];
            $dashboard_data['total_events'] = $db->selectOne("SELECT COUNT(*) as count FROM events")['count'];
            $dashboard_data['total_sermons'] = $db->selectOne("SELECT COUNT(*) as count FROM sermons")['count'];
            $dashboard_data['total_donations'] = $db->selectOne("SELECT COUNT(*) as count FROM donations")['count'];
            $dashboard_data['total_bookings'] = $db->selectOne("SELECT COUNT(*) as count FROM pastor_bookings")['count'];
            $dashboard_data['recent_activities'] = $db->select("SELECT * FROM activity_logs ORDER BY created_at DESC LIMIT 10");
            break;
            
        case 'pastor':
            $dashboard_data['upcoming_bookings'] = $db->select("SELECT * FROM pastor_bookings WHERE status = 'pending' ORDER BY booking_date ASC LIMIT 5");
            $dashboard_data['prayer_requests'] = $db->select("SELECT * FROM prayer_requests WHERE status = 'pending' ORDER BY created_at DESC LIMIT 5");
            $dashboard_data['recent_activities'] = $db->select("SELECT * FROM activity_logs WHERE user_id = ? ORDER BY created_at DESC LIMIT 5", [$_SESSION['user_id']]);
            break;
            
        case 'member':
        default:
            // Get gallery images for logged-in users
            $gallery_images = $db->query("SELECT * FROM gallery WHERE status = 'published' ORDER BY created_at DESC LIMIT 6");
            $upcoming_events = $db->query("SELECT * FROM events WHERE status = 'upcoming' ORDER BY event_date ASC LIMIT 5");
            $sermons = $db->query("SELECT * FROM sermons ORDER BY sermon_date DESC LIMIT 5");
            $news_items = $db->query("SELECT * FROM news WHERE status = 'published' ORDER BY created_at DESC LIMIT 5");
            $leadership = $db->query("SELECT * FROM leadership WHERE is_active = 1 ORDER BY order_position ASC");
            $members = $db->query("SELECT * FROM users WHERE is_active = 1 ORDER BY created_at DESC LIMIT 8");
            break;
    }
} catch (Exception $e) {
    // Set empty arrays if queries fail
}

// Clean any buffered output
ob_end_clean();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Dashboard - Salem Dominion Ministries - Member portal and church management">
    <title>Dashboard - Salem Dominion Ministries</title>
    
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
            background: var(--snow-white);
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

        /* Navigation - Iconic */
        .navbar {
            background: rgba(255, 255, 255, 0.95) !important;
            backdrop-filter: blur(20px);
            box-shadow: var(--shadow-soft);
            padding: 1rem 0;
            transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            z-index: 1000;
        }

        .navbar.scrolled {
            padding: 0.5rem 0;
            box-shadow: var(--shadow-divine);
        }

        .navbar-brand {
            display: flex;
            align-items: center;
            gap: 15px;
            font-family: 'Great Vibes', cursive;
            font-size: 2.2rem;
            font-weight: 700;
            color: var(--midnight-blue) !important;
            text-decoration: none !important;
            transition: all 0.3s ease;
        }

        .navbar-brand:hover {
            transform: scale(1.02);
        }

        .navbar-brand img {
            height: 50px;
            width: auto;
            border-radius: 50%;
            background: var(--gradient-heaven);
            padding: 8px;
            box-shadow: 0 0 30px rgba(251, 191, 36, 0.3);
            transition: all 0.5s ease;
        }

        .navbar-brand:hover img {
            transform: scale(1.1) rotate(5deg);
            box-shadow: 0 0 40px rgba(251, 191, 36, 0.5);
        }

        .navbar-nav .nav-link {
            color: var(--midnight-blue) !important;
            font-weight: 400;
            font-size: 0.95rem;
            margin: 0 12px;
            transition: all 0.3s ease;
            position: relative;
            text-decoration: none !important;
        }

        .navbar-nav .nav-link:hover,
        .navbar-nav .nav-link.active {
            color: var(--ocean-blue) !important;
            font-weight: 500;
        }

        .navbar-nav .nav-link::after {
            content: '';
            position: absolute;
            bottom: -8px;
            left: 50%;
            width: 0;
            height: 2px;
            background: var(--gradient-divine);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            transform: translateX(-50%);
        }

        .navbar-nav .nav-link:hover::after,
        .navbar-nav .nav-link.active::after {
            width: 100%;
        }

        /* Dashboard Layout */
        .dashboard-container {
            min-height: 100vh;
            background: var(--gradient-heaven);
        }

        .dashboard-header {
            background: var(--gradient-ocean);
            padding: 2rem 0;
            color: var(--snow-white);
            position: relative;
            overflow: hidden;
        }

        .dashboard-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 300%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
            animation: divineShimmer 15s infinite;
        }

        @keyframes divineShimmer {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }

        .dashboard-header-content {
            position: relative;
            z-index: 10;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .welcome-section {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 2rem;
            margin-bottom: 2rem;
        }

        .welcome-info h1 {
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
            text-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
        }

        .welcome-info p {
            font-size: 1.2rem;
            opacity: 0.9;
            margin-bottom: 0;
        }

        .user-actions {
            display: flex;
            gap: 1rem;
        }

        .btn-dashboard {
            background: rgba(255, 255, 255, 0.2);
            color: var(--snow-white);
            border: 1px solid rgba(255, 255, 255, 0.3);
            padding: 0.75rem 1.5rem;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-dashboard:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            color: var(--snow-white);
        }

        /* Dashboard Content */
        .dashboard-content {
            padding: 2rem;
        }

        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 2rem;
            margin-bottom: 2rem;
        }

        .dashboard-card {
            background: var(--snow-white);
            border-radius: 20px;
            padding: 2rem;
            box-shadow: var(--shadow-soft);
            border: 1px solid rgba(125, 211, 252, 0.2);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .dashboard-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: var(--gradient-divine);
        }

        .dashboard-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-divine);
        }

        .card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.5rem;
        }

        .card-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--midnight-blue);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .card-title i {
            color: var(--heavenly-gold);
            font-size: 1.2rem;
        }

        .card-action {
            background: var(--gradient-ocean);
            color: var(--snow-white);
            padding: 0.5rem 1rem;
            border-radius: 20px;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }

        .card-action:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(14, 165, 233, 0.3);
            color: var(--snow-white);
        }

        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .stat-item {
            text-align: center;
            padding: 1.5rem;
            background: rgba(14, 165, 233, 0.1);
            border-radius: 15px;
            transition: all 0.3s ease;
        }

        .stat-item:hover {
            background: rgba(14, 165, 233, 0.15);
            transform: translateY(-3px);
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: 900;
            color: var(--ocean-blue);
            font-family: 'Playfair Display', serif;
            margin-bottom: 0.5rem;
        }

        .stat-label {
            color: var(--midnight-blue);
            font-weight: 600;
            font-size: 0.9rem;
        }

        /* Gallery Section */
        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .gallery-item {
            position: relative;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: var(--shadow-soft);
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .gallery-item:hover {
            transform: scale(1.05);
            box-shadow: var(--shadow-divine);
        }

        .gallery-item img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            transition: all 0.3s ease;
        }

        .gallery-item:hover img {
            transform: scale(1.1);
        }

        /* Activity List */
        .activity-list {
            list-style: none;
            padding: 0;
        }

        .activity-item {
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            padding: 1rem;
            border-bottom: 1px solid rgba(125, 211, 252, 0.1);
            transition: all 0.3s ease;
        }

        .activity-item:hover {
            background: rgba(14, 165, 233, 0.05);
        }

        .activity-icon {
            width: 40px;
            height: 40px;
            background: var(--gradient-divine);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--midnight-blue);
            flex-shrink: 0;
            font-size: 1rem;
        }

        .activity-content {
            flex: 1;
        }

        .activity-title {
            font-weight: 600;
            color: var(--midnight-blue);
            margin-bottom: 0.25rem;
        }

        .activity-time {
            font-size: 0.85rem;
            color: rgba(15, 23, 42, 0.6);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .welcome-section {
                flex-direction: column;
                text-align: center;
                gap: 1rem;
            }

            .dashboard-grid {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }

            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .gallery-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .user-actions {
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <img src="assets/logo-DEFqnQ4s.jpeg" alt="Salem Dominion Ministries">
                <span>Salem Dominion Ministries</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="about.php">About</a></li>
                    <li class="nav-item"><a class="nav-link" href="ministries.php">Ministries</a></li>
                    <li class="nav-item"><a class="nav-link" href="events.php">Events</a></li>
                    <li class="nav-item"><a class="nav-link" href="sermons.php">Sermons</a></li>
                    <li class="nav-item"><a class="nav-link" href="news.php">News</a></li>
                    <li class="nav-item"><a class="nav-link" href="gallery.php">Gallery</a></li>
                    <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
                    <li class="nav-item">
                        <a class="nav-link" href="profile.php">
                            <i class="fas fa-user me-2"></i>Profile
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">
                            <i class="fas fa-sign-out-alt me-2"></i>Logout
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Dashboard Container -->
    <div class="dashboard-container">
        <!-- Dashboard Header -->
        <div class="dashboard-header">
            <div class="dashboard-header-content">
                <div class="welcome-section">
                    <div class="welcome-info">
                        <h1>Welcome back, <?php echo htmlspecialchars($user['first_name'] ?? 'Member'); ?>!</h1>
                        <p><?php echo ucfirst($user_role); ?> Dashboard - Salem Dominion Ministries International</p>
                    </div>
                    <div class="user-actions">
                        <a href="profile.php" class="btn-dashboard">
                            <i class="fas fa-user-edit"></i> Edit Profile
                        </a>
                        <a href="gallery.php" class="btn-dashboard">
                            <i class="fas fa-images"></i> Gallery
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Dashboard Content -->
        <div class="dashboard-content">
            <?php if ($user_role === 'admin'): ?>
                <!-- Admin Dashboard -->
                <div class="dashboard-grid">
                    <div class="dashboard-card" data-aos="fade-up">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-users"></i>
                                Total Members
                            </h3>
                            <a href="#" class="card-action">View All</a>
                        </div>
                        <div class="stats-grid">
                            <div class="stat-item">
                                <div class="stat-number"><?php echo number_format($dashboard_data['total_users'] ?? 0); ?></div>
                                <div class="stat-label">Active Members</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-number"><?php echo number_format($members && $members->num_rows > 0 ? $members->num_rows : 0); ?></div>
                                <div class="stat-label">New This Month</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="dashboard-grid">
                    <div class="dashboard-card" data-aos="fade-up" data-aos-delay="100">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-calendar"></i>
                                Events
                            </h3>
                            <a href="events.php" class="card-action">Manage Events</a>
                        </div>
                        <div class="stats-grid">
                            <div class="stat-item">
                                <div class="stat-number"><?php echo number_format($dashboard_data['total_events'] ?? 0); ?></div>
                                <div class="stat-label">Total Events</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-number"><?php echo number_format($upcoming_events && $upcoming_events->num_rows > 0 ? $upcoming_events->num_rows : 0); ?></div>
                                <div class="stat-label">Upcoming</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="dashboard-grid">
                    <div class="dashboard-card" data-aos="fade-up" data-aos-delay="200">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-microphone"></i>
                                Sermons
                            </h3>
                            <a href="sermons.php" class="card-action">Manage Sermons</a>
                        </div>
                        <div class="stats-grid">
                            <div class="stat-item">
                                <div class="stat-number"><?php echo number_format($dashboard_data['total_sermons'] ?? 0); ?></div>
                                <div class="stat-label">Total Sermons</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-number"><?php echo number_format($sermons && $sermons->num_rows > 0 ? $sermons->num_rows : 0); ?></div>
                                <div class="stat-label">This Month</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="dashboard-grid">
                    <div class="dashboard-card" data-aos="fade-up" data-aos-delay="300">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-donate"></i>
                                Donations
                            </h3>
                            <a href="#" class="card-action">View All</a>
                        </div>
                        <div class="stats-grid">
                            <div class="stat-item">
                                <div class="stat-number"><?php echo number_format($dashboard_data['total_donations'] ?? 0); ?></div>
                                <div class="stat-label">Total Donations</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-number">$<?php echo number_format($dashboard_data['total_bookings'] ?? 0); ?></div>
                                <div class="stat-label">Bookings</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="dashboard-card" data-aos="fade-up" data-aos-delay="400">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-history"></i>
                            Recent Activities
                        </h3>
                        </div>
                        <ul class="activity-list">
                            <?php if ($dashboard_data['recent_activities'] && $dashboard_data['recent_activities']->num_rows > 0): ?>
                                <?php while ($activity = $dashboard_data['recent_activities']->fetch_assoc()): ?>
                                    <li class="activity-item">
                                        <div class="activity-icon">
                                            <i class="fas fa-bell"></i>
                                        </div>
                                        <div class="activity-content">
                                            <div class="activity-title"><?php echo htmlspecialchars($activity['description'] ?? 'Activity logged'); ?></div>
                                            <div class="activity-time"><?php echo date('M j, Y H:i', strtotime($activity['created_at'])); ?></div>
                                        </div>
                                    </li>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <li class="activity-item">
                                    <div class="activity-content">
                                        <div class="activity-title">No recent activities</div>
                                    </div>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>

            <?php elseif ($user_role === 'pastor'): ?>
                <!-- Pastor Dashboard -->
                <div class="dashboard-grid">
                    <div class="dashboard-card" data-aos="fade-up">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-calendar-check"></i>
                                Upcoming Bookings
                            </h3>
                            <a href="#" class="card-action">View All</a>
                        </div>
                        <ul class="activity-list">
                            <?php if ($dashboard_data['upcoming_bookings'] && $dashboard_data['upcoming_bookings']->num_rows > 0): ?>
                                <?php while ($booking = $dashboard_data['upcoming_bookings']->fetch_assoc()): ?>
                                    <li class="activity-item">
                                        <div class="activity-icon">
                                            <i class="fas fa-user-clock"></i>
                                        </div>
                                        <div class="activity-content">
                                            <div class="activity-title"><?php echo htmlspecialchars($booking['client_name'] ?? 'Client'); ?> - <?php echo htmlspecialchars($booking['service_type'] ?? 'Service'); ?></div>
                                            <div class="activity-time"><?php echo date('M j, Y H:i', strtotime($booking['booking_date'])); ?></div>
                                        </div>
                                    </li>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <li class="activity-item">
                                    <div class="activity-content">
                                        <div class="activity-title">No upcoming bookings</div>
                                    </div>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>

                <div class="dashboard-grid">
                    <div class="dashboard-card" data-aos="fade-up" data-aos-delay="100">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-pray"></i>
                                Prayer Requests
                            </h3>
                            <a href="#" class="card-action">View All</a>
                        </div>
                        <ul class="activity-list">
                            <?php if ($dashboard_data['prayer_requests'] && $dashboard_data['prayer_requests']->num_rows > 0): ?>
                                <?php while ($prayer = $dashboard_data['prayer_requests']->fetch_assoc()): ?>
                                    <li class="activity-item">
                                        <div class="activity-icon">
                                            <i class="fas fa-hands-praying"></i>
                                        </div>
                                        <div class="activity-content">
                                            <div class="activity-title"><?php echo htmlspecialchars($prayer['name'] ?? 'Anonymous'); ?> - <?php echo htmlspecialchars($prayer['request'] ?? 'Prayer request'); ?></div>
                                            <div class="activity-time"><?php echo date('M j, Y H:i', strtotime($prayer['created_at'])); ?></div>
                                        </div>
                                    </li>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <li class="activity-item">
                                    <div class="activity-content">
                                        <div class="activity-title">No prayer requests</div>
                                    </div>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>

                <div class="dashboard-card" data-aos="fade-up" data-aos-delay="200">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-history"></i>
                            Recent Activities
                        </h3>
                    </div>
                    <ul class="activity-list">
                        <?php if ($dashboard_data['recent_activities'] && $dashboard_data['recent_activities']->num_rows > 0): ?>
                            <?php while ($activity = $dashboard_data['recent_activities']->fetch_assoc()): ?>
                                <li class="activity-item">
                                    <div class="activity-icon">
                                        <i class="fas fa-bell"></i>
                                        </div>
                                    <div class="activity-content">
                                        <div class="activity-title"><?php echo htmlspecialchars($activity['description'] ?? 'Activity logged'); ?></div>
                                        <div class="activity-time"><?php echo date('M j, Y H:i', strtotime($activity['created_at'])); ?></div>
                                    </div>
                                </li>
                            <?php endwhile; ?>
                        <?php else: ?>
                                <li class="activity-item">
                                    <div class="activity-content">
                                        <div class="activity-title">No recent activities</div>
                                    </div>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>

            <?php else: ?>
                <!-- Member Dashboard -->
                <div class="dashboard-grid">
                    <div class="dashboard-card" data-aos="fade-up">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-images"></i>
                                Gallery
                            </h3>
                            <a href="gallery.php" class="card-action">View Gallery</a>
                        </div>
                        <div class="gallery-grid">
                            <?php if ($gallery_images && $gallery_images->num_rows > 0): ?>
                                <?php $count = 0; ?>
                                <?php while ($image = $gallery_images->fetch_assoc() && $count < 6): ?>
                                    <div class="gallery-item" onclick="window.location.href='gallery.php'">
                                        <img src="<?php echo htmlspecialchars($image['file_url']); ?>" alt="<?php echo htmlspecialchars($image['title'] ?? 'Gallery Image'); ?>">
                                    </div>
                                    <?php $count++; ?>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <div class="gallery-item" onclick="window.location.href='gallery.php'">
                                    <img src="assets/hero-worship-CWyaH0tr.jpg" alt="Gallery">
                                </div>
                                <div class="gallery-item" onclick="window.location.href='gallery.php'">
                                    <img src="assets/hero-community-CDAgPtPb.jpg" alt="Gallery">
                                </div>
                                <div class="gallery-item" onclick="window.location.href='gallery.php'">
                                    <img src="assets/hero-choir-6lo-hX_h.jpg" alt="Gallery">
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="dashboard-grid">
                    <div class="dashboard-card" data-aos="fade-up" data-aos-delay="100">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-calendar"></i>
                                Upcoming Events
                            </h3>
                            <a href="events.php" class="card-action">View All Events</a>
                        </div>
                        <ul class="activity-list">
                            <?php if ($upcoming_events && $upcoming_events->num_rows > 0): ?>
                                <?php while ($event = $upcoming_events->fetch_assoc()): ?>
                                    <li class="activity-item">
                                        <div class="activity-icon">
                                            <i class="fas fa-calendar-alt"></i>
                                        </div>
                                        <div class="activity-content">
                                            <div class="activity-title"><?php echo htmlspecialchars($event['title'] ?? 'Event'); ?></div>
                                            <div class="activity-time"><?php echo date('M j, Y', strtotime($event['event_date'])); ?></div>
                                        </div>
                                    </li>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <li class="activity-item">
                                    <div class="activity-content">
                                        <div class="activity-title">No upcoming events</div>
                                    </div>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>

                <div class="dashboard-grid">
                    <div class="dashboard-card" data-aos="fade-up" data-aos-delay="200">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-microphone"></i>
                                Recent Sermons
                            </h3>
                            <a href="sermons.php" class="card-action">View All Sermons</a>
                        </div>
                        <ul class="activity-list">
                            <?php if ($sermons && $sermons->num_rows > 0): ?>
                                <?php while ($sermon = $sermons->fetch_assoc()): ?>
                                    <li class="activity-item">
                                        <div class="activity-icon">
                                            <i class="fas fa-bible"></i>
                                        </div>
                                        <div class="activity-content">
                                            <div class="activity-title"><?php echo htmlspecialchars($sermon['title'] ?? 'Sermon'); ?></div>
                                            <div class="activity-time"><?php echo date('M j, Y', strtotime($sermon['sermon_date'])); ?></div>
                                        </div>
                                    </li>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <li class="activity-item">
                                    <div class="activity-content">
                                        <div class="activity-title">No recent sermons</div>
                                    </div>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>

                <div class="dashboard-grid">
                    <div class="dashboard-card" data-aos="fade-up" data-aos-delay="300">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-newspaper"></i>
                                Latest News
                            </h3>
                            <a href="news.php" class="card-action">View All News</a>
                        </div>
                        <ul class="activity-list">
                            <?php if ($news_items && $news_items->num_rows > 0): ?>
                                <?php while ($news = $news_items->fetch_assoc()): ?>
                                    <li class="activity-item">
                                        <div class="activity-icon">
                                            <i class="fas fa-newspaper"></i>
                                        </div>
                                        <div class="activity-content">
                                            <div class="activity-title"><?php echo htmlspecialchars($news['title'] ?? 'News'); ?></div>
                                            <div class="activity-time"><?php echo date('M j, Y', strtotime($news['created_at'])); ?></div>
                                        </div>
                                    </li>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <li class="activity-item">
                                    <div class="activity-content">
                                        <div class="activity-title">No recent news</div>
                                    </div>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>

                <div class="dashboard-grid">
                    <div class="dashboard-card" data-aos="fade-up" data-aos-delay="400">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-users"></i>
                                Leadership
                            </h3>
                            <a href="leadership.php" class="card-action">View Leadership</a>
                        </div>
                        <ul class="activity-list">
                            <?php if ($leadership && $leadership->num_rows > 0): ?>
                                <?php while ($leader = $leadership->fetch_assoc()): ?>
                                    <li class="activity-item">
                                        <div class="activity-icon">
                                            <i class="fas fa-user-tie"></i>
                                        </div>
                                        <div class="activity-content">
                                            <div class="activity-title"><?php echo htmlspecialchars($leader['name'] ?? 'Leader'); ?> - <?php echo htmlspecialchars($leader['title'] ?? 'Position'); ?></div>
                                            <div class="activity-time"><?php echo htmlspecialchars($leader['email'] ?? 'Email'); ?></div>
                                        </div>
                                    </li>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <li class="activity-item">
                                    <div class="activity-content">
                                        <div class="activity-title">No leadership information</div>
                                    </div>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Clean Footer -->
    <?php require_once 'components/ultimate_footer_clean.php'; ?>

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

        // Navbar scroll effect
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar');
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });

        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    </script>
</body>
</html>
