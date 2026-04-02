<?php
// Ultimate Perfect Footer System
require_once 'config.php';
require_once 'db.php';

// Get church information and statistics
$church_info = [
    'name' => 'Salem Dominion Ministries',
    'address' => '123 Church Street, City, State',
    'phone' => '+256 753 244480',
    'email' => 'visit@salemdominionministries.com'
];

try {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME, DB_PORT);
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }
    
    // Get statistics
    $total_members = $conn->query("SELECT COUNT(*) as count FROM users WHERE is_active = 1")->fetch_assoc()['count'];
    $total_events = $conn->query("SELECT COUNT(*) as count FROM events WHERE status = 'upcoming'")->fetch_assoc()['count'];
    $total_gallery = $conn->query("SELECT COUNT(*) as count FROM gallery WHERE status = 'published'")->fetch_assoc()['count'];
    
    // Get upcoming events
    $upcoming_events = $conn->query("SELECT title, event_date, location FROM events WHERE status = 'upcoming' ORDER BY event_date ASC LIMIT 3");
    
    // Get recent gallery items
    $recent_gallery = $conn->query("SELECT title, file_url FROM gallery WHERE status = 'published' ORDER BY created_at DESC LIMIT 3");
    
    $conn->close();
    
} catch (Exception $e) {
    $total_members = 0;
    $total_events = 0;
    $total_gallery = 0;
    $upcoming_events = [];
    $recent_gallery = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfect Footer - Salem Dominion Ministries</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            min-height: 100vh;
        }
        
        /* Perfect Footer Styles */
        .ultimate-footer {
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
            color: white;
            position: relative;
            overflow: hidden;
        }
        
        .ultimate-footer::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #16a34a 0%, #0ea5e9 50%, #dc2626 100%);
        }
        
        .footer-wave {
            position: absolute;
            top: -2px;
            left: 0;
            width: 100%;
            height: 60px;
            background: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1440 320'%3E%3Cpath fill='%231e293b' fill-opacity='1' d='M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,122.7C672,117,768,139,864,138.7C960,139,1056,117,1152,96C1248,75,1344,53,1392,42.7L1440,32L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z'%3E%3C/path%3E%3C/svg%3E") no-repeat;
            background-size: cover;
        }
        
        .footer-content {
            padding: 80px 0 40px;
            position: relative;
            z-index: 1;
        }
        
        .footer-section {
            margin-bottom: 40px;
        }
        
        .footer-logo {
            font-size: 2.5rem;
            color: #16a34a;
            margin-bottom: 20px;
            display: inline-block;
        }
        
        .footer-description {
            color: #94a3b8;
            line-height: 1.6;
            margin-bottom: 30px;
        }
        
        .footer-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: white;
            margin-bottom: 25px;
            position: relative;
            padding-bottom: 10px;
        }
        
        .footer-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 50px;
            height: 3px;
            background: linear-gradient(90deg, #16a34a 0%, #0ea5e9 100%);
            border-radius: 2px;
        }
        
        .footer-links {
            list-style: none;
            padding: 0;
        }
        
        .footer-links li {
            margin-bottom: 12px;
        }
        
        .footer-links a {
            color: #94a3b8;
            text-decoration: none;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
        }
        
        .footer-links a:hover {
            color: #16a34a;
            transform: translateX(5px);
        }
        
        .footer-links a i {
            width: 20px;
            margin-right: 10px;
            color: #16a34a;
        }
        
        .contact-item {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
            color: #94a3b8;
        }
        
        .contact-item i {
            width: 40px;
            height: 40px;
            background: rgba(22, 163, 74, 0.1);
            color: #16a34a;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            font-size: 1rem;
        }
        
        .social-links {
            display: flex;
            gap: 15px;
            margin-top: 25px;
        }
        
        .social-links a {
            width: 45px;
            height: 45px;
            background: rgba(22, 163, 74, 0.1);
            color: #16a34a;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            transition: all 0.3s ease;
            font-size: 1.2rem;
        }
        
        .social-links a:hover {
            background: #16a34a;
            color: white;
            transform: translateY(-3px);
        }
        
        .event-item {
            background: rgba(255, 255, 255, 0.05);
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 15px;
            border-left: 3px solid #16a34a;
        }
        
        .event-title {
            font-weight: 600;
            color: white;
            margin-bottom: 5px;
        }
        
        .event-details {
            font-size: 0.9rem;
            color: #94a3b8;
        }
        
        .gallery-preview {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
        }
        
        .gallery-thumb {
            aspect-ratio: 1;
            border-radius: 8px;
            overflow: hidden;
            position: relative;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .gallery-thumb:hover {
            transform: scale(1.05);
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        }
        
        .gallery-thumb img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .gallery-thumb::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.5);
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        .gallery-thumb:hover::after {
            opacity: 1;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
            margin-top: 30px;
        }
        
        .stat-item {
            text-align: center;
            padding: 15px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 10px;
        }
        
        .stat-number {
            font-size: 1.8rem;
            font-weight: bold;
            color: #16a34a;
            margin-bottom: 5px;
        }
        
        .stat-label {
            font-size: 0.9rem;
            color: #94a3b8;
        }
        
        .footer-bottom {
            background: #0f172a;
            padding: 30px 0;
            text-align: center;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .footer-bottom-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 20px;
        }
        
        .footer-copyright {
            color: #94a3b8;
        }
        
        .footer-bottom-links {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
        }
        
        .footer-bottom-links a {
            color: #94a3b8;
            text-decoration: none;
            transition: color 0.3s ease;
            font-size: 0.9rem;
        }
        
        .footer-bottom-links a:hover {
            color: #16a34a;
        }
        
        /* Back to Top Button */
        .back-to-top {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, #16a34a 0%, #0ea5e9 100%);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            font-size: 1.2rem;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
            z-index: 1000;
            box-shadow: 0 5px 15px rgba(22, 163, 74, 0.3);
        }
        
        .back-to-top.show {
            opacity: 1;
            visibility: visible;
        }
        
        .back-to-top:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(22, 163, 74, 0.4);
        }
        
        /* Mobile Responsive */
        @media (max-width: 768px) {
            .footer-content {
                padding: 60px 0 30px;
            }
            
            .footer-section {
                margin-bottom: 30px;
            }
            
            .footer-logo {
                font-size: 2rem;
            }
            
            .stats-grid {
                grid-template-columns: repeat(3, 1fr);
                gap: 10px;
            }
            
            .stat-number {
                font-size: 1.5rem;
            }
            
            .gallery-preview {
                grid-template-columns: repeat(3, 1fr);
                gap: 8px;
            }
            
            .footer-bottom-content {
                flex-direction: column;
                text-align: center;
            }
            
            .footer-bottom-links {
                justify-content: center;
            }
            
            .back-to-top {
                bottom: 20px;
                right: 20px;
                width: 45px;
                height: 45px;
            }
        }
        
        @media (max-width: 576px) {
            .footer-content {
                padding: 40px 0 20px;
            }
            
            .stats-grid {
                grid-template-columns: 1fr;
                gap: 10px;
            }
            
            .gallery-preview {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .social-links {
                justify-content: center;
            }
            
            .contact-item {
                flex-direction: column;
                text-align: center;
            }
            
            .contact-item i {
                margin-right: 0;
                margin-bottom: 10px;
            }
        }
        
        /* Tablet Responsive */
        @media (min-width: 769px) and (max-width: 1024px) {
            .gallery-preview {
                grid-template-columns: repeat(3, 1fr);
            }
            
            .stats-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }
        
        /* Large Desktop */
        @media (min-width: 1200px) {
            .footer-content {
                padding: 100px 0 50px;
            }
            
            .gallery-preview {
                grid-template-columns: repeat(4, 1fr);
            }
        }
    </style>
</head>
<body>
    <!-- Demo Content -->
    <div style="min-height: 100vh; padding: 50px 20px; text-align: center;">
        <h1 style="color: #1e293b; margin-bottom: 20px;">🎯 Perfect Footer Demonstration</h1>
        <p style="color: #64748b; font-size: 1.1rem;">Resize your browser to see the responsive footer in action!</p>
        <div style="margin-top: 50px;">
            <p style="color: #94a3b8;">Scroll down to see the perfect footer...</p>
            <i class="fas fa-arrow-down fa-2x" style="color: #16a34a; animation: bounce 2s infinite;"></i>
        </div>
    </div>

    <!-- Ultimate Perfect Footer -->
    <footer class="ultimate-footer">
        <div class="footer-wave"></div>
        
        <div class="container">
            <div class="footer-content">
                <div class="row">
                    <!-- Church Info Section -->
                    <div class="col-lg-4 col-md-6">
                        <div class="footer-section">
                            <div class="footer-logo">
                                <i class="fas fa-church"></i>
                            </div>
                            <h3 class="footer-title">Salem Dominion Ministries</h3>
                            <p class="footer-description">
                                Welcome to our spiritual family. We are a community dedicated to spreading God's love, 
                                growing in faith, and serving our community with compassion and grace.
                            </p>
                            
                            <!-- Church Statistics -->
                            <div class="stats-grid">
                                <div class="stat-item">
                                    <div class="stat-number"><?php echo $total_members; ?>+</div>
                                    <div class="stat-label">Members</div>
                                </div>
                                <div class="stat-item">
                                    <div class="stat-number"><?php echo $total_events; ?></div>
                                    <div class="stat-label">Events</div>
                                </div>
                                <div class="stat-item">
                                    <div class="stat-number"><?php echo $total_gallery; ?>+</div>
                                    <div class="stat-label">Gallery</div>
                                </div>
                            </div>
                            
                            <!-- Social Media -->
                            <div class="social-links">
                                <a href="#" title="Facebook">
                                    <i class="fab fa-facebook-f"></i>
                                </a>
                                <a href="#" title="Twitter">
                                    <i class="fab fa-twitter"></i>
                                </a>
                                <a href="#" title="Instagram">
                                    <i class="fab fa-instagram"></i>
                                </a>
                                <a href="#" title="YouTube">
                                    <i class="fab fa-youtube"></i>
                                </a>
                                <a href="#" title="WhatsApp">
                                    <i class="fab fa-whatsapp"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Quick Links Section -->
                    <div class="col-lg-2 col-md-6">
                        <div class="footer-section">
                            <h4 class="footer-title">Quick Links</h4>
                            <ul class="footer-links">
                                <li><a href="#"><i class="fas fa-home"></i> Home</a></li>
                                <li><a href="#"><i class="fas fa-church"></i> About Us</a></li>
                                <li><a href="#"><i class="fas fa-calendar"></i> Events</a></li>
                                <li><a href="#"><i class="fas fa-images"></i> Gallery</a></li>
                                <li><a href="#"><i class="fas fa-bible"></i> Sermons</a></li>
                                <li><a href="#"><i class="fas fa-pray"></i> Prayer Requests</a></li>
                                <li><a href="#"><i class="fas fa-donate"></i> Give</a></li>
                                <li><a href="#"><i class="fas fa-users"></i> Ministries</a></li>
                            </ul>
                        </div>
                    </div>
                    
                    <!-- Upcoming Events Section -->
                    <div class="col-lg-3 col-md-6">
                        <div class="footer-section">
                            <h4 class="footer-title">Upcoming Events</h4>
                            <?php if (!empty($upcoming_events)): ?>
                                <?php foreach ($upcoming_events as $event): ?>
                                    <div class="event-item">
                                        <div class="event-title"><?php echo htmlspecialchars($event['title']); ?></div>
                                        <div class="event-details">
                                            <i class="fas fa-calendar"></i> <?php echo date('M j, Y', strtotime($event['event_date'])); ?><br>
                                            <i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($event['location']); ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="event-item">
                                    <div class="event-title">Sunday Service</div>
                                    <div class="event-details">
                                        <i class="fas fa-calendar"></i> Every Sunday<br>
                                        <i class="fas fa-map-marker-alt"></i> Main Sanctuary
                                    </div>
                                </div>
                                <div class="event-item">
                                    <div class="event-title">Bible Study</div>
                                    <div class="event-details">
                                        <i class="fas fa-calendar"></i> Wednesday 6PM<br>
                                        <i class="fas fa-map-marker-alt"></i> Fellowship Hall
                                    </div>
                                </div>
                                <div class="event-item">
                                    <div class="event-title">Youth Fellowship</div>
                                    <div class="event-details">
                                        <i class="fas fa-calendar"></i> Friday 5PM<br>
                                        <i class="fas fa-map-marker-alt"></i> Youth Center
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <!-- Contact & Gallery Section -->
                    <div class="col-lg-3 col-md-6">
                        <div class="footer-section">
                            <h4 class="footer-title">Contact & Gallery</h4>
                            
                            <!-- Contact Info -->
                            <div class="contact-item">
                                <i class="fas fa-map-marker-alt"></i>
                                <div>
                                    <strong>Address</strong><br>
                                    <?php echo htmlspecialchars($church_info['address']); ?>
                                </div>
                            </div>
                            
                            <div class="contact-item">
                                <i class="fas fa-phone"></i>
                                <div>
                                    <strong>Phone</strong><br>
                                    <?php echo htmlspecialchars($church_info['phone']); ?>
                                </div>
                            </div>
                            
                            <div class="contact-item">
                                <i class="fas fa-envelope"></i>
                                <div>
                                    <strong>Email</strong><br>
                                    <?php echo htmlspecialchars($church_info['email']); ?>
                                </div>
                            </div>
                            
                            <!-- Gallery Preview -->
                            <h5 style="color: white; margin-top: 25px; margin-bottom: 15px;">Recent Gallery</h5>
                            <div class="gallery-preview">
                                <?php if (!empty($recent_gallery)): ?>
                                    <?php foreach ($recent_gallery as $gallery): ?>
                                        <div class="gallery-thumb">
                                            <?php if ($gallery['file_url'] && file_exists($gallery['file_url'])): ?>
                                                <img src="<?php echo htmlspecialchars($gallery['file_url']); ?>" alt="<?php echo htmlspecialchars($gallery['title']); ?>">
                                            <?php else: ?>
                                                <img src="assets/default-gallery.jpg" alt="Gallery">
                                            <?php endif; ?>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <div class="gallery-thumb">
                                        <img src="assets/default-gallery.jpg" alt="Gallery">
                                    </div>
                                    <div class="gallery-thumb">
                                        <img src="assets/default-gallery.jpg" alt="Gallery">
                                    </div>
                                    <div class="gallery-thumb">
                                        <img src="assets/default-gallery.jpg" alt="Gallery">
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Footer Bottom -->
        <div class="footer-bottom">
            <div class="container">
                <div class="footer-bottom-content">
                    <div class="footer-copyright">
                        <p>&copy; <?php echo date('Y'); ?> <?php echo htmlspecialchars($church_info['name']); ?>. All rights reserved.</p>
                        <p style="font-size: 0.9rem; margin-top: 5px;">Made with <i class="fas fa-heart" style="color: #dc2626;"></i> for God's glory</p>
                    </div>
                    <div class="footer-bottom-links">
                        <a href="#">Privacy Policy</a>
                        <a href="#">Terms of Service</a>
                        <a href="#">Cookie Policy</a>
                        <a href="#">Sitemap</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    
    <!-- Back to Top Button -->
    <a href="#" class="back-to-top" id="backToTop">
        <i class="fas fa-arrow-up"></i>
    </a>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Back to Top Button
        const backToTopButton = document.getElementById('backToTop');
        
        window.addEventListener('scroll', () => {
            if (window.pageYOffset > 300) {
                backToTopButton.classList.add('show');
            } else {
                backToTopButton.classList.remove('show');
            }
        });
        
        backToTopButton.addEventListener('click', (e) => {
            e.preventDefault();
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
        
        // Add animation to bounce arrow
        const style = document.createElement('style');
        style.textContent = `
            @keyframes bounce {
                0%, 20%, 50%, 80%, 100% {
                    transform: translateY(0);
                }
                40% {
                    transform: translateY(-20px);
                }
                60% {
                    transform: translateY(-10px);
                }
            }
        `;
        document.head.appendChild(style);
    </script>
</body>
</html>
