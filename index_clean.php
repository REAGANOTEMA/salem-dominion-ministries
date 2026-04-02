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

// Include required files with error handling
try {
    require_once 'config.php';
    require_once 'db.php';
} catch (Exception $e) {
    // Silent error handling
}

// Get dynamic data with error handling
$services = null;
$ministries = null;
$news = null;
$events = null;
$sermons = null;
$gallery = null;

try {
    $services = $db->query("SELECT * FROM service_times WHERE is_active = 1 ORDER BY FIELD(day_of_week, 'sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'), start_time");
    $ministries = $db->query("SELECT * FROM ministries WHERE is_active = 1 LIMIT 3");
    $news = $db->query("SELECT * FROM news WHERE status = 'published' ORDER BY published_at DESC LIMIT 3");
    $events = $db->query("SELECT * FROM events WHERE status = 'upcoming' ORDER BY event_date ASC LIMIT 3");
    $sermons = $db->query("SELECT * FROM sermons WHERE status = 'published' ORDER BY sermon_date DESC LIMIT 3");
    $gallery = $db->query("SELECT * FROM gallery WHERE status = 'published' AND is_featured = 1 LIMIT 6");
} catch (Exception $e) {
    // Set empty arrays to prevent errors
    $services = [];
    $ministries = [];
    $news = [];
    $events = [];
    $sermons = [];
    $gallery = [];
}

// Helper function for safe HTML output
function safe_html($string, $default = '') {
    return htmlspecialchars($string ?? $default, ENT_QUOTES, 'UTF-8');
}

// Helper function for safe date formatting
function safe_date($date, $format) {
    try {
        return date($format, strtotime($date));
    } catch (Exception $e) {
        return 'Date not available';
    }
}

// Clean any buffered output
ob_end_clean();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Salem Dominion Ministries - Where Faith Meets Hope. Experience divine worship with Apostle Faty Musasizi in Iganga, Uganda.">
    <meta name="keywords" content="church, Iganga, Uganda, worship, Apostle Faty Musasizi, Salem Dominion Ministries">
    <meta name="author" content="Salem Dominion Ministries">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- AOS Animation -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700;900&family=Montserrat:wght@300;400;500;600;700&family=Great+Vibes&display=swap" rel="stylesheet">
    
    <style>
        /* Clean White and Blue Design with Heavenly Touches */
        :root {
            --primary-blue: #1e3a8a;
            --light-blue: #3b82f6;
            --sky-blue: #60a5fa;
            --pale-blue: #dbeafe;
            --white: #ffffff;
            --heavenly-gold: #fbbf24;
            --text-dark: #1f2937;
            --text-light: #6b7280;
            --shadow-soft: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            --shadow-medium: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Montserrat', sans-serif;
            line-height: 1.6;
            color: var(--text-dark);
            background: var(--white);
            overflow-x: hidden;
        }

        /* Navigation */
        .navbar {
            background: var(--white) !important;
            box-shadow: var(--shadow-soft);
            padding: 1rem 0;
            transition: all 0.3s ease;
        }

        .navbar-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            font-family: 'Great Vibes', cursive;
            font-size: 2rem;
            font-weight: 700;
            color: var(--primary-blue) !important;
        }

        .navbar-brand img {
            height: 45px;
            width: auto;
            border-radius: 50%;
            background: var(--pale-blue);
            padding: 5px;
            box-shadow: 0 0 20px rgba(59, 130, 246, 0.3);
            transition: all 0.3s ease;
        }

        .navbar-brand:hover img {
            transform: scale(1.1);
            box-shadow: 0 0 30px rgba(59, 130, 246, 0.5);
        }

        .navbar-nav .nav-link {
            color: var(--text-dark) !important;
            font-weight: 500;
            margin: 0 10px;
            transition: all 0.3s ease;
            position: relative;
        }

        .navbar-nav .nav-link:hover,
        .navbar-nav .nav-link.active {
            color: var(--primary-blue) !important;
        }

        .navbar-nav .nav-link::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 50%;
            width: 0;
            height: 2px;
            background: var(--heavenly-gold);
            transition: all 0.3s ease;
            transform: translateX(-50%);
        }

        .navbar-nav .nav-link:hover::after,
        .navbar-nav .nav-link.active::after {
            width: 80%;
        }

        /* Hero Section */
        .hero-section {
            background: linear-gradient(135deg, var(--primary-blue) 0%, var(--light-blue) 50%, var(--sky-blue) 100%);
            color: var(--white);
            padding: 120px 0 80px;
            position: relative;
            overflow: hidden;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: -50%;
            width: 200%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
            animation: shimmer 8s infinite;
        }

        @keyframes shimmer {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }

        .hero-content {
            position: relative;
            z-index: 2;
            text-align: center;
        }

        .hero-logo {
            margin-bottom: 2rem;
            animation: float 6s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-15px); }
        }

        .hero-logo img {
            height: 120px;
            width: auto;
            border-radius: 50%;
            background: var(--white);
            padding: 15px;
            box-shadow: 0 0 40px rgba(251, 191, 36, 0.4);
        }

        .hero-title {
            font-family: 'Playfair Display', serif;
            font-size: 3.5rem;
            font-weight: 900;
            margin-bottom: 1.5rem;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }

        .hero-subtitle {
            font-size: 1.5rem;
            margin-bottom: 2rem;
            opacity: 0.95;
        }

        .bible-verse {
            font-family: 'Playfair Display', serif;
            font-size: 1.3rem;
            font-style: italic;
            margin-bottom: 2rem;
            padding: 1.5rem;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            border-left: 4px solid var(--heavenly-gold);
        }

        .bible-reference {
            font-size: 1rem;
            color: var(--heavenly-gold);
            font-weight: 600;
        }

        .hero-cta {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn-heavenly {
            background: var(--white);
            color: var(--primary-blue);
            padding: 15px 35px;
            border-radius: 50px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            border: 2px solid var(--white);
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-heavenly:hover {
            background: transparent;
            color: var(--white);
            transform: translateY(-2px);
            box-shadow: var(--shadow-medium);
        }

        .btn-heavenly-outline {
            background: transparent;
            color: var(--white);
            border: 2px solid var(--white);
        }

        .btn-heavenly-outline:hover {
            background: var(--white);
            color: var(--primary-blue);
        }

        /* Section Styles */
        .section {
            padding: 80px 0;
        }

        .section-light {
            background: var(--white);
        }

        .section-blue {
            background: linear-gradient(135deg, var(--pale-blue) 0%, var(--white) 100%);
        }

        .section-title {
            font-family: 'Playfair Display', serif;
            font-size: 2.5rem;
            font-weight: 700;
            text-align: center;
            margin-bottom: 1rem;
            color: var(--primary-blue);
        }

        .section-subtitle {
            text-align: center;
            font-size: 1.1rem;
            color: var(--text-light);
            margin-bottom: 3rem;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        /* Service Times */
        .service-card {
            background: var(--white);
            border-radius: 15px;
            padding: 2rem;
            box-shadow: var(--shadow-soft);
            text-align: center;
            transition: all 0.3s ease;
            border: 1px solid var(--pale-blue);
        }

        .service-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-medium);
            border-color: var(--light-blue);
        }

        .service-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, var(--light-blue), var(--sky-blue));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            color: var(--white);
            font-size: 1.5rem;
        }

        .service-time {
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--primary-blue);
            margin-bottom: 0.5rem;
        }

        .service-day {
            color: var(--text-light);
            font-weight: 500;
        }

        /* Ministries Grid */
        .ministry-card {
            background: var(--white);
            border-radius: 15px;
            overflow: hidden;
            box-shadow: var(--shadow-soft);
            transition: all 0.3s ease;
            height: 100%;
            border: 1px solid var(--pale-blue);
        }

        .ministry-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-medium);
        }

        .ministry-image {
            height: 200px;
            background: linear-gradient(135deg, var(--light-blue), var(--sky-blue));
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--white);
            font-size: 3rem;
        }

        .ministry-content {
            padding: 1.5rem;
        }

        .ministry-title {
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--primary-blue);
            margin-bottom: 1rem;
        }

        .ministry-description {
            color: var(--text-light);
            margin-bottom: 1.5rem;
        }

        /* News & Events */
        .news-card {
            background: var(--white);
            border-radius: 15px;
            overflow: hidden;
            box-shadow: var(--shadow-soft);
            transition: all 0.3s ease;
            border: 1px solid var(--pale-blue);
        }

        .news-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-medium);
        }

        .news-image {
            height: 150px;
            background: linear-gradient(135deg, var(--light-blue), var(--sky-blue));
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--white);
            font-size: 2rem;
        }

        .news-content {
            padding: 1.5rem;
        }

        .news-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--primary-blue);
            margin-bottom: 0.5rem;
        }

        .news-date {
            color: var(--text-light);
            font-size: 0.9rem;
            margin-bottom: 1rem;
        }

        .news-excerpt {
            color: var(--text-light);
            font-size: 0.95rem;
        }

        /* Map Section */
        .map-section {
            background: var(--white);
            border-radius: 15px;
            overflow: hidden;
            box-shadow: var(--shadow-medium);
        }

        .map-container {
            height: 400px;
            background: linear-gradient(135deg, var(--pale-blue), var(--light-blue));
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary-blue);
            font-size: 1.2rem;
        }

        .contact-info {
            padding: 2rem;
        }

        .contact-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .contact-icon {
            width: 40px;
            height: 40px;
            background: var(--pale-blue);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary-blue);
        }

        /* Footer */
        .footer {
            background: var(--primary-blue);
            color: var(--white);
            padding: 3rem 0 1rem;
        }

        .footer-logo {
            text-align: center;
            margin-bottom: 2rem;
        }

        .footer-logo img {
            height: 60px;
            border-radius: 50%;
            background: var(--white);
            padding: 8px;
            margin-bottom: 1rem;
        }

        .footer-church-name {
            font-family: 'Great Vibes', cursive;
            font-size: 2rem;
            color: var(--heavenly-gold);
        }

        .footer-links {
            text-align: center;
            margin-bottom: 2rem;
        }

        .footer-links a {
            color: var(--white);
            text-decoration: none;
            margin: 0 15px;
            transition: color 0.3s ease;
        }

        .footer-links a:hover {
            color: var(--heavenly-gold);
        }

        .footer-contact {
            text-align: center;
            margin-bottom: 2rem;
        }

        .footer-contact p {
            margin-bottom: 0.5rem;
        }

        .footer-copyright {
            text-align: center;
            padding-top: 2rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            color: rgba(255, 255, 255, 0.8);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .hero-title {
                font-size: 2.5rem;
            }
            
            .hero-subtitle {
                font-size: 1.2rem;
            }
            
            .bible-verse {
                font-size: 1.1rem;
                padding: 1rem;
            }
            
            .section-title {
                font-size: 2rem;
            }
            
            .hero-cta {
                flex-direction: column;
                align-items: center;
            }
            
            .btn-heavenly {
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
                <img src="public/images/logo.png" alt="Salem Dominion Ministries">
                <span>Salem Dominion Ministries</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link active" href="index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="about.php">About</a></li>
                    <li class="nav-item"><a class="nav-link" href="ministries.php">Ministries</a></li>
                    <li class="nav-item"><a class="nav-link" href="events.php">Events</a></li>
                    <li class="nav-item"><a class="nav-link" href="sermons.php">Sermons</a></li>
                    <li class="nav-item"><a class="nav-link" href="news.php">News</a></li>
                    <li class="nav-item"><a class="nav-link" href="gallery.php">Gallery</a></li>
                    <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="hero-content" data-aos="fade-up">
                <div class="hero-logo">
                    <img src="public/images/logo.png" alt="Salem Dominion Ministries">
                </div>
                <h1 class="hero-title">Welcome to Salem Dominion Ministries</h1>
                <p class="hero-subtitle">Where Faith Meets Hope</p>
                <div class="bible-verse">
                    "For I know the thoughts that I think toward you, says the Lord, thoughts of peace and not of evil, to give you a future and a hope."
                    <div class="bible-reference">- Jeremiah 29:11</div>
                </div>
                <div class="hero-cta">
                    <a href="about.php" class="btn-heavenly">
                        <i class="fas fa-church"></i> Learn More
                    </a>
                    <a href="contact.php" class="btn-heavenly btn-heavenly-outline">
                        <i class="fas fa-phone"></i> Contact Us
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Service Times -->
    <section class="section section-light">
        <div class="container">
            <h2 class="section-title" data-aos="fade-up">Service Times</h2>
            <p class="section-subtitle" data-aos="fade-up" data-aos-delay="100">Join us for worship and fellowship</p>
            
            <div class="row g-4">
                <?php if ($services && $services->num_rows > 0): ?>
                    <?php while ($service = $services->fetch_assoc()): ?>
                        <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="200">
                            <div class="service-card">
                                <div class="service-icon">
                                    <i class="fas fa-cross"></i>
                                </div>
                                <div class="service-time">
                                    <?php echo date('g:i A', strtotime($service['start_time'])); ?> - 
                                    <?php echo date('g:i A', strtotime($service['end_time'])); ?>
                                </div>
                                <div class="service-day"><?php echo ucfirst($service['day_of_week']); ?></div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="col-12 text-center">
                        <div class="service-card">
                            <div class="service-icon">
                                <i class="fas fa-calendar-alt"></i>
                            </div>
                            <div class="service-time">Sunday Service</div>
                            <div class="service-day">10:00 AM - 12:00 PM</div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Ministries -->
    <section class="section section-blue">
        <div class="container">
            <h2 class="section-title" data-aos="fade-up">Our Ministries</h2>
            <p class="section-subtitle" data-aos="fade-up" data-aos-delay="100">Discover ways to serve and grow</p>
            
            <div class="row g-4">
                <?php if ($ministries && $ministries->num_rows > 0): ?>
                    <?php while ($ministry = $ministries->fetch_assoc()): ?>
                        <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="200">
                            <div class="ministry-card">
                                <div class="ministry-image">
                                    <i class="fas fa-hands-helping"></i>
                                </div>
                                <div class="ministry-content">
                                    <h3 class="ministry-title"><?php echo safe_html($ministry['name']); ?></h3>
                                    <p class="ministry-description"><?php echo safe_html(substr($ministry['description'], 0, 100)); ?>...</p>
                                    <a href="ministries.php" class="btn-heavenly btn-sm">Learn More</a>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="200">
                        <div class="ministry-card">
                            <div class="ministry-image">
                                <i class="fas fa-child"></i>
                            </div>
                            <div class="ministry-content">
                                <h3 class="ministry-title">Children Ministry</h3>
                                <p class="ministry-description">Nurturing young hearts with God's love through age-appropriate Bible teaching and fun activities.</p>
                                <a href="children_ministry.php" class="btn-heavenly btn-sm">Learn More</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="300">
                        <div class="ministry-card">
                            <div class="ministry-image">
                                <i class="fas fa-users"></i>
                            </div>
                            <div class="ministry-content">
                                <h3 class="ministry-title">Youth Ministry</h3>
                                <p class="ministry-description">Empowering teenagers to grow in faith and develop leadership skills through various activities.</p>
                                <a href="ministries.php" class="btn-heavenly btn-sm">Learn More</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="400">
                        <div class="ministry-card">
                            <div class="ministry-image">
                                <i class="fas fa-music"></i>
                            </div>
                            <div class="ministry-content">
                                <h3 class="ministry-title">Worship Ministry</h3>
                                <p class="ministry-description">Leading our congregation in worship through music, song, and praise to glorify God.</p>
                                <a href="ministries.php" class="btn-heavenly btn-sm">Learn More</a>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Latest News & Events -->
    <section class="section section-light">
        <div class="container">
            <h2 class="section-title" data-aos="fade-up">Latest News & Events</h2>
            <p class="section-subtitle" data-aos="fade-up" data-aos-delay="100">Stay updated with our community</p>
            
            <div class="row g-4">
                <?php if ($news && $news->num_rows > 0): ?>
                    <?php while ($news_item = $news->fetch_assoc()): ?>
                        <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="200">
                            <div class="news-card">
                                <div class="news-image">
                                    <i class="fas fa-newspaper"></i>
                                </div>
                                <div class="news-content">
                                    <h3 class="news-title"><?php echo safe_html($news_item['title']); ?></h3>
                                    <p class="news-date"><?php echo safe_date($news_item['published_at'], 'F j, Y'); ?></p>
                                    <p class="news-excerpt"><?php echo safe_html(substr($news_item['content'], 0, 80)); ?>...</p>
                                    <a href="news.php" class="btn-heavenly btn-sm">Read More</a>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="200">
                        <div class="news-card">
                            <div class="news-image">
                                <i class="fas fa-calendar-check"></i>
                            </div>
                            <div class="news-content">
                                <h3 class="news-title">Weekly Service</h3>
                                <p class="news-date">Every Sunday</p>
                                <p class="news-excerpt">Join us for our weekly worship service and experience God's presence.</p>
                                <a href="events.php" class="btn-heavenly btn-sm">Learn More</a>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                
                <?php if ($events && $events->num_rows > 0): ?>
                    <?php while ($event = $events->fetch_assoc()): ?>
                        <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="300">
                            <div class="news-card">
                                <div class="news-image">
                                    <i class="fas fa-calendar-alt"></i>
                                </div>
                                <div class="news-content">
                                    <h3 class="news-title"><?php echo safe_html($event['title']); ?></h3>
                                    <p class="news-date"><?php echo safe_date($event['event_date'], 'F j, Y'); ?></p>
                                    <p class="news-excerpt"><?php echo safe_html(substr($event['description'], 0, 80)); ?>...</p>
                                    <a href="events.php" class="btn-heavenly btn-sm">View Event</a>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Map & Contact -->
    <section class="section section-blue">
        <div class="container">
            <h2 class="section-title" data-aos="fade-up">Visit Us</h2>
            <p class="section-subtitle" data-aos="fade-up" data-aos-delay="100">Find us in Iganga, Uganda</p>
            
            <div class="map-section" data-aos="fade-up" data-aos-delay="200">
                <div class="row g-0">
                    <div class="col-lg-8">
                        <div class="map-container">
                            <div class="text-center">
                                <i class="fas fa-map-marked-alt fa-3x mb-3"></i>
                                <h5>Salem Dominion Ministries</h5>
                                <p>Main Street, Iganga Town, Uganda</p>
                                <p>Near Iganga Market</p>
                                <a href="map.php" class="btn-heavenly mt-3">View Map</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="contact-info">
                            <div class="contact-item">
                                <div class="contact-icon">
                                    <i class="fas fa-phone"></i>
                                </div>
                                <div>
                                    <h6>Phone</h6>
                                    <p>+256753244480</p>
                                </div>
                            </div>
                            <div class="contact-item">
                                <div class="contact-icon">
                                    <i class="fas fa-envelope"></i>
                                </div>
                                <div>
                                    <h6>Email</h6>
                                    <p>apostle@salemdominionministries.com</p>
                                </div>
                            </div>
                            <div class="contact-item">
                                <div class="contact-icon">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div>
                                    <h6>Senior Pastor</h6>
                                    <p>Apostle Faty Musasizi</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-logo" data-aos="fade-up">
                <img src="public/images/logo.png" alt="Salem Dominion Ministries">
                <div class="footer-church-name">Salem Dominion Ministries</div>
            </div>
            
            <div class="footer-links" data-aos="fade-up" data-aos-delay="100">
                <a href="index.php">Home</a>
                <a href="about.php">About</a>
                <a href="ministries.php">Ministries</a>
                <a href="events.php">Events</a>
                <a href="sermons.php">Sermons</a>
                <a href="news.php">News</a>
                <a href="gallery.php">Gallery</a>
                <a href="contact.php">Contact</a>
            </div>
            
            <div class="footer-contact" data-aos="fade-up" data-aos-delay="200">
                <p><i class="fas fa-phone me-2"></i> +256753244480</p>
                <p><i class="fas fa-envelope me-2"></i> apostle@salemdominionministries.com</p>
                <p><i class="fas fa-map-marker-alt me-2"></i> Iganga Town, Uganda</p>
            </div>
            
            <div class="footer-copyright">
                <p>&copy; 2026 Salem Dominion Ministries. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- AOS Animation -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    
    <script>
        // Initialize AOS
        AOS.init({
            duration: 1000,
            once: true,
            offset: 100
        });

        // Navbar scroll effect
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar');
            if (window.scrollY > 50) {
                navbar.style.padding = '0.5rem 0';
                navbar.style.boxShadow = '0 4px 20px rgba(0,0,0,0.1)';
            } else {
                navbar.style.padding = '1rem 0';
                navbar.style.boxShadow = '0 2px 10px rgba(0,0,0,0.1)';
            }
        });
    </script>
</body>
</html>
