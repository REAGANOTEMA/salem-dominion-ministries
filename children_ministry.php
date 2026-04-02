<?php
// Include session helper and start session safely
require_once 'session_helper.php';
secure_session_start();
require_once 'db.php';

// Get children ministry events
$children_events = $db->query("SELECT * FROM events WHERE category = 'children' AND event_date >= CURDATE() ORDER BY event_date LIMIT 5");

// Get children ministry news
$children_news = $db->query("SELECT * FROM news WHERE category = 'children' ORDER BY created_at DESC LIMIT 3");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Children Ministry - Salem Dominion Ministries</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="assets/css/heavenly_design.css" rel="stylesheet">
    <style>
        .children-hero {
            background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url('public/images/children-hero.jpg');
            background-size: cover;
            background-position: center;
            color: white;
            padding: 100px 0;
        }
        .navbar-brand {
            font-weight: bold;
            font-size: 1.5rem;
        }
        .children-card {
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            border: none;
            border-radius: 15px;
            transition: transform 0.3s ease;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
        }
        .children-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(255, 215, 0, 0.2);
        }
        .age-group-card {
            background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%);
            color: white;
            border-radius: 10px;
        }
        .activity-card {
            background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
            border-radius: 10px;
        }
        .fun-element {
            position: absolute;
            font-size: 3rem;
            opacity: 0.1;
            z-index: 0;
        }
        .fun-element:nth-child(1) { top: 10%; left: 10%; color: #ff6b6b; }
        .fun-element:nth-child(2) { top: 20%; right: 15%; color: #4ecdc4; }
        .fun-element:nth-child(3) { bottom: 15%; left: 20%; color: #ffd93d; }
        .fun-element:nth-child(4) { bottom: 10%; right: 10%; color: #a8e6cf; }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <i class="fas fa-church"></i> Salem Dominion Ministries
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="about.php">About</a></li>
                    <li class="nav-item"><a class="nav-link" href="ministries.php">Ministries</a></li>
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
    <section class="children-hero">
        <div class="container text-center position-relative">
            <i class="fas fa-child fun-element"></i>
            <i class="fas fa-heart fun-element"></i>
            <i class="fas fa-star fun-element"></i>
            <i class="fas fa-smile fun-element"></i>
            <h1 class="display-4 fw-bold mb-4">Children Ministry</h1>
            <p class="lead mb-4">"Let the little children come to me, and do not hinder them, for the kingdom of heaven belongs to such as these." - Matthew 19:14</p>
        </div>
    </section>

    <!-- Ministry Overview -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto text-center">
                    <h2 class="mb-4">Welcome to Our Children Ministry</h2>
                    <p class="lead mb-4">
                        At Salem Dominion Ministries, we believe that children are the future of our church and community.
                        Our Children Ministry is dedicated to nurturing young hearts with God's love, teaching biblical
                        truths in fun and engaging ways, and helping children grow in their relationship with Jesus Christ.
                    </p>
                </div>
            </div>

            <!-- Age Groups -->
            <div class="row g-4 mb-5">
                <div class="col-md-6 col-lg-3">
                    <div class="card age-group-card text-center h-100">
                        <div class="card-body d-flex flex-column">
                            <i class="fas fa-baby fa-3x mb-3"></i>
                            <h5 class="card-title">Nursery</h5>
                            <p class="card-text">Ages 0-2</p>
                            <p class="card-text flex-grow-1">Caring environment for our youngest members with loving supervision and age-appropriate activities.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="card age-group-card text-center h-100">
                        <div class="card-body d-flex flex-column">
                            <i class="fas fa-child fa-3x mb-3"></i>
                            <h5 class="card-title">Toddlers</h5>
                            <p class="card-text">Ages 3-5</p>
                            <p class="card-text flex-grow-1">Fun learning experiences, songs, stories, and crafts that introduce children to God's love.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="card age-group-card text-center h-100">
                        <div class="card-body d-flex flex-column">
                            <i class="fas fa-school fa-3x mb-3"></i>
                            <h5 class="card-title">Elementary</h5>
                            <p class="card-text">Ages 6-11</p>
                            <p class="card-text flex-grow-1">Biblical teaching, memory verses, worship, and activities that help children grow spiritually.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="card age-group-card text-center h-100">
                        <div class="card-body d-flex flex-column">
                            <i class="fas fa-users fa-3x mb-3"></i>
                            <h5 class="card-title">Pre-Teens</h5>
                            <p class="card-text">Ages 12-14</p>
                            <p class="card-text flex-grow-1">Preparing for youth ministry with deeper biblical study, leadership development, and service opportunities.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Activities & Programs -->
    <section class="py-5">
        <div class="container">
            <h2 class="text-center mb-5">Our Programs & Activities</h2>
            <div class="row g-4">
                <div class="col-lg-4">
                    <div class="card activity-card h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-bible fa-3x text-primary mb-3"></i>
                            <h5 class="card-title">Sunday School</h5>
                            <p class="card-text">Weekly Bible lessons, stories, and activities designed specifically for each age group during our Sunday morning service.</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card activity-card h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-music fa-3x text-success mb-3"></i>
                            <h5 class="card-title">Children's Choir</h5>
                            <p class="card-text">Learning worship songs, hymns, and developing musical talents while praising God together as a group.</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card activity-card h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-hands-helping fa-3x text-warning mb-3"></i>
                            <h5 class="card-title">Community Service</h5>
                            <p class="card-text">Teaching children the importance of serving others through food drives, visiting nursing homes, and helping those in need.</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card activity-card h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-praying-hands fa-3x text-info mb-3"></i>
                            <h5 class="card-title">Prayer Time</h5>
                            <p class="card-text">Dedicated time for children to learn about prayer, share prayer requests, and experience the power of talking to God.</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card activity-card h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-palette fa-3x text-danger mb-3"></i>
                            <h5 class="card-title">Crafts & Arts</h5>
                            <p class="card-text">Creative activities that reinforce Bible lessons and help children express their faith through art and crafts.</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card activity-card h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-running fa-3x text-secondary mb-3"></i>
                            <h5 class="card-title">Games & Sports</h5>
                            <p class="card-text">Physical activities and games that promote teamwork, healthy living, and having fun while learning about God.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Children's Gallery -->
    <section class="py-5">
        <div class="container">
            <h2 class="text-center mb-5">Our Children in Action</h2>
            <div class="row g-4">
                <!-- Children's Images -->
                <div class="col-lg-4 col-md-6">
                    <div class="children-card">
                        <div class="children-image-container">
                            <img src="public/images/children1.jpg" alt="Children Worship" class="children-image">
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">Children's Worship</h5>
                            <p class="card-text">Our children praising God with joyful hearts and beautiful voices.</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="children-card">
                        <div class="children-image-container">
                            <img src="public/images/children2.jpg" alt="Bible Study" class="children-image">
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">Bible Learning</h5>
                            <p class="card-text">Children learning God's Word through engaging stories and activities.</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="children-card">
                        <div class="children-image-container">
                            <img src="public/images/children3.jpg" alt="Arts and Crafts" class="children-image">
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">Creative Arts</h5>
                            <p class="card-text">Expressing faith through creative crafts and artistic activities.</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="children-card">
                        <div class="children-image-container">
                            <img src="public/images/children4.jpg" alt="Outdoor Activities" class="children-image">
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">Fun Activities</h5>
                            <p class="card-text">Outdoor games and activities that teach teamwork and friendship.</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="children-card">
                        <div class="children-image-container">
                            <img src="public/images/children5.jpg" alt="Prayer Time" class="children-image">
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">Prayer Time</h5>
                            <p class="card-text">Children learning to pray and talk to God in their own special way.</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="children-card">
                        <div class="children-image-container">
                            <img src="public/images/children6.jpg" alt="Community Service" class="children-image">
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">Service Projects</h5>
                            <p class="card-text">Children serving others and learning the joy of helping those in need.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Upcoming Events & News -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row">
                <!-- Upcoming Events -->
                <div class="col-lg-6 mb-4">
                    <div class="card children-card">
                        <div class="card-body">
                            <h4 class="card-title mb-4"><i class="fas fa-calendar-alt text-primary"></i> Upcoming Children's Events</h4>
                            <?php if ($children_events->num_rows > 0): ?>
                                <div class="list-group list-group-flush">
                                    <?php while ($event = $children_events->fetch_assoc()): ?>
                                        <a href="events.php" class="list-group-item list-group-item-action">
                                            <div class="d-flex w-100 justify-content-between">
                                                <h6 class="mb-1"><?php echo htmlspecialchars($event['title']); ?></h6>
                                                <small><?php echo date('M j, Y', strtotime($event['event_date'])); ?></small>
                                            </div>
                                            <p class="mb-1"><?php echo htmlspecialchars(substr($event['description'], 0, 100)); ?>...</p>
                                        </a>
                                    <?php endwhile; ?>
                                </div>
                            <?php else: ?>
                                <p class="text-muted">No upcoming children's events scheduled.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Latest News -->
                <div class="col-lg-6 mb-4">
                    <div class="card children-card">
                        <div class="card-body">
                            <h4 class="card-title mb-4"><i class="fas fa-newspaper text-success"></i> Children's Ministry News</h4>
                            <?php if ($children_news->num_rows > 0): ?>
                                <div class="row g-3">
                                    <?php while ($news = $children_news->fetch_assoc()): ?>
                                        <div class="col-12">
                                            <div class="card border-0 bg-white">
                                                <div class="card-body p-0">
                                                    <h6><a href="news.php" class="text-decoration-none"><?php echo htmlspecialchars($news['title']); ?></a></h6>
                                                    <p class="text-muted small mb-1"><?php echo date('M j, Y', strtotime($news['created_at'])); ?></p>
                                                    <p class="mb-0"><?php echo htmlspecialchars(substr($news['content'], 0, 120)); ?>...</p>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endwhile; ?>
                                </div>
                            <?php else: ?>
                                <p class="text-muted">No recent news from the children's ministry.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action -->
    <section class="py-5 bg-primary text-white">
        <div class="container text-center">
            <h2 class="mb-4">Get Your Children Involved!</h2>
            <p class="lead mb-4">We'd love to welcome your children to our ministry. Contact us to learn more about how they can join in the fun and grow in their faith.</p>
            <div class="row justify-content-center">
                <div class="col-md-4 mb-3">
                    <a href="contact.php" class="btn btn-light btn-lg w-100">
                        <i class="fas fa-envelope"></i> Contact Us
                    </a>
                </div>
                <div class="col-md-4 mb-3">
                    <a href="register.php" class="btn btn-outline-light btn-lg w-100">
                        <i class="fas fa-user-plus"></i> Register Family
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <?php require_once 'footer_enhanced.php'; ?>
    
    <!-- Navigation Arrows -->
    <?php require_once 'components/navigation_arrows.php'; ?>
    
    <!-- Heavenly Scripts -->
    <script src="assets/js/heavenly_functionality.js"></script>
    <script src="assets/js/spiritual_enhancement.js"></script>
</body>
</html>