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

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');
    $message_type = $_POST['message_type'] ?? 'general';

    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        $errors[] = 'Please fill in all required fields.';
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Please enter a valid email address.';
    }

    if (empty($errors)) {
        $stmt = $db->prepare("INSERT INTO contact_messages (name, email, phone, subject, message, message_type) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param('ssssss', $name, $email, $phone, $subject, $message, $message_type);
        if ($stmt->execute()) {
            $success = 'Thank you for your message! We will get back to you soon.';
        } else {
            $errors[] = 'Failed to send message. Please try again.';
        }
        $stmt->close();
    }
}

// Clean any buffered output
ob_end_clean();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - Salem Dominion Ministries</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .contact-hero {
            background: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)), url('https://images.unsplash.com/photo-1423666639041-f56000c27a9a?ixlib=rb-4.0.3');
            background-size: cover;
            background-position: center;
            color: white;
            padding: 80px 0;
        }
        .navbar-brand {
            font-weight: bold;
            font-size: 1.5rem;
        }
        .contact-card {
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            border: none;
            border-radius: 15px;
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
                    <li class="nav-item"><a class="nav-link" href="ministries.php">Ministries</a></li>
                    <li class="nav-item"><a class="nav-link" href="events.php">Events</a></li>
                    <li class="nav-item"><a class="nav-link" href="sermons.php">Sermons</a></li>
                    <li class="nav-item"><a class="nav-link" href="news.php">News</a></li>
                    <li class="nav-item"><a class="nav-link" href="gallery.php">Gallery</a></li>
                    <li class="nav-item"><a class="nav-link active" href="contact.php">Contact</a></li>
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
    <section class="contact-hero">
        <div class="container text-center">
            <h1 class="display-4 fw-bold mb-4">Contact Us</h1>
            <p class="lead mb-4">We'd love to hear from you. Reach out with questions, prayer requests, or just to say hello.</p>
        </div>
    </section>

    <!-- Contact Content -->
    <section class="py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <div class="row">
                        <!-- Contact Form -->
                        <div class="col-lg-8 mb-4">
                            <div class="card contact-card">
                                <div class="card-body p-4">
                                    <h3 class="card-title mb-4"><i class="fas fa-envelope text-primary"></i> Send us a Message</h3>

                                    <?php if (!empty($errors)): ?>
                                        <div class="alert alert-danger">
                                            <ul class="mb-0">
                                                <?php foreach ($errors as $error): ?>
                                                    <li><?php echo htmlspecialchars($error); ?></li>
                                                <?php endforeach; ?>
                                            </ul>
                                        </div>
                                    <?php endif; ?>

                                    <?php if ($success): ?>
                                        <div class="alert alert-success">
                                            <?php echo htmlspecialchars($success); ?>
                                        </div>
                                    <?php endif; ?>

                                    <form method="POST" action="">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="name" class="form-label">Name *</label>
                                                <input type="text" class="form-control" id="name" name="name" required
                                                       value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="email" class="form-label">Email *</label>
                                                <input type="email" class="form-control" id="email" name="email" required
                                                       value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="phone" class="form-label">Phone</label>
                                                <input type="tel" class="form-control" id="phone" name="phone"
                                                       value="<?php echo htmlspecialchars($_POST['phone'] ?? ''); ?>">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="message_type" class="form-label">Message Type</label>
                                                <select class="form-control" id="message_type" name="message_type">
                                                    <option value="general">General Inquiry</option>
                                                    <option value="prayer">Prayer Request</option>
                                                    <option value="testimony">Testimony</option>
                                                    <option value="feedback">Feedback</option>
                                                    <option value="children_ministry">Children Ministry</option>
                                                    <option value="booking_inquiry">Booking Inquiry</option>
                                                    <option value="other">Other</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label for="subject" class="form-label">Subject *</label>
                                            <input type="text" class="form-control" id="subject" name="subject" required
                                                   value="<?php echo htmlspecialchars($_POST['subject'] ?? ''); ?>">
                                        </div>
                                        <div class="mb-3">
                                            <label for="message" class="form-label">Message *</label>
                                            <textarea class="form-control" id="message" name="message" rows="5" required><?php echo htmlspecialchars($_POST['message'] ?? ''); ?></textarea>
                                        </div>
                                        <button type="submit" class="btn btn-primary btn-lg">
                                            <i class="fas fa-paper-plane"></i> Send Message
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Contact Info -->
                        <div class="col-lg-4">
                            <div class="card contact-card mb-4">
                                <div class="card-body">
                                    <h5 class="card-title"><i class="fas fa-map-marker-alt text-primary"></i> Visit Us</h5>
                                    <p class="card-text">
                                        Salem Dominion Ministries<br>
                                        Main Street, Iganga Town, Uganda<br>
                                        Near Iganga Market
                                    </p>
                                    <div class="mt-3">
                                        <a href="map.php" class="btn btn-primary btn-sm">
                                            <i class="fas fa-map me-2"></i>View Map & Directions
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <div class="card contact-card mb-4">
                                <div class="card-body">
                                    <h5 class="card-title"><i class="fas fa-clock text-success"></i> Service Times</h5>
                                    <ul class="list-unstyled">
                                        <?php
                                        $services = $db->query("SELECT * FROM service_times WHERE is_active = 1 ORDER BY start_time");
                                        while ($service = $services->fetch_assoc()):
                                        ?>
                                        <li><strong><?php echo ucfirst($service['day_of_week']); ?>:</strong> <?php echo date('g:i A', strtotime($service['start_time'])); ?> - <?php echo date('g:i A', strtotime($service['end_time'])); ?></li>
                                        <?php endwhile; ?>
                                    </ul>
                                </div>
                            </div>

                            <div class="card contact-card">
                                <div class="card-body">
                                    <h5 class="card-title"><i class="fas fa-phone text-info"></i> Get in Touch</h5>
                                    <p class="card-text">
                                        <strong>Email:</strong> visit@salemdominionministries.com<br>
                                        <strong>Phone:</strong> [Church Phone Number]
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
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