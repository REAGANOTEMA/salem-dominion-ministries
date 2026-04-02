<?php
// Include session helper and start session safely
require_once 'session_helper.php';
secure_session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Meet our leadership team at Salem Dominion Ministries - Serving Iganga with spiritual guidance and pastoral care">
    <meta name="keywords" content="church leadership, pastors, apostles, evangelists, Salem Dominion Ministries, Iganga">
    <title>Leadership - Salem Dominion Ministries</title>
    
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

        /* Hero Section */
        .hero-section {
            background: var(--gradient-divine);
            color: white;
            padding: 120px 0 80px;
            text-align: center;
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
            animation: shimmer 3s infinite;
        }

        @keyframes shimmer {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }

        .hero-title {
            font-size: clamp(2.5rem, 5vw, 4rem);
            font-weight: 900;
            margin-bottom: 1rem;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }

        .hero-subtitle {
            font-size: clamp(1.2rem, 3vw, 1.8rem);
            font-weight: 300;
            margin-bottom: 2rem;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        /* Senior Pastor Section */
        .senior-pastor-section {
            padding: 80px 0;
            position: relative;
            z-index: 2;
        }

        .senior-pastor-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            position: relative;
            transition: all 0.3s ease;
        }

        .senior-pastor-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 30px 60px rgba(255, 215, 0, 0.2);
        }

        .senior-pastor-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--gradient-heavenly);
        }

        .pastor-image-container {
            position: relative;
            height: 400px;
            overflow: hidden;
        }

        .pastor-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .senior-pastor-card:hover .pastor-image {
            transform: scale(1.05);
        }

        .pastor-badge {
            position: absolute;
            top: 20px;
            right: 20px;
            background: var(--gradient-heavenly);
            color: white;
            padding: 0.8rem 1.5rem;
            border-radius: 25px;
            font-weight: 600;
            font-size: 1rem;
            box-shadow: 0 5px 15px rgba(255, 215, 0, 0.3);
        }

        .pastor-content {
            padding: 3rem;
        }

        .pastor-name {
            font-size: 2.5rem;
            font-weight: 900;
            color: var(--primary-color);
            margin-bottom: 0.5rem;
        }

        .pastor-title {
            font-size: 1.3rem;
            color: var(--divine-blue);
            margin-bottom: 1.5rem;
            font-weight: 600;
        }

        .pastor-bio {
            font-size: 1.1rem;
            line-height: 1.8;
            color: var(--dark-color);
            margin-bottom: 2rem;
        }

        .pastor-contact {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .contact-btn {
            padding: 0.8rem 1.5rem;
            border: none;
            border-radius: 25px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-primary-pastor {
            background: var(--gradient-heavenly);
            color: white;
        }

        .btn-secondary-pastor {
            background: var(--gradient-divine);
            color: white;
        }

        .contact-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        /* Leadership Team Section */
        .leadership-section {
            padding: 80px 0;
            background: var(--gradient-angel);
            position: relative;
            z-index: 2;
        }

        .section-title {
            font-size: 3rem;
            font-weight: 900;
            text-align: center;
            margin-bottom: 1rem;
            color: var(--primary-color);
        }

        .section-subtitle {
            text-align: center;
            font-size: 1.2rem;
            color: var(--dark-color);
            margin-bottom: 4rem;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        .leadership-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 2rem;
            margin-bottom: 3rem;
        }

        .leader-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            position: relative;
        }

        .leader-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: var(--gradient-divine);
        }

        .leader-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(65, 105, 225, 0.2);
        }

        .leader-image-container {
            position: relative;
            height: 250px;
            overflow: hidden;
        }

        .leader-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .leader-card:hover .leader-image {
            transform: scale(1.1);
        }

        .leader-position-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            background: var(--gradient-divine);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.8rem;
            box-shadow: 0 3px 10px rgba(65, 105, 225, 0.3);
        }

        .leader-content {
            padding: 2rem;
        }

        .leader-name {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 0.5rem;
        }

        .leader-position {
            font-size: 1rem;
            color: var(--divine-blue);
            margin-bottom: 1rem;
            font-weight: 600;
        }

        .leader-bio {
            font-size: 0.95rem;
            line-height: 1.6;
            color: var(--dark-color);
            margin-bottom: 1.5rem;
        }

        .leader-actions {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        .leader-btn {
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 20px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            font-size: 0.85rem;
        }

        .btn-contact-leader {
            background: var(--gradient-divine);
            color: white;
        }

        .btn-pray-leader {
            background: var(--gradient-heavenly);
            color: white;
        }

        .leader-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        /* Ministry Philosophy Section */
        .philosophy-section {
            padding: 80px 0;
            position: relative;
            z-index: 2;
        }

        .philosophy-content {
            background: white;
            border-radius: 20px;
            padding: 3rem;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            position: relative;
            overflow: hidden;
        }

        .philosophy-content::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--gradient-heavenly);
        }

        .philosophy-title {
            font-size: 2rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 2rem;
            text-align: center;
        }

        .philosophy-points {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
        }

        .philosophy-point {
            text-align: center;
            padding: 2rem;
            border-radius: 15px;
            background: var(--gradient-angel);
            transition: all 0.3s ease;
        }

        .philosophy-point:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .philosophy-icon {
            font-size: 3rem;
            color: var(--heavenly-gold);
            margin-bottom: 1rem;
        }

        .philosophy-point-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 1rem;
        }

        .philosophy-point-text {
            font-size: 1rem;
            line-height: 1.6;
            color: var(--dark-color);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .hero-section {
                padding: 80px 0 60px;
            }
            
            .senior-pastor-section,
            .leadership-section,
            .philosophy-section {
                padding: 60px 0;
            }
            
            .pastor-content {
                padding: 2rem;
            }
            
            .pastor-name {
                font-size: 2rem;
            }
            
            .leadership-grid {
                grid-template-columns: 1fr;
                gap: 1.5rem;
                margin: 0 1rem 3rem;
            }
            
            .philosophy-content {
                padding: 2rem;
                margin: 0 1rem;
            }
            
            .philosophy-points {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }
        }

        /* Loading Animation */
        .image-loading {
            position: relative;
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: loading 1.5s infinite;
        }

        @keyframes loading {
            0% { background-position: 200% 0; }
            100% { background-position: -200% 0; }
        }
    </style>
</head>
<body>
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
                    <li class="nav-item"><a class="nav-link active" href="leadership.php">Leadership</a></li>
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

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <h1 class="hero-title" data-aos="fade-up">Meet Our Leadership</h1>
            <p class="hero-subtitle" data-aos="fade-up" data-aos-delay="100">
                Dedicated servants of God, committed to shepherding our community with love, wisdom, and spiritual guidance.
            </p>
        </div>
    </section>

    <!-- Senior Pastor Section -->
    <section class="senior-pastor-section py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6" data-aos="fade-right">
                    <div class="pastor-image-container">
                        <img src="public/images/leadership/senior-pastor.jpeg" alt="Apostle Faty Musasizi" class="pastor-image">
                    </div>
                </div>
                <div class="col-lg-6" data-aos="fade-left">
                    <div class="pastor-content">
                        <h2 class="pastor-name">Apostle Faty Musasizi</h2>
                        <p class="pastor-title">Senior Pastor & Founder</p>
                        <p class="pastor-bio">
                            Apostle Faty Musasizi is the visionary founder and Senior Pastor of Salem Dominion Ministries. 
                            With over 20 years of ministry experience, he has dedicated his life to spreading the 
                            gospel and building a community of faith, hope, and love in Iganga and beyond.
                        </p>
                        <div class="pastor-contacts">
                            <p><i class="fas fa-envelope"></i> apostle@salemdominionministries.com</p>
                            <p><i class="fas fa-phone"></i> +256 XXX XXX XXX</p>
                        </div>
                        <div class="pastor-actions">
                            <button class="btn btn-primary" onclick="openPrayerModal()">
                                <i class="fas fa-praying-hands"></i> Prayer Request
                            </button>
                            <button class="btn btn-outline-primary" onclick="contactPastor()">
                                <i class="fas fa-envelope"></i> Contact Pastor
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Leadership Team Section -->
    <section class="leadership-section">
        <div class="container">
            <h2 class="section-title" data-aos="fade-up">Our Leadership Team</h2>
            <p class="section-subtitle" data-aos="fade-up" data-aos-delay="100">
                Committed leaders serving together to advance God's kingdom and nurture our spiritual family.
            </p>

            <div class="leadership-grid">
                <!-- Pastor Nabulya Joyce -->
                <div class="leader-card" data-aos="fade-up" data-aos-delay="100">
                    <div class="leader-image-container">
                        <img src="public/images/leadership/church-treasurer.jpeg" alt="Pastor Nabulya Joyce" class="leader-image">
                        <div class="leader-position-badge">Church Treasurer</div>
                    </div>
                    <div class="leader-content">
                        <h3 class="leader-name">Pastor Nabulya Joyce</h3>
                        <p class="leader-position">Church Treasurer</p>
                        <p class="leader-bio">
                            Pastor Nabulya Joyce serves faithfully as our Church Treasurer, bringing wisdom and integrity 
                            to financial stewardship. Her commitment to transparency and accountability ensures that 
                            every resource is used effectively for God's work.
                        </p>
                        <div class="leader-actions">
                            <a href="tel:+256753244480" class="leader-btn btn-contact-leader">
                                <i class="fas fa-phone"></i> Contact
                            </a>
                            <button class="leader-btn btn-pray-leader" onclick="showPrayerModal('Pastor Nabulya Joyce')">
                                <i class="fas fa-pray"></i> Pray
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Apostle Irene Mirembe -->
                <div class="leader-card" data-aos="fade-up" data-aos-delay="200">
                    <div class="leader-image-container">
                        <img src="public/images/leadership/church-administrator.jpeg" alt="Apostle Irene Mirembe" class="leader-image">
                        <div class="leader-position-badge">Church Administrator</div>
                    </div>
                    <div class="leader-content">
                        <h3 class="leader-name">Apostle Irene Mirembe</h3>
                        <p class="leader-position">Church Administrator</p>
                        <p class="leader-bio">
                            Apostle Irene Mirembe oversees the administrative operations of our ministry with 
                            exceptional organizational skills and spiritual insight. Her leadership ensures smooth 
                            coordination of all church activities and programs.
                        </p>
                        <div class="leader-actions">
                            <a href="tel:+256753244480" class="leader-btn btn-contact-leader">
                                <i class="fas fa-phone"></i> Contact
                            </a>
                            <button class="leader-btn btn-pray-leader" onclick="showPrayerModal('Apostle Irene Mirembe')">
                                <i class="fas fa-pray"></i> Pray
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Evangelist Kisakye Halima -->
                <div class="leader-card" data-aos="fade-up" data-aos-delay="300">
                    <div class="leader-image-container">
                        <img src="public/images/leadership/mission-director.jpeg" alt="Evangelist Kisakye Halima" class="leader-image">
                        <div class="leader-position-badge">Mission Director</div>
                    </div>
                    <div class="leader-content">
                        <h3 class="leader-name">Evangelist Kisakye Halima</h3>
                        <p class="leader-position">Mission Director</p>
                        <p class="leader-bio">
                            Evangelist Kisakye Halima leads our mission initiatives with passion and dedication. 
                            Her evangelistic zeal and cross-cultural ministry experience have opened doors for 
                            the Gospel in communities throughout Iganga district.
                        </p>
                        <div class="leader-actions">
                            <a href="tel:+256753244480" class="leader-btn btn-contact-leader">
                                <i class="fas fa-phone"></i> Contact
                            </a>
                            <button class="leader-btn btn-pray-leader" onclick="showPrayerModal('Evangelist Kisakye Halima')">
                                <i class="fas fa-pray"></i> Pray
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Pastor Damali Namwima -->
                <div class="leader-card" data-aos="fade-up" data-aos-delay="400">
                    <div class="leader-image-container">
                        <img src="public/images/leadership/altars-director.jpeg" alt="Pastor Damali Namwima" class="leader-image">
                        <div class="leader-position-badge">Altars Director</div>
                    </div>
                    <div class="leader-content">
                        <h3 class="leader-name">Pastor Damali Namwima</h3>
                        <p class="leader-position">Altars Director</p>
                        <p class="leader-bio">
                            Pastor Damali Namwima oversees our altar ministry with deep spiritual sensitivity. 
                            Her pastoral care and prayer ministry have brought healing and restoration to many 
                            who seek God's presence and guidance.
                        </p>
                        <div class="leader-actions">
                            <a href="tel:+256753244480" class="leader-btn btn-contact-leader">
                                <i class="fas fa-phone"></i> Contact
                            </a>
                            <button class="leader-btn btn-pray-leader" onclick="showPrayerModal('Pastor Damali Namwima')">
                                <i class="fas fa-pray"></i> Pray
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Pastor Jotham Bright Mulinde -->
                <div class="leader-card" data-aos="fade-up" data-aos-delay="500">
                    <div class="leader-image-container">
                        <img src="public/images/leadership/church-elder.jpeg" alt="Pastor Jotham Bright Mulinde" class="leader-image">
                        <div class="leader-position-badge">Church Elder</div>
                    </div>
                    <div class="leader-content">
                        <h3 class="leader-name">Pastor Jotham Bright Mulinde</h3>
                        <p class="leader-position">Church Elder</p>
                        <p class="leader-bio">
                            Pastor Jotham Bright Mulinde serves as a respected Church Elder, bringing wisdom and 
                            stability to our leadership team. His years of ministry experience and spiritual 
                            maturity provide guidance and mentorship to our congregation.
                        </p>
                        <div class="leader-actions">
                            <a href="tel:+256753244480" class="leader-btn btn-contact-leader">
                                <i class="fas fa-phone"></i> Contact
                            </a>
                            <button class="leader-btn btn-pray-leader" onclick="showPrayerModal('Pastor Jotham Bright Mulinde')">
                                <i class="fas fa-pray"></i> Pray
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Pastor Jonathan Ngobi -->
                <div class="leader-card" data-aos="fade-up" data-aos-delay="600">
                    <div class="leader-image-container">
                        <img src="public/images/leadership/bulanga-branch-pastor.jpeg" alt="Pastor Jonathan Ngobi" class="leader-image">
                        <div class="leader-position-badge">Bulanga Branch Pastor</div>
                    </div>
                    <div class="leader-content">
                        <h3 class="leader-name">Pastor Jonathan Ngobi</h3>
                        <p class="leader-position">Bulanga Branch Pastor</p>
                        <p class="leader-bio">
                            Pastor Jonathan Ngobi faithfully leads our Bulanga branch with dedication and 
                            pastoral excellence. His leadership has established a thriving spiritual community 
                            that serves the Bulanga area with the love of Christ.
                        </p>
                        <div class="leader-actions">
                            <a href="tel:+256753244480" class="leader-btn btn-contact-leader">
                                <i class="fas fa-phone"></i> Contact
                            </a>
                            <button class="leader-btn btn-pray-leader" onclick="showPrayerModal('Pastor Jonathan Ngobi')">
                                <i class="fas fa-pray"></i> Pray
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Pastor Miriam Gerald -->
                <div class="leader-card" data-aos="fade-up" data-aos-delay="700">
                    <div class="leader-image-container">
                        <img src="public/images/leadership/children-ministry-director.jpeg" alt="Pastor Miriam Gerald" class="leader-image">
                        <div class="leader-position-badge">Children's Ministry Director</div>
                    </div>
                    <div class="leader-content">
                        <h3 class="leader-name">Pastor Miriam Gerald</h3>
                        <p class="leader-position">Children's Ministry Director</p>
                        <p class="leader-bio">
                            Pastor Miriam Gerald leads our children's ministry with passion and creativity. 
                            Her dedication to nurturing the next generation ensures that our children grow 
                            in faith and knowledge of God's Word from an early age.
                        </p>
                        <div class="leader-actions">
                            <a href="tel:+256753244480" class="leader-btn btn-contact-leader">
                                <i class="fas fa-phone"></i> Contact
                            </a>
                            <button class="leader-btn btn-pray-leader" onclick="showPrayerModal('Pastor Miriam Gerald')">
                                <i class="fas fa-pray"></i> Pray
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Ministry Philosophy Section -->
    <section class="philosophy-section">
        <div class="container">
            <div class="philosophy-content" data-aos="fade-up">
                <h2 class="philosophy-title">Our Leadership Philosophy</h2>
                <div class="philosophy-points">
                    <div class="philosophy-point" data-aos="fade-up" data-aos-delay="100">
                        <div class="philosophy-icon">🙏</div>
                        <h3 class="philosophy-point-title">Prayer-Led</h3>
                        <p class="philosophy-point-text">
                            Every decision and action is rooted in prayer and seeking God's guidance above all else.
                        </p>
                    </div>
                    <div class="philosophy-point" data-aos="fade-up" data-aos-delay="200">
                        <div class="philosophy-icon">📖</div>
                        <h3 class="philosophy-point-title">Biblical Foundation</h3>
                        <p class="philosophy-point-text">
                            Our leadership is firmly established on the unchanging truth of God's Word.
                        </p>
                    </div>
                    <div class="philosophy-point" data-aos="fade-up" data-aos-delay="300">
                        <div class="philosophy-icon">❤️</div>
                        <h3 class="philosophy-point-title">Servant Leadership</h3>
                        <p class="philosophy-point-text">
                            We lead by example, serving others with humility and Christ-like love.
                        </p>
                    </div>
                    <div class="philosophy-point" data-aos="fade-up" data-aos-delay="400">
                        <div class="philosophy-icon">🤝</div>
                        <h3 class="philosophy-point-title">Team Ministry</h3>
                        <p class="philosophy-point-text">
                            We work together as a unified team, valuing each person's unique gifts and calling.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Prayer Modal -->
    <div class="modal fade" id="prayerModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Prayer Request</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Send a prayer request for <strong id="leaderName"></strong></p>
                    <form id="prayerForm">
                        <div class="mb-3">
                            <label class="form-label">Your Name</label>
                            <input type="text" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Prayer Request</label>
                            <textarea class="form-control" rows="3" required></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="submitPrayerRequest()">Submit Prayer</button>
                </div>
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

        // Show prayer modal
        function showPrayerModal(leaderName) {
            document.getElementById('leaderName').textContent = leaderName;
            const modal = new bootstrap.Modal(document.getElementById('prayerModal'));
            modal.show();
        }

        // Submit prayer request
        function submitPrayerRequest() {
            const form = document.getElementById('prayerForm');
            if (form.checkValidity()) {
                // Show success message
                alert('Prayer request submitted successfully! Our team will be praying for you.');
                
                // Close modal
                const modal = bootstrap.Modal.getInstance(document.getElementById('prayerModal'));
                modal.hide();
                
                // Reset form
                form.reset();
            } else {
                form.reportValidity();
            }
        }

        // Add image loading animations
        document.addEventListener('DOMContentLoaded', function() {
            const images = document.querySelectorAll('img');
            images.forEach(img => {
                img.addEventListener('load', function() {
                    this.style.animation = 'fadeIn 0.5s ease-in';
                });
            });
        });

        // Add fadeIn animation
        const style = document.createElement('style');
        style.textContent = `
            @keyframes fadeIn {
                from { opacity: 0; transform: translateY(20px); }
                to { opacity: 1; transform: translateY(0); }
            }
        `;
        document.head.appendChild(style);

        // Add spiritual enhancement
        if (window.spiritualEnhancement) {
            setTimeout(() => {
                window.spiritualEnhancement.showSpiritualGuidance("May God bless our leaders as they serve His people with wisdom and grace.");
            }, 8000);
        }
    </script>
</body>
</html>
