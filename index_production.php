<?php
// Production-ready error handling
error_reporting(0);
ini_set('display_errors', 0);
ini_set('log_errors', 1);

// Start output buffering
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

// Get dynamic data with error handling
try {
    $services = $db->select("SELECT * FROM service_times WHERE is_active = 1 ORDER BY FIELD(day_of_week, 'sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'), start_time");
    $ministries = $db->select("SELECT * FROM ministries WHERE is_active = 1 ORDER BY name");
    $news = $db->select("SELECT * FROM news WHERE status = 'published' ORDER BY published_at DESC LIMIT 3");
    $events = $db->select("SELECT * FROM events WHERE status = 'upcoming' ORDER BY event_date ASC LIMIT 3");
    $sermons = $db->select("SELECT * FROM sermons WHERE status = 'published' ORDER BY sermon_date DESC LIMIT 3");
    $gallery = $db->select("SELECT * FROM gallery WHERE status = 'published' ORDER BY created_at DESC LIMIT 6");
    
    // Get upcoming events for footer
    $upcoming_events = $db->select("SELECT * FROM events WHERE status = 'upcoming' AND event_date >= NOW() ORDER BY event_date ASC LIMIT 3");
    
    // Get recent gallery for footer
    $recent_gallery = $db->select("SELECT * FROM gallery WHERE status = 'published' ORDER BY created_at DESC LIMIT 3");
    
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
    <title>Salem Dominion Ministries - Welcome</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        }
        
        .hero-section {
            background: linear-gradient(135deg, #16a34a 0%, #15803d 100%);
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
            left: 0;
            right: 0;
            bottom: 0;
            background: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1440 320'%3E%3Cpath fill='%23ffffff' fill-opacity='0.1' d='M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,122.7C672,117,768,139,864,138.7C960,139,1056,117,1152,96C1248,75,1344,53,1392,42.7L1440,32L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z'%3E%3C/path%3E%3C/svg%3E") no-repeat;
            background-size: cover;
            opacity: 0.3;
        }
        
        .hero-content h1 {
            font-size: 3.5rem;
            font-weight: bold;
            margin-bottom: 20px;
            position: relative;
            z-index: 1;
        }
        
        .hero-content p {
            font-size: 1.3rem;
            margin-bottom: 30px;
            position: relative;
            z-index: 1;
        }
        
        .section-title {
            text-align: center;
            margin-bottom: 50px;
            position: relative;
        }
        
        .section-title h2 {
            font-size: 2.5rem;
            font-weight: bold;
            color: #1e293b;
            margin-bottom: 15px;
        }
        
        .section-title p {
            font-size: 1.1rem;
            color: #64748b;
        }
        
        .service-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            padding: 30px;
            text-align: center;
            margin-bottom: 30px;
            transition: transform 0.3s ease;
            height: 100%;
        }
        
        .service-card:hover {
            transform: translateY(-10px);
        }
        
        .service-icon {
            font-size: 3rem;
            color: #16a34a;
            margin-bottom: 20px;
        }
        
        .ministry-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            padding: 25px;
            margin-bottom: 25px;
            transition: transform 0.3s ease;
            height: 100%;
        }
        
        .ministry-card:hover {
            transform: translateY(-5px);
        }
        
        .news-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            overflow: hidden;
            margin-bottom: 25px;
            transition: transform 0.3s ease;
            height: 100%;
        }
        
        .news-card:hover {
            transform: translateY(-5px);
        }
        
        .news-image {
            height: 200px;
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #64748b;
        }
        
        .news-content {
            padding: 20px;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #16a34a 0%, #15803d 100%);
            border: none;
            border-radius: 25px;
            padding: 12px 30px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(22, 163, 74, 0.3);
        }
        
        .cta-section {
            background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%);
            color: white;
            padding: 80px 0;
            text-align: center;
            margin: 80px 0;
        }
        
        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 50px;
        }
        
        .gallery-item {
            height: 200px;
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #64748b;
            font-size: 2rem;
            transition: transform 0.3s ease;
        }
        
        .gallery-item:hover {
            transform: scale(1.05);
        }
        
        @media (max-width: 768px) {
            .hero-content h1 {
                font-size: 2.5rem;
            }
            
            .hero-content p {
                font-size: 1.1rem;
            }
            
            .section-title h2 {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <?php include 'components/universal_nav.php'; ?>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="hero-content">
                <h1><i class="fas fa-church"></i> Salem Dominion Ministries</h1>
                <p class="lead">Welcome to our spiritual home where faith comes alive</p>
                <a href="donations.php" class="btn btn-light btn-lg me-3">
                    <i class="fas fa-donate"></i> Give
                </a>
                <a href="book_pastor.php" class="btn btn-outline-light btn-lg">
                    <i class="fas fa-calendar"></i> Book Pastor
                </a>
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
                                <i class="fas fa-pray"></i>
                            </div>
                            <h4>Sunday Service</h4>
                            <p class="text-muted">Sunday at 10:30 AM</p>
                            <p class="small">Join us for powerful worship and life-changing message</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="service-card">
                            <div class="service-icon">
                                <i class="fas fa-bible"></i>
                            </div>
                            <h4>Bible Study</h4>
                            <p class="text-muted">Wednesday at 5:30 PM</p>
                            <p class="small">Deep dive into God's Word with fellowship</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="service-card">
                            <div class="service-icon">
                                <i class="fas fa-users"></i>
                            </div>
                            <h4>Youth Fellowship</h4>
                            <p class="text-muted">Friday at 5:00 PM</p>
                            <p class="small">Youth ministry gathering and fellowship</p>
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
                <p>Discover ways to get involved and serve</p>
            </div>
            
            <div class="row">
                <?php if (!empty($ministries)): ?>
                    <?php foreach ($ministries as $ministry): ?>
                        <div class="col-md-4">
                            <div class="ministry-card">
                                <h4><?php echo htmlspecialchars($ministry['name']); ?></h4>
                                <p class="text-muted"><?php echo htmlspecialchars($ministry['leader_name'] ?? ''); ?></p>
                                <p><?php echo htmlspecialchars($ministry['description'] ?? ''); ?></p>
                                <p class="small">
                                    <i class="fas fa-calendar"></i> <?php echo htmlspecialchars($ministry['meeting_day'] ?? ''); ?> 
                                    <?php echo htmlspecialchars($ministry['meeting_time'] ?? ''); ?>
                                </p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-md-4">
                        <div class="ministry-card">
                            <h4>Children Ministry</h4>
                            <p class="text-muted">Children Ministry Director</p>
                            <p>Spiritual education and fun activities for children ages 0-13</p>
                            <p class="small"><i class="fas fa-calendar"></i> Sunday 8:00 AM & 10:30 AM</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="ministry-card">
                            <h4>Youth Ministry</h4>
                            <p class="text-muted">Youth Director</p>
                            <p>Empowering teenagers to live for Christ</p>
                            <p class="small"><i class="fas fa-calendar"></i> Friday 5:00 PM</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="ministry-card">
                            <h4>Women Ministry</h4>
                            <p class="text-muted">Women Ministry Leader</p>
                            <p>Fellowship and spiritual growth for women</p>
                            <p class="small"><i class="fas fa-calendar"></i> Tuesday 6:00 PM</p>
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
                <p>Stay updated with what's happening in our church</p>
            </div>
            
            <div class="row">
                <?php if (!empty($news)): ?>
                    <?php foreach ($news as $item): ?>
                        <div class="col-md-4">
                            <div class="news-card">
                                <div class="news-image">
                                    <i class="fas fa-newspaper fa-3x"></i>
                                </div>
                                <div class="news-content">
                                    <h5><?php echo htmlspecialchars($item['title']); ?></h5>
                                    <p class="text-muted small"><?php echo date('M j, Y', strtotime($item['published_at'])); ?></p>
                                    <p><?php echo htmlspecialchars(substr($item['excerpt'] ?? '', 0, 100)); ?>...</p>
                                    <a href="#" class="btn btn-sm btn-primary">Read More</a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-md-4">
                        <div class="news-card">
                            <div class="news-image">
                                <i class="fas fa-bullhorn fa-3x"></i>
                            </div>
                            <div class="news-content">
                                <h5>Church Anniversary Celebration</h5>
                                <p class="text-muted small">March 31, 2026</p>
                                <p>Join us as we celebrate 10 years of God's faithfulness...</p>
                                <a href="#" class="btn btn-sm btn-primary">Read More</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="news-card">
                            <div class="news-image">
                                <i class="fas fa-users fa-3x"></i>
                            </div>
                            <div class="news-content">
                                <h5>New Youth Program Launch</h5>
                                <p class="text-muted small">March 31, 2026</p>
                                <p>Starting next month, we are launching new programs...</p>
                                <a href="#" class="btn btn-sm btn-primary">Read More</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="news-card">
                            <div class="news-image">
                                <i class="fas fa-heart fa-3x"></i>
                            </div>
                            <div class="news-content">
                                <h5>Community Outreach</h5>
                                <p class="text-muted small">March 31, 2026</p>
                                <p>Our church is reaching out to the community with love...</p>
                                <a href="#" class="btn btn-sm btn-primary">Read More</a>
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
            <div class="section-title">
                <h2>Gallery</h2>
                <p>Glimpses of our church life and activities</p>
            </div>
            
            <div class="gallery-grid">
                <?php if (!empty($gallery)): ?>
                    <?php foreach ($gallery as $item): ?>
                        <div class="gallery-item">
                            <i class="fas fa-image"></i>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="gallery-item">
                        <i class="fas fa-church"></i>
                    </div>
                    <div class="gallery-item">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="gallery-item">
                        <i class="fas fa-music"></i>
                    </div>
                    <div class="gallery-item">
                        <i class="fas fa-pray"></i>
                    </div>
                    <div class="gallery-item">
                        <i class="fas fa-bible"></i>
                    </div>
                    <div class="gallery-item">
                        <i class="fas fa-heart"></i>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="text-center">
                <a href="gallery.php" class="btn btn-primary btn-lg">
                    <i class="fas fa-images"></i> View Full Gallery
                </a>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <div class="container">
            <h2>Get Involved Today</h2>
            <p class="lead">Join our community and experience the love of Christ</p>
            <div class="mt-4">
                <a href="register.php" class="btn btn-light btn-lg me-3">
                    <i class="fas fa-user-plus"></i> Become a Member
                </a>
                <a href="contact.php" class="btn btn-outline-light btn-lg">
                    <i class="fas fa-envelope"></i> Contact Us
                </a>
            </div>
        </div>
    </section>

    <!-- Include Footer -->
    <?php include 'components/ultimate_footer_new.php'; ?>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
