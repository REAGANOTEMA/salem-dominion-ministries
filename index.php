&lt;?php
session_start();
require_once 'db.php';
?&gt;
&lt;!DOCTYPE html&gt;
&lt;html lang="en"&gt;
&lt;head&gt;
    &lt;meta charset="UTF-8"&gt;
    &lt;meta name="viewport" content="width=device-width, initial-scale=1.0"&gt;
    &lt;title&gt;Salem Dominion Ministries - Home&lt;/title&gt;
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
        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
            transition: all 0.3s ease;
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
    &lt;section class="hero-section"&gt;
        &lt;div class="container text-center"&gt;
            &lt;h1 class="display-4 fw-bold mb-4"&gt;Welcome to Salem Dominion Ministries&lt;/h1&gt;
            &lt;p class="lead mb-4"&gt;Experience God's love, grow in faith, and serve our community together.&lt;/p&gt;
            &lt;a href="about.php" class="btn btn-primary btn-lg me-3"&gt;&lt;i class="fas fa-info-circle"&gt;&lt;/i&gt; Learn More&lt;/a&gt;
            &lt;a href="contact.php" class="btn btn-outline-light btn-lg"&gt;&lt;i class="fas fa-envelope"&gt;&lt;/i&gt; Contact Us&lt;/a&gt;
        &lt;/div&gt;
    &lt;/section&gt;

    &lt;!-- Service Times --&gt;
    &lt;section class="py-5 bg-light"&gt;
        &lt;div class="container"&gt;
            &lt;h2 class="text-center mb-5"&gt;Service Times&lt;/h2&gt;
            &lt;div class="row"&gt;
                &lt;?php
                $services = $db-&gt;query("SELECT * FROM service_times WHERE is_active = 1 ORDER BY FIELD(day_of_week, 'sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'), start_time");
                while ($service = $services-&gt;fetch_assoc()):
                ?&gt;
                &lt;div class="col-md-6 col-lg-4 mb-4"&gt;
                    &lt;div class="card card-hover h-100"&gt;
                        &lt;div class="card-body text-center"&gt;
                            &lt;h5 class="card-title"&gt;&lt;i class="fas fa-clock text-primary"&gt;&lt;/i&gt; &lt;?php echo ucfirst($service['day_of_week']); ?&gt;&lt;/h5&gt;
                            &lt;h6 class="card-subtitle mb-2 text-muted"&gt;&lt;?php echo htmlspecialchars($service['service_name']); ?&gt;&lt;/h6&gt;
                            &lt;p class="card-text"&gt;&lt;?php echo date('g:i A', strtotime($service['start_time'])); ?&gt; - &lt;?php echo date('g:i A', strtotime($service['end_time'])); ?&gt;&lt;/p&gt;
                            &lt;p class="card-text"&gt;&lt;small class="text-muted"&gt;&lt;?php echo htmlspecialchars($service['location']); ?&gt;&lt;/small&gt;&lt;/p&gt;
                        &lt;/div&gt;
                    &lt;/div&gt;
                &lt;/div&gt;
                &lt;?php endwhile; ?&gt;
            &lt;/div&gt;
        &lt;/div&gt;
    &lt;/section&gt;

    &lt;!-- Ministries Preview --&gt;
    &lt;section class="py-5"&gt;
        &lt;div class="container"&gt;
            &lt;h2 class="text-center mb-5"&gt;Our Ministries&lt;/h2&gt;
            &lt;div class="row"&gt;
                &lt;?php
                $ministries = $db-&gt;query("SELECT * FROM ministries WHERE is_active = 1 LIMIT 3");
                while ($ministry = $ministries-&gt;fetch_assoc()):
                ?&gt;
                &lt;div class="col-md-4 mb-4"&gt;
                    &lt;div class="card card-hover h-100"&gt;
                        &lt;div class="card-body"&gt;
                            &lt;h5 class="card-title"&gt;&lt;?php echo htmlspecialchars($ministry['name']); ?&gt;&lt;/h5&gt;
                            &lt;p class="card-text"&gt;&lt;?php echo htmlspecialchars(substr($ministry['description'], 0, 100)) . '...'; ?&gt;&lt;/p&gt;
                            &lt;a href="ministries.php" class="btn btn-primary"&gt;Learn More&lt;/a&gt;
                        &lt;/div&gt;
                    &lt;/div&gt;
                &lt;/div&gt;
                &lt;?php endwhile; ?&gt;
            &lt;/div&gt;
        &lt;/div&gt;
    &lt;/section&gt;

    &lt;!-- Latest News --&gt;
    &lt;section class="py-5 bg-light"&gt;
        &lt;div class="container"&gt;
            &lt;h2 class="text-center mb-5"&gt;Latest News&lt;/h2&gt;
            &lt;div class="row"&gt;
                &lt;?php
                $news = $db-&gt;query("SELECT * FROM news WHERE status = 'published' ORDER BY published_at DESC LIMIT 3");
                while ($item = $news-&gt;fetch_assoc()):
                ?&gt;
                &lt;div class="col-md-4 mb-4"&gt;
                    &lt;div class="card card-hover h-100"&gt;
                        &lt;?php if ($item['featured_image_url']): ?&gt;
                        &lt;img src="&lt;?php echo htmlspecialchars($item['featured_image_url']); ?&gt;" class="card-img-top" alt="News Image"&gt;
                        &lt;?php endif; ?&gt;
                        &lt;div class="card-body"&gt;
                            &lt;h5 class="card-title"&gt;&lt;?php echo htmlspecialchars($item['title']); ?&gt;&lt;/h5&gt;
                            &lt;p class="card-text"&gt;&lt;?php echo htmlspecialchars(substr($item['excerpt'], 0, 100)) . '...'; ?&gt;&lt;/p&gt;
                            &lt;a href="news.php?id=&lt;?php echo $item['id']; ?&gt;" class="btn btn-primary"&gt;Read More&lt;/a&gt;
                        &lt;/div&gt;
                    &lt;/div&gt;
                &lt;/div&gt;
                &lt;?php endwhile; ?&gt;
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
                        &lt;li&gt;&lt;a href="about.php" class="text-light"&gt;About Us&lt;/a&gt;&lt;/li&gt;
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