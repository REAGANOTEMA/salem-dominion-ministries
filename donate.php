&lt;?php
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

    if ($amount &lt;= 0) {
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

        $stmt = $db-&gt;prepare("INSERT INTO donations (user_id, amount, donor_name, donor_email, donor_phone, payment_method, purpose, is_anonymous, message) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt-&gt;bind_param('idsssssiss', $user_id, $amount, $donor_name, $donor_email, $donor_phone, $payment_method, $purpose, $is_anonymous, $message);

        if ($stmt-&gt;execute()) {
            $success = 'Thank you for your generous donation! Your contribution helps us continue our mission.';
        } else {
            $errors[] = 'Failed to process donation. Please try again.';
        }
        $stmt-&gt;close();
    }
}

// Get recent donations (non-anonymous)
$recent_donations = $db-&gt;query("SELECT donor_name, amount, purpose, created_at FROM donations WHERE is_anonymous = 0 ORDER BY created_at DESC LIMIT 10");
?&gt;
&lt;!DOCTYPE html&gt;
&lt;html lang="en"&gt;
&lt;head&gt;
    &lt;meta charset="UTF-8"&gt;
    &lt;meta name="viewport" content="width=device-width, initial-scale=1.0"&gt;
    &lt;title&gt;Donate - Salem Dominion Ministries&lt;/title&gt;
    &lt;link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"&gt;
    &lt;link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet"&gt;
    &lt;style&gt;
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
    &lt;/style&gt;
&lt;/head&gt;
&lt;body&gt;
    &lt;!-- Navigation --&gt;
    &lt;nav class="navbar navbar-expand-lg navbar-dark bg-dark"&gt;
        &lt;div class="container"&gt;
            &lt;a class="navbar-brand" href="index.php"&gt;
                &lt;i class="fas fa-church"&gt;&lt;/i&gt; Salem Dominion Ministries
            &lt;/a&gt;
            &lt;button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"&gt;
                &lt;span class="navbar-toggler-icon"&gt;&lt;/span&gt;
            &lt;/button&gt;
            &lt;div class="collapse navbar-collapse" id="navbarNav"&gt;
                &lt;ul class="navbar-nav me-auto"&gt;
                    &lt;li class="nav-item"&gt;&lt;a class="nav-link" href="index.php"&gt;Home&lt;/a&gt;&lt;/li&gt;
                    &lt;li class="nav-item"&gt;&lt;a class="nav-link" href="about.php"&gt;About&lt;/a&gt;&lt;/li&gt;
                    &lt;li class="nav-item"&gt;&lt;a class="nav-link" href="ministries.php"&gt;Ministries&lt;/a&gt;&lt;/li&gt;
                    &lt;li class="nav-item"&gt;&lt;a class="nav-link" href="events.php"&gt;Events&lt;/a&gt;&lt;/li&gt;
                    &lt;li class="nav-item"&gt;&lt;a class="nav-link" href="sermons.php"&gt;Sermons&lt;/a&gt;&lt;/li&gt;
                    &lt;li class="nav-item"&gt;&lt;a class="nav-link" href="news.php"&gt;News&lt;/a&gt;&lt;/li&gt;
                    &lt;li class="nav-item"&gt;&lt;a class="nav-link" href="gallery.php"&gt;Gallery&lt;/a&gt;&lt;/li&gt;
                    &lt;li class="nav-item"&gt;&lt;a class="nav-link" href="contact.php"&gt;Contact&lt;/a&gt;&lt;/li&gt;
                &lt;/ul&gt;
                &lt;ul class="navbar-nav"&gt;
                    &lt;?php if (isset($_SESSION['user_id'])): ?&gt;
                        &lt;li class="nav-item"&gt;&lt;a class="nav-link" href="dashboard.php"&gt;Dashboard&lt;/a&gt;&lt;/li&gt;
                        &lt;li class="nav-item"&gt;&lt;a class="nav-link" href="logout.php"&gt;Logout&lt;/a&gt;&lt;/li&gt;
                    &lt;?php else: ?&gt;
                        &lt;li class="nav-item"&gt;&lt;a class="nav-link" href="login.php"&gt;Login&lt;/a&gt;&lt;/li&gt;
                        &lt;li class="nav-item"&gt;&lt;a class="nav-link" href="register.php"&gt;Register&lt;/a&gt;&lt;/li&gt;
                    &lt;?php endif; ?&gt;
                &lt;/ul&gt;
            &lt;/div&gt;
        &lt;/div&gt;
    &lt;/nav&gt;

    &lt;!-- Hero Section --&gt;
    &lt;section class="donate-hero"&gt;
        &lt;div class="container text-center"&gt;
            &lt;h1 class="display-4 fw-bold mb-4"&gt;Make a Difference&lt;/h1&gt;
            &lt;p class="lead mb-4"&gt;Your generous giving helps us continue our mission of spreading God's love and serving our community.&lt;/p&gt;
        &lt;/div&gt;
    &lt;/section&gt;

    &lt;!-- Donation Content --&gt;
    &lt;section class="py-5"&gt;
        &lt;div class="container"&gt;
            &lt;div class="row"&gt;
                &lt;div class="col-lg-8 mx-auto"&gt;
                    &lt;div class="row"&gt;
                        &lt;!-- Donation Form --&gt;
                        &lt;div class="col-lg-8 mb-4"&gt;
                            &lt;div class="card donation-card"&gt;
                                &lt;div class="card-body p-4"&gt;
                                    &lt;h3 class="card-title mb-4"&gt;&lt;i class="fas fa-heart text-danger"&gt;&lt;/i&gt; Make a Donation&lt;/h3&gt;

                                    &lt;?php if (!empty($errors)): ?&gt;
                                        &lt;div class="alert alert-danger"&gt;
                                            &lt;ul class="mb-0"&gt;
                                                &lt;?php foreach ($errors as $error): ?&gt;
                                                    &lt;li&gt;&lt;?php echo htmlspecialchars($error); ?&gt;&lt;/li&gt;
                                                &lt;?php endforeach; ?&gt;
                                            &lt;/ul&gt;
                                        &lt;/div&gt;
                                    &lt;?php endif; ?&gt;

                                    &lt;?php if ($success): ?&gt;
                                        &lt;div class="alert alert-success"&gt;
                                            &lt;?php echo htmlspecialchars($success); ?&gt;
                                        &lt;/div&gt;
                                    &lt;?php endif; ?&gt;

                                    &lt;form method="POST" action=""&gt;
                                        &lt;!-- Quick Amount Selection --&gt;
                                        &lt;div class="mb-3"&gt;
                                            &lt;label class="form-label"&gt;Select Amount&lt;/label&gt;
                                            &lt;div class="row g-2 mb-3"&gt;
                                                &lt;div class="col-6 col-md-3"&gt;
                                                    &lt;button type="button" class="btn amount-btn w-100" data-amount="25"&gt;$25&lt;/button&gt;
                                                &lt;/div&gt;
                                                &lt;div class="col-6 col-md-3"&gt;
                                                    &lt;button type="button" class="btn amount-btn w-100" data-amount="50"&gt;$50&lt;/button&gt;
                                                &lt;/div&gt;
                                                &lt;div class="col-6 col-md-3"&gt;
                                                    &lt;button type="button" class="btn amount-btn w-100" data-amount="100"&gt;$100&lt;/button&gt;
                                                &lt;/div&gt;
                                                &lt;div class="col-6 col-md-3"&gt;
                                                    &lt;button type="button" class="btn amount-btn w-100" data-amount="250"&gt;$250&lt;/button&gt;
                                                &lt;/div&gt;
                                            &lt;/div&gt;
                                            &lt;div class="input-group"&gt;
                                                &lt;span class="input-group-text"&gt;$&lt;/span&gt;
                                                &lt;input type="number" class="form-control" id="amount" name="amount" step="0.01" min="1" required
                                                       value="&lt;?php echo htmlspecialchars($_POST['amount'] ?? ''); ?&gt;" placeholder="Enter custom amount"&gt;
                                            &lt;/div&gt;
                                        &lt;/div&gt;

                                        &lt;!-- Donor Information --&gt;
                                        &lt;div class="mb-3"&gt;
                                            &lt;div class="form-check"&gt;
                                                &lt;input class="form-check-input" type="checkbox" id="is_anonymous" name="is_anonymous"&gt;
                                                &lt;label class="form-check-label" for="is_anonymous"&gt;
                                                    Make this donation anonymous
                                                &lt;/label&gt;
                                            &lt;/div&gt;
                                        &lt;/div&gt;

                                        &lt;div id="donor_info" class="donor-fields"&gt;
                                            &lt;div class="row"&gt;
                                                &lt;div class="col-md-6 mb-3"&gt;
                                                    &lt;label for="donor_name" class="form-label"&gt;Full Name *&lt;/label&gt;
                                                    &lt;input type="text" class="form-control" id="donor_name" name="donor_name"
                                                           value="&lt;?php echo htmlspecialchars($_POST['donor_name'] ?? ''); ?&gt;"&gt;
                                                &lt;/div&gt;
                                                &lt;div class="col-md-6 mb-3"&gt;
                                                    &lt;label for="donor_email" class="form-label"&gt;Email *&lt;/label&gt;
                                                    &lt;input type="email" class="form-control" id="donor_email" name="donor_email"
                                                           value="&lt;?php echo htmlspecialchars($_POST['donor_email'] ?? ''); ?&gt;"&gt;
                                                &lt;/div&gt;
                                            &lt;/div&gt;
                                            &lt;div class="mb-3"&gt;
                                                &lt;label for="donor_phone" class="form-label"&gt;Phone&lt;/label&gt;
                                                &lt;input type="tel" class="form-control" id="donor_phone" name="donor_phone"
                                                       value="&lt;?php echo htmlspecialchars($_POST['donor_phone'] ?? ''); ?&gt;"&gt;
                                            &lt;/div&gt;
                                        &lt;/div&gt;

                                        &lt;div class="mb-3"&gt;
                                            &lt;label for="purpose" class="form-label"&gt;Donation Purpose&lt;/label&gt;
                                            &lt;select class="form-control" id="purpose" name="purpose"&gt;
                                                &lt;option value="general"&gt;General Fund&lt;/option&gt;
                                                &lt;option value="building"&gt;Building Fund&lt;/option&gt;
                                                &lt;option value="missions"&gt;Missions&lt;/option&gt;
                                                &lt;option value="children_ministry"&gt;Children Ministry&lt;/option&gt;
                                                &lt;option value="youth_ministry"&gt;Youth Ministry&lt;/option&gt;
                                                &lt;option value="food_bank"&gt;Food Bank&lt;/option&gt;
                                                &lt;option value="other"&gt;Other&lt;/option&gt;
                                            &lt;/select&gt;
                                        &lt;/div&gt;

                                        &lt;div class="mb-3"&gt;
                                            &lt;label for="payment_method" class="form-label"&gt;Payment Method&lt;/label&gt;
                                            &lt;select class="form-control" id="payment_method" name="payment_method"&gt;
                                                &lt;option value="cash"&gt;Cash&lt;/option&gt;
                                                &lt;option value="check"&gt;Check&lt;/option&gt;
                                                &lt;option value="online"&gt;Online Payment&lt;/option&gt;
                                                &lt;option value="bank_transfer"&gt;Bank Transfer&lt;/option&gt;
                                            &lt;/select&gt;
                                        &lt;/div&gt;

                                        &lt;div class="mb-3"&gt;
                                            &lt;label for="message" class="form-label"&gt;Message (Optional)&lt;/label&gt;
                                            &lt;textarea class="form-control" id="message" name="message" rows="3"
                                                      placeholder="Share why you're giving or any special instructions"&gt;&lt;?php echo htmlspecialchars($_POST['message'] ?? ''); ?&gt;&lt;/textarea&gt;
                                        &lt;/div&gt;

                                        &lt;button type="submit" class="btn btn-success btn-lg w-100"&gt;
                                            &lt;i class="fas fa-hand-holding-heart"&gt;&lt;/i&gt; Complete Donation
                                        &lt;/button&gt;
                                    &lt;/form&gt;
                                &lt;/div&gt;
                            &lt;/div&gt;
                        &lt;/div&gt;

                        &lt;!-- Impact & Recent Donations --&gt;
                        &lt;div class="col-lg-4"&gt;
                            &lt;!-- Impact Card --&gt;
                            &lt;div class="card impact-card mb-4"&gt;
                                &lt;div class="card-body text-center"&gt;
                                    &lt;i class="fas fa-hands-helping fa-3x mb-3"&gt;&lt;/i&gt;
                                    &lt;h5 class="card-title"&gt;Your Impact&lt;/h5&gt;
                                    &lt;p class="card-text"&gt;
                                        $25 feeds a family for a week&lt;br&gt;
                                        $50 provides school supplies for a child&lt;br&gt;
                                        $100 supports our community outreach&lt;br&gt;
                                        $250 helps build our new sanctuary
                                    &lt;/p&gt;
                                &lt;/div&gt;
                            &lt;/div&gt;

                            &lt;!-- Recent Donations --&gt;
                            &lt;div class="card donation-card"&gt;
                                &lt;div class="card-body"&gt;
                                    &lt;h5 class="card-title"&gt;&lt;i class="fas fa-history text-info"&gt;&lt;/i&gt; Recent Donations&lt;/h5&gt;
                                    &lt;?php if ($recent_donations-&gt;num_rows &gt; 0): ?&gt;
                                        &lt;div class="list-group list-group-flush"&gt;
                                            &lt;?php while ($donation = $recent_donations-&gt;fetch_assoc()): ?&gt;
                                                &lt;div class="list-group-item px-0"&gt;
                                                    &lt;div class="d-flex justify-content-between align-items-center"&gt;
                                                        &lt;div&gt;
                                                            &lt;strong&gt;$&lt;?php echo number_format($donation['amount'], 2); ?&gt;&lt;/strong&gt;
                                                            &lt;br&gt;&lt;small class="text-muted"&gt;&lt;?php echo htmlspecialchars($donation['donor_name']); ?&gt;&lt;/small&gt;
                                                        &lt;/div&gt;
                                                        &lt;small class="text-muted"&gt;&lt;?php echo date('M j', strtotime($donation['created_at'])); ?&gt;&lt;/small&gt;
                                                    &lt;/div&gt;
                                                    &lt;?php if ($donation['purpose'] && $donation['purpose'] !== 'general'): ?&gt;
                                                        &lt;small class="text-primary"&gt;&lt;?php echo ucfirst(str_replace('_', ' ', $donation['purpose'])); ?&gt;&lt;/small&gt;
                                                    &lt;?php endif; ?&gt;
                                                &lt;/div&gt;
                                            &lt;?php endwhile; ?&gt;
                                        &lt;/div&gt;
                                    &lt;?php else: ?&gt;
                                        &lt;p class="text-muted mb-0"&gt;No recent donations to display.&lt;/p&gt;
                                    &lt;?php endif; ?&gt;
                                &lt;/div&gt;
                            &lt;/div&gt;
                        &lt;/div&gt;
                    &lt;/div&gt;
                &lt;/div&gt;
            &lt;/div&gt;
        &lt;/div&gt;
    &lt;/section&gt;

    &lt;!-- Footer --&gt;
    &lt;footer class="bg-dark text-light py-4"&gt;
        &lt;div class="container"&gt;
            &lt;div class="row"&gt;
                &lt;div class="col-md-4"&gt;
                    &lt;h5&gt;Salem Dominion Ministries&lt;/h5&gt;
                    &lt;p&gt;Serving our community with faith, hope, and love.&lt;/p&gt;
                &lt;/div&gt;
                &lt;div class="col-md-4"&gt;
                    &lt;h5&gt;Quick Links&lt;/h5&gt;
                    &lt;ul class="list-unstyled"&gt;
                        &lt;li&gt;&lt;a href="index.php" class="text-light"&gt;Home&lt;/a&gt;&lt;/li&gt;
                        &lt;li&gt;&lt;a href="about.php" class="text-light"&gt;About Us&lt;/a&gt;&lt;/li&gt;
                        &lt;li&gt;&lt;a href="donate.php" class="text-light"&gt;Donate&lt;/a&gt;&lt;/li&gt;
                    &lt;/ul&gt;
                &lt;/div&gt;
                &lt;div class="col-md-4"&gt;
                    &lt;h5&gt;Contact Info&lt;/h5&gt;
                    &lt;p&gt;&lt;i class="fas fa-envelope"&gt;&lt;/i&gt; visit@salemdominionministries.com&lt;/p&gt;
                    &lt;p&gt;&lt;i class="fas fa-phone"&gt;&lt;/i&gt; Contact us for service times&lt;/p&gt;
                &lt;/div&gt;
            &lt;/div&gt;
            &lt;hr&gt;
            &lt;p class="text-center mb-0"&gt;&amp;copy; 2026 Salem Dominion Ministries. All rights reserved.&lt;/p&gt;
        &lt;/div&gt;
    &lt;/footer&gt;

    &lt;script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"&gt;&lt;/script&gt;
    &lt;script&gt;
        // Quick amount selection
        document.querySelectorAll('.amount-btn').forEach(btn =&gt; {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.amount-btn').forEach(b =&gt; b.classList.remove('active'));
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
                inputs.forEach(input =&gt; {
                    input.required = false;
                    input.disabled = true;
                });
                requiredLabels.forEach(label =&gt; {
                    label.textContent = label.textContent.replace(' *', '');
                });
            } else {
                donorFields.style.opacity = '1';
                inputs.forEach(input =&gt; {
                    input.required = true;
                    input.disabled = false;
                });
                requiredLabels.forEach(label =&gt; {
                    if (!label.textContent.includes(' *')) {
                        label.textContent += ' *';
                    }
                });
            }
        });
    &lt;/script&gt;
&lt;/body&gt;
&lt;/html&gt;