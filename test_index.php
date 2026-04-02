<?php
session_start();
require_once 'db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Salem Dominion Ministries - Home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .hero-section {
            background: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)), url('https://images.unsplash.com/photo-1438232992991-995b7058bbb3?ixlib=rb-4.0.3');
            background-size: cover;
            background-position: center;
            color: white;
            padding: 100px 0;
        }
        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
            transition: all 0.3s ease;
        }
        .navbar-brand {
            font-weight: bold;
            font-size: 1.5rem;
        }
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
    <section class="hero-section">
        <div class="container text-center">
            <h1 class="display-4 fw-bold mb-4">Welcome to Salem Dominion Ministries</h1>
            <p class="lead mb-4">Experience God's love, grow in faith, and serve our community together.</p>
            <a href="about.php" class="btn btn-primary btn-lg me-3"><i class="fas fa-info-circle"></i> Learn More</a>
            <a href="contact.php" class="btn btn-outline-light btn-lg"><i class="fas fa-envelope"></i> Contact Us</a>
        </div>
    </section>

    <!-- Service Times -->
    <section class="py-5 bg-light">
        <div class="container">
            <h2 class="text-center mb-5">Service Times</h2>
            <div class="row">
                <?php
                $services = $db->query("SELECT * FROM service_times WHERE is_active = 1 ORDER BY FIELD(day_of_week, 'sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'), start_time");
                while ($service = $services->fetch_assoc()):
                ?>
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card card-hover h-100">
                        <div class="card-body text-center">
                            <h5 class="card-title"><i class="fas fa-clock text-primary"></i> <?php echo ucfirst($service['day_of_week']); ?></h5>
                            <h6 class="card-subtitle mb-2 text-muted"><?php echo htmlspecialchars($service['service_name']); ?></h6>
                            <p class="card-text"><?php echo date('g:i A', strtotime($service['start_time'])); ?> - <?php echo date('g:i A', strtotime($service['end_time'])); ?></p>
                            <p class="card-text"><small class="text-muted"><?php echo htmlspecialchars($service['location']); ?></small></p>
                        </div>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
        </div>
    </section>

    <!-- Ministries Preview -->
    <section class="py-5">
        <div class="container">
            <h2 class="text-center mb-5">Our Ministries</h2>
            <div class="row">
                <?php
                $ministries = $db->query("SELECT * FROM ministries WHERE is_active = 1 LIMIT 3");
                while ($ministry = $ministries->fetch_assoc()):
                ?>
                <div class="col-md-4 mb-4">
                    <div class="card card-hover h-100">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($ministry['name']); ?></h5>
                            <p class="card-text"><?php echo htmlspecialchars(substr($ministry['description'], 0, 100)) . '...'; ?></p>
                            <a href="ministries.php" class="btn btn-primary">Learn More</a>
                        </div>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
        </div>
    </section>

    <!-- Latest News -->
    <section class="py-5 bg-light">
        <div class="container">
            <h2 class="text-center mb-5">Latest News</h2>
            <div class="row">
                <?php
                $news = $db->query("SELECT * FROM news WHERE status = 'published' ORDER BY published_at DESC LIMIT 3");
                while ($item = $news->fetch_assoc()):
                ?>
                <div class="col-md-4 mb-4">
                    <div class="card card-hover h-100">
                        <?php if ($item['featured_image_url']): ?>
                        <img src="<?php echo htmlspecialchars($item['featured_image_url']); ?>" class="card-img-top" alt="News Image">
                        <?php endif; ?>
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($item['title']); ?></h5>
                            <p class="card-text"><?php echo htmlspecialchars(substr($item['excerpt'], 0, 100)) . '...'; ?></p>
                            <a href="news.php?id=<?php echo $item['id']; ?>" class="btn btn-primary">Read More</a>
                        </div>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-light py-4">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h5>Salem Dominion Ministries</h5>
                    <p>Serving our community with faith, hope, and love.</p>
                </div>
                <div class="col-md-4">
                    <h5>Quick Links</h5>
                    <ul class="list-unstyled">
                        <li><a href="about.php" class="text-light">About Us</a></li>
                        <li><a href="contact.php" class="text-light">Contact</a></li>
                        <li><a href="donate.php" class="text-light">Donate</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h5>Contact Info</h5>
                    <p><i class="fas fa-envelope"></i> visit@salemdominionministries.com</p>
                    <p><i class="fas fa-phone"></i> Contact us for service times</p>
                </div>
            </div>
            <hr>
            <p class="text-center mb-0">&copy; 2026 Salem Dominion Ministries. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>