&lt;?php
session_start();
require_once 'db.php';

// Get children ministry events
$children_events = $db-&gt;query("SELECT * FROM events WHERE category = 'children' AND event_date &gt;= CURDATE() ORDER BY event_date LIMIT 5");

// Get children ministry news
$children_news = $db-&gt;query("SELECT * FROM news WHERE category = 'children' ORDER BY created_at DESC LIMIT 3");
?&gt;
&lt;!DOCTYPE html&gt;
&lt;html lang="en"&gt;
&lt;head&gt;
    &lt;meta charset="UTF-8"&gt;
    &lt;meta name="viewport" content="width=device-width, initial-scale=1.0"&gt;
    &lt;title&gt;Children Ministry - Salem Dominion Ministries&lt;/title&gt;
    &lt;link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"&gt;
    &lt;link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet"&gt;
    &lt;style&gt;
        .children-hero {
            background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url('https://images.unsplash.com/photo-1544717297-fa95b6ee9643?ixlib=rb-4.0.3');
            background-size: cover;
            background-position: center;
            color: white;
            padding: 80px 0;
        }
        .navbar-brand {
            font-weight: bold;
            font-size: 1.5rem;
        }
        .children-card {
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            border: none;
            border-radius: 15px;
            transition: transform 0.3s ease;
        }
        .children-card:hover {
            transform: translateY(-5px);
        }
        .age-group-card {
            background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%);
            color: white;
            border-radius: 10px;
        }
        .activity-card {
            background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
            border-radius: 10px;
        }
        .fun-element {
            position: absolute;
            font-size: 3rem;
            opacity: 0.1;
            z-index: 0;
        }
        .fun-element:nth-child(1) { top: 10%; left: 10%; color: #ff6b6b; }
        .fun-element:nth-child(2) { top: 20%; right: 15%; color: #4ecdc4; }
        .fun-element:nth-child(3) { bottom: 15%; left: 20%; color: #ffd93d; }
        .fun-element:nth-child(4) { bottom: 10%; right: 10%; color: #a8e6cf; }
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
    &lt;section class="children-hero"&gt;
        &lt;div class="container text-center position-relative"&gt;
            &lt;i class="fas fa-child fun-element"&gt;&lt;/i&gt;
            &lt;i class="fas fa-heart fun-element"&gt;&lt;/i&gt;
            &lt;i class="fas fa-star fun-element"&gt;&lt;/i&gt;
            &lt;i class="fas fa-smile fun-element"&gt;&lt;/i&gt;
            &lt;h1 class="display-4 fw-bold mb-4"&gt;Children Ministry&lt;/h1&gt;
            &lt;p class="lead mb-4"&gt;"Let the little children come to me, and do not hinder them, for the kingdom of heaven belongs to such as these." - Matthew 19:14&lt;/p&gt;
        &lt;/div&gt;
    &lt;/section&gt;

    &lt;!-- Ministry Overview --&gt;
    &lt;section class="py-5 bg-light"&gt;
        &lt;div class="container"&gt;
            &lt;div class="row"&gt;
                &lt;div class="col-lg-8 mx-auto text-center"&gt;
                    &lt;h2 class="mb-4"&gt;Welcome to Our Children Ministry&lt;/h2&gt;
                    &lt;p class="lead mb-4"&gt;
                        At Salem Dominion Ministries, we believe that children are the future of our church and community.
                        Our Children Ministry is dedicated to nurturing young hearts with God's love, teaching biblical
                        truths in fun and engaging ways, and helping children grow in their relationship with Jesus Christ.
                    &lt;/p&gt;
                &lt;/div&gt;
            &lt;/div&gt;

            &lt;!-- Age Groups --&gt;
            &lt;div class="row g-4 mb-5"&gt;
                &lt;div class="col-md-6 col-lg-3"&gt;
                    &lt;div class="card age-group-card text-center h-100"&gt;
                        &lt;div class="card-body d-flex flex-column"&gt;
                            &lt;i class="fas fa-baby fa-3x mb-3"&gt;&lt;/i&gt;
                            &lt;h5 class="card-title"&gt;Nursery&lt;/h5&gt;
                            &lt;p class="card-text"&gt;Ages 0-2&lt;/p&gt;
                            &lt;p class="card-text flex-grow-1"&gt;Caring environment for our youngest members with loving supervision and age-appropriate activities.&lt;/p&gt;
                        &lt;/div&gt;
                    &lt;/div&gt;
                &lt;/div&gt;
                &lt;div class="col-md-6 col-lg-3"&gt;
                    &lt;div class="card age-group-card text-center h-100"&gt;
                        &lt;div class="card-body d-flex flex-column"&gt;
                            &lt;i class="fas fa-child fa-3x mb-3"&gt;&lt;/i&gt;
                            &lt;h5 class="card-title"&gt;Toddlers&lt;/h5&gt;
                            &lt;p class="card-text"&gt;Ages 3-5&lt;/p&gt;
                            &lt;p class="card-text flex-grow-1"&gt;Fun learning experiences, songs, stories, and crafts that introduce children to God's love.&lt;/p&gt;
                        &lt;/div&gt;
                    &lt;/div&gt;
                &lt;/div&gt;
                &lt;div class="col-md-6 col-lg-3"&gt;
                    &lt;div class="card age-group-card text-center h-100"&gt;
                        &lt;div class="card-body d-flex flex-column"&gt;
                            &lt;i class="fas fa-school fa-3x mb-3"&gt;&lt;/i&gt;
                            &lt;h5 class="card-title"&gt;Elementary&lt;/h5&gt;
                            &lt;p class="card-text"&gt;Ages 6-11&lt;/p&gt;
                            &lt;p class="card-text flex-grow-1"&gt;Biblical teaching, memory verses, worship, and activities that help children grow spiritually.&lt;/p&gt;
                        &lt;/div&gt;
                    &lt;/div&gt;
                &lt;/div&gt;
                &lt;div class="col-md-6 col-lg-3"&gt;
                    &lt;div class="card age-group-card text-center h-100"&gt;
                        &lt;div class="card-body d-flex flex-column"&gt;
                            &lt;i class="fas fa-users fa-3x mb-3"&gt;&lt;/i&gt;
                            &lt;h5 class="card-title"&gt;Pre-Teens&lt;/h5&gt;
                            &lt;p class="card-text"&gt;Ages 12-14&lt;/p&gt;
                            &lt;p class="card-text flex-grow-1"&gt;Preparing for youth ministry with deeper biblical study, leadership development, and service opportunities.&lt;/p&gt;
                        &lt;/div&gt;
                    &lt;/div&gt;
                &lt;/div&gt;
            &lt;/div&gt;
        &lt;/div&gt;
    &lt;/section&gt;

    &lt;!-- Activities & Programs --&gt;
    &lt;section class="py-5"&gt;
        &lt;div class="container"&gt;
            &lt;h2 class="text-center mb-5"&gt;Our Programs &amp; Activities&lt;/h2&gt;
            &lt;div class="row g-4"&gt;
                &lt;div class="col-lg-4"&gt;
                    &lt;div class="card activity-card h-100"&gt;
                        &lt;div class="card-body text-center"&gt;
                            &lt;i class="fas fa-bible fa-3x text-primary mb-3"&gt;&lt;/i&gt;
                            &lt;h5 class="card-title"&gt;Sunday School&lt;/h5&gt;
                            &lt;p class="card-text"&gt;Weekly Bible lessons, stories, and activities designed specifically for each age group during our Sunday morning service.&lt;/p&gt;
                        &lt;/div&gt;
                    &lt;/div&gt;
                &lt;/div&gt;
                &lt;div class="col-lg-4"&gt;
                    &lt;div class="card activity-card h-100"&gt;
                        &lt;div class="card-body text-center"&gt;
                            &lt;i class="fas fa-music fa-3x text-success mb-3"&gt;&lt;/i&gt;
                            &lt;h5 class="card-title"&gt;Children's Choir&lt;/h5&gt;
                            &lt;p class="card-text"&gt;Learning worship songs, hymns, and developing musical talents while praising God together as a group.&lt;/p&gt;
                        &lt;/div&gt;
                    &lt;/div&gt;
                &lt;/div&gt;
                &lt;div class="col-lg-4"&gt;
                    &lt;div class="card activity-card h-100"&gt;
                        &lt;div class="card-body text-center"&gt;
                            &lt;i class="fas fa-hands-helping fa-3x text-warning mb-3"&gt;&lt;/i&gt;
                            &lt;h5 class="card-title"&gt;Community Service&lt;/h5&gt;
                            &lt;p class="card-text"&gt;Teaching children the importance of serving others through food drives, visiting nursing homes, and helping those in need.&lt;/p&gt;
                        &lt;/div&gt;
                    &lt;/div&gt;
                &lt;/div&gt;
                &lt;div class="col-lg-4"&gt;
                    &lt;div class="card activity-card h-100"&gt;
                        &lt;div class="card-body text-center"&gt;
                            &lt;i class="fas fa-praying-hands fa-3x text-info mb-3"&gt;&lt;/i&gt;
                            &lt;h5 class="card-title"&gt;Prayer Time&lt;/h5&gt;
                            &lt;p class="card-text"&gt;Dedicated time for children to learn about prayer, share prayer requests, and experience the power of talking to God.&lt;/p&gt;
                        &lt;/div&gt;
                    &lt;/div&gt;
                &lt;/div&gt;
                &lt;div class="col-lg-4"&gt;
                    &lt;div class="card activity-card h-100"&gt;
                        &lt;div class="card-body text-center"&gt;
                            &lt;i class="fas fa-palette fa-3x text-danger mb-3"&gt;&lt;/i&gt;
                            &lt;h5 class="card-title"&gt;Crafts &amp; Arts&lt;/h5&gt;
                            &lt;p class="card-text"&gt;Creative activities that reinforce Bible lessons and help children express their faith through art and crafts.&lt;/p&gt;
                        &lt;/div&gt;
                    &lt;/div&gt;
                &lt;/div&gt;
                &lt;div class="col-lg-4"&gt;
                    &lt;div class="card activity-card h-100"&gt;
                        &lt;div class="card-body text-center"&gt;
                            &lt;i class="fas fa-running fa-3x text-secondary mb-3"&gt;&lt;/i&gt;
                            &lt;h5 class="card-title"&gt;Games &amp; Sports&lt;/h5&gt;
                            &lt;p class="card-text"&gt;Physical activities and games that promote teamwork, healthy living, and having fun while learning about God.&lt;/p&gt;
                        &lt;/div&gt;
                    &lt;/div&gt;
                &lt;/div&gt;
            &lt;/div&gt;
        &lt;/div&gt;
    &lt;/section&gt;

    &lt;!-- Upcoming Events & News --&gt;
    &lt;section class="py-5 bg-light"&gt;
        &lt;div class="container"&gt;
            &lt;div class="row"&gt;
                &lt;!-- Upcoming Events --&gt;
                &lt;div class="col-lg-6 mb-4"&gt;
                    &lt;div class="card children-card"&gt;
                        &lt;div class="card-body"&gt;
                            &lt;h4 class="card-title mb-4"&gt;&lt;i class="fas fa-calendar-alt text-primary"&gt;&lt;/i&gt; Upcoming Children's Events&lt;/h4&gt;
                            &lt;?php if ($children_events-&gt;num_rows &gt; 0): ?&gt;
                                &lt;div class="list-group list-group-flush"&gt;
                                    &lt;?php while ($event = $children_events-&gt;fetch_assoc()): ?&gt;
                                        &lt;a href="events.php" class="list-group-item list-group-item-action"&gt;
                                            &lt;div class="d-flex w-100 justify-content-between"&gt;
                                                &lt;h6 class="mb-1"&gt;&lt;?php echo htmlspecialchars($event['title']); ?&gt;&lt;/h6&gt;
                                                &lt;small&gt;&lt;?php echo date('M j, Y', strtotime($event['event_date'])); ?&gt;&lt;/small&gt;
                                            &lt;/div&gt;
                                            &lt;p class="mb-1"&gt;&lt;?php echo htmlspecialchars(substr($event['description'], 0, 100)); ?&gt;...&lt;/p&gt;
                                        &lt;/a&gt;
                                    &lt;?php endwhile; ?&gt;
                                &lt;/div&gt;
                            &lt;?php else: ?&gt;
                                &lt;p class="text-muted"&gt;No upcoming children's events scheduled.&lt;/p&gt;
                            &lt;?php endif; ?&gt;
                        &lt;/div&gt;
                    &lt;/div&gt;
                &lt;/div&gt;

                &lt;!-- Latest News --&gt;
                &lt;div class="col-lg-6 mb-4"&gt;
                    &lt;div class="card children-card"&gt;
                        &lt;div class="card-body"&gt;
                            &lt;h4 class="card-title mb-4"&gt;&lt;i class="fas fa-newspaper text-success"&gt;&lt;/i&gt; Children's Ministry News&lt;/h4&gt;
                            &lt;?php if ($children_news-&gt;num_rows &gt; 0): ?&gt;
                                &lt;div class="row g-3"&gt;
                                    &lt;?php while ($news = $children_news-&gt;fetch_assoc()): ?&gt;
                                        &lt;div class="col-12"&gt;
                                            &lt;div class="card border-0 bg-white"&gt;
                                                &lt;div class="card-body p-0"&gt;
                                                    &lt;h6&gt;&lt;a href="news.php" class="text-decoration-none"&gt;&lt;?php echo htmlspecialchars($news['title']); ?&gt;&lt;/a&gt;&lt;/h6&gt;
                                                    &lt;p class="text-muted small mb-1"&gt;&lt;?php echo date('M j, Y', strtotime($news['created_at'])); ?&gt;&lt;/p&gt;
                                                    &lt;p class="mb-0"&gt;&lt;?php echo htmlspecialchars(substr($news['content'], 0, 120)); ?&gt;...&lt;/p&gt;
                                                &lt;/div&gt;
                                            &lt;/div&gt;
                                        &lt;/div&gt;
                                    &lt;?php endwhile; ?&gt;
                                &lt;/div&gt;
                            &lt;?php else: ?&gt;
                                &lt;p class="text-muted"&gt;No recent news from the children's ministry.&lt;/p&gt;
                            &lt;?php endif; ?&gt;
                        &lt;/div&gt;
                    &lt;/div&gt;
                &lt;/div&gt;
            &lt;/div&gt;
        &lt;/div&gt;
    &lt;/section&gt;

    &lt;!-- Call to Action --&gt;
    &lt;section class="py-5 bg-primary text-white"&gt;
        &lt;div class="container text-center"&gt;
            &lt;h2 class="mb-4"&gt;Get Your Children Involved!&lt;/h2&gt;
            &lt;p class="lead mb-4"&gt;We'd love to welcome your children to our ministry. Contact us to learn more about how they can join in the fun and grow in their faith.&lt;/p&gt;
            &lt;div class="row justify-content-center"&gt;
                &lt;div class="col-md-4 mb-3"&gt;
                    &lt;a href="contact.php" class="btn btn-light btn-lg w-100"&gt;
                        &lt;i class="fas fa-envelope"&gt;&lt;/i&gt; Contact Us
                    &lt;/a&gt;
                &lt;/div&gt;
                &lt;div class="col-md-4 mb-3"&gt;
                    &lt;a href="register.php" class="btn btn-outline-light btn-lg w-100"&gt;
                        &lt;i class="fas fa-user-plus"&gt;&lt;/i&gt; Register Family
                    &lt;/a&gt;
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