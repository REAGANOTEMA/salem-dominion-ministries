&lt;?php
session_start();
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
        $stmt = $db-&gt;prepare("INSERT INTO contact_messages (name, email, phone, subject, message, message_type) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt-&gt;bind_param('ssssss', $name, $email, $phone, $subject, $message, $message_type);
        if ($stmt-&gt;execute()) {
            $success = 'Thank you for your message! We will get back to you soon.';
        } else {
            $errors[] = 'Failed to send message. Please try again.';
        }
        $stmt-&gt;close();
    }
}
?&gt;
&lt;!DOCTYPE html&gt;
&lt;html lang="en"&gt;
&lt;head&gt;
    &lt;meta charset="UTF-8"&gt;
    &lt;meta name="viewport" content="width=device-width, initial-scale=1.0"&gt;
    &lt;title&gt;Contact Us - Salem Dominion Ministries&lt;/title&gt;
    &lt;link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"&gt;
    &lt;link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet"&gt;
    &lt;style&gt;
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
                    &lt;li class="nav-item"&gt;&lt;a class="nav-link active" href="contact.php"&gt;Contact&lt;/a&gt;&lt;/li&gt;
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
    &lt;section class="contact-hero"&gt;
        &lt;div class="container text-center"&gt;
            &lt;h1 class="display-4 fw-bold mb-4"&gt;Contact Us&lt;/h1&gt;
            &lt;p class="lead mb-4"&gt;We'd love to hear from you. Reach out with questions, prayer requests, or just to say hello.&lt;/p&gt;
        &lt;/div&gt;
    &lt;/section&gt;

    &lt;!-- Contact Content --&gt;
    &lt;section class="py-5"&gt;
        &lt;div class="container"&gt;
            &lt;div class="row"&gt;
                &lt;div class="col-lg-8 mx-auto"&gt;
                    &lt;div class="row"&gt;
                        &lt;!-- Contact Form --&gt;
                        &lt;div class="col-lg-8 mb-4"&gt;
                            &lt;div class="card contact-card"&gt;
                                &lt;div class="card-body p-4"&gt;
                                    &lt;h3 class="card-title mb-4"&gt;&lt;i class="fas fa-envelope text-primary"&gt;&lt;/i&gt; Send us a Message&lt;/h3&gt;

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
                                        &lt;div class="row"&gt;
                                            &lt;div class="col-md-6 mb-3"&gt;
                                                &lt;label for="name" class="form-label"&gt;Name *&lt;/label&gt;
                                                &lt;input type="text" class="form-control" id="name" name="name" required
                                                       value="&lt;?php echo htmlspecialchars($_POST['name'] ?? ''); ?&gt;"&gt;
                                            &lt;/div&gt;
                                            &lt;div class="col-md-6 mb-3"&gt;
                                                &lt;label for="email" class="form-label"&gt;Email *&lt;/label&gt;
                                                &lt;input type="email" class="form-control" id="email" name="email" required
                                                       value="&lt;?php echo htmlspecialchars($_POST['email'] ?? ''); ?&gt;"&gt;
                                            &lt;/div&gt;
                                        &lt;/div&gt;
                                        &lt;div class="row"&gt;
                                            &lt;div class="col-md-6 mb-3"&gt;
                                                &lt;label for="phone" class="form-label"&gt;Phone&lt;/label&gt;
                                                &lt;input type="tel" class="form-control" id="phone" name="phone"
                                                       value="&lt;?php echo htmlspecialchars($_POST['phone'] ?? ''); ?&gt;"&gt;
                                            &lt;/div&gt;
                                            &lt;div class="col-md-6 mb-3"&gt;
                                                &lt;label for="message_type" class="form-label"&gt;Message Type&lt;/label&gt;
                                                &lt;select class="form-control" id="message_type" name="message_type"&gt;
                                                    &lt;option value="general"&gt;General Inquiry&lt;/option&gt;
                                                    &lt;option value="prayer"&gt;Prayer Request&lt;/option&gt;
                                                    &lt;option value="testimony"&gt;Testimony&lt;/option&gt;
                                                    &lt;option value="feedback"&gt;Feedback&lt;/option&gt;
                                                    &lt;option value="children_ministry"&gt;Children Ministry&lt;/option&gt;
                                                    &lt;option value="booking_inquiry"&gt;Booking Inquiry&lt;/option&gt;
                                                    &lt;option value="other"&gt;Other&lt;/option&gt;
                                                &lt;/select&gt;
                                            &lt;/div&gt;
                                        &lt;/div&gt;
                                        &lt;div class="mb-3"&gt;
                                            &lt;label for="subject" class="form-label"&gt;Subject *&lt;/label&gt;
                                            &lt;input type="text" class="form-control" id="subject" name="subject" required
                                                   value="&lt;?php echo htmlspecialchars($_POST['subject'] ?? ''); ?&gt;"&gt;
                                        &lt;/div&gt;
                                        &lt;div class="mb-3"&gt;
                                            &lt;label for="message" class="form-label"&gt;Message *&lt;/label&gt;
                                            &lt;textarea class="form-control" id="message" name="message" rows="5" required&gt;&lt;?php echo htmlspecialchars($_POST['message'] ?? ''); ?&gt;&lt;/textarea&gt;
                                        &lt;/div&gt;
                                        &lt;button type="submit" class="btn btn-primary btn-lg"&gt;
                                            &lt;i class="fas fa-paper-plane"&gt;&lt;/i&gt; Send Message
                                        &lt;/button&gt;
                                    &lt;/form&gt;
                                &lt;/div&gt;
                            &lt;/div&gt;
                        &lt;/div&gt;

                        &lt;!-- Contact Info --&gt;
                        &lt;div class="col-lg-4"&gt;
                            &lt;div class="card contact-card mb-4"&gt;
                                &lt;div class="card-body"&gt;
                                    &lt;h5 class="card-title"&gt;&lt;i class="fas fa-map-marker-alt text-primary"&gt;&lt;/i&gt; Visit Us&lt;/h5&gt;
                                    &lt;p class="card-text"&gt;
                                        Salem Dominion Ministries&lt;br&gt;
                                        [Church Address]&lt;br&gt;
                                        [City, State, ZIP]
                                    &lt;/p&gt;
                                &lt;/div&gt;
                            &lt;/div&gt;

                            &lt;div class="card contact-card mb-4"&gt;
                                &lt;div class="card-body"&gt;
                                    &lt;h5 class="card-title"&gt;&lt;i class="fas fa-clock text-success"&gt;&lt;/i&gt; Service Times&lt;/h5&gt;
                                    &lt;ul class="list-unstyled"&gt;
                                        &lt;?php
                                        $services = $db-&gt;query("SELECT * FROM service_times WHERE is_active = 1 ORDER BY start_time");
                                        while ($service = $services-&gt;fetch_assoc()):
                                        ?&gt;
                                        &lt;li&gt;&lt;strong&gt;&lt;?php echo ucfirst($service['day_of_week']); ?&gt;:&lt;/strong&gt; &lt;?php echo date('g:i A', strtotime($service['start_time'])); ?&gt; - &lt;?php echo date('g:i A', strtotime($service['end_time'])); ?&gt;&lt;/li&gt;
                                        &lt;?php endwhile; ?&gt;
                                    &lt;/ul&gt;
                                &lt;/div&gt;
                            &lt;/div&gt;

                            &lt;div class="card contact-card"&gt;
                                &lt;div class="card-body"&gt;
                                    &lt;h5 class="card-title"&gt;&lt;i class="fas fa-phone text-info"&gt;&lt;/i&gt; Get in Touch&lt;/h5&gt;
                                    &lt;p class="card-text"&gt;
                                        &lt;strong&gt;Email:&lt;/strong&gt; visit@salemdominionministries.com&lt;br&gt;
                                        &lt;strong&gt;Phone:&lt;/strong&gt; [Church Phone Number]
                                    &lt;/p&gt;
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
&lt;/body&gt;
&lt;/html&gt;