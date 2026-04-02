<?php
session_start();
require_once 'db.php';

// Get church information and leadership
$leaders = $db->query("SELECT u.first_name, u.last_name, u.email, u.avatar_url, u.role FROM users u WHERE u.is_active = 1 AND u.role IN ('admin', 'pastor') ORDER BY u.role");
$testimonials = $db->query("SELECT * FROM testimonials WHERE is_approved = 1 ORDER BY is_featured DESC, created_at DESC LIMIT 6");
$ministries_count = $db->selectOne("SELECT COUNT(*) as count FROM ministries WHERE is_active = 1")['count'];
$members_count = $db->selectOne("SELECT COUNT(*) as count FROM users WHERE is_active = 1 AND role = 'member'")['count'];
$events_count = $db->selectOne("SELECT COUNT(*) as count FROM events WHERE status = 'upcoming'")['count'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - Salem Dominion Ministries</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- AOS Animation -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700;900&family=Montserrat:wght@300;400;500;600;700&family=Dancing+Script:wght@700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #e74c3c;
            --accent-color: #f39c12;
            --light-color: #ecf0f1;
            --dark-color: #34495e;
            --gradient-primary: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --gradient-secondary: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            --gradient-church: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Montserrat', sans-serif;
            line-height: 1.6;
            color: var(--dark-color);
            overflow-x: hidden;
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: 'Playfair Display', serif;
            font-weight: 700;
        }

        /* Navigation */
        .navbar {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            box-shadow: 0 2px 20px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            padding: 1rem 0;
        }

        .navbar-brand {
            font-family: 'Dancing Script', cursive;
            font-size: 2rem;
            font-weight: 700;
            background: var(--gradient-church);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Hero Section */
        .hero-section {
            height: 60vh;
            background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)),
                        url('https://images.unsplash.com/photo-1506905925346-21bda4d32df4?ixlib=rb-4.0.3') center/cover;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: white;
            position: relative;
            overflow: hidden;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: var(--gradient-church);
            opacity: 0.3;
            animation: gradientShift 10s ease infinite;
        }

        @keyframes gradientShift {
            0%, 100% { opacity: 0.3; }
            50% { opacity: 0.5; }
        }

        .hero-content {
            position: relative;
            z-index: 2;
            max-width: 800px;
            padding: 0 20px;
        }

        /* Section Styles */
        .section {
            padding: 80px 0;
            position: relative;
        }

        .section-alt {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        }

        .section-title {
            font-size: 2.5rem;
            font-weight: 900;
            text-align: center;
            margin-bottom: 1rem;
            background: var(--gradient-church);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .section-subtitle {
            text-align: center;
            font-size: 1.1rem;
            color: var(--dark-color);
            margin-bottom: 3rem;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        /* Story Section */
        .story-content {
            background: white;
            border-radius: 20px;
            padding: 3rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            position: relative;
            overflow: hidden;
        }

        .story-content::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 5px;
            height: 100%;
            background: var(--gradient-church);
        }

        /* Stats Counter */
        .stats-section {
            background: var(--gradient-church);
            color: white;
            padding: 60px 0;
            position: relative;
            overflow: hidden;
        }

        .stats-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: -50%;
            width: 200%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
            animation: shimmer 3s infinite;
        }

        @keyframes shimmer {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }

        .stat-card {
            text-align: center;
            padding: 2rem;
        }

        .stat-number {
            font-size: 3rem;
            font-weight: 900;
            margin-bottom: 0.5rem;
            font-family: 'Playfair Display', serif;
        }

        .stat-label {
            font-size: 1.1rem;
            opacity: 0.9;
        }

        /* Leadership Team */
        .leader-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            text-align: center;
        }

        .leader-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
        }

        .leader-avatar {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            margin: 2rem auto;
            background: var(--gradient-primary);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 3rem;
            font-weight: 700;
        }

        .leader-info {
            padding: 0 2rem 2rem;
        }

        .leader-name {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            color: var(--primary-color);
        }

        .leader-role {
            color: var(--accent-color);
            font-weight: 600;
            margin-bottom: 1rem;
        }

        /* Values Section */
        .values-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
        }

        .value-card {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            border: 2px solid transparent;
            background-clip: padding-box;
        }

        .value-card:hover {
            border-color: var(--accent-color);
            transform: translateY(-5px);
        }

        .value-icon {
            font-size: 3rem;
            color: var(--accent-color);
            margin-bottom: 1rem;
        }

        .value-title {
            font-size: 1.3rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: var(--primary-color);
        }

        /* Testimonials */
        .testimonial-card {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            position: relative;
            margin-bottom: 2rem;
        }

        .testimonial-card::before {
            content: '"';
            position: absolute;
            top: 1rem;
            left: 1rem;
            font-size: 4rem;
            color: var(--accent-color);
            opacity: 0.3;
            font-family: 'Playfair Display', serif;
        }

        .testimonial-content {
            font-style: italic;
            margin-bottom: 1.5rem;
            padding-left: 2rem;
        }

        .testimonial-author {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .author-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: var(--gradient-primary);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
        }

        .author-info h6 {
            margin: 0;
            color: var(--primary-color);
            font-weight: 700;
        }

        .author-info small {
            color: var(--accent-color);
        }

        .testimonial-rating {
            color: #ffc107;
            margin-bottom: 1rem;
        }

        /* Call to Action */
        .cta-section {
            background: var(--gradient-secondary);
            color: white;
            padding: 80px 0;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .cta-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: -50%;
            width: 200%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
            animation: shimmer 3s infinite;
        }

        .btn-cta {
            background: white;
            color: var(--primary-color);
            padding: 15px 40px;
            font-size: 1.1rem;
            font-weight: 600;
            border: none;
            border-radius: 50px;
            margin: 0.5rem;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .btn-cta:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .hero-section {
                height: 50vh;
            }
            
            .section-title {
                font-size: 2rem;
            }
            
            .section {
                padding: 60px 0;
            }
            
            .story-content {
                padding: 2rem 1.5rem;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <i class="fas fa-church"></i> Salem Dominion Ministries
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto">
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
    <section class="hero-section">
        <div class="hero-content">
            <h1 class="display-4 fw-bold mb-4" data-aos="fade-up">About Salem Dominion Ministries</h1>
            <p class="lead mb-4" data-aos="fade-up" data-aos-delay="100">A community of faith, hope, and love serving God and humanity</p>
        </div>
    </section>

    <!-- Our Story -->
    <section class="section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-4" data-aos="fade-right">
                    <img src="https://images.unsplash.com/photo-1506905925346-21bda4d32df4?ixlib=rb-4.0.3" alt="Church Building" class="img-fluid rounded-3">
                </div>
                <div class="col-lg-6 mb-4" data-aos="fade-left">
                    <div class="story-content">
                        <h2 class="section-title text-start mb-4">Our Story</h2>
                        <p class="mb-3">Salem Dominion Ministries was founded with a vision to create a vibrant community where people can encounter God's love, grow in their faith, and make a positive impact in the world.</p>
                        <p class="mb-3">Over the years, we have grown from a small gathering to a thriving church family, touching countless lives through our various ministries, outreach programs, and community services.</p>
                        <p class="mb-3">Our mission is to lead people into a growing relationship with Jesus Christ, to equip them for their God-given purpose, and to send them out to transform their world with the love and power of the Gospel.</p>
                        <p>At Salem Dominion Ministries, we believe that everyone has a purpose and that together, we can make a difference in our community and beyond.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats-section">
        <div class="container">
            <div class="row">
                <div class="col-md-3 col-6 mb-4" data-aos="fade-up">
                    <div class="stat-card">
                        <div class="stat-number" data-count="<?php echo $ministries_count; ?>">0</div>
                        <div class="stat-label">Active Ministries</div>
                    </div>
                </div>
                <div class="col-md-3 col-6 mb-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="stat-card">
                        <div class="stat-number" data-count="<?php echo $members_count; ?>">0</div>
                        <div class="stat-label">Church Members</div>
                    </div>
                </div>
                <div class="col-md-3 col-6 mb-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="stat-card">
                        <div class="stat-number" data-count="<?php echo $events_count; ?>">0</div>
                        <div class="stat-label">Upcoming Events</div>
                    </div>
                </div>
                <div class="col-md-3 col-6 mb-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="stat-card">
                        <div class="stat-number" data-count="10">0</div>
                        <div class="stat-label">Years of Ministry</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Our Values -->
    <section class="section section-alt">
        <div class="container">
            <h2 class="section-title" data-aos="fade-up">Our Core Values</h2>
            <p class="section-subtitle" data-aos="fade-up" data-aos-delay="100">The principles that guide everything we do</p>
            
            <div class="values-grid">
                <div class="value-card" data-aos="fade-up" data-aos-delay="200">
                    <div class="value-icon">
                        <i class="fas fa-cross"></i>
                    </div>
                    <h3 class="value-title">Christ-Centered</h3>
                    <p>We keep Jesus at the center of all we do, following His teachings and example in every aspect of our ministry and personal lives.</p>
                </div>
                
                <div class="value-card" data-aos="fade-up" data-aos-delay="300">
                    <div class="value-icon">
                        <i class="fas fa-heart"></i>
                    </div>
                    <h3 class="value-title">Love & Compassion</h3>
                    <p>We demonstrate God's love through genuine care, compassion, and service to others, creating a welcoming environment for all.</p>
                </div>
                
                <div class="value-card" data-aos="fade-up" data-aos-delay="400">
                    <div class="value-icon">
                        <i class="fas fa-bible"></i>
                    </div>
                    <h3 class="value-title">Biblical Truth</h3>
                    <p>We uphold the authority of Scripture and teach its timeless truths with relevance and application to everyday life.</p>
                </div>
                
                <div class="value-card" data-aos="fade-up" data-aos-delay="500">
                    <div class="value-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <h3 class="value-title">Community</h3>
                    <p>We foster authentic relationships and build a strong, supportive community where people can belong, grow, and serve together.</p>
                </div>
                
                <div class="value-card" data-aos="fade-up" data-aos-delay="600">
                    <div class="value-icon">
                        <i class="fas fa-hands-helping"></i>
                    </div>
                    <h3 class="value-title">Service</h3>
                    <p>We are called to serve others sacrificially, using our gifts and resources to meet needs and make a positive impact.</p>
                </div>
                
                <div class="value-card" data-aos="fade-up" data-aos-delay="700">
                    <div class="value-icon">
                        <i class="fas fa-globe"></i>
                    </div>
                    <h3 class="value-title">Mission</h3>
                    <p>We are passionate about sharing the Gospel locally and globally, making disciples and advancing God's kingdom.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Leadership Team -->
    <section class="section">
        <div class="container">
            <h2 class="section-title" data-aos="fade-up">Meet Our Leadership</h2>
            <p class="section-subtitle" data-aos="fade-up" data-aos-delay="100">Dedicated servants committed to guiding and nurturing our church family</p>
            
            <div class="row">
                <?php while ($leader = $leaders->fetch_assoc()): ?>
                <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="leader-card">
                        <div class="leader-avatar">
                            <?php if ($leader['avatar_url']): ?>
                                <img src="<?php echo htmlspecialchars($leader['avatar_url']); ?>" alt="<?php echo htmlspecialchars($leader['first_name'] . ' ' . $leader['last_name']); ?>" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
                            <?php else: ?>
                                <?php echo strtoupper(substr($leader['first_name'], 0, 1) . substr($leader['last_name'], 0, 1)); ?>
                            <?php endif; ?>
                        </div>
                        <div class="leader-info">
                            <h5 class="leader-name"><?php echo htmlspecialchars($leader['first_name'] . ' ' . $leader['last_name']); ?></h5>
                            <p class="leader-role"><?php echo ucfirst($leader['role']); ?></p>
                            <p class="text-muted">Dedicated to serving our church family with wisdom, compassion, and spiritual guidance.</p>
                            <div class="mt-3">
                                <a href="mailto:<?php echo htmlspecialchars($leader['email']); ?>" class="btn btn-primary btn-sm">
                                    <i class="fas fa-envelope me-1"></i> Contact
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
        </div>
    </section>

    <!-- Testimonials -->
    <section class="section section-alt">
        <div class="container">
            <h2 class="section-title" data-aos="fade-up">What Our Members Say</h2>
            <p class="section-subtitle" data-aos="fade-up" data-aos-delay="100">Real stories from our church family</p>
            
            <div class="row">
                <?php while ($testimonial = $testimonials->fetch_assoc()): ?>
                <div class="col-lg-4 mb-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="testimonial-card">
                        <div class="testimonial-rating">
                            <?php for ($i = 0; $i < 5; $i++): ?>
                                <i class="fas fa-star"></i>
                            <?php endfor; ?>
                        </div>
                        <div class="testimonial-content">
                            "<?php echo htmlspecialchars($testimonial['testimonial']); ?>"
                        </div>
                        <div class="testimonial-author">
                            <div class="author-avatar">
                                <?php echo strtoupper(substr($testimonial['name'], 0, 1)); ?>
                            </div>
                            <div class="author-info">
                                <h6><?php echo htmlspecialchars($testimonial['name']); ?></h6>
                                <small><?php echo htmlspecialchars($testimonial['occupation'] ?? 'Church Member'); ?></small>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
        </div>
    </section>

    <!-- Call to Action -->
    <section class="cta-section">
        <div class="container">
            <h2 class="display-5 fw-bold mb-4" data-aos="fade-up">Join Our Church Family</h2>
            <p class="lead mb-4" data-aos="fade-up" data-aos-delay="100">Experience the joy of belonging to a community that loves God and loves people</p>
            <div data-aos="fade-up" data-aos-delay="200">
                <a href="contact.php" class="btn-cta">
                    <i class="fas fa-envelope me-2"></i> Get in Touch
                </a>
                <a href="ministries.php" class="btn-cta">
                    <i class="fas fa-users me-2"></i> Join a Ministry
                </a>
                <a href="events.php" class="btn-cta">
                    <i class="fas fa-calendar me-2"></i> Visit Us
                </a>
            </div>
        </div>
    </section>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        // Initialize AOS
        AOS.init({
            duration: 1000,
            once: true,
            offset: 100
        });

        // Counter animation
        function animateCounter() {
            const counters = document.querySelectorAll('.stat-number');
            
            counters.forEach(counter => {
                const target = parseInt(counter.getAttribute('data-count'));
                const increment = target / 100;
                let current = 0;
                
                const updateCounter = () => {
                    current += increment;
                    if (current < target) {
                        counter.textContent = Math.ceil(current);
                        requestAnimationFrame(updateCounter);
                    } else {
                        counter.textContent = target;
                    }
                };
                
                updateCounter();
            });
        }

        // Trigger counter animation when in viewport
        const observerOptions = {
            threshold: 0.5
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    animateCounter();
                    observer.unobserve(entry.target);
                }
            });
        }, observerOptions);

        const statsSection = document.querySelector('.stats-section');
        if (statsSection) {
            observer.observe(statsSection);
        }

        // Navbar scroll effect
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar');
            if (window.scrollY > 100) {
                navbar.style.padding = '0.5rem 0';
                navbar.style.boxShadow = '0 4px 30px rgba(0,0,0,0.1)';
            } else {
                navbar.style.padding = '1rem 0';
                navbar.style.boxShadow = '0 2px 20px rgba(0,0,0,0.1)';
            }
        });
    </script>
</body>
</html>
