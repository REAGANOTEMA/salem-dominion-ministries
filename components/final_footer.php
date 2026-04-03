<?php
// Final Clean Footer Component - Contact Info Removed
require_once 'config_force.php';

// Get church information and statistics
$church_info = [
    'name' => 'Salem Dominion Ministries',
    'address' => '123 Church Street, City, State',
    'phone' => '+256 753 244480',
    'email' => 'visit@saleldominionministries.com'
];

try {
    $db = new Database();
    
    // Get statistics
    $total_members = $db->select("SELECT COUNT(*) as count FROM users WHERE is_active = 1")[0]['count'] ?? 0;
    $upcoming_events = $db->select("SELECT title, event_date FROM events WHERE status = 'upcoming' ORDER BY event_date ASC LIMIT 2");
    $recent_gallery = $db->select("SELECT title, file_url FROM gallery WHERE status = 'published' ORDER BY created_at DESC LIMIT 3");
    
} catch (Exception $e) {
    $total_members = 0;
    $upcoming_events = [];
    $recent_gallery = [];
}
?>

<!-- Final Clean Footer Component -->
<link rel="stylesheet" href="assets/css/final-clean.css">

<!-- Clean & Fiery Footer -->
<footer class="perfect-footer">
    <!-- Footer Content Area -->
    <div class="footer-content-area">
        <div class="container">
            <div class="row">
                <!-- Church Information Only -->
                <div class="col-lg-4 col-md-6">
                    <div class="footer-section">
                        <h5 class="footer-title">
                            <i class="fas fa-church"></i> <?php echo htmlspecialchars($church_info['name']); ?>
                        </h5>
                        <p class="footer-description">
                            Welcome to our spiritual home where faith comes alive through worship, fellowship, and service. Experience the power of God's love in our vibrant community.
                        </p>
                        
                        <!-- Social Links -->
                        <div class="social-links">
                            <a href="#" title="Facebook">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a href="https://youtube.com/@musasizifaty?si=a-VP5-Qen45nV1Jf" title="YouTube">
                                <i class="fab fa-youtube"></i>
                            </a>
                            <a href="#" title="Instagram">
                                <i class="fab fa-instagram"></i>
                            </a>
                            <a href="#" title="Twitter">
                                <i class="fab fa-twitter"></i>
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Quick Links Only -->
                <div class="col-lg-4 col-md-6">
                    <div class="footer-section">
                        <h5 class="footer-title">
                            <i class="fas fa-link"></i> Quick Links
                        </h5>
                        <ul class="footer-links">
                            <li>
                                <a href="index_final.php">
                                    <i class="fas fa-home"></i> Home
                                </a>
                            </li>
                            <li>
                                <a href="about.php">
                                    <i class="fas fa-church"></i> About Us
                                </a>
                            </li>
                            <li>
                                <a href="events.php">
                                    <i class="fas fa-calendar"></i> Events
                                </a>
                            </li>
                            <li>
                                <a href="gallery.php">
                                    <i class="fas fa-images"></i> Gallery
                                </a>
                            </li>
                            <li>
                                <a href="sermons.php">
                                    <i class="fas fa-bible"></i> Sermons
                                </a>
                            </li>
                            <li>
                                <a href="donations.php">
                                    <i class="fas fa-donate"></i> Give
                                </a>
                            </li>
                            <li>
                                <a href="book_pastor.php">
                                    <i class="fas fa-calendar"></i> Book Pastor
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                
                <!-- Recent Activities Only -->
                <div class="col-lg-4 col-md-12">
                    <div class="footer-section">
                        <h5 class="footer-title">
                            <i class="fas fa-fire"></i> Recent Activities
                        </h5>
                        
                        <!-- Upcoming Events -->
                        <?php if (!empty($upcoming_events)): ?>
                            <?php foreach ($upcoming_events as $event): ?>
                                <div class="event-item">
                                    <div class="event-title">
                                        <i class="fas fa-calendar-alt"></i> <?php echo htmlspecialchars($event['title']); ?>
                                    </div>
                                    <div class="event-date">
                                        <?php echo date('M j, Y', strtotime($event['event_date'])); ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="event-item">
                                <div class="event-title">
                                    <i class="fas fa-calendar-alt"></i> Sunday Service
                                </div>
                                <div class="event-date">
                                    Every Sunday at 10:30 AM
                                </div>
                            </div>
                            <div class="event-item">
                                <div class="event-title">
                                    <i class="fas fa-pray"></i> Bible Study
                                </div>
                                <div class="event-date">
                                    Every Wednesday at 7:00 PM
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <!-- Church Statistics -->
                        <div class="event-item" style="background: var(--gradient-secondary); border-color: var(--primary-color);">
                            <div class="event-title">
                                <i class="fas fa-users"></i> Our Community
                            </div>
                            <div class="event-date">
                                <?php echo $total_members; ?>+ Members Growing Strong
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Fiery Bottom Graphics -->
    <div class="fiery-bottom-graphics">
        <!-- Fire Particles -->
        <div class="fire-particles">
            <div class="fire-particle"></div>
            <div class="fire-particle"></div>
            <div class="fire-particle"></div>
            <div class="fire-particle"></div>
            <div class="fire-particle"></div>
            <div class="fire-particle"></div>
            <div class="fire-particle"></div>
            <div class="fire-particle"></div>
            <div class="fire-particle"></div>
        </div>
    </div>
    
    <!-- Footer Bottom -->
    <div class="footer-bottom">
        <div class="container">
            <div class="footer-bottom-content">
                <div class="footer-copyright">
                    <i class="fas fa-copyright"></i> <?php echo date('Y'); ?> <?php echo htmlspecialchars($church_info['name']); ?>. All rights reserved.
                </div>
                <div class="footer-bottom-links">
                    <a href="privacy.php">
                        <i class="fas fa-shield-alt"></i> Privacy Policy
                    </a>
                    <a href="terms.php">
                        <i class="fas fa-file-contract"></i> Terms of Service
                    </a>
                    <a href="sitemap.php">
                        <i class="fas fa-sitemap"></i> Sitemap
                    </a>
                </div>
            </div>
            <div style="text-align: center; margin-top: 1rem;">
                <small style="color: rgba(254, 242, 242, 0.6);">
                    <i class="fas fa-fire"></i> Powered by Divine Grace <i class="fas fa-fire"></i>
                </small>
            </div>
        </div>
    </div>
</footer>

<!-- Final Footer Enhancement Script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add dynamic fire particles
    const fireParticles = document.querySelector('.fire-particles');
    if (fireParticles) {
        // Create additional fire particles for mobile
        if (window.innerWidth <= 768) {
            for (let i = 0; i < 6; i++) {
                const particle = document.createElement('div');
                particle.className = 'fire-particle';
                particle.style.left = Math.random() * 100 + '%';
                particle.style.animationDelay = Math.random() * 3 + 's';
                fireParticles.appendChild(particle);
            }
        }
        
        // Create additional fire particles for desktop
        if (window.innerWidth >= 1024) {
            for (let i = 0; i < 12; i++) {
                const particle = document.createElement('div');
                particle.className = 'fire-particle';
                particle.style.left = Math.random() * 100 + '%';
                particle.style.animationDelay = Math.random() * 3 + 's';
                fireParticles.appendChild(particle);
            }
        }
    }
    
    // Add hover effects to social links
    const socialLinks = document.querySelectorAll('.social-links a');
    socialLinks.forEach(link => {
        link.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-5px) scale(1.1) rotate(5deg)';
        });
        
        link.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0) scale(1) rotate(0deg)';
        });
    });
    
    // Add glow effect on scroll
    let lastScrollTop = 0;
    window.addEventListener('scroll', function() {
        const footer = document.querySelector('.perfect-footer');
        const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        
        if (scrollTop > lastScrollTop && scrollTop > 100) {
            // Scrolling down
            footer.style.boxShadow = '0 -10px 30px rgba(220, 38, 38, 0.3)';
        } else {
            // Scrolling up
            footer.style.boxShadow = 'none';
        }
        
        lastScrollTop = scrollTop <= 0 ? 0 : scrollTop;
    });
    
    // Add fire effect on click
    const footerElements = document.querySelectorAll('.footer-section');
    footerElements.forEach(element => {
        element.addEventListener('click', function(e) {
            if (e.target.tagName !== 'A') {
                // Create fire burst effect
                const fire = document.createElement('div');
                fire.style.cssText = `
                    position: absolute;
                    width: 20px;
                    height: 20px;
                    background: radial-gradient(circle, #dc2626, #f59e0b);
                    border-radius: 50%;
                    pointer-events: none;
                    animation: fireBurst 0.6s ease-out forwards;
                    z-index: 1000;
                `;
                fire.style.left = e.clientX - 10 + 'px';
                fire.style.top = e.clientY - 10 + 'px';
                document.body.appendChild(fire);
                
                setTimeout(() => fire.remove(), 600);
            }
        });
    });
});

// Add fire burst animation
const style = document.createElement('style');
style.textContent = `
    @keyframes fireBurst {
        0% {
            transform: scale(0);
            opacity: 1;
        }
        100% {
            transform: scale(3);
            opacity: 0;
        }
    }
`;
document.head.appendChild(style);
</script>
