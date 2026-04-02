<?php
session_start();
require_once 'db.php';

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $requestor_name = trim($_POST['requestor_name'] ?? '');
    $requestor_email = trim($_POST['requestor_email'] ?? '');
    $requestor_phone = trim($_POST['requestor_phone'] ?? '');
    $prayer_request = trim($_POST['prayer_request'] ?? '');
    $request_type = $_POST['request_type'] ?? 'personal';
    $is_anonymous = isset($_POST['is_anonymous']) ? 1 : 0;
    $is_public = isset($_POST['is_public']) ? 1 : 0;

    if (empty($prayer_request)) {
        $errors[] = 'Please enter your prayer request.';
    }

    if (empty($requestor_name) && !$is_anonymous) {
        $errors[] = 'Please enter your name or select anonymous request.';
    }

    if (empty($requestor_email) && !$is_anonymous) {
        $errors[] = 'Please enter your email or select anonymous request.';
    }

    if (!empty($requestor_email) && !filter_var($requestor_email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Please enter a valid email address.';
    }

    if (empty($errors)) {
        $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

        $stmt = $db->prepare("INSERT INTO prayer_requests (user_id, requestor_name, requestor_email, requestor_phone, prayer_request, request_type, is_anonymous, is_public) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param('isssssii', $user_id, $requestor_name, $requestor_email, $requestor_phone, $prayer_request, $request_type, $is_anonymous, $is_public);

        if ($stmt->execute()) {
            $success = 'Your prayer request has been submitted. We will pray for you and keep you in our thoughts.';
        } else {
            $errors[] = 'Failed to submit prayer request. Please try again.';
        }
        $stmt->close();
    }
}

// Get public prayer requests
$public_requests = $db->query("SELECT * FROM prayer_requests WHERE is_public = 1 AND is_anonymous = 0 ORDER BY created_at DESC LIMIT 10");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prayer Requests - Salem Dominion Ministries</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .prayer-hero {
            background: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)), url('https://images.unsplash.com/photo-1507692049790-de58290a4354?ixlib=rb-4.0.3');
            background-size: cover;
            background-position: center;
            color: white;
            padding: 80px 0;
        }
        .navbar-brand {
            font-weight: bold;
            font-size: 1.5rem;
        }
        .prayer-card {
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            border: none;
            border-radius: 15px;
        }
        .prayer-request-item {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 15px;
            border-left: 4px solid #007bff;
        }
        .verse-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 10px;
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
    <section class="prayer-hero">
        <div class="container text-center">
            <h1 class="display-4 fw-bold mb-4">Prayer Requests</h1>
            <p class="lead mb-4">"Therefore I tell you, whatever you ask for in prayer, believe that you have received it, and it will be yours." - Mark 11:24</p>
        </div>
    </section>

    <!-- Prayer Content -->
    <section class="py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <div class="row">
                        <!-- Prayer Request Form -->
                        <div class="col-lg-8 mb-4">
                            <div class="card prayer-card">
                                <div class="card-body p-4">
                                    <h3 class="card-title mb-4"><i class="fas fa-praying-hands text-primary"></i> Submit a Prayer Request</h3>

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
                                        <!-- Privacy Options -->
                                        <div class="mb-3">
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="checkbox" id="is_anonymous" name="is_anonymous">
                                                <label class="form-check-label" for="is_anonymous">
                                                    Submit anonymously
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="is_public" name="is_public" checked>
                                                <label class="form-check-label" for="is_public">
                                                    Allow my prayer request to be shared publicly (helps others pray)
                                                </label>
                                            </div>
                                        </div>

                                        <div id="requestor_info" class="requestor-fields">
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label for="requestor_name" class="form-label">Your Name *</label>
                                                    <input type="text" class="form-control" id="requestor_name" name="requestor_name"
                                                           value="<?php echo htmlspecialchars($_POST['requestor_name'] ?? ''); ?>">
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label for="requestor_email" class="form-label">Email *</label>
                                                    <input type="email" class="form-control" id="requestor_email" name="requestor_email"
                                                           value="<?php echo htmlspecialchars($_POST['requestor_email'] ?? ''); ?>">
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label for="requestor_phone" class="form-label">Phone (Optional)</label>
                                                <input type="tel" class="form-control" id="requestor_phone" name="requestor_phone"
                                                       value="<?php echo htmlspecialchars($_POST['requestor_phone'] ?? ''); ?>">
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="request_type" class="form-label">Type of Request</label>
                                            <select class="form-control" id="request_type" name="request_type">
                                                <option value="personal">Personal</option>
                                                <option value="family">Family</option>
                                                <option value="health">Health</option>
                                                <option value="spiritual">Spiritual</option>
                                                <option value="financial">Financial</option>
                                                <option value="relationships">Relationships</option>
                                                <option value="other">Other</option>
                                            </select>
                                        </div>

                                        <div class="mb-3">
                                            <label for="prayer_request" class="form-label">Prayer Request *</label>
                                            <textarea class="form-control" id="prayer_request" name="prayer_request" rows="5" required
                                                      placeholder="Please share your prayer request. Be as specific as you feel comfortable sharing."><?php echo htmlspecialchars($_POST['prayer_request'] ?? ''); ?></textarea>
                                        </div>

                                        <button type="submit" class="btn btn-primary btn-lg w-100">
                                            <i class="fas fa-paper-plane"></i> Submit Prayer Request
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Public Requests & Verse -->
                        <div class="col-lg-4">
                            <!-- Encouraging Verse -->
                            <div class="card verse-card mb-4">
                                <div class="card-body text-center">
                                    <i class="fas fa-bible fa-3x mb-3"></i>
                                    <h5 class="card-title">Be Encouraged</h5>
                                    <p class="card-text mb-0">
                                        "Do not be anxious about anything, but in every situation, by prayer and petition, with thanksgiving, present your requests to God."<br><br>
                                        <strong>- Philippians 4:6</strong>
                                    </p>
                                </div>
                            </div>

                            <!-- Public Prayer Requests -->
                            <div class="card prayer-card">
                                <div class="card-body">
                                    <h5 class="card-title"><i class="fas fa-users text-info"></i> Community Prayer Requests</h5>
                                    <p class="text-muted small mb-3">These requests have been shared publicly to encourage community prayer.</p>

                                    <?php if ($public_requests->num_rows > 0): ?>
                                        <div class="prayer-requests-list">
                                            <?php while ($request = $public_requests->fetch_assoc()): ?>
                                                <div class="prayer-request-item">
                                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                                        <strong><?php echo htmlspecialchars($request['requestor_name']); ?></strong>
                                                        <small class="text-muted"><?php echo date('M j, Y', strtotime($request['created_at'])); ?></small>
                                                    </div>
                                                    <p class="mb-2"><?php echo htmlspecialchars(substr($request['prayer_request'], 0, 150)); ?><?php echo strlen($request['prayer_request']) > 150 ? '...' : ''; ?></p>
                                                    <small class="text-primary"><i class="fas fa-tag"></i> <?php echo ucfirst($request['request_type']); ?></small>
                                                </div>
                                            <?php endwhile; ?>
                                        </div>
                                    <?php else: ?>
                                        <p class="text-muted mb-0">No public prayer requests at this time.</p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
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
    <script>
        // Toggle requestor info fields
        document.getElementById('is_anonymous').addEventListener('change', function() {
            const requestorFields = document.getElementById('requestor_info');
            const requiredLabels = requestorFields.querySelectorAll('label[for]');
            const inputs = requestorFields.querySelectorAll('input[type="text"], input[type="email"]');

            if (this.checked) {
                requestorFields.style.opacity = '0.5';
                inputs.forEach(input => {
                    input.required = false;
                    input.disabled = true;
                });
                requiredLabels.forEach(label => {
                    label.textContent = label.textContent.replace(' *', '');
                });
            } else {
                requestorFields.style.opacity = '1';
                inputs.forEach(input => {
                    input.required = true;
                    input.disabled = false;
                });
                requiredLabels.forEach(label => {
                    if (!label.textContent.includes(' *')) {
                        label.textContent += ' *';
                    }
                });
            }
        });
    </script>
</body>
</html>