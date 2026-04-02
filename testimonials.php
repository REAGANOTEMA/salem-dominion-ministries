<?php
session_start();
require_once 'db.php';

$errors = [];
$success = '';

// Handle testimonial submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $occupation = trim($_POST['occupation'] ?? '');
    $testimonial = trim($_POST['testimonial'] ?? '');
    $rating = intval($_POST['rating'] ?? 5);

    if (empty($name) || empty($testimonial)) {
        $errors[] = 'Please fill in all required fields.';
    }

    if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Please enter a valid email address.';
    }

    if (empty($errors)) {
        $stmt = $db->prepare("INSERT INTO testimonials (name, email, occupation, testimonial, rating, is_approved) VALUES (?, ?, ?, ?, ?, FALSE)");
        $stmt->bind_param('ssssi', $name, $email, $occupation, $testimonial, $rating);
        if ($stmt->execute()) {
            $success = 'Thank you for sharing your testimony! It will be reviewed and published soon.';
        } else {
            $errors[] = 'Failed to submit testimonial. Please try again.';
        }
        $stmt->close();
    }
}

// Get approved testimonials
$testimonials = $db->query("SELECT * FROM testimonials WHERE is_approved = 1 AND is_featured = 1 ORDER BY rating DESC, created_at DESC LIMIT 6");
$all_testimonials = $db->query("SELECT * FROM testimonials WHERE is_approved = 1 ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Testimonials - Salem Dominion Ministries</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .testimonials-hero {
            background: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)), url('https://images.unsplash.com/photo-1551836022-d5d88e9218df?ixlib=rb-4.0.3');
            background-size: cover;
            background-position: center;
            color: white;
            padding: 80px 0;
        }
        .navbar-brand {
            font-weight: bold;
            font-size: 1.5rem;
        }
        .testimonial-card {
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            border: none;
            border-radius: 15px;
            background: white;
            transition: transform 0.3s ease;
        }
        .testimonial-card:hover {
            transform: translateY(-5px);
        }
        .testimonial-quote {
            font-size: 1.1rem;
            font-style: italic;
            color: #555;
            position: relative;
            padding-left: 30px;
        }
        .testimonial-quote::before {
            content: '\201C';
            position: absolute;
            left: 0;
            top: -10px;
            font-size: 4rem;
            color: #007bff;
            opacity: 0.3;
            font-family: Georgia, serif;
        }
        .rating-stars {
            color: #ffc107;
        }
        .avatar-placeholder {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
            font-weight: bold;
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
    <section class="testimonials-hero">
        <div class="container text-center">
            <h1 class="display-4 fw-bold mb-4">Testimonies of God's Faithfulness</h1>
            <p class="lead mb-4">"Come and hear, all you who fear God; let me tell you what he has done for me." - Psalm 66:16</p>
        </div>
    </section>

    <!-- Featured Testimonials -->
    <section class="py-5">
        <div class="container">
            <h2 class="text-center mb-5"><i class="fas fa-star text-warning"></i> Featured Testimonies</h2>
            <div class="row g-4">
                <?php if ($testimonials->num_rows > 0): ?>
                    <?php while ($t = $testimonials->fetch_assoc()): ?>
                        <div class="col-lg-4 col-md-6">
                            <div class="card testimonial-card h-100">
                                <div class="card-body p-4">
                                    <div class="d-flex align-items-center mb-3">
                                        <?php if ($t['photo_url']): ?>
                                            <img src="<?php echo htmlspecialchars($t['photo_url']); ?>" alt="<?php echo htmlspecialchars($t['name']); ?>" class="rounded-circle me-3" style="width: 60px; height: 60px; object-fit: cover;">
                                        <?php else: ?>
                                            <div class="avatar-placeholder me-3">
                                                <?php echo strtoupper(substr($t['name'], 0, 1)); ?>
                                            </div>
                                        <?php endif; ?>
                                        <div>
                                            <h6 class="mb-0"><?php echo htmlspecialchars($t['name']); ?></h6>
                                            <?php if ($t['occupation']): ?>
                                                <small class="text-muted"><?php echo htmlspecialchars($t['occupation']); ?></small>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="rating-stars mb-3">
                                        <?php for ($i = 0; $i < 5; $i++): ?>
                                            <i class="fas fa-star<?php echo $i < $t['rating'] ? '' : '-o'; ?>"></i>
                                        <?php endfor; ?>
                                    </div>
                                    <p class="testimonial-quote mb-3">
                                        <?php echo nl2br(htmlspecialchars($t['testimonial'])); ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="col-12 text-center py-5">
                        <i class="fas fa-quote-left fa-3x text-muted mb-3"></i>
                        <h4 class="text-muted">No featured testimonies yet</h4>
                        <p class="text-muted">Be the first to share your testimony!</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- All Testimonials & Submit Form -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row">
                <!-- Submit Testimonial Form -->
                <div class="col-lg-4 mb-4">
                    <div class="card testimonial-card">
                        <div class="card-body p-4">
                            <h4 class="card-title mb-4"><i class="fas fa-pen-fancy text-primary"></i> Share Your Testimony</h4>

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
                                <div class="mb-3">
                                    <label for="name" class="form-label">Your Name *</label>
                                    <input type="text" class="form-control" id="name" name="name" required
                                           value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>">
                                </div>
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" name="email"
                                           value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
                                </div>
                                <div class="mb-3">
                                    <label for="occupation" class="form-label">Occupation</label>
                                    <input type="text" class="form-control" id="occupation" name="occupation"
                                           value="<?php echo htmlspecialchars($_POST['occupation'] ?? ''); ?>">
                                </div>
                                <div class="mb-3">
                                    <label for="rating" class="form-label">Rating</label>
                                    <select class="form-control" id="rating" name="rating">
                                        <option value="5" selected>⭐⭐⭐⭐⭐ (5 Stars)</option>
                                        <option value="4">⭐⭐⭐⭐ (4 Stars)</option>
                                        <option value="3">⭐⭐⭐ (3 Stars)</option>
                                        <option value="2">⭐⭐ (2 Stars)</option>
                                        <option value="1">⭐ (1 Star)</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="testimonial" class="form-label">Your Testimony *</label>
                                    <textarea class="form-control" id="testimonial" name="testimonial" rows="5" required
                                              placeholder="Share how God has worked in your life..."><?php echo htmlspecialchars($_POST['testimonial'] ?? ''); ?></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-paper-plane"></i> Submit Testimony
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- All Testimonials List -->
                <div class="col-lg-8">
                    <h3 class="mb-4"><i class="fas fa-comments text-info"></i> All Testimonies</h3>
                    <?php if ($all_testimonials->num_rows > 0): ?>
                        <div class="row g-4">
                            <?php while ($t = $all_testimonials->fetch_assoc()): ?>
                                <div class="col-md-6">
                                    <div class="card testimonial-card">
                                        <div class="card-body p-3">
                                            <div class="d-flex align-items-center mb-2">
                                                <?php if ($t['photo_url']): ?>
                                                    <img src="<?php echo htmlspecialchars($t['photo_url']); ?>" alt="<?php echo htmlspecialchars($t['name']); ?>" class="rounded-circle me-2" style="width: 40px; height: 40px; object-fit: cover;">
                                                <?php else: ?>
                                                    <div class="avatar-placeholder me-2" style="width: 40px; height: 40px; font-size: 1rem;">
                                                        <?php echo strtoupper(substr($t['name'], 0, 1)); ?>
                                                    </div>
                                                <?php endif; ?>
                                                <div>
                                                    <h6 class="mb-0" style="font-size: 0.9rem;"><?php echo htmlspecialchars($t['name']); ?></h6>
                                                    <div class="rating-stars" style="font-size: 0.7rem;">
                                                        <?php for ($i = 0; $i < 5; $i++): ?>
                                                            <i class="fas fa-star<?php echo $i < $t['rating'] ? '' : '-o'; ?>"></i>
                                                        <?php endfor; ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <p class="testimonial-quote mb-0" style="font-size: 0.9rem;">
                                                <?php echo nl2br(htmlspecialchars(substr($t['testimonial'], 0, 200))); ?><?php echo strlen($t['testimonial']) > 200 ? '...' : ''; ?>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        </div>
                    <?php else: ?>
                        <p class="text-muted">No testimonies available yet.</p>
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
                        <li><a href="about.php" class="text-light">About Us</a></li>
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