<?php
// Complete error suppression to prevent unwanted output
error_reporting(0);
ini_set('display_errors', 0);
ini_set('log_errors', 0);

// Buffer output to catch any accidental output
ob_start();

// Get leadership data with error handling
require_once 'db.php';
try {
    $leadership = $db->query("SELECT * FROM leadership WHERE is_active = 1 ORDER BY order_position ASC LIMIT 4");
} catch (Exception $e) {
    $leadership = [];
}

// Clean any buffered output
ob_end_clean();
?>

<!-- Enhanced Ultimate Footer -->
<footer class="ultimate-footer">
    <!-- Floating Particles Background -->
    <div class="footer-particles" id="footerParticles"></div>
    
    <!-- Shimmer Effect -->
    <div class="footer-shimmer"></div>
    
    <!-- Main Footer Content -->
    <div class="footer-content">
        <!-- Top Section -->
        <div class="footer-top">
            <div class="container">
                <div class="row">
                    <!-- Church Branding -->
                    <div class="col-lg-4 col-md-6">
                        <div class="footer-brand" data-aos="fade-up">
                            <div class="brand-logo">
                                <img src="assets/logo-DEFqnQ4s.jpeg" alt="Salem Dominion Ministries">
                            </div>
                            <div class="brand-info">
                                <h3>Salem Dominion Ministries</h3>
                                <p class="brand-motto">Spreading the Gospel • Transforming Lives</p>
                                <p class="brand-description">
                                    A vibrant church community committed to spreading the love of Christ, 
                                    making disciples, and serving our community with compassion and excellence.
                                </p>
                            </div>
                            <div class="social-links">
                                <a href="https://facebook.com" target="_blank" class="social-link facebook">
                                    <i class="fab fa-facebook-f"></i>
                                </a>
                                <a href="https://twitter.com" target="_blank" class="social-link twitter">
                                    <i class="fab fa-twitter"></i>
                                </a>
                                <a href="https://instagram.com" target="_blank" class="social-link instagram">
                                    <i class="fab fa-instagram"></i>
                                </a>
                                <a href="https://youtube.com" target="_blank" class="social-link youtube">
                                    <i class="fab fa-youtube"></i>
                                </a>
                                <a href="https://wa.me/256753244480" target="_blank" class="social-link whatsapp">
                                    <i class="fab fa-whatsapp"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Quick Links -->
                    <div class="col-lg-2 col-md-6">
                        <div class="footer-section" data-aos="fade-up" data-aos-delay="100">
                            <h4 class="section-title">Quick Links</h4>
                            <ul class="footer-links">
                                <li><a href="index.php"><i class="fas fa-home"></i> Home</a></li>
                                <li><a href="about.php"><i class="fas fa-info-circle"></i> About Us</a></li>
                                <li><a href="ministries.php"><i class="fas fa-hands-helping"></i> Ministries</a></li>
                                <li><a href="events.php"><i class="fas fa-calendar"></i> Events</a></li>
                                <li><a href="sermons.php"><i class="fas fa-microphone"></i> Sermons</a></li>
                                <li><a href="news.php"><i class="fas fa-newspaper"></i> News</a></li>
                                <li><a href="gallery.php"><i class="fas fa-images"></i> Gallery</a></li>
                                <li><a href="contact.php"><i class="fas fa-envelope"></i> Contact</a></li>
                            </ul>
                        </div>
                    </div>
                    
                    <!-- Contact Info -->
                    <div class="col-lg-3 col-md-6">
                        <div class="footer-section" data-aos="fade-up" data-aos-delay="200">
                            <h4 class="section-title">Contact Info</h4>
                            <div class="contact-info">
                                <div class="contact-item">
                                    <div class="contact-icon">
                                        <i class="fas fa-map-marker-alt"></i>
                                    </div>
                                    <div class="contact-text">
                                        <h5>Location</h5>
                                        <p>Main Street, Iganga Town<br>Uganda, East Africa</p>
                                    </div>
                                </div>
                                <div class="contact-item">
                                    <div class="contact-icon">
                                        <i class="fas fa-phone"></i>
                                    </div>
                                    <div class="contact-text">
                                        <h5>Phone</h5>
                                        <p>Office: +256 753 244 480<br>Pastor: +256 753 244 480</p>
                                    </div>
                                </div>
                                <div class="contact-item">
                                    <div class="contact-icon">
                                        <i class="fas fa-envelope"></i>
                                    </div>
                                    <div class="contact-text">
                                        <h5>Email</h5>
                                        <p>apostle@salemdominionministries.com<br>info@salemdominionministries.com</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Service Times -->
                    <div class="col-lg-3 col-md-6">
                        <div class="footer-section" data-aos="fade-up" data-aos-delay="300">
                            <h4 class="section-title">Service Times</h4>
                            <div class="service-times">
                                <div class="service-item">
                                    <div class="service-time">8:00 AM</div>
                                    <div class="service-name">Early Morning Service</div>
                                </div>
                                <div class="service-item">
                                    <div class="service-time">10:00 AM</div>
                                    <div class="service-name">Main Service</div>
                                </div>
                                <div class="service-item">
                                    <div class="service-time">12:00 PM</div>
                                    <div class="service-name">Youth Service</div>
                                </div>
                                <div class="service-item">
                                    <div class="service-time">6:00 PM</div>
                                    <div class="service-name">Evening Service</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Leadership Showcase -->
        <div class="footer-leadership">
            <div class="container">
                <h3 class="leadership-title" data-aos="fade-up">Our Leadership</h3>
                <div class="leadership-grid">
                    <?php if ($leadership && $leadership->num_rows > 0): ?>
                        <?php while ($leader = $leadership->fetch_assoc()): ?>
                            <div class="leader-card" data-aos="fade-up" data-aos-delay="200">
                                <div class="leader-image">
                                    <img src="assets/<?php echo htmlspecialchars($leader['image'] ?? 'pastor-Cw0w7ugz.jpeg'); ?>" 
                                         alt="<?php echo htmlspecialchars($leader['name']); ?>"
                                         onerror="this.src='assets/pastor-Cw0w7ugz.jpeg'">
                                </div>
                                <div class="leader-info">
                                    <h5><?php echo htmlspecialchars($leader['name']); ?></h5>
                                    <p><?php echo htmlspecialchars($leader['title']); ?></p>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <!-- Sample Leadership -->
                        <div class="leader-card" data-aos="fade-up" data-aos-delay="200">
                            <div class="leader-image">
                                <img src="assets/APOSTLE-IRENE-MIREMBE-CwWfzcRx.jpeg" alt="Apostle Faty Musasizi">
                            </div>
                            <div class="leader-info">
                                <h5>Apostle Faty Musasizi</h5>
                                <p>Senior Pastor</p>
                            </div>
                        </div>
                        <div class="leader-card" data-aos="fade-up" data-aos-delay="300">
                            <div class="leader-image">
                                <img src="assets/PASTOR-NABULYA-JOYCE-BdB4SkbM.jpeg" alt="Pastor Nabulya Joyce">
                            </div>
                            <div class="leader-info">
                                <h5>Pastor Nabulya Joyce</h5>
                                <p>Associate Pastor</p>
                            </div>
                        </div>
                        <div class="leader-card" data-aos="fade-up" data-aos-delay="400">
                            <div class="leader-image">
                                <img src="assets/Pastor-damali-namwuma-DSRkNJ6q.png" alt="Pastor Damali Namwuma">
                            </div>
                            <div class="leader-info">
                                <h5>Pastor Damali Namwuma</h5>
                                <p>Youth Pastor</p>
                            </div>
                        </div>
                        <div class="leader-card" data-aos="fade-up" data-aos-delay="500">
                            <div class="leader-image">
                                <img src="assets/Pastor-miriam-Gerald-CApzM7-5.jpeg" alt="Pastor Miriam Gerald">
                            </div>
                            <div class="leader-info">
                                <h5>Pastor Miriam Gerald</h5>
                                <p>Worship Leader</p>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <!-- Newsletter Section -->
        <div class="footer-newsletter">
            <div class="container">
                <div class="newsletter-content" data-aos="fade-up">
                    <h3 class="newsletter-title">Stay Connected</h3>
                    <p class="newsletter-description">
                        Subscribe to our newsletter for weekly updates, events, and spiritual inspiration
                    </p>
                    <form class="newsletter-form" action="newsletter_subscribe.php" method="POST">
                        <div class="form-group">
                            <input type="email" name="email" class="form-control" placeholder="Enter your email address" required>
                            <button type="submit" class="btn-newsletter">
                                <i class="fas fa-paper-plane"></i> Subscribe
                            </button>
                        </div>
                    </form>
                    <div class="newsletter-benefits">
                        <div class="benefit-item">
                            <i class="fas fa-check-circle"></i>
                            <span>Weekly sermons</span>
                        </div>
                        <div class="benefit-item">
                            <i class="fas fa-check-circle"></i>
                            <span>Event updates</span>
                        </div>
                        <div class="benefit-item">
                            <i class="fas fa-check-circle"></i>
                            <span>Spiritual inspiration</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Bottom Section -->
        <div class="footer-bottom">
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        <div class="copyright">
                            <p>&copy; <?php echo date('Y'); ?> Salem Dominion Ministries. All rights reserved.</p>
                            <div class="legal-links">
                                <a href="#">Privacy Policy</a>
                                <a href="#">Terms of Service</a>
                                <a href="#">Cookie Policy</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="footer-credits">
                            <div class="designer-credit">
                                <i class="fas fa-crown"></i>
                                <span>Designed and Managed by</span>
                                <a href="https://reaganotema.com" target="_blank" class="designer-link">Mr. Reagan Otema</a>
                            </div>
                            <div class="developer-contact">
                                <a href="https://wa.me/256753244480" target="_blank" class="developer-whatsapp">
                                    <i class="fab fa-whatsapp"></i>
                                    <span>Developer WhatsApp</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- WhatsApp Floating Button -->
    <div class="whatsapp-float" onclick="openWhatsApp()">
        <div class="whatsapp-icon">
            <i class="fab fa-whatsapp"></i>
        </div>
        <div class="whatsapp-tooltip">
            <div class="tooltip-content">
                <h6>Chat with Pastor</h6>
                <p>Need spiritual guidance? Talk to Apostle Faty Musasizi now!</p>
                <small>+256 753 244 480</small>
            </div>
        </div>
    </div>
    
    <!-- Back to Top Button -->
    <div class="back-to-top" onclick="scrollToTop()">
        <i class="fas fa-arrow-up"></i>
    </div>
</footer>

<style>
/* Enhanced Ultimate Footer Styles */
.ultimate-footer {
    position: relative;
    background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #334155 100%);
    color: #ffffff;
    overflow: hidden;
    margin-top: 100px;
}

/* Floating Particles */
.footer-particles {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    overflow: hidden;
    z-index: 1;
}

.particle {
    position: absolute;
    width: 3px;
    height: 3px;
    background: #fbbf24;
    border-radius: 50%;
    opacity: 0.3;
    animation: floatParticle 15s infinite linear;
}

@keyframes floatParticle {
    0% {
        transform: translateY(100vh) translateX(0);
        opacity: 0;
    }
    10% {
        opacity: 0.3;
    }
    90% {
        opacity: 0.3;
    }
    100% {
        transform: translateY(-100vh) translateX(100px);
        opacity: 0;
    }
}

/* Shimmer Effect */
.footer-shimmer {
    position: absolute;
    top: 0;
    left: -100%;
    width: 300%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(251, 191, 36, 0.05), transparent);
    animation: shimmerFooter 20s infinite;
    z-index: 2;
}

@keyframes shimmerFooter {
    0% { transform: translateX(-100%); }
    100% { transform: translateX(100%); }
}

/* Footer Content */
.footer-content {
    position: relative;
    z-index: 10;
}

/* Top Section */
.footer-top {
    padding: 60px 0 40px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.footer-brand {
    text-align: center;
}

.brand-logo img {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    border: 3px solid #fbbf24;
    padding: 8px;
    margin-bottom: 20px;
    box-shadow: 0 0 30px rgba(251, 191, 36, 0.3);
    transition: all 0.3s ease;
}

.brand-logo:hover img {
    transform: scale(1.1) rotate(5deg);
    box-shadow: 0 0 40px rgba(251, 191, 36, 0.5);
}

.brand-info h3 {
    font-family: 'Playfair Display', serif;
    font-size: 1.8rem;
    font-weight: 700;
    margin-bottom: 10px;
    color: #fbbf24;
}

.brand-motto {
    font-family: 'Great Vibes', cursive;
    font-size: 1.2rem;
    margin-bottom: 15px;
    color: #eab308;
}

.brand-description {
    font-size: 0.95rem;
    line-height: 1.6;
    color: rgba(255, 255, 255, 0.8);
    margin-bottom: 25px;
}

.social-links {
    display: flex;
    justify-content: center;
    gap: 15px;
}

.social-link {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #ffffff;
    text-decoration: none;
    transition: all 0.3s ease;
    font-size: 1rem;
}

.social-link.facebook {
    background: #1877f2;
}

.social-link.twitter {
    background: #1da1f2;
}

.social-link.instagram {
    background: linear-gradient(45deg, #f09433, #e6683c, #dc2743, #cc2366, #bc1888);
}

.social-link.youtube {
    background: #ff0000;
}

.social-link.whatsapp {
    background: #25d366;
}

.social-link:hover {
    transform: translateY(-3px) scale(1.1);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
}

/* Footer Sections */
.footer-section h4.section-title {
    font-family: 'Playfair Display', serif;
    font-size: 1.3rem;
    font-weight: 700;
    margin-bottom: 25px;
    color: #fbbf24;
    position: relative;
    padding-bottom: 10px;
}

.footer-section h4.section-title::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 50px;
    height: 2px;
    background: #fbbf24;
}

.footer-links {
    list-style: none;
    padding: 0;
    margin: 0;
}

.footer-links li {
    margin-bottom: 12px;
}

.footer-links a {
    color: rgba(255, 255, 255, 0.8);
    text-decoration: none;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 0.95rem;
}

.footer-links a:hover {
    color: #fbbf24;
    transform: translateX(5px);
}

.footer-links a i {
    width: 16px;
    text-align: center;
}

/* Contact Info */
.contact-info {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.contact-item {
    display: flex;
    align-items: flex-start;
    gap: 15px;
}

.contact-icon {
    width: 40px;
    height: 40px;
    background: rgba(251, 191, 36, 0.2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fbbf24;
    flex-shrink: 0;
}

.contact-text h5 {
    font-size: 1rem;
    font-weight: 600;
    margin-bottom: 5px;
    color: #fbbf24;
}

.contact-text p {
    font-size: 0.9rem;
    color: rgba(255, 255, 255, 0.8);
    line-height: 1.4;
    margin: 0;
}

/* Service Times */
.service-times {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.service-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 15px;
    background: rgba(255, 255, 255, 0.05);
    border-radius: 10px;
    border: 1px solid rgba(255, 255, 255, 0.1);
    transition: all 0.3s ease;
}

.service-item:hover {
    background: rgba(251, 191, 36, 0.1);
    border-color: rgba(251, 191, 36, 0.3);
    transform: translateX(5px);
}

.service-time {
    font-family: 'Playfair Display', serif;
    font-size: 1.1rem;
    font-weight: 700;
    color: #fbbf24;
}

.service-name {
    font-size: 0.9rem;
    color: rgba(255, 255, 255, 0.8);
}

/* Leadership Showcase */
.footer-leadership {
    padding: 40px 0;
    border-top: 1px solid rgba(255, 255, 255, 0.1);
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.leadership-title {
    font-family: 'Playfair Display', serif;
    font-size: 2rem;
    font-weight: 700;
    text-align: center;
    margin-bottom: 40px;
    color: #fbbf24;
}

.leadership-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 25px;
}

.leader-card {
    text-align: center;
    transition: all 0.3s ease;
}

.leader-card:hover {
    transform: translateY(-5px);
}

.leader-image {
    width: 100px;
    height: 100px;
    margin: 0 auto 15px;
    border-radius: 50%;
    overflow: hidden;
    border: 3px solid #fbbf24;
    box-shadow: 0 0 20px rgba(251, 191, 36, 0.3);
    transition: all 0.3s ease;
}

.leader-card:hover .leader-image {
    transform: scale(1.05);
    box-shadow: 0 0 30px rgba(251, 191, 36, 0.5);
}

.leader-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.leader-info h5 {
    font-size: 1.1rem;
    font-weight: 600;
    margin-bottom: 5px;
    color: #ffffff;
}

.leader-info p {
    font-size: 0.9rem;
    color: #fbbf24;
    margin: 0;
}

/* Newsletter Section */
.footer-newsletter {
    padding: 40px 0;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.newsletter-content {
    text-align: center;
    max-width: 600px;
    margin: 0 auto;
}

.newsletter-title {
    font-family: 'Playfair Display', serif;
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 15px;
    color: #fbbf24;
}

.newsletter-description {
    font-size: 1rem;
    color: rgba(255, 255, 255, 0.8);
    margin-bottom: 30px;
    line-height: 1.6;
}

.newsletter-form .form-group {
    display: flex;
    gap: 10px;
    margin-bottom: 20px;
}

.newsletter-form input {
    flex: 1;
    padding: 12px 20px;
    border: 2px solid rgba(251, 191, 36, 0.3);
    border-radius: 25px;
    background: rgba(255, 255, 255, 0.1);
    color: #ffffff;
    font-size: 0.95rem;
    transition: all 0.3s ease;
}

.newsletter-form input:focus {
    outline: none;
    border-color: #fbbf24;
    background: rgba(255, 255, 255, 0.15);
}

.newsletter-form input::placeholder {
    color: rgba(255, 255, 255, 0.5);
}

.btn-newsletter {
    padding: 12px 25px;
    background: linear-gradient(135deg, #fbbf24, #eab308);
    color: #0f172a;
    border: none;
    border-radius: 25px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    white-space: nowrap;
}

.btn-newsletter:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(251, 191, 36, 0.3);
}

.newsletter-benefits {
    display: flex;
    justify-content: center;
    gap: 20px;
    flex-wrap: wrap;
}

.benefit-item {
    display: flex;
    align-items: center;
    gap: 5px;
    font-size: 0.9rem;
    color: rgba(255, 255, 255, 0.8);
}

.benefit-item i {
    color: #fbbf24;
}

/* Bottom Section */
.footer-bottom {
    padding: 30px 0;
    background: rgba(0, 0, 0, 0.2);
}

.copyright p {
    margin-bottom: 10px;
    color: rgba(255, 255, 255, 0.8);
    font-size: 0.9rem;
}

.legal-links {
    display: flex;
    gap: 20px;
    flex-wrap: wrap;
}

.legal-links a {
    color: rgba(255, 255, 255, 0.6);
    text-decoration: none;
    font-size: 0.85rem;
    transition: color 0.3s ease;
}

.legal-links a:hover {
    color: #fbbf24;
}

.footer-credits {
    text-align: right;
}

.designer-credit {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 8px;
    margin-bottom: 10px;
    font-size: 0.9rem;
    color: rgba(255, 255, 255, 0.8);
}

.designer-credit i {
    color: #fbbf24;
}

.designer-link {
    color: #fbbf24;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s ease;
}

.designer-link:hover {
    color: #eab308;
    text-decoration: underline;
}

.developer-contact {
    text-align: right;
}

.developer-whatsapp {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    color: #25d366;
    text-decoration: none;
    font-size: 0.85rem;
    transition: all 0.3s ease;
}

.developer-whatsapp:hover {
    color: #128c7e;
    transform: scale(1.05);
}

/* WhatsApp Floating Button */
.whatsapp-float {
    position: fixed;
    bottom: 30px;
    right: 30px;
    width: 60px;
    height: 60px;
    background: #25d366;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #ffffff;
    cursor: pointer;
    z-index: 1000;
    transition: all 0.3s ease;
    box-shadow: 0 10px 25px rgba(37, 211, 102, 0.3);
}

.whatsapp-float:hover {
    transform: scale(1.1);
    box-shadow: 0 15px 35px rgba(37, 211, 102, 0.4);
}

.whatsapp-icon {
    font-size: 1.5rem;
}

.whatsapp-tooltip {
    position: absolute;
    bottom: 70px;
    right: 0;
    background: #ffffff;
    color: #0f172a;
    padding: 15px;
    border-radius: 10px;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
    opacity: 0;
    visibility: hidden;
    transition: all 0.3s ease;
    white-space: nowrap;
    transform: translateY(10px);
}

.whatsapp-float:hover .whatsapp-tooltip {
    opacity: 1;
    visibility: visible;
    transform: translateY(0);
}

.tooltip-content h6 {
    font-size: 0.9rem;
    font-weight: 600;
    margin-bottom: 5px;
    color: #25d366;
}

.tooltip-content p {
    font-size: 0.8rem;
    margin-bottom: 5px;
    color: #64748b;
}

.tooltip-content small {
    font-size: 0.75rem;
    color: #94a3b8;
}

/* Back to Top Button */
.back-to-top {
    position: fixed;
    bottom: 30px;
    left: 30px;
    width: 50px;
    height: 50px;
    background: linear-gradient(135deg, #fbbf24, #eab308);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #0f172a;
    cursor: pointer;
    z-index: 1000;
    transition: all 0.3s ease;
    box-shadow: 0 10px 25px rgba(251, 191, 36, 0.3);
    opacity: 0;
    visibility: hidden;
    transform: translateY(20px);
}

.back-to-top.visible {
    opacity: 1;
    visibility: visible;
    transform: translateY(0);
}

.back-to-top:hover {
    transform: translateY(-3px);
    box-shadow: 0 15px 35px rgba(251, 191, 36, 0.4);
}

.back-to-top i {
    font-size: 1.2rem;
}

/* Responsive Design */
@media (max-width: 768px) {
    .footer-top {
        padding: 40px 0 30px;
    }
    
    .brand-logo img {
        width: 60px;
        height: 60px;
    }
    
    .brand-info h3 {
        font-size: 1.5rem;
    }
    
    .social-links {
        justify-content: center;
    }
    
    .leadership-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
    }
    
    .leader-image {
        width: 80px;
        height: 80px;
    }
    
    .newsletter-form .form-group {
        flex-direction: column;
    }
    
    .newsletter-benefits {
        justify-content: center;
    }
    
    .footer-credits {
        text-align: center;
        margin-top: 20px;
    }
    
    .designer-credit {
        justify-content: center;
    }
    
    .whatsapp-float {
        width: 50px;
        height: 50px;
        bottom: 20px;
        right: 20px;
    }
    
    .back-to-top {
        width: 45px;
        height: 45px;
        bottom: 20px;
        left: 20px;
    }
}
</style>

<script>
// Create floating particles
function createFooterParticles() {
    const particlesContainer = document.getElementById('footerParticles');
    const particleCount = 20;
    
    if (particlesContainer) {
        for (let i = 0; i < particleCount; i++) {
            const particle = document.createElement('div');
            particle.className = 'particle';
            particle.style.left = Math.random() * 100 + '%';
            particle.style.animationDelay = Math.random() * 15 + 's';
            particle.style.animationDuration = (15 + Math.random() * 10) + 's';
            particlesContainer.appendChild(particle);
        }
    }
}

// WhatsApp functionality
function openWhatsApp() {
    window.open('https://wa.me/256753244480', '_blank');
}

// Back to top functionality
function scrollToTop() {
    window.scrollTo({
        top: 0,
        behavior: 'smooth'
    });
}

// Show/hide back to top button
window.addEventListener('scroll', function() {
    const backToTop = document.querySelector('.back-to-top');
    if (window.scrollY > 300) {
        backToTop.classList.add('visible');
    } else {
        backToTop.classList.remove('visible');
    }
});

// Initialize footer particles
document.addEventListener('DOMContentLoaded', function() {
    createFooterParticles();
});
</script>
