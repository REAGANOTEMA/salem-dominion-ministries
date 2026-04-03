<?php
// Children's Ministry Page - Perfect Design with All Children Images
require_once 'config_force.php';

// Initialize variables to prevent undefined errors
$children_activities = [];
$upcoming_events = [];

// Get dynamic data with forced database connection
try {
    $db = new Database();
    
    $upcoming_events = $db->select("SELECT title, event_date FROM events WHERE status = 'upcoming' AND event_date >= NOW() ORDER BY event_date ASC LIMIT 3");
    
} catch (Exception $e) {
    $upcoming_events = [];
}

// Children's ministry information
$children_info = [
    'title' => 'Children\'s Ministry',
    'subtitle' => 'Nurturing Young Hearts in Christ\'s Love',
    'description' => 'Our Children\'s Ministry is dedicated to helping children grow in their faith through fun, engaging activities that teach biblical principles in ways kids can understand and enjoy.',
    'mission' => 'To create a safe, loving environment where children can discover God\'s love, build lasting friendships, and develop a strong foundation of faith that will last a lifetime.',
    'age_groups' => [
        ['name' => 'Little Lambs', 'age' => 'Ages 3-5', 'description' => 'Preschool activities with Bible stories, songs, and play'],
        ['name' => 'Young Warriors', 'age' => 'Ages 6-8', 'description' => 'Early elementary with interactive Bible learning and games'],
        ['name' => 'Faith Explorers', 'age' => 'Ages 9-11', 'description' => 'Upper elementary with deeper Bible study and service projects'],
        ['name' => 'Youth Leaders', 'age' => 'Ages 12-15', 'description' => 'Middle school with leadership training and mentorship']
    ],
    'activities' => [
        ['name' => 'Sunday School', 'time' => '9:00 AM', 'description' => 'Age-appropriate Bible lessons and activities'],
        ['name' => 'Children\'s Church', 'time' => '10:30 AM', 'description' => 'Kid-friendly worship service during main service'],
        ['name' => 'Bible Clubs', 'time' => 'Wednesday 6:00 PM', 'description' => 'Fun Bible study with games and snacks'],
        ['name' => 'Vacation Bible School', 'time' => 'Summer', 'description' => 'Week-long adventure with Bible learning and fun'],
        ['name' => 'Family Events', 'time' => 'Monthly', 'description' => 'Activities for the whole family to enjoy together']
    ]
];

// Children images from assets folder
$children_images = [
    ['file' => 'assets/children-celebrating-Z18oVWUU.jpeg', 'title' => 'Children Celebrating', 'description' => 'Kids celebrating during Sunday school with joy and excitement'],
    ['file' => 'assets/children-eating-withpastor-Bagnofdx.jpeg', 'title' => 'Children with Pastor', 'description' => 'Children enjoying fellowship and sharing with our pastor'],
    ['file' => 'assets/children-food-20X1VRUx.jpeg', 'title' => 'Children\'s Meal Time', 'description' => 'Kids sharing meals and building friendships'],
    ['file' => 'assets/children-with-books-Cc2LmxDu.jpeg', 'title' => 'Children Learning', 'description' => 'Children engaged in Bible study and learning activities'],
    ['file' => 'assets/a-kid-showing-how-kindness-isgood-BBxs16el.jpeg', 'title' => 'Kindness in Action', 'description' => 'Children demonstrating God\'s love through acts of kindness'],
    ['file' => 'assets/support-children-now-Dqa2JhXn.jpeg', 'title' => 'Support Children', 'description' => 'Join us in supporting children\'s ministry and programs']
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Children's Ministry - Salem Dominion Ministries</title>
    <meta name="description" content="Discover our vibrant Children's Ministry at Salem Dominion Ministries - nurturing young hearts in Christ's love through fun, faith-building activities.">
    <meta name="keywords" content="children ministry, kids church, Sunday school, Bible study, Salem Dominion">
    <meta name="author" content="Salem Dominion Ministries">
    
    <!-- Open Graph Meta Tags -->
    <meta property="og:title" content="Children's Ministry - Salem Dominion Ministries">
    <meta property="og:description" content="Nurturing young hearts in Christ's love through fun, engaging activities.">
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?php echo (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>">
    <meta property="og:image" content="<?php echo (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST']; ?>/assets/children-celebrating-Z18oVWUU.jpeg">
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
                <h1 data-aos="fade-up"><i class="fas fa-child"></i> <?php echo htmlspecialchars($children_info['title']); ?></h1>
                <p class="lead" data-aos="fade-up" data-aos-delay="200"><?php echo htmlspecialchars($children_info['subtitle']); ?></p>
                <div class="action-buttons" data-aos="fade-up" data-aos-delay="400">
                    <a href="#activities" class="btn btn-primary">
                        <i class="fas fa-calendar"></i> View Activities
                    </a>
                    <a href="#gallery" class="btn btn-success">
                        <i class="fas fa-images"></i> See Photos
                    </a>
                    <a href="register.php" class="btn btn-purple">
                        <i class="fas fa-user-plus"></i> Register Child
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section class="py-5">
        <div class="container">
            <div class="section-title" data-aos="fade-up">
                <h2>About Our Ministry</h2>
                <p>Learn more about our mission and vision</p>
            </div>
            
            <div class="row">
                <div class="col-lg-6" data-aos="fade-up">
                    <div class="ministry-card">
                        <div class="ministry-icon">
                            <i class="fas fa-heart"></i>
                        </div>
                        <h4>Our Mission</h4>
                        <p><?php echo htmlspecialchars($children_info['mission']); ?></p>
                    </div>
                </div>
                <div class="col-lg-6" data-aos="fade-up" data-aos-delay="100">
                    <div class="ministry-card">
                        <div class="ministry-icon">
                            <i class="fas fa-star"></i>
                        </div>
                        <h4>Our Vision</h4>
                        <p><?php echo htmlspecialchars($children_info['description']); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Age Groups Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="section-title" data-aos="fade-up">
                <h2>Age Groups</h2>
                <p>Programs designed for every age and stage</p>
            </div>
            
            <div class="row">
                <?php foreach ($children_info['age_groups'] as $index => $group): ?>
                    <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="<?php echo $index * 100; ?>">
                        <div class="ministry-card">
                            <div class="ministry-icon">
                                <i class="fas fa-users"></i>
                            </div>
                            <h4><?php echo htmlspecialchars($group['name']); ?></h4>
                            <p class="small text-muted"><?php echo htmlspecialchars($group['age']); ?></p>
                            <p><?php echo htmlspecialchars($group['description']); ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Activities Section -->
    <section id="activities" class="py-5">
        <div class="container">
            <div class="section-title" data-aos="fade-up">
                <h2>Our Activities</h2>
                <p>Fun, faith-building programs for children</p>
            </div>
            
            <div class="row">
                <?php foreach ($children_info['activities'] as $index => $activity): ?>
                    <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="<?php echo $index * 100; ?>">
                        <div class="event-card">
                            <div class="event-date">
                                <i class="fas fa-clock"></i>
                                <div>
                                    <div class="day"><?php echo htmlspecialchars($activity['time']); ?></div>
                                </div>
                            </div>
                            <div class="event-content">
                                <h4><?php echo htmlspecialchars($activity['name']); ?></h4>
                                <p><?php echo htmlspecialchars($activity['description']); ?></p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Gallery Section -->
    <section id="gallery" class="py-5 bg-light">
        <div class="container">
            <div class="section-title" data-aos="fade-up">
                <h2>Children's Gallery</h2>
                <p>See our children in action!</p>
            </div>
            
            <div class="gallery-grid">
                <?php foreach ($children_images as $index => $image): ?>
                    <div class="gallery-item" data-aos="fade-up" data-aos-delay="<?php echo $index * 100; ?>">
                        <img src="<?php echo htmlspecialchars($image['file']); ?>" alt="<?php echo htmlspecialchars($image['title']); ?>">
                        <div class="gallery-overlay">
                            <h4><?php echo htmlspecialchars($image['title']); ?></h4>
                            <p><?php echo htmlspecialchars($image['description']); ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Upcoming Events Section -->
    <section class="py-5">
        <div class="container">
            <div class="section-title" data-aos="fade-up">
                <h2>Upcoming Events</h2>
                <p>Don't miss these special activities</p>
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
                                    <div class="day">15</div>
                                    <div class="month">DEC</div>
                                </div>
                            </div>
                            <div class="event-content">
                                <h4>Christmas Party</h4>
                                <p class="small text-muted">Saturday, December 15, 2024 - 2:00 PM</p>
                                <p>Join us for our annual Christmas celebration!</p>
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
                <h2>Get Your Child Involved!</h2>
                <p>Join our Children's Ministry and watch your child grow in faith and friendship</p>
                <div class="action-buttons">
                    <a href="register.php" class="btn btn-primary btn-lg">
                        <i class="fas fa-user-plus"></i> Register Now
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
