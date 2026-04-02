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

// Get church information and leadership
try {
    $leaders = $db->query("SELECT u.first_name, u.last_name, u.email, u.avatar_url, u.role FROM users u WHERE u.is_active = 1 AND u.role IN ('admin', 'pastor') ORDER BY u.role");
    $testimonials = $db->query("SELECT * FROM testimonials WHERE is_approved = 1 ORDER BY is_featured DESC, created_at DESC LIMIT 6");
    $ministries_count = $db->selectOne("SELECT COUNT(*) as count FROM ministries WHERE is_active = 1")['count'];
    $members_count = $db->selectOne("SELECT COUNT(*) as count FROM users WHERE is_active = 1 AND role = 'member'")['count'];
    $events_count = $db->selectOne("SELECT COUNT(*) as count FROM events WHERE status = 'upcoming'")['count'];
} catch (Exception $e) {
    $leaders = [];
    $testimonials = [];
    $ministries_count = 5;
    $members_count = 150;
    $events_count = 8;
}

// Clean any buffered output
ob_end_clean();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="About Salem Dominion Ministries - Learn about our divine mission, Apostle Faty Musasizi, and our spiritual community in Iganga, Uganda">
    <title>About Us - Salem Dominion Ministries</title>
    
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

        /* Mission Section - Iconic */
        .mission-card {
            background: var(--snow-white);
            border-radius: 30px;
            padding: 4rem 3rem;
            box-shadow: var(--shadow-divine);
            border: 1px solid rgba(125, 211, 252, 0.1);
            position: relative;
            overflow: hidden;
            margin-bottom: 3rem;
        }

        .mission-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 6px;
            background: var(--gradient-ocean);
        }

        .mission-icon {
            width: 100px;
            height: 100px;
            background: var(--gradient-ocean);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 2rem;
            color: var(--snow-white);
            font-size: 3rem;
            box-shadow: 0 20px 40px rgba(14, 165, 233, 0.3);
            transition: all 0.5s ease;
        }

        .mission-icon:hover {
            transform: scale(1.1) rotate(15deg);
            box-shadow: 0 25px 50px rgba(14, 165, 233, 0.4);
        }

        .mission-title {
            font-size: 2rem;
            font-weight: 700;
            color: var(--midnight-blue);
            margin-bottom: 1.5rem;
            text-align: center;
        }

        .mission-description {
            font-size: 1.1rem;
            line-height: 1.8;
            color: var(--ocean-blue);
            text-align: center;
        }

        /* Stats Section - Iconic */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            margin-top: 4rem;
        }

        .stat-card {
            background: var(--snow-white);
            border-radius: 25px;
            padding: 3rem 2rem;
            text-align: center;
            box-shadow: var(--shadow-soft);
            border: 1px solid rgba(125, 211, 252, 0.2);
            transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .stat-card:hover {
            transform: translateY(-10px);
            box-shadow: var(--shadow-divine);
            border-color: var(--ice-blue);
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

        .stat-number {
            font-size: 3rem;
            font-weight: 900;
            color: var(--heavenly-gold);
            margin-bottom: 1rem;
            font-family: 'Playfair Display', serif;
            animation: countUp 2s ease-out;
        }

        @keyframes countUp {
            from { opacity: 0; transform: scale(0.5); }
            to { opacity: 1; transform: scale(1); }
        }

        .stat-label {
            font-size: 1.2rem;
            color: var(--midnight-blue);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        /* Leadership Section - Iconic */
        .leadership-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2.5rem;
            margin-top: 4rem;
        }

        .leader-card {
            background: var(--snow-white);
            border-radius: 30px;
            overflow: hidden;
            box-shadow: var(--shadow-soft);
            border: 1px solid rgba(125, 211, 252, 0.2);
            transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
        }

        .leader-card:hover {
            transform: translateY(-10px);
            box-shadow: var(--shadow-divine);
        }

        .leader-image {
            height: 300px;
            background: var(--gradient-ocean);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--snow-white);
            font-size: 5rem;
            position: relative;
            overflow: hidden;
        }

        .leader-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .leader-image::before {
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

        .leader-card:hover .leader-image::before {
            transform: translateX(100%);
        }

        .leader-content {
            padding: 2.5rem;
        }

        .leader-name {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--midnight-blue);
            margin-bottom: 0.5rem;
            font-family: 'Playfair Display', serif;
        }

        .leader-role {
            font-size: 1.1rem;
            color: var(--heavenly-gold);
            font-weight: 600;
            margin-bottom: 1.5rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .leader-bio {
            color: var(--ocean-blue);
            line-height: 1.7;
            margin-bottom: 2rem;
        }

        .leader-actions {
            display: flex;
            gap: 1rem;
            justify-content: center;
        }

        .btn-leader {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 25px;
            background: var(--gradient-ocean);
            color: var(--snow-white);
            border-radius: 50px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
            border: none;
            font-size: 0.9rem;
        }

        .btn-leader:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(14, 165, 233, 0.3);
            color: var(--snow-white);
        }

        /* Testimonials Section - Iconic */
        .testimonials-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 2rem;
            margin-top: 4rem;
        }

        .testimonial-card {
            background: var(--snow-white);
            border-radius: 25px;
            padding: 2.5rem;
            box-shadow: var(--shadow-soft);
            border: 1px solid rgba(125, 211, 252, 0.2);
            position: relative;
            transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .testimonial-card:hover {
            transform: translateY(-8px);
            box-shadow: var(--shadow-divine);
        }

        .testimonial-card::before {
            content: '"';
            position: absolute;
            top: 1rem;
            left: 1.5rem;
            font-size: 4rem;
            color: var(--heavenly-gold);
            opacity: 0.3;
            font-family: 'Playfair Display', serif;
        }

        .testimonial-text {
            font-size: 1.1rem;
            line-height: 1.8;
            color: var(--midnight-blue);
            margin-bottom: 2rem;
            font-style: italic;
        }

        .testimonial-author {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .author-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: var(--gradient-heaven);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--midnight-blue);
            font-weight: 700;
            font-size: 1.2rem;
        }

        .author-info h4 {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--midnight-blue);
            margin-bottom: 0.25rem;
        }

        .author-info p {
            color: var(--ocean-blue);
            font-size: 0.9rem;
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

            .mission-card {
                padding: 3rem 2rem;
            }

            .stats-grid,
            .leadership-grid,
            .testimonials-grid {
                grid-template-columns: 1fr;
                gap: 1.5rem;
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
                <img src="public/images/logo.png" alt="Salem Dominion Ministries">
                <span>Salem Dominion Ministries</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link active" href="about.php">About</a></li>
                    <li class="nav-item"><a class="nav-link" href="ministries.php">Ministries</a></li>
                    <li class="nav-item"><a class="nav-link" href="events.php">Events</a></li>
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
                <img src="public/images/logo.png" alt="Salem Dominion Ministries">
            </div>
            <h1 class="hero-title">About Our Ministry</h1>
            <p class="hero-subtitle">Discover Our Divine Calling & Spiritual Journey</p>
        </div>
    </section>

    <!-- Mission Section -->
    <section class="section section-light">
        <div class="container">
            <h2 class="section-title" data-aos="fade-up">Our Divine Mission</h2>
            <p class="section-subtitle" data-aos="fade-up" data-aos-delay="100">Serving God's kingdom with faith, hope, and love</p>
            
            <div class="mission-card" data-aos="fade-up" data-aos-delay="200">
                <div class="mission-icon">
                    <i class="fas fa-cross"></i>
                </div>
                <h3 class="mission-title">Transforming Lives Through Divine Love</h3>
                <p class="mission-description">
                    At Salem Dominion Ministries, we are called to be a beacon of hope and transformation in our community. 
                    Under the leadership of Apostle Faty Musasizi, we strive to create an environment where people can encounter 
                    God's presence, grow in their faith, and discover their divine purpose. Our mission is rooted in the 
                    unconditional love of Christ and the power of the Holy Spirit to bring healing, restoration, and 
                    revival to every heart that seeks Him.
                </p>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="section section-heaven">
        <div class="container">
            <h2 class="section-title" data-aos="fade-up">Our Impact</h2>
            <p class="section-subtitle" data-aos="fade-up" data-aos-delay="100">Growing together in faith and service</p>
            
            <div class="stats-grid">
                <div class="stat-card" data-aos="fade-up" data-aos-delay="200">
                    <div class="stat-number" data-count="<?php echo $members_count; ?>"><?php echo $members_count; ?></div>
                    <div class="stat-label">Members</div>
                </div>
                
                <div class="stat-card" data-aos="fade-up" data-aos-delay="300">
                    <div class="stat-number" data-count="<?php echo $ministries_count; ?>"><?php echo $ministries_count; ?></div>
                    <div class="stat-label">Ministries</div>
                </div>
                
                <div class="stat-card" data-aos="fade-up" data-aos-delay="400">
                    <div class="stat-number" data-count="<?php echo $events_count; ?>"><?php echo $events_count; ?></div>
                    <div class="stat-label">Events</div>
                </div>
                
                <div class="stat-card" data-aos="fade-up" data-aos-delay="500">
                    <div class="stat-number">25</div>
                    <div class="stat-label">Years of Ministry</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Leadership Section -->
    <section class="section section-light">
        <div class="container">
            <h2 class="section-title" data-aos="fade-up">Our Leadership</h2>
            <p class="section-subtitle" data-aos="fade-up" data-aos-delay="100">Dedicated servants called to lead with divine wisdom</p>
            
            <div class="leadership-grid">
                <div class="leader-card" data-aos="fade-up" data-aos-delay="200">
                    <div class="leader-image">
                        <img src="public/images/leadership/apostle-faty.jpg" alt="Apostle Faty Musasizi" onerror="this.style.display='none'; this.parentElement.innerHTML='<i class=\"fas fa-user\"></i>';">
                    </div>
                    <div class="leader-content">
                        <h3 class="leader-name">Apostle Faty Musasizi</h3>
                        <div class="leader-role">Senior Pastor & Founder</div>
                        <p class="leader-bio">
                            Apostle Faty Musasizi is the visionary founder and Senior Pastor of Salem Dominion Ministries. 
                            With over 25 years of ministry experience, he has dedicated his life to spreading the 
                            gospel and building a community of faith, hope, and love in Iganga and beyond.
                        </p>
                        <div class="leader-actions">
                            <a href="mailto:apostle@salemdominionministries.com" class="btn-leader">
                                <i class="fas fa-envelope"></i> Contact
                            </a>
                            <a href="tel:+256753244480" class="btn-leader">
                                <i class="fas fa-phone"></i> Call
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="leader-card" data-aos="fade-up" data-aos-delay="300">
                    <div class="leader-image">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="leader-content">
                        <h3 class="leader-name">Leadership Team</h3>
                        <div class="leader-role">Dedicated Ministers</div>
                        <p class="leader-bio">
                            Our leadership team consists of passionate and dedicated ministers who are committed 
                            to serving God's people with excellence and integrity. Each leader brings unique gifts 
                            and talents to advance God's kingdom.
                        </p>
                        <div class="leader-actions">
                            <a href="ministries.php" class="btn-leader">
                                <i class="fas fa-users"></i> Meet Team
                            </a>
                            <a href="contact.php" class="btn-leader">
                                <i class="fas fa-envelope"></i> Connect
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="section section-heaven">
        <div class="container">
            <h2 class="section-title" data-aos="fade-up">What People Say</h2>
            <p class="section-subtitle" data-aos="fade-up" data-aos-delay="100">Real stories of transformation and hope</p>
            
            <div class="testimonials-grid">
                <?php if ($testimonials && $testimonials->num_rows > 0): ?>
                    <?php while ($testimonial = $testimonials->fetch_assoc()): ?>
                        <div class="testimonial-card" data-aos="fade-up" data-aos-delay="200">
                            <p class="testimonial-text">
                                "<?php echo htmlspecialchars(substr($testimonial['content'], 0, 200)); ?>..."
                            </p>
                            <div class="testimonial-author">
                                <div class="author-avatar">
                                    <?php echo strtoupper(substr($testimonial['name'], 0, 1)); ?>
                                </div>
                                <div class="author-info">
                                    <h4><?php echo htmlspecialchars($testimonial['name']); ?></h4>
                                    <p>Church Member</p>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="testimonial-card" data-aos="fade-up" data-aos-delay="200">
                        <p class="testimonial-text">
                            "Salem Dominion Ministries has transformed my life completely. The teachings are powerful, 
                            the community is loving, and I've grown so much in my faith here."
                        </p>
                        <div class="testimonial-author">
                            <div class="author-avatar">J</div>
                            <div class="author-info">
                                <h4>John Member</h4>
                                <p>Church Member</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="testimonial-card" data-aos="fade-up" data-aos-delay="300">
                        <p class="testimonial-text">
                            "The worship here is heavenly! Apostle Faty Musasizi's teachings have brought 
                            so much clarity and purpose to my life. I'm blessed to be part of this family."
                        </p>
                        <div class="testimonial-author">
                            <div class="author-avatar">M</div>
                            <div class="author-info">
                                <h4>Mary Believer</h4>
                                <p>Church Member</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="testimonial-card" data-aos="fade-up" data-aos-delay="400">
                        <p class="testimonial-text">
                            "This church is truly a place where faith meets hope. The love and support I've received 
                            here has helped me through some of the toughest times in my life."
                        </p>
                        <div class="testimonial-author">
                            <div class="author-avatar">D</div>
                            <div class="author-info">
                                <h4>David Faithful</h4>
                                <p>Church Member</p>
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
            <h2 class="cta-title" data-aos="fade-up">Join Our Divine Family</h2>
            <p class="cta-subtitle" data-aos="fade-up" data-aos-delay="100">Experience the transformative power of God's love</p>
            
            <div class="cta-buttons" data-aos="fade-up" data-aos-delay="200">
                <a href="contact.php" class="btn-cta btn-primary">
                    <i class="fas fa-phone"></i> Get Connected
                </a>
                <a href="register.php" class="btn-cta btn-outline">
                    <i class="fas fa-user-plus"></i> Join Us
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
