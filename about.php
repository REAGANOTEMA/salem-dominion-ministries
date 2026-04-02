&lt;?php
session_start();
require_once 'db.php';
?&gt;
&lt;!DOCTYPE html&gt;
&lt;html lang="en"&gt;
&lt;head&gt;
    &lt;meta charset="UTF-8"&gt;
    &lt;meta name="viewport" content="width=device-width, initial-scale=1.0"&gt;
    &lt;title&gt;About Us - Salem Dominion Ministries&lt;/title&gt;
    &lt;link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"&gt;
    &lt;link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet"&gt;
    &lt;style&gt;
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
                    &lt;li class="nav-item"&gt;&lt;a class="nav-link active" href="about.php"&gt;About&lt;/a&gt;&lt;/li&gt;
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
    &lt;section class="hero-section"&gt;
        &lt;div class="container text-center"&gt;
            &lt;h1 class="display-4 fw-bold mb-4"&gt;About Salem Dominion Ministries&lt;/h1&gt;
            &lt;p class="lead mb-4"&gt;Discover our mission, vision, and the heart behind our ministry.&lt;/p&gt;
        &lt;/div&gt;
    &lt;/section&gt;

    &lt;!-- About Content --&gt;
    &lt;section class="py-5"&gt;
        &lt;div class="container"&gt;
            &lt;div class="row"&gt;
                &lt;div class="col-lg-8 mx-auto"&gt;
                    &lt;h2 class="text-center mb-4"&gt;Our Story&lt;/h2&gt;
                    &lt;p class="lead text-center mb-5"&gt;
                        Salem Dominion Ministries is a vibrant Christian community dedicated to spreading God's love,
                        fostering spiritual growth, and serving our local community with compassion and excellence.
                    &lt;/p&gt;

                    &lt;div class="row mb-5"&gt;
                        &lt;div class="col-md-6"&gt;
                            &lt;h3&gt;&lt;i class="fas fa-bullseye text-primary"&gt;&lt;/i&gt; Our Mission&lt;/h3&gt;
                            &lt;p&gt;
                                To create a welcoming environment where people can encounter God's love, grow in their faith,
                                and discover their purpose in serving others. We are committed to building a community that
                                reflects Christ's love and teachings.
                            &lt;/p&gt;
                        &lt;/div&gt;
                        &lt;div class="col-md-6"&gt;
                            &lt;h3&gt;&lt;i class="fas fa-eye text-success"&gt;&lt;/i&gt; Our Vision&lt;/h3&gt;
                            &lt;p&gt;
                                To be a beacon of hope and faith in our community, reaching out to all people with the
                                message of salvation and demonstrating God's love through practical service and spiritual
                                guidance.
                            &lt;/p&gt;
                        &lt;/div&gt;
                    &lt;/div&gt;

                    &lt;h3 class="mb-4"&gt;&lt;i class="fas fa-heart text-danger"&gt;&lt;/i&gt; What We Believe&lt;/h3&gt;
                    &lt;div class="row"&gt;
                        &lt;div class="col-md-6 mb-4"&gt;
                            &lt;div class="card h-100"&gt;
                                &lt;div class="card-body"&gt;
                                    &lt;h5 class="card-title"&gt;The Bible&lt;/h5&gt;
                                    &lt;p class="card-text"&gt;We believe the Bible is God's inspired Word, providing guidance for faith and life.&lt;/p&gt;
                                &lt;/div&gt;
                            &lt;/div&gt;
                        &lt;/div&gt;
                        &lt;div class="col-md-6 mb-4"&gt;
                            &lt;div class="card h-100"&gt;
                                &lt;div class="card-body"&gt;
                                    &lt;h5 class="card-title"&gt;Jesus Christ&lt;/h5&gt;
                                    &lt;p class="card-text"&gt;We believe in Jesus Christ as the Son of God and our Savior.&lt;/p&gt;
                                &lt;/div&gt;
                            &lt;/div&gt;
                        &lt;/div&gt;
                        &lt;div class="col-md-6 mb-4"&gt;
                            &lt;div class="card h-100"&gt;
                                &lt;div class="card-body"&gt;
                                    &lt;h5 class="card-title"&gt;The Holy Spirit&lt;/h5&gt;
                                    &lt;p class="card-text"&gt;We believe in the Holy Spirit's power to transform lives.&lt;/p&gt;
                                &lt;/div&gt;
                            &lt;/div&gt;
                        &lt;/div&gt;
                        &lt;div class="col-md-6 mb-4"&gt;
                            &lt;div class="card h-100"&gt;
                                &lt;div class="card-body"&gt;
                                    &lt;h5 class="card-title"&gt;Community&lt;/h5&gt;
                                    &lt;p class="card-text"&gt;We believe in the importance of Christian fellowship and service.&lt;/p&gt;
                                &lt;/div&gt;
                            &lt;/div&gt;
                        &lt;/div&gt;
                    &lt;/div&gt;

                    &lt;h3 class="mb-4"&gt;&lt;i class="fas fa-users text-info"&gt;&lt;/i&gt; Our Leadership&lt;/h3&gt;
                    &lt;?php
                    $pastor = $db-&gt;query("SELECT * FROM users WHERE role = 'pastor' LIMIT 1")-&gt;fetch_assoc();
                    if ($pastor):
                    ?&gt;
                    &lt;div class="card mb-4"&gt;
                        &lt;div class="card-body text-center"&gt;
                            &lt;?php if ($pastor['avatar_url']): ?&gt;
                            &lt;img src="&lt;?php echo htmlspecialchars($pastor['avatar_url']); ?&gt;" alt="Pastor" class="rounded-circle mb-3" style="width: 120px; height: 120px; object-fit: cover;"&gt;
                            &lt;?php endif; ?&gt;
                            &lt;h5&gt;&lt;?php echo htmlspecialchars($pastor['first_name'] . ' ' . $pastor['last_name']); ?&gt;&lt;/h5&gt;
                            &lt;p class="text-muted"&gt;Senior Pastor&lt;/p&gt;
                        &lt;/div&gt;
                    &lt;/div&gt;
                    &lt;?php endif; ?&gt;
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
                        &lt;li&gt;&lt;a href="contact.php" class="text-light"&gt;Contact&lt;/a&gt;&lt;/li&gt;
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