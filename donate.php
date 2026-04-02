<?php
session_start();
require_once 'db.php';

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $amount = floatval($_POST['amount'] ?? 0);
    $donor_name = trim($_POST['donor_name'] ?? '');
    $donor_email = trim($_POST['donor_email'] ?? '');
    $donor_phone = trim($_POST['donor_phone'] ?? '');
    $payment_method = $_POST['payment_method'] ?? 'cash';
    $purpose = trim($_POST['purpose'] ?? '');
    $is_anonymous = isset($_POST['is_anonymous']) ? 1 : 0;
    $message = trim($_POST['message'] ?? '');

    if ($amount <= 0) {
        $errors[] = 'Please enter a valid donation amount.';
    }

    if (empty($donor_name) && !$is_anonymous) {
        $errors[] = 'Please enter your name or select anonymous donation.';
    }

    if (empty($donor_email) && !$is_anonymous) {
        $errors[] = 'Please enter your email or select anonymous donation.';
    }

    if (!empty($donor_email) && !filter_var($donor_email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Please enter a valid email address.';
    }

    if (empty($errors)) {
        $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

        $stmt = $db->prepare("INSERT INTO donations (user_id, amount, donor_name, donor_email, donor_phone, payment_method, purpose, is_anonymous, message) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param('idsssssiss', $user_id, $amount, $donor_name, $donor_email, $donor_phone, $payment_method, $purpose, $is_anonymous, $message);

        if ($stmt->execute()) {
            $success = 'Thank you for your generous donation! Your contribution helps us continue our mission.';
        } else {
            $errors[] = 'Failed to process donation. Please try again.';
        }
        $stmt->close();
    }
}

// Get recent donations (non-anonymous)
$recent_donations = $db->query("SELECT donor_name, amount, purpose, created_at FROM donations WHERE is_anonymous = 0 ORDER BY created_at DESC LIMIT 10");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donate - Salem Dominion Ministries</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .donate-hero {
            background: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)), url('https://images.unsplash.com/photo-1559027615-cd4628902d4a?ixlib=rb-4.0.3');
            background-size: cover;
            background-position: center;
            color: white;
            padding: 80px 0;
        }
        .navbar-brand {
            font-weight: bold;
            font-size: 1.5rem;
        }
        .donation-card {
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            border: none;
            border-radius: 15px;
        }
        .amount-btn {
            border: 2px solid #007bff;
            background: white;
            color: #007bff;
            transition: all 0.3s ease;
        }
        .amount-btn:hover,
        .amount-btn.active {
            background: #007bff;
            color: white;
        }
        .impact-card {
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
    <section class="donate-hero">
        <div class="container text-center">
            <h1 class="display-4 fw-bold mb-4">Make a Difference</h1>
            <p class="lead mb-4">Your generous giving helps us continue our mission of spreading God's love and serving our community.</p>
        </div>
    </section>

    <!-- Donation Content -->
    <section class="py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <div class="row">
                        <!-- Donation Form -->
                        <div class="col-lg-8 mb-4">
                            <div class="card donation-card">
                                <div class="card-body p-4">
                                    <h3 class="card-title mb-4"><i class="fas fa-heart text-danger"></i> Make a Donation</h3>

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
                                        <!-- Quick Amount Selection -->
                                        <div class="mb-3">
                                            <label class="form-label">Select Amount</label>
                                            <div class="row g-2 mb-3">
                                                <div class="col-6 col-md-3">
                                                    <button type="button" class="btn amount-btn w-100" data-amount="25">$25</button>
                                                </div>
                                                <div class="col-6 col-md-3">
                                                    <button type="button" class="btn amount-btn w-100" data-amount="50">$50</button>
                                                </div>
                                                <div class="col-6 col-md-3">
                                                    <button type="button" class="btn amount-btn w-100" data-amount="100">$100</button>
                                                </div>
                                                <div class="col-6 col-md-3">
                                                    <button type="button" class="btn amount-btn w-100" data-amount="250">$250</button>
                                                </div>
                                            </div>
                                            <div class="input-group">
                                                <span class="input-group-text">$</span>
                                                <input type="number" class="form-control" id="amount" name="amount" step="0.01" min="1" required
                                                       value="<?php echo htmlspecialchars($_POST['amount'] ?? ''); ?>" placeholder="Enter custom amount">
                                            </div>
                                        </div>

                                        <!-- Donor Information -->
                                        <div class="mb-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="is_anonymous" name="is_anonymous">
                                                <label class="form-check-label" for="is_anonymous">
                                                    Make this donation anonymous
                                                </label>
                                            </div>
                                        </div>

                                        <div id="donor_info" class="donor-fields">
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label for="donor_name" class="form-label">Full Name *</label>
                                                    <input type="text" class="form-control" id="donor_name" name="donor_name"
                                                           value="<?php echo htmlspecialchars($_POST['donor_name'] ?? ''); ?>">
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label for="donor_email" class="form-label">Email *</label>
                                                    <input type="email" class="form-control" id="donor_email" name="donor_email"
                                                           value="<?php echo htmlspecialchars($_POST['donor_email'] ?? ''); ?>">
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label for="donor_phone" class="form-label">Phone</label>
                                                <input type="tel" class="form-control" id="donor_phone" name="donor_phone"
                                                       value="<?php echo htmlspecialchars($_POST['donor_phone'] ?? ''); ?>">
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="purpose" class="form-label">Donation Purpose</label>
                                            <select class="form-control" id="purpose" name="purpose">
                                                <option value="general">General Fund</option>
                                                <option value="building">Building Fund</option>
                                                <option value="missions">Missions</option>
                                                <option value="children_ministry">Children Ministry</option>
                                                <option value="youth_ministry">Youth Ministry</option>
                                                <option value="food_bank">Food Bank</option>
                                                <option value="other">Other</option>
                                            </select>
                                        </div>

                                        <div class="mb-3">
                                            <label for="payment_method" class="form-label">Payment Method</label>
                                            <select class="form-control" id="payment_method" name="payment_method">
                                                <option value="cash">Cash</option>
                                                <option value="check">Check</option>
                                                <option value="online">Online Payment</option>
                                                <option value="bank_transfer">Bank Transfer</option>
                                            </select>
                                        </div>

                                        <div class="mb-3">
                                            <label for="message" class="form-label">Message (Optional)</label>
                                            <textarea class="form-control" id="message" name="message" rows="3"
                                                      placeholder="Share why you're giving or any special instructions"><?php echo htmlspecialchars($_POST['message'] ?? ''); ?></textarea>
                                        </div>

                                        <button type="submit" class="btn btn-success btn-lg w-100">
                                            <i class="fas fa-hand-holding-heart"></i> Complete Donation
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Impact & Recent Donations -->
                        <div class="col-lg-4">
                            <!-- Impact Card -->
                            <div class="card impact-card mb-4">
                                <div class="card-body text-center">
                                    <i class="fas fa-hands-helping fa-3x mb-3"></i>
                                    <h5 class="card-title">Your Impact</h5>
                                    <p class="card-text">
                                        $25 feeds a family for a week<br>
                                        $50 provides school supplies for a child<br>
                                        $100 supports our community outreach<br>
                                        $250 helps build our new sanctuary
                                    </p>
                                </div>
                            </div>

                            <!-- Recent Donations -->
                            <div class="card donation-card">
                                <div class="card-body">
                                    <h5 class="card-title"><i class="fas fa-history text-info"></i> Recent Donations</h5>
                                    <?php if ($recent_donations->num_rows > 0): ?>
                                        <div class="list-group list-group-flush">
                                            <?php while ($donation = $recent_donations->fetch_assoc()): ?>
                                                <div class="list-group-item px-0">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <div>
                                                            <strong>$<?php echo number_format($donation['amount'], 2); ?></strong>
                                                            <br><small class="text-muted"><?php echo htmlspecialchars($donation['donor_name']); ?></small>
                                                        </div>
                                                        <small class="text-muted"><?php echo date('M j', strtotime($donation['created_at'])); ?></small>
                                                    </div>
                                                    <?php if ($donation['purpose'] && $donation['purpose'] !== 'general'): ?>
                                                        <small class="text-primary"><?php echo ucfirst(str_replace('_', ' ', $donation['purpose'])); ?></small>
                                                    <?php endif; ?>
                                                </div>
                                            <?php endwhile; ?>
                                        </div>
                                    <?php else: ?>
                                        <p class="text-muted mb-0">No recent donations to display.</p>
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
        // Quick amount selection
        document.querySelectorAll('.amount-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.amount-btn').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                document.getElementById('amount').value = this.dataset.amount;
            });
        });

        // Toggle donor info fields
        document.getElementById('is_anonymous').addEventListener('change', function() {
            const donorFields = document.getElementById('donor_info');
            const requiredLabels = donorFields.querySelectorAll('label[for]');
            const inputs = donorFields.querySelectorAll('input');

            if (this.checked) {
                donorFields.style.opacity = '0.5';
                inputs.forEach(input => {
                    input.required = false;
                    input.disabled = true;
                });
                requiredLabels.forEach(label => {
                    label.textContent = label.textContent.replace(' *', '');
                });
            } else {
                donorFields.style.opacity = '1';
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