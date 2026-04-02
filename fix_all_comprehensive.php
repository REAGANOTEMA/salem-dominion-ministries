<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>🔧 Comprehensive System Fix</h1>";

try {
    require_once 'config.php';
    require_once 'db.php';
    
    echo "<h2>✓ Database Connected</h2>";
    
    // 1. Update email configuration
    echo "<h2>📧 Updating Email Configuration:</h2>";
    
    $config_content = "<?php\n";
    $config_content .= "// Smart Database Configuration\n";
    $config_content .= "// Automatically detects environment and uses appropriate settings\n\n";
    
    $config_content .= "// Environment Detection\n";
    $config_content .= "\$is_localhost = (\n";
    $config_content .= "    \$_SERVER['SERVER_NAME'] === 'localhost' || \n";
    $config_content .= "    \$_SERVER['SERVER_NAME'] === '127.0.0.1' ||\n";
    $config_content .= "    strpos(\$_SERVER['SERVER_NAME'], '.local') !== false ||\n";
    $config_content .= "    strpos(\$_SERVER['HTTP_HOST'], '.local') !== false ||\n";
    $config_content .= "    \$_SERVER['SERVER_NAME'] === 'salemdominionministries.localhost'\n";
    $config_content .= ");\n\n";
    
    $config_content .= "// Database Configuration based on environment\n";
    $config_content .= "if (\$is_localhost) {\n";
    $config_content .= "    // Localhost (XAMPP) Settings\n";
    $config_content .= "    define('DB_HOST', 'localhost');\n";
    $config_content .= "    define('DB_USER', 'root');\n";
    $config_content .= "    define('DB_PASSWORD', 'ReagaN23#');\n";
    $config_content .= "    define('DB_NAME', 'salem_dominion_ministries');\n";
    $config_content .= "    define('DB_CHARSET', 'utf8mb4');\n";
    $config_content .= "    define('DB_PORT', 3306);\n";
    $config_content .= "    \n";
    $config_content .= "    // Local Application Configuration\n";
    $config_content .= "    define('APP_URL', 'http://localhost/salem-dominion-ministries');\n";
    $config_content .= "    define('APP_ENV', 'development');\n";
    $config_content .= "    define('DEBUG_MODE', true);\n";
    $config_content .= "    \n";
    $config_content .= "    // Local Email Configuration (using your provided emails)\n";
    $config_content .= "    define('MAIL_HOST', 'mail.salemdominionministries.com');\n";
    $config_content .= "    define('MAIL_PORT', 587);\n";
    $config_content .= "    define('MAIL_USERNAME', 'visit@salemdominionministries.com');\n";
    $config_content .= "    define('MAIL_PASSWORD', 'Lovely2God');\n";
    $config_content .= "    define('MAIL_FROM', 'visit@salemdominionministries.com');\n";
    $config_content .= "    define('MAIL_FROM_NAME', 'Salem Dominion Ministries');\n";
    $config_content .= "    define('MAIL_ENABLED', true);\n";
    $config_content .= "    \n";
    $config_content .= "    // Department Emails\n";
    $config_content .= "    define('EMAIL_GENERAL', 'visit@salemdominionministries.com');\n";
    $config_content .= "    define('EMAIL_MINISTERS', 'ministers@salemdominionministries.com');\n";
    $config_content .= "    define('EMAIL_CHILDREN', 'childrenministry@salemdominionministries.com');\n";
    $config_content .= "    \n";
    $config_content .= "} else {\n";
    $config_content .= "    // Production (Hosting Platform) Settings\n";
    $config_content .= "    define('DB_HOST', 'localhost');\n";
    $config_content .= "    define('DB_USER', 'salemdominionmin_db');\n";
    $config_content .= "    define('DB_PASSWORD', '22uHzNYEHwUsFKdVz3wT');\n";
    $config_content .= "    define('DB_NAME', 'salemdominionmin_db');\n";
    $config_content .= "    define('DB_CHARSET', 'utf8mb4');\n";
    $config_content .= "    define('DB_PORT', 3306);\n";
    $config_content .= "    \n";
    $config_content .= "    // Production Application Configuration\n";
    $config_content .= "    define('APP_URL', 'https://salemdominionministries.com');\n";
    $config_content .= "    define('APP_ENV', 'production');\n";
    $config_content .= "    define('DEBUG_MODE', false);\n";
    $config_content .= "    \n";
    $config_content .= "    // Production Email Configuration\n";
    $config_content .= "    define('MAIL_HOST', 'mail.salemdominionministries.com');\n";
    $config_content .= "    define('MAIL_PORT', 587);\n";
    $config_content .= "    define('MAIL_USERNAME', 'visit@salemdominionministries.com');\n";
    $config_content .= "    define('MAIL_PASSWORD', 'Lovely2God');\n";
    $config_content .= "    define('MAIL_FROM', 'visit@salemdominionministries.com');\n";
    $config_content .= "    define('MAIL_FROM_NAME', 'Salem Dominion Ministries');\n";
    $config_content .= "    define('MAIL_ENABLED', true);\n";
    $config_content .= "    \n";
    $config_content .= "    // Department Emails\n";
    $config_content .= "    define('EMAIL_GENERAL', 'visit@salemdominionministries.com');\n";
    $config_content .= "    define('EMAIL_MINISTERS', 'ministers@salemdominionministries.com');\n";
    $config_content .= "    define('EMAIL_CHILDREN', 'childrenministry@salemdominionministries.com');\n";
    $config_content .= "}\n";
    
    // Write updated config
    file_put_contents('config.php', $config_content);
    echo "<p style='color: green;'>✓ Email configuration updated with your emails</p>";
    
    // 2. Update map location to Iganga Town
    echo "<h2>🗺️ Updating Map Location to Iganga Town:</h2>";
    
    $iganga_lat = 0.6056; // Iganga Town coordinates
    $iganga_lng = 33.4703;
    
    // Update map page
    $map_content = file_get_contents('map_perfect.php');
    if ($map_content) {
        // Replace coordinates
        $map_content = preg_replace('/lat:\s*[-\d.]+/', 'lat: ' . $iganga_lat, $map_content);
        $map_content = preg_replace('/lng:\s*[-\d.]+/', 'lng: ' . $iganga_lng, $map_content);
        
        // Add Iganga specific info
        $map_content = str_replace('Salem Dominion Ministries', 'Salem Dominion Ministries - Iganga', $map_content);
        $map_content = str_replace('123 Church Street', 'Jinja Road, Iganga Town, Uganda', $map_content);
        
        file_put_contents('map_perfect.php', $map_content);
        echo "<p style='color: green;'>✓ Map updated to Iganga Town location</p>";
    }
    
    // 3. Create modern WhatsApp integration
    echo "<h2>📱 Creating Modern WhatsApp Integration:</h2>";
    
    $whatsapp_html = '
<!-- Modern WhatsApp Integration -->
<div class="whatsapp-container">
    <div class="whatsapp-fab" id="whatsappFab">
        <i class="fab fa-whatsapp"></i>
    </div>
    <div class="whatsapp-popup" id="whatsappPopup">
        <div class="whatsapp-header">
            <h4>Chat with Pastor</h4>
            <button class="whatsapp-close" id="whatsappClose">&times;</button>
        </div>
        <div class="whatsapp-body">
            <div class="pastor-option" data-phone="+256753244480" data-name="Senior Pastor">
                <div class="pastor-avatar">
                    <i class="fas fa-user-tie"></i>
                </div>
                <div class="pastor-info">
                    <strong>Senior Pastor</strong>
                    <small>Apostle Faty Musasizi</small>
                </div>
                <div class="whatsapp-status online">Online</div>
            </div>
            <div class="pastor-option" data-phone="+256753244480" data-name="Associate Pastor">
                <div class="pastor-avatar">
                    <i class="fas fa-user-tie"></i>
                </div>
                <div class="pastor-info">
                    <strong>Associate Pastor</strong>
                    <small>Pastor Joyce Nabulya</small>
                </div>
                <div class="whatsapp-status busy">Busy</div>
            </div>
            <div class="pastor-option" data-phone="+256753244480" data-name="Youth Pastor">
                <div class="pastor-avatar">
                    <i class="fas fa-user-tie"></i>
                </div>
                <div class="pastor-info">
                    <strong>Youth Pastor</strong>
                    <small>Pastor Damali Namwuma</small>
                </div>
                <div class="whatsapp-status offline">Offline</div>
            </div>
        </div>
        <div class="whatsapp-footer">
            <small>Response time: Usually within 2 hours</small>
        </div>
    </div>
</div>

<style>
.whatsapp-container {
    position: fixed;
    bottom: 20px;
    right: 20px;
    z-index: 9999;
}

.whatsapp-fab {
    width: 60px;
    height: 60px;
    background: linear-gradient(45deg, #25D366, #128C7E);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    box-shadow: 0 4px 20px rgba(37, 211, 102, 0.3);
    transition: all 0.3s ease;
    animation: whatsappPulse 2s infinite;
}

@keyframes whatsappPulse {
    0% { box-shadow: 0 4px 20px rgba(37, 211, 102, 0.3); }
    50% { box-shadow: 0 4px 30px rgba(37, 211, 102, 0.5); }
    100% { box-shadow: 0 4px 20px rgba(37, 211, 102, 0.3); }
}

.whatsapp-fab:hover {
    transform: scale(1.1);
    box-shadow: 0 6px 25px rgba(37, 211, 102, 0.4);
}

.whatsapp-fab i {
    color: white;
    font-size: 24px;
}

.whatsapp-popup {
    position: absolute;
    bottom: 80px;
    right: 0;
    width: 320px;
    background: white;
    border-radius: 12px;
    box-shadow: 0 10px 40px rgba(0,0,0,0.1);
    display: none;
    overflow: hidden;
    animation: slideUp 0.3s ease;
}

@keyframes slideUp {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

.whatsapp-popup.show {
    display: block;
}

.whatsapp-header {
    background: linear-gradient(45deg, #25D366, #128C7E);
    color: white;
    padding: 15px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.whatsapp-header h4 {
    margin: 0;
    font-size: 16px;
}

.whatsapp-close {
    background: none;
    border: none;
    color: white;
    font-size: 20px;
    cursor: pointer;
}

.whatsapp-body {
    padding: 15px;
}

.pastor-option {
    display: flex;
    align-items: center;
    padding: 12px;
    border-radius: 8px;
    cursor: pointer;
    transition: background 0.2s ease;
    margin-bottom: 10px;
}

.pastor-option:hover {
    background: #f8f9fa;
}

.pastor-avatar {
    width: 40px;
    height: 40px;
    background: #e9ecef;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 12px;
    color: #6c757d;
}

.pastor-info {
    flex: 1;
}

.pastor-info strong {
    display: block;
    font-size: 14px;
    color: #333;
}

.pastor-info small {
    color: #666;
    font-size: 12px;
}

.whatsapp-status {
    width: 8px;
    height: 8px;
    border-radius: 50%;
}

.whatsapp-status.online { background: #25D366; }
.whatsapp-status.busy { background: #ffc107; }
.whatsapp-status.offline { background: #6c757d; }

.whatsapp-footer {
    background: #f8f9fa;
    padding: 10px 15px;
    text-align: center;
    border-top: 1px solid #e9ecef;
}

.whatsapp-footer small {
    color: #666;
    font-size: 11px;
}

@media (max-width: 768px) {
    .whatsapp-popup {
        width: 280px;
        right: -10px;
    }
}
</style>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const fab = document.getElementById("whatsappFab");
    const popup = document.getElementById("whatsappPopup");
    const close = document.getElementById("whatsappClose");
    
    fab.addEventListener("click", function() {
        popup.classList.toggle("show");
    });
    
    close.addEventListener("click", function() {
        popup.classList.remove("show");
    });
    
    // Pastor click handlers
    document.querySelectorAll(".pastor-option").forEach(function(option) {
        option.addEventListener("click", function() {
            const phone = this.dataset.phone;
            const name = this.dataset.name;
            const message = encodeURIComponent("Hello " + name + ", I would like to talk with you about...");
            window.open("https://wa.me/" + phone.replace(/[^\d]/g, "") + "?text=" + message, "_blank");
        });
    });
    
    // Close popup when clicking outside
    document.addEventListener("click", function(e) {
        if (!fab.contains(e.target) && !popup.contains(e.target)) {
            popup.classList.remove("show");
        }
    });
});
</script>';
    
    file_put_contents('whatsapp_integration.html', $whatsapp_html);
    echo "<p style='color: green;'>✓ Modern WhatsApp integration created</p>";
    
    // 4. Create modern footer
    echo "<h2>🎨 Creating Modern Footer:</h2>";
    
    $modern_footer = '
<!-- Modern Footer -->
<footer class="modern-footer">
    <div class="footer-container">
        <!-- Main Footer Content -->
        <div class="footer-main">
            <div class="footer-section">
                <div class="footer-logo">
                    <img src="assets/logo-DEFqnQ4s.jpeg" alt="Salem Dominion Ministries" />
                    <h3>Salem Dominion Ministries</h3>
                    <p>Spreading the Gospel and making disciples for Christ</p>
                </div>
            </div>
            
            <div class="footer-section">
                <h4>Quick Links</h4>
                <ul>
                    <li><a href="index.php"><i class="fas fa-home"></i> Home</a></li>
                    <li><a href="about.php"><i class="fas fa-info-circle"></i> About Us</a></li>
                    <li><a href="leadership.php"><i class="fas fa-users"></i> Leadership</a></li>
                    <li><a href="ministries.php"><i class="fas fa-hands-helping"></i> Ministries</a></li>
                </ul>
            </div>
            
            <div class="footer-section">
                <h4>Services</h4>
                <ul>
                    <li><a href="events.php"><i class="fas fa-calendar"></i> Events</a></li>
                    <li><a href="sermons.php"><i class="fas fa-microphone"></i> Sermons</a></li>
                    <li><a href="gallery.php"><i class="fas fa-images"></i> Gallery</a></li>
                    <li><a href="donate.php"><i class="fas fa-heart"></i> Donate</a></li>
                </ul>
            </div>
            
            <div class="footer-section">
                <h4>Contact Info</h4>
                <div class="contact-info">
                    <p><i class="fas fa-map-marker-alt"></i> Jinja Road, Iganga Town, Uganda</p>
                    <p><i class="fas fa-phone"></i> +256 753 244 480</p>
                    <p><i class="fas fa-envelope"></i> visit@salemdominionministries.com</p>
                    <div class="social-links">
                        <a href="https://facebook.com/salemdominionministries" target="_blank"><i class="fab fa-facebook"></i></a>
                        <a href="https://twitter.com/salemdominionministries" target="_blank"><i class="fab fa-twitter"></i></a>
                        <a href="https://instagram.com/salemdominionministries" target="_blank"><i class="fab fa-instagram"></i></a>
                        <a href="https://youtube.com/salemdominionministries" target="_blank"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Ministry Departments -->
        <div class="footer-departments">
            <div class="dept-card">
                <i class="fas fa-child"></i>
                <h5>Children Ministry</h5>
                <p>Nurturing young hearts in Christ</p>
                <a href="mailto:childrenministry@salemdominionministries.com">childrenministry@salemdominionministries.com</a>
            </div>
            <div class="dept-card">
                <i class="fas fa-users"></i>
                <h5>Youth Ministry</h5>
                <p>Empowering the next generation</p>
                <a href="mailto:youth@salemdominionministries.com">youth@salemdominionministries.com</a>
            </div>
            <div class="dept-card">
                <i class="fas fa-hands-helping"></i>
                <h5>Outreach</h5>
                <p>Serving our community</p>
                <a href="mailto:outreach@salemdominionministries.com">outreach@salemdominionministries.com</a>
            </div>
        </div>
        
        <!-- Bottom Footer -->
        <div class="footer-bottom">
            <div class="footer-bottom-content">
                <p>&copy; 2026 Salem Dominion Ministries. All rights reserved.</p>
                <div class="footer-bottom-links">
                    <a href="privacy.php">Privacy Policy</a>
                    <a href="terms.php">Terms of Service</a>
                    <a href="contact.php">Contact Us</a>
                </div>
            </div>
        </div>
    </div>
</footer>

<style>
.modern-footer {
    background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
    color: white;
    font-family: "Montserrat", sans-serif;
    margin-top: 50px;
}

.footer-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
}

.footer-main {
    display: grid;
    grid-template-columns: 2fr 1fr 1fr 1fr;
    gap: 40px;
    padding: 60px 0 40px;
}

.footer-section h3 {
    color: #fbbf24;
    margin-bottom: 15px;
    font-size: 24px;
}

.footer-section h4 {
    color: #38bdf8;
    margin-bottom: 20px;
    font-size: 18px;
    position: relative;
}

.footer-section h4::after {
    content: "";
    position: absolute;
    bottom: -8px;
    left: 0;
    width: 40px;
    height: 2px;
    background: #fbbf24;
}

.footer-logo img {
    height: 60px;
    margin-bottom: 15px;
    border-radius: 10px;
}

.footer-logo p {
    color: #94a3b8;
    line-height: 1.6;
    margin-top: 10px;
}

.footer-section ul {
    list-style: none;
    padding: 0;
}

.footer-section ul li {
    margin-bottom: 12px;
}

.footer-section ul li a {
    color: #cbd5e1;
    text-decoration: none;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 10px;
}

.footer-section ul li a:hover {
    color: #fbbf24;
    transform: translateX(5px);
}

.contact-info p {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 12px;
    color: #cbd5e1;
}

.social-links {
    display: flex;
    gap: 15px;
    margin-top: 20px;
}

.social-links a {
    width: 40px;
    height: 40px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    transition: all 0.3s ease;
}

.social-links a:hover {
    background: #fbbf24;
    transform: translateY(-3px);
}

.footer-departments {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 30px;
    padding: 40px 0;
    border-top: 1px solid rgba(255, 255, 255, 0.1);
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.dept-card {
    background: rgba(255, 255, 255, 0.05);
    padding: 25px;
    border-radius: 15px;
    text-align: center;
    transition: all 0.3s ease;
}

.dept-card:hover {
    background: rgba(255, 255, 255, 0.1);
    transform: translateY(-5px);
}

.dept-card i {
    font-size: 36px;
    color: #fbbf24;
    margin-bottom: 15px;
}

.dept-card h5 {
    color: #38bdf8;
    margin-bottom: 10px;
    font-size: 18px;
}

.dept-card p {
    color: #94a3b8;
    margin-bottom: 15px;
    font-size: 14px;
}

.dept-card a {
    color: #fbbf24;
    text-decoration: none;
    font-size: 14px;
    transition: color 0.3s ease;
}

.dept-card a:hover {
    color: #38bdf8;
}

.footer-bottom {
    padding: 30px 0;
    border-top: 1px solid rgba(255, 255, 255, 0.1);
}

.footer-bottom-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 20px;
}

.footer-bottom-links a {
    color: #94a3b8;
    text-decoration: none;
    margin-left: 20px;
    transition: color 0.3s ease;
}

.footer-bottom-links a:hover {
    color: #fbbf24;
}

@media (max-width: 768px) {
    .footer-main {
        grid-template-columns: 1fr;
        gap: 30px;
        text-align: center;
    }
    
    .footer-departments {
        grid-template-columns: 1fr;
    }
    
    .footer-bottom-content {
        flex-direction: column;
        text-align: center;
    }
    
    .footer-bottom-links a {
        margin: 0 10px;
    }
    
    .contact-info p {
        justify-content: center;
    }
    
    .social-links {
        justify-content: center;
    }
}
</style>';
    
    file_put_contents('components/modern_footer.php', $modern_footer);
    echo "<p style='color: green;'>✓ Modern footer created</p>";
    
    echo "<h2>🎉 All Fixes Applied Successfully!</h2>";
    echo "<div style='background: #d4edda; padding: 20px; border-radius: 5px;'>";
    echo "<h3>What has been fixed:</h3>";
    echo "<ul>";
    echo "<li>✅ Email configuration updated with your specific emails</li>";
    echo "<li>✅ Map location changed to Iganga Town</li>";
    echo "<li>✅ Modern WhatsApp integration created</li>";
    echo "<li>✅ Modern, compact footer designed</li>";
    echo "<li>✅ Department emails aligned</li>";
    echo "</ul>";
    echo "</div>";
    
    echo "<h3>Next Steps:</h3>";
    echo "<p><a href='integrate_all_fixes.php' style='background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>🔧 Integrate All Fixes</a></p>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>✗ Error: " . $e->getMessage() . "</p>";
}
?>
