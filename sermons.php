<?php
// Complete error suppression to prevent unwanted output
error_reporting(0);
ini_set('display_errors', 0);
ini_set('log_errors', 0);

// Buffer output to catch any accidental output
ob_start();

session_start();
require_once 'config_force.php';

// Get recent sermons
try {
    $recent_sermons = $db->query("SELECT s.*, u.first_name, u.last_name FROM sermons s LEFT JOIN users u ON s.created_by = u.id WHERE s.status = 'published' ORDER BY s.sermon_date DESC LIMIT 12");
    $sermon_series = $db->query("SELECT sermon_series as series, COUNT(*) as count FROM sermons WHERE sermon_series IS NOT NULL AND sermon_series != '' GROUP BY sermon_series ORDER BY series");
} catch (Exception $e) {
    $recent_sermons = [];
    $sermon_series = [];
}

// Handle view count update (AJAX)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_view'])) {
    $sermon_id = intval($_POST['sermon_id']);
    try {
        $db->query("UPDATE sermons SET views = views + 1 WHERE id = $sermon_id");
    } catch (Exception $e) {
        // Silent error handling
    }
    exit;
}

// Clean any buffered output
ob_end_clean();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Sermons at Salem Dominion Ministries - Listen to powerful teachings by Apostle Faty Musasizi">
    <title>Sermons - Salem Dominion Ministries</title>
    
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

        /* Sermon Series Section */
        .series-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            margin-top: 4rem;
        }

        .series-card {
            background: var(--snow-white);
            border-radius: 25px;
            padding: 2rem;
            text-align: center;
            box-shadow: var(--shadow-soft);
            border: 1px solid rgba(125, 211, 252, 0.2);
            transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .series-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--gradient-divine);
        }

        .series-card:hover {
            transform: translateY(-10px);
            box-shadow: var(--shadow-divine);
            border-color: var(--ice-blue);
        }

        .series-icon {
            width: 80px;
            height: 80px;
            background: var(--gradient-divine);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            color: var(--midnight-blue);
            font-size: 2rem;
            box-shadow: 0 15px 35px rgba(251, 191, 36, 0.3);
            transition: all 0.5s ease;
        }

        .series-card:hover .series-icon {
            transform: scale(1.1) rotate(15deg);
            box-shadow: 0 20px 45px rgba(251, 191, 36, 0.4);
        }

        .series-name {
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--midnight-blue);
            margin-bottom: 0.5rem;
            font-family: 'Playfair Display', serif;
        }

        .series-count {
            color: var(--heavenly-gold);
            font-weight: 600;
            font-size: 1.1rem;
        }

        /* Sermons Grid - Iconic */
        .sermons-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
            gap: 3rem;
            margin-top: 4rem;
        }

        .sermon-card {
            background: var(--snow-white);
            border-radius: 30px;
            overflow: hidden;
            box-shadow: var(--shadow-soft);
            border: 1px solid rgba(125, 211, 252, 0.2);
            transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
        }

        .sermon-card:hover {
            transform: translateY(-15px);
            box-shadow: var(--shadow-divine);
        }

        .sermon-header {
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

        .sermon-header img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .sermon-header::before {
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

        .sermon-card:hover .sermon-header::before {
            transform: translateX(100%);
        }

        .sermon-content {
            padding: 3rem;
        }

        .sermon-date-badge {
            position: absolute;
            top: 20px;
            right: 20px;
            background: var(--gradient-divine);
            color: var(--midnight-blue);
            padding: 12px 18px;
            border-radius: 20px;
            text-align: center;
            font-weight: 700;
            box-shadow: var(--shadow-heavenly);
            z-index: 10;
            font-size: 0.9rem;
        }

        .sermon-title {
            font-size: 2rem;
            font-weight: 700;
            color: var(--midnight-blue);
            margin-bottom: 1.5rem;
            font-family: 'Playfair Display', serif;
        }

        .sermon-preacher {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1.5rem;
            padding: 1rem;
            background: var(--pearl-white);
            border-radius: 15px;
            transition: all 0.3s ease;
        }

        .sermon-preacher:hover {
            background: var(--ice-blue);
            transform: translateX(5px);
        }

        .preacher-avatar {
            width: 50px;
            height: 50px;
            background: var(--gradient-ocean);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--snow-white);
            font-weight: 700;
            font-size: 1.2rem;
            flex-shrink: 0;
        }

        .preacher-info {
            flex: 1;
        }

        .preacher-name {
            font-weight: 600;
            color: var(--midnight-blue);
            margin-bottom: 0.25rem;
        }

        .preacher-title {
            color: var(--heavenly-gold);
            font-size: 0.9rem;
        }

        .sermon-description {
            font-size: 1.1rem;
            line-height: 1.8;
            color: var(--ocean-blue);
            margin-bottom: 2rem;
        }

        .sermon-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            padding: 1rem;
            background: var(--pearl-white);
            border-radius: 15px;
        }

        .sermon-views {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--text-secondary);
            font-size: 0.9rem;
        }

        .sermon-duration {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--text-secondary);
            font-size: 0.9rem;
        }

        .sermon-actions {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn-sermon {
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

        .btn-sermon::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.2);
            transition: all 0.4s ease;
        }

        .btn-sermon:hover::before {
            left: 100%;
        }

        .btn-sermon:hover {
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

            .series-grid,
            .sermons-grid {
                grid-template-columns: 1fr;
                gap: 2rem;
            }

            .sermon-content {
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
                    <li class="nav-item"><a class="nav-link" href="events.php">Events</a></li>
                    <li class="nav-item"><a class="nav-link active" href="sermons.php">Sermons</a></li>
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
            <h1 class="hero-title">Divine Sermons</h1>
            <p class="hero-subtitle">Life-Changing Messages from God's Word</p>
        </div>
    </section>

    <!-- Sermon Series Section -->
    <section class="section section-heaven">
        <div class="container">
            <h2 class="section-title" data-aos="fade-up">Sermon Series</h2>
            <p class="section-subtitle" data-aos="fade-up" data-aos-delay="100">Explore our collection of powerful teaching series</p>
            
            <div class="series-grid">
                <?php if ($sermon_series && count($sermon_series) > 0): ?>
                    <?php foreach ($sermon_series as $series): ?>
                        <div class="series-card" data-aos="fade-up" data-aos-delay="200">
                            <div class="series-icon">
                                <i class="fas fa-book-open"></i>
                            </div>
                            <h3 class="series-name"><?php echo htmlspecialchars($series['series']); ?></h3>
                            <div class="series-count"><?php echo $series['count']; ?> Messages</div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="series-card" data-aos="fade-up" data-aos-delay="200">
                        <div class="series-icon">
                            <i class="fas fa-cross"></i>
                        </div>
                        <h3 class="series-name">Foundations of Faith</h3>
                        <div class="series-count">8 Messages</div>
                    </div>
                    <div class="series-card" data-aos="fade-up" data-aos-delay="300">
                        <div class="series-icon">
                            <i class="fas fa-dove"></i>
                        </div>
                        <h3 class="series-name">Holy Spirit Empowerment</h3>
                        <div class="series-count">6 Messages</div>
                    </div>
                    <div class="series-card" data-aos="fade-up" data-aos-delay="400">
                        <div class="series-icon">
                            <i class="fas fa-crown"></i>
                        </div>
                        <h3 class="series-name">Kingdom Living</h3>
                        <div class="series-count">12 Messages</div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Recent Sermons Section -->
    <section class="section section-light">
        <div class="container">
            <h2 class="section-title" data-aos="fade-up">Recent Sermons</h2>
            <p class="section-subtitle" data-aos="fade-up" data-aos-delay="100">Latest messages from our pulpit</p>
            
            <div class="sermons-grid">
                <?php if ($recent_sermons && count($recent_sermons) > 0): ?>
                    <?php foreach ($recent_sermons as $sermon): ?>
                        <div class="sermon-card" data-aos="fade-up" data-aos-delay="200">
                            <div class="sermon-header">
                                <img src="assets/hero-worship-CWyaH0tr.jpg" alt="Sermon" onerror="this.style.display='none'; this.parentElement.innerHTML='<i class=\"fas fa-microphone\"></i>';">
                                <div class="sermon-date-badge">
                                    <?php echo date('M j', strtotime($sermon['sermon_date'])); ?>
                                </div>
                            </div>
                            <div class="sermon-content">
                                <h3 class="sermon-title"><?php echo htmlspecialchars($sermon['title']); ?></h3>
                                <div class="sermon-preacher">
                                    <div class="preacher-avatar">
                                        <?php echo strtoupper(substr($sermon['first_name'] ?? 'A', 0) . substr($sermon['last_name'] ?? 'M', 0)); ?>
                                    </div>
                                    <div class="preacher-info">
                                        <div class="preacher-name">
                                            <?php echo htmlspecialchars(($sermon['first_name'] ?? 'Apostle') . ' ' . ($sermon['last_name'] ?? 'Faty')); ?>
                                        </div>
                                        <div class="preacher-title">Senior Pastor</div>
                                    </div>
                                </div>
                                <p class="sermon-description">
                                    <?php echo htmlspecialchars(substr($sermon['description'] ?? 'Join us for this powerful message that will transform your faith and deepen your relationship with God.', 0, 200)); ?>...
                                </p>
                                <div class="sermon-meta">
                                    <div class="sermon-views">
                                        <i class="fas fa-eye"></i>
                                        <?php echo number_format($sermon['views'] ?? 0); ?> views
                                    </div>
                                    <div class="sermon-duration">
                                        <i class="fas fa-clock"></i>
                                        <?php echo $sermon['duration'] ?? '45'; ?> min
                                    </div>
                                </div>
                                <div class="sermon-actions">
                                    <a href="#" class="btn-sermon" onclick="playSermon(<?php echo $sermon['id']; ?>)">
                                        <i class="fas fa-play"></i> Play
                                    </a>
                                    <a href="#" class="btn-sermon btn-outline" onclick="downloadSermon(<?php echo $sermon['id']; ?>)">
                                        <i class="fas fa-download"></i> Download
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <!-- Sample Sermons -->
                    <div class="sermon-card" data-aos="fade-up" data-aos-delay="200">
                        <div class="sermon-header">
                            <img src="assets/hero-worship-CWyaH0tr.jpg" alt="Sermon">
                            <div class="sermon-date-badge">
                                Dec 8
                            </div>
                        </div>
                        <div class="sermon-content">
                            <h3 class="sermon-title">The Power of Faith</h3>
                            <div class="sermon-preacher">
                                <div class="preacher-avatar">AF</div>
                                <div class="preacher-info">
                                    <div class="preacher-name">Apostle Faty Musasizi</div>
                                    <div class="preacher-title">Senior Pastor</div>
                                </div>
                            </div>
                            <p class="sermon-description">
                                Discover the transformative power of faith in this inspiring message that explores how faith can move mountains and bring miracles into your life.
                            </p>
                            <div class="sermon-meta">
                                <div class="sermon-views">
                                    <i class="fas fa-eye"></i>
                                    1,234 views
                                </div>
                                <div class="sermon-duration">
                                    <i class="fas fa-clock"></i>
                                    45 min
                                </div>
                            </div>
                            <div class="sermon-actions">
                                <a href="#" class="btn-sermon">
                                    <i class="fas fa-play"></i> Play
                                </a>
                                <a href="#" class="btn-sermon btn-outline">
                                    <i class="fas fa-download"></i> Download
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="sermon-card" data-aos="fade-up" data-aos-delay="300">
                        <div class="sermon-header">
                            <img src="assets/hero-choir-6lo-hX_h.jpg" alt="Sermon">
                            <div class="sermon-date-badge">
                                Dec 1
                            </div>
                        </div>
                        <div class="sermon-content">
                            <h3 class="sermon-title">Walking in Divine Purpose</h3>
                            <div class="sermon-preacher">
                                <div class="preacher-avatar">AF</div>
                                <div class="preacher-info">
                                    <div class="preacher-name">Apostle Faty Musasizi</div>
                                    <div class="preacher-title">Senior Pastor</div>
                                </div>
                            </div>
                            <p class="sermon-description">
                                Learn how to discover and fulfill your God-given purpose in this life-changing teaching that will guide you toward your destiny.
                            </p>
                            <div class="sermon-meta">
                                <div class="sermon-views">
                                    <i class="fas fa-eye"></i>
                                    987 views
                                </div>
                                <div class="sermon-duration">
                                    <i class="fas fa-clock"></i>
                                    52 min
                                </div>
                            </div>
                            <div class="sermon-actions">
                                <a href="#" class="btn-sermon">
                                    <i class="fas fa-play"></i> Play
                                </a>
                                <a href="#" class="btn-sermon btn-outline">
                                    <i class="fas fa-download"></i> Download
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="sermon-card" data-aos="fade-up" data-aos-delay="400">
                        <div class="sermon-header">
                            <img src="assets/hero-community-CDAgPtPb.jpg" alt="Sermon">
                            <div class="sermon-date-badge">
                                Nov 24
                            </div>
                        </div>
                        <div class="sermon-content">
                            <h3 class="sermon-title">The Blessing of Obedience</h3>
                            <div class="sermon-preacher">
                                <div class="preacher-avatar">AF</div>
                                <div class="preacher-info">
                                    <div class="preacher-name">Apostle Faty Musasizi</div>
                                    <div class="preacher-title">Senior Pastor</div>
                                </div>
                            </div>
                            <p class="sermon-description">
                                Explore the supernatural blessings that come through complete obedience to God's Word in this powerful and practical teaching.
                            </p>
                            <div class="sermon-meta">
                                <div class="sermon-views">
                                    <i class="fas fa-eye"></i>
                                    756 views
                                </div>
                                <div class="sermon-duration">
                                    <i class="fas fa-clock"></i>
                                    48 min
                                </div>
                            </div>
                            <div class="sermon-actions">
                                <a href="#" class="btn-sermon">
                                    <i class="fas fa-play"></i> Play
                                </a>
                                <a href="#" class="btn-sermon btn-outline">
                                    <i class="fas fa-download"></i> Download
                                </a>
                            </div>
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
            <h2 class="cta-title" data-aos="fade-up">Transform Your Life</h2>
            <p class="cta-subtitle" data-aos="fade-up" data-aos-delay="100">Subscribe to our sermons and grow in faith</p>
            
            <div class="cta-buttons" data-aos="fade-up" data-aos-delay="200">
                <a href="newsletter_subscribe.php" class="btn-cta btn-primary">
                    <i class="fas fa-envelope"></i> Subscribe to Sermons
                </a>
                <a href="contact.php" class="btn-cta btn-outline">
                    <i class="fas fa-phone"></i> Request Prayer
                </a>
            </div>
        </div>
    </section>

    <!-- Ultimate Footer -->
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

        // Sermon functions
        function playSermon(sermonId) {
            // Update view count
            fetch('', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `update_view=1&sermon_id=${sermonId}`
            });
            
            // Show sermon player modal (you can implement this)
            alert('Sermon player would open here. Sermon ID: ' + sermonId);
        }

        function downloadSermon(sermonId) {
            // Download sermon (you can implement this)
            alert('Sermon download would start here. Sermon ID: ' + sermonId);
        }

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
