<?php
// Complete error suppression to prevent unwanted output
error_reporting(0);
ini_set('display_errors', 0);
ini_set('log_errors', 0);

// Buffer output to catch any accidental output
ob_start();

// Clean any buffered output
ob_end_clean();
?>

<!-- Enhanced Ultimate Footer with Fire Effects -->
<footer class="ultimate-footer">
    <!-- Fire Particles Background -->
    <div class="fire-particles" id="fireParticles"></div>
    
    <!-- Fire Glow Effect -->
    <div class="fire-glow"></div>
    
    <!-- Animated Flames -->
    <div class="flames-container" id="flamesContainer"></div>
    
    <!-- Main Footer Content -->
    <div class="footer-content">
        <!-- Top Section -->
        <div class="footer-top">
            <div class="container">
                <div class="row">
                    <!-- Church Branding -->
                    <div class="col-lg-5 col-md-6">
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
                                    Where faith meets divine purpose and lives are transformed by God's grace.
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
                                <a href="https://www.youtube.com/@Musasizifaty" target="_blank" class="social-link youtube">
                                    <i class="fab fa-youtube"></i>
                                </a>
                                <a href="https://www.tiktok.com/@salem1dominionchurch?_r=1&_t=ZS-95E1n40LieS" target="_blank" class="social-link tiktok">
                                    <i class="fab fa-tiktok"></i>
                                </a>
                                <a href="https://wa.me/256753244480" target="_blank" class="social-link whatsapp">
                                    <i class="fab fa-whatsapp"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Quick Links -->
                    <div class="col-lg-3 col-md-6">
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
                    
                    <!-- Newsletter -->
                    <div class="col-lg-4 col-md-6">
                        <div class="footer-section" data-aos="fade-up" data-aos-delay="200">
                            <h4 class="section-title">Stay Connected</h4>
                            <p class="newsletter-text">
                                Subscribe to receive weekly updates, spiritual inspiration, and event notifications.
                            </p>
                            <form class="newsletter-form" action="newsletter_subscribe.php" method="POST">
                                <div class="form-group">
                                    <input type="email" name="email" class="form-control" placeholder="Enter your email" required>
                                    <button type="submit" class="btn-newsletter">
                                        <i class="fas fa-fire"></i> Subscribe
                                    </button>
                                </div>
                            </form>
                            <div class="newsletter-benefits">
                                <div class="benefit-item">
                                    <i class="fas fa-fire"></i>
                                    <span>Weekly Sermons</span>
                                </div>
                                <div class="benefit-item">
                                    <i class="fas fa-calendar-alt"></i>
                                    <span>Event Updates</span>
                                </div>
                                <div class="benefit-item">
                                    <i class="fas fa-bible"></i>
                                    <span>Spiritual Growth</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Bottom Section -->
        <div class="footer-bottom">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <div class="copyright">
                            <p>&copy; <?php echo date('Y'); ?> Salem Dominion Ministries. All rights reserved.</p>
                            <div class="legal-links">
                                <a href="#">Privacy Policy</a>
                                <a href="#">Terms of Service</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="footer-credits">
                            <div class="designer-credit">
                                <i class="fas fa-crown"></i>
                                <span>Designed & Managed by</span>
                                <a href="https://reaganotema.com" target="_blank" class="designer-link">Mr. Reagan Otema</a>
                            </div>
                            <div class="developer-contact">
                                <a href="https://wa.me/256772514889" target="_blank" class="developer-whatsapp">
                                    <i class="fab fa-whatsapp"></i>
                                    <span>Developer: +256 772 514 889</span>
                                </a>
                                <a href="mailto:reaganotemas@gmail.com" class="developer-email">
                                    <i class="fas fa-envelope"></i>
                                    <span>reaganotemas@gmail.com</span>
                                </a>
                                <a href="https://wa.me/256753244480" target="_blank" class="pastor-whatsapp">
                                    <i class="fab fa-whatsapp"></i>
                                    <span>Pastor: +256 753 244 480</span>
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
/* Enhanced Ultimate Footer with Fire Effects */
.ultimate-footer {
    position: relative;
    background: linear-gradient(135deg, #0f0a0a 0%, #1a0a0a 25%, #2d1b1b 50%, #1a0a0a 75%, #0f0a0a 100%);
    color: #ffffff;
    overflow: hidden;
    margin-top: 100px;
}

/* Fire Particles */
.fire-particles {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    overflow: hidden;
    z-index: 1;
}

.fire-particle {
    position: absolute;
    width: 3px;
    height: 3px;
    border-radius: 50%;
    opacity: 0;
    animation: fireFloat 8s infinite;
}

@keyframes fireFloat {
    0% {
        transform: translateY(100vh) scale(0);
        opacity: 0;
    }
    10% {
        opacity: 1;
    }
    50% {
        transform: translateY(50vh) scale(1);
    }
    90% {
        opacity: 1;
    }
    100% {
        transform: translateY(0) scale(0.5);
        opacity: 0;
    }
}

/* Fire Glow Effect */
.fire-glow {
    position: absolute;
    bottom: 0;
    left: 0;
    width: 100%;
    height: 300px;
    background: linear-gradient(to top, 
        rgba(180, 30, 30, 0.3) 0%, 
        rgba(220, 50, 20, 0.15) 30%, 
        rgba(255, 100, 50, 0.05) 60%, 
        transparent 100%);
    animation: fireGlow 4s ease-in-out infinite alternate;
    z-index: 2;
}

@keyframes fireGlow {
    0% { opacity: 0.6; transform: scaleY(1); }
    100% { opacity: 1; transform: scaleY(1.1); }
}

/* Flames Container */
.flames-container {
    position: absolute;
    bottom: 0;
    left: 0;
    width: 100%;
    height: 200px;
    overflow: hidden;
    z-index: 3;
    pointer-events: none;
}

.flame {
    position: absolute;
    bottom: 0;
    width: 20px;
    height: 100px;
    background: linear-gradient(to top, 
        rgba(255, 50, 0, 0.8) 0%, 
        rgba(255, 150, 0, 0.4) 50%, 
        rgba(255, 200, 0, 0.1) 100%);
    border-radius: 50% 50% 0 0;
    animation: flameRise 3s ease-in-out infinite;
    filter: blur(2px);
}

@keyframes flameRise {
    0%, 100% {
        transform: translateY(0) scaleY(1);
        opacity: 0.7;
    }
    50% {
        transform: translateY(-30px) scaleY(1.2);
        opacity: 1;
    }
}

/* Footer Content */
.footer-content {
    position: relative;
    z-index: 10;
}

/* Top Section */
.footer-top {
    padding: 40px 0 30px;
    border-bottom: 1px solid rgba(255, 100, 50, 0.2);
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

.col-lg-3 { flex: 0 0 25%; }
.col-lg-4 { flex: 0 0 33.333333%; }
.col-lg-5 { flex: 0 0 41.666667%; }
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
    margin-bottom: 1rem;
    box-shadow: 0 0 20px rgba(255, 100, 50, 0.5), 0 0 40px rgba(255, 50, 0, 0.3);
    transition: all 0.3s ease;
    animation: logoGlow 3s ease-in-out infinite alternate;
}

@keyframes logoGlow {
    0% { box-shadow: 0 0 30px rgba(255, 100, 50, 0.5), 0 0 60px rgba(255, 50, 0, 0.3); }
    100% { box-shadow: 0 0 40px rgba(255, 100, 50, 0.7), 0 0 80px rgba(255, 50, 0, 0.5); }
}

.brand-logo img:hover {
    transform: scale(1.05);
}

.brand-info h3 {
    font-family: 'Playfair Display', serif;
    font-size: 1.5rem;
    font-weight: 700;
    background: linear-gradient(135deg, #ff6b35, #ff3535, #ff6b35);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    margin-bottom: 0.3rem;
    animation: textFire 3s ease-in-out infinite alternate;
}

@keyframes textFire {
    0% { filter: brightness(1); }
    100% { filter: brightness(1.2); }
}

.brand-motto {
    font-family: 'Great Vibes', cursive;
    font-size: 1.1rem;
    color: #ff6b35;
    margin-bottom: 0.5rem;
    opacity: 0.9;
}

.brand-description {
    font-size: 0.85rem;
    line-height: 1.5;
    color: rgba(255, 255, 255, 0.8);
    max-width: 350px;
    margin: 0 auto;
}

.social-links {
    display: flex;
    justify-content: center;
    gap: 1rem;
    margin-top: 1.5rem;
}

.social-link {
    width: 38px;
    height: 38px;
    background: rgba(255, 100, 50, 0.2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #ffffff;
    text-decoration: none;
    transition: all 0.3s ease;
    font-size: 0.9rem;
    border: 1px solid rgba(255, 100, 50, 0.3);
}

.social-link:hover {
    background: linear-gradient(135deg, #ff3535, #ff6b35);
    transform: scale(1.1) rotate(5deg);
    box-shadow: 0 0 20px rgba(255, 100, 50, 0.5);
}

.social-link.tiktok {
    background: rgba(0, 0, 0, 0.3);
}

.social-link.tiktok:hover {
    background: linear-gradient(135deg, #00f2ea, #ff0050);
}

/* Footer Sections */
.footer-section {
    margin-bottom: 1.5rem;
}

.section-title {
    font-family: 'Playfair Display', serif;
    font-size: 1.2rem;
    font-weight: 700;
    background: linear-gradient(135deg, #ff6b35, #ff3535);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    margin-bottom: 1rem;
    position: relative;
}

.section-title::after {
    content: '';
    position: absolute;
    bottom: -8px;
    left: 0;
    width: 50px;
    height: 2px;
    background: linear-gradient(90deg, #ff3535, #ff6b35);
    border-radius: 1px;
}

.footer-links {
    list-style: none;
    padding: 0;
    margin: 0;
}

.footer-links li {
    margin-bottom: 0.5rem;
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
    color: #ff6b35;
    transform: translateX(5px);
}

.footer-links a i {
    width: 16px;
    text-align: center;
    color: #ff6b35;
}

/* Newsletter */
.newsletter-text {
    font-size: 0.85rem;
    color: rgba(255, 255, 255, 0.8);
    margin-bottom: 1rem;
    line-height: 1.5;
}

.newsletter-form {
    display: flex;
    gap: 0.8rem;
    margin-bottom: 1.5rem;
}

.form-group {
    flex: 1;
    display: flex;
    gap: 0.8rem;
}

.form-control {
    flex: 1;
    padding: 12px 15px;
    border: 2px solid rgba(255, 100, 50, 0.3);
    border-radius: 25px;
    background: rgba(255, 255, 255, 0.05);
    color: #ffffff;
    font-size: 0.95rem;
    transition: all 0.3s ease;
}

.form-control:focus {
    outline: none;
    border-color: #ff6b35;
    box-shadow: 0 0 15px rgba(255, 100, 50, 0.3);
    background: rgba(255, 255, 255, 0.1);
}

.form-control::placeholder {
    color: rgba(255, 255, 255, 0.5);
}

.btn-newsletter {
    background: linear-gradient(135deg, #ff3535, #ff6b35);
    color: #ffffff;
    border: none;
    padding: 12px 20px;
    border-radius: 25px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    white-space: nowrap;
}

.btn-newsletter:hover {
    background: linear-gradient(135deg, #ff6b35, #ff3535);
    transform: translateY(-2px);
    box-shadow: 0 5px 20px rgba(255, 100, 50, 0.4);
}

.newsletter-benefits {
    display: flex;
    flex-direction: column;
    gap: 0.8rem;
}

.benefit-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.9rem;
    color: rgba(255, 255, 255, 0.8);
}

.benefit-item i {
    color: #ff6b35;
    font-size: 0.8rem;
}

/* Bottom Section */
.footer-bottom {
    background: rgba(0, 0, 0, 0.4);
    padding: 1.5rem 0;
    border-top: 1px solid rgba(255, 100, 50, 0.2);
}

.copyright {
    margin-bottom: 1rem;
}

.copyright p {
    font-size: 0.9rem;
    color: rgba(255, 255, 255, 0.7);
    margin-bottom: 0.5rem;
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
    color: #ff6b35;
}

.footer-credits {
    text-align: center;
}

.designer-credit {
    margin-bottom: 1rem;
    font-size: 0.95rem;
    color: rgba(255, 255, 255, 0.8);
}

.designer-credit i {
    color: #ff6b35;
    margin-right: 0.5rem;
}

.designer-link {
    color: #ff6b35;
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
    flex-wrap: wrap;
}

.developer-whatsapp,
.pastor-whatsapp {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    background: linear-gradient(135deg, #25d366, #128c7e);
    color: #ffffff;
    padding: 0.5rem 1rem;
    border-radius: 25px;
    text-decoration: none;
    transition: all 0.3s ease;
    font-size: 0.85rem;
}

.developer-whatsapp:hover,
.pastor-whatsapp:hover {
    background: linear-gradient(135deg, #128c7e, #075e54);
    transform: scale(1.05);
    box-shadow: 0 5px 15px rgba(37, 211, 102, 0.3);
}

.developer-email {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    background: linear-gradient(135deg, #ea4335, #c5221f);
    color: #ffffff;
    padding: 0.5rem 1rem;
    border-radius: 25px;
    text-decoration: none;
    transition: all 0.3s ease;
    font-size: 0.85rem;
}

.developer-email:hover {
    background: linear-gradient(135deg, #c5221f, #a31815);
    transform: scale(1.05);
    box-shadow: 0 5px 15px rgba(234, 67, 53, 0.3);
    color: #ffffff;
}

/* WhatsApp Floating Button */
.whatsapp-float {
    position: fixed;
    bottom: 30px;
    right: 30px;
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, #25d366, #128c7e);
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
    background: linear-gradient(135deg, #ff6b35, #ff3535);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #ffffff;
    cursor: pointer;
    z-index: 1000;
    transition: all 0.3s ease;
    opacity: 0;
    visibility: hidden;
    box-shadow: 0 10px 25px rgba(255, 100, 50, 0.3);
}

.back-to-top.visible {
    opacity: 1;
    visibility: visible;
}

.back-to-top:hover {
    transform: translateY(-3px);
    box-shadow: 0 15px 35px rgba(255, 100, 50, 0.4);
}

/* Responsive Design */
@media (max-width: 768px) {
    .container {
        padding: 0 10px;
    }

    .row {
        margin: 0 -10px;
    }

    .col-lg-3, .col-lg-4, .col-lg-5 {
        flex: 0 0 100%;
    }

    .col-md-6 {
        flex: 0 0 100%;
        margin-bottom: 2rem;
    }

    .brand-description {
        max-width: 100%;
    }

    .social-links {
        flex-wrap: wrap;
        justify-content: center;
    }

    .newsletter-form {
        flex-direction: column;
        gap: 0.8rem;
    }

    .form-group {
        flex-direction: column;
    }

    .newsletter-benefits {
        flex-direction: row;
        flex-wrap: wrap;
        justify-content: center;
    }

    .developer-contact {
        flex-direction: column;
        gap: 0.8rem;
        align-items: center;
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

    .brand-info h3 {
        font-size: 1.5rem;
    }

    .section-title {
        font-size: 1.2rem;
    }
}
</style>

<script>
// Create fire particles
function createFireParticles() {
    const container = document.getElementById('fireParticles');
    const particleCount = 30;
    
    if (container) {
        for (let i = 0; i < particleCount; i++) {
            const particle = document.createElement('div');
            particle.className = 'fire-particle';
            particle.style.left = Math.random() * 100 + '%';
            particle.style.animationDelay = Math.random() * 8 + 's';
            particle.style.animationDuration = (6 + Math.random() * 4) + 's';
            
            // Random fire colors
            const colors = ['rgba(255, 50, 0, ', 'rgba(255, 100, 0, ', 'rgba(255, 150, 0, ', 'rgba(255, 200, 0, '];
            const color = colors[Math.floor(Math.random() * colors.length)];
            const opacity = 0.3 + Math.random() * 0.7;
            particle.style.background = color + opacity + ')';
            particle.style.boxShadow = '0 0 10px ' + color + opacity + ')';
            
            container.appendChild(particle);
        }
    }
}

// Create animated flames
function createFlames() {
    const container = document.getElementById('flamesContainer');
    const flameCount = 15;
    
    if (container) {
        for (let i = 0; i < flameCount; i++) {
            const flame = document.createElement('div');
            flame.className = 'flame';
            flame.style.left = (i * (100 / flameCount) + Math.random() * 5) + '%';
            flame.style.width = (15 + Math.random() * 15) + 'px';
            flame.style.height = (80 + Math.random() * 60) + 'px';
            flame.style.animationDelay = Math.random() * 3 + 's';
            flame.style.animationDuration = (2 + Math.random() * 2) + 's';
            
            container.appendChild(flame);
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

// Initialize fire effects
document.addEventListener('DOMContentLoaded', function() {
    createFireParticles();
    createFlames();
});
</script>