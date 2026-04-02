<?php
// Complete error suppression to prevent unwanted output
error_reporting(0);
ini_set('display_errors', 0);
ini_set('log_errors', 0);

// Buffer output to catch any accidental output
ob_start();

// Get leadership data for footer (without images)
require_once 'db.php';
try {
    $leadership = $db->query("SELECT * FROM leadership WHERE is_active = 1 ORDER BY order_position ASC LIMIT 4");
} catch (Exception $e) {
    $leadership = [];
}

// Clean any buffered output
ob_end_clean();
?>

<!-- Enhanced Ultimate Footer - Clean Version -->
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
                                <li><a href="leadership.php"><i class="fas fa-users"></i> Leadership</a></li>
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
                                <i class="fas fa-paper-plane"></i>
                                Subscribe
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
                            <div class="pastor-contact">
                                <a href="https://wa.me/256753244480" target="_blank" class="pastor-whatsapp">
                                    <i class="fab fa-whatsapp"></i>
                                    <span>Pastor WhatsApp</span>
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
    animation: floatParticle 20s infinite linear;
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
    animation: shimmerFooter 25s infinite;
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

.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 15px;
}

.row {
    display: flex;
    flex-wrap: wrap;
    margin: 0 -15px;
}

.col-lg-1 { flex: 0 0 8.333333%; }
.col-lg-2 { flex: 0 0 16.666667%; }
.col-lg-3 { flex: 0 0 25%; }
.col-lg-4 { flex: 0 0 33.333333%; }
.col-lg-6 { flex: 0 0 50%; }
.col-md-6 { flex: 0 0 50%; }

/* Church Branding */
.footer-brand {
    text-align: center;
}

.brand-logo img {
    height: 60px;
    width: auto;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.1);
    padding: 8px;
    margin-bottom: 1.5rem;
    box-shadow: 0 0 20px rgba(251, 191, 36, 0.3);
    transition: all 0.3s ease;
}

.brand-logo img:hover {
    transform: scale(1.05);
    box-shadow: 0 0 30px rgba(251, 191, 36, 0.5);
}

.brand-info h3 {
    font-family: 'Playfair Display', serif;
    font-size: 1.8rem;
    font-weight: 700;
    color: #fbbf24;
    margin-bottom: 0.5rem;
}

.brand-motto {
    font-family: 'Great Vibes', cursive;
    font-size: 1.2rem;
    color: #fbbf24;
    margin-bottom: 1rem;
    opacity: 0.9;
}

.brand-description {
    font-size: 0.95rem;
    line-height: 1.6;
    color: rgba(255, 255, 255, 0.8);
    max-width: 300px;
    margin: 0 auto;
}

.social-links {
    display: flex;
    justify-content: center;
    gap: 1rem;
    margin-top: 1.5rem;
}

.social-link {
    width: 45px;
    height: 45px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #ffffff;
    text-decoration: none;
    transition: all 0.3s ease;
    font-size: 1.1rem;
}

.social-link:hover {
    background: #fbbf24;
    transform: scale(1.1) rotate(5deg);
    box-shadow: 0 10px 25px rgba(251, 191, 36, 0.4);
}

.social-link.facebook:hover { background: #1877f2; }
.social-link.twitter:hover { background: #1da1f2; }
.social-link.instagram:hover { background: linear-gradient(45deg, #f09433, #e6683c, #dc2743, #cc2366, #bc1888); }
.social-link.youtube:hover { background: #ff0000; }
.social-link.whatsapp:hover { background: #25d366; }

/* Footer Sections */
.footer-section {
    margin-bottom: 2rem;
}

.section-title {
    font-family: 'Playfair Display', serif;
    font-size: 1.3rem;
    font-weight: 700;
    color: #fbbf24;
    margin-bottom: 1.5rem;
    position: relative;
}

.section-title::after {
    content: '';
    position: absolute;
    bottom: -8px;
    left: 0;
    width: 50px;
    height: 2px;
    background: #fbbf24;
    border-radius: 1px;
}

.footer-links {
    list-style: none;
    padding: 0;
    margin: 0;
}

.footer-links li {
    margin-bottom: 0.8rem;
}

.footer-links a {
    color: rgba(255, 255, 255, 0.9);
    text-decoration: none;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 0.5rem;
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
    gap: 1.5rem;
}

.contact-item {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
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
    font-size: 1rem;
}

.contact-text h5 {
    font-size: 1rem;
    font-weight: 600;
    color: #fbbf24;
    margin-bottom: 0.5rem;
}

.contact-text p {
    font-size: 0.9rem;
    color: rgba(255, 255, 255, 0.8);
    margin: 0;
    line-height: 1.4;
}

/* Service Times */
.service-times {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.service-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem;
    background: rgba(255, 255, 255, 0.05);
    border-radius: 10px;
    border: 1px solid rgba(251, 191, 36, 0.2);
    transition: all 0.3s ease;
}

.service-item:hover {
    background: rgba(251, 191, 36, 0.1);
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

/* Newsletter Section */
.footer-newsletter {
    background: rgba(255, 255, 255, 0.03);
    padding: 3rem 0;
    border-top: 1px solid rgba(255, 255, 255, 0.1);
}

.newsletter-content {
    text-align: center;
    max-width: 500px;
    margin: 0 auto;
}

.newsletter-title {
    font-family: 'Playfair Display', serif;
    font-size: 2rem;
    font-weight: 700;
    color: #fbbf24;
    margin-bottom: 1rem;
}

.newsletter-description {
    font-size: 1rem;
    color: rgba(255, 255, 255, 0.9);
    margin-bottom: 2rem;
    line-height: 1.6;
}

.newsletter-form {
    display: flex;
    gap: 1rem;
    margin-bottom: 2rem;
}

.form-group {
    flex: 1;
}

.form-control {
    width: 100%;
    padding: 12px 15px;
    border: 2px solid rgba(251, 191, 36, 0.3);
    border-radius: 25px;
    background: rgba(255, 255, 255, 0.1);
    color: #ffffff;
    font-size: 0.95rem;
    transition: all 0.3s ease;
}

.form-control:focus {
    outline: none;
    border-color: #fbbf24;
    box-shadow: 0 0 20px rgba(251, 191, 36, 0.3);
    background: rgba(255, 255, 255, 0.15);
}

.form-control::placeholder {
    color: rgba(255, 255, 255, 0.6);
}

.btn-newsletter {
    background: #fbbf24;
    color: #0f172a;
    border: none;
    padding: 12px 25px;
    border-radius: 25px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    white-space: nowrap;
}

.btn-newsletter:hover {
    background: #f59e0b;
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(251, 191, 36, 0.4);
}

.newsletter-benefits {
    display: flex;
    justify-content: center;
    gap: 2rem;
    flex-wrap: wrap;
}

.benefit-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.85rem;
    color: rgba(255, 255, 255, 0.7);
}

.benefit-item i {
    color: #fbbf24;
}

/* Bottom Section */
.footer-bottom {
    background: rgba(0, 0, 0, 0.3);
    padding: 2rem 0;
    border-top: 1px solid rgba(255, 255, 255, 0.1);
}

.copyright {
    margin-bottom: 1rem;
}

.legal-links {
    display: flex;
    gap: 1rem;
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
    text-align: center;
}

.designer-credit {
    margin-bottom: 1rem;
    font-size: 1rem;
}

.designer-text {
    color: rgba(255, 255, 255, 0.9);
}

.designer-text i {
    color: #fbbf24;
    margin-right: 0.5rem;
}

.designer-link {
    color: #fbbf24;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s ease;
}

.designer-link:hover {
    color: #ffffff;
    text-decoration: underline;
}

.developer-contact {
    display: flex;
    justify-content: center;
    gap: 1rem;
    margin-top: 1rem;
}

.developer-whatsapp,
.pastor-whatsapp {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    background: #25d366;
    color: #ffffff;
    padding: 0.5rem 1rem;
    border-radius: 25px;
    text-decoration: none;
    transition: all 0.3s ease;
    font-size: 0.85rem;
}

.developer-whatsapp:hover,
.pastor-whatsapp:hover {
    background: #128c7e;
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
    min-width: 200px;
}

.whatsapp-float:hover .whatsapp-tooltip {
    opacity: 1;
    visibility: visible;
    transform: translateY(10px);
}

.tooltip-content h6 {
    font-size: 0.9rem;
    font-weight: 600;
    color: #25d366;
    margin-bottom: 5px;
}

.tooltip-content p {
    font-size: 0.85rem;
    margin-bottom: 5px;
    line-height: 1.4;
}

.tooltip-content small {
    font-size: 0.75rem;
    color: #6b7280;
}

/* Back to Top Button */
.back-to-top {
    position: fixed;
    bottom: 30px;
    left: 30px;
    width: 50px;
    height: 50px;
    background: #fbbf24;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #0f172a;
    cursor: pointer;
    z-index: 1000;
    transition: all 0.3s ease;
    opacity: 0;
    visibility: hidden;
    box-shadow: 0 10px 25px rgba(251, 191, 36, 0.3);
}

.back-to-top.visible {
    opacity: 1;
    visibility: visible;
}

.back-to-top:hover {
    transform: translateY(-3px);
    box-shadow: 0 15px 35px rgba(251, 191, 36, 0.4);
}

/* Responsive Design */
@media (max-width: 768px) {
    .container {
        padding: 0 10px;
    }

    .row {
        margin: 0 -10px;
    }

    .col-md-6 {
        flex: 0 0 50%;
    }

    .brand-description {
        max-width: 100%;
    }

    .social-links {
        flex-wrap: wrap;
        justify-content: center;
    }

    .contact-info {
        gap: 1rem;
    }

    .service-times {
        gap: 0.8rem;
    }

    .service-item {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
        padding: 0.8rem;
    }

    .newsletter-form {
        flex-direction: column;
        gap: 0.8rem;
    }

    .newsletter-benefits {
        flex-direction: column;
        gap: 0.8rem;
    }

    .developer-contact {
        flex-direction: column;
        gap: 0.8rem;
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

    .whatsapp-tooltip {
        bottom: 60px;
        right: -10px;
        min-width: 180px;
        font-size: 0.8rem;
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
            particle.style.animationDuration = (20 + Math.random() * 10) + 's';
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

// Initialize particles
document.addEventListener('DOMContentLoaded', function() {
    createFooterParticles();
});
</script>
