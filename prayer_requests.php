&lt;?php
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

        $stmt = $db-&gt;prepare("INSERT INTO prayer_requests (user_id, requestor_name, requestor_email, requestor_phone, prayer_request, request_type, is_anonymous, is_public) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt-&gt;bind_param('isssssii', $user_id, $requestor_name, $requestor_email, $requestor_phone, $prayer_request, $request_type, $is_anonymous, $is_public);

        if ($stmt-&gt;execute()) {
            $success = 'Your prayer request has been submitted. We will pray for you and keep you in our thoughts.';
        } else {
            $errors[] = 'Failed to submit prayer request. Please try again.';
        }
        $stmt-&gt;close();
    }
}

// Get public prayer requests
$public_requests = $db-&gt;query("SELECT * FROM prayer_requests WHERE is_public = 1 AND is_anonymous = 0 ORDER BY created_at DESC LIMIT 10");
?&gt;
&lt;!DOCTYPE html&gt;
&lt;html lang="en"&gt;
&lt;head&gt;
    &lt;meta charset="UTF-8"&gt;
    &lt;meta name="viewport" content="width=device-width, initial-scale=1.0"&gt;
    &lt;title&gt;Prayer Requests - Salem Dominion Ministries&lt;/title&gt;
    &lt;link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"&gt;
    &lt;link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet"&gt;
    &lt;style&gt;
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
    &lt;section class="prayer-hero"&gt;
        &lt;div class="container text-center"&gt;
            &lt;h1 class="display-4 fw-bold mb-4"&gt;Prayer Requests&lt;/h1&gt;
            &lt;p class="lead mb-4"&gt;"Therefore I tell you, whatever you ask for in prayer, believe that you have received it, and it will be yours." - Mark 11:24&lt;/p&gt;
        &lt;/div&gt;
    &lt;/section&gt;

    &lt;!-- Prayer Content --&gt;
    &lt;section class="py-5"&gt;
        &lt;div class="container"&gt;
            &lt;div class="row"&gt;
                &lt;div class="col-lg-8 mx-auto"&gt;
                    &lt;div class="row"&gt;
                        &lt;!-- Prayer Request Form --&gt;
                        &lt;div class="col-lg-8 mb-4"&gt;
                            &lt;div class="card prayer-card"&gt;
                                &lt;div class="card-body p-4"&gt;
                                    &lt;h3 class="card-title mb-4"&gt;&lt;i class="fas fa-praying-hands text-primary"&gt;&lt;/i&gt; Submit a Prayer Request&lt;/h3&gt;

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
                                        &lt;!-- Privacy Options --&gt;
                                        &lt;div class="mb-3"&gt;
                                            &lt;div class="form-check mb-2"&gt;
                                                &lt;input class="form-check-input" type="checkbox" id="is_anonymous" name="is_anonymous"&gt;
                                                &lt;label class="form-check-label" for="is_anonymous"&gt;
                                                    Submit anonymously
                                                &lt;/label&gt;
                                            &lt;/div&gt;
                                            &lt;div class="form-check"&gt;
                                                &lt;input class="form-check-input" type="checkbox" id="is_public" name="is_public" checked&gt;
                                                &lt;label class="form-check-label" for="is_public"&gt;
                                                    Allow my prayer request to be shared publicly (helps others pray)
                                                &lt;/label&gt;
                                            &lt;/div&gt;
                                        &lt;/div&gt;

                                        &lt;div id="requestor_info" class="requestor-fields"&gt;
                                            &lt;div class="row"&gt;
                                                &lt;div class="col-md-6 mb-3"&gt;
                                                    &lt;label for="requestor_name" class="form-label"&gt;Your Name *&lt;/label&gt;
                                                    &lt;input type="text" class="form-control" id="requestor_name" name="requestor_name"
                                                           value="&lt;?php echo htmlspecialchars($_POST['requestor_name'] ?? ''); ?&gt;"&gt;
                                                &lt;/div&gt;
                                                &lt;div class="col-md-6 mb-3"&gt;
                                                    &lt;label for="requestor_email" class="form-label"&gt;Email *&lt;/label&gt;
                                                    &lt;input type="email" class="form-control" id="requestor_email" name="requestor_email"
                                                           value="&lt;?php echo htmlspecialchars($_POST['requestor_email'] ?? ''); ?&gt;"&gt;
                                                &lt;/div&gt;
                                            &lt;/div&gt;
                                            &lt;div class="mb-3"&gt;
                                                &lt;label for="requestor_phone" class="form-label"&gt;Phone (Optional)&lt;/label&gt;
                                                &lt;input type="tel" class="form-control" id="requestor_phone" name="requestor_phone"
                                                       value="&lt;?php echo htmlspecialchars($_POST['requestor_phone'] ?? ''); ?&gt;"&gt;
                                            &lt;/div&gt;
                                        &lt;/div&gt;

                                        &lt;div class="mb-3"&gt;
                                            &lt;label for="request_type" class="form-label"&gt;Type of Request&lt;/label&gt;
                                            &lt;select class="form-control" id="request_type" name="request_type"&gt;
                                                &lt;option value="personal"&gt;Personal&lt;/option&gt;
                                                &lt;option value="family"&gt;Family&lt;/option&gt;
                                                &lt;option value="health"&gt;Health&lt;/option&gt;
                                                &lt;option value="spiritual"&gt;Spiritual&lt;/option&gt;
                                                &lt;option value="financial"&gt;Financial&lt;/option&gt;
                                                &lt;option value="relationships"&gt;Relationships&lt;/option&gt;
                                                &lt;option value="other"&gt;Other&lt;/option&gt;
                                            &lt;/select&gt;
                                        &lt;/div&gt;

                                        &lt;div class="mb-3"&gt;
                                            &lt;label for="prayer_request" class="form-label"&gt;Prayer Request *&lt;/label&gt;
                                            &lt;textarea class="form-control" id="prayer_request" name="prayer_request" rows="5" required
                                                      placeholder="Please share your prayer request. Be as specific as you feel comfortable sharing."&gt;&lt;?php echo htmlspecialchars($_POST['prayer_request'] ?? ''); ?&gt;&lt;/textarea&gt;
                                        &lt;/div&gt;

                                        &lt;button type="submit" class="btn btn-primary btn-lg w-100"&gt;
                                            &lt;i class="fas fa-paper-plane"&gt;&lt;/i&gt; Submit Prayer Request
                                        &lt;/button&gt;
                                    &lt;/form&gt;
                                &lt;/div&gt;
                            &lt;/div&gt;
                        &lt;/div&gt;

                        &lt;!-- Public Requests & Verse --&gt;
                        &lt;div class="col-lg-4"&gt;
                            &lt;!-- Encouraging Verse --&gt;
                            &lt;div class="card verse-card mb-4"&gt;
                                &lt;div class="card-body text-center"&gt;
                                    &lt;i class="fas fa-bible fa-3x mb-3"&gt;&lt;/i&gt;
                                    &lt;h5 class="card-title"&gt;Be Encouraged&lt;/h5&gt;
                                    &lt;p class="card-text mb-0"&gt;
                                        "Do not be anxious about anything, but in every situation, by prayer and petition, with thanksgiving, present your requests to God."&lt;br&gt;&lt;br&gt;
                                        &lt;strong&gt;- Philippians 4:6&lt;/strong&gt;
                                    &lt;/p&gt;
                                &lt;/div&gt;
                            &lt;/div&gt;

                            &lt;!-- Public Prayer Requests --&gt;
                            &lt;div class="card prayer-card"&gt;
                                &lt;div class="card-body"&gt;
                                    &lt;h5 class="card-title"&gt;&lt;i class="fas fa-users text-info"&gt;&lt;/i&gt; Community Prayer Requests&lt;/h5&gt;
                                    &lt;p class="text-muted small mb-3"&gt;These requests have been shared publicly to encourage community prayer.&lt;/p&gt;

                                    &lt;?php if ($public_requests-&gt;num_rows &gt; 0): ?&gt;
                                        &lt;div class="prayer-requests-list"&gt;
                                            &lt;?php while ($request = $public_requests-&gt;fetch_assoc()): ?&gt;
                                                &lt;div class="prayer-request-item"&gt;
                                                    &lt;div class="d-flex justify-content-between align-items-start mb-2"&gt;
                                                        &lt;strong&gt;&lt;?php echo htmlspecialchars($request['requestor_name']); ?&gt;&lt;/strong&gt;
                                                        &lt;small class="text-muted"&gt;&lt;?php echo date('M j, Y', strtotime($request['created_at'])); ?&gt;&lt;/small&gt;
                                                    &lt;/div&gt;
                                                    &lt;p class="mb-2"&gt;&lt;?php echo htmlspecialchars(substr($request['prayer_request'], 0, 150)); ?&gt;&lt;?php echo strlen($request['prayer_request']) &gt; 150 ? '...' : ''; ?&gt;&lt;/p&gt;
                                                    &lt;small class="text-primary"&gt;&lt;i class="fas fa-tag"&gt;&lt;/i&gt; &lt;?php echo ucfirst($request['request_type']); ?&gt;&lt;/small&gt;
                                                &lt;/div&gt;
                                            &lt;?php endwhile; ?&gt;
                                        &lt;/div&gt;
                                    &lt;?php else: ?&gt;
                                        &lt;p class="text-muted mb-0"&gt;No public prayer requests at this time.&lt;/p&gt;
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
        // Toggle requestor info fields
        document.getElementById('is_anonymous').addEventListener('change', function() {
            const requestorFields = document.getElementById('requestor_info');
            const requiredLabels = requestorFields.querySelectorAll('label[for]');
            const inputs = requestorFields.querySelectorAll('input[type="text"], input[type="email"]');

            if (this.checked) {
                requestorFields.style.opacity = '0.5';
                inputs.forEach(input =&gt; {
                    input.required = false;
                    input.disabled = true;
                });
                requiredLabels.forEach(label =&gt; {
                    label.textContent = label.textContent.replace(' *', '');
                });
            } else {
                requestorFields.style.opacity = '1';
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