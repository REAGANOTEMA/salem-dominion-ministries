<?php
// Complete error suppression to prevent unwanted output
error_reporting(0);
ini_set('display_errors', 0);
ini_set('log_errors', 0);

// Buffer output to catch any accidental output
ob_start();

// Include session helper and start session safely
require_once 'session_helper.php';
secure_session_start();
require_once 'db.php';

// Get all ministries
$ministries = $db->query("SELECT * FROM ministries ORDER BY name");

// Clean any buffered output
ob_end_clean();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ministries - Salem Dominion Ministries</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="assets/css/heavenly_design.css" rel="stylesheet">
    <link href="assets/css/perfect_responsive.css" rel="stylesheet">
    <style>
        .ministries-hero {
            background: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)), url('https://images.unsplash.com/photo-1520637836862-4d197d17c23a?ixlib=rb-4.0.3');
            background-size: cover;
            background-position: center;
            color: white;
            padding: 80px 0;
        }
        .navbar-brand {
            font-weight: bold;
            font-size: 1.5rem;
        }
        .ministry-card {
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            border: none;
            border-radius: 15px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            height: 100%;
        }
        .ministry-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.2);
        }
        .ministry-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
        }
        .featured-ministry {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand heavenly-logo" href="index.php">
                <img src="public/images/logo.png" alt="Salem Dominion Ministries" class="logo-img">
                <span class="brand-text">Salem Dominion Ministries</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="about.php">About</a></li>
                    <li class="nav-item"><a class="nav-link active" href="ministries.php">Ministries</a></li>
                    <li class="nav-item"><a class="nav-link" href="events.php">Events</a></li>
                    <li class="nav-item"><a class="nav-link" href="sermons.php">Sermons</a></li>
                    <li class="nav-item"><a class="nav-link" href="news.php">News</a></li>
                    <li class="nav-item"><a class="nav-link" href="gallery.php">Gallery</a></li>
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
    <section class="ministries-hero">
        <div class="container text-center">
            <h1 class="display-4 fw-bold mb-4">Our Ministries</h1>
            <p class="lead mb-4">"Each of you should use whatever gift you have received to serve others, as faithful stewards of God's grace in its various forms." - 1 Peter 4:10</p>
        </div>
    </section>

    <!-- Ministries Overview -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto text-center">
                    <h2 class="mb-4">Serving God Through Service</h2>
                    <p class="lead mb-4">
                        At Salem Dominion Ministries, we believe that every member has unique gifts and talents that can be used
                        to serve God and build up His kingdom. Our various ministries provide opportunities for everyone to get
                        involved and make a difference in our church and community.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Ministries Grid -->
    <section class="py-5">
        <div class="container">
            <div class="row g-4">
                <!-- Featured Ministry: Children -->
                <div class="col-lg-6 mb-4">
                    <div class="card ministry-card featured-ministry">
                        <div class="card-body text-center p-5">
                            <i class="fas fa-child ministry-icon"></i>
                            <h3 class="card-title mb-3">Children Ministry</h3>
                            <p class="card-text mb-4">
                                Nurturing young hearts with God's love through age-appropriate Bible teaching, fun activities,
                                songs, crafts, and community service projects.
                            </p>
                            <a href="children_ministry.php" class="btn btn-light btn-lg">
                                <i class="fas fa-arrow-right"></i> Learn More
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Featured Ministry: Worship -->
                <div class="col-lg-6 mb-4">
                    <div class="card ministry-card featured-ministry">
                        <div class="card-body text-center p-5">
                            <i class="fas fa-music ministry-icon"></i>
                            <h3 class="card-title mb-3">Worship Ministry</h3>
                            <p class="card-text mb-4">
                                Leading our congregation in worship through music, song, and praise. Join our choir, worship team,
                                or instrumental groups to glorify God together.
                            </p>
                            <a href="contact.php" class="btn btn-light btn-lg">
                                <i class="fas fa-envelope"></i> Get Involved
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Other Ministries from Database -->
                <?php
                $ministry_icons = [
                    'youth' => 'fas fa-users',
                    'women' => 'fas fa-female',
                    'men' => 'fas fa-male',
                    'senior' => 'fas fa-user-friends',
                    'outreach' => 'fas fa-hand-holding-heart',
                    'missions' => 'fas fa-globe',
                    'prayer' => 'fas fa-praying-hands',
                    'hospitality' => 'fas fa-utensils',
                    'media' => 'fas fa-video',
                    'default' => 'fas fa-church'
                ];

                $ministry_colors = [
                    'youth' => 'primary',
                    'women' => 'danger',
                    'men' => 'info',
                    'senior' => 'warning',
                    'outreach' => 'success',
                    'missions' => 'secondary',
                    'prayer' => 'dark',
                    'hospitality' => 'primary',
                    'media' => 'info',
                    'default' => 'primary'
                ];

                while ($ministry = $ministries->fetch_assoc()):
                    $icon = $ministry_icons[$ministry['category']] ?? $ministry_icons['default'];
                    $color = $ministry_colors[$ministry['category']] ?? $ministry_colors['default'];
                ?>
                <div class="col-lg-4 col-md-6">
                    <div class="card ministry-card h-100">
                        <div class="card-body text-center p-4">
                            <i class="<?php echo $icon; ?> ministry-icon text-<?php echo $color; ?>"></i>
                            <h5 class="card-title mb-3"><?php echo htmlspecialchars($ministry['name']); ?></h5>
                            <p class="card-text"><?php echo htmlspecialchars(substr($ministry['description'], 0, 120)); ?>...</p>
                            <div class="mt-auto">
                                <small class="text-muted d-block mb-2">
                                    <i class="fas fa-user"></i> <?php echo htmlspecialchars($ministry['leader_name']); ?>
                                </small>
                                <?php if ($ministry['meeting_time']): ?>
                                    <small class="text-muted d-block mb-3">
                                        <i class="fas fa-clock"></i> <?php echo htmlspecialchars($ministry['meeting_time']); ?>
                                    </small>
                                <?php endif; ?>
                                <a href="contact.php" class="btn btn-outline-<?php echo $color; ?> btn-sm">
                                    <i class="fas fa-envelope"></i> Contact
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endwhile; ?>

                <!-- Additional Ministries (if not in database) -->
                <div class="col-lg-4 col-md-6">
                    <div class="card ministry-card h-100">
                        <div class="card-body text-center p-4">
                            <i class="fas fa-users ministry-icon text-success"></i>
                            <h5 class="card-title mb-3">Youth Ministry</h5>
                            <p class="card-text">Empowering teenagers to grow in faith, develop leadership skills, and serve their community through various activities and outreach programs.</p>
                            <a href="contact.php" class="btn btn-outline-success btn-sm">
                                <i class="fas fa-envelope"></i> Contact
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6">
                    <div class="card ministry-card h-100">
                        <div class="card-body text-center p-4">
                            <i class="fas fa-female ministry-icon text-danger"></i>
                            <h5 class="card-title mb-3">Women's Ministry</h5>
                            <p class="card-text">Supporting women in their spiritual journey through Bible study, fellowship, prayer groups, and community service opportunities.</p>
                            <a href="contact.php" class="btn btn-outline-danger btn-sm">
                                <i class="fas fa-envelope"></i> Contact
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6">
                    <div class="card ministry-card h-100">
                        <div class="card-body text-center p-4">
                            <i class="fas fa-male ministry-icon text-info"></i>
                            <h5 class="card-title mb-3">Men's Ministry</h5>
                            <p class="card-text">Building godly men through brotherhood, accountability, Bible study, and service projects that strengthen families and community.</p>
                            <a href="contact.php" class="btn btn-outline-info btn-sm">
                                <i class="fas fa-envelope"></i> Contact
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6">
                    <div class="card ministry-card h-100">
                        <div class="card-body text-center p-4">
                            <i class="fas fa-hand-holding-heart ministry-icon text-warning"></i>
                            <h5 class="card-title mb-3">Outreach Ministry</h5>
                            <p class="card-text">Reaching out to our community through food drives, homeless shelters, prison ministry, and other compassionate service programs.</p>
                            <a href="contact.php" class="btn btn-outline-warning btn-sm">
                                <i class="fas fa-envelope"></i> Contact
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6">
                    <div class="card ministry-card h-100">
                        <div class="card-body text-center p-4">
                            <i class="fas fa-praying-hands ministry-icon text-dark"></i>
                            <h5 class="card-title mb-3">Prayer Ministry</h5>
                            <p class="card-text">Dedicated to intercessory prayer, maintaining a prayer chain, and providing spiritual support for those in need of prayer.</p>
                            <a href="prayer_requests.php" class="btn btn-outline-dark btn-sm">
                                <i class="fas fa-praying-hands"></i> Submit Request
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6">
                    <div class="card ministry-card h-100">
                        <div class="card-body text-center p-4">
                            <i class="fas fa-utensils ministry-icon text-primary"></i>
                            <h5 class="card-title mb-3">Hospitality Ministry</h5>
                            <p class="card-text">Creating welcoming environments through greeting visitors, coordinating fellowship meals, and ensuring everyone feels at home.</p>
                            <a href="contact.php" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-envelope"></i> Contact
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action -->
    <section class="py-5 bg-primary text-white">
        <div class="container text-center">
            <h2 class="mb-4">Find Your Place to Serve</h2>
            <p class="lead mb-4">God has given each of us unique gifts and talents. Discover how you can use yours to serve Him and bless others.</p>
            <div class="row justify-content-center">
                <div class="col-md-4 mb-3">
                    <a href="contact.php" class="btn btn-light btn-lg w-100">
                        <i class="fas fa-envelope"></i> Get Involved
                    </a>
                </div>
                <div class="col-md-4 mb-3">
                    <a href="donate.php" class="btn btn-outline-light btn-lg w-100">
                        <i class="fas fa-hand-holding-heart"></i> Support Ministries
                    </a>
    <!-- Footer -->
    <?php require_once 'footer_enhanced.php'; ?>
    
    <!-- Navigation Arrows -->
    <?php require_once 'components/navigation_arrows.php'; ?>
    
    <!-- Heavenly Scripts -->
    <script src="assets/js/heavenly_functionality.js"></script>
    <script src="assets/js/spiritual_enhancement.js"></script>
</body>
</html>