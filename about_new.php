<?php
// Complete Cleanup - Correct Spelling, Delete Old Versions, Remove Contact Info
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
    'email' => 'admin@salemdominionministries.com',
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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Salem Dominion Ministries - Welcome | Perfectly Clean & Updated</title>
    <meta name="description" content="Welcome to Salem Dominion Ministries - Your spiritual home where faith comes alive through worship, fellowship, and service.">
    <meta name="keywords" content="church, ministry, worship, fellowship, Salem Dominion, Christian, community">
    <meta name="author" content="Salem Dominion Ministries">
    
    <!-- Open Graph Meta Tags -->
    <meta property="og:title" content="Salem Dominion Ministries - Welcome | Perfectly Clean & Updated">
    <meta property="og:description" content="Your spiritual home where faith comes alive through worship, fellowship, and service.">
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?php echo (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>">
    <meta property="og:image" content="<?php echo (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST']; ?>/assets/general-pastor.jpeg">
    <meta property="og:site_name" content="Salem Dominion Ministries">
    
    <!-- Twitter Card Meta Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Salem Dominion Ministries - Welcome | Perfectly Clean & Updated">
    <meta name="twitter:description" content="Your spiritual home where faith comes alive through worship, fellowship, and service.">
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
    
    <!-- Final Clean CSS -->
    <link rel="stylesheet" href="assets/css/final-clean.css">
</head>
<body>
    <!-- Production-Ready Navigation -->
    <?php include 'components/universal_nav_perfect.php'; ?>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="hero-content">
                <h1 data-aos="fade-up">
                    <i class="fas fa-church"></i> 
                    <span style="display: inline-block; margin: 0 0.5rem;">Salem Dominion Ministries</span>
                </h1>
                <p class="lead" data-aos="fade-up" data-aos-delay="200">
                    Spreading the Gospel • Transforming Lives • Building Community
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

    <!-- About Section -->
    <section class="py-5">
        <div class="container">
            <div class="section-title" data-aos="fade-up">
                <h2>About Us</h2>
                <p>Learn more about our mission, vision, and values</p>
            </div>
            
            <div class="row">
                <div class="col-lg-6" data-aos="fade-up" data-aos-delay="200">
                    <div class="ministry-card">
                        <div class="ministry-icon">
                            <i class="fas fa-church"></i>
                        </div>
                        <h4>Our Mission</h4>
                        <p>To empower believers and transform communities through the power of the Gospel, creating a vibrant church family that reflects God's love and grace.</p>
                    </div>
                </div>
                <div class="col-lg-6" data-aos="fade-up" data-aos-delay="300">
                    <div class="ministry-card">
                        <div class="ministry-icon">
                            <i class="fas fa-eye"></i>
                        </div>
                        <h4>Our Vision</h4>
                        <p>To be a beacon of hope and spiritual transformation in our community, reaching people with the life-changing message of Jesus Christ.</p>
                    </div>
                </div>
                <div class="col-lg-6" data-aos="fade-up" data-aos-delay="400">
                    <div class="ministry-card">
                        <div class="ministry-icon">
                            <i class="fas fa-heart"></i>
                        </div>
                        <h4>Our Values</h4>
                        <p>We are committed to biblical teaching, authentic worship, genuine fellowship, compassionate service, and spiritual growth.</p>
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
                            <p>Nurturing young hearts in Christ's love through fun, engaging activities and biblical teaching.</p>
                        </div>
                    </div>
                    <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                        <div class="ministry-card">
                            <div class="ministry-icon">
                                <i class="fas fa-hands-helping"></i>
                            </div>
                            <h4>Outreach</h4>
                            <p>Spreading God's love to our community through service projects, evangelism, and compassionate outreach programs.</p>
                        </div>
                    </div>
                    <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                        <div class="ministry-card">
                            <div class="ministry-icon">
                                <i class="fas fa-music"></i>
                            </div>
                            <h4>Worship Ministry</h4>
                            <p>Leading our congregation in powerful worship and praise, creating an atmosphere where God's presence is tangibly felt.</p>
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
                                <h4>Welcome to Salem Dominion Ministries</h4>
                                <p class="small text-muted">Coming Soon</p>
                                <p>We're excited to share what God is doing in our church. Stay tuned for updates and announcements!</p>
                                <a href="news.php" class="btn btn-sm btn-outline-primary">All News</a>
                            </div>
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
                                <h4>Christmas Celebration</h4>
                                <p class="small text-muted">Sunday, December 15, 2024 - 10:00 AM</p>
                                <p>Celebrate the birth of our Lord with special service, carols, and fellowship time.</p>
                                <a href="#" class="btn btn-sm btn-outline-success">Learn More</a>
                            </div>
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
                <h2>Join Our Family</h2>
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

    <!-- Clean Footer - No Contact Info -->
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
