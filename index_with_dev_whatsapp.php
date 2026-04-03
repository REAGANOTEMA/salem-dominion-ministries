<?php
// Production-Ready Homepage with Developer WhatsApp
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
    <meta property="og:image" content="<?php echo (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST']; ?>/assets/images/APOSTLE-IRENE-MIREMBE-CwWfzcRx.jpeg">
    <meta property="og:site_name" content="Salem Dominion Ministries">
    
    <!-- Twitter Card Meta Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Salem Dominion Ministries - Welcome">
    <meta name="twitter:description" content="Your spiritual home where faith comes alive through worship, fellowship, and service.">
    <meta name="twitter:image" content="<?php echo (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST']; ?>/assets/images/APOSTLE-IRENE-MIREMBE-CwWfzcRx.jpeg">
    
    <!-- Canonical URL -->
    <link rel="canonical" href="<?php echo (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Inter', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            margin: 0;
            padding: 0;
            line-height: 1.6;
        }
        
        .hero-section {
            background: linear-gradient(135deg, #16a34a 0%, #15803d 100%);
            color: white;
            padding: 100px 0 80px;
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
            font-weight: 700;
            margin-bottom: 20px;
            position: relative;
            z-index: 1;
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .hero-content p {
            font-size: 1.3rem;
            margin-bottom: 35px;
            position: relative;
            z-index: 1;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }
        
        .section-title {
            text-align: center;
            margin-bottom: 50px;
            position: relative;
        }
        
        .section-title h2 {
            font-size: 2.5rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 15px;
        }
        
        .section-title p {
            font-size: 1.1rem;
            color: #64748b;
            max-width: 600px;
            margin: 0 auto;
        }
        
        .service-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            padding: 30px;
            text-align: center;
            margin-bottom: 30px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            height: 100%;
            border: 1px solid rgba(0,0,0,0.05);
        }
        
        .service-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.12);
        }
        
        .service-icon {
            font-size: 3rem;
            color: #16a34a;
            margin-bottom: 20px;
            display: inline-block;
            width: 80px;
            height: 80px;
            line-height: 80px;
            background: rgba(22, 163, 74, 0.1);
            border-radius: 50%;
            transition: all 0.3s ease;
        }
        
        .service-card:hover .service-icon {
            background: #16a34a;
            color: white;
            transform: scale(1.1);
        }
        
        .ministry-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.06);
            padding: 25px;
            margin-bottom: 25px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            height: 100%;
            border: 1px solid rgba(0,0,0,0.05);
        }
        
        .ministry-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
        }
        
        .news-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.06);
            overflow: hidden;
            margin-bottom: 25px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            height: 100%;
            border: 1px solid rgba(0,0,0,0.05);
        }
        
        .news-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
        }
        
        .news-image {
            height: 180px;
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #64748b;
            font-size: 3rem;
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
            color: white;
            text-decoration: none;
            display: inline-block;
            font-size: 1rem;
        }
        
        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(22, 163, 74, 0.3);
            color: white;
        }
        
        .btn-success {
            background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%);
            border: none;
            border-radius: 25px;
            padding: 12px 30px;
            font-weight: 600;
            transition: all 0.3s ease;
            color: white;
            text-decoration: none;
            display: inline-block;
            font-size: 1rem;
        }
        
        .btn-success:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(14, 165, 233, 0.3);
            color: white;
        }
        
        .btn-purple {
            background: linear-gradient(135deg, #7c3aed 0%, #6d28d0 100%);
            border: none;
            border-radius: 25px;
            padding: 12px 30px;
            font-weight: 600;
            transition: all 0.3s ease;
            color: white;
            text-decoration: none;
            display: inline-block;
            font-size: 1rem;
        }
        
        .btn-purple:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(124, 58, 237, 0.3);
            color: white;
        }
        
        .cta-section {
            background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%);
            color: white;
            padding: 80px 0;
            text-align: center;
            margin: 80px 0;
            position: relative;
            overflow: hidden;
        }
        
        .cta-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1440 320'%3E%3Cpath fill='%23ffffff' fill-opacity='0.05' d='M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,122.7C672,117,768,139,864,138.7C960,139,1056,117,1152,96C1248,75,1344,53,1392,42.7L1440,32L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z'%3E%3C/path%3E%3C/svg%3E") no-repeat;
            background-size: cover;
            opacity: 0.3;
        }
        
        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }
        
        .gallery-item {
            height: 200px;
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #64748b;
            font-size: 2rem;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            cursor: pointer;
            border: 1px solid rgba(0,0,0,0.05);
        }
        
        .gallery-item:hover {
            transform: scale(1.05);
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
        }
        
        .quick-actions {
            background: linear-gradient(135deg, #f8fafc 0%, #ffffff 100%);
            padding: 60px 0;
            text-align: center;
            border-top: 1px solid rgba(0,0,0,0.05);
        }
        
        .action-buttons {
            display: flex;
            justify-content: center;
            gap: 20px;
            flex-wrap: wrap;
            margin-top: 30px;
        }
        
        .stats-section {
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
            color: white;
            padding: 60px 0;
            text-align: center;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 30px;
            max-width: 800px;
            margin: 0 auto;
        }
        
        .stat-item {
            text-align: center;
        }
        
        .stat-number {
            font-size: 3rem;
            font-weight: 700;
            color: #16a34a;
            margin-bottom: 10px;
        }
        
        .stat-label {
            font-size: 1.1rem;
            color: #94a3b8;
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
            
            .gallery-grid {
                grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
                gap: 15px;
            }
            
            .action-buttons {
                flex-direction: column;
                align-items: center;
            }
            
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 20px;
            }
            
            .stat-number {
                font-size: 2.5rem;
            }
        }
        
        @media (max-width: 480px) {
            .hero-section {
                padding: 80px 0 60px;
            }
            
            .hero-content h1 {
                font-size: 2rem;
            }
            
            .section-title h2 {
                font-size: 1.8rem;
            }
            
            .gallery-grid {
                grid-template-columns: 1fr;
            }
            
            .stats-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
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
                                    <i class="fas fa-newspaper"></i>
                                </div>
                                <div class="news-content">
                                    <h5><?php echo htmlspecialchars($item['title']); ?></h5>
                                    <p class="text-muted small"><?php echo date('M j, Y', strtotime($item['published_at'])); ?></p>
                                    <p><?php echo htmlspecialchars(substr($item['excerpt'] ?? '', 0, 80)); ?>...</p>
                                    <a href="#" class="btn btn-sm btn-primary">Read More</a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-md-4">
                        <div class="news-card">
                            <div class="news-image">
                                <i class="fas fa-bullhorn"></i>
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
                                <i class="fas fa-users"></i>
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
                                <i class="fas fa-heart"></i>
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

    <!-- Stats Section -->
    <section class="stats-section">
        <div class="container">
            <div class="section-title">
                <h2 style="color: white;">Our Impact</h2>
                <p style="color: #94a3b8;">Growing together in faith and service</p>
            </div>
            
            <div class="stats-grid">
                <div class="stat-item">
                    <div class="stat-number"><?php echo count($services); ?></div>
                    <div class="stat-label">Weekly Services</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number"><?php echo count($ministries); ?></div>
                    <div class="stat-label">Active Ministries</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number"><?php echo count($events); ?></div>
                    <div class="stat-label">Upcoming Events</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">24/7</div>
                    <div class="stat-label">Prayer Support</div>
                </div>
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
            <div class="section-title">
                <h2 style="color: white;">Ready to Join Our Community?</h2>
                <p style="color: rgba(255,255,255,0.9);">Take the next step in your spiritual journey</p>
            </div>
            <div class="action-buttons">
                <a href="register.php" class="btn btn-primary">
                    <i class="fas fa-user-plus"></i> Become a Member
                </a>
                <a href="donations.php" class="btn btn-success">
                    <i class="fas fa-donate"></i> Make Donation
                </a>
                <a href="book_pastor.php" class="btn btn-purple">
                    <i class="fas fa-calendar"></i> Book Pastor
                </a>
                <a href="contact.php" class="btn btn-primary">
                    <i class="fas fa-envelope"></i> Contact Us
                </a>
            </div>
        </div>
    </section>

    <!-- Quick Actions Section -->
    <section class="quick-actions">
        <div class="container">
            <div class="section-title">
                <h2>Get Connected</h2>
                <p>Stay connected with our church community</p>
            </div>
            <div class="action-buttons">
                <a href="events.php" class="btn btn-primary">
                    <i class="fas fa-calendar"></i> View Events
                </a>
                <a href="sermons.php" class="btn btn-purple">
                    <i class="fas fa-bible"></i> Listen to Sermons
                </a>
                <a href="https://wa.me/256753244480?text=Hello!%20I%20need%20help%20with%20Salem%20Dominion%20Ministries%20website." 
                   class="btn btn-success" 
                   target="_blank" 
                   rel="noopener noreferrer">
                    <i class="fab fa-whatsapp"></i> WhatsApp Support
                </a>
            </div>
        </div>
    </section>

    <!-- Include Perfect Footer -->
    <?php include 'components/perfect_footer.php'; ?>
    
    <!-- Include Developer WhatsApp Component -->
    <?php include 'components/developer_whatsapp.php'; ?>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Production Ready Scripts -->
    <script>
        // Production-ready functionality
        document.addEventListener('DOMContentLoaded', function() {
            // Smooth scrolling for anchor links
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
            
            // Add loading complete class
            document.body.classList.add('loaded');
        });
        
        // Performance monitoring (optional)
        if ('performance' in window) {
            window.addEventListener('load', function() {
                setTimeout(function() {
                    const perfData = window.performance.timing;
                    const pageLoadTime = perfData.loadEventEnd - perfData.navigationStart;
                    console.log('Page load time: ' + pageLoadTime + 'ms');
                }, 0);
            });
        }
    </script>
</body>
</html>
