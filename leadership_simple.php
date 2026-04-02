<?php
// Simple Working Leadership Page - No Blank Page Issue
error_reporting(0);
ini_set('display_errors', 0);

// Leadership data
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
        'email' => 'joyce@saleldominionministries.com',
        'phone' => '+256753244480',
        'image' => 'PASTOR-NABULYA-JOYCE-BdB4SkbM.jpeg',
        'order_position' => 2
    ],
    [
        'name' => 'Pastor Damali Namwuma',
        'title' => 'Youth Pastor',
        'bio' => 'Dynamic and energetic, Pastor Damali leads our youth ministry, mentoring young people to discover their purpose and calling in God.',
        'email' => 'youth@saleldominionministries.com',
        'phone' => '+256753244480',
        'image' => 'Pastor-damali-namwuma-DSRkNJ6q.png',
        'order_position' => 3
    ],
    [
        'name' => 'Pastor Miriam Gerald',
        'title' => 'Worship Leader',
        'bio' => 'Anointed musician and worship leader, Pastor Miriam leads our worship team into the presence of God through spirit-filled praise and worship.',
        'email' => 'worship@saleldominionministries.com',
        'phone' => '+256753244480',
        'image' => 'Pastor-miriam-Gerald-CApzM7-5.jpeg',
        'order_position' => 4
    ],
    [
        'name' => 'Pastor Faty Musasizi',
        'title' => 'General Pastor',
        'bio' => 'Dedicated servant leader committed to spreading the Gospel and serving the community with love and compassion.',
        'email' => 'pastor@saleldominionministries.com',
        'phone' => '+256753244480',
        'image' => 'pastor.jpeg',
        'order_position' => 5
    ]
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leadership - Salem Dominion Ministries</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            margin: 0;
            padding: 0;
            color: #1e293b;
        }
        
        .hero-section {
            background: linear-gradient(135deg, #16a34a 0%, #15803d 100%);
            color: white;
            padding: 80px 0 60px;
            text-align: center;
            position: relative;
        }
        
        .hero-content h1 {
            font-size: 2.5rem;
            font-weight: bold;
            margin-bottom: 15px;
        }
        
        .hero-content p {
            font-size: 1.1rem;
            margin-bottom: 25px;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }
        
        .section-title {
            text-align: center;
            margin-bottom: 50px;
        }
        
        .section-title h2 {
            font-size: 2rem;
            font-weight: bold;
            color: #1e293b;
            margin-bottom: 10px;
        }
        
        .section-title p {
            font-size: 1rem;
            color: #64748b;
        }
        
        .leadership-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 30px;
            margin-bottom: 50px;
        }
        
        .leader-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.08);
            overflow: hidden;
            transition: transform 0.3s ease;
        }
        
        .leader-card:hover {
            transform: translateY(-5px);
        }
        
        .leader-image {
            height: 250px;
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            position: relative;
            overflow: hidden;
        }
        
        .leader-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .leader-content {
            padding: 25px;
            text-align: center;
        }
        
        .leader-name {
            font-size: 1.3rem;
            font-weight: bold;
            color: #1e293b;
            margin-bottom: 8px;
        }
        
        .leader-title {
            color: #16a34a;
            font-weight: 600;
            margin-bottom: 15px;
        }
        
        .leader-bio {
            color: #64748b;
            line-height: 1.5;
            margin-bottom: 20px;
            font-size: 0.9rem;
        }
        
        .leader-contact {
            display: flex;
            justify-content: center;
            gap: 10px;
            flex-wrap: wrap;
        }
        
        .contact-btn {
            background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%);
            color: white;
            padding: 8px 15px;
            border-radius: 20px;
            text-decoration: none;
            font-size: 0.8rem;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }
        
        .contact-btn:hover {
            transform: translateY(-2px);
            color: white;
        }
        
        .contact-btn.whatsapp {
            background: linear-gradient(135deg, #25D366 0%, #128C7E 100%);
        }
        
        .intro-section {
            padding: 60px 0;
            background: white;
        }
        
        .intro-content {
            max-width: 800px;
            margin: 0 auto;
            text-align: center;
        }
        
        .intro-content h2 {
            font-size: 2rem;
            color: #1e293b;
            margin-bottom: 15px;
        }
        
        .intro-content p {
            font-size: 1rem;
            color: #64748b;
            line-height: 1.6;
            margin-bottom: 20px;
        }
        
        .stats-section {
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
            color: white;
            padding: 50px 0;
            text-align: center;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 30px;
            max-width: 600px;
            margin: 0 auto;
        }
        
        .stat-item {
            text-align: center;
        }
        
        .stat-number {
            font-size: 2.5rem;
            font-weight: bold;
            color: #fbbf24;
            margin-bottom: 8px;
        }
        
        .stat-label {
            font-size: 0.9rem;
            color: rgba(255,255,255,0.8);
        }
        
        @media (max-width: 768px) {
            .hero-content h1 {
                font-size: 2rem;
            }
            
            .leadership-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }
            
            .leader-image {
                height: 200px;
            }
            
            .leader-content {
                padding: 20px;
            }
            
            .leader-name {
                font-size: 1.1rem;
            }
            
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 20px;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <?php 
    try {
        include 'components/universal_nav_perfect.php';
    } catch (Exception $e) {
        // Fallback navigation if include fails
        echo '<nav style="background: #1e293b; padding: 15px 0;">
                <div style="max-width: 1200px; margin: 0 auto; display: flex; justify-content: space-between; align-items: center; padding: 0 20px;">
                    <div style="color: white; font-size: 1.2rem; font-weight: bold;">
                        <i class="fas fa-church"></i> Salem Dominion Ministries
                    </div>
                    <div>
                        <a href="index.php" style="color: white; text-decoration: none; margin: 0 15px;">Home</a>
                        <a href="leadership.php" style="color: white; text-decoration: none; margin: 0 15px;">Leadership</a>
                        <a href="contact.php" style="color: white; text-decoration: none; margin: 0 15px;">Contact</a>
                    </div>
                </div>
            </nav>';
    }
    ?>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="hero-content">
                <h1><i class="fas fa-users"></i> Our Leadership</h1>
                <p>Meet our dedicated team of servant leaders committed to guiding you in your spiritual journey</p>
            </div>
        </div>
    </section>

    <!-- Introduction Section -->
    <section class="intro-section">
        <div class="container">
            <div class="intro-content">
                <h2>Guided by Faith, Led by Experience</h2>
                <p>Our leadership team is comprised of anointed men and women who have been called by God to serve and lead. Each leader brings unique gifts, experience, and a heart for ministry, working together to build a strong spiritual foundation for our community.</p>
                <p>With decades of combined ministry experience, our leaders are committed to teaching, mentoring, and shepherding our congregation with wisdom, compassion, and integrity.</p>
            </div>
        </div>
    </section>

    <!-- Leadership Grid -->
    <section class="py-5">
        <div class="container">
            <div class="section-title">
                <h2>Meet Our Leaders</h2>
                <p>Get to know the amazing people who lead our ministry</p>
            </div>
            
            <div class="leadership-grid">
                <?php foreach ($leadership as $leader): ?>
                    <div class="leader-card">
                        <div class="leader-image">
                            <?php 
                            $imagePath = 'public/images/leadership/' . $leader['image'];
                            if ($leader['image'] && file_exists($imagePath)): 
                            ?>
                                <img src="<?php echo htmlspecialchars($imagePath); ?>" alt="<?php echo htmlspecialchars($leader['name']); ?>">
                            <?php else: ?>
                                <img src="https://via.placeholder.com/400x250/16a34a/ffffff?text=<?php echo urlencode(substr($leader['name'], 0, 15)); ?>" alt="<?php echo htmlspecialchars($leader['name']); ?>">
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
                                <a href="https://wa.me/<?php echo str_replace(['+', '-', ' '], '', $leader['phone']); ?>?text=Hello!%20I'm%20interested%20in%20learning%20more%20about%20<?php echo urlencode($leader['name']); ?>" 
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
            <div class="section-title">
                <h2 style="color: white;">Our Leadership Impact</h2>
                <p style="color: rgba(255,255,255,0.8);">Years of service and dedication to our community</p>
            </div>
            
            <div class="stats-grid">
                <div class="stat-item">
                    <div class="stat-number"><?php echo count($leadership); ?></div>
                    <div class="stat-label">Dedicated Leaders</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">25+</div>
                    <div class="stat-label">Years of Service</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">1000+</div>
                    <div class="stat-label">Lives Impacted</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">24/7</div>
                    <div class="stat-label">Available Support</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <?php 
    try {
        include 'components/perfect_footer.php';
    } catch (Exception $e) {
        // Fallback footer if include fails
        echo '<footer style="background: #1e293b; color: white; padding: 30px 0; text-align: center;">
                <div style="max-width: 1200px; margin: 0 auto; padding: 0 20px;">
                    <p>&copy; ' . date('Y') . ' Salem Dominion Ministries. All rights reserved.</p>
                    <p><i class="fas fa-phone"></i> +256 753 244480 | <i class="fas fa-envelope"></i> visit@saleldominionministries.com</p>
                </div>
            </footer>';
    }
    ?>
    
    <!-- Developer WhatsApp -->
    <?php 
    try {
        include 'components/developer_whatsapp.php';
    } catch (Exception $e) {
        // Fallback WhatsApp button if include fails
        echo '<a href="https://wa.me/256753244480?text=Hello!%20I%20need%20help%20with%20Salem%20Dominion%20Ministries%20website." 
                   style="position: fixed; bottom: 25px; left: 25px; width: 50px; height: 50px; background: #25D366; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; text-decoration: none; font-size: 20px; box-shadow: 0 5px 15px rgba(37, 211, 102, 0.3); z-index: 1000;"
                   target="_blank" 
                   rel="noopener noreferrer">
                    <i class="fab fa-whatsapp"></i>
                </a>';
    }
    ?>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
