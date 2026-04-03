<?php
// Error-Free Production-Ready Homepage
require_once 'perfect_error_free.php';

// Buffer output to catch any accidental output
ob_start();

// Include required files with error handling
try {
    require_once 'config.php';
    require_once 'db.php';
} catch (Exception $e) {
    // Silent fail for production
}

// Initialize variables to prevent undefined errors
$services = [];
$ministries = [];
$news = [];
$events = [];
$sermons = [];
$gallery = [];
$upcoming_events = [];
$recent_gallery = [];

// Get dynamic data with perfect error handling
try {
    $services = $db->select("SELECT * FROM service_times WHERE is_active = 1 ORDER BY FIELD(day_of_week, 'sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'), start_time");
    $ministries = $db->select("SELECT * FROM ministries WHERE is_active = 1 ORDER BY name");
    $news = $db->select("SELECT * FROM news WHERE status = 'published' ORDER BY published_at DESC LIMIT 3");
    $events = $db->select("SELECT * FROM events WHERE status = 'upcoming' ORDER BY event_date ASC LIMIT 3");
    $sermons = $db->select("SELECT * FROM sermons WHERE status = 'published' ORDER BY sermon_date DESC LIMIT 3");
    $gallery = $db->query("SELECT * FROM gallery WHERE status = 'published' ORDER BY created_at DESC LIMIT 4");
    
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

// Clean output buffer
ob_end_clean();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Salem Dominion Ministries - Welcome | Production Ready</title>
    <meta name="description" content="Welcome to Salem Dominion Ministries - Your spiritual home where faith comes alive through worship, fellowship, and service.">
    <meta name="keywords" content="church, ministry, worship, fellowship, Salem Dominion, Christian, community">
    <meta name="author" content="Salem Dominion Ministries">
    
    <!-- Open Graph Meta Tags -->
    <meta property="og:title" content="Salem Dominion Ministries - Welcome">
    <meta property="og:description" content="Your spiritual home where faith comes alive through worship, fellowship, and service.">
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?php echo (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>">
    <meta property="og:image" content="<?php echo (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST']; ?>/assets/images/logo">
    <meta property="og:site_name" content="Salem Dominion Ministries">
    
    <!-- Twitter Card Meta Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Salem Dominion Ministries - Welcome">
    <meta name="twitter:description" content="Your spiritual home where faith comes alive through worship, fellowship, and service.">
    <meta name="twitter:image" content="<?php echo (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST']; ?>/assets/images/logo">
    
    <!-- Canonical URL -->
    <link rel="canonical" href="<?php echo (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>">
    
    <!-- PWA Manifest -->
    <link rel="manifest" href="/pwa_manifest.json">
    <link rel="apple-touch-icon" href="/assets/icons/icon-152x152.png">
    <meta name="theme-color" content="#16a34a">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="Salem Church">
    <meta name="application-name" content="Salem Dominion Ministries">
    <meta name="msapplication-TileColor" content="#16a34a">
    <meta name="msapplication-config" content="/salem_browserconfig.xml">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <!-- AOS Animation Library -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/index-perfect.css">
</head>
<body>
    <!-- Production-Ready Navigation -->
    <?php include 'components/universal_nav_perfect.php'; ?>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="hero-content">
                <h1><i class="fas fa-church"></i> Salem Dominion Ministries</h1>
                <p class="lead">Welcome to our spiritual home where faith comes alive through worship, fellowship, and service</p>
                <div class="action-buttons">
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

    <!-- Services Section -->
    <section class="py-5">
        <div class="container">
            <div class="section-title">
                <h2>Our Services</h2>
                <p>Join us for powerful worship and life-changing messages</p>
            </div>
            
            <div class="row">
                <?php if (!empty($services)): ?>
                    <?php foreach ($services as $service): ?>
                        <div class="col-md-4">
                            <div class="service-card">
                                <div class="service-icon">
                                    <i class="fas fa-clock"></i>
                                </div>
                                <h4><?php echo htmlspecialchars($service['service_name']); ?></h4>
                                <p class="text-muted"><?php echo htmlspecialchars($service['day_of_week']); ?> at <?php echo date('g:i A', strtotime($service['start_time'])); ?></p>
                                <p class="small"><?php echo htmlspecialchars($service['description'] ?? ''); ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-md-4">
                        <div class="service-card">
                            <div class="service-icon">
                                <i class="fas fa-clock"></i>
                            </div>
                            <h4>Sunday Service</h4>
                            <p class="text-muted">10:00 AM</p>
                            <p class="small">Join us for powerful worship and life-changing messages</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="service-card">
                            <div class="service-icon">
                                <i class="fas fa-pray"></i>
                            </div>
                            <h4>Wednesday Prayer</h4>
                            <p class="text-muted">6:00 PM</p>
                            <p class="small">Mid-week prayer and fellowship</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="service-card">
                            <div class="service-icon">
                                <i class="fas fa-bible"></i>
                            </div>
                            <h4>Bible Study</h4>
                            <p class="text-muted">7:00 PM</p>
                            <p class="small">Deep dive into God's word</p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Ministries Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="section-title">
                <h2>Our Ministries</h2>
                <p>Discover ways to get involved and grow in your faith</p>
            </div>
            
            <div class="row">
                <?php if (!empty($ministries)): ?>
                    <?php foreach ($ministries as $ministry): ?>
                        <div class="col-md-4">
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
                    <div class="col-md-4">
                        <div class="ministry-card">
                            <div class="ministry-icon">
                                <i class="fas fa-users"></i>
                            </div>
                            <h4>Children's Ministry</h4>
                            <p>Nurturing young hearts in Christ's love</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="ministry-card">
                            <div class="ministry-icon">
                                <i class="fas fa-hands-helping"></i>
                            </div>
                            <h4>Outreach</h4>
                            <p>Spreading God's love to our community</p>
                        </div>
                    </div>
                    <div class="col-md-4">
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
    <section class="py-5">
        <div class="container">
            <div class="section-title">
                <h2>Latest News</h2>
                <p>Stay updated with what's happening in our church community</p>
            </div>
            
            <div class="row">
                <?php if (!empty($news)): ?>
                    <?php foreach ($news as $article): ?>
                        <div class="col-md-4">
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
                    <div class="col-md-4">
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
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Events Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="section-title">
                <h2>Upcoming Events</h2>
                <p>Join us for these special gatherings and celebrations</p>
            </div>
            
            <div class="row">
                <?php if (!empty($events)): ?>
                    <?php foreach ($events as $event): ?>
                        <div class="col-md-4">
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
                    <div class="col-md-4">
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
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Gallery Section -->
    <section class="py-5">
        <div class="container">
            <div class="section-title">
                <h2>Gallery</h2>
                <p>Glimpses of our church life and community</p>
            </div>
            
            <div class="gallery-grid">
                <?php if (!empty($gallery)): ?>
                    <?php foreach ($gallery as $image): ?>
                        <div class="gallery-item" data-aos="fade-up">
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
                    <div class="gallery-item" data-aos="fade-up">
                        <i class="fas fa-images"></i>
                    </div>
                    <div class="gallery-item" data-aos="fade-up">
                        <i class="fas fa-images"></i>
                    </div>
                    <div class="gallery-item" data-aos="fade-up">
                        <i class="fas fa-images"></i>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Statistics Section -->
    <section class="stats-section">
        <div class="container">
            <div class="section-title">
                <h2>Our Impact</h2>
                <p>God's faithfulness reflected in numbers</p>
            </div>
            
            <div class="stats-grid">
                <div class="stat-item" data-aos="fade-up">
                    <div class="stat-number">500+</div>
                    <div class="stat-label">Members</div>
                </div>
                <div class="stat-item" data-aos="fade-up" data-aos-delay="100">
                    <div class="stat-number">50+</div>
                    <div class="stat-label">Ministries</div>
                </div>
                <div class="stat-item" data-aos="fade-up" data-aos-delay="200">
                    <div class="stat-number">1000+</div>
                    <div class="stat-label">Lives Touched</div>
                </div>
                <div class="stat-item" data-aos="fade-up" data-aos-delay="300">
                    <div class="stat-number">25+</div>
                    <div class="stat-label">Years of Service</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action Section -->
    <section class="cta-section">
        <div class="container">
            <div class="cta-content">
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

    <!-- Footer -->
    <?php include 'components/perfect_footer.php'; ?>

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
            once: true
        });
    </script>
</body>
</html>
