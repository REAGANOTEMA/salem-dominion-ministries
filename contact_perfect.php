<?php
// Complete error suppression to prevent unwanted output
error_reporting(0);
ini_set('display_errors', 0);
ini_set('log_errors', 0);

// Buffer output to catch any accidental output
ob_start();

// Handle contact form submission
$success_message = '';
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        $error_message = 'Please fill in all required fields.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = 'Please enter a valid email address.';
    } else {
        // Here you would typically send an email or save to database
        // For now, we'll just show success message
        $success_message = 'Thank you for contacting us! We will get back to you soon.';
        
        // Clear form
        $_POST = [];
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
    <meta name="description" content="Contact Salem Dominion Ministries - Get in touch with us for prayer, information, or partnership">
    <title>Contact Us - Salem Dominion Ministries</title>
    
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

        /* Contact Cards */
        .contact-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 3rem;
            margin-top: 4rem;
        }

        .contact-card {
            background: var(--snow-white);
            border-radius: 30px;
            padding: 3rem;
            box-shadow: var(--shadow-soft);
            border: 1px solid rgba(125, 211, 252, 0.2);
            transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            text-align: center;
        }

        .contact-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--gradient-divine);
        }

        .contact-card:hover {
            transform: translateY(-15px);
            box-shadow: var(--shadow-divine);
        }

        .contact-icon {
            width: 80px;
            height: 80px;
            background: var(--gradient-divine);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--midnight-blue);
            font-size: 2rem;
            margin: 0 auto 2rem;
            box-shadow: 0 15px 35px rgba(251, 191, 36, 0.3);
            transition: all 0.5s ease;
        }

        .contact-card:hover .contact-icon {
            transform: scale(1.1) rotate(15deg);
            box-shadow: 0 20px 45px rgba(251, 191, 36, 0.4);
        }

        .contact-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--midnight-blue);
            margin-bottom: 1.5rem;
            font-family: 'Playfair Display', serif;
        }

        .contact-info {
            font-size: 1.1rem;
            line-height: 1.8;
            color: var(--ocean-blue);
            margin-bottom: 2rem;
        }

        .contact-action {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 12px 25px;
            background: var(--gradient-ocean);
            color: var(--snow-white);
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .contact-action:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(14, 165, 233, 0.3);
            color: var(--snow-white);
        }

        .contact-action.whatsapp:hover {
            background: #25d366;
        }

        /* Contact Form */
        .contact-form-section {
            background: var(--gradient-heaven);
            padding: 4rem;
            border-radius: 30px;
            margin-top: 4rem;
            border: 1px solid var(--ice-blue);
        }

        .contact-form {
            background: var(--snow-white);
            padding: 3rem;
            border-radius: 25px;
            box-shadow: var(--shadow-soft);
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-bottom: 2rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: var(--midnight-blue);
        }

        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid var(--ice-blue);
            border-radius: 15px;
            background: var(--snow-white);
            color: var(--midnight-blue);
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--ocean-blue);
            box-shadow: 0 0 20px rgba(14, 165, 233, 0.2);
        }

        .form-control::placeholder {
            color: rgba(15, 23, 42, 0.4);
        }

        textarea.form-control {
            resize: vertical;
            min-height: 150px;
        }

        .btn-submit {
            background: var(--gradient-ocean);
            color: var(--snow-white);
            border: none;
            padding: 15px 40px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 1.1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            position: relative;
            overflow: hidden;
        }

        .btn-submit::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.2);
            transition: all 0.4s ease;
        }

        .btn-submit:hover::before {
            left: 100%;
        }

        .btn-submit:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(14, 165, 233, 0.3);
            color: var(--snow-white);
        }

        /* Alert Styles */
        .alert {
            padding: 1rem 1.5rem;
            border-radius: 15px;
            margin-bottom: 2rem;
            border: none;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .alert-success {
            background: linear-gradient(135deg, rgba(34, 197, 94, 0.1), rgba(34, 197, 94, 0.05));
            color: #16a34a;
            border: 1px solid rgba(34, 197, 94, 0.2);
        }

        .alert-danger {
            background: linear-gradient(135deg, rgba(239, 68, 68, 0.1), rgba(239, 68, 68, 0.05));
            color: #dc2626;
            border: 1px solid rgba(239, 68, 68, 0.2);
        }

        /* Map Section */
        .map-section {
            margin-top: 4rem;
            border-radius: 25px;
            overflow: hidden;
            box-shadow: var(--shadow-divine);
            height: 400px;
            position: relative;
        }

        .map-section iframe {
            width: 100%;
            height: 100%;
            border: none;
        }

        /* Service Times */
        .service-times {
            background: var(--gradient-heaven);
            padding: 3rem;
            border-radius: 25px;
            margin-top: 4rem;
            border: 1px solid var(--ice-blue);
        }

        .service-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            margin-top: 2rem;
        }

        .service-item {
            text-align: center;
            padding: 2rem;
            background: var(--snow-white);
            border-radius: 20px;
            border: 1px solid var(--ice-blue);
            transition: all 0.3s ease;
        }

        .service-item:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-divine);
        }

        .service-time {
            font-size: 2rem;
            font-weight: 900;
            color: var(--heavenly-gold);
            font-family: 'Playfair Display', serif;
            margin-bottom: 0.5rem;
        }

        .service-name {
            font-size: 1.2rem;
            color: var(--midnight-blue);
            font-weight: 600;
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

            .contact-grid {
                grid-template-columns: 1fr;
                gap: 2rem;
            }

            .contact-form-section {
                padding: 2rem;
            }

            .contact-form {
                padding: 2rem;
            }

            .form-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
            }

            .service-grid {
                grid-template-columns: 1fr;
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
                    <li class="nav-item"><a class="nav-link active" href="contact.php">Contact</a></li>
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
            <h1 class="hero-title">Contact Us</h1>
            <p class="hero-subtitle">We'd Love to Hear From You</p>
        </div>
    </section>

    <!-- Contact Information Section -->
    <section class="section section-heaven">
        <div class="container">
            <h2 class="section-title" data-aos="fade-up">Get in Touch</h2>
            <p class="section-subtitle" data-aos="fade-up" data-aos-delay="100">Connect with us through various channels</p>
            
            <div class="contact-grid">
                <div class="contact-card" data-aos="fade-up" data-aos-delay="200">
                    <div class="contact-icon">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <h3 class="contact-title">Visit Us</h3>
                    <div class="contact-info">
                        Main Street<br>
                        Iganga Town, Uganda<br>
                        East Africa
                    </div>
                    <a href="map.php" class="contact-action">
                        <i class="fas fa-directions"></i> Get Directions
                    </a>
                </div>

                <div class="contact-card" data-aos="fade-up" data-aos-delay="300">
                    <div class="contact-icon">
                        <i class="fas fa-phone"></i>
                    </div>
                    <h3 class="contact-title">Call Us</h3>
                    <div class="contact-info">
                        Office: +256 753 244 480<br>
                        Pastor: +256 753 244 480<br>
                        Available: Mon-Sat, 9AM-6PM
                    </div>
                    <a href="tel:+256753244480" class="contact-action">
                        <i class="fas fa-phone-alt"></i> Call Now
                    </a>
                </div>

                <div class="contact-card" data-aos="fade-up" data-aos-delay="400">
                    <div class="contact-icon">
                        <i class="fab fa-whatsapp"></i>
                    </div>
                    <h3 class="contact-title">WhatsApp</h3>
                    <div class="contact-info">
                        Pastor Faty Musasizi<br>
                        +256 753 244 480<br>
                        Quick responses 24/7
                    </div>
                    <a href="https://wa.me/256753244480" class="contact-action whatsapp" target="_blank">
                        <i class="fab fa-whatsapp"></i> Chat on WhatsApp
                    </a>
                </div>

                <div class="contact-card" data-aos="fade-up" data-aos-delay="500">
                    <div class="contact-icon">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <h3 class="contact-title">Email Us</h3>
                    <div class="contact-info">
                        apostle@salemdominionministries.com<br>
                        info@salemdominionministries.com<br>
                        We respond within 24 hours
                    </div>
                    <a href="mailto:apostle@salemdominionministries.com" class="contact-action">
                        <i class="fas fa-paper-plane"></i> Send Email
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Form Section -->
    <section class="section section-light">
        <div class="container">
            <h2 class="section-title" data-aos="fade-up">Send Us a Message</h2>
            <p class="section-subtitle" data-aos="fade-up" data-aos-delay="100">We'd love to hear from you</p>
            
            <div class="contact-form-section" data-aos="fade-up" data-aos-delay="200">
                <form class="contact-form" method="POST">
                    <?php if ($success_message): ?>
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle"></i>
                            <?php echo htmlspecialchars($success_message); ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($error_message): ?>
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-circle"></i>
                            <?php echo htmlspecialchars($error_message); ?>
                        </div>
                    <?php endif; ?>
                    
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="name" class="form-label">Your Name *</label>
                            <input type="text" id="name" name="name" class="form-control" 
                                   value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>" 
                                   placeholder="Enter your full name" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="email" class="form-label">Email Address *</label>
                            <input type="email" id="email" name="email" class="form-control" 
                                   value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" 
                                   placeholder="your.email@example.com" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="phone" class="form-label">Phone Number</label>
                            <input type="tel" id="phone" name="phone" class="form-control" 
                                   value="<?php echo htmlspecialchars($_POST['phone'] ?? ''); ?>" 
                                   placeholder="+256 XXX XXX XXX">
                        </div>
                        
                        <div class="form-group">
                            <label for="subject" class="form-label">Subject *</label>
                            <input type="text" id="subject" name="subject" class="form-control" 
                                   value="<?php echo htmlspecialchars($_POST['subject'] ?? ''); ?>" 
                                   placeholder="How can we help you?" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="message" class="form-label">Message *</label>
                        <textarea id="message" name="message" class="form-control" 
                                  placeholder="Tell us more about your inquiry..." required><?php echo htmlspecialchars($_POST['message'] ?? ''); ?></textarea>
                    </div>
                    
                    <div class="text-center">
                        <button type="submit" class="btn-submit">
                            <i class="fas fa-paper-plane me-2"></i>
                            Send Message
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <!-- Map Section -->
    <section class="section section-light">
        <div class="container">
            <h2 class="section-title" data-aos="fade-up">Find Us</h2>
            <p class="section-subtitle" data-aos="fade-up" data-aos-delay="100">Visit our church location</p>
            
            <div class="map-section" data-aos="fade-up" data-aos-delay="200">
                <iframe src="https://www.openstreetmap.org/export/embed.html?bbox=33.4700%2C0.5600%2C33.4900%2C0.5800&layer=mapnik&marker=0.5700%2C33.4800" 
                        allowfullscreen="" 
                        loading="lazy" 
                        referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            </div>
        </div>
    </section>

    <!-- Service Times Section -->
    <section class="section section-heaven">
        <div class="container">
            <h2 class="section-title" data-aos="fade-up">Service Times</h2>
            <p class="section-subtitle" data-aos="fade-up" data-aos-delay="100">Join us for worship and fellowship</p>
            
            <div class="service-times" data-aos="fade-up" data-aos-delay="200">
                <div class="service-grid">
                    <div class="service-item">
                        <div class="service-time">8:00 AM</div>
                        <div class="service-name">Early Morning Service</div>
                    </div>
                    <div class="service-item">
                        <div class="service-time">10:00 AM</div>
                        <div class="service-name">Main Service</div>
                    </div>
                    <div class="service-item">
                        <div class="service-time">12:00 PM</div>
                        <div class="service-name">Youth Service</div>
                    </div>
                    <div class="service-item">
                        <div class="service-time">6:00 PM</div>
                        <div class="service-name">Evening Service</div>
                    </div>
                </div>
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
