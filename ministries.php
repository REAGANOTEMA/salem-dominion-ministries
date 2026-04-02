&lt;?php
session_start();
require_once 'db.php';

// Get all ministries
$ministries = $db-&gt;query("SELECT * FROM ministries ORDER BY name");
?&gt;
&lt;!DOCTYPE html&gt;
&lt;html lang="en"&gt;
&lt;head&gt;
    &lt;meta charset="UTF-8"&gt;
    &lt;meta name="viewport" content="width=device-width, initial-scale=1.0"&gt;
    &lt;title&gt;Ministries - Salem Dominion Ministries&lt;/title&gt;
    &lt;link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"&gt;
    &lt;link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet"&gt;
    &lt;style&gt;
        .ministries-hero {
            background: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)), url('https://images.unsplash.com/photo-1520637836862-4d197d17c23a?ixlib=rb-4.0.3');
            background-size: cover;
            background-position: center;
            color: white;
            padding: 80px 0;
        }
        .navbar-brand {
            font-weight: bold;
            font-size: 1.5rem;
        }
        .ministry-card {
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            border: none;
            border-radius: 15px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            height: 100%;
        }
        .ministry-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.2);
        }
        .ministry-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
        }
        .featured-ministry {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
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
                    &lt;li class="nav-item"&gt;&lt;a class="nav-link active" href="ministries.php"&gt;Ministries&lt;/a&gt;&lt;/li&gt;
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
    &lt;section class="ministries-hero"&gt;
        &lt;div class="container text-center"&gt;
            &lt;h1 class="display-4 fw-bold mb-4"&gt;Our Ministries&lt;/h1&gt;
            &lt;p class="lead mb-4"&gt;"Each of you should use whatever gift you have received to serve others, as faithful stewards of God's grace in its various forms." - 1 Peter 4:10&lt;/p&gt;
        &lt;/div&gt;
    &lt;/section&gt;

    &lt;!-- Ministries Overview --&gt;
    &lt;section class="py-5 bg-light"&gt;
        &lt;div class="container"&gt;
            &lt;div class="row"&gt;
                &lt;div class="col-lg-8 mx-auto text-center"&gt;
                    &lt;h2 class="mb-4"&gt;Serving God Through Service&lt;/h2&gt;
                    &lt;p class="lead mb-4"&gt;
                        At Salem Dominion Ministries, we believe that every member has unique gifts and talents that can be used
                        to serve God and build up His kingdom. Our various ministries provide opportunities for everyone to get
                        involved and make a difference in our church and community.
                    &lt;/p&gt;
                &lt;/div&gt;
            &lt;/div&gt;
        &lt;/div&gt;
    &lt;/section&gt;

    &lt;!-- Ministries Grid --&gt;
    &lt;section class="py-5"&gt;
        &lt;div class="container"&gt;
            &lt;div class="row g-4"&gt;
                &lt;!-- Featured Ministry: Children --&gt;
                &lt;div class="col-lg-6 mb-4"&gt;
                    &lt;div class="card ministry-card featured-ministry"&gt;
                        &lt;div class="card-body text-center p-5"&gt;
                            &lt;i class="fas fa-child ministry-icon"&gt;&lt;/i&gt;
                            &lt;h3 class="card-title mb-3"&gt;Children Ministry&lt;/h3&gt;
                            &lt;p class="card-text mb-4"&gt;
                                Nurturing young hearts with God's love through age-appropriate Bible teaching, fun activities,
                                songs, crafts, and community service projects.
                            &lt;/p&gt;
                            &lt;a href="children_ministry.php" class="btn btn-light btn-lg"&gt;
                                &lt;i class="fas fa-arrow-right"&gt;&lt;/i&gt; Learn More
                            &lt;/a&gt;
                        &lt;/div&gt;
                    &lt;/div&gt;
                &lt;/div&gt;

                &lt;!-- Featured Ministry: Worship --&gt;
                &lt;div class="col-lg-6 mb-4"&gt;
                    &lt;div class="card ministry-card featured-ministry"&gt;
                        &lt;div class="card-body text-center p-5"&gt;
                            &lt;i class="fas fa-music ministry-icon"&gt;&lt;/i&gt;
                            &lt;h3 class="card-title mb-3"&gt;Worship Ministry&lt;/h3&gt;
                            &lt;p class="card-text mb-4"&gt;
                                Leading our congregation in worship through music, song, and praise. Join our choir, worship team,
                                or instrumental groups to glorify God together.
                            &lt;/p&gt;
                            &lt;a href="contact.php" class="btn btn-light btn-lg"&gt;
                                &lt;i class="fas fa-envelope"&gt;&lt;/i&gt; Get Involved
                            &lt;/a&gt;
                        &lt;/div&gt;
                    &lt;/div&gt;
                &lt;/div&gt;

                &lt;!-- Other Ministries from Database --&gt;
                &lt;?php
                $ministry_icons = [
                    'youth' =&gt; 'fas fa-users',
                    'women' =&gt; 'fas fa-female',
                    'men' =&gt; 'fas fa-male',
                    'senior' =&gt; 'fas fa-user-friends',
                    'outreach' =&gt; 'fas fa-hand-holding-heart',
                    'missions' =&gt; 'fas fa-globe',
                    'prayer' =&gt; 'fas fa-praying-hands',
                    'hospitality' =&gt; 'fas fa-utensils',
                    'media' =&gt; 'fas fa-video',
                    'default' =&gt; 'fas fa-church'
                ];

                $ministry_colors = [
                    'youth' =&gt; 'primary',
                    'women' =&gt; 'danger',
                    'men' =&gt; 'info',
                    'senior' =&gt; 'warning',
                    'outreach' =&gt; 'success',
                    'missions' =&gt; 'secondary',
                    'prayer' =&gt; 'dark',
                    'hospitality' =&gt; 'primary',
                    'media' =&gt; 'info',
                    'default' =&gt; 'primary'
                ];

                while ($ministry = $ministries-&gt;fetch_assoc()):
                    $icon = $ministry_icons[$ministry['category']] ?? $ministry_icons['default'];
                    $color = $ministry_colors[$ministry['category']] ?? $ministry_colors['default'];
                ?&gt;
                &lt;div class="col-lg-4 col-md-6"&gt;
                    &lt;div class="card ministry-card h-100"&gt;
                        &lt;div class="card-body text-center p-4"&gt;
                            &lt;i class="&lt;?php echo $icon; ?&gt; ministry-icon text-&lt;?php echo $color; ?&gt;"&gt;&lt;/i&gt;
                            &lt;h5 class="card-title mb-3"&gt;&lt;?php echo htmlspecialchars($ministry['name']); ?&gt;&lt;/h5&gt;
                            &lt;p class="card-text"&gt;&lt;?php echo htmlspecialchars(substr($ministry['description'], 0, 120)); ?&gt;...&lt;/p&gt;
                            &lt;div class="mt-auto"&gt;
                                &lt;small class="text-muted d-block mb-2"&gt;
                                    &lt;i class="fas fa-user"&gt;&lt;/i&gt; &lt;?php echo htmlspecialchars($ministry['leader_name']); ?&gt;
                                &lt;/small&gt;
                                &lt;?php if ($ministry['meeting_time']): ?&gt;
                                    &lt;small class="text-muted d-block mb-3"&gt;
                                        &lt;i class="fas fa-clock"&gt;&lt;/i&gt; &lt;?php echo htmlspecialchars($ministry['meeting_time']); ?&gt;
                                    &lt;/small&gt;
                                &lt;?php endif; ?&gt;
                                &lt;a href="contact.php" class="btn btn-outline-&lt;?php echo $color; ?&gt; btn-sm"&gt;
                                    &lt;i class="fas fa-envelope"&gt;&lt;/i&gt; Contact
                                &lt;/a&gt;
                            &lt;/div&gt;
                        &lt;/div&gt;
                    &lt;/div&gt;
                &lt;/div&gt;
                &lt;?php endwhile; ?&gt;

                &lt;!-- Additional Ministries (if not in database) --&gt;
                &lt;div class="col-lg-4 col-md-6"&gt;
                    &lt;div class="card ministry-card h-100"&gt;
                        &lt;div class="card-body text-center p-4"&gt;
                            &lt;i class="fas fa-users ministry-icon text-success"&gt;&lt;/i&gt;
                            &lt;h5 class="card-title mb-3"&gt;Youth Ministry&lt;/h5&gt;
                            &lt;p class="card-text"&gt;Empowering teenagers to grow in faith, develop leadership skills, and serve their community through various activities and outreach programs.&lt;/p&gt;
                            &lt;a href="contact.php" class="btn btn-outline-success btn-sm"&gt;
                                &lt;i class="fas fa-envelope"&gt;&lt;/i&gt; Contact
                            &lt;/a&gt;
                        &lt;/div&gt;
                    &lt;/div&gt;
                &lt;/div&gt;

                &lt;div class="col-lg-4 col-md-6"&gt;
                    &lt;div class="card ministry-card h-100"&gt;
                        &lt;div class="card-body text-center p-4"&gt;
                            &lt;i class="fas fa-female ministry-icon text-danger"&gt;&lt;/i&gt;
                            &lt;h5 class="card-title mb-3"&gt;Women's Ministry&lt;/h5&gt;
                            &lt;p class="card-text"&gt;Supporting women in their spiritual journey through Bible study, fellowship, prayer groups, and community service opportunities.&lt;/p&gt;
                            &lt;a href="contact.php" class="btn btn-outline-danger btn-sm"&gt;
                                &lt;i class="fas fa-envelope"&gt;&lt;/i&gt; Contact
                            &lt;/a&gt;
                        &lt;/div&gt;
                    &lt;/div&gt;
                &lt;/div&gt;

                &lt;div class="col-lg-4 col-md-6"&gt;
                    &lt;div class="card ministry-card h-100"&gt;
                        &lt;div class="card-body text-center p-4"&gt;
                            &lt;i class="fas fa-male ministry-icon text-info"&gt;&lt;/i&gt;
                            &lt;h5 class="card-title mb-3"&gt;Men's Ministry&lt;/h5&gt;
                            &lt;p class="card-text"&gt;Building godly men through brotherhood, accountability, Bible study, and service projects that strengthen families and community.&lt;/p&gt;
                            &lt;a href="contact.php" class="btn btn-outline-info btn-sm"&gt;
                                &lt;i class="fas fa-envelope"&gt;&lt;/i&gt; Contact
                            &lt;/a&gt;
                        &lt;/div&gt;
                    &lt;/div&gt;
                &lt;/div&gt;

                &lt;div class="col-lg-4 col-md-6"&gt;
                    &lt;div class="card ministry-card h-100"&gt;
                        &lt;div class="card-body text-center p-4"&gt;
                            &lt;i class="fas fa-hand-holding-heart ministry-icon text-warning"&gt;&lt;/i&gt;
                            &lt;h5 class="card-title mb-3"&gt;Outreach Ministry&lt;/h5&gt;
                            &lt;p class="card-text"&gt;Reaching out to our community through food drives, homeless shelters, prison ministry, and other compassionate service programs.&lt;/p&gt;
                            &lt;a href="contact.php" class="btn btn-outline-warning btn-sm"&gt;
                                &lt;i class="fas fa-envelope"&gt;&lt;/i&gt; Contact
                            &lt;/a&gt;
                        &lt;/div&gt;
                    &lt;/div&gt;
                &lt;/div&gt;

                &lt;div class="col-lg-4 col-md-6"&gt;
                    &lt;div class="card ministry-card h-100"&gt;
                        &lt;div class="card-body text-center p-4"&gt;
                            &lt;i class="fas fa-praying-hands ministry-icon text-dark"&gt;&lt;/i&gt;
                            &lt;h5 class="card-title mb-3"&gt;Prayer Ministry&lt;/h5&gt;
                            &lt;p class="card-text"&gt;Dedicated to intercessory prayer, maintaining a prayer chain, and providing spiritual support for those in need of prayer.&lt;/p&gt;
                            &lt;a href="prayer_requests.php" class="btn btn-outline-dark btn-sm"&gt;
                                &lt;i class="fas fa-praying-hands"&gt;&lt;/i&gt; Submit Request
                            &lt;/a&gt;
                        &lt;/div&gt;
                    &lt;/div&gt;
                &lt;/div&gt;

                &lt;div class="col-lg-4 col-md-6"&gt;
                    &lt;div class="card ministry-card h-100"&gt;
                        &lt;div class="card-body text-center p-4"&gt;
                            &lt;i class="fas fa-utensils ministry-icon text-primary"&gt;&lt;/i&gt;
                            &lt;h5 class="card-title mb-3"&gt;Hospitality Ministry&lt;/h5&gt;
                            &lt;p class="card-text"&gt;Creating welcoming environments through greeting visitors, coordinating fellowship meals, and ensuring everyone feels at home.&lt;/p&gt;
                            &lt;a href="contact.php" class="btn btn-outline-primary btn-sm"&gt;
                                &lt;i class="fas fa-envelope"&gt;&lt;/i&gt; Contact
                            &lt;/a&gt;
                        &lt;/div&gt;
                    &lt;/div&gt;
                &lt;/div&gt;
            &lt;/div&gt;
        &lt;/div&gt;
    &lt;/section&gt;

    &lt;!-- Call to Action --&gt;
    &lt;section class="py-5 bg-primary text-white"&gt;
        &lt;div class="container text-center"&gt;
            &lt;h2 class="mb-4"&gt;Find Your Place to Serve&lt;/h2&gt;
            &lt;p class="lead mb-4"&gt;God has given each of us unique gifts and talents. Discover how you can use yours to serve Him and bless others.&lt;/p&gt;
            &lt;div class="row justify-content-center"&gt;
                &lt;div class="col-md-4 mb-3"&gt;
                    &lt;a href="contact.php" class="btn btn-light btn-lg w-100"&gt;
                        &lt;i class="fas fa-envelope"&gt;&lt;/i&gt; Get Involved
                    &lt;/a&gt;
                &lt;/div&gt;
                &lt;div class="col-md-4 mb-3"&gt;
                    &lt;a href="donate.php" class="btn btn-outline-light btn-lg w-100"&gt;
                        &lt;i class="fas fa-hand-holding-heart"&gt;&lt;/i&gt; Support Ministries
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