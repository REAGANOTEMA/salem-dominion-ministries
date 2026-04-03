<?php
// Clean Footer - No Contact Info
require_once 'config_force.php';

// Initialize variables to prevent undefined errors
$upcoming_events = [];
$recent_gallery = [];
$church_info = [];

// Get dynamic data with forced database connection
try {
    $db = new Database();
    
    // Get upcoming events for footer
    $upcoming_events = $db->select("SELECT title, event_date FROM events WHERE status = 'upcoming' AND event_date >= NOW() ORDER BY event_date ASC LIMIT 2");
    
    // Get recent gallery for footer
    $recent_gallery = $db->select("SELECT title, file_url FROM gallery WHERE status = 'published' ORDER BY created_at DESC LIMIT 3");
    
    // Get church info
    $church_info = $db->selectOne("SELECT * FROM church_info WHERE id = 1");
    
} catch (Exception $e) {
    // Set empty arrays if database fails
    $upcoming_events = [];
    $recent_gallery = [];
    $church_info = [];
}
?>
<footer class="footer">
    <div class="container">
        <div class="row">
            <!-- About Section -->
            <div class="col-lg-4 col-md-6">
                <div class="footer-widget">
                    <h4 class="footer-title">About Salem Dominion Ministries</h4>
                    <p class="footer-text">
                        A vibrant church community committed to spreading the love of Christ, making disciples, and serving our community with compassion and excellence.
                    </p>
                    <div class="footer-social">
                        <a href="https://youtube.com/@musasizifaty?si=a-VP5-Qen45nV1Jf" target="_blank" title="YouTube">
                            <i class="fab fa-youtube"></i>
                        </a>
                        <a href="#" title="Facebook">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" title="Twitter">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" title="Instagram">
                            <i class="fab fa-instagram"></i>
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Quick Links -->
            <div class="col-lg-4 col-md-6">
                <div class="footer-widget">
                    <h4 class="footer-title">Quick Links</h4>
                    <ul class="footer-links">
                        <li><a href="index_new.php"><i class="fas fa-home"></i> Home</a></li>
                        <li><a href="about_new.php"><i class="fas fa-info-circle"></i> About Us</a></li>
                        <li><a href="ministries.php"><i class="fas fa-church"></i> Ministries</a></li>
                        <li><a href="events.php"><i class="fas fa-calendar"></i> Events</a></li>
                        <li><a href="sermons.php"><i class="fas fa-bible"></i> Sermons</a></li>
                        <li><a href="news.php"><i class="fas fa-newspaper"></i> News</a></li>
                        <li><a href="gallery.php"><i class="fas fa-images"></i> Gallery</a></li>
                        <li><a href="children.php"><i class="fas fa-child"></i> Children</a></li>
                        <li><a href="pastors.php"><i class="fas fa-users"></i> Pastors</a></li>
                        <li><a href="contact.php"><i class="fas fa-envelope"></i> Contact</a></li>
                    </ul>
                </div>
            </div>
            
            <!-- Upcoming Events -->
            <div class="col-lg-4 col-md-6">
                <div class="footer-widget">
                    <h4 class="footer-title">Upcoming Events</h4>
                    <div class="footer-events">
                        <?php if (!empty($upcoming_events)): ?>
                            <?php foreach ($upcoming_events as $event): ?>
                                <div class="footer-event">
                                    <i class="fas fa-calendar-alt"></i>
                                    <div>
                                        <div class="event-title"><?php echo htmlspecialchars($event['title']); ?></div>
                                        <div class="event-date"><?php echo date('M j, Y', strtotime($event['event_date'])); ?></div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="footer-event">
                                <i class="fas fa-calendar-alt"></i>
                                <div>
                                    <div class="event-title">Christmas Service</div>
                                    <div class="event-date">December 15, 2024</div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Recent Gallery -->
        <div class="row">
            <div class="col-12">
                <div class="footer-widget">
                    <h4 class="footer-title">Recent Gallery</h4>
                    <div class="footer-gallery">
                        <?php if (!empty($recent_gallery)): ?>
                            <?php foreach ($recent_gallery as $image): ?>
                                <div class="footer-gallery-item">
                                    <img src="<?php echo htmlspecialchars($image['file_url']); ?>" alt="<?php echo htmlspecialchars($image['title']); ?>">
                                    <div class="footer-gallery-overlay">
                                        <h5><?php echo htmlspecialchars($image['title']); ?></h5>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="footer-gallery-item">
                                <i class="fas fa-images"></i>
                            </div>
                            <div class="footer-gallery-item">
                                <i class="fas fa-images"></i>
                            </div>
                            <div class="footer-gallery-item">
                                <i class="fas fa-images"></i>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Footer Bottom -->
        <div class="footer-bottom">
            <div class="row">
                <div class="col-md-6">
                    <div class="footer-copyright">
                        <p>&copy; <?php echo date('Y'); ?> Salem Dominion Ministries. All rights reserved.</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="footer-bottom-links">
                        <a href="privacy-policy.php">Privacy Policy</a>
                        <a href="terms-of-service.php">Terms of Service</a>
                        <a href="sitemap.php">Sitemap</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>

<style>
/* Footer Styles */
.footer {
    background: var(--gradient-dark);
    color: white;
    padding: 60px 0 30px;
    position: relative;
    overflow: hidden;
}

.footer::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="%23dc2626" fill-opacity="0.1" d="M0,96L48,112C96,128,192,160C384,160,480,128C576,128,672,122.7C768,117,864,138.7C960,160,1056,128C1152,117,1248,160L1440,320Z"></path></svg>') no-repeat center bottom;
    background-size: cover;
    opacity: 0.3;
}

.footer-widget {
    margin-bottom: 30px;
}

.footer-title {
    color: white;
    font-size: 1.2rem;
    font-weight: 600;
    margin-bottom: 20px;
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
    background: var(--gradient-primary);
}

.footer-text {
    color: rgba(254, 242, 242, 0.8);
    line-height: 1.6;
    margin-bottom: 20px;
}

.footer-social {
    display: flex;
    gap: 15px;
    margin-top: 20px;
}

.footer-social a {
    width: 40px;
    height: 40px;
    background: var(--gradient-primary);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    text-decoration: none;
    transition: var(--transition-smooth);
}

.footer-social a:hover {
    transform: translateY(-3px);
    box-shadow: var(--shadow-lg);
}

.footer-links {
    list-style: none;
    padding: 0;
}

.footer-links li {
    margin-bottom: 10px;
}

.footer-links a {
    color: rgba(254, 242, 242, 0.8);
    text-decoration: none;
    transition: var(--transition-smooth);
    display: flex;
    align-items: center;
    gap: 10px;
}

.footer-links a:hover {
    color: white;
    transform: translateX(5px);
}

.footer-events {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.footer-event {
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 15px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: var(--border-radius);
    transition: var(--transition-smooth);
}

.footer-event:hover {
    background: rgba(255, 255, 255, 0.2);
    transform: translateY(-2px);
}

.footer-event i {
    color: var(--accent-color);
    font-size: 1.2rem;
}

.event-title {
    color: white;
    font-weight: 600;
    margin-bottom: 5px;
}

.event-date {
    color: rgba(254, 242, 242, 0.7);
    font-size: 0.9rem;
}

.footer-gallery {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(100px, 1fr));
    gap: 15px;
}

.footer-gallery-item {
    position: relative;
    border-radius: var(--border-radius);
    overflow: hidden;
    height: 80px;
    background: rgba(255, 255, 255, 0.1);
    display: flex;
    align-items: center;
    justify-content: center;
    transition: var(--transition-smooth);
}

.footer-gallery-item:hover {
    transform: scale(1.05);
}

.footer-gallery-item img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.footer-gallery-item i {
    color: rgba(254, 242, 242, 0.5);
    font-size: 2rem;
}

.footer-gallery-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(220, 38, 38, 0.8);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: var(--transition-smooth);
}

.footer-gallery-item:hover .footer-gallery-overlay {
    opacity: 1;
}

.footer-gallery-overlay h5 {
    color: white;
    font-size: 0.8rem;
    margin: 0;
    text-align: center;
}

.footer-bottom {
    border-top: 1px solid rgba(254, 242, 242, 0.2);
    margin-top: 40px;
    padding-top: 30px;
}

.footer-copyright {
    text-align: center;
}

.footer-copyright p {
    color: rgba(254, 242, 242, 0.7);
    margin: 0;
}

.footer-bottom-links {
    text-align: center;
    display: flex;
    justify-content: center;
    gap: 20px;
}

.footer-bottom-links a {
    color: rgba(254, 242, 242, 0.7);
    text-decoration: none;
    transition: var(--transition-smooth);
}

.footer-bottom-links a:hover {
    color: white;
}

/* Responsive Design */
@media (max-width: 767px) {
    .footer {
        padding: 40px 0 20px;
    }
    
    .footer-widget {
        margin-bottom: 30px;
    }
    
    .footer-social {
        justify-content: center;
    }
    
    .footer-gallery {
        grid-template-columns: repeat(auto-fit, minmax(80px, 1fr));
    }
    
    .footer-bottom-links {
        flex-direction: column;
        gap: 10px;
        margin-top: 20px;
    }
}
</style>
