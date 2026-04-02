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
require_once 'config.php';
require_once 'db.php';

// Get events data with error handling
try {
    $events = $db->query("SELECT e.*, u.first_name, u.last_name FROM events e LEFT JOIN users u ON e.created_by = u.id WHERE e.status != 'deleted' ORDER BY e.event_date ASC");
    $upcoming_events = $db->query("SELECT e.*, u.first_name, u.last_name FROM events e LEFT JOIN users u ON e.created_by = u.id WHERE e.status = 'upcoming' AND e.event_date >= CURDATE() ORDER BY e.event_date ASC LIMIT 6");
    $past_events = $db->query("SELECT e.*, u.first_name, u.last_name FROM events e LEFT JOIN users u ON e.created_by = u.id WHERE e.event_date < CURDATE() AND e.status = 'completed' ORDER BY e.event_date DESC LIMIT 3");
} catch (Exception $e) {
    $events = [];
    $upcoming_events = [];
    $past_events = [];
}

// Clean any buffered output
ob_end_clean();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Events at Salem Dominion Ministries - Join our upcoming events and services with Apostle Faty Musasizi">
    <title>Events - Salem Dominion Ministries</title>
    
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

        /* Hero Section - Mindblowing */
        .hero {
            background: var(--gradient-ocean);
            min-height: 60vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .hero::before {
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

        .hero-particles {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
        }

        .particle {
            position: absolute;
            width: 4px;
            height: 4px;
            background: var(--heavenly-gold);
            border-radius: 50%;
            opacity: 0.6;
            animation: float 20s infinite linear;
        }

        @keyframes float {
            0% {
                transform: translateY(100vh) translateX(0);
                opacity: 0;
            }
            10% {
                opacity: 0.6;
            }
            90% {
                opacity: 0.6;
            }
            100% {
                transform: translateY(-100vh) translateX(100px);
                opacity: 0;
            }
        }

        .hero-content {
            position: relative;
            z-index: 10;
            text-align: center;
            color: var(--snow-white);
            max-width: 800px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .hero-logo {
            margin-bottom: 2rem;
            animation: logoFloat 8s ease-in-out infinite;
        }

        @keyframes logoFloat {
            0%, 100% { transform: translateY(0) scale(1); }
            50% { transform: translateY(-15px) scale(1.05); }
        }

        .hero-logo img {
            height: 120px;
            width: auto;
            border-radius: 50%;
            background: var(--snow-white);
            padding: 15px;
            box-shadow: 0 0 50px rgba(251, 191, 36, 0.4);
            transition: all 0.5s ease;
        }

        .hero-logo:hover img {
            transform: scale(1.1) rotate(10deg);
            box-shadow: 0 0 70px rgba(251, 191, 36, 0.6);
        }

        .hero-title {
            font-family: 'Playfair Display', serif;
            font-size: clamp(2.5rem, 6vw, 4rem);
            font-weight: 900;
            margin-bottom: 1.5rem;
            text-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
            letter-spacing: -0.02em;
            animation: titleGlow 4s ease-in-out infinite alternate;
        }

        @keyframes titleGlow {
            0% { text-shadow: 0 4px 20px rgba(0, 0, 0, 0.3); }
            100% { text-shadow: 0 4px 30px rgba(251, 191, 36, 0.4); }
        }

        .hero-subtitle {
            font-family: 'Great Vibes', cursive;
            font-size: clamp(1.8rem, 4vw, 2.5rem);
            font-weight: 400;
            margin-bottom: 2rem;
            opacity: 0.95;
            letter-spacing: 0.05em;
            animation: subtitleFloat 6s ease-in-out infinite;
        }

        @keyframes subtitleFloat {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }

        /* Sections - Iconic Design */
        .section {
            padding: 100px 0;
            position: relative;
        }

        .section-light {
            background: var(--snow-white);
        }

        .section-heaven {
            background: var(--gradient-heaven);
        }

        .section-title {
            font-family: 'Playfair Display', serif;
            font-size: clamp(2.5rem, 6vw, 3.5rem);
            font-weight: 900;
            text-align: center;
            margin-bottom: 1rem;
            position: relative;
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: -15px;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 4px;
            background: var(--gradient-divine);
            border-radius: 2px;
        }

        .section-subtitle {
            text-align: center;
            font-size: 1.2rem;
            margin-bottom: 4rem;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
            font-weight: 300;
        }

        /* Events Grid - Iconic */
        .events-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
            gap: 3rem;
            margin-top: 4rem;
        }

        .event-card {
            background: var(--snow-white);
            border-radius: 30px;
            overflow: hidden;
            box-shadow: var(--shadow-soft);
            border: 1px solid rgba(125, 211, 252, 0.2);
            transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
        }

        .event-card:hover {
            transform: translateY(-15px);
            box-shadow: var(--shadow-divine);
        }

        .event-header {
            height: 250px;
            background: var(--gradient-ocean);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--snow-white);
            font-size: 5rem;
            position: relative;
            overflow: hidden;
        }

        .event-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(45deg, transparent 30%, rgba(255, 255, 255, 0.1) 50%, transparent 70%);
            transform: translateX(-100%);
            transition: transform 0.6s ease;
        }

        .event-card:hover .event-header::before {
            transform: translateX(100%);
        }

        .event-date-badge {
            position: absolute;
            top: 20px;
            right: 20px;
            background: var(--gradient-divine);
            color: var(--midnight-blue);
            padding: 15px 20px;
            border-radius: 20px;
            text-align: center;
            font-weight: 700;
            box-shadow: var(--shadow-heavenly);
            z-index: 10;
        }

        .event-day {
            font-size: 2rem;
            line-height: 1;
            margin-bottom: 5px;
        }

        .event-month {
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .event-content {
            padding: 3rem;
        }

        .event-title {
            font-size: 2rem;
            font-weight: 700;
            color: var(--midnight-blue);
            margin-bottom: 1.5rem;
            font-family: 'Playfair Display', serif;
        }

        .event-description {
            font-size: 1.1rem;
            line-height: 1.8;
            color: var(--ocean-blue);
            margin-bottom: 2rem;
        }

        .event-details {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .event-detail-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem;
            background: var(--pearl-white);
            border-radius: 15px;
            transition: all 0.3s ease;
        }

        .event-detail-item:hover {
            background: var(--ice-blue);
            transform: translateX(5px);
        }

        .event-icon {
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

        .event-text {
            flex: 1;
            font-size: 1rem;
            color: var(--midnight-blue);
            font-weight: 500;
        }

        .event-actions {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn-event {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 15px 30px;
            background: var(--gradient-ocean);
            color: var(--snow-white);
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            border: none;
            font-size: 1rem;
            position: relative;
            overflow: hidden;
        }

        .btn-event::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.2);
            transition: all 0.4s ease;
        }

        .btn-event:hover::before {
            left: 100%;
        }

        .btn-event:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(14, 165, 233, 0.3);
            color: var(--snow-white);
        }

        .btn-outline {
            background: transparent;
            color: var(--ocean-blue);
            border: 2px solid var(--ocean-blue);
        }

        .btn-outline:hover {
            background: var(--ocean-blue);
            color: var(--snow-white);
        }

        /* Past Events Section */
        .past-events {
            background: var(--gradient-heaven);
            padding: 80px 0;
        }

        .past-event-card {
            background: var(--snow-white);
            border-radius: 20px;
            padding: 2rem;
            box-shadow: var(--shadow-soft);
            border: 1px solid rgba(125, 211, 252, 0.2);
            transition: all 0.3s ease;
            margin-bottom: 2rem;
        }

        .past-event-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-divine);
        }

        .past-event-header {
            display: flex;
            align-items: center;
            gap: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .past-event-icon {
            width: 60px;
            height: 60px;
            background: var(--gradient-divine);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--midnight-blue);
            font-size: 1.5rem;
        }

        .past-event-info h4 {
            font-size: 1.3rem;
            color: var(--midnight-blue);
            margin-bottom: 0.5rem;
        }

        .past-event-info p {
            color: var(--heavenly-gold);
            font-weight: 600;
            margin-bottom: 0;
        }

        /* CTA Section - Iconic */
        .cta-section {
            background: var(--gradient-ocean);
            padding: 100px 0;
            text-align: center;
            color: var(--snow-white);
            position: relative;
            overflow: hidden;
        }

        .cta-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 300%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
            animation: divineShimmer 15s infinite;
        }

        .cta-content {
            position: relative;
            z-index: 10;
        }

        .cta-title {
            font-size: clamp(2rem, 5vw, 3rem);
            font-weight: 900;
            margin-bottom: 2rem;
            text-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
        }

        .cta-subtitle {
            font-size: 1.3rem;
            margin-bottom: 3rem;
            opacity: 0.95;
        }

        .cta-buttons {
            display: flex;
            gap: 2rem;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn-cta {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 18px 40px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 1.1rem;
            text-decoration: none;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .btn-cta::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.2);
            transition: all 0.4s ease;
        }

        .btn-cta:hover::before {
            left: 100%;
        }

        .btn-primary {
            background: var(--snow-white);
            color: var(--midnight-blue);
            box-shadow: 0 10px 30px rgba(255, 255, 255, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 40px rgba(255, 255, 255, 0.4);
            color: var(--midnight-blue);
        }

        .btn-outline {
            background: transparent;
            color: var(--snow-white);
            border: 2px solid var(--snow-white);
            backdrop-filter: blur(10px);
        }

        .btn-outline:hover {
            background: var(--snow-white);
            color: var(--midnight-blue);
            transform: translateY(-3px);
            box-shadow: 0 15px 40px rgba(255, 255, 255, 0.3);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .hero {
                min-height: 50vh;
                padding: 2rem 0;
            }

            .hero-title {
                font-size: 2.5rem;
            }

            .hero-subtitle {
                font-size: 2rem;
            }

            .section {
                padding: 60px 0;
            }

            .events-grid {
                grid-template-columns: 1fr;
                gap: 2rem;
            }

            .event-content {
                padding: 2rem;
            }

            .cta-buttons {
                flex-direction: column;
                align-items: center;
            }

            .btn-cta {
                width: 250px;
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
                    <li class="nav-item"><a class="nav-link active" href="events.php">Events</a></li>
                    <li class="nav-item"><a class="nav-link" href="sermons.php">Sermons</a></li>
                    <li class="nav-item"><a class="nav-link" href="news.php">News</a></li>
                    <li class="nav-item"><a class="nav-link" href="gallery.php">Gallery</a></li>
                    <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
                    <li class="nav-item">
                        <a class="nav-link" href="login.php">
                            <i class="fas fa-sign-in-alt me-2"></i>Login
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero">
        <!-- Divine Particles -->
        <div class="hero-particles" id="heroParticles"></div>
        
        <div class="hero-content" data-aos="fade-up" data-aos-duration="1500">
            <div class="hero-logo">
                <img src="assets/logo-DEFqnQ4s.jpeg" alt="Salem Dominion Ministries">
            </div>
            <h1 class="hero-title">Church Events</h1>
            <p class="hero-subtitle">Join Us for Divine Fellowship</p>
        </div>
    </section>

    <!-- Upcoming Events Section -->
    <section class="section section-light">
        <div class="container">
            <h2 class="section-title" data-aos="fade-up">Upcoming Events</h2>
            <p class="section-subtitle" data-aos="fade-up" data-aos-delay="100">Don't miss these special gatherings</p>
            
            <div class="events-grid">
                <?php if ($upcoming_events && $upcoming_events->num_rows > 0): ?>
                    <?php while ($event = $upcoming_events->fetch_assoc()): ?>
                        <div class="event-card" data-aos="fade-up" data-aos-delay="200">
                            <div class="event-header">
                                <img src="assets/hero-community-CDAgPtPb.jpg" alt="Event" style="width: 100%; height: 100%; object-fit: cover;">
                                <div class="event-date-badge">
                                    <div class="event-day"><?php echo date('d', strtotime($event['event_date'])); ?></div>
                                    <div class="event-month"><?php echo date('M', strtotime($event['event_date'])); ?></div>
                                </div>
                            </div>
                            <div class="event-content">
                                <h3 class="event-title"><?php echo htmlspecialchars($event['title']); ?></h3>
                                <p class="event-description">
                                    <?php echo htmlspecialchars(substr($event['description'], 0, 200)); ?>...
                                </p>
                                <div class="event-details">
                                    <div class="event-detail-item">
                                        <div class="event-icon">
                                            <i class="fas fa-calendar"></i>
                                        </div>
                                        <div class="event-text">
                                            <?php echo date('l, F j, Y', strtotime($event['event_date'])); ?>
                                        </div>
                                    </div>
                                    <div class="event-detail-item">
                                        <div class="event-icon">
                                            <i class="fas fa-clock"></i>
                                        </div>
                                        <div class="event-text">
                                            <?php echo date('g:i A', strtotime($event['event_time'])); ?>
                                        </div>
                                    </div>
                                    <div class="event-detail-item">
                                        <div class="event-icon">
                                            <i class="fas fa-map-marker-alt"></i>
                                        </div>
                                        <div class="event-text">
                                            <?php echo htmlspecialchars($event['location']); ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="event-actions">
                                    <a href="event_register.php?id=<?php echo $event['id']; ?>" class="btn-event">
                                        <i class="fas fa-user-plus"></i> Register
                                    </a>
                                    <a href="#" class="btn-event btn-outline">
                                        <i class="fas fa-info-circle"></i> Details
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <!-- Sample Events -->
                    <div class="event-card" data-aos="fade-up" data-aos-delay="200">
                        <div class="event-header">
                            <img src="assets/hero-community-CDAgPtPb.jpg" alt="Community Event" style="width: 100%; height: 100%; object-fit: cover;">
                            <div class="event-date-badge">
                                <div class="event-day">15</div>
                                <div class="event-month">DEC</div>
                            </div>
                        </div>
                        <div class="event-content">
                            <h3 class="event-title">Christmas Celebration</h3>
                            <p class="event-description">
                                Join us for a special Christmas celebration with worship, fellowship, and special performances by our children and youth ministries.
                            </p>
                            <div class="event-details">
                                <div class="event-detail-item">
                                    <div class="event-icon">
                                        <i class="fas fa-calendar"></i>
                                    </div>
                                    <div class="event-text">
                                        Sunday, December 15, 2026
                                    </div>
                                </div>
                                <div class="event-detail-item">
                                    <div class="event-icon">
                                        <i class="fas fa-clock"></i>
                                    </div>
                                    <div class="event-text">
                                        10:00 AM - 2:00 PM
                                    </div>
                                </div>
                                <div class="event-detail-item">
                                    <div class="event-icon">
                                        <i class="fas fa-map-marker-alt"></i>
                                    </div>
                                    <div class="event-text">
                                        Main Church Hall
                                    </div>
                                </div>
                            </div>
                            <div class="event-actions">
                                <a href="contact.php" class="btn-event">
                                    <i class="fas fa-user-plus"></i> Register
                                </a>
                                <a href="contact.php" class="btn-event btn-outline">
                                    <i class="fas fa-info-circle"></i> Details
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="event-card" data-aos="fade-up" data-aos-delay="300">
                        <div class="event-header">
                            <img src="assets/hero-worship-CWyaH0tr.jpg" alt="Worship Event" style="width: 100%; height: 100%; object-fit: cover;">
                            <div class="event-date-badge">
                                <div class="event-day">22</div>
                                <div class="event-month">DEC</div>
                            </div>
                        </div>
                        <div class="event-content">
                            <h3 class="event-title">New Year's Eve Service</h3>
                            <p class="event-description">
                                Welcome the new year with an evening of powerful worship, prayer, and prophetic ministry led by Apostle Faty Musasizi.
                            </p>
                            <div class="event-details">
                                <div class="event-detail-item">
                                    <div class="event-icon">
                                        <i class="fas fa-calendar"></i>
                                    </div>
                                    <div class="event-text">
                                        Tuesday, December 22, 2026
                                    </div>
                                </div>
                                <div class="event-detail-item">
                                    <div class="event-icon">
                                        <i class="fas fa-clock"></i>
                                    </div>
                                    <div class="event-text">
                                        8:00 PM - 1:00 AM
                                    </div>
                                </div>
                                <div class="event-detail-item">
                                    <div class="event-icon">
                                        <i class="fas fa-map-marker-alt"></i>
                                    </div>
                                    <div class="event-text">
                                        Main Church Hall
                                    </div>
                                </div>
                            </div>
                            <div class="event-actions">
                                <a href="contact.php" class="btn-event">
                                    <i class="fas fa-user-plus"></i> Register
                                </a>
                                <a href="contact.php" class="btn-event btn-outline">
                                    <i class="fas fa-info-circle"></i> Details
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="event-card" data-aos="fade-up" data-aos-delay="400">
                        <div class="event-header">
                            <img src="assets/hero-choir-6lo-hX_h.jpg" alt="Choir Event" style="width: 100%; height: 100%; object-fit: cover;">
                            <div class="event-date-badge">
                                <div class="event-day">05</div>
                                <div class="event-month">JAN</div>
                            </div>
                        </div>
                        <div class="event-content">
                            <h3 class="event-title">Youth Conference 2027</h3>
                            <p class="event-description">
                                Annual youth conference featuring guest speakers, workshops, worship sessions, and fellowship for young people aged 13-25.
                            </p>
                            <div class="event-details">
                                <div class="event-detail-item">
                                    <div class="event-icon">
                                        <i class="fas fa-calendar"></i>
                                    </div>
                                    <div class="event-text">
                                        Friday, January 5, 2027
                                    </div>
                                </div>
                                <div class="event-detail-item">
                                    <div class="event-icon">
                                        <i class="fas fa-clock"></i>
                                    </div>
                                    <div class="event-text">
                                        9:00 AM - 6:00 PM
                                    </div>
                                </div>
                                <div class="event-detail-item">
                                    <div class="event-icon">
                                        <i class="fas fa-map-marker-alt"></i>
                                    </div>
                                    <div class="event-text">
                                        Church Grounds
                                    </div>
                                </div>
                            </div>
                            <div class="event-actions">
                                <a href="contact.php" class="btn-event">
                                    <i class="fas fa-user-plus"></i> Register
                                </a>
                                <a href="contact.php" class="btn-event btn-outline">
                                    <i class="fas fa-info-circle"></i> Details
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Past Events Section -->
    <section class="past-events">
        <div class="container">
            <h2 class="section-title" data-aos="fade-up">Recent Events</h2>
            <p class="section-subtitle" data-aos="fade-up" data-aos-delay="100">Memorable moments from our past gatherings</p>
            
            <div class="row">
                <?php if ($past_events && $past_events->num_rows > 0): ?>
                    <?php while ($event = $past_events->fetch_assoc()): ?>
                        <div class="col-lg-6" data-aos="fade-up" data-aos-delay="200">
                            <div class="past-event-card">
                                <div class="past-event-header">
                                    <div class="past-event-icon">
                                        <i class="fas fa-check-circle"></i>
                                    </div>
                                    <div class="past-event-info">
                                        <h4><?php echo htmlspecialchars($event['title']); ?></h4>
                                        <p><?php echo date('F j, Y', strtotime($event['event_date'])); ?></p>
                                    </div>
                                </div>
                                <p class="event-description">
                                    <?php echo htmlspecialchars(substr($event['description'], 0, 150)); ?>...
                                </p>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="col-lg-6" data-aos="fade-up" data-aos-delay="200">
                        <div class="past-event-card">
                            <div class="past-event-header">
                                <div class="past-event-icon">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                                <div class="past-event-info">
                                    <h4>Thanksgiving Service</h4>
                                    <p>November 28, 2026</p>
                                </div>
                            </div>
                            <p class="event-description">
                                A beautiful thanksgiving service where we celebrated God's faithfulness with testimonies, worship, and special prayers.
                            </p>
                        </div>
                    </div>
                    
                    <div class="col-lg-6" data-aos="fade-up" data-aos-delay="300">
                        <div class="past-event-card">
                            <div class="past-event-header">
                                <div class="past-event-icon">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                                <div class="past-event-info">
                                    <h4>Family Fun Day</h4>
                                    <p>October 15, 2026</p>
                                </div>
                            </div>
                            <p class="event-description">
                                A wonderful family day with games, food, and fellowship that brought together families from across our community.
                            </p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <!-- Divine Particles -->
        <div class="hero-particles" id="ctaParticles"></div>
        
        <div class="cta-content">
            <h2 class="cta-title" data-aos="fade-up">Join Our Next Event</h2>
            <p class="cta-subtitle" data-aos="fade-up" data-aos-delay="100">Be part of our growing community</p>
            
            <div class="cta-buttons" data-aos="fade-up" data-aos-delay="200">
                <a href="contact.php" class="btn-cta btn-primary">
                    <i class="fas fa-calendar"></i> Register for Events
                </a>
                <a href="newsletter_subscribe.php" class="btn-cta btn-outline">
                    <i class="fas fa-envelope"></i> Get Event Updates
                </a>
            </div>
        </div>
    </section>

    <!-- Ultimate Footer -->
    <?php require_once 'components/ultimate_footer.php'; ?>

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

        // Create divine particles
        function createParticles() {
            const particlesContainer = document.getElementById('heroParticles');
            const ctaParticles = document.getElementById('ctaParticles');
            const particleCount = 15;
            
            if (particlesContainer) {
                for (let i = 0; i < particleCount; i++) {
                    const particle = document.createElement('div');
                    particle.className = 'particle';
                    particle.style.left = Math.random() * 100 + '%';
                    particle.style.animationDelay = Math.random() * 20 + 's';
                    particle.style.animationDuration = (15 + Math.random() * 10) + 's';
                    particlesContainer.appendChild(particle);
                }
            }
            
            if (ctaParticles) {
                for (let i = 0; i < particleCount; i++) {
                    const particle = document.createElement('div');
                    particle.className = 'particle';
                    particle.style.left = Math.random() * 100 + '%';
                    particle.style.animationDelay = Math.random() * 20 + 's';
                    particle.style.animationDuration = (15 + Math.random() * 10) + 's';
                    ctaParticles.appendChild(particle);
                }
            }
        }

        // Navbar scroll effect
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar');
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });

        // Initialize particles
        createParticles();

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

        // Add parallax effect to hero section
        window.addEventListener('scroll', function() {
            const scrolled = window.pageYOffset;
            const heroContent = document.querySelector('.hero-content');
            if (heroContent) {
                heroContent.style.transform = `translateY(${scrolled * 0.5}px)`;
                heroContent.style.opacity = 1 - (scrolled / 600);
            }
        });
    </script>
</body>
</html>
