<?php
// Compact Perfect Footer Component - Include this in all pages
require_once 'config.php';
require_once 'db.php';

// Get church information and statistics
$church_info = [
    'name' => 'Salem Dominion Ministries',
    'address' => '123 Church Street, City, State',
    'phone' => '+256 753 244480',
    'email' => 'visit@salemdominionministries'
];

try {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME, DB_PORT);
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }
    
    // Get statistics
    $total_members = $conn->query("SELECT COUNT(*) as count FROM users WHERE is_active = 1")->fetch_assoc()['count'];
    $upcoming_events = $conn->query("SELECT title, event_date FROM events WHERE status = 'upcoming' ORDER BY event_date ASC LIMIT 2");
    $recent_gallery = $conn->query("SELECT title, file_url FROM gallery WHERE status = 'published' ORDER BY created_at DESC LIMIT 3");
    
    $conn->close();
    
} catch (Exception $e) {
    $total_members = 0;
    $upcoming_events = [];
    $recent_gallery = [];
}
?>

<!-- Compact Perfect Footer Component -->
<style>
/* Compact Footer Styles */
.compact-footer {
    background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
    color: white;
    position: relative;
    overflow: hidden;
    margin-top: 40px;
}

.compact-footer::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: linear-gradient(90deg, #16a34a 0%, #0ea5e9 50%, #dc2626 100%);
}

.footer-content {
    padding: 40px 0 20px;
    position: relative;
    z-index: 1;
}

.footer-section {
    margin-bottom: 25px;
}

.footer-logo {
    font-size: 1.5rem;
    color: #16a34a;
    margin-bottom: 10px;
    display: inline-block;
}

.footer-description {
    color: #94a3b8;
    line-height: 1.4;
    margin-bottom: 15px;
    font-size: 0.85rem;
}

.footer-title {
    font-size: 0.9rem;
    font-weight: 600;
    color: white;
    margin-bottom: 12px;
    position: relative;
    padding-bottom: 5px;
}

.footer-title::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 30px;
    height: 2px;
    background: linear-gradient(90deg, #16a34a 0%, #0ea5e9 100%);
    border-radius: 1px;
}

.footer-links {
    list-style: none;
    padding: 0;
    margin: 0;
}

.footer-links li {
    margin-bottom: 6px;
}

.footer-links a {
    color: #94a3b8;
    text-decoration: none;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    font-size: 0.8rem;
}

.footer-links a:hover {
    color: #16a34a;
    transform: translateX(3px);
}

.footer-links a i {
    width: 15px;
    margin-right: 6px;
    color: #16a34a;
    font-size: 0.7rem;
}

.contact-item {
    display: flex;
    align-items: center;
    margin-bottom: 8px;
    color: #94a3b8;
    font-size: 0.8rem;
}

.contact-item i {
    width: 25px;
    height: 25px;
    background: rgba(22, 163, 74, 0.1);
    color: #16a34a;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 8px;
    font-size: 0.7rem;
}

.social-links {
    display: flex;
    gap: 8px;
    margin-top: 15px;
}

.social-links a {
    width: 30px;
    height: 30px;
    background: rgba(22, 163, 74, 0.1);
    color: #16a34a;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    transition: all 0.3s ease;
    font-size: 0.8rem;
}

.social-links a:hover {
    background: #16a34a;
    color: white;
    transform: translateY(-2px);
}

.event-item {
    background: rgba(255, 255, 255, 0.05);
    padding: 8px;
    border-radius: 6px;
    margin-bottom: 8px;
    border-left: 2px solid #16a34a;
}

.event-title {
    font-weight: 600;
    font-size: 0.75rem;
    margin-bottom: 3px;
    color: white;
}

.event-date {
    font-size: 0.7rem;
    color: #94a3b8;
}

.gallery-preview {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 6px;
    margin-top: 10px;
}

.gallery-thumb {
    aspect-ratio: 1;
    border-radius: 4px;
    overflow: hidden;
    position: relative;
    cursor: pointer;
    transition: all 0.3s ease;
}

.gallery-thumb:hover {
    transform: scale(1.05);
}

.gallery-thumb img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 8px;
    margin-top: 15px;
}

.stat-item {
    text-align: center;
    padding: 8px;
    background: rgba(255, 255, 255, 0.05);
    border-radius: 6px;
}

.stat-number {
    font-size: 1.2rem;
    font-weight: bold;
    color: #16a34a;
    margin-bottom: 3px;
}

.stat-label {
    font-size: 0.7rem;
    color: #94a3b8;
}

.footer-bottom {
    background: #0f172a;
    padding: 15px 0;
    text-align: center;
    border-top: 1px solid rgba(255, 255, 255, 0.1);
}

.footer-bottom-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 10px;
}

.footer-copyright {
    color: #94a3b8;
    font-size: 0.75rem;
}

.footer-bottom-links {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

.footer-bottom-links a {
    color: #94a3b8;
    text-decoration: none;
    transition: color 0.3s ease;
    font-size: 0.75rem;
}

.footer-bottom-links a:hover {
    color: #16a34a;
}

/* Back to Top Button */
.back-to-top {
    position: fixed;
    bottom: 20px;
    right: 20px;
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, #16a34a 0%, #15803d 100%);
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s ease;
    z-index: 1000;
    font-size: 0.9rem;
    box-shadow: 0 4px 12px rgba(22, 163, 74, 0.3);
}

.back-to-top:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 20px rgba(22, 163, 74, 0.4);
}

/* Mobile Responsive */
@media (max-width: 768px) {
    .footer-content {
        padding: 30px 0 15px;
    }
    
    .footer-section {
        margin-bottom: 20px;
    }
    
    .footer-logo {
        font-size: 1.3rem;
        margin-bottom: 8px;
    }
    
    .footer-title {
        font-size: 0.85rem;
        margin-bottom: 10px;
    }
    
    .footer-description {
        font-size: 0.8rem;
        margin-bottom: 12px;
    }
    
    .footer-links a {
        font-size: 0.75rem;
    }
    
    .contact-item {
        font-size: 0.75rem;
        margin-bottom: 6px;
    }
    
    .gallery-preview {
        grid-template-columns: repeat(3, 1fr);
        gap: 4px;
    }
    
    .stats-grid {
        grid-template-columns: repeat(3, 1fr);
        gap: 6px;
        margin-top: 12px;
    }
    
    .stat-number {
        font-size: 1rem;
    }
    
    .stat-label {
        font-size: 0.65rem;
    }
    
    .footer-bottom {
        padding: 12px 0;
    }
    
    .footer-bottom-content {
        flex-direction: column;
        text-align: center;
        gap: 8px;
    }
    
    .footer-copyright {
        font-size: 0.7rem;
    }
    
    .footer-bottom-links {
        justify-content: center;
        gap: 8px;
    }
    
    .footer-bottom-links a {
        font-size: 0.7rem;
    }
    
    .back-to-top {
        bottom: 15px;
        right: 15px;
        width: 35px;
        height: 35px;
        font-size: 0.8rem;
    }
}

@media (max-width: 576px) {
    .footer-logo {
        font-size: 1.2rem;
    }
    
    .footer-title {
        font-size: 0.8rem;
    }
    
    .footer-description {
        font-size: 0.75rem;
    }
    
    .footer-links a {
        font-size: 0.7rem;
        padding: 2px 0;
    }
    
    .contact-item {
        font-size: 0.7rem;
        margin-bottom: 5px;
        flex-direction: column;
        text-align: center;
    }
    
    .contact-item i {
        margin-right: 0;
        margin-bottom: 5px;
        width: 20px;
        height: 20px;
        font-size: 0.6rem;
    }
    
    .gallery-preview {
        grid-template-columns: repeat(2, 1fr);
        gap: 3px;
    }
    
    .event-item {
        padding: 6px;
        margin-bottom: 6px;
    }
    
    .event-title {
        font-size: 0.7rem;
    }
    
    .event-date {
        font-size: 0.65rem;
    }
    
    .social-links {
        justify-content: center;
        gap: 6px;
        margin-top: 10px;
    }
    
    .social-links a {
        width: 25px;
        height: 25px;
        font-size: 0.7rem;
    }
    
    .stats-grid {
        grid-template-columns: 1fr;
        gap: 5px;
        margin-top: 10px;
    }
    
    .stat-number {
        font-size: 0.9rem;
    }
    
    .stat-label {
        font-size: 0.6rem;
    }
    
    .footer-bottom {
        padding: 10px 0;
    }
    
    .footer-bottom-links {
        gap: 6px;
    }
    
    .footer-bottom-links a {
        font-size: 0.65rem;
    }
    
    .footer-copyright {
        font-size: 0.65rem;
    }
    
    .back-to-top {
        bottom: 10px;
        right: 10px;
        width: 30px;
        height: 30px;
        font-size: 0.7rem;
    }
}
</style>

<!-- Compact Perfect Footer Component -->
<footer class="compact-footer">
    <div class="container">
        <div class="footer-content">
            <div class="row">
                <!-- Church Info -->
                <div class="col-lg-4 col-md-6">
                    <div class="footer-section">
                        <div class="footer-logo">
                            <i class="fas fa-church"></i> Salem Dominion Ministries
                        </div>
                        <p class="footer-description">
                            Welcome to our spiritual home where faith comes alive through worship, fellowship, and service.
                        </p>
                        
                        <div class="contact-item">
                            <i class="fas fa-map-marker-alt"></i>
                            <span>123 Church Street, City, State</span>
                        </div>
                        <div class="contact-item">
                            <i class="fas fa-phone"></i>
                            <span>+256 753 244480</span>
                        </div>
                        <div class="contact-item">
                            <i class="fas fa-envelope"></i>
                            <span>visit@salemdominionministries.com</span>
                        </div>
                        
                        <div class="social-links">
                            <a href="#" title="Facebook"><i class="fab fa-facebook-f"></i></a>
                            <a href="#" title="Twitter"><i class="fab fa-twitter"></i></a>
                            <a href="#" title="Instagram"><i class="fab fa-instagram"></i></a>
                            <a href="#" title="YouTube"><i class="fab fa-youtube"></i></a>
                        </div>
                    </div>
                </div>
                
                <!-- Quick Links -->
                <div class="col-lg-4 col-md-6">
                    <div class="footer-section">
                        <h5 class="footer-title">Quick Links</h5>
                        <ul class="footer-links">
                            <li><a href="index.php"><i class="fas fa-home"></i> Home</a></li>
                            <li><a href="about.php"><i class="fas fa-church"></i> About Us</a></li>
                            <li><a href="events.php"><i class="fas fa-calendar"></i> Events</a></li>
                            <li><a href="gallery.php"><i class="fas fa-images"></i> Gallery</a></li>
                            <li><a href="sermons.php"><i class="fas fa-bible"></i> Sermons</a></li>
                            <li><a href="donations.php"><i class="fas fa-donate"></i> Give</a></li>
                            <li><a href="book_pastor.php"><i class="fas fa-calendar"></i> Book Pastor</a></li>
                            <li><a href="contact.php"><i class="fas fa-envelope"></i> Contact</a></li>
                        </ul>
                    </div>
                </div>
                
                <!-- Recent Activities -->
                <div class="col-lg-4 col-md-12">
                    <div class="footer-section">
                        <h5 class="footer-title">Recent Activities</h5>
                        
                        <!-- Upcoming Events -->
                        <?php if ($upcoming_events && $upcoming_events->num_rows > 0): ?>
                            <?php while ($event = $upcoming_events->fetch_assoc()): ?>
                                <div class="event-item">
                                    <div class="event-title"><?php echo htmlspecialchars($event['title']); ?></div>
                                    <div class="event-date"><?php echo date('M j, Y', strtotime($event['event_date'])); ?></div>
                                </div>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <div class="event-item">
                                <div class="event-title">Sunday Service</div>
                                <div class="event-date">Every Sunday at 10:30 AM</div>
                            </div>
                            <div class="event-item">
                                <div class="event-title">Bible Study</div>
                                <div class="event-date">Wednesday at 5:30 PM</div>
                            </div>
                        <?php endif; ?>
                        
                        <!-- Gallery Preview -->
                        <h5 class="footer-title" style="margin-top: 15px;">Gallery</h5>
                        <div class="gallery-preview">
                            <?php if ($recent_gallery && $recent_gallery->num_rows > 0): ?>
                                <?php while ($gallery = $recent_gallery->fetch_assoc()): ?>
                                    <div class="gallery-thumb">
                                        <img src="<?php echo htmlspecialchars($gallery['file_url']); ?>" alt="<?php echo htmlspecialchars($gallery['title']); ?>">
                                    </div>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <div class="gallery-thumb">
                                    <img src="https://via.placeholder.com/100x100/16a34a/ffffff?text=Church" alt="Church">
                                </div>
                                <div class="gallery-thumb">
                                    <img src="https://via.placeholder.com/100x100/0ea5e9/ffffff?text=Worship" alt="Worship">
                                </div>
                                <div class="gallery-thumb">
                                    <img src="https://via.placeholder.com/100x100/dc2626/ffffff?text=Community" alt="Community">
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Statistics -->
            <div class="row mt-3">
                <div class="col-12">
                    <div class="stats-grid">
                        <div class="stat-item">
                            <div class="stat-number"><?php echo $total_members; ?></div>
                            <div class="stat-label">Members</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number"><?php echo $upcoming_events ? $upcoming_events->num_rows : 0; ?></div>
                            <div class="stat-label">Events</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number"><?php echo $recent_gallery ? $recent_gallery->num_rows : 0; ?></div>
                            <div class="stat-label">Gallery</div>
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
                    © <?php echo date('Y'); ?> Salem Dominion Ministries. All rights reserved.
                </div>
                <div class="footer-bottom-links">
                    <a href="privacy.php">Privacy Policy</a>
                    <a href="terms.php">Terms of Service</a>
                    <a href="sitemap.php">Sitemap</a>
                </div>
            </div>
        </div>
    </div>
</footer>

<!-- Back to Top Button -->
<a href="#" class="back-to-top" id="backToTop" title="Back to Top">
    <i class="fas fa-arrow-up"></i>
</a>

<script>
// Back to top functionality
document.getElementById('backToTop').addEventListener('click', function(e) {
    e.preventDefault();
    window.scrollTo({ top: 0, behavior: 'smooth' });
});

// Show/hide back to top button based on scroll position
window.addEventListener('scroll', function() {
    var backToTop = document.getElementById('backToTop');
    if (window.pageYOffset > 300) {
        backToTop.style.display = 'flex';
    } else {
        backToTop.style.display = 'none';
    }
});

// Initially hide back to top button
document.getElementById('backToTop').style.display = 'none';
</script>
