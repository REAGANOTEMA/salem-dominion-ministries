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
    <meta name="description" content="Salem Dominion Ministries - Divine Worship Experience with Apostle Faty Musasizi">
    <meta name="keywords" content="church, divine worship, Apostle Faty Musasizi, Iganga, Uganda, spiritual">
    
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
            min-height: 100vh;
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
            margin-bottom: 3rem;
            animation: logoFloat 8s ease-in-out infinite;
        }

        @keyframes logoFloat {
            0%, 100% { transform: translateY(0) scale(1); }
            50% { transform: translateY(-20px) scale(1.05); }
        }

        .hero-logo img {
            height: 150px;
            width: auto;
            border-radius: 50%;
            background: var(--snow-white);
            padding: 20px;
            box-shadow: 0 0 60px rgba(251, 191, 36, 0.4);
            transition: all 0.5s ease;
        }

        .hero-logo:hover img {
            transform: scale(1.1) rotate(10deg);
            box-shadow: 0 0 80px rgba(251, 191, 36, 0.6);
        }

        .hero-title {
            font-family: 'Playfair Display', serif;
            font-size: clamp(3rem, 8vw, 5rem);
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
            font-size: clamp(2rem, 5vw, 3rem);
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

        /* Bible Verse - Iconic Display */
        .bible-verse-container {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) scale(0);
            background: rgba(15, 23, 42, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 30px;
            padding: 3rem;
            max-width: 600px;
            width: 90%;
            z-index: 10000;
            opacity: 0;
            transition: all 0.8s cubic-bezier(0.4, 0, 0.2, 1);
            border: 2px solid var(--heavenly-gold);
            box-shadow: var(--shadow-heavenly);
            text-align: center;
        }

        .bible-verse-container.show {
            transform: translate(-50%, -50%) scale(1);
            opacity: 1;
        }

        .bible-verse-text {
            font-family: 'Playfair Display', serif;
            font-size: 1.8rem;
            font-style: italic;
            color: var(--snow-white);
            margin-bottom: 1.5rem;
            line-height: 1.6;
            position: relative;
        }

        .bible-verse-text::before,
        .bible-verse-text::after {
            content: '✞';
            position: absolute;
            color: var(--heavenly-gold);
            font-size: 2rem;
            opacity: 0.8;
        }

        .bible-verse-text::before {
            top: -2rem;
            left: -2rem;
        }

        .bible-verse-text::after {
            bottom: -2rem;
            right: -2rem;
        }

        .bible-verse-reference {
            font-family: 'Montserrat', sans-serif;
            font-size: 1.2rem;
            color: var(--heavenly-gold);
            font-weight: 600;
            letter-spacing: 0.1em;
        }

        .close-verse {
            position: absolute;
            top: 1rem;
            right: 1rem;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid var(--heavenly-gold);
            color: var(--snow-white);
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .close-verse:hover {
            background: var(--heavenly-gold);
            color: var(--midnight-blue);
            transform: rotate(90deg);
        }

        /* Hero Actions */
        .hero-actions {
            display: flex;
            gap: 1.5rem;
            justify-content: center;
            flex-wrap: wrap;
            margin-top: 3rem;
        }

        .btn-hero {
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

        .btn-hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.2);
            transition: all 0.4s ease;
        }

        .btn-hero:hover::before {
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
            color: var(--midnight-blue);
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
            color: var(--ocean-blue);
            margin-bottom: 4rem;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
            font-weight: 300;
        }

        /* Service Cards - Iconic */
        .service-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-top: 4rem;
        }

        .service-card {
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

        .service-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 6px;
            background: var(--gradient-ocean);
        }

        .service-card:hover {
            transform: translateY(-15px);
            box-shadow: var(--shadow-divine);
            border-color: var(--ocean-blue);
        }

        .service-icon {
            width: 90px;
            height: 90px;
            background: var(--gradient-ocean);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 2rem;
            color: var(--snow-white);
            font-size: 2.5rem;
            box-shadow: 0 15px 35px rgba(14, 165, 233, 0.3);
            transition: all 0.5s ease;
            position: relative;
        }

        .service-icon::before {
            content: '';
            position: absolute;
            top: -5px;
            left: -5px;
            right: -5px;
            bottom: -5px;
            background: var(--gradient-divine);
            border-radius: 50%;
            z-index: -1;
            opacity: 0;
            transition: opacity 0.5s ease;
        }

        .service-card:hover .service-icon::before {
            opacity: 1;
        }

        .service-card:hover .service-icon {
            transform: scale(1.1) rotate(10deg);
            box-shadow: 0 20px 45px rgba(14, 165, 233, 0.4);
        }

        .service-time {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--midnight-blue);
            margin-bottom: 0.5rem;
            font-family: 'Playfair Display', serif;
        }

        .service-day {
            color: var(--ocean-blue);
            font-weight: 500;
            font-size: 1.1rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        /* Ministries - Iconic Grid */
        .ministry-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 2.5rem;
            margin-top: 4rem;
        }

        .ministry-card {
            background: var(--snow-white);
            border-radius: 25px;
            overflow: hidden;
            box-shadow: var(--shadow-soft);
            border: 1px solid rgba(125, 211, 252, 0.2);
            transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
        }

        .ministry-card:hover {
            transform: translateY(-10px);
            box-shadow: var(--shadow-divine);
        }

        .ministry-image {
            height: 250px;
            background: var(--gradient-ocean);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--snow-white);
            font-size: 4rem;
            position: relative;
            overflow: hidden;
        }

        .ministry-image::before {
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

        .ministry-card:hover .ministry-image::before {
            transform: translateX(100%);
        }

        .ministry-content {
            padding: 2.5rem;
        }

        .ministry-title {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--midnight-blue);
            margin-bottom: 1.5rem;
            font-family: 'Playfair Display', serif;
        }

        .ministry-description {
            color: var(--ocean-blue);
            margin-bottom: 2rem;
            line-height: 1.7;
            font-weight: 300;
        }

        .btn-ministry {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 30px;
            background: var(--gradient-ocean);
            color: var(--snow-white);
            border-radius: 50px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
            border: none;
            font-size: 1rem;
        }

        .btn-ministry:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(14, 165, 233, 0.3);
            color: var(--snow-white);
        }

        /* Contact Section - Iconic */
        .contact-section {
            background: var(--gradient-heaven);
            padding: 100px 0;
        }

        .contact-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .contact-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 4rem;
            align-items: center;
        }

        .contact-info {
            background: var(--snow-white);
            border-radius: 30px;
            padding: 3rem;
            box-shadow: var(--shadow-divine);
        }

        .contact-item {
            display: flex;
            align-items: center;
            gap: 1.5rem;
            margin-bottom: 2.5rem;
        }

        .contact-icon {
            width: 60px;
            height: 60px;
            background: var(--gradient-ocean);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--snow-white);
            font-size: 1.5rem;
            flex-shrink: 0;
            box-shadow: 0 10px 25px rgba(14, 165, 233, 0.3);
        }

        .contact-details h4 {
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--midnight-blue);
            margin-bottom: 0.5rem;
        }

        .contact-details p {
            color: var(--ocean-blue);
            font-size: 1.1rem;
            margin-bottom: 0;
        }

        .map-container {
            background: var(--snow-white);
            border-radius: 30px;
            overflow: hidden;
            box-shadow: var(--shadow-divine);
            height: 500px;
            position: relative;
        }

        .map-placeholder {
            width: 100%;
            height: 100%;
            background: var(--gradient-heaven);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: var(--midnight-blue);
            text-align: center;
            padding: 2rem;
        }

        .map-placeholder i {
            font-size: 4rem;
            color: var(--ocean-blue);
            margin-bottom: 1.5rem;
        }

        /* Footer - Iconic */
        .footer {
            background: var(--midnight-blue);
            color: var(--snow-white);
            padding: 4rem 0 2rem;
            position: relative;
        }

        .footer::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 6px;
            background: var(--gradient-divine);
        }

        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            text-align: center;
        }

        .footer-logo {
            margin-bottom: 2rem;
        }

        .footer-logo img {
            height: 80px;
            border-radius: 50%;
            background: var(--snow-white);
            padding: 15px;
            margin-bottom: 1.5rem;
            box-shadow: 0 0 40px rgba(251, 191, 36, 0.4);
        }

        .footer-church-name {
            font-family: 'Great Vibes', cursive;
            font-size: 2.5rem;
            color: var(--heavenly-gold);
            margin-bottom: 0.5rem;
        }

        .footer-tagline {
            font-size: 1.1rem;
            opacity: 0.8;
            margin-bottom: 2rem;
        }

        .footer-links {
            display: flex;
            justify-content: center;
            gap: 2rem;
            margin-bottom: 2rem;
            flex-wrap: wrap;
        }

        .footer-links a {
            color: var(--snow-white);
            text-decoration: none;
            font-weight: 400;
            transition: all 0.3s ease;
            position: relative;
        }

        .footer-links a:hover {
            color: var(--heavenly-gold);
            transform: translateY(-2px);
        }

        .footer-contact {
            margin-bottom: 2rem;
        }

        .footer-contact p {
            margin-bottom: 0.5rem;
            opacity: 0.9;
        }

        .footer-copyright {
            padding-top: 2rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            opacity: 0.7;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .hero {
                min-height: 100vh;
                padding: 2rem 0;
            }

            .hero-title {
                font-size: 3rem;
            }

            .hero-subtitle {
                font-size: 2rem;
            }

            .hero-actions {
                flex-direction: column;
                align-items: center;
            }

            .btn-hero {
                width: 250px;
                justify-content: center;
            }

            .section {
                padding: 60px 0;
            }

            .service-grid,
            .ministry-grid {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }

            .contact-grid {
                grid-template-columns: 1fr;
                gap: 2rem;
            }

            .footer-links {
                flex-direction: column;
                gap: 1rem;
            }

            .bible-verse-container {
                padding: 2rem;
                margin: 1rem;
            }

            .bible-verse-text {
                font-size: 1.4rem;
            }
        }

        /* Loading Animation */
        .loader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: var(--midnight-blue);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 99999;
            transition: opacity 0.5s ease;
        }

        .loader.hidden {
            opacity: 0;
            pointer-events: none;
        }

        .loader-content {
            text-align: center;
            color: var(--snow-white);
        }

        .loader-logo {
            width: 80px;
            height: 80px;
            border: 3px solid var(--heavenly-gold);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 2rem;
            animation: spin 2s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .loader-logo i {
            font-size: 2rem;
            color: var(--heavenly-gold);
        }
    </style>
</head>
<body>
    <!-- Loading Screen -->
    <div class="loader" id="loader">
        <div class="loader-content">
            <div class="loader-logo">
                <i class="fas fa-church"></i>
            </div>
            <h3 class="font-divine">Salem Dominion Ministries</h3>
            <p>Preparing your divine experience...</p>
        </div>
    </div>

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
                    <li class="nav-item">
                        <a class="nav-link" href="login.php" style="color: var(--heavenly-gold) !important; font-weight: 600;">
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
            <!-- Newsletter Success/Error Messages -->
            <?php if (isset($_GET['newsletter_success'])): ?>
                <div class="alert alert-success alert-dismissible fade show position-fixed" style="top: 100px; right: 20px; z-index: 9999; max-width: 350px;" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    <?php echo htmlspecialchars($_GET['newsletter_success']); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            
            <?php if (isset($_GET['newsletter_error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show position-fixed" style="top: 100px; right: 20px; z-index: 9999; max-width: 350px;" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <?php echo htmlspecialchars($_GET['newsletter_error']); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            
            <div class="hero-logo">
                <img src="public/images/logo.png" alt="Salem Dominion Ministries">
            </div>
            <h1 class="hero-title">Salem Dominion Ministries</h1>
            <p class="hero-subtitle">Where Faith Meets Divine Purpose</p>
            
            <div class="hero-actions">
                <a href="about.php" class="btn-hero btn-primary">
                    <i class="fas fa-church"></i> Discover Ministry
                </a>
                <a href="contact.php" class="btn-hero btn-outline">
                    <i class="fas fa-phone"></i> Connect With Us
                </a>
                <a href="login.php" class="btn-hero btn-primary">
                    <i class="fas fa-sign-in-alt"></i> Member Login
                </a>
            </div>
        </div>
    </section>

    <!-- Bible Verse Container (Hidden by default) -->
    <div class="bible-verse-container" id="bibleVerseContainer">
        <span class="close-verse" onclick="closeBibleVerse()">×</span>
        <div class="bible-verse-text" id="bibleVerseText">
            Loading divine wisdom...
        </div>
        <div class="bible-verse-reference" id="bibleVerseReference">
            - Scripture
        </div>
    </div>

    <!-- Service Times Section -->
    <section class="section section-light">
        <div class="container">
            <h2 class="section-title" data-aos="fade-up">Divine Services</h2>
            <p class="section-subtitle" data-aos="fade-up" data-aos-delay="100">Join us in worship and fellowship</p>
            
            <div class="service-grid">
                <?php if ($services && $services->num_rows > 0): ?>
                    <?php while ($service = $services->fetch_assoc()): ?>
                        <div class="service-card" data-aos="fade-up" data-aos-delay="200">
                            <div class="service-icon">
                                <i class="fas fa-cross"></i>
                            </div>
                            <div class="service-time">
                                <?php echo date('g:i A', strtotime($service['start_time'])); ?> - 
                                <?php echo date('g:i A', strtotime($service['end_time'])); ?>
                            </div>
                            <div class="service-day"><?php echo ucfirst($service['day_of_week']); ?></div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="service-card" data-aos="fade-up" data-aos-delay="200">
                        <div class="service-icon">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                        <div class="service-time">10:00 AM - 12:00 PM</div>
                        <div class="service-day">Sunday Service</div>
                    </div>
                    <div class="service-card" data-aos="fade-up" data-aos-delay="300">
                        <div class="service-icon">
                            <i class="fas fa-praying-hands"></i>
                        </div>
                        <div class="service-time">6:00 PM - 7:30 PM</div>
                        <div class="service-day">Wednesday Prayer</div>
                    </div>
                    <div class="service-card" data-aos="fade-up" data-aos-delay="400">
                        <div class="service-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="service-time">5:00 PM - 6:00 PM</div>
                        <div class="service-day">Friday Fellowship</div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Ministries Section -->
    <section class="section section-heaven">
        <div class="container">
            <h2 class="section-title" data-aos="fade-up">Our Ministries</h2>
            <p class="section-subtitle" data-aos="fade-up" data-aos-delay="100">Discover your divine calling</p>
            
            <div class="ministry-grid">
                <?php if ($ministries && $ministries->num_rows > 0): ?>
                    <?php while ($ministry = $ministries->fetch_assoc()): ?>
                        <div class="ministry-card" data-aos="fade-up" data-aos-delay="200">
                            <div class="ministry-image">
                                <i class="fas fa-hands-helping"></i>
                            </div>
                            <div class="ministry-content">
                                <h3 class="ministry-title"><?php echo safe_html($ministry['name']); ?></h3>
                                <p class="ministry-description"><?php echo safe_html(substr($ministry['description'], 0, 150)); ?>...</p>
                                <a href="ministries.php" class="btn-ministry">Explore Ministry</a>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="ministry-card" data-aos="fade-up" data-aos-delay="200">
                        <div class="ministry-image">
                            <i class="fas fa-child"></i>
                        </div>
                        <div class="ministry-content">
                            <h3 class="ministry-title">Children Ministry</h3>
                            <p class="ministry-description">Nurturing young hearts with God's love through age-appropriate Bible teaching and fun activities.</p>
                            <a href="children_ministry.php" class="btn-ministry">Explore Ministry</a>
                        </div>
                    </div>
                    <div class="ministry-card" data-aos="fade-up" data-aos-delay="300">
                        <div class="ministry-image">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="ministry-content">
                            <h3 class="ministry-title">Youth Ministry</h3>
                            <p class="ministry-description">Empowering teenagers to grow in faith and develop leadership skills through various activities.</p>
                            <a href="ministries.php" class="btn-ministry">Explore Ministry</a>
                        </div>
                    </div>
                    <div class="ministry-card" data-aos="fade-up" data-aos-delay="400">
                        <div class="ministry-image">
                            <i class="fas fa-music"></i>
                        </div>
                        <div class="ministry-content">
                            <h3 class="ministry-title">Worship Ministry</h3>
                            <p class="ministry-description">Leading our congregation in worship through music, song, and praise to glorify God.</p>
                            <a href="ministries.php" class="btn-ministry">Explore Ministry</a>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section class="contact-section">
        <div class="contact-container">
            <h2 class="section-title" data-aos="fade-up">Visit Us</h2>
            <p class="section-subtitle" data-aos="fade-up" data-aos-delay="100">Find us in Iganga, Uganda</p>
            
            <div class="contact-grid">
                <div class="contact-info" data-aos="fade-right" data-aos-delay="200">
                    <div class="contact-item">
                        <div class="contact-icon">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <div class="contact-details">
                            <h4>Location</h4>
                            <p>Main Street, Iganga Town</p>
                            <p>Near Iganga Market, Uganda</p>
                        </div>
                    </div>
                    
                    <div class="contact-item">
                        <div class="contact-icon">
                            <i class="fas fa-phone"></i>
                        </div>
                        <div class="contact-details">
                            <h4>Contact</h4>
                            <p>+256753244480</p>
                            <p>Available for prayer and counseling</p>
                        </div>
                    </div>
                    
                    <div class="contact-item">
                        <div class="contact-icon">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div class="contact-details">
                            <h4>Email</h4>
                            <p>apostle@salemdominionministries.com</p>
                            <p>Senior Pastor: Apostle Faty Musasizi</p>
                        </div>
                    </div>
                </div>
                
                <div class="map-container" data-aos="fade-left" data-aos-delay="300">
                    <div class="map-placeholder">
                        <i class="fas fa-map-marked-alt"></i>
                        <h3>Salem Dominion Ministries</h3>
                        <p>Main Street, Iganga Town, Uganda</p>
                        <a href="map.php" class="btn-ministry">View Interactive Map</a>
                    </div>
                </div>
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

        // Hide loader when page is loaded
        window.addEventListener('load', function() {
            setTimeout(function() {
                document.getElementById('loader').classList.add('hidden');
            }, 1500);
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

        // Create divine particles
        function createParticles() {
            const particlesContainer = document.getElementById('heroParticles');
            const particleCount = 20;
            
            for (let i = 0; i < particleCount; i++) {
                const particle = document.createElement('div');
                particle.className = 'particle';
                particle.style.left = Math.random() * 100 + '%';
                particle.style.animationDelay = Math.random() * 20 + 's';
                particle.style.animationDuration = (15 + Math.random() * 10) + 's';
                particlesContainer.appendChild(particle);
            }
        }

        // Bible verses for display
        const bibleVerses = [
            { text: "For I know the thoughts that I think toward you, says the Lord, thoughts of peace and not of evil, to give you a future and a hope.", reference: "Jeremiah 29:11" },
            { text: "Come to me, all you who are weary and burdened, and I will give you rest.", reference: "Matthew 11:28" },
            { text: "For where two or three gather in my name, there am I with them.", reference: "Matthew 18:20" },
            { text: "Be strong and courageous. Do not be afraid; do not be discouraged, for the Lord your God will be with you wherever you go.", reference: "Joshua 1:9" },
            { text: "The Lord is my shepherd; I shall not want.", reference: "Psalm 23:1" },
            { text: "I can do all things through Christ who strengthens me.", reference: "Philippians 4:13" },
            { text: "Trust in the Lord with all your heart and lean not on your own understanding.", reference: "Proverbs 3:5" },
            { text: "The Lord your God is with you, the Mighty Warrior who saves.", reference: "Zephaniah 3:17" },
            { text: "For God so loved the world that he gave his one and only Son.", reference: "John 3:16" },
            { text: "The Lord bless you and keep you; the Lord make his face shine on you.", reference: "Numbers 6:24-25" }
        ];

        // Show Bible verse every 10 minutes
        function showBibleVerse() {
            const container = document.getElementById('bibleVerseContainer');
            const textElement = document.getElementById('bibleVerseText');
            const referenceElement = document.getElementById('bibleVerseReference');
            
            // Get random verse
            const randomVerse = bibleVerses[Math.floor(Math.random() * bibleVerses.length)];
            
            // Update content
            textElement.textContent = randomVerse.text;
            referenceElement.textContent = "- " + randomVerse.reference;
            
            // Show verse
            container.classList.add('show');
            
            // Auto-hide after 15 seconds
            setTimeout(function() {
                container.classList.remove('show');
            }, 15000);
        }

        // Close Bible verse manually
        function closeBibleVerse() {
            document.getElementById('bibleVerseContainer').classList.remove('show');
        }

        // Initialize particles and start Bible verse timer
        createParticles();
        
        // Show first Bible verse after 2 minutes (120000 ms)
        setTimeout(showBibleVerse, 120000);
        
        // Then show every 10 minutes (600000 ms)
        setInterval(showBibleVerse, 600000);

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
