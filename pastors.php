<?php
// Pastors Page - Perfect Design with All Pastor Images
require_once 'config_force.php';

// Initialize variables to prevent undefined errors
$upcoming_events = [];

// Get dynamic data with forced database connection
try {
    $db = new Database();
    
    $upcoming_events = $db->select("SELECT title, event_date FROM events WHERE status = 'upcoming' AND event_date >= NOW() ORDER BY event_date ASC LIMIT 3");
    
} catch (Exception $e) {
    $upcoming_events = [];
}

// Pastors information with all images from assets folder
$pastors_info = [
    [
        'name' => 'Apostle Faty Musasizi',
        'title' => 'Senior Pastor & Founder',
        'bio' => 'Apostle Faty Musasizi is a passionate servant of God with over 25 years of ministry experience. She founded Salem Dominion Ministries with a vision to empower believers and transform communities through the power of the Gospel.',
        'email' => 'admin@saleldominionministries.com',
        'phone' => '+256 753 244480',
        'image' => 'assets/general-pastor.jpeg',
        'specialties' => ['Leadership', 'Spiritual Guidance', 'Community Building', 'Mentorship']
    ],
    [
        'name' => 'Evangelist Kisakye Halima',
        'title' => 'Associate Pastor',
        'bio' => 'Evangelist Kisakye Halima serves with dedication and passion for spreading the Gospel. With a heart for outreach and evangelism, she brings energy and enthusiasm to every ministry opportunity.',
        'email' => 'halima@saleldominionministries.com',
        'phone' => '+256 753 244481',
        'image' => 'assets/Evangelist-kisakye-Halima-Z7IQJGGv.jpeg',
        'specialties' => ['Evangelism', 'Outreach', 'Teaching', 'Prayer Ministry']
    ],
    [
        'name' => 'Pastor Nabulya Joyce',
        'title' => 'Youth Pastor',
        'bio' => 'Pastor Nabulya Joyce has a special calling to work with young people, helping them navigate the challenges of growing up while building a strong foundation of faith that will last a lifetime.',
        'email' => 'joyce@saleldominionministries.com',
        'phone' => '+256 753 244482',
        'image' => 'assets/PASTOR-NABULYA-JOYCE-BdB4SkbM.jpeg',
        'specialties' => ['Youth Ministry', 'Mentorship', 'Leadership Training', 'Bible Study']
    ],
    [
        'name' => 'Pastor Damali Namwuma',
        'title' => 'Associate Pastor',
        'bio' => 'Pastor Damali Namwuma brings wisdom and experience to the ministry team. With a background in theology and pastoral care, he provides compassionate guidance to the congregation.',
        'email' => 'damali@saleldominionministries.com',
        'phone' => '+256 753 244483',
        'image' => 'assets/Pastor-damali-namwuma-DSRkNJ6q.png',
        'specialties' => ['Pastoral Care', 'Counseling', 'Teaching', 'Family Ministry']
    ],
    [
        'name' => 'Pastor Miriam Gerald',
        'title' => 'Worship Leader',
        'bio' => 'Pastor Miriam Gerald leads our worship ministry with a heart for praise and a passion for creating an atmosphere where people can encounter God\'s presence through music and worship.',
        'email' => 'miriam@saleldominionministries.com',
        'phone' => '+256 753 244484',
        'image' => 'assets/Pastor-miriam-Gerald-CApzM7-5.jpeg',
        'specialties' => ['Worship Leading', 'Music Ministry', 'Creative Arts', 'Spiritual Formation']
    ],
    [
        'name' => 'Pastor Jonathan Ngobi',
        'title' => 'Missions Director',
        'bio' => 'Pastor Jonathan Ngobi directs our missions and outreach programs, taking the Gospel beyond the church walls and into the community with compassion and service.',
        'email' => 'jonathan@saleldominionministries.com',
        'phone' => '+256 753 244485',
        'image' => 'assets/pastor-jonathan-Ngobi-B-Ezegv1.jpeg',
        'specialties' => ['Missions', 'Community Outreach', 'Service Projects', 'Partnership Development']
    ],
    [
        'name' => 'Pastor Jotham Bright Mulinde',
        'title' => 'Discipleship Pastor',
        'bio' => 'Pastor Jotham Bright Mulinde focuses on helping believers grow deeper in their faith through discipleship, mentoring, and spiritual formation programs.',
        'email' => 'jotham@saleldominionministries.com',
        'phone' => '+256 753 244486',
        'image' => 'assets/pastor-jotham-Bright-Mulinde-Ca8YLs3V.jpeg',
        'specialties' => ['Discipleship', 'Spiritual Formation', 'Mentoring', 'Bible Teaching']
    ]
];

// Ministry statistics
$ministry_stats = [
    ['number' => '7', 'label' => 'Dedicated Pastors', 'icon' => 'fa-users'],
    ['number' => '25+', 'label' => 'Years Combined Ministry', 'icon' => 'fa-calendar'],
    ['number' => '500+', 'label' => 'Lives Impacted Weekly', 'icon' => 'fa-heart'],
    ['number' => '1000+', 'label' => 'Community Members', 'icon' => 'fa-church']
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Our Pastors - Salem Dominion Ministries</title>
    <meta name="description" content="Meet our dedicated team of pastors at Salem Dominion Ministries - servant leaders committed to spreading God's love and building His kingdom.">
    <meta name="keywords" content="pastors, church leadership, ministry team, Salem Dominion, spiritual leaders">
    <meta name="author" content="Salem Dominion Ministries">
    
    <!-- Open Graph Meta Tags -->
    <meta property="og:title" content="Our Pastors - Salem Dominion Ministries">
    <meta property="og:description" content="Meet our dedicated team of pastors committed to spreading God's love.">
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?php echo (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>">
    <meta property="og:image" content="<?php echo (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST']; ?>/assets/general-pastor.jpeg">
    <meta property="og:site_name" content="Salem Dominion Ministries">
    
    <!-- Canonical URL -->
    <link rel="canonical" href="<?php echo (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>">
    
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
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
    <link rel="shortcut icon" type="image/x-icon" href="/favicon.ico">
    <link rel="apple-touch-icon" href="/favicon.ico">
    <link rel="icon" sizes="192x192" href="/favicon.ico">
    <link rel="icon" sizes="512x512" href="/favicon.ico">
</head>
<body>
    <!-- Production-Ready Navigation -->
    <?php include 'components/universal_nav_perfect.php'; ?>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="hero-content">
                <h1 data-aos="fade-up"><i class="fas fa-users"></i> Our Pastoral Team</h1>
                <p class="lead" data-aos="fade-up" data-aos-delay="200">Meet our dedicated servant leaders committed to God's work</p>
                <div class="action-buttons" data-aos="fade-up" data-aos-delay="400">
                    <a href="#team" class="btn btn-primary">
                        <i class="fas fa-users"></i> Meet the Team
                    </a>
                    <a href="contact.php" class="btn btn-success">
                        <i class="fas fa-envelope"></i> Contact Pastors
                    </a>
                    <a href="book_pastor.php" class="btn btn-purple">
                        <i class="fas fa-calendar"></i> Book a Pastor
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Ministry Statistics -->
    <section class="stats-section">
        <div class="container">
            <div class="section-title" data-aos="fade-up">
                <h2 style="color: white;">Our Ministry Impact</h2>
                <p style="color: rgba(254, 242, 242, 0.8);">God's faithfulness through our pastoral team</p>
            </div>
            
            <div class="stats-grid">
                <?php foreach ($ministry_stats as $index => $stat): ?>
                    <div class="stat-item" data-aos="fade-up" data-aos-delay="<?php echo $index * 100; ?>">
                        <div class="stat-number"><?php echo htmlspecialchars($stat['number']); ?></div>
                        <div class="stat-label"><i class="fas <?php echo htmlspecialchars($stat['icon']); ?>"></i> <?php echo htmlspecialchars($stat['label']); ?></div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Pastoral Team Section -->
    <section id="team" class="py-5">
        <div class="container">
            <div class="section-title" data-aos="fade-up">
                <h2>Meet Our Pastors</h2>
                <p>Dedicated servants called to minister God's people</p>
            </div>
            
            <div class="row">
                <?php foreach ($pastors_info as $index => $pastor): ?>
                    <div class="col-lg-6" data-aos="fade-up" data-aos-delay="<?php echo $index * 100; ?>">
                        <div class="pastor-card">
                            <div class="pastor-image">
                                <img src="<?php echo htmlspecialchars($pastor['image']); ?>" alt="<?php echo htmlspecialchars($pastor['name']); ?>" onerror="this.style.display='none';">
                                <div class="pastor-overlay">
                                    <i class="fas fa-cross"></i>
                                </div>
                            </div>
                            <div class="pastor-info">
                                <h3><?php echo htmlspecialchars($pastor['name']); ?></h3>
                                <div class="title"><?php echo htmlspecialchars($pastor['title']); ?></div>
                                <p class="bio"><?php echo htmlspecialchars($pastor['bio']); ?></p>
                                
                                <div class="pastor-contact">
                                    <div><i class="fas fa-envelope"></i> <?php echo htmlspecialchars($pastor['email']); ?></div>
                                    <div><i class="fas fa-phone"></i> <?php echo htmlspecialchars($pastor['phone']); ?></div>
                                </div>
                                
                                <!-- Specialties -->
                                <div class="pastor-specialties" style="margin-top: 1.5rem;">
                                    <h5 style="color: var(--primary-color); margin-bottom: 1rem;">Ministry Focus:</h5>
                                    <div class="row">
                                        <?php foreach ($pastor['specialties'] as $specialty): ?>
                                            <div class="col-6" style="margin-bottom: 0.5rem;">
                                                <span style="background: var(--gradient-secondary); color: white; padding: 0.3rem 0.8rem; border-radius: 20px; font-size: 0.8rem; display: inline-block;">
                                                    <i class="fas fa-star" style="margin-right: 0.3rem;"></i><?php echo htmlspecialchars($specialty); ?>
                                                </span>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Ministry Philosophy Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="section-title" data-aos="fade-up">
                <h2>Our Ministry Philosophy</h2>
                <p>The heart behind our pastoral leadership</p>
            </div>
            
            <div class="row">
                <div class="col-md-4" data-aos="fade-up">
                    <div class="ministry-card">
                        <div class="ministry-icon">
                            <i class="fas fa-bible"></i>
                        </div>
                        <h4>Biblical Foundation</h4>
                        <p>Our ministry is built on the solid foundation of God's Word, with every teaching and decision rooted in Scripture.</p>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="ministry-card">
                        <div class="ministry-icon">
                            <i class="fas fa-heart"></i>
                        </div>
                        <h4>Love & Compassion</h4>
                        <p>We minister with genuine love and compassion, seeking to understand and meet the real needs of God's people.</p>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="ministry-card">
                        <div class="ministry-icon">
                            <i class="fas fa-hands-helping"></i>
                        </div>
                        <h4>Service & Outreach</h4>
                        <p>We believe in active service, reaching beyond our walls to make a tangible difference in our community.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Upcoming Events Section -->
    <section class="py-5">
        <div class="container">
            <div class="section-title" data-aos="fade-up">
                <h2>Pastoral Events</h2>
                <p>Special gatherings and services led by our pastoral team</p>
            </div>
            
            <div class="row">
                <?php if (!empty($upcoming_events)): ?>
                    <?php foreach ($upcoming_events as $index => $event): ?>
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
                                    <div class="day">20</div>
                                    <div class="month">DEC</div>
                                </div>
                            </div>
                            <div class="event-content">
                                <h4>Pastors' Conference</h4>
                                <p class="small text-muted">Friday, December 20, 2024 - 6:00 PM</p>
                                <p>Annual gathering for spiritual renewal and ministry training</p>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Call to Action Section -->
    <section class="cta-section">
        <div class="container">
            <div class="cta-content" data-aos="fade-up">
                <h2>Connect With Our Pastors</h2>
                <p>Our pastoral team is here to support you in your spiritual journey</p>
                <div class="action-buttons">
                    <a href="contact.php" class="btn btn-primary btn-lg">
                        <i class="fas fa-envelope"></i> Contact Pastors
                    </a>
                    <a href="book_pastor.php" class="btn btn-success btn-lg">
                        <i class="fas fa-calendar"></i> Schedule Meeting
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
