<?php
// Perfect Iconic Responsive Homepage
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
    'name' => 'Apostle Irene Mirembe',
    'title' => 'Senior Pastor & Founder',
    'bio' => 'Apostle Irene Mirembe is a passionate servant of God with over 25 years of ministry experience. She founded Salem Dominion Ministries with a vision to empower believers and transform communities through the power of the Gospel.',
    'email' => 'pastor@saleldominionministries.com',
    'phone' => '+256 753 244480',
    'image' => 'assets/images/pastor.jpeg'
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Salem Dominion Ministries - Welcome | Iconic Design</title>
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
    
    <!-- Custom Iconic CSS -->
    <link rel="stylesheet" href="assets/css/iconic-responsive.css">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
</head>
<body>
    <!-- Production-Ready Navigation -->
    <?php include 'components/universal_nav_perfect.php'; ?>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="hero-content">
                <h1 data-aos="fade-up"><i class="fas fa-church"></i> Salem Dominion Ministries</h1>
                <p class="lead" data-aos="fade-up" data-aos-delay="200">Welcome to our spiritual home where faith comes alive through worship, fellowship, and service</p>
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
    <section class="pastor-section">
        <div class="container">
            <div class="section-title" data-aos="fade-up">
                <h2>Meet Our Pastor</h2>
                <p>Learn more about our spiritual leader</p>
            </div>
            
            <div class="pastor-card" data-aos="fade-up" data-aos-delay="200">
                <div class="pastor-image">
                    <img src="<?php echo htmlspecialchars($pastor_info['image']); ?>" alt="<?php echo htmlspecialchars($pastor_info['name']); ?>" onerror="this.style.display='none';">
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
    </section>

    <!-- Services Section -->
    <section class="py-5">
        <div class="container">
            <div class="section-title" data-aos="fade-up">
                <h2>Our Services</h2>
                <p>Join us for powerful worship and life-changing messages</p>
            </div>
            
            <div class="row">
                <?php if (!empty($services)): ?>
                    <?php foreach ($services as $index => $service): ?>
                        <div class="col-md-4" data-aos="fade-up" data-aos-delay="<?php echo $index * 100; ?>">
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
                    <div class="col-md-4" data-aos="fade-up">
                        <div class="service-card">
                            <div class="service-icon">
                                <i class="fas fa-clock"></i>
                            </div>
                            <h4>Sunday Service</h4>
                            <p class="text-muted">10:00 AM</p>
                            <p class="small">Join us for powerful worship and life-changing messages</p>
                        </div>
                    </div>
                    <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                        <div class="service-card">
                            <div class="service-icon">
                                <i class="fas fa-pray"></i>
                            </div>
                            <h4>Wednesday Prayer</h4>
                            <p class="text-muted">6:00 PM</p>
                            <p class="small">Mid-week prayer and fellowship</p>
                        </div>
                    </div>
                    <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
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
            once: true,
            offset: 100
        });
    </script>
</body>
</html>
