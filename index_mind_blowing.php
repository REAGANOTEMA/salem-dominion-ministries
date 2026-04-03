<?php
// Mind-Blowing & Iconic Homepage - Final Perfect Version
require_once 'config_force.php';

// Initialize variables to prevent undefined errors
$services = [];
$ministries = [];
$news = [];
$events = [];
$sermons = [];
$gallery = [];
$upcoming_events = [];
$recent_gallery = [];

// Get dynamic data with forced database connection
try {
    $db = new Database();
    
    $services = $db->select("SELECT * FROM service_times WHERE is_active = 1 ORDER BY FIELD(day_of_week, 'sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'), start_time");
    $ministries = $db->select("SELECT * FROM ministries WHERE is_active = 1 ORDER BY name");
    $news = $db->select("SELECT * FROM news WHERE status = 'published' ORDER BY published_at DESC LIMIT 3");
    $events = $db->select("SELECT * FROM events WHERE status = 'upcoming' ORDER BY event_date ASC LIMIT 3");
    $sermons = $db->select("SELECT * FROM sermons WHERE status = 'published' ORDER BY sermon_date DESC LIMIT 3");
    $gallery = $db->select("SELECT * FROM gallery WHERE status = 'published' ORDER BY created_at DESC LIMIT 4");
    
    // Get upcoming events for footer
    $upcoming_events = $db->select("SELECT title, event_date FROM events WHERE status = 'upcoming' AND event_date >= NOW() ORDER BY event_date ASC LIMIT 2");
    
    // Get recent gallery for footer
    $recent_gallery = $db->select("SELECT title, file_url FROM gallery WHERE status = 'published' ORDER BY created_at DESC LIMIT 3");
    
} catch (Exception $e) {
    // Set empty arrays if database fails
    $services = [];
    $ministries = [];
    $news = [];
    $events = [];
    $sermons = [];
    $gallery = [];
    $upcoming_events = [];
    $recent_gallery = [];
}

// Pastor information
$pastor_info = [
    'name' => 'Apostle Faty Musasizi',
    'title' => 'Senior Pastor & Founder',
    'bio' => 'Apostle Faty Musasizi is a passionate servant of God with over 25 years of ministry experience. She founded Salem Dominion Ministries with a vision to empower believers and transform communities through the power of the Gospel.',
    'email' => 'admin@saleldominionministries.com',
    'phone' => '+256 753 244480',
    'image' => 'assets/general-pastor.jpeg'
];

// Church statistics
$church_stats = [
    ['number' => '500+', 'label' => 'Members', 'icon' => 'fa-users'],
    ['number' => '50+', 'label' => 'Ministries', 'icon' => 'fa-church'],
    ['number' => '1000+', 'label' => 'Lives Touched', 'icon' => 'fa-heart'],
    ['number' => '25+', 'label' => 'Years of Service', 'icon' => 'fa-calendar']
];

// Hero images for rotating background
$hero_images = [
    'assets/hero-worship-CWyaH0tr.jpg',
    'assets/hero-community-CDAgPtPb.jpg',
    'assets/hero-choir-6lo-hX_h.jpg'
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Salem Dominion Ministries - Welcome | Mind-Blowing Experience</title>
    <meta name="description" content="Experience the divine presence at Salem Dominion Ministries - Where faith comes alive through powerful worship, life-changing messages, and transformative community service.">
    <meta name="keywords" content="church, ministry, worship, fellowship, Salem Dominion, Christian, community, divine, spiritual">
    <meta name="author" content="Salem Dominion Ministries">
    
    <!-- Open Graph Meta Tags -->
    <meta property="og:title" content="Salem Dominion Ministries - Welcome | Mind-Blowing Experience">
    <meta property="og:description" content="Experience the divine presence at Salem Dominion Ministries - Where faith comes alive through powerful worship, life-changing messages, and transformative community service.">
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?php echo (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>">
    <meta property="og:image" content="<?php echo (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST']; ?>/assets/general-pastor.jpeg">
    <meta property="og:site_name" content="Salem Dominion Ministries">
    
    <!-- Twitter Card Meta Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Salem Dominion Ministries - Welcome | Mind-Blowing Experience">
    <meta name="twitter:description" content="Experience the divine presence at Salem Dominion Ministries - Where faith comes alive through powerful worship, life-changing messages, and transformative community service.">
    <meta name="twitter:image" content="<?php echo (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST']; ?>/assets/general-pastor.jpeg">
    
    <!-- Canonical URL -->
    <link rel="canonical" href="<?php echo (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>">
    
    <!-- PWA Manifest -->
    <link rel="manifest" href="/pwa_manifest.json">
    <link rel="apple-touch-icon" href="/assets/icons/icon-152x152.png">
    <meta name="theme-color" content="#dc2626">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="Salem Church">
    <meta name="application-name" content="Salem Dominion Ministries">
    <meta name="msapplication-TileColor" content="#dc2626">
    <meta name="msapplication-config" content="/salem_browserconfig.xml">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
    <link rel="shortcut icon" type="image/x-icon" href="/favicon.ico">
    <link rel="apple-touch-icon" href="/favicon.ico">
    <link rel="icon" sizes="192x192" href="/favicon.ico">
    <link rel="icon" sizes="512x512" href="/favicon.ico">
    
    <!-- Preload Critical Resources -->
    <link rel="preload" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" as="style">
    <link rel="preload" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" as="style">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <!-- AOS Animation Library -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Mind-Blowing CSS -->
    <style>
        :root {
            --primary-color: #dc2626;
            --secondary-color: #ea580c;
            --accent-color: #f59e0b;
            --dark-color: #450a0a;
            --light-color: #fef2f2;
            --text-color: #7f1d1d;
            --gradient-primary: linear-gradient(135deg, #dc2626 0%, #991b1b 100%);
            --gradient-secondary: linear-gradient(135deg, #ea580c 0%, #dc2626 100%);
            --gradient-accent: linear-gradient(135deg, #f59e0b 0%, #ea580c 100%);
            --gradient-dark: linear-gradient(135deg, #450a0a 0%, #7f1d1d 100%);
            --gradient-hero: linear-gradient(135deg, rgba(220, 38, 38, 0.95) 0%, rgba(153, 27, 27, 0.95) 100%);
            --gradient-divine: linear-gradient(45deg, #dc2626, #ea580c, #f59e0b, #dc2626);
            --shadow-sm: 0 2px 4px rgba(220, 38, 38, 0.1);
            --shadow-md: 0 4px 6px rgba(220, 38, 38, 0.1);
            --shadow-lg: 0 10px 15px rgba(220, 38, 38, 0.1);
            --shadow-xl: 0 20px 25px rgba(220, 38, 38, 0.1);
            --transition-smooth: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            --border-radius: 12px;
            --border-radius-lg: 20px;
            --font-primary: 'Poppins', sans-serif;
        }
        
        /* Mind-Blowing Hero Section */
        .hero-section {
            min-height: 100vh;
            background: var(--gradient-hero), 
                        url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="%23dc2626" fill-opacity="0.1" d="M0,96L48,112C96,128,192,160C384,160,480,128C576,128,672,122.7C768,117,864,138.7C960,160,1056,128C1152,117,1248,160C1344,192,1392,128L1440,320Z"></path></svg>') no-repeat center bottom;
            background-size: cover;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
            animation: divineGlow 4s ease-in-out infinite alternate;
        }
        
        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: var(--gradient-divine);
            opacity: 0.3;
            animation: divineWave 8s linear infinite;
        }
        
        .hero-section::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="divine-pattern" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="50" cy="50" r="1" fill="rgba(220,38,38,0.2)"/><circle cx="25" cy="25" r="0.5" fill="rgba(234,88,12,0.1)"/><circle cx="75" cy="75" r="0.8" fill="rgba(245,158,11,0.15)"/></pattern></defs><rect width="100" height="100" fill="url(%23divine-pattern)"/></svg>') repeat;
            opacity: 0.4;
        }
        
        .hero-content {
            text-align: center;
            padding: 2rem 1rem;
            position: relative;
            z-index: 2;
            max-width: 100%;
        }
        
        .hero-content h1 {
            font-size: 3.5rem;
            font-weight: 700;
            color: white;
            margin-bottom: 1.5rem;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
            animation: fadeInUp 1s ease-out;
            background: linear-gradient(45deg, #fff, #f8f9fa, #fff);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-fill-color: var(--primary-color);
        }
        
        .hero-content .lead {
            font-size: 1.3rem;
            color: rgba(255,255,255,0.9);
            margin-bottom: 2rem;
            font-weight: 300;
            animation: fadeInUp 1s ease-out 0.2s both;
        }
        
        .action-buttons {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            align-items: center;
            animation: fadeInUp 1s ease-out 0.4s both;
        }
        
        .action-buttons .btn {
            min-width: 200px;
            padding: 1.2rem 2rem;
            font-size: 1.1rem;
            font-weight: 600;
            border-radius: 50px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            transition: var(--transition-smooth);
            border: 2px solid transparent;
            position: relative;
            overflow: hidden;
        }
        
        .action-buttons .btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }
        
        .action-buttons .btn:hover::before {
            left: 100%;
        }
        
        .btn-primary {
            background: var(--gradient-primary);
            color: white;
            box-shadow: var(--shadow-lg);
        }
        
        .btn-primary:hover {
            transform: translateY(-3px) scale(1.05);
            box-shadow: var(--shadow-xl);
            border-color: rgba(255,255,255,0.3);
        }
        
        .btn-success {
            background: var(--gradient-secondary);
            color: white;
            box-shadow: var(--shadow-lg);
        }
        
        .btn-success:hover {
            transform: translateY(-3px) scale(1.05);
            box-shadow: var(--shadow-xl);
            border-color: rgba(255,255,255,0.3);
        }
        
        .btn-purple {
            background: var(--gradient-accent);
            color: white;
            box-shadow: var(--shadow-lg);
        }
        
        .btn-purple:hover {
            transform: translateY(-3px) scale(1.05);
            box-shadow: var(--shadow-xl);
            border-color: rgba(255,255,255,0.3);
        }
        
        /* Mind-Blowing Animations */
        @keyframes divineGlow {
            0%, 100% {
                opacity: 0.3;
                transform: scale(1);
            }
            50% {
                opacity: 0.6;
                transform: scale(1.02);
            }
        }
        
        @keyframes divineWave {
            0% {
                transform: translateX(-100%) rotate(0deg);
            }
            100% {
                transform: translateX(100%) rotate(360deg);
            }
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
        
        /* Floating Particles */
        .floating-particles {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            pointer-events: none;
            overflow: hidden;
        }
        
        .particle {
            position: absolute;
            width: 4px;
            height: 4px;
            background: var(--gradient-primary);
            border-radius: 50%;
            animation: floatUp 6s ease-in-out infinite;
        }
        
        @keyframes floatUp {
            0%, 100% {
                transform: translateY(100vh) scale(0);
                opacity: 0;
            }
            50% {
                transform: translateY(-10px) scale(1);
                opacity: 0.8;
            }
        }
        
        /* Responsive Design */
        @media (max-width: 767px) {
            .hero-content h1 {
                font-size: 2.5rem;
            }
            
            .hero-content .lead {
                font-size: 1.1rem;
            }
            
            .action-buttons {
                flex-direction: column;
                align-items: center;
            }
            
            .action-buttons .btn {
                width: 100%;
                max-width: 250px;
            }
        }
        
        @media (min-width: 768px) {
            .action-buttons {
                flex-direction: row;
                justify-content: center;
                gap: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <!-- Production-Ready Navigation -->
    <?php include 'components/universal_nav_perfect.php'; ?>

    <!-- Mind-Blowing Hero Section -->
    <section class="hero-section">
        <!-- Floating Particles -->
        <div class="floating-particles">
            <?php for ($i = 0; $i < 15; $i++): ?>
                <div class="particle" style="left: <?php echo rand(5, 95); ?>%; animation-delay: <?php echo ($i * 0.4); ?>s;"></div>
            <?php endfor; ?>
        </div>
        
        <div class="container">
            <div class="hero-content">
                <h1 data-aos="fade-up">
                    <i class="fas fa-church"></i> Salem Dominion Ministries
                </h1>
                <p class="lead" data-aos="fade-up" data-aos-delay="200">
                    Experience the divine presence where faith comes alive through powerful worship, life-changing messages, and transformative community service
                </p>
                <div class="action-buttons" data-aos="fade-up" data-aos-delay="400">
                    <a href="donations.php" class="btn btn-primary">
                        <i class="fas fa-donate"></i> Give
                    </a>
                    <a href="book_pastor.php" class="btn btn-success">
                        <i class="fas fa-calendar"></i> Book Pastor
                    </a>
                    <a href="register.php" class="btn btn-purple">
                        <i class="fas fa-user-plus"></i> Join Us
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Pastor Section -->
    <section class="py-5">
        <div class="container">
            <div class="section-title" data-aos="fade-up">
                <h2>Meet Our Pastor</h2>
                <p>Learn more about our spiritual leader</p>
            </div>
            
            <div class="row justify-content-center">
                <div class="col-lg-6" data-aos="fade-up" data-aos-delay="200">
                    <div class="pastor-card">
                        <div class="pastor-image">
                            <img src="<?php echo htmlspecialchars($pastor_info['image']); ?>" alt="<?php echo htmlspecialchars($pastor_info['name']); ?>">
                            <div class="pastor-overlay">
                                <i class="fas fa-cross"></i>
                            </div>
                        </div>
                        <div class="pastor-info">
                            <h3><?php echo htmlspecialchars($pastor_info['name']); ?></h3>
                            <div class="title"><?php echo htmlspecialchars($pastor_info['title']); ?></div>
                            <p class="bio"><?php echo htmlspecialchars($pastor_info['bio']); ?></p>
                            <div class="pastor-contact">
                                <div><i class="fas fa-envelope"></i> <?php echo htmlspecialchars($pastor_info['email']); ?></div>
                                <div><i class="fas fa-phone"></i> <?php echo htmlspecialchars($pastor_info['phone']); ?></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Ministries Section -->
    <section class="py-5">
        <div class="container">
            <div class="section-title" data-aos="fade-up">
                <h2>Our Ministries</h2>
                <p>Discover ways to get involved and grow in your faith</p>
            </div>
            
            <div class="row">
                <?php if (!empty($ministries)): ?>
                    <?php foreach ($ministries as $index => $ministry): ?>
                        <div class="col-md-4" data-aos="fade-up" data-aos-delay="<?php echo $index * 100; ?>">
                            <div class="ministry-card">
                                <div class="ministry-icon">
                                    <i class="fas <?php echo htmlspecialchars($ministry['icon'] ?? 'fa-church'); ?>"></i>
                                </div>
                                <h4><?php echo htmlspecialchars($ministry['name']); ?></h4>
                                <p><?php echo htmlspecialchars($ministry['description']); ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-md-4" data-aos="fade-up">
                        <div class="ministry-card">
                            <div class="ministry-icon">
                                <i class="fas fa-users"></i>
                            </div>
                            <h4>Children's Ministry</h4>
                            <p>Nurturing young hearts in Christ's love</p>
                        </div>
                    </div>
                    <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                        <div class="ministry-card">
                            <div class="ministry-icon">
                                <i class="fas fa-hands-helping"></i>
                            </div>
                            <h4>Outreach</h4>
                            <p>Spreading God's love to our community</p>
                        </div>
                    </div>
                    <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                        <div class="ministry-card">
                            <div class="ministry-icon">
                                <i class="fas fa-music"></i>
                            </div>
                            <h4>Worship Team</h4>
                            <p>Leading congregation in praise and worship</p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- News Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="section-title" data-aos="fade-up">
                <h2>Latest News</h2>
                <p>Stay updated with what's happening in our church community</p>
            </div>
            
            <div class="row">
                <?php if (!empty($news)): ?>
                    <?php foreach ($news as $index => $article): ?>
                        <div class="col-md-4" data-aos="fade-up" data-aos-delay="<?php echo $index * 100; ?>">
                            <div class="news-card">
                                <div class="news-image">
                                    <i class="fas fa-newspaper"></i>
                                </div>
                                <div class="news-content">
                                    <h4><?php echo htmlspecialchars($article['title']); ?></h4>
                                    <p class="small text-muted"><?php echo date('F j, Y', strtotime($article['published_at'])); ?></p>
                                    <p><?php echo htmlspecialchars(substr($article['content'], 0, 150)); ?>...</p>
                                    <a href="news_article.php?id=<?php echo $article['id']; ?>" class="btn btn-sm btn-outline-primary">Read More</a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-md-4" data-aos="fade-up">
                        <div class="news-card">
                            <div class="news-image">
                                <i class="fas fa-newspaper"></i>
                            </div>
                            <div class="news-content">
                                <h4>Welcome to Our Church</h4>
                                <p class="small text-muted">Coming Soon</p>
                                <p>Exciting things are happening at Salem Dominion Ministries. Stay tuned for updates!</p>
                                <a href="news.php" class="btn btn-sm btn-outline-primary">All News</a>
                            </div>
                        </div>
                    <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Events Section -->
    <section class="py-5">
        <div class="container">
            <div class="section-title" data-aos="fade-up">
                <h2>Upcoming Events</h2>
                <p>Join us for these special gatherings and celebrations</p>
            </div>
            
            <div class="row">
                <?php if (!empty($events)): ?>
                    <?php foreach ($events as $index => $event): ?>
                        <div class="col-md-4" data-aos="fade-up" data-aos-delay="<?php echo $index * 100; ?>">
                            <div class="event-card">
                                <div class="event-date">
                                    <i class="fas fa-calendar-alt"></i>
                                    <div>
                                        <div class="day"><?php echo date('d', strtotime($event['event_date'])); ?></div>
                                        <div class="month"><?php echo date('M', strtotime($event['event_date'])); ?></div>
                                    </div>
                                </div>
                                <div class="event-content">
                                    <h4><?php echo htmlspecialchars($event['title']); ?></h4>
                                    <p class="small text-muted"><?php echo date('l, F j, Y - g:i A', strtotime($event['event_date'])); ?></p>
                                    <p><?php echo htmlspecialchars($event['description']); ?></p>
                                    <a href="#" class="btn btn-sm btn-outline-success">Learn More</a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-md-4" data-aos="fade-up">
                        <div class="event-card">
                            <div class="event-date">
                                <i class="fas fa-calendar-alt"></i>
                                <div>
                                    <div class="day">15</div>
                                    <div class="month">DEC</div>
                                </div>
                            </div>
                            <div class="event-content">
                                <h4>Christmas Service</h4>
                                <p class="small text-muted">Sunday, December 15, 2024 - 10:00 AM</p>
                                <p>Celebrate the birth of our Lord with special service and fellowship</p>
                                <a href="#" class="btn btn-sm btn-outline-success">Learn More</a>
                            </div>
                        </div>
                    <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Gallery Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="section-title" data-aos="fade-up">
                <h2>Gallery</h2>
                <p>Glimpses of our church life and community</p>
            </div>
            
            <div class="gallery-grid">
                <?php if (!empty($gallery)): ?>
                    <?php foreach ($gallery as $index => $image): ?>
                        <div class="gallery-item" data-aos="fade-up" data-aos-delay="<?php echo $index * 100; ?>">
                            <img src="<?php echo htmlspecialchars($image['file_url']); ?>" alt="<?php echo htmlspecialchars($image['title']); ?>">
                            <div class="gallery-overlay">
                                <h4><?php echo htmlspecialchars($image['title']); ?></h4>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="gallery-item" data-aos="fade-up">
                        <i class="fas fa-images"></i>
                    </div>
                    <div class="gallery-item" data-aos="fade-up" data-aos-delay="100">
                        <i class="fas fa-images"></i>
                    </div>
                    <div class="gallery-item" data-aos="fade-up" data-aos-delay="200">
                        <i class="fas fa-images"></i>
                    </div>
                    <div class="gallery-item" data-aos="fade-up" data-aos-delay="300">
                        <i class="fas fa-images"></i>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Statistics Section -->
    <section class="stats-section">
        <div class="container">
            <div class="section-title" data-aos="fade-up">
                <h2 style="color: white;">Our Impact</h2>
                <p style="color: rgba(254, 242, 242, 0.8);">God's faithfulness reflected in numbers</p>
            </div>
            
            <div class="stats-grid">
                <?php foreach ($church_stats as $index => $stat): ?>
                    <div class="stat-item" data-aos="fade-up" data-aos-delay="<?php echo $index * 100; ?>">
                        <div class="stat-number"><?php echo htmlspecialchars($stat['number']); ?></div>
                        <div class="stat-label"><i class="fas <?php echo htmlspecialchars($stat['icon']); ?>"></i> <?php echo htmlspecialchars($stat['label']); ?></div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Call to Action Section -->
    <section class="cta-section">
        <div class="container">
            <div class="cta-content" data-aos="fade-up">
                <h2>Ready to Join Our Family?</h2>
                <p>Experience the warmth and community of Salem Dominion Ministries</p>
                <div class="action-buttons">
                    <a href="register.php" class="btn btn-primary btn-lg">
                        <i class="fas fa-user-plus"></i> Become a Member
                    </a>
                    <a href="contact.php" class="btn btn-success btn-lg">
                        <i class="fas fa-envelope"></i> Contact Us
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Final Clean Footer -->
    <?php include 'components/final_footer.php'; ?>

    <!-- Developer WhatsApp Button -->
    <?php include 'components/developer_whatsapp.php'; ?>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- AOS Animation Library -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    
    <!-- Custom JS -->
    <script src="assets/js/index-perfect.js"></script>
    
    <!-- Initialize AOS -->
    <script>
        AOS.init({
            duration: 1000,
            once: true,
            offset: 100
        });
    </script>
</body>
</html>
