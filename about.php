<?php
session_start();
require_once 'db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - Salem Dominion Ministries</title>
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
                    <li class="nav-item"><a class="nav-link active" href="about.php">About</a></li>
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
            <h1 class="display-4 fw-bold mb-4">About Salem Dominion Ministries</h1>
            <p class="lead mb-4">Discover our mission, vision, and the heart behind our ministry.</p>
        </div>
    </section>

    <!-- About Content -->
    <section class="py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <h2 class="text-center mb-4">Our Story</h2>
                    <p class="lead text-center mb-5">
                        Salem Dominion Ministries is a vibrant Christian community dedicated to spreading God's love,
                        fostering spiritual growth, and serving our local community with compassion and excellence.
                    </p>

                    <div class="row mb-5">
                        <div class="col-md-6">
                            <h3><i class="fas fa-bullseye text-primary"></i> Our Mission</h3>
                            <p>
                                To create a welcoming environment where people can encounter God's love, grow in their faith,
                                and discover their purpose in serving others. We are committed to building a community that
                                reflects Christ's love and teachings.
                            </p>
                        </div>
                        <div class="col-md-6">
                            <h3><i class="fas fa-eye text-success"></i> Our Vision</h3>
                            <p>
                                To be a beacon of hope and faith in our community, reaching out to all people with the
                                message of salvation and demonstrating God's love through practical service and spiritual
                                guidance.
                            </p>
                        </div>
                    </div>

                    <h3 class="mb-4"><i class="fas fa-heart text-danger"></i> What We Believe</h3>
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h5 class="card-title">The Bible</h5>
                                    <p class="card-text">We believe the Bible is God's inspired Word, providing guidance for faith and life.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-4">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h5 class="card-title">Jesus Christ</h5>
                                    <p class="card-text">We believe in Jesus Christ as the Son of God and our Savior.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-4">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h5 class="card-title">The Holy Spirit</h5>
                                    <p class="card-text">We believe in the Holy Spirit's power to transform lives.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-4">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h5 class="card-title">Community</h5>
                                    <p class="card-text">We believe in the importance of Christian fellowship and service.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <h3 class="mb-4"><i class="fas fa-users text-info"></i> Our Leadership</h3>
                    <?php
                    $pastor = $db->query("SELECT * FROM users WHERE role = 'pastor' LIMIT 1")->fetch_assoc();
                    if ($pastor):
                    ?>
                    <div class="card mb-4">
                        <div class="card-body text-center">
                            <?php if ($pastor['avatar_url']): ?>
                            <img src="<?php echo htmlspecialchars($pastor['avatar_url']); ?>" alt="Pastor" class="rounded-circle mb-3" style="width: 120px; height: 120px; object-fit: cover;">
                            <?php endif; ?>
                            <h5><?php echo htmlspecialchars($pastor['first_name'] . ' ' . $pastor['last_name']); ?></h5>
                            <p class="text-muted">Senior Pastor</p>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
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
                        <li><a href="index.php" class="text-light">Home</a></li>
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