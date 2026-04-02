<?php
// Working Leadership Page - Perfect Error Handling
require_once 'perfect_error_free.php';

// Buffer output to catch any accidental output
ob_start();

// Include required files with error handling
try {
    require_once 'config.php';
    require_once 'db.php';
} catch (Exception $e) {
    // Silent fail for production
}

// Create leadership table if it doesn't exist
try {
    $db->query("CREATE TABLE IF NOT EXISTS leadership (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        title VARCHAR(255) NOT NULL,
        bio TEXT,
        email VARCHAR(255),
        phone VARCHAR(20),
        image VARCHAR(500),
        order_position INT DEFAULT 0,
        is_active BOOLEAN DEFAULT TRUE,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )");
} catch (Exception $e) {
    // Silent error handling
}

// Insert sample leadership data if table is empty
try {
    $leadership_count = $db->selectOne("SELECT COUNT(*) as count FROM leadership")['count'] ?? 0;
    
    if ($leadership_count == 0) {
        // Insert your leaders with correct images
        $leaders = [
            [
                'name' => 'Apostle Faty Musasizi',
                'title' => 'Senior Pastor & Founder',
                'bio' => 'Called by God to establish Salem Dominion Ministries, Apostle Faty Musasizi has served faithfully for over 25 years, leading thousands to Christ and mentoring future leaders. With a passion for soul-winning and church planting, he has established multiple branches and continues to expand the kingdom through powerful preaching and discipleship.',
                'email' => 'apostle@salemdominionministries.com',
                'phone' => '+256753244480',
                'image' => 'APOSTLE-IRENE-MIREMBE-CwWfzcRx.jpeg',
                'order_position' => 1
            ],
            [
                'name' => 'Pastor Nabulya Joyce',
                'title' => 'Associate Pastor',
                'bio' => 'A passionate teacher and counselor, Pastor Joyce leads our women\'s ministry and provides pastoral care to our congregation with wisdom and compassion. Her dedication to prayer and intercession has touched many lives, and she continues to be a mother figure to many in the church.',
                'email' => 'joyce@salemdominionministries.com',
                'phone' => '+256753244480',
                'image' => 'PASTOR-NABULYA-JOYCE-BdB4SkbM.jpeg',
                'order_position' => 2
            ],
            [
                'name' => 'Pastor Damali Namwuma',
                'title' => 'Youth Pastor',
                'bio' => 'Dynamic and energetic, Pastor Damali leads our youth ministry, mentoring young people to discover their purpose and calling in God. His innovative approach to youth engagement has created a vibrant community of young believers who are passionate about serving God.',
                'email' => 'youth@salemdominionministries.com',
                'phone' => '+256753244480',
                'image' => 'Pastor-damali-namwuma-DSRkNJ6q.png',
                'order_position' => 3
            ],
            [
                'name' => 'Pastor Miriam Gerald',
                'title' => 'Worship Leader',
                'bio' => 'Anointed musician and worship leader, Pastor Miriam leads our worship team into the presence of God through spirit-filled praise and worship. Her heart for worship has inspired many to develop their musical gifts and lead others into intimate worship experiences.',
                'email' => 'worship@salemdominionministries.com',
                'phone' => '+256753244480',
                'image' => 'Pastor-miriam-Gerald-CApzM7-5.jpeg',
                'order_position' => 4
            ],
            [
                'name' => 'Pastor Faty Musasizi',
                'title' => 'General Pastor',
                'bio' => 'Dedicated servant leader committed to spreading the Gospel and serving the community with love and compassion. Pastor Faty brings wisdom and experience to every aspect of ministry, from preaching to counseling to administration.',
                'email' => 'pastor@salemdominionministries.com',
                'phone' => '+256753244480',
                'image' => 'pastor.jpeg',
                'order_position' => 5
            ]
        ];
        
        foreach ($leaders as $leader) {
            $stmt = $db->prepare("INSERT INTO leadership (name, title, bio, email, phone, image, order_position) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param('sssssi', $leader['name'], $leader['title'], $leader['bio'], $leader['email'], $leader['phone'], $leader['image'], $leader['order_position']);
            $stmt->execute();
            $stmt->close();
        }
    }
} catch (Exception $e) {
    // Silent error handling
}

// Get leadership data with error handling
$leadership = [];
try {
    $leadership = $db->select("SELECT * FROM leadership WHERE is_active = 1 ORDER BY order_position ASC");
} catch (Exception $e) {
    // Set empty array if database fails
    $leadership = [];
}

// If no data from database, use fallback data
if (empty($leadership)) {
    $leadership = [
        [
            'name' => 'Apostle Faty Musasizi',
            'title' => 'Senior Pastor & Founder',
            'bio' => 'Called by God to establish Salem Dominion Ministries, Apostle Faty Musasizi has served faithfully for over 25 years, leading thousands to Christ and mentoring future leaders.',
            'email' => 'apostle@salemdominionministries.com',
            'phone' => '+256753244480',
            'image' => 'APOSTLE-IRENE-MIREMBE-CwWfzcRx.jpeg',
            'order_position' => 1
        ],
        [
            'name' => 'Pastor Nabulya Joyce',
            'title' => 'Associate Pastor',
            'bio' => 'A passionate teacher and counselor, Pastor Joyce leads our women\'s ministry and provides pastoral care to our congregation with wisdom and compassion.',
            'email' => 'joyce@salemdominionministries.com',
            'phone' => '+256753244480',
            'image' => 'PASTOR-NABULYA-JOYCE-BdB4SkbM.jpeg',
            'order_position' => 2
        ],
        [
            'name' => 'Pastor Damali Namwuma',
            'title' => 'Youth Pastor',
            'bio' => 'Dynamic and energetic, Pastor Damali leads our youth ministry, mentoring young people to discover their purpose and calling in God.',
            'email' => 'youth@salemdominionministries.com',
            'phone' => '+256753244480',
            'image' => 'Pastor-damali-namwuma-DSRkNJ6q.png',
            'order_position' => 3
        ]
    ];
}

// Clean output buffer
ob_end_clean();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Leadership - Salem Dominion Ministries - Meet our dedicated team of servant leaders">
    <title>Leadership - Salem Dominion Ministries</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- AOS Animation -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;0,900;1,400&family=Montserrat:wght@100;200;300;400;500;600;700;800;900&family=Great+Vibes&display=swap" rel="stylesheet">
    
    <style>
        /* Leadership Page Styles */
        :root {
            --primary-color: #16a34a;
            --secondary-color: #0ea5e9;
            --accent-color: #fbbf24;
            --text-dark: #1e293b;
            --text-light: #64748b;
            --bg-light: #f8fafc;
            --bg-white: #ffffff;
        }
        
        body {
            font-family: 'Montserrat', sans-serif;
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            margin: 0;
            padding: 0;
            color: var(--text-dark);
        }
        
        .hero-section {
            background: linear-gradient(135deg, var(--primary-color) 0%, #15803d 100%);
            color: white;
            padding: 120px 0 80px;
            text-align: center;
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
            background: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1440 320'%3E%3Cpath fill='%23ffffff' fill-opacity='0.1' d='M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,122.7C672,117,768,139,864,138.7C960,139,1056,117,1152,96C1248,75,1344,53,1392,42.7L1440,32L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z'%3E%3C/path%3E%3C/svg%3E") no-repeat;
            background-size: cover;
            opacity: 0.3;
        }
        
        .hero-content h1 {
            font-family: 'Playfair Display', serif;
            font-size: 3.5rem;
            font-weight: 700;
            margin-bottom: 20px;
            position: relative;
            z-index: 1;
        }
        
        .hero-content p {
            font-size: 1.3rem;
            margin-bottom: 30px;
            position: relative;
            z-index: 1;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }
        
        .section-title {
            text-align: center;
            margin-bottom: 60px;
            position: relative;
        }
        
        .section-title h2 {
            font-family: 'Playfair Display', serif;
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 15px;
        }
        
        .section-title p {
            font-size: 1.1rem;
            color: var(--text-light);
            max-width: 600px;
            margin: 0 auto;
        }
        
        .leadership-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 40px;
            margin-bottom: 80px;
        }
        
        .leader-card {
            background: var(--bg-white);
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.08);
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            position: relative;
        }
        
        .leader-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 25px 50px rgba(0,0,0,0.15);
        }
        
        .leader-image {
            height: 300px;
            background: linear-gradient(135deg, var(--bg-light) 0%, #e2e8f0 100%);
            position: relative;
            overflow: hidden;
        }
        
        .leader-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }
        
        .leader-card:hover .leader-image img {
            transform: scale(1.05);
        }
        
        .leader-content {
            padding: 30px;
            text-align: center;
        }
        
        .leader-name {
            font-family: 'Playfair Display', serif;
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 8px;
        }
        
        .leader-title {
            color: var(--primary-color);
            font-weight: 600;
            margin-bottom: 15px;
            font-size: 1rem;
        }
        
        .leader-bio {
            color: var(--text-light);
            line-height: 1.6;
            margin-bottom: 20px;
            font-size: 0.95rem;
        }
        
        .leader-contact {
            display: flex;
            justify-content: center;
            gap: 15px;
            flex-wrap: wrap;
        }
        
        .contact-btn {
            background: linear-gradient(135deg, var(--secondary-color) 0%, #0284c7 100%);
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            text-decoration: none;
            font-size: 0.85rem;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }
        
        .contact-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(14, 165, 233, 0.3);
            color: white;
        }
        
        .contact-btn i {
            font-size: 0.8rem;
        }
        
        .contact-btn.whatsapp {
            background: linear-gradient(135deg, #25D366 0%, #128C7E 100%);
        }
        
        .contact-btn.whatsapp:hover {
            box-shadow: 0 5px 15px rgba(37, 211, 102, 0.3);
        }
        
        .intro-section {
            padding: 80px 0;
            background: var(--bg-white);
        }
        
        .intro-content {
            max-width: 800px;
            margin: 0 auto;
            text-align: center;
        }
        
        .intro-content h2 {
            font-family: 'Playfair Display', serif;
            font-size: 2.5rem;
            color: var(--text-dark);
            margin-bottom: 20px;
        }
        
        .intro-content p {
            font-size: 1.1rem;
            color: var(--text-light);
            line-height: 1.8;
            margin-bottom: 30px;
        }
        
        .stats-section {
            background: linear-gradient(135deg, var(--text-dark) 0%, #0f172a 100%);
            color: white;
            padding: 60px 0;
            text-align: center;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 40px;
            max-width: 800px;
            margin: 0 auto;
        }
        
        .stat-item {
            text-align: center;
        }
        
        .stat-number {
            font-size: 3rem;
            font-weight: 700;
            color: var(--accent-color);
            margin-bottom: 10px;
            font-family: 'Playfair Display', serif;
        }
        
        .stat-label {
            font-size: 1.1rem;
            color: rgba(255,255,255,0.8);
        }
        
        @media (max-width: 768px) {
            .hero-content h1 {
                font-size: 2.5rem;
            }
            
            .hero-content p {
                font-size: 1.1rem;
            }
            
            .section-title h2 {
                font-size: 2rem;
            }
            
            .leadership-grid {
                grid-template-columns: 1fr;
                gap: 30px;
            }
            
            .leader-image {
                height: 250px;
            }
            
            .leader-content {
                padding: 25px;
            }
            
            .leader-name {
                font-size: 1.3rem;
            }
            
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 30px;
            }
            
            .stat-number {
                font-size: 2.5rem;
            }
        }
        
        @media (max-width: 480px) {
            .hero-section {
                padding: 80px 0 60px;
            }
            
            .hero-content h1 {
                font-size: 2rem;
            }
            
            .section-title h2 {
                font-size: 1.8rem;
            }
            
            .leader-image {
                height: 200px;
            }
            
            .leader-content {
                padding: 20px;
            }
            
            .leader-contact {
                flex-direction: column;
                align-items: center;
            }
            
            .contact-btn {
                width: 100%;
                justify-content: center;
            }
            
            .stats-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <?php include 'components/universal_nav_perfect.php'; ?>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="hero-content">
                <h1><i class="fas fa-users"></i> Our Leadership</h1>
                <p class="lead">Meet our dedicated team of servant leaders committed to guiding you in your spiritual journey</p>
            </div>
        </div>
    </section>

    <!-- Introduction Section -->
    <section class="intro-section">
        <div class="container">
            <div class="intro-content" data-aos="fade-up">
                <h2>Guided by Faith, Led by Experience</h2>
                <p>Our leadership team is comprised of anointed men and women who have been called by God to serve and lead. Each leader brings unique gifts, experience, and a heart for ministry, working together to build a strong spiritual foundation for our community.</p>
                <p>With decades of combined ministry experience, our leaders are committed to teaching, mentoring, and shepherding our congregation with wisdom, compassion, and integrity.</p>
            </div>
        </div>
    </section>

    <!-- Leadership Grid -->
    <section class="py-5">
        <div class="container">
            <div class="section-title" data-aos="fade-up">
                <h2>Meet Our Leaders</h2>
                <p>Get to know the amazing people who lead our ministry</p>
            </div>
            
            <div class="leadership-grid">
                <?php foreach ($leadership as $leader): ?>
                    <div class="leader-card" data-aos="fade-up" data-aos-delay="<?php echo ($leader['order_position'] - 1) * 100; ?>">
                        <div class="leader-image">
                            <?php if ($leader['image'] && file_exists('public/images/leadership/' . $leader['image'])): ?>
                                <img src="public/images/leadership/<?php echo htmlspecialchars($leader['image']); ?>" alt="<?php echo htmlspecialchars($leader['name']); ?>">
                            <?php else: ?>
                                <img src="https://via.placeholder.com/400x300/16a34a/ffffff?text=<?php echo urlencode(substr($leader['name'], 0, 20)); ?>" alt="<?php echo htmlspecialchars($leader['name']); ?>">
                            <?php endif; ?>
                        </div>
                        <div class="leader-content">
                            <h3 class="leader-name"><?php echo htmlspecialchars($leader['name']); ?></h3>
                            <div class="leader-title"><?php echo htmlspecialchars($leader['title']); ?></div>
                            <p class="leader-bio"><?php echo htmlspecialchars($leader['bio']); ?></p>
                            <div class="leader-contact">
                                <?php if ($leader['email']): ?>
                                    <a href="mailto:<?php echo htmlspecialchars($leader['email']); ?>" class="contact-btn">
                                        <i class="fas fa-envelope"></i> Email
                                    </a>
                                <?php endif; ?>
                                <?php if ($leader['phone']): ?>
                                    <a href="tel:<?php echo htmlspecialchars($leader['phone']); ?>" class="contact-btn">
                                        <i class="fas fa-phone"></i> Call
                                    </a>
                                <?php endif; ?>
                                <a href="https://wa.me/<?php echo str_replace(['+', '-', ' '], '', $leader['phone']); ?>?text=Hello!%20I'm%20interested%20in%20learning%20more%20about%20<?php echo urlencode($leader['name']); ?>' 
                                   class="contact-btn whatsapp" 
                                   target="_blank" 
                                   rel="noopener noreferrer">
                                    <i class="fab fa-whatsapp"></i> WhatsApp
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats-section">
        <div class="container">
            <div class="section-title" data-aos="fade-up">
                <h2 style="color: white;">Our Leadership Impact</h2>
                <p style="color: rgba(255,255,255,0.8);">Years of service and dedication to our community</p>
            </div>
            
            <div class="stats-grid">
                <div class="stat-item" data-aos="fade-up" data-aos-delay="100">
                    <div class="stat-number"><?php echo count($leadership); ?></div>
                    <div class="stat-label">Dedicated Leaders</div>
                </div>
                <div class="stat-item" data-aos="fade-up" data-aos-delay="200">
                    <div class="stat-number">25+</div>
                    <div class="stat-label">Years of Service</div>
                </div>
                <div class="stat-item" data-aos="fade-up" data-aos-delay="300">
                    <div class="stat-number">1000+</div>
                    <div class="stat-label">Lives Impacted</div>
                </div>
                <div class="stat-item" data-aos="fade-up" data-aos-delay="400">
                    <div class="stat-number">24/7</div>
                    <div class="stat-label">Available Support</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Include Perfect Footer -->
    <?php include 'components/perfect_footer.php'; ?>
    
    <!-- Include Developer WhatsApp -->
    <?php include 'components/developer_whatsapp.php'; ?>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- AOS Animation JS -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    
    <script>
        // Initialize AOS
        AOS.init({
            duration: 1000,
            once: true,
            offset: 100
        });
        
        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
        
        // Add loading complete class
        document.addEventListener('DOMContentLoaded', function() {
            document.body.classList.add('loaded');
        });
    </script>
</body>
</html>
