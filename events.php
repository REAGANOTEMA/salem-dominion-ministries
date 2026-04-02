<?php
// Include session helper and start session safely
require_once 'session_helper.php';
secure_session_start();
require_once 'config.php';
require_once 'db.php';

// Handle event actions
$action = $_GET['action'] ?? 'list';
$event_id = $_GET['id'] ?? null;

// Get all events with error handling
try {
    $events = $db->query("SELECT e.*, u.first_name, u.last_name FROM events e LEFT JOIN users u ON e.created_by = u.id WHERE e.status != 'deleted' ORDER BY e.event_date ASC");
    $upcoming_events = $db->query("SELECT e.*, u.first_name, u.last_name FROM events e LEFT JOIN users u ON e.created_by = u.id WHERE e.status = 'upcoming' AND e.event_date >= CURDATE() ORDER BY e.event_date ASC LIMIT 6");
    $past_events = $db->query("SELECT e.*, u.first_name, u.last_name FROM events e LEFT JOIN users u ON e.created_by = u.id WHERE e.event_date < CURDATE() AND e.status = 'completed' ORDER BY e.event_date DESC LIMIT 3");
} catch (Exception $e) {
    error_log("Database error in events: " . $e->getMessage());
    $events = [];
    $upcoming_events = [];
    $past_events = [];
}

// Handle event registration
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register_event'])) {
    $event_id = $_POST['event_id'];
    $user_id = $_SESSION['user_id'] ?? null;
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    
    if ($event_id && $name && $email) {
        try {
            // Check if already registered
            $existing = $db->selectOne("SELECT id FROM event_registrations WHERE event_id = ? AND email = ?", [$event_id, $email]);
            
            if (!$existing) {
                $db->query("INSERT INTO event_registrations (event_id, user_id, name, email, phone, registration_date) VALUES (?, ?, ?, ?, ?, NOW())", [$event_id, $user_id, $name, $email, $phone]);
                $success_message = "Successfully registered for the event!";
            } else {
                $error_message = "You are already registered for this event.";
            }
        } catch (Exception $e) {
            error_log("Registration error: " . $e->getMessage());
            $error_message = "Registration failed. Please try again.";
        }
    }
}

// Helper functions
function safe_html($string, $default = '') {
    return htmlspecialchars($string ?? $default, ENT_QUOTES, 'UTF-8');
}

function safe_date($date, $format) {
    try {
        return date($format, strtotime($date));
    } catch (Exception $e) {
        return 'Date not available';
    }
}

function format_event_date($date) {
    $event_date = new DateTime($date);
    $today = new DateTime();
    $diff = $today->diff($event_date);
    
    if ($diff->days == 0) {
        return 'Today';
    } elseif ($diff->days == 1) {
        return $event_date > $today ? 'Tomorrow' : 'Yesterday';
    } elseif ($diff->days <= 7) {
        return $event_date > $today ? $event_date->format('l') : $event_date->format('l') . ' (Past)';
    } else {
        return safe_date($date, 'M j, Y');
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Events at Salem Dominion Ministries - Join us for worship, fellowship, and community activities">
    <meta name="keywords" content="church events, worship services, community activities, Salem Dominion Ministries">
    <title>Events - Salem Dominion Ministries</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- AOS Animation -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700;900&family=Montserrat:wght@300;400;500;600;700&family=Dancing+Script:wght@700&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="assets/css/perfect_responsive.css" rel="stylesheet">
    
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

        body {
            font-family: 'Montserrat', sans-serif;
            line-height: 1.6;
            color: var(--dark-color);
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
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
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><text y="50" font-size="100" fill="rgba(255,215,0,0.02)">✨</text></svg>') repeat;
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

        /* Events Header */
        .events-header {
            background: var(--gradient-divine);
            color: white;
            padding: 120px 0 80px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .events-header::before {
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

        .events-title {
            font-size: clamp(2.5rem, 5vw, 4rem);
            font-weight: 900;
            margin-bottom: 1rem;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }

        .events-subtitle {
            font-size: clamp(1.2rem, 3vw, 1.8rem);
            font-weight: 300;
            margin-bottom: 2rem;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        /* Filter Tabs */
        .filter-tabs {
            background: white;
            border-radius: 20px;
            padding: 1rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            margin-bottom: 3rem;
            position: relative;
            z-index: 2;
        }

        .filter-tabs .nav-link {
            color: var(--primary-color);
            font-weight: 600;
            border: none;
            border-radius: 15px;
            padding: 0.8rem 1.5rem;
            margin: 0.25rem;
            transition: all 0.3s ease;
        }

        .filter-tabs .nav-link:hover {
            background: rgba(255, 215, 0, 0.1);
            color: var(--heavenly-gold);
        }

        .filter-tabs .nav-link.active {
            background: var(--gradient-heavenly);
            color: white;
        }

        /* Event Cards */
        .event-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            margin-bottom: 2rem;
            position: relative;
        }

        .event-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--gradient-heavenly);
        }

        .event-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(255, 215, 0, 0.2);
        }

        .event-image {
            height: 250px;
            background: var(--gradient-angel);
            position: relative;
            overflow: hidden;
        }

        .event-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .event-card:hover .event-image img {
            transform: scale(1.1);
        }

        .event-date-badge {
            position: absolute;
            top: 20px;
            right: 20px;
            background: var(--gradient-heavenly);
            color: white;
            padding: 0.8rem 1.2rem;
            border-radius: 15px;
            font-weight: 700;
            text-align: center;
            box-shadow: 0 5px 15px rgba(255, 215, 0, 0.3);
        }

        .event-date-badge .day {
            font-size: 1.5rem;
            display: block;
        }

        .event-date-badge .month {
            font-size: 0.9rem;
            opacity: 0.9;
        }

        .event-content {
            padding: 2rem;
        }

        .event-title {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: var(--primary-color);
        }

        .event-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .event-meta-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--dark-color);
            font-size: 0.9rem;
        }

        .event-meta-item i {
            color: var(--heavenly-gold);
        }

        .event-description {
            color: var(--dark-color);
            margin-bottom: 1.5rem;
            line-height: 1.6;
        }

        .event-actions {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .btn-event {
            padding: 0.8rem 1.5rem;
            border-radius: 50px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-primary-event {
            background: var(--gradient-heavenly);
            color: white;
        }

        .btn-primary-event:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(255, 215, 0, 0.3);
        }

        .btn-outline-event {
            background: transparent;
            color: var(--primary-color);
            border: 2px solid var(--primary-color);
        }

        .btn-outline-event:hover {
            background: var(--primary-color);
            color: white;
        }

        /* Event Registration Modal */
        .registration-modal .modal-content {
            border-radius: 20px;
            border: none;
        }

        .registration-modal .modal-header {
            background: var(--gradient-divine);
            color: white;
            border-radius: 20px 20px 0 0;
            border: none;
        }

        .registration-modal .modal-title {
            font-weight: 700;
        }

        /* Calendar View */
        .calendar-view {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            margin-bottom: 2rem;
        }

        .calendar-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        .calendar-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 0.5rem;
        }

        .calendar-day {
            aspect-ratio: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .calendar-day:hover {
            background: rgba(255, 215, 0, 0.1);
            transform: scale(1.05);
        }

        .calendar-day.has-event {
            background: var(--gradient-heavenly);
            color: white;
        }

        /* Stats Section */
        .stats-section {
            background: var(--gradient-angel);
            border-radius: 20px;
            padding: 2rem;
            margin-bottom: 3rem;
        }

        .stat-item {
            text-align: center;
            padding: 1rem;
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: 900;
            color: var(--heavenly-gold);
            margin-bottom: 0.5rem;
        }

        .stat-label {
            color: var(--dark-color);
            font-weight: 600;
        }

        /* Floating Elements */
        .floating-angel {
            position: fixed;
            font-size: 2rem;
            color: var(--heavenly-gold);
            opacity: 0.3;
            pointer-events: none;
            z-index: 2;
            animation: float 6s ease-in-out infinite;
        }

        .angel-1 { top: 20%; left: 5%; animation-delay: 0s; }
        .angel-2 { top: 60%; right: 5%; animation-delay: 2s; }

        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-30px) rotate(10deg); }
        }

        /* Responsive */
        @media (max-width: 768px) {
            .events-header {
                padding: 80px 0 60px;
            }
            
            .event-date-badge {
                top: 10px;
                right: 10px;
                padding: 0.5rem 0.8rem;
            }
            
            .event-date-badge .day {
                font-size: 1.2rem;
            }
            
            .event-content {
                padding: 1.5rem;
            }
            
            .event-actions {
                flex-direction: column;
            }
            
            .btn-event {
                width: 100%;
                justify-content: center;
            }
            
            .calendar-grid {
                gap: 0.25rem;
            }
            
            .calendar-day {
                font-size: 0.9rem;
            }
        }

        /* Loading State */
        .loading-spinner {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: white;
            animation: spin 1s ease-in-out infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* Success/Error Messages */
        .alert-custom {
            border-radius: 15px;
            border: none;
            padding: 1rem 1.5rem;
            margin-bottom: 2rem;
        }

        .alert-success-custom {
            background: linear-gradient(135deg, #d4edda, #c3e6cb);
            color: #155724;
            border-left: 4px solid #28a745;
        }

        .alert-error-custom {
            background: linear-gradient(135deg, #f8d7da, #f5c6cb);
            color: #721c24;
            border-left: 4px solid #dc3545;
        }
    </style>
</head>
<body>
    <!-- Floating Angels -->
    <div class="floating-angel angel-1" role="img" aria-label="Decorative angel">🕊️</div>
    <div class="floating-angel angel-2" role="img" aria-label="Decorative angel">✨</div>

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
                    <li class="nav-item"><a class="nav-link active" href="events.php">Events</a></li>
                    <li class="nav-item"><a class="nav-link" href="sermons.php">Sermons</a></li>
                    <li class="nav-item"><a class="nav-link" href="news.php">News</a></li>
                    <li class="nav-item"><a class="nav-link" href="gallery.php">Gallery</a></li>
                    <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
                </ul>
                <ul class="navbar-nav">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li class="nav-item"><a class="nav-link" href="dashboard.php">Dashboard</a></li>
                        <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
                    <?php else: ?>
                        <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
                        <li class="nav-item"><a class="nav-link" href="register.php">Register</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Events Header -->
    <header class="events-header">
        <div class="container">
            <h1 class="events-title" data-aos="fade-up">Church Events</h1>
            <p class="events-subtitle" data-aos="fade-up" data-aos-delay="100">Join us for worship, fellowship, and community activities</p>
        </div>
    </header>

    <!-- Main Content -->
    <main class="container" style="position: relative; z-index: 2;">
        <!-- Success/Error Messages -->
        <?php if (isset($success_message)): ?>
            <div class="alert alert-success-custom alert-custom" data-aos="fade-down">
                <i class="fas fa-check-circle me-2"></i>
                <?php echo safe_html($success_message); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($error_message)): ?>
            <div class="alert alert-error-custom alert-custom" data-aos="fade-down">
                <i class="fas fa-exclamation-circle me-2"></i>
                <?php echo safe_html($error_message); ?>
            </div>
        <?php endif; ?>

        <!-- Stats Section -->
        <section class="stats-section" data-aos="fade-up">
            <div class="row">
                <div class="col-md-3 col-6">
                    <div class="stat-item">
                        <div class="stat-number"><?php echo count($events); ?></div>
                        <div class="stat-label">Total Events</div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="stat-item">
                        <div class="stat-number"><?php echo count($upcoming_events); ?></div>
                        <div class="stat-label">Upcoming</div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="stat-item">
                        <div class="stat-number"><?php echo count($past_events); ?></div>
                        <div class="stat-label">Past Events</div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="stat-item">
                        <div class="stat-number">12</div>
                        <div class="stat-label">This Month</div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Filter Tabs -->
        <section class="filter-tabs" data-aos="fade-up" data-aos-delay="100">
            <ul class="nav nav-tabs justify-content-center" id="eventTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="upcoming-tab" data-bs-toggle="tab" data-bs-target="#upcoming" type="button" role="tab">
                        <i class="fas fa-calendar-alt me-2"></i>Upcoming Events
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="past-tab" data-bs-toggle="tab" data-bs-target="#past" type="button" role="tab">
                        <i class="fas fa-history me-2"></i>Past Events
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="calendar-tab" data-bs-toggle="tab" data-bs-target="#calendar" type="button" role="tab">
                        <i class="fas fa-calendar me-2"></i>Calendar View
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="all-tab" data-bs-toggle="tab" data-bs-target="#all" type="button" role="tab">
                        <i class="fas fa-list me-2"></i>All Events
                    </button>
                </li>
            </ul>
        </section>

        <!-- Tab Content -->
        <div class="tab-content" id="eventTabContent">
            <!-- Upcoming Events -->
            <div class="tab-pane fade show active" id="upcoming" role="tabpanel">
                <div class="row">
                    <?php if ($upcoming_events && $upcoming_events->num_rows > 0): ?>
                        <?php while ($event = $upcoming_events->fetch_assoc()): ?>
                            <div class="col-lg-6 col-xl-4" data-aos="fade-up" data-aos-delay="100">
                                <div class="event-card">
                                    <div class="event-image">
                                        <img src="https://images.unsplash.com/photo-1506905925346-21bda4d32df4?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="<?php echo safe_html($event['title']); ?>">
                                        <div class="event-date-badge">
                                            <span class="day"><?php echo safe_date($event['event_date'], 'd'); ?></span>
                                            <span class="month"><?php echo safe_date($event['event_date'], 'M'); ?></span>
                                        </div>
                                    </div>
                                    <div class="event-content">
                                        <h3 class="event-title"><?php echo safe_html($event['title']); ?></h3>
                                        <div class="event-meta">
                                            <div class="event-meta-item">
                                                <i class="fas fa-clock"></i>
                                                <span><?php echo safe_date($event['event_date'], 'g:i A'); ?></span>
                                            </div>
                                            <div class="event-meta-item">
                                                <i class="fas fa-calendar"></i>
                                                <span><?php echo format_event_date($event['event_date']); ?></span>
                                            </div>
                                        </div>
                                        <?php if ($event['location']): ?>
                                            <div class="event-meta">
                                                <div class="event-meta-item">
                                                    <i class="fas fa-map-marker-alt"></i>
                                                    <span><?php echo safe_html($event['location']); ?></span>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                        <p class="event-description">
                                            <?php echo safe_html(substr($event['description'], 0, 150)) . '...'; ?>
                                        </p>
                                        <div class="event-actions">
                                            <button class="btn-event btn-primary-event" data-bs-toggle="modal" data-bs-target="#registrationModal" data-event-id="<?php echo $event['id']; ?>">
                                                <i class="fas fa-user-plus"></i> Register
                                            </button>
                                            <a href="#" class="btn-event btn-outline-event">
                                                <i class="fas fa-info-circle"></i> Details
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <div class="col-12">
                            <div class="text-center py-5">
                                <i class="fas fa-calendar-times fa-4x text-muted mb-3"></i>
                                <h3 class="text-muted">No Upcoming Events</h3>
                                <p class="text-muted">Check back soon for new events and activities!</p>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Past Events -->
            <div class="tab-pane fade" id="past" role="tabpanel">
                <div class="row">
                    <?php if ($past_events && $past_events->num_rows > 0): ?>
                        <?php while ($event = $past_events->fetch_assoc()): ?>
                            <div class="col-lg-6 col-xl-4" data-aos="fade-up" data-aos-delay="100">
                                <div class="event-card opacity-75">
                                    <div class="event-image">
                                        <img src="https://images.unsplash.com/photo-1506905925346-21bda4d32df4?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="<?php echo safe_html($event['title']); ?>">
                                        <div class="event-date-badge">
                                            <span class="day"><?php echo safe_date($event['event_date'], 'd'); ?></span>
                                            <span class="month"><?php echo safe_date($event['event_date'], 'M'); ?></span>
                                        </div>
                                    </div>
                                    <div class="event-content">
                                        <h3 class="event-title"><?php echo safe_html($event['title']); ?></h3>
                                        <div class="event-meta">
                                            <div class="event-meta-item">
                                                <i class="fas fa-clock"></i>
                                                <span><?php echo safe_date($event['event_date'], 'g:i A'); ?></span>
                                            </div>
                                            <div class="event-meta-item">
                                                <i class="fas fa-calendar"></i>
                                                <span><?php echo safe_date($event['event_date'], 'M j, Y'); ?></span>
                                            </div>
                                        </div>
                                        <?php if ($event['location']): ?>
                                            <div class="event-meta">
                                                <div class="event-meta-item">
                                                    <i class="fas fa-map-marker-alt"></i>
                                                    <span><?php echo safe_html($event['location']); ?></span>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                        <p class="event-description">
                                            <?php echo safe_html(substr($event['description'], 0, 150)) . '...'; ?>
                                        </p>
                                        <div class="event-actions">
                                            <a href="#" class="btn-event btn-outline-event">
                                                <i class="fas fa-images"></i> View Photos
                                            </a>
                                            <a href="#" class="btn-event btn-outline-event">
                                                <i class="fas fa-video"></i> Watch Recording
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <div class="col-12">
                            <div class="text-center py-5">
                                <i class="fas fa-history fa-4x text-muted mb-3"></i>
                                <h3 class="text-muted">No Past Events</h3>
                                <p class="text-muted">Our past events will appear here once they've occurred.</p>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Calendar View -->
            <div class="tab-pane fade" id="calendar" role="tabpanel">
                <div class="calendar-view">
                    <div class="calendar-header">
                        <h3><i class="fas fa-calendar me-2"></i>Event Calendar</h3>
                        <div>
                            <button class="btn btn-outline-primary btn-sm me-2">
                                <i class="fas fa-chevron-left"></i>
                            </button>
                            <span class="fw-bold">December 2026</span>
                            <button class="btn btn-outline-primary btn-sm ms-2">
                                <i class="fas fa-chevron-right"></i>
                            </button>
                        </div>
                    </div>
                    <div class="calendar-grid">
                        <?php
                        $days = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
                        foreach ($days as $day): ?>
                            <div class="calendar-day fw-bold text-muted"><?php echo $day; ?></div>
                        <?php endforeach; ?>
                        
                        <?php for ($i = 1; $i <= 31; $i++): ?>
                            <div class="calendar-day <?php echo ($i % 7 === 0 || $i % 7 === 6) ? 'text-muted' : ''; ?>">
                                <?php echo $i; ?>
                            </div>
                        <?php endfor; ?>
                    ?>
                    </div>
                </div>
            </div>

            <!-- All Events -->
            <div class="tab-pane fade" id="all" role="tabpanel">
                <div class="row">
                    <?php if ($events && $events->num_rows > 0): ?>
                        <?php while ($event = $events->fetch_assoc()): ?>
                            <div class="col-lg-6 col-xl-4" data-aos="fade-up" data-aos-delay="100">
                                <div class="event-card">
                                    <div class="event-image">
                                        <img src="https://images.unsplash.com/photo-1506905925346-21bda4d32df4?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="<?php echo safe_html($event['title']); ?>">
                                        <div class="event-date-badge">
                                            <span class="day"><?php echo safe_date($event['event_date'], 'd'); ?></span>
                                            <span class="month"><?php echo safe_date($event['event_date'], 'M'); ?></span>
                                        </div>
                                    </div>
                                    <div class="event-content">
                                        <h3 class="event-title"><?php echo safe_html($event['title']); ?></h3>
                                        <div class="event-meta">
                                            <div class="event-meta-item">
                                                <i class="fas fa-clock"></i>
                                                <span><?php echo safe_date($event['event_date'], 'g:i A'); ?></span>
                                            </div>
                                            <div class="event-meta-item">
                                                <i class="fas fa-calendar"></i>
                                                <span><?php echo format_event_date($event['event_date']); ?></span>
                                            </div>
                                        </div>
                                        <?php if ($event['location']): ?>
                                            <div class="event-meta">
                                                <div class="event-meta-item">
                                                    <i class="fas fa-map-marker-alt"></i>
                                                    <span><?php echo safe_html($event['location']); ?></span>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                        <p class="event-description">
                                            <?php echo safe_html(substr($event['description'], 0, 150)) . '...'; ?>
                                        </p>
                                        <div class="event-actions">
                                            <a href="#" class="btn-event btn-primary-event">
                                                <i class="fas fa-info-circle"></i> Details
                                            </a>
                                            <a href="#" class="btn-event btn-outline-event">
                                                <i class="fas fa-share"></i> Share
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <div class="col-12">
                            <div class="text-center py-5">
                                <i class="fas fa-calendar-times fa-4x text-muted mb-3"></i>
                                <h3 class="text-muted">No Events Found</h3>
                                <p class="text-muted">No events are currently scheduled.</p>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>

    <!-- Registration Modal -->
    <div class="modal fade registration-modal" id="registrationModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-user-plus me-2"></i>Event Registration
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="events.php">
                    <div class="modal-body">
                        <input type="hidden" name="event_id" id="event_id">
                        <div class="mb-3">
                            <label class="form-label">Full Name *</label>
                            <input type="text" class="form-control" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email Address *</label>
                            <input type="email" class="form-control" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Phone Number</label>
                            <input type="tel" class="form-control" name="phone">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Number of Attendees</label>
                            <select class="form-select" name="attendees">
                                <option value="1">1 Person</option>
                                <option value="2">2 People</option>
                                <option value="3">3 People</option>
                                <option value="4">4 People</option>
                                <option value="5">5+ People</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Special Requirements</label>
                            <textarea class="form-control" name="requirements" rows="3" placeholder="Any special needs or requirements..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" name="register_event" class="btn btn-primary">
                            <i class="fas fa-check me-2"></i>Register Now
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Enhanced Footer -->
    <?php require_once 'footer_enhanced.php'; ?>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="assets/js/heavenly_sounds.js"></script>
    <script src="assets/js/perfect_animations.js"></script>
    <script src="assets/js/spiritual_enhancement.js"></script>
    <script>
        // Initialize AOS
        AOS.init({
            duration: 1000,
            once: true,
            offset: 100
        });

        // Handle event registration modal
        document.getElementById('registrationModal').addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const eventId = button.getAttribute('data-event-id');
            document.getElementById('event_id').value = eventId;
        });

        // Calendar navigation
        let currentMonth = new Date().getMonth();
        let currentYear = new Date().getFullYear();

        function updateCalendar() {
            // Calendar update logic here
            console.log('Updating calendar for', currentMonth, currentYear);
        }

        // Add heavenly interactions
        if (window.heavenlyGuidance) {
            setTimeout(() => {
                window.heavenlyGuidance.showWelcomeMessage();
            }, 3000);
        }

        // Performance monitoring
        window.addEventListener('load', function() {
            const loadTime = performance.timing.loadEventEnd - performance.timing.navigationStart;
            console.log('Events page load time:', loadTime + 'ms');
        });
    </script>
</body>
</html>
