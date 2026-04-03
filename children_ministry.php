<?php
// Include session helper and start session safely
require_once 'session_helper.php';
secure_session_start();
require_once 'db.php';

// Get children ministry events
$children_events = $db->query("SELECT * FROM events WHERE category = 'children' AND event_date >= CURDATE() ORDER BY event_date LIMIT 5");

// Get children ministry news
$children_news = $db->query("SELECT * FROM news WHERE category = 'children' ORDER BY created_at DESC LIMIT 3");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Children Ministry - Salem Dominion Ministries - Nurturing young hearts with God's love">
    <title>Children Ministry - Salem Dominion Ministries</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- AOS Animation -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;0,900;1,400&family=Montserrat:wght@100;200;300;400;500;600;700;800;900&family=Great+Vibes&display=swap" rel="stylesheet">
    
    <style>
        /* ICONIC DESIGN SYSTEM - Children Ministry */
        :root {
            --midnight-blue: #0f172a;
            --ocean-blue: #0ea5e9;
            --sky-blue: #38bdf8;
            --ice-blue: #7dd3fc;
            --snow-white: #ffffff;
            --pearl-white: #f8fafc;
            --heavenly-gold: #fbbf24;
            --divine-light: #fef3c7;
            --child-pink: #ff9a9e;
            --child-blue: #a8edea;
            --child-yellow: #fed6e3;
            --shadow-divine: 0 20px 40px rgba(15, 23, 42, 0.15);
            --shadow-soft: 0 10px 25px rgba(15, 23, 42, 0.08);
            --gradient-ocean: linear-gradient(135deg, #0f172a 0%, #0ea5e9 50%, #38bdf8 100%);
            --gradient-heaven: linear-gradient(135deg, #ffffff 0%, #f8fafc 50%, #7dd3fc 100%);
            --gradient-divine: linear-gradient(135deg, #fbbf24 0%, #fef3c7 100%);
            --gradient-children: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%);
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
        }

        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: 
                radial-gradient(circle at 20% 80%, rgba(255, 154, 158, 0.05) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(168, 237, 234, 0.05) 0%, transparent 50%);
            pointer-events: none;
            z-index: 1;
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: 'Playfair Display', serif;
            font-weight: 700;
            line-height: 1.2;
        }

        .font-divine {
            font-family: 'Great Vibes', cursive;
            color: var(--heavenly-gold);
        }

        /* Navigation */
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
        }

        .navbar-brand img {
            height: 50px;
            width: auto;
            border-radius: 50%;
            background: var(--gradient-heaven);
            padding: 8px;
            box-shadow: 0 0 30px rgba(251, 191, 36, 0.3);
        }

        .navbar-nav .nav-link {
            color: var(--midnight-blue) !important;
            font-weight: 400;
            font-size: 0.95rem;
            margin: 0 12px;
            transition: all 0.3s ease;
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

        /* Hero Section */
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
            0% { transform: translateY(100vh) translateX(0); opacity: 0; }
            10% { opacity: 0.6; }
            90% { opacity: 0.6; }
            100% { transform: translateY(-100vh) translateX(100px); opacity: 0; }
        }

        .hero-content {
            position: relative;
            z-index: 10;
            text-align: center;
            color: var(--snow-white);
            max-width: 800px;
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
        }

        .hero-title {
            font-size: clamp(2.5rem, 6vw, 4rem);
            font-weight: 900;
            margin-bottom: 1.5rem;
            text-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
        }

        .hero-subtitle {
            font-family: 'Great Vibes', cursive;
            font-size: clamp(1.8rem, 4vw, 2.5rem);
            opacity: 0.95;
        }

        /* Fun Elements */
        .fun-element {
            position: absolute;
            font-size: 3rem;
            opacity: 0.15;
            z-index: 0;
            animation: floatElement 6s ease-in-out infinite;
        }

        .fun-element:nth-child(1) { top: 10%; left: 10%; color: #ff6b6b; animation-delay: 0s; }
        .fun-element:nth-child(2) { top: 20%; right: 15%; color: #4ecdc4; animation-delay: 1s; }
        .fun-element:nth-child(3) { bottom: 15%; left: 20%; color: #ffd93d; animation-delay: 2s; }
        .fun-element:nth-child(4) { bottom: 10%; right: 10%; color: #a8e6cf; animation-delay: 3s; }
        .fun-element:nth-child(5) { top: 50%; left: 5%; color: #ff9a9e; animation-delay: 4s; }
        .fun-element:nth-child(6) { top: 30%; right: 5%; color: #fed6e3; animation-delay: 5s; }

        @keyframes floatElement {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(10deg); }
        }

        /* Sections */
        .section {
            padding: 80px 0;
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
            font-size: clamp(2rem, 5vw, 3rem);
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
            font-size: 1.1rem;
            margin-bottom: 3rem;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
            color: var(--ocean-blue);
        }

        /* Age Group Cards */
        .age-group-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            margin-top: 3rem;
        }

        .age-group-card {
            background: var(--gradient-children);
            border-radius: 20px;
            padding: 2rem;
            text-align: center;
            color: white;
            transition: all 0.5s ease;
            box-shadow: var(--shadow-soft);
        }

        .age-group-card:hover {
            transform: translateY(-10px);
            box-shadow: var(--shadow-divine);
        }

        .age-group-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
            opacity: 0.9;
        }

        .age-group-title {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .age-group-age {
            font-size: 1.1rem;
            font-weight: 600;
            opacity: 0.9;
            margin-bottom: 1rem;
        }

        .age-group-desc {
            font-size: 0.95rem;
            line-height: 1.6;
            opacity: 0.95;
        }

        /* Activity Cards */
        .activity-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-top: 3rem;
        }

        .activity-card {
            background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
            border-radius: 20px;
            padding: 2rem;
            text-align: center;
            transition: all 0.5s ease;
            box-shadow: var(--shadow-soft);
        }

        .activity-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-divine);
        }

        .activity-icon {
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }

        .activity-title {
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--midnight-blue);
            margin-bottom: 0.5rem;
        }

        .activity-desc {
            font-size: 0.95rem;
            color: var(--midnight-blue);
            line-height: 1.6;
        }

        /* Gallery Cards */
        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-top: 3rem;
        }

        .gallery-card {
            background: var(--snow-white);
            border-radius: 20px;
            overflow: hidden;
            box-shadow: var(--shadow-soft);
            transition: all 0.5s ease;
        }

        .gallery-card:hover {
            transform: translateY(-10px);
            box-shadow: var(--shadow-divine);
        }

        .gallery-image {
            height: 250px;
            overflow: hidden;
            position: relative;
        }

        .gallery-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .gallery-card:hover .gallery-image img {
            transform: scale(1.1);
        }

        .gallery-content {
            padding: 1.5rem;
        }

        .gallery-title {
            font-size: 1.2rem;
            font-weight: 700;
            color: var(--midnight-blue);
            margin-bottom: 0.5rem;
        }

        .gallery-desc {
            font-size: 0.95rem;
            color: var(--ocean-blue);
            line-height: 1.5;
        }

        /* Events & News Cards */
        .events-news-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
            gap: 2rem;
            margin-top: 3rem;
        }

        .info-card {
            background: var(--snow-white);
            border-radius: 20px;
            padding: 2rem;
            box-shadow: var(--shadow-soft);
        }

        .info-card-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--midnight-blue);
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .info-card-title i {
            color: var(--ocean-blue);
        }

        .event-item {
            padding: 1rem;
            background: var(--pearl-white);
            border-radius: 15px;
            margin-bottom: 1rem;
            transition: all 0.3s ease;
            text-decoration: none;
            color: var(--midnight-blue);
            display: block;
        }

        .event-item:hover {
            background: var(--ice-blue);
            transform: translateX(5px);
        }

        .event-item h6 {
            font-weight: 600;
            margin-bottom: 0.25rem;
        }

        .event-item small {
            color: var(--heavenly-gold);
            font-weight: 600;
        }

        .event-item p {
            font-size: 0.9rem;
            color: var(--ocean-blue);
            margin-bottom: 0;
        }

        /* CTA Section */
        .cta-section {
            background: var(--gradient-ocean);
            padding: 80px 0;
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
            margin-bottom: 1.5rem;
            text-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
        }

        .cta-subtitle {
            font-size: 1.2rem;
            margin-bottom: 2rem;
            opacity: 0.95;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        .cta-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn-cta {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 15px 30px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 1rem;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .btn-cta-primary {
            background: var(--snow-white);
            color: var(--midnight-blue);
        }

        .btn-cta-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(255, 255, 255, 0.3);
            color: var(--midnight-blue);
        }

        .btn-cta-outline {
            background: transparent;
            color: var(--snow-white);
            border: 2px solid var(--snow-white);
        }

        .btn-cta-outline:hover {
            background: var(--snow-white);
            color: var(--midnight-blue);
            transform: translateY(-3px);
        }

        /* Donate Button */
        .donate-btn-fixed {
            position: fixed;
            bottom: 30px;
            left: 50%;
            transform: translateX(-50%);
            background: linear-gradient(135deg, #ff3535, #ff6b35);
            color: white;
            padding: 15px 30px;
            border-radius: 50px;
            font-weight: 600;
            text-decoration: none;
            z-index: 1000;
            box-shadow: 0 10px 30px rgba(255, 100, 50, 0.4);
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            animation: donatePulse 2s ease-in-out infinite;
        }

        @keyframes donatePulse {
            0%, 100% { transform: translateX(-50%) scale(1); }
            50% { transform: translateX(-50%) scale(1.05); }
        }

        .donate-btn-fixed:hover {
            transform: translateX(-50%) scale(1.1);
            box-shadow: 0 15px 40px rgba(255, 100, 50, 0.5);
            color: white;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .section {
                padding: 60px 0;
            }

            .hero-title {
                font-size: 2.5rem;
            }

            .hero-subtitle {
                font-size: 1.8rem;
            }

            .events-news-grid {
                grid-template-columns: 1fr;
            }

            .cta-buttons {
                flex-direction: column;
                align-items: center;
            }

            .btn-cta {
                width: 250px;
                justify-content: center;
            }

            .donate-btn-fixed {
                bottom: 20px;
                padding: 12px 20px;
                font-size: 0.9rem;
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
                        <a class="nav-link" href="donate.php" style="color: var(--heavenly-gold) !important; font-weight: 600;">
                            <i class="fas fa-heart me-1"></i> Donate
                        </a>
                    </li>
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
        <div class="hero-particles" id="heroParticles"></div>
        <div class="hero-content" data-aos="fade-up">
            <div class="hero-logo">
                <img src="assets/logo-DEFqnQ4s.jpeg" alt="Salem Dominion Ministries">
            </div>
            <h1 class="hero-title">Children Ministry</h1>
            <p class="hero-subtitle">"Let the little children come to me" - Matthew 19:14</p>
        </div>
        <!-- Fun Elements -->
        <i class="fas fa-child fun-element"></i>
        <i class="fas fa-heart fun-element"></i>
        <i class="fas fa-star fun-element"></i>
        <i class="fas fa-smile fun-element"></i>
        <i class="fas fa-balloon fun-element"></i>
        <i class="fas fa-rainbow fun-element"></i>
    </section>

    <!-- Ministry Overview -->
    <section class="section section-light">
        <div class="container">
            <h2 class="section-title" data-aos="fade-up">Welcome to Our Children Ministry</h2>
            <p class="section-subtitle" data-aos="fade-up">Nurturing young hearts with God's love</p>
            
            <div class="row justify-content-center" data-aos="fade-up">
                <div class="col-lg-8 text-center">
                    <p class="lead" style="font-size: 1.1rem; line-height: 1.8; color: var(--ocean-blue);">
                        At Salem Dominion Ministries, we believe that children are the future of our church and community.
                        Our Children Ministry is dedicated to nurturing young hearts with God's love, teaching biblical
                        truths in fun and engaging ways, and helping children grow in their relationship with Jesus Christ.
                    </p>
                </div>
            </div>

            <!-- Age Groups -->
            <h3 class="section-title" data-aos="fade-up" style="margin-top: 4rem;">Age Groups</h3>
            <div class="age-group-grid">
                <div class="age-group-card" data-aos="fade-up" data-aos-delay="100">
                    <div class="age-group-icon"><i class="fas fa-baby"></i></div>
                    <h4 class="age-group-title">Nursery</h4>
                    <p class="age-group-age">Ages 0-2</p>
                    <p class="age-group-desc">Caring environment for our youngest members with loving supervision and age-appropriate activities.</p>
                </div>
                <div class="age-group-card" data-aos="fade-up" data-aos-delay="200">
                    <div class="age-group-icon"><i class="fas fa-child"></i></div>
                    <h4 class="age-group-title">Toddlers</h4>
                    <p class="age-group-age">Ages 3-5</p>
                    <p class="age-group-desc">Fun learning experiences, songs, stories, and crafts that introduce children to God's love.</p>
                </div>
                <div class="age-group-card" data-aos="fade-up" data-aos-delay="300">
                    <div class="age-group-icon"><i class="fas fa-school"></i></div>
                    <h4 class="age-group-title">Elementary</h4>
                    <p class="age-group-age">Ages 6-11</p>
                    <p class="age-group-desc">Biblical teaching, memory verses, worship, and activities that help children grow spiritually.</p>
                </div>
                <div class="age-group-card" data-aos="fade-up" data-aos-delay="400">
                    <div class="age-group-icon"><i class="fas fa-users"></i></div>
                    <h4 class="age-group-title">Pre-Teens</h4>
                    <p class="age-group-age">Ages 12-14</p>
                    <p class="age-group-desc">Preparing for youth ministry with deeper biblical study, leadership development, and service opportunities.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Programs & Activities -->
    <section class="section section-heaven">
        <div class="container">
            <h2 class="section-title" data-aos="fade-up">Our Programs & Activities</h2>
            <p class="section-subtitle" data-aos="fade-up">Fun and faith-filled experiences for every child</p>
            
            <div class="activity-grid">
                <div class="activity-card" data-aos="fade-up" data-aos-delay="100">
                    <div class="activity-icon"><i class="fas fa-bible" style="color: #6366f1;"></i></div>
                    <h4 class="activity-title">Sunday School</h4>
                    <p class="activity-desc">Weekly Bible lessons, stories, and activities designed specifically for each age group during our Sunday morning service.</p>
                </div>
                <div class="activity-card" data-aos="fade-up" data-aos-delay="200">
                    <div class="activity-icon"><i class="fas fa-music" style="color: #10b981;"></i></div>
                    <h4 class="activity-title">Children's Choir</h4>
                    <p class="activity-desc">Learning worship songs, hymns, and developing musical talents while praising God together as a group.</p>
                </div>
                <div class="activity-card" data-aos="fade-up" data-aos-delay="300">
                    <div class="activity-icon"><i class="fas fa-hands-helping" style="color: #f59e0b;"></i></div>
                    <h4 class="activity-title">Community Service</h4>
                    <p class="activity-desc">Teaching children the importance of serving others through food drives, visiting nursing homes, and helping those in need.</p>
                </div>
                <div class="activity-card" data-aos="fade-up" data-aos-delay="400">
                    <div class="activity-icon"><i class="fas fa-praying-hands" style="color: #3b82f6;"></i></div>
                    <h4 class="activity-title">Prayer Time</h4>
                    <p class="activity-desc">Dedicated time for children to learn about prayer, share prayer requests, and experience the power of talking to God.</p>
                </div>
                <div class="activity-card" data-aos="fade-up" data-aos-delay="500">
                    <div class="activity-icon"><i class="fas fa-palette" style="color: #ef4444;"></i></div>
                    <h4 class="activity-title">Crafts & Arts</h4>
                    <p class="activity-desc">Creative activities that reinforce Bible lessons and help children express their faith through art and crafts.</p>
                </div>
                <div class="activity-card" data-aos="fade-up" data-aos-delay="600">
                    <div class="activity-icon"><i class="fas fa-running" style="color: #6b7280;"></i></div>
                    <h4 class="activity-title">Games & Sports</h4>
                    <p class="activity-desc">Physical activities and games that promote teamwork, healthy living, and having fun while learning about God.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Children's Gallery -->
    <section class="section section-light">
        <div class="container">
            <h2 class="section-title" data-aos="fade-up">Our Children in Action</h2>
            <p class="section-subtitle" data-aos="fade-up">Capturing precious moments of faith and joy</p>
            
            <div class="gallery-grid">
                <div class="gallery-card" data-aos="fade-up" data-aos-delay="100">
                    <div class="gallery-image">
                        <img src="assets/children-celebrating-Z18oVWUU.jpeg" alt="Children Celebrating">
                    </div>
                    <div class="gallery-content">
                        <h4 class="gallery-title">Children's Celebration</h4>
                        <p class="gallery-desc">Our children praising God with joyful hearts and beautiful voices.</p>
                    </div>
                </div>
                <div class="gallery-card" data-aos="fade-up" data-aos-delay="200">
                    <div class="gallery-image">
                        <img src="assets/children-with-books-Cc2LmxDu.jpeg" alt="Bible Study">
                    </div>
                    <div class="gallery-content">
                        <h4 class="gallery-title">Bible Learning</h4>
                        <p class="gallery-desc">Children learning God's Word through engaging stories and activities.</p>
                    </div>
                </div>
                <div class="gallery-card" data-aos="fade-up" data-aos-delay="300">
                    <div class="gallery-image">
                        <img src="assets/children-eating-withpastor-Bagnofdx.jpeg" alt="Children with Pastor">
                    </div>
                    <div class="gallery-content">
                        <h4 class="gallery-title">Fellowship Time</h4>
                        <p class="gallery-desc">Building relationships and sharing meals together in Christian community.</p>
                    </div>
                </div>
                <div class="gallery-card" data-aos="fade-up" data-aos-delay="400">
                    <div class="gallery-image">
                        <img src="assets/a-kid-showing-how-kindness-isgood-BBxs16el.jpeg" alt="Teaching Kindness">
                    </div>
                    <div class="gallery-content">
                        <h4 class="gallery-title">Learning Kindness</h4>
                        <p class="gallery-desc">Teaching children to show love and kindness to others.</p>
                    </div>
                </div>
                <div class="gallery-card" data-aos="fade-up" data-aos-delay="500">
                    <div class="gallery-image">
                        <img src="assets/children-food-20X1VRUx.jpeg" alt="Children's Outreach">
                    </div>
                    <div class="gallery-content">
                        <h4 class="gallery-title">Community Outreach</h4>
                        <p class="gallery-desc">Children serving others and learning the joy of giving.</p>
                    </div>
                </div>
                <div class="gallery-card" data-aos="fade-up" data-aos-delay="600">
                    <div class="gallery-image">
                        <img src="assets/support-children-now-Dqa2JhXn.jpeg" alt="Support Children">
                    </div>
                    <div class="gallery-content">
                        <h4 class="gallery-title">Support Our Children</h4>
                        <p class="gallery-desc">Help us nurture the next generation of faithful believers.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Upcoming Events & News -->
    <section class="section section-heaven">
        <div class="container">
            <h2 class="section-title" data-aos="fade-up">What's Happening</h2>
            <p class="section-subtitle" data-aos="fade-up">Stay updated with children's ministry news and events</p>
            
            <div class="events-news-grid">
                <!-- Upcoming Events -->
                <div class="info-card" data-aos="fade-up">
                    <h3 class="info-card-title">
                        <i class="fas fa-calendar-alt"></i> Upcoming Events
                    </h3>
                    <?php if ($children_events && $children_events->num_rows > 0): ?>
                        <?php while ($event = $children_events->fetch_assoc()): ?>
                            <a href="events.php" class="event-item">
                                <h6><?php echo htmlspecialchars($event['title']); ?></h6>
                                <small><i class="fas fa-calendar"></i> <?php echo date('M j, Y', strtotime($event['event_date'])); ?></small>
                                <p><?php echo htmlspecialchars(substr($event['description'], 0, 100)); ?>...</p>
                            </a>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <p class="text-muted">No upcoming children's events scheduled. Check back soon!</p>
                    <?php endif; ?>
                </div>

                <!-- Latest News -->
                <div class="info-card" data-aos="fade-up" data-aos-delay="100">
                    <h3 class="info-card-title">
                        <i class="fas fa-newspaper"></i> Latest News
                    </h3>
                    <?php if ($children_news && $children_news->num_rows > 0): ?>
                        <?php while ($news = $children_news->fetch_assoc()): ?>
                            <div class="event-item">
                                <h6><a href="news.php" class="text-decoration-none" style="color: inherit;"><?php echo htmlspecialchars($news['title']); ?></a></h6>
                                <small><i class="fas fa-clock"></i> <?php echo date('M j, Y', strtotime($news['created_at'])); ?></small>
                                <p><?php echo htmlspecialchars(substr($news['content'], 0, 120)); ?>...</p>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <p class="text-muted">No recent news from the children's ministry.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action -->
    <section class="cta-section">
        <div class="cta-content">
            <h2 class="cta-title" data-aos="fade-up">Get Your Children Involved!</h2>
            <p class="cta-subtitle" data-aos="fade-up">We'd love to welcome your children to our ministry. Contact us to learn more about how they can join in the fun and grow in their faith.</p>
            <div class="cta-buttons" data-aos="fade-up">
                <a href="contact.php" class="btn-cta btn-cta-primary">
                    <i class="fas fa-envelope"></i> Contact Us
                </a>
                <a href="donate.php" class="btn-cta btn-cta-outline">
                    <i class="fas fa-heart"></i> Support Children
                </a>
            </div>
        </div>
    </section>

    <!-- Fixed Donate Button -->
    <a href="donate.php" class="donate-btn-fixed">
        <i class="fas fa-heart"></i> Donate to Children's Ministry
    </a>

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

        // Create particles
        function createParticles() {
            const container = document.getElementById('heroParticles');
            for (let i = 0; i < 15; i++) {
                const particle = document.createElement('div');
                particle.className = 'particle';
                particle.style.left = Math.random() * 100 + '%';
                particle.style.animationDelay = Math.random() * 20 + 's';
                particle.style.animationDuration = (15 + Math.random() * 10) + 's';
                container.appendChild(particle);
            }
        }
        createParticles();

        // Navbar scroll effect
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar');
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });
    </script>
</body>
</html>