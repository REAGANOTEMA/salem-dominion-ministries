<?php
// Error reporting and performance optimization
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/logs/php_errors.log');

// Include session helper and start session safely
require_once 'session_helper.php';
secure_session_start();

// Include required files with error handling
try {
    require_once 'config.php';
    require_once 'db.php';
} catch (Exception $e) {
    error_log("Database connection failed: " . $e->getMessage());
    die("Database connection error. Please try again later.");
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
    error_log("Query failed: " . $e->getMessage());
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

// Set performance headers
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: SAMEORIGIN');
header('X-XSS-Protection: "1; mode=block"');
header('Referrer-Policy: "strict-origin-when-cross-origin"');

// Cache control for static content
if (isset($_SERVER['HTTP_IF_MODIFIED_SINCE'])) {
    header('HTTP/1.1 304 Not Modified');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Welcome to Salem Dominion Ministries - Experience God's love, grow in faith, and serve our community together">
    <meta name="keywords" content="church, ministry, faith, God, Jesus, worship, community, Salem Dominion Ministries">
    <meta name="author" content="Salem Dominion Ministries">
    <meta name="robots" content="index, follow">
    
    <!-- Open Graph Meta Tags -->
    <meta property="og:title" content="Salem Dominion Ministries - Welcome Home">
    <meta property="og:description" content="Experience God's love, grow in faith, and serve our community together">
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?php echo APP_URL; ?>">
    <meta property="og:image" content="<?php echo APP_URL; ?>/assets/images/og-image.jpg">
    <meta property="og:site_name" content="Salem Dominion Ministries">
    
    <!-- Twitter Card Meta Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Salem Dominion Ministries - Welcome Home">
    <meta name="twitter:description" content="Experience God's love, grow in faith, and serve our community together">
    <meta name="twitter:image" content="<?php echo APP_URL; ?>/assets/images/twitter-image.jpg">
    
    <!-- Favicon and App Icons -->
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="manifest" href="/manifest.json">
    
    <!-- Theme Color -->
    <meta name="theme-color" content="#FFD700">
    <meta name="msapplication-TileColor" content="#FFD700">
    
    <!-- Preconnect for performance -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://cdn.jsdelivr.net">
    <link rel="preconnect" href="https://unpkg.com">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    
    <!-- AOS Animation -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700;900&family=Montserrat:wght@300;400;500;600;700&family=Dancing+Script:wght@700&display=swap" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link href="assets/css/perfect_responsive.css" rel="stylesheet">
    
    <style>
        /* Critical CSS for immediate rendering */
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
            --gradient-church: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Montserrat', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
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
            font-family: 'Playfair Display', Georgia, serif;
            font-weight: 700;
            line-height: 1.2;
        }

        /* Skip to content for accessibility */
        .skip-to-content {
            position: absolute;
            top: -40px;
            left: 6px;
            background: var(--heavenly-gold);
            color: white;
            padding: 8px;
            text-decoration: none;
            border-radius: 4px;
            z-index: 10000;
            transition: top 0.3s;
        }

        .skip-to-content:focus {
            top: 6px;
        }

        /* Loading animation */
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

        /* Floating Angels */
        .floating-angel {
            position: fixed;
            font-size: 2rem;
            color: var(--heavenly-gold);
            opacity: 0.3;
            pointer-events: none;
            z-index: 2;
            transition: all 0.3s ease;
        }

        .angel-1 { top: 20%; left: 5%; animation-delay: 0s; }
        .angel-2 { top: 60%; right: 5%; animation-delay: 2s; }
        .angel-3 { top: 40%; left: 10%; animation-delay: 4s; }

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
            min-height: 600px;
            background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)),
                        url('https://images.unsplash.com/photo-1506905925346-21bda4d32df4?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80') center/cover;
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

        .angel-left { top: 20%; left: 10%; }
        .angel-right { top: 20%; right: 10%; }

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
            font-size: clamp(2rem, 5vw, 4rem);
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
            font-size: clamp(1rem, 2.5vw, 1.5rem);
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

        .hero-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
            animation: fadeInUp 1s ease 0.4s both;
        }

        .btn-hero {
            padding: 15px 40px;
            font-size: 1.1rem;
            font-weight: 600;
            border: none;
            border-radius: 50px;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
            position: relative;
            overflow: hidden;
            min-height: 44px;
            min-width: 44px;
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
            padding: clamp(60px, 8vw, 100px) 0;
            position: relative;
            z-index: 2;
        }

        .section-alt {
            background: var(--gradient-angel);
        }

        .section-title {
            font-size: clamp(2rem, 4vw, 3rem);
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
            font-size: clamp(1rem, 2vw, 1.2rem);
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

        /* Service Cards */
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

        /* Event Cards */
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

        /* Sermon Cards */
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

        /* CTA Section */
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

        /* App Banner */
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
            min-height: 44px;
            min-width: 44px;
        }

        .app-button:hover {
            background: white;
            color: var(--primary-color);
            transform: translateY(-2px);
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

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .hero-buttons {
                flex-direction: column;
                align-items: center;
            }
            
            .btn-hero {
                width: 100%;
                max-width: 280px;
            }
            
            .floating-angel {
                font-size: 1.5rem;
            }
            
            .angel-3 {
                display: none;
            }
        }

        /* Performance optimizations */
        .card-custom,
        .service-card,
        .event-card,
        .sermon-card {
            will-change: transform;
            transform: translateZ(0);
        }

        /* Accessibility */
        .sr-only {
            position: absolute;
            width: 1px;
            height: 1px;
            padding: 0;
            margin: -1px;
            overflow: hidden;
            clip: rect(0, 0, 0, 0);
            white-space: nowrap;
            border: 0;
        }

        /* Focus styles */
        .btn:focus,
        .nav-link:focus,
        .card:focus {
            outline: 3px solid var(--heavenly-gold);
            outline-offset: 2px;
        }

        /* Print styles */
        @media print {
            .navbar,
            .floating-angel,
            .hero-angel,
            .app-banner,
            .booking-cta {
                display: none !important;
            }
            
            .hero-section {
                background: white !important;
                color: black !important;
                height: auto !important;
            }
        }
    </style>
</head>
<body>
    <!-- Skip to content link for accessibility -->
    <a href="#main-content" class="skip-to-content">Skip to main content</a>

    <!-- Floating Angels -->
    <div class="floating-angel angel-1" role="img" aria-label="Decorative angel">🕊️</div>
    <div class="floating-angel angel-2" role="img" aria-label="Decorative angel">✨</div>
    <div class="floating-angel angel-3" role="img" aria-label="Decorative angel">👼</div>

    <!-- Loader -->
    <div class="loader" id="loader" role="status" aria-label="Loading">
        <div class="loader-content">
            <i class="fas fa-dove loader-icon" aria-hidden="true"></i>
            <p class="mt-3">Loading God's blessings...</p>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg fixed-top" role="navigation" aria-label="Main navigation">
        <div class="container">
            <a class="navbar-brand" href="index.php" aria-label="Salem Dominion Ministries Home">
                <i class="fas fa-church" aria-hidden="true"></i> Salem Dominion Ministries
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" 
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
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
                    <li class="nav-item"><a class="nav-link" href="map.php">Find Us</a></li>
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

    <!-- Main Content -->
    <main id="main-content">
        <!-- Hero Section -->
        <section class="hero-section" role="banner" aria-labelledby="hero-title">
            <div class="hero-angel angel-left" role="img" aria-label="Decorative angel">👼</div>
            <div class="hero-angel angel-right" role="img" aria-label="Decorative angel">🕊️</div>
            <div class="hero-content">
                <h1 class="hero-title" id="hero-title">Welcome to Salem Dominion Ministries</h1>
                <p class="hero-subtitle">Experience God's love, grow in faith, and serve our community together</p>
                <div class="hero-buttons">
                    <a href="about.php" class="btn btn-hero btn-primary-hero" aria-label="Learn more about our church">
                        <i class="fas fa-info-circle" aria-hidden="true"></i> Learn More
                    </a>
                    <a href="contact.php" class="btn btn-hero btn-outline-hero" aria-label="Contact our church">
                        <i class="fas fa-envelope" aria-hidden="true"></i> Contact Us
                    </a>
                </div>
            </div>
        </section>

        <!-- Service Times -->
        <section class="section section-alt" aria-labelledby="services-title">
            <div class="container">
                <h2 class="section-title" id="services-title" data-aos="fade-up">Service Times</h2>
                <p class="section-subtitle" data-aos="fade-up" data-aos-delay="100">Join us for worship and fellowship</p>
                
                <div class="row">
                    <?php if ($services && $services->num_rows > 0): ?>
                        <?php while ($service = $services->fetch_assoc()): ?>
                        <div class="col-md-6 col-lg-4 mb-4" data-aos="fade-up" data-aos-delay="200">
                            <div class="service-card">
                                <div class="service-icon">
                                    <i class="fas fa-clock" aria-hidden="true"></i>
                                </div>
                                <h3 class="card-title"><?php echo safe_html(ucfirst($service['day_of_week'])); ?></h3>
                                <h4 class="card-subtitle mb-2 text-muted"><?php echo safe_html($service['service_name']); ?></h4>
                                <p class="card-text">
                                    <i class="fas fa-clock text-primary" aria-hidden="true"></i> 
                                    <?php echo safe_date($service['start_time'], 'g:i A'); ?> - 
                                    <?php echo safe_date($service['end_time'], 'g:i A'); ?>
                                </p>
                                <p class="card-text">
                                    <i class="fas fa-map-marker-alt text-primary" aria-hidden="true"></i> 
                                    <?php echo safe_html($service['location']); ?>
                                </p>
                                <?php if ($service['description']): ?>
                                <p class="card-text small text-muted"><?php echo safe_html($service['description']); ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <div class="col-12">
                            <div class="alert alert-info text-center">
                                <i class="fas fa-info-circle" aria-hidden="true"></i> Service times will be available soon.
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </section>

        <!-- Ministries Preview -->
        <section class="section" aria-labelledby="ministries-title">
            <div class="container">
                <h2 class="section-title" id="ministries-title" data-aos="fade-up">Our Ministries</h2>
                <p class="section-subtitle" data-aos="fade-up" data-aos-delay="100">Discover ways to get involved and grow in your faith</p>
                
                <div class="row">
                    <?php if ($ministries && $ministries->num_rows > 0): ?>
                        <?php while ($ministry = $ministries->fetch_assoc()): ?>
                        <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-delay="200">
                            <div class="ministry-card card-custom">
                                <div class="card-body">
                                    <h3 class="card-title">
                                        <i class="fas fa-users text-primary" aria-hidden="true"></i>
                                        <?php echo safe_html($ministry['name']); ?>
                                    </h3>
                                    <p class="card-text"><?php echo safe_html(substr($ministry['description'], 0, 150)) . '...'; ?></p>
                                    <?php if ($ministry['meeting_day'] && $ministry['meeting_time']): ?>
                                    <p class="card-text small text-muted">
                                        <i class="fas fa-calendar" aria-hidden="true"></i> <?php echo safe_html($ministry['meeting_day']); ?> 
                                        <?php echo safe_html($ministry['meeting_time']); ?>
                                    </p>
                                    <?php endif; ?>
                                    <a href="ministries.php" class="btn btn-primary btn-sm" aria-label="Learn more about ministries">Learn More</a>
                                </div>
                            </div>
                        </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <div class="col-12">
                            <div class="alert alert-info text-center">
                                <i class="fas fa-info-circle" aria-hidden="true"></i> Ministry information will be available soon.
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
                
                <div class="text-center mt-4" data-aos="fade-up" data-aos-delay="300">
                    <a href="ministries.php" class="btn btn-primary btn-lg" aria-label="View all ministries">View All Ministries</a>
                </div>
            </div>
        </section>

        <!-- Upcoming Events -->
        <section class="section section-alt" aria-labelledby="events-title">
            <div class="container">
                <h2 class="section-title" id="events-title" data-aos="fade-up">Upcoming Events</h2>
                <p class="section-subtitle" data-aos="fade-up" data-aos-delay="100">Join us for these exciting events</p>
                
                <div class="row">
                    <?php if ($events && $events->num_rows > 0): ?>
                        <?php while ($event = $events->fetch_assoc()): ?>
                        <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-delay="200">
                            <div class="event-card">
                                <div class="event-date">
                                    <i class="fas fa-calendar-alt" aria-hidden="true"></i>
                                    <?php echo safe_date($event['event_date'], 'F j, Y'); ?>
                                </div>
                                <div class="card-body">
                                    <h3 class="card-title"><?php echo safe_html($event['title']); ?></h3>
                                    <p class="card-text">
                                        <i class="fas fa-clock text-primary" aria-hidden="true"></i> 
                                        <?php echo safe_date($event['event_date'], 'g:i A'); ?>
                                    </p>
                                    <?php if ($event['location']): ?>
                                    <p class="card-text">
                                        <i class="fas fa-map-marker-alt text-primary" aria-hidden="true"></i> 
                                        <?php echo safe_html($event['location']); ?>
                                    </p>
                                    <?php endif; ?>
                                    <p class="card-text"><?php echo safe_html(substr($event['description'], 0, 100)) . '...'; ?></p>
                                    <a href="events.php" class="btn btn-primary btn-sm" aria-label="Learn more about events">Learn More</a>
                                </div>
                            </div>
                        </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <div class="col-12">
                            <div class="alert alert-info text-center">
                                <i class="fas fa-info-circle" aria-hidden="true"></i> Event information will be available soon.
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </section>

        <!-- Latest Sermons -->
        <section class="section" aria-labelledby="sermons-title">
            <div class="container">
                <h2 class="section-title" id="sermons-title" data-aos="fade-up">Latest Sermons</h2>
                <p class="section-subtitle" data-aos="fade-up" data-aos-delay="100">Be inspired by God's Word</p>
                
                <div class="row">
                    <?php if ($sermons && $sermons->num_rows > 0): ?>
                        <?php while ($sermon = $sermons->fetch_assoc()): ?>
                        <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-delay="200">
                            <div class="sermon-card">
                                <h3 class="card-title">
                                    <i class="fas fa-bible text-primary" aria-hidden="true"></i>
                                    <?php echo safe_html($sermon['title']); ?>
                                </h3>
                                <?php if ($sermon['bible_reference']): ?>
                                <p class="sermon-verse"><?php echo safe_html($sermon['bible_reference']); ?></p>
                                <?php endif; ?>
                                <p class="card-text">
                                    <i class="fas fa-user text-primary" aria-hidden="true"></i> 
                                    <?php echo safe_html($sermon['preacher']); ?>
                                </p>
                                <p class="card-text">
                                    <i class="fas fa-calendar text-primary" aria-hidden="true"></i> 
                                    <?php echo safe_date($sermon['sermon_date'], 'F j, Y'); ?>
                                </p>
                                <?php if ($sermon['description']): ?>
                                <p class="card-text"><?php echo safe_html(substr($sermon['description'], 0, 100)) . '...'; ?></p>
                                <?php endif; ?>
                                <div class="mt-3">
                                    <?php if ($sermon['video_url']): ?>
                                    <a href="<?php echo safe_html($sermon['video_url']); ?>" class="btn btn-primary btn-sm me-2" target="_blank" rel="noopener" aria-label="Watch sermon video">
                                        <i class="fas fa-play" aria-hidden="true"></i> Watch
                                    </a>
                                    <?php endif; ?>
                                    <?php if ($sermon['audio_url']): ?>
                                    <a href="<?php echo safe_html($sermon['audio_url']); ?>" class="btn btn-outline-primary btn-sm" target="_blank" rel="noopener" aria-label="Listen to sermon audio">
                                        <i class="fas fa-headphones" aria-hidden="true"></i> Listen
                                    </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <div class="col-12">
                            <div class="alert alert-info text-center">
                                <i class="fas fa-info-circle" aria-hidden="true"></i> Sermon recordings will be available soon.
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </section>

        <!-- Gallery Preview -->
        <section class="section section-alt" aria-labelledby="gallery-title">
            <div class="container">
                <h2 class="section-title" id="gallery-title" data-aos="fade-up">Gallery</h2>
                <p class="section-subtitle" data-aos="fade-up" data-aos-delay="100">Moments from our church family</p>
                
                <div class="row">
                    <?php if ($gallery && $gallery->num_rows > 0): ?>
                        <?php while ($item = $gallery->fetch_assoc()): ?>
                        <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-delay="200">
                            <div class="gallery-item">
                                <img src="<?php echo safe_html($item['file_url']); ?>" alt="<?php echo safe_html($item['title']); ?>" loading="lazy">
                                <div class="gallery-overlay">
                                    <div>
                                        <h6><?php echo safe_html($item['title']); ?></h6>
                                        <?php if ($item['description']): ?>
                                        <p class="small"><?php echo safe_html(substr($item['description'], 0, 50)) . '...'; ?></p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <div class="col-12">
                            <div class="alert alert-info text-center">
                                <i class="fas fa-info-circle" aria-hidden="true"></i> Gallery images will be available soon.
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
                
                <div class="text-center mt-4" data-aos="fade-up" data-aos-delay="300">
                    <a href="gallery.php" class="btn btn-primary btn-lg" aria-label="View full gallery">View Full Gallery</a>
                </div>
            </div>
        </section>

        <!-- Pastor Booking CTA -->
        <section class="booking-cta" aria-labelledby="booking-title">
            <div class="container">
                <h2 class="text-white mb-4" id="booking-title" data-aos="fade-up">Book a Call with Our Pastor</h2>
                <p class="lead mb-4" data-aos="fade-up" data-aos-delay="100">
                    Need spiritual guidance, counseling, or prayer? Schedule a one-on-one session with our pastor.
                </p>
                <div data-aos="fade-up" data-aos-delay="200">
                    <a href="pastor_booking.php" class="btn btn-light btn-lg me-3" aria-label="Book appointment with pastor">
                        <i class="fas fa-calendar-check" aria-hidden="true"></i> Book Appointment
                    </a>
                    <a href="contact.php" class="btn btn-outline-light btn-lg" aria-label="Contact church office">
                        <i class="fas fa-phone" aria-hidden="true"></i> Contact Office
                    </a>
                </div>
            </div>
        </section>

        <!-- Mobile App Banner -->
        <section class="app-banner" aria-labelledby="app-title">
            <div class="container">
                <h3 class="mb-3" id="app-title" data-aos="fade-up">Take Our Church With You</h3>
                <p class="mb-4" data-aos="fade-up" data-aos-delay="100">Download our mobile app for sermons, events, and more</p>
                <div class="app-buttons" data-aos="fade-up" data-aos-delay="200">
                    <a href="#" class="app-button" onclick="installApp(); return false;" aria-label="Install mobile app">
                        <i class="fas fa-download" aria-hidden="true"></i> Install App
                    </a>
                    <a href="#" class="app-button" aria-label="Download from App Store">
                        <i class="fab fa-apple" aria-hidden="true"></i> App Store
                    </a>
                    <a href="#" class="app-button" aria-label="Download from Google Play">
                        <i class="fab fa-google-play" aria-hidden="true"></i> Google Play
                    </a>
                </div>
            </div>
        </section>
    </main>

    <!-- Enhanced Footer -->
    <?php require_once 'footer_enhanced.php'; ?>
</body>
</html>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigS/jj88gg3TDOcYve" crossorigin="anonymous"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script src="assets/js/heavenly_sounds.js"></script>
<script src="assets/js/perfect_animations.js"></script>
    <script src="assets/js/spiritual_enhancement.js"></script>
<script>
// Initialize everything when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    'use strict';
    
    // Initialize AOS
    try {
        AOS.init({
            duration: 1000,
            once: true,
            offset: 100,
            disable: window.matchMedia('(prefers-reduced-motion: reduce)').matches
        });
    } catch (error) {
        console.warn('AOS initialization failed:', error);
    }
    
    // Hide loader
    setTimeout(function() {
        const loader = document.getElementById('loader');
        if (loader) {
            loader.classList.add('hidden');
        }
    }, 1500);
    
    // Navbar scroll effect
    let lastScrollTop = 0;
    window.addEventListener('scroll', function() {
        const navbar = document.querySelector('.navbar');
        const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        
        if (navbar) {
            if (scrollTop > 100) {
                navbar.style.padding = '0.5rem 0';
                navbar.style.boxShadow = '0 4px 30px rgba(0,0,0,0.1)';
            } else {
                navbar.style.padding = '1rem 0';
                navbar.style.boxShadow = '0 2px 20px rgba(0,0,0,0.1)';
            }
        }
        
        lastScrollTop = scrollTop;
    });
    
    // Smooth scrolling for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(function(anchor) {
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
    window.installApp = function() {
        if ('serviceWorker' in navigator) {
            window.addEventListener('beforeinstallprompt', function(e) {
                e.preventDefault();
                e.prompt();
                e.userChoice.then(function(choiceResult) {
                    if (choiceResult.outcome === 'accepted') {
                        console.log('User accepted the install prompt');
                    }
                });
            });
        } else {
            alert('App installation is not available on this device. Please visit our app store page.');
        }
    };
    
    // PWA Service Worker registration
    if ('serviceWorker' in navigator) {
        navigator.serviceWorker.register('/sw.js')
            .then(function(registration) {
                console.log('SW registered');
            })
            .catch(function(error) {
                console.log('SW registration failed');
            });
    }
    
    // Add heavenly interactions
    if (window.heavenlyGuidance) {
        setTimeout(function() {
            window.heavenlyGuidance.showWelcomeMessage();
        }, 4000);
        
        // Periodic angelic presence
        setInterval(function() {
            if (Math.random() > 0.95) {
                window.heavenlyGuidance.showAngelGuidance();
            }
        }, 45000);
        
        // Heavenly hover effects
        document.querySelectorAll('.btn-hero, .card-custom, .service-card').forEach(function(element) {
            element.addEventListener('mouseenter', function() {
                if (Math.random() > 0.9) {
                    window.heavenlyGuidance.playGentleChime();
                }
            });
        });
    }
    
    // Performance monitoring
    if (window.performance && window.performance.navigation) {
        const loadTime = performance.timing.loadEventEnd - performance.timing.navigationStart;
        console.log('Page load time:', loadTime + 'ms');
    }
    
    // Error handling
    window.addEventListener('error', function(e) {
        console.error('JavaScript error:', e.error);
    });
    
    console.log('Salem Dominion Ministries website loaded successfully');
});

// Handle page visibility for performance
document.addEventListener('visibilitychange', function() {
    if (document.hidden) {
        // Page is hidden, pause animations
        if (window.perfectAnimations) {
            window.perfectAnimations.pauseAnimations();
        }
    } else {
        // Page is visible, resume animations
        if (window.perfectAnimations) {
            window.perfectAnimations.resumeAnimations();
        }
    }
});
</script>
