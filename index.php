<?php
session_start();
require_once 'db.php';

// Get dynamic data
$services = $db->query("SELECT * FROM service_times WHERE is_active = 1 ORDER BY FIELD(day_of_week, 'sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'), start_time");
$ministries = $db->query("SELECT * FROM ministries WHERE is_active = 1 LIMIT 3");
$news = $db->query("SELECT * FROM news WHERE status = 'published' ORDER BY published_at DESC LIMIT 3");
$events = $db->query("SELECT * FROM events WHERE status = 'upcoming' ORDER BY event_date ASC LIMIT 3");
$sermons = $db->query("SELECT * FROM sermons WHERE status = 'published' ORDER BY sermon_date DESC LIMIT 3");
$gallery = $db->query("SELECT * FROM gallery WHERE status = 'published' AND is_featured = 1 LIMIT 6");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Salem Dominion Ministries - Welcome Home</title>
    
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
            --gradient-primary: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --gradient-secondary: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            --gradient-church: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
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
            overflow-x: hidden;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            position: relative;
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

        /* Floating Angels */
        .floating-angel {
            position: fixed;
            font-size: 2rem;
            color: var(--heavenly-gold);
            opacity: 0.3;
            pointer-events: none;
            z-index: 2;
            animation: float 6s ease-in-out infinite;
        }

        .angel-1 {
            top: 20%;
            left: 5%;
            animation-delay: 0s;
        }

        .angel-2 {
            top: 60%;
            right: 5%;
            animation-delay: 2s;
        }

        .angel-3 {
            top: 40%;
            left: 10%;
            animation-delay: 4s;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-30px) rotate(10deg); }
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

        .navbar-nav .nav-link {
            color: var(--primary-color) !important;
            font-weight: 500;
            margin: 0 0.5rem;
            transition: all 0.3s ease;
            position: relative;
        }

        .navbar-nav .nav-link:hover {
            color: var(--heavenly-gold) !important;
            transform: translateY(-2px);
        }

        .navbar-nav .nav-link::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            width: 0;
            height: 2px;
            background: var(--gradient-heavenly);
            transition: all 0.3s ease;
            transform: translateX(-50%);
        }

        .navbar-nav .nav-link:hover::after {
            width: 100%;
        }

        /* Hero Section */
        .hero-section {
            height: 100vh;
            background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)),
                        url('https://images.unsplash.com/photo-1506905925346-21bda4d32df4?ixlib=rb-4.0.3') center/cover;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: white;
            position: relative;
            overflow: hidden;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: var(--gradient-divine);
            opacity: 0.4;
            animation: gradientShift 10s ease infinite;
        }

        @keyframes gradientShift {
            0%, 100% { opacity: 0.4; }
            50% { opacity: 0.6; }
        }

        .hero-angel {
            position: absolute;
            font-size: 4rem;
            color: rgba(255, 255, 255, 0.3);
            animation: pulse 3s ease-in-out infinite;
        }

        .angel-left {
            top: 20%;
            left: 10%;
        }

        .angel-right {
            top: 20%;
            right: 10%;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); opacity: 0.3; }
            50% { transform: scale(1.1); opacity: 0.5; }
        }

        .hero-content {
            position: relative;
            z-index: 2;
            max-width: 800px;
            padding: 0 20px;
        }

        .hero-title {
            font-size: 4rem;
            font-weight: 900;
            margin-bottom: 1.5rem;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
            animation: fadeInUp 1s ease;
            background: linear-gradient(45deg, #FFD700, #FFFFFF);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .hero-subtitle {
            font-size: 1.5rem;
            margin-bottom: 2rem;
            font-weight: 300;
            animation: fadeInUp 1s ease 0.2s both;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5);
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .btn-hero {
            padding: 15px 40px;
            font-size: 1.1rem;
            font-weight: 600;
            border: none;
            border-radius: 50px;
            margin: 0.5rem;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
            position: relative;
            overflow: hidden;
        }

        .btn-primary-hero {
            background: var(--gradient-heavenly);
            color: white;
            box-shadow: 0 5px 15px rgba(255, 215, 0, 0.3);
        }

        .btn-outline-hero {
            background: transparent;
            color: white;
            border: 2px solid var(--heavenly-gold);
        }

        .btn-hero:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(255, 215, 0, 0.4);
        }

        /* Section Styles */
        .section {
            padding: 100px 0;
            position: relative;
            z-index: 2;
        }

        .section-alt {
            background: var(--gradient-angel);
        }

        .section-title {
            font-size: 3rem;
            font-weight: 900;
            text-align: center;
            margin-bottom: 1rem;
            background: var(--gradient-divine);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            position: relative;
        }

        .section-title::before {
            content: '✨';
            position: absolute;
            top: -20px;
            right: 20px;
            font-size: 1.5rem;
            color: var(--heavenly-gold);
            opacity: 0.6;
        }

        .section-subtitle {
            text-align: center;
            font-size: 1.2rem;
            color: var(--dark-color);
            margin-bottom: 3rem;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        /* Card Styles */
        .card-custom {
            border: none;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            height: 100%;
            background: white;
            position: relative;
        }

        .card-custom::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: var(--gradient-heavenly);
        }

        .card-custom:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(255, 215, 0, 0.2);
        }

        .card-custom .card-img-top {
            height: 200px;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .card-custom:hover .card-img-top {
            transform: scale(1.1);
        }

        .card-custom .card-body {
            padding: 2rem;
        }

        .card-custom .card-title {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: var(--primary-color);
        }

        /* Service Times */
        .service-card {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            text-align: center;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            border: 2px solid transparent;
            background-clip: padding-box;
            position: relative;
            overflow: hidden;
        }

        .service-card::before {
            content: '🕊️';
            position: absolute;
            top: 10px;
            right: 10px;
            font-size: 1.5rem;
            opacity: 0.3;
        }

        .service-card:hover {
            border-color: var(--heavenly-gold);
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(255, 215, 0, 0.2);
        }

        .service-icon {
            font-size: 3rem;
            color: var(--heavenly-gold);
            margin-bottom: 1rem;
            animation: pulse 2s infinite;
        }

        /* Ministries */
        .ministry-card {
            position: relative;
            overflow: hidden;
            border-radius: 20px;
            background: white;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .ministry-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: var(--gradient-heavenly);
        }

        .ministry-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(255, 215, 0, 0.2);
        }

        /* Events */
        .event-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .event-date {
            background: var(--gradient-heavenly);
            color: white;
            padding: 1rem;
            text-align: center;
            font-weight: 700;
        }

        .event-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(255, 215, 0, 0.2);
        }

        /* Gallery */
        .gallery-item {
            position: relative;
            overflow: hidden;
            border-radius: 15px;
            height: 250px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .gallery-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .gallery-item:hover img {
            transform: scale(1.1);
        }

        .gallery-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(to bottom, transparent, rgba(255, 215, 0, 0.7));
            display: flex;
            align-items: flex-end;
            padding: 1rem;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .gallery-item:hover .gallery-overlay {
            opacity: 1;
        }

        /* Sermons */
        .sermon-card {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .sermon-card::before {
            content: '📖';
            position: absolute;
            top: 10px;
            right: 10px;
            font-size: 1.5rem;
            opacity: 0.3;
        }

        .sermon-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(255, 215, 0, 0.2);
        }

        .sermon-verse {
            font-style: italic;
            color: var(--heavenly-gold);
            margin-bottom: 1rem;
        }

        /* Pastor Booking CTA */
        .booking-cta {
            background: var(--gradient-divine);
            color: white;
            padding: 4rem 0;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .booking-cta::before {
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

        /* Footer */
        footer {
            background: var(--primary-color);
            color: white;
            padding: 3rem 0 1rem;
            position: relative;
            z-index: 2;
        }

        .footer-widget h5 {
            color: var(--heavenly-gold);
            margin-bottom: 1.5rem;
            font-weight: 700;
        }

        .footer-widget ul {
            list-style: none;
            padding: 0;
        }

        .footer-widget ul li {
            margin-bottom: 0.5rem;
        }

        .footer-widget ul li a {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .footer-widget ul li a:hover {
            color: var(--heavenly-gold);
        }

        .social-links {
            display: flex;
            gap: 1rem;
            margin-top: 1rem;
        }

        .social-links a {
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            transition: all 0.3s ease;
        }

        .social-links a:hover {
            background: var(--heavenly-gold);
            transform: translateY(-3px);
        }

        /* Mobile App Banner */
        .app-banner {
            background: var(--gradient-heavenly);
            color: white;
            padding: 2rem 0;
            text-align: center;
            position: relative;
            z-index: 2;
        }

        .app-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
            margin-top: 1rem;
            flex-wrap: wrap;
        }

        .app-button {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.8rem 1.5rem;
            background: rgba(255, 255, 255, 0.2);
            border: 2px solid white;
            border-radius: 10px;
            color: white;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .app-button:hover {
            background: white;
            color: var(--primary-color);
            transform: translateY(-2px);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .hero-title {
                font-size: 2.5rem;
            }
            
            .hero-subtitle {
                font-size: 1.2rem;
            }
            
            .section-title {
                font-size: 2rem;
            }
            
            .section {
                padding: 60px 0;
            }
            
            .floating-angel {
                display: none;
            }
        }

        /* Loading Animation */
        .loader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: white;
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            transition: opacity 0.5s ease;
        }

        .loader.hidden {
            opacity: 0;
            pointer-events: none;
        }

        .loader-content {
            text-align: center;
        }

        .loader-icon {
            font-size: 3rem;
            color: var(--heavenly-gold);
            animation: spin 2s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <!-- Floating Angels -->
    <div class="floating-angel angel-1">🕊️</div>
    <div class="floating-angel angel-2">✨</div>
    <div class="floating-angel angel-3">👼</div>

    <!-- Loader -->
    <div class="loader" id="loader">
        <div class="loader-content">
            <i class="fas fa-dove loader-icon"></i>
            <p class="mt-3">Loading God's blessings...</p>
        </div>
    </div>

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

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="hero-angel angel-left">👼</div>
        <div class="hero-angel angel-right">🕊️</div>
        <div class="hero-content">
            <h1 class="hero-title">Welcome to Salem Dominion Ministries</h1>
            <p class="hero-subtitle">Experience God's love, grow in faith, and serve our community together</p>
            <div class="hero-buttons">
                <a href="about.php" class="btn btn-hero btn-primary-hero">
                    <i class="fas fa-info-circle me-2"></i> Learn More
                </a>
                <a href="contact.php" class="btn btn-hero btn-outline-hero">
                    <i class="fas fa-envelope me-2"></i> Contact Us
                </a>
            </div>
        </div>
    </section>

    <!-- Service Times -->
    <section class="section section-alt">
        <div class="container">
            <h2 class="section-title" data-aos="fade-up">Service Times</h2>
            <p class="section-subtitle" data-aos="fade-up" data-aos-delay="100">Join us for worship and fellowship</p>
            
            <div class="row">
                <?php while ($service = $services->fetch_assoc()): ?>
                <div class="col-md-6 col-lg-4 mb-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="service-card">
                        <div class="service-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <h5 class="card-title"><?php echo ucfirst($service['day_of_week']); ?></h5>
                        <h6 class="card-subtitle mb-2 text-muted"><?php echo htmlspecialchars($service['service_name']); ?></h6>
                        <p class="card-text">
                            <i class="fas fa-clock text-primary"></i> 
                            <?php echo date('g:i A', strtotime($service['start_time'])); ?> - 
                            <?php echo date('g:i A', strtotime($service['end_time'])); ?>
                        </p>
                        <p class="card-text">
                            <i class="fas fa-map-marker-alt text-primary"></i> 
                            <?php echo htmlspecialchars($service['location']); ?>
                        </p>
                        <?php if ($service['description']): ?>
                        <p class="card-text small text-muted"><?php echo htmlspecialchars($service['description']); ?></p>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
        </div>
    </section>

    <!-- Ministries Preview -->
    <section class="section">
        <div class="container">
            <h2 class="section-title" data-aos="fade-up">Our Ministries</h2>
            <p class="section-subtitle" data-aos="fade-up" data-aos-delay="100">Discover ways to get involved and grow in your faith</p>
            
            <div class="row">
                <?php while ($ministry = $ministries->fetch_assoc()): ?>
                <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="ministry-card card-custom">
                        <div class="card-body">
                            <h5 class="card-title">
                                <i class="fas fa-users text-primary me-2"></i>
                                <?php echo htmlspecialchars($ministry['name']); ?>
                            </h5>
                            <p class="card-text"><?php echo htmlspecialchars(substr($ministry['description'], 0, 150)) . '...'; ?></p>
                            <?php if ($ministry['meeting_day'] && $ministry['meeting_time']): ?>
                            <p class="card-text small text-muted">
                                <i class="fas fa-calendar"></i> <?php echo htmlspecialchars($ministry['meeting_day']); ?> 
                                <?php echo htmlspecialchars($ministry['meeting_time']); ?>
                            </p>
                            <?php endif; ?>
                            <a href="ministries.php" class="btn btn-primary btn-sm">Learn More</a>
                        </div>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
            
            <div class="text-center mt-4" data-aos="fade-up" data-aos-delay="300">
                <a href="ministries.php" class="btn btn-primary btn-lg">View All Ministries</a>
            </div>
        </div>
    </section>

    <!-- Upcoming Events -->
    <section class="section section-alt">
        <div class="container">
            <h2 class="section-title" data-aos="fade-up">Upcoming Events</h2>
            <p class="section-subtitle" data-aos="fade-up" data-aos-delay="100">Join us for these exciting events</p>
            
            <div class="row">
                <?php while ($event = $events->fetch_assoc()): ?>
                <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="event-card">
                        <div class="event-date">
                            <i class="fas fa-calendar-alt me-2"></i>
                            <?php echo date('F j, Y', strtotime($event['event_date'])); ?>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($event['title']); ?></h5>
                            <p class="card-text">
                                <i class="fas fa-clock text-primary"></i> 
                                <?php echo date('g:i A', strtotime($event['event_date'])); ?>
                            </p>
                            <?php if ($event['location']): ?>
                            <p class="card-text">
                                <i class="fas fa-map-marker-alt text-primary"></i> 
                                <?php echo htmlspecialchars($event['location']); ?>
                            </p>
                            <?php endif; ?>
                            <p class="card-text"><?php echo htmlspecialchars(substr($event['description'], 0, 100)) . '...'; ?></p>
                            <a href="events.php" class="btn btn-primary btn-sm">Learn More</a>
                        </div>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
        </div>
    </section>

    <!-- Latest Sermons -->
    <section class="section">
        <div class="container">
            <h2 class="section-title" data-aos="fade-up">Latest Sermons</h2>
            <p class="section-subtitle" data-aos="fade-up" data-aos-delay="100">Be inspired by God's Word</p>
            
            <div class="row">
                <?php while ($sermon = $sermons->fetch_assoc()): ?>
                <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="sermon-card">
                        <h5 class="card-title">
                            <i class="fas fa-bible text-primary me-2"></i>
                            <?php echo htmlspecialchars($sermon['title']); ?>
                        </h5>
                        <?php if ($sermon['bible_reference']): ?>
                        <p class="sermon-verse"><?php echo htmlspecialchars($sermon['bible_reference']); ?></p>
                        <?php endif; ?>
                        <p class="card-text">
                            <i class="fas fa-user text-primary"></i> 
                            <?php echo htmlspecialchars($sermon['preacher']); ?>
                        </p>
                        <p class="card-text">
                            <i class="fas fa-calendar text-primary"></i> 
                            <?php echo date('F j, Y', strtotime($sermon['sermon_date'])); ?>
                        </p>
                        <?php if ($sermon['description']): ?>
                        <p class="card-text"><?php echo htmlspecialchars(substr($sermon['description'], 0, 100)) . '...'; ?></p>
                        <?php endif; ?>
                        <div class="mt-3">
                            <?php if ($sermon['video_url']): ?>
                            <a href="<?php echo htmlspecialchars($sermon['video_url']); ?>" class="btn btn-primary btn-sm me-2" target="_blank">
                                <i class="fas fa-play"></i> Watch
                            </a>
                            <?php endif; ?>
                            <?php if ($sermon['audio_url']): ?>
                            <a href="<?php echo htmlspecialchars($sermon['audio_url']); ?>" class="btn btn-outline-primary btn-sm" target="_blank">
                                <i class="fas fa-headphones"></i> Listen
                            </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
        </div>
    </section>

    <!-- Gallery Preview -->
    <section class="section section-alt">
        <div class="container">
            <h2 class="section-title" data-aos="fade-up">Gallery</h2>
            <p class="section-subtitle" data-aos="fade-up" data-aos-delay="100">Moments from our church family</p>
            
            <div class="row">
                <?php while ($item = $gallery->fetch_assoc()): ?>
                <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="gallery-item">
                        <img src="<?php echo htmlspecialchars($item['file_url']); ?>" alt="<?php echo htmlspecialchars($item['title']); ?>">
                        <div class="gallery-overlay">
                            <div>
                                <h6><?php echo htmlspecialchars($item['title']); ?></h6>
                                <?php if ($item['description']): ?>
                                <p class="small"><?php echo htmlspecialchars(substr($item['description'], 0, 50)) . '...'; ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
            
            <div class="text-center mt-4" data-aos="fade-up" data-aos-delay="300">
                <a href="gallery.php" class="btn btn-primary btn-lg">View Full Gallery</a>
            </div>
        </div>
    </section>

    <!-- Pastor Booking CTA -->
    <section class="booking-cta">
        <div class="container">
            <h2 class="text-white mb-4" data-aos="fade-up">Book a Call with Our Pastor</h2>
            <p class="lead mb-4" data-aos="fade-up" data-aos-delay="100">
                Need spiritual guidance, counseling, or prayer? Schedule a one-on-one session with our pastor.
            </p>
            <div data-aos="fade-up" data-aos-delay="200">
                <a href="pastor_booking.php" class="btn btn-light btn-lg me-3">
                    <i class="fas fa-calendar-check me-2"></i> Book Appointment
                </a>
                <a href="contact.php" class="btn btn-outline-light btn-lg">
                    <i class="fas fa-phone me-2"></i> Contact Office
                </a>
            </div>
        </div>
    </section>

    <!-- Mobile App Banner -->
    <section class="app-banner">
        <div class="container">
            <h3 class="mb-3" data-aos="fade-up">Take Our Church With You</h3>
            <p class="mb-4" data-aos="fade-up" data-aos-delay="100">Download our mobile app for sermons, events, and more</p>
            <div class="app-buttons" data-aos="fade-up" data-aos-delay="200">
                <a href="#" class="app-button" onclick="installApp(); return false;">
                    <i class="fas fa-download"></i> Install App
                </a>
                <a href="#" class="app-button">
                    <i class="fab fa-apple"></i> App Store
                </a>
                <a href="#" class="app-button">
                    <i class="fab fa-google-play"></i> Google Play
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="footer-widget">
                        <h5><i class="fas fa-church me-2"></i> Salem Dominion Ministries</h5>
                        <p>Serving our community with faith, hope, and love. Experience God's presence and grow in your spiritual journey.</p>
                        <div class="social-links">
                            <a href="#"><i class="fab fa-facebook-f"></i></a>
                            <a href="#"><i class="fab fa-twitter"></i></a>
                            <a href="#"><i class="fab fa-instagram"></i></a>
                            <a href="#"><i class="fab fa-youtube"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="footer-widget">
                        <h5>Quick Links</h5>
                        <ul>
                            <li><a href="about.php">About Us</a></li>
                            <li><a href="ministries.php">Ministries</a></li>
                            <li><a href="events.php">Events</a></li>
                            <li><a href="sermons.php">Sermons</a></li>
                            <li><a href="gallery.php">Gallery</a></li>
                            <li><a href="contact.php">Contact</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="footer-widget">
                        <h5>Contact Info</h5>
                        <ul>
                            <li><i class="fas fa-envelope me-2"></i> visit@salemdominionministries.com</li>
                            <li><i class="fas fa-phone me-2"></i> +1 (555) 123-4567</li>
                            <li><i class="fas fa-map-marker-alt me-2"></i> 123 Church Street, City, State</li>
                            <li><i class="fas fa-clock me-2"></i> Office: Mon-Fri 9AM-5PM</li>
                        </ul>
                    </div>
                </div>
            </div>
            <hr class="my-4" style="border-color: rgba(255,255,255,0.1);">
            <div class="text-center">
                <p class="mb-0">&copy; 2026 Salem Dominion Ministries. All rights reserved. | 
                    <a href="#" class="text-white-50">Privacy Policy</a> | 
                    <a href="#" class="text-white-50">Terms of Service</a>
                </p>
            </div>
        </div>
    </footer>

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

        // Hide loader
        window.addEventListener('load', function() {
            setTimeout(function() {
                document.getElementById('loader').classList.add('hidden');
            }, 1500);
        });

        // Navbar scroll effect
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar');
            if (window.scrollY > 100) {
                navbar.style.padding = '0.5rem 0';
                navbar.style.boxShadow = '0 4px 30px rgba(0,0,0,0.1)';
            } else {
                navbar.style.padding = '1rem 0';
                navbar.style.boxShadow = '0 2px 20px rgba(0,0,0,0.1)';
            }
        });

        // Smooth scrolling for anchor links
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

        // Mobile app installation
        function installApp() {
            if ('serviceWorker' in navigator) {
                window.addEventListener('beforeinstallprompt', (e) => {
                    e.preventDefault();
                    e.prompt();
                    e.userChoice.then((choiceResult) => {
                        if (choiceResult.outcome === 'accepted') {
                            console.log('User accepted the install prompt');
                        }
                    });
                });
            } else {
                alert('App installation is not available on this device. Please visit our app store page.');
            }
        }

        // PWA Service Worker registration
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('/sw.js')
                .then(registration => console.log('SW registered'))
                .catch(error => console.log('SW registration failed'));
        }

        // Add heavenly interactions
        document.addEventListener('DOMContentLoaded', () => {
            // Welcome message after page load
            setTimeout(() => {
                if (window.heavenlyGuidance) {
                    window.heavenlyGuidance.showWelcomeMessage();
                }
            }, 4000);
            
            // Periodic angelic presence
            setInterval(() => {
                if (window.heavenlyGuidance && Math.random() > 0.95) {
                    window.heavenlyGuidance.showAngelGuidance();
                }
            }, 45000); // Every 45 seconds
            
            // Heavenly hover effects
            document.querySelectorAll('.btn-hero, .card-custom, .service-card').forEach(element => {
                element.addEventListener('mouseenter', () => {
                    if (window.heavenlyGuidance && Math.random() > 0.9) {
                        window.heavenlyGuidance.playGentleChime();
                    }
                });
            });
        });

        // Floating angel animation
        document.querySelectorAll('.floating-angel').forEach((angel, index) => {
            setInterval(() => {
                const randomX = Math.random() * 20 - 10;
                const randomY = Math.random() * 20 - 10;
                angel.style.transform = `translate(${randomX}px, ${randomY}px)`;
            }, 3000 + (index * 1000));
        });
    </script>
</body>
</html>
