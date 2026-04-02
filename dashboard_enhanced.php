<?php
session_start();
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

switch ($user_role) {
    case 'admin':
        $dashboard_data['total_users'] = $db->selectOne("SELECT COUNT(*) as count FROM users WHERE is_active = 1")['count'];
        $dashboard_data['total_events'] = $db->selectOne("SELECT COUNT(*) as count FROM events")['count'];
        $dashboard_data['total_bookings'] = $db->selectOne("SELECT COUNT(*) as count FROM pastor_bookings")['count'];
        $dashboard_data['total_donations'] = $db->selectOne("SELECT COUNT(*) as count FROM donations")['count'];
        break;
        
    case 'pastor':
        $dashboard_data['upcoming_bookings'] = $db->select("SELECT * FROM pastor_bookings WHERE status = 'pending' ORDER BY booking_date ASC LIMIT 5");
        $dashboard_data['prayer_requests'] = $db->select("SELECT * FROM prayer_requests WHERE status = 'pending' ORDER BY created_at DESC LIMIT 5");
        break;
        
    case 'member':
        $dashboard_data['my_bookings'] = $db->select("SELECT * FROM pastor_bookings WHERE client_email = ? ORDER BY booking_date DESC LIMIT 3", [$user['email']]);
        $dashboard_data['upcoming_events'] = $db->select("SELECT * FROM events WHERE status = 'upcoming' ORDER BY event_date ASC LIMIT 5");
        break;
        
    default:
        $dashboard_data['upcoming_events'] = $db->select("SELECT * FROM events WHERE status = 'upcoming' ORDER BY event_date ASC LIMIT 5");
        break;
}

// Get recent activities
$recent_activities = $db->select("SELECT * FROM activity_logs WHERE user_id = ? ORDER BY created_at DESC LIMIT 5", [$_SESSION['user_id']]);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Salem Dominion Ministries</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- AOS Animation -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700;900&family=Montserrat:wght@300;400;500;600;700&family=Dancing+Script:wght@700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #e74c3c;
            --accent-color: #f39c12;
            --heavenly-gold: #FFD700;
            --angel-white: #F8F8FF;
            --divine-blue: #4169E1;
            --grace-purple: #9370DB;
            --light-color: #ecf0f1;
            --dark-color: #34495e;
            --gradient-heavenly: linear-gradient(135deg, #FFD700 0%, #FFA500 50%, #FF6347 100%);
            --gradient-angel: linear-gradient(135deg, #F8F8FF 0%, #E6E6FA 50%, #D8BFD8 100%);
            --gradient-divine: linear-gradient(135deg, #4169E1 0%, #9370DB 50%, #FFD700 100%);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Montserrat', sans-serif;
            line-height: 1.6;
            color: var(--dark-color);
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            position: relative;
            overflow-x: hidden;
        }

        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><text y="50" font-size="100" fill="rgba(255,215,0,0.03)">✨</text></svg>') repeat;
            pointer-events: none;
            z-index: 1;
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: 'Playfair Display', serif;
            font-weight: 700;
        }

        /* Navigation */
        .navbar {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            box-shadow: 0 2px 20px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            padding: 1rem 0;
            position: relative;
            z-index: 1000;
        }

        .navbar-brand {
            font-family: 'Dancing Script', cursive;
            font-size: 2rem;
            font-weight: 700;
            background: var(--gradient-divine);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Dashboard Header */
        .dashboard-header {
            background: var(--gradient-divine);
            color: white;
            padding: 80px 0 40px;
            position: relative;
            overflow: hidden;
        }

        .dashboard-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: -50%;
            width: 200%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
            animation: shimmer 3s infinite;
        }

        @keyframes shimmer {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }

        .welcome-angel {
            position: absolute;
            top: 20px;
            right: 20px;
            font-size: 3rem;
            color: rgba(255, 255, 255, 0.3);
            animation: float 3s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }

        /* Dashboard Container */
        .dashboard-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
            position: relative;
            z-index: 2;
        }

        /* Stats Cards */
        .stat-card {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            border: 2px solid transparent;
            background-clip: padding-box;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: var(--gradient-heavenly);
        }

        .stat-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
            border-color: var(--heavenly-gold);
        }

        .stat-icon {
            font-size: 3rem;
            color: var(--heavenly-gold);
            margin-bottom: 1rem;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: 900;
            color: var(--primary-color);
            font-family: 'Playfair Display', serif;
        }

        .stat-label {
            color: var(--dark-color);
            font-weight: 600;
            margin-top: 0.5rem;
        }

        /* Activity Cards */
        .activity-card {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            margin-bottom: 2rem;
            position: relative;
        }

        .activity-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 5px;
            height: 100%;
            background: var(--gradient-angel);
            border-radius: 20px 0 0 20px;
        }

        .activity-item {
            display: flex;
            align-items: center;
            padding: 1rem 0;
            border-bottom: 1px solid #f0f0f0;
            transition: all 0.3s ease;
        }

        .activity-item:last-child {
            border-bottom: none;
        }

        .activity-item:hover {
            background: rgba(255, 215, 0, 0.05);
            margin: 0 -1rem;
            padding: 1rem;
            border-radius: 10px;
        }

        .activity-icon {
            width: 40px;
            height: 40px;
            background: var(--gradient-heavenly);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            margin-right: 1rem;
            flex-shrink: 0;
        }

        .activity-content {
            flex: 1;
        }

        .activity-title {
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 0.25rem;
        }

        .activity-time {
            font-size: 0.9rem;
            color: #666;
        }

        /* Quick Actions */
        .quick-actions {
            background: var(--gradient-angel);
            border-radius: 20px;
            padding: 2rem;
            margin-bottom: 2rem;
        }

        .action-btn {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem 1.5rem;
            background: white;
            border: none;
            border-radius: 15px;
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            margin-bottom: 1rem;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .action-btn:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            background: var(--gradient-heavenly);
            color: white;
        }

        .action-icon {
            font-size: 1.5rem;
            color: var(--heavenly-gold);
        }

        .action-btn:hover .action-icon {
            color: white;
        }

        /* Prayer Corner */
        .prayer-corner {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 20px;
            padding: 2rem;
            text-align: center;
            border: 2px solid var(--heavenly-gold);
            position: relative;
            overflow: hidden;
        }

        .prayer-corner::before {
            content: '🙏';
            position: absolute;
            top: 10px;
            right: 10px;
            font-size: 2rem;
            opacity: 0.3;
        }

        .prayer-btn {
            background: var(--gradient-heavenly);
            color: white;
            border: none;
            padding: 1rem 2rem;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s ease;
            margin-top: 1rem;
        }

        .prayer-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(255, 215, 0, 0.3);
        }

        /* User Profile Card */
        .profile-card {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            position: relative;
            overflow: hidden;
        }

        .profile-avatar {
            width: 100px;
            height: 100px;
            background: var(--gradient-divine);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 2rem;
            font-weight: 700;
            margin: 0 auto 1rem;
            position: relative;
        }

        .profile-avatar::after {
            content: '';
            position: absolute;
            top: -5px;
            left: -5px;
            right: -5px;
            bottom: -5px;
            background: var(--gradient-heavenly);
            border-radius: 50%;
            z-index: -1;
            animation: pulse 2s infinite;
        }

        .profile-name {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 0.5rem;
        }

        .profile-role {
            color: var(--accent-color);
            font-weight: 600;
            margin-bottom: 1rem;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .dashboard-header {
                padding: 60px 0 30px;
            }
            
            .dashboard-container {
                padding: 1rem;
            }
            
            .stat-card {
                padding: 1.5rem;
            }
            
            .stat-number {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <i class="fas fa-church"></i> Salem Dominion Ministries
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto">
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
                    <li class="nav-item"><a class="nav-link active" href="dashboard.php">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Dashboard Header -->
    <header class="dashboard-header">
        <div class="container">
            <div class="welcome-angel">
                <i class="fas fa-dove"></i>
            </div>
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="display-4 fw-bold mb-3" data-aos="fade-up">
                        Welcome Back, <?php echo htmlspecialchars($user['first_name']); ?>
                    </h1>
                    <p class="lead mb-0" data-aos="fade-up" data-aos-delay="100">
                        May God's grace and peace be with you today and always
                    </p>
                </div>
                <div class="col-md-4" data-aos="fade-left">
                    <div class="profile-card">
                        <div class="profile-avatar">
                            <?php echo strtoupper(substr($user['first_name'], 0, 1) . substr($user['last_name'], 0, 1)); ?>
                        </div>
                        <h5 class="profile-name"><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></h5>
                        <p class="profile-role"><?php echo ucfirst($user['role']); ?></p>
                        <small class="text-muted">Member since <?php echo date('M Y', strtotime($user['created_at'])); ?></small>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Dashboard Content -->
    <div class="dashboard-container">
        <!-- Stats Section -->
        <?php if ($user_role === 'admin'): ?>
        <div class="row mb-4">
            <div class="col-md-3 col-6 mb-3" data-aos="fade-up">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-number"><?php echo $dashboard_data['total_users']; ?></div>
                    <div class="stat-label">Total Members</div>
                </div>
            </div>
            <div class="col-md-3 col-6 mb-3" data-aos="fade-up" data-aos-delay="100">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-calendar"></i>
                    </div>
                    <div class="stat-number"><?php echo $dashboard_data['total_events']; ?></div>
                    <div class="stat-label">Events</div>
                </div>
            </div>
            <div class="col-md-3 col-6 mb-3" data-aos="fade-up" data-aos-delay="200">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-phone"></i>
                    </div>
                    <div class="stat-number"><?php echo $dashboard_data['total_bookings']; ?></div>
                    <div class="stat-label">Bookings</div>
                </div>
            </div>
            <div class="col-md-3 col-6 mb-3" data-aos="fade-up" data-aos-delay="300">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-heart"></i>
                    </div>
                    <div class="stat-number"><?php echo $dashboard_data['total_donations']; ?></div>
                    <div class="stat-label">Donations</div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Quick Actions -->
        <div class="quick-actions" data-aos="fade-up">
            <h4 class="mb-3">
                <i class="fas fa-magic me-2"></i>Quick Actions
            </h4>
            <div class="row">
                <div class="col-md-3 col-6">
                    <a href="pastor_booking.php" class="action-btn">
                        <div class="action-icon">
                            <i class="fas fa-calendar-plus"></i>
                        </div>
                        <div>
                            <div class="action-title">Book Pastor</div>
                            <small>Schedule a meeting</small>
                        </div>
                    </a>
                </div>
                <div class="col-md-3 col-6">
                    <a href="prayer_requests.php" class="action-btn">
                        <div class="action-icon">
                            <i class="fas fa-praying-hands"></i>
                        </div>
                        <div>
                            <div class="action-title">Prayer Request</div>
                            <small>Submit prayer needs</small>
                        </div>
                    </a>
                </div>
                <div class="col-md-3 col-6">
                    <a href="events.php" class="action-btn">
                        <div class="action-icon">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                        <div>
                            <div class="action-title">View Events</div>
                            <small>Upcoming activities</small>
                        </div>
                    </a>
                </div>
                <div class="col-md-3 col-6">
                    <a href="sermons.php" class="action-btn">
                        <div class="action-icon">
                            <i class="fas fa-bible"></i>
                        </div>
                        <div>
                            <div class="action-title">Latest Sermons</div>
                            <small>Watch & listen</small>
                        </div>
                    </a>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="row">
            <!-- Recent Activities -->
            <div class="col-md-8" data-aos="fade-right">
                <div class="activity-card">
                    <h4 class="mb-4">
                        <i class="fas fa-history me-2"></i>Recent Activities
                    </h4>
                    <?php if (empty($recent_activities)): ?>
                        <div class="text-center py-4">
                            <i class="fas fa-dove fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No recent activities. Start exploring the church features!</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($recent_activities as $activity): ?>
                        <div class="activity-item">
                            <div class="activity-icon">
                                <i class="fas fa-star"></i>
                            </div>
                            <div class="activity-content">
                                <div class="activity-title"><?php echo htmlspecialchars($activity['action']); ?></div>
                                <div class="activity-time"><?php echo date('M j, Y g:i A', strtotime($activity['created_at'])); ?></div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <!-- Role-specific content -->
                <?php if ($user_role === 'pastor' && !empty($dashboard_data['upcoming_bookings'])): ?>
                <div class="activity-card">
                    <h4 class="mb-4">
                        <i class="fas fa-calendar-check me-2"></i>Upcoming Bookings
                    </h4>
                    <?php foreach ($dashboard_data['upcoming_bookings'] as $booking): ?>
                    <div class="activity-item">
                        <div class="activity-icon">
                            <i class="fas fa-phone"></i>
                        </div>
                        <div class="activity-content">
                            <div class="activity-title"><?php echo htmlspecialchars($booking['client_name']); ?></div>
                            <div class="activity-time"><?php echo date('M j, Y g:i A', strtotime($booking['booking_date'] . ' ' . $booking['start_time'])); ?></div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>

                <?php if ($user_role === 'member' && !empty($dashboard_data['my_bookings'])): ?>
                <div class="activity-card">
                    <h4 class="mb-4">
                        <i class="fas fa-calendar-alt me-2"></i>My Bookings
                    </h4>
                    <?php foreach ($dashboard_data['my_bookings'] as $booking): ?>
                    <div class="activity-item">
                        <div class="activity-icon">
                            <i class="fas fa-video"></i>
                        </div>
                        <div class="activity-content">
                            <div class="activity-title"><?php echo ucfirst($booking['booking_type']); ?> - <?php echo htmlspecialchars($booking['subject']); ?></div>
                            <div class="activity-time"><?php echo date('M j, Y g:i A', strtotime($booking['booking_date'] . ' ' . $booking['start_time'])); ?></div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>

            <!-- Sidebar -->
            <div class="col-md-4" data-aos="fade-left">
                <!-- Prayer Corner -->
                <div class="prayer-corner mb-4">
                    <h4 class="mb-3">
                        <i class="fas fa-praying-hands me-2"></i>Daily Prayer
                    </h4>
                    <p>May the Lord bless you and keep you. May His face shine upon you.</p>
                    <button class="prayer-btn" onclick="heavenlyGuidance.showPrayerGuidance('general')">
                        <i class="fas fa-dove me-2"></i>Receive Blessing
                    </button>
                </div>

                <!-- Upcoming Events -->
                <?php if (!empty($dashboard_data['upcoming_events'])): ?>
                <div class="activity-card">
                    <h4 class="mb-4">
                        <i class="fas fa-calendar me-2"></i>Upcoming Events
                    </h4>
                    <?php foreach ($dashboard_data['upcoming_events'] as $event): ?>
                    <div class="activity-item">
                        <div class="activity-icon">
                            <i class="fas fa-star"></i>
                        </div>
                        <div class="activity-content">
                            <div class="activity-title"><?php echo htmlspecialchars($event['title']); ?></div>
                            <div class="activity-time"><?php echo date('M j, Y', strtotime($event['event_date'])); ?></div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>

                <!-- Scripture of the Day -->
                <div class="activity-card">
                    <h4 class="mb-4">
                        <i class="fas fa-bible me-2"></i>Today's Scripture
                    </h4>
                    <div class="text-center">
                        <h5 class="text-primary mb-2">Jeremiah 29:11</h5>
                        <p class="fst-italic">"For I know the thoughts that I think toward you, says the Lord, thoughts of peace and not of evil, to give you a future and a hope."</p>
                        <button class="btn btn-primary btn-sm mt-2" onclick="heavenlyGuidance.showPrayerGuidance('guidance')">
                            <i class="fas fa-pray me-1"></i>Pray on This
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="assets/js/heavenly_sounds.js"></script>
    <script>
        // Initialize AOS
        AOS.init({
            duration: 1000,
            once: true,
            offset: 100
        });

        // Show dashboard-specific guidance
        document.addEventListener('DOMContentLoaded', () => {
            setTimeout(() => {
                if (window.heavenlyGuidance) {
                    window.heavenlyGuidance.showDashboardGuidance('<?php echo $user_role; ?>');
                }
            }, 3000);
        });

        // Add heavenly hover effects
        document.querySelectorAll('.stat-card, .activity-card, .action-btn').forEach(element => {
            element.addEventListener('mouseenter', () => {
                if (window.heavenlyGuidance && Math.random() > 0.9) {
                    window.heavenlyGuidance.playGentleChime();
                }
            });
        });

        // Periodic angelic presence
        setInterval(() => {
            if (window.heavenlyGuidance && Math.random() > 0.98) {
                window.heavenlyGuidance.showAngelGuidance();
            }
        }, 30000); // Every 30 seconds
    </script>
</body>
</html>
