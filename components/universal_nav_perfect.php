<?php
// Production-Ready Universal Navigation with WhatsApp
require_once 'config_force.php';

// Check if user is logged in
$user_is_logged_in = isset($_SESSION['user_id']);
$current_user = $user_is_logged_in ? $_SESSION : null;
$is_admin = $user_is_logged_in && ($_SESSION['role'] ?? '') === 'admin';
?>

<!-- PWA Manifest Link -->
<link rel="manifest" href="/pwa_manifest.json">
<link rel="apple-touch-icon" href="/assets/icons/icon-152x152.png">
<meta name="theme-color" content="#16a34a">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="default">
<meta name="apple-mobile-web-app-title" content="Salem Church">
<meta name="application-name" content="Salem Dominion Ministries">
<meta name="msapplication-TileColor" content="#16a34a">
<meta name="msapplication-config" content="/salem_browserconfig.xml">

<style>
/* Production-Ready Universal Navigation */
.universal-nav {
    background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    position: sticky;
    top: 0;
    z-index: 1000;
}

.navbar-brand {
    font-weight: bold;
    font-size: 1.2rem;
    color: white !important;
    transition: all 0.3s ease;
}

.navbar-brand:hover {
    transform: scale(1.05);
}

.navbar-nav .nav-link {
    color: rgba(255,255,255,0.8) !important;
    padding: 12px 15px;
    margin: 0 5px;
    border-radius: 8px;
    transition: all 0.3s ease;
    font-weight: 500;
}

.navbar-nav .nav-link:hover {
    background: rgba(255,255,255,0.1);
    color: white !important;
    transform: translateY(-2px);
}

.navbar-nav .nav-link.active {
    background: rgba(22, 163, 74, 0.2);
    color: #16a34a !important;
    border-left: 3px solid #16a34a;
}

.navbar-nav .nav-link i {
    margin-right: 8px;
}

.donate-btn {
    background: linear-gradient(135deg, #16a34a 0%, #15803d 100%);
    color: white !important;
    padding: 8px 20px;
    border-radius: 20px;
    font-weight: 600;
    transition: all 0.3s ease;
    margin-left: 10px;
}

.donate-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(22, 163, 74, 0.3);
    background: linear-gradient(135deg, #15803d 0%, #166534 100%);
}

.book-pastor-btn {
    background: linear-gradient(135deg, #7c3aed 0%, #6d28d0 100%);
    color: white !important;
    padding: 8px 20px;
    border-radius: 20px;
    font-weight: 600;
    transition: all 0.3s ease;
    margin-left: 10px;
}

.book-pastor-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(124, 58, 237, 0.3);
    background: linear-gradient(135deg, #6d28d0 0%, #5b21b6 100%);
}

.pwa-install-btn {
    background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%);
    color: white !important;
    padding: 8px 20px;
    border-radius: 20px;
    font-weight: 600;
    transition: all 0.3s ease;
    margin-left: 10px;
    font-size: 0.85rem;
}

.pwa-install-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(14, 165, 233, 0.3);
    background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%);
}

.user-dropdown {
    position: relative;
}

.user-avatar {
    width: 35px;
    height: 35px;
    border-radius: 50%;
    border: 2px solid #16a34a;
    margin-right: 10px;
}

.user-menu {
    background: white;
    border-radius: 10px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.2);
    padding: 10px 0;
    min-width: 200px;
    position: absolute;
    right: 0;
    top: 100%;
    margin-top: 10px;
    display: none;
}

.user-menu.show {
    display: block;
}

.user-menu a {
    color: #1e293b !important;
    padding: 10px 20px;
    display: block;
    text-decoration: none;
    transition: all 0.3s ease;
}

.user-menu a:hover {
    background: #f8fafc;
    color: #16a34a !important;
}

.user-menu .dropdown-divider {
    border-top: 1px solid #e5e7eb;
    margin: 10px 0;
}

/* WhatsApp Integration in Navigation */
.navbar-whatsapp {
    background: linear-gradient(135deg, #25D366 0%, #128C7E 100%);
    color: white !important;
    padding: 8px 15px;
    border-radius: 20px;
    font-weight: 600;
    transition: all 0.3s ease;
    margin-left: 10px;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
}

.navbar-whatsapp:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(37, 211, 102, 0.3);
    color: white !important;
}

.navbar-whatsapp i {
    margin-right: 5px;
}

@media (max-width: 768px) {
    .navbar-nav .nav-link {
        padding: 10px 15px;
        margin: 2px 0;
    }
    
    .donate-btn, .book-pastor-btn, .pwa-install-btn, .navbar-whatsapp {
        margin: 5px 0;
        display: inline-block;
    }
}
</style>

<!-- Production-Ready Navigation -->
<nav class="navbar navbar-expand-lg navbar-dark universal-nav">
    <div class="container">
        <a class="navbar-brand" href="index_live.php">
            <img src="assets/images/logo" alt="Salem Dominion Ministries Logo" style="height: 40px; margin-right: 10px; border-radius: 8px;" onerror="this.style.display='none'; this.alt='Logo not available';">
            <span style="vertical-align: middle;">Salem Dominion Ministries</span>
        </a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link" href="index_perfect.php">
                        <i class="fas fa-home"></i> Home
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="children.php">
                        <i class="fas fa-child"></i> Children
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="pastors.php">
                        <i class="fas fa-users"></i> Pastors
                    </a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="ministriesDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-church"></i> Ministries
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="ministriesDropdown">
                        <li><a class="dropdown-item" href="children.php"><i class="fas fa-child"></i> Children's Ministry</a></li>
                        <li><a class="dropdown-item" href="youth.php"><i class="fas fa-user-graduate"></i> Youth Ministry</a></li>
                        <li><a class="dropdown-item" href="women.php"><i class="fas fa-female"></i> Women's Ministry</a></li>
                        <li><a class="dropdown-item" href="men.php"><i class="fas fa-male"></i> Men's Ministry</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="outreach.php"><i class="fas fa-hands-helping"></i> Outreach</a></li>
                        <li><a class="dropdown-item" href="missions.php"><i class="fas fa-globe"></i> Missions</a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="prayer.php">
                        <i class="fas fa-pray"></i> Prayer
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="events.php">
                        <i class="fas fa-calendar"></i> Events
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="gallery.php">
                        <i class="fas fa-images"></i> Gallery
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="sermons.php">
                        <i class="fas fa-bible"></i> Sermons
                    </a>
                </li>
                <li class="nav-item">
                <li class="nav-item">
                    <a class="nav-link" href="contact.php">
                        <i class="fas fa-envelope"></i> Contact
                    </a>
                </li>
            </ul>
            
            <ul class="navbar-nav">
                <!-- Quick Action Buttons -->
                <li class="nav-item">
                    <a class="nav-link donate-btn" href="donations.php">
                        <i class="fas fa-donate"></i> Give
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link book-pastor-btn" href="book_pastor.php">
                        <i class="fas fa-calendar"></i> Book Pastor
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link pwa-install-btn" href="#" onclick="installPWA(event)">
                        <i class="fas fa-download"></i> Install App
                    </a>
                </li>
                
                <!-- WhatsApp Support -->
                <li class="nav-item">
                    <a href="https://wa.me/256753244480?text=Hello!%20I%20need%20help%20with%20Salem%20Dominion%20Ministries%20website." 
                       class="navbar-whatsapp" 
                       target="_blank" 
                       rel="noopener noreferrer"
                       title="Chat with Developer on WhatsApp">
                        <i class="fab fa-whatsapp"></i> Support
                    </a>
                </li>
                
                <?php if ($user_is_logged_in): ?>
                    <!-- User Menu -->
                    <li class="nav-item user-dropdown">
                        <a class="nav-link" href="#" onclick="toggleUserMenu(event)">
                            <?php if ($current_user['avatar'] && file_exists($current_user['avatar'])): ?>
                                <img src="<?php echo htmlspecialchars($current_user['avatar']); ?>" alt="Avatar" class="user-avatar">
                            <?php else: ?>
                                <i class="fas fa-user-circle fa-2x"></i>
                            <?php endif; ?>
                            <?php echo htmlspecialchars($current_user['first_name'] . ' ' . $current_user['last_name']); ?>
                            <i class="fas fa-chevron-down ms-2"></i>
                        </a>
                        
                        <div class="user-menu" id="userMenu">
                            <a href="dashboard_no_paths.php">
                                <i class="fas fa-tachometer-alt"></i> Dashboard
                            </a>
                            <a href="profile.php">
                                <i class="fas fa-user"></i> Profile
                            </a>
                            <?php if ($is_admin): ?>
                                <a href="admin_dashboard_production.php">
                                    <i class="fas fa-cog"></i> Admin Panel
                                </a>
                                <a href="admin_donations_perfect.php">
                                    <i class="fas fa-donate"></i> Donations
                                </a>
                                <a href="admin_communications.php">
                                    <i class="fas fa-bullhorn"></i> Communications
                                </a>
                            <?php endif; ?>
                            <div class="dropdown-divider"></div>
                            <a href="auth_system.php?action=logout">
                                <i class="fas fa-sign-out-alt"></i> Logout
                            </a>
                        </div>
                    </li>
                <?php else: ?>
                    <!-- Login/Register -->
                    <li class="nav-item">
                        <a class="nav-link" href="login_perfect.php">
                            <i class="fas fa-sign-in-alt"></i> Login
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="register.php">
                            <i class="fas fa-user-plus"></i> Register
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<script>
// User menu toggle
function toggleUserMenu(event) {
    event.preventDefault();
    const userMenu = document.getElementById('userMenu');
    userMenu.classList.toggle('show');
}

// Close user menu when clicking outside
document.addEventListener('click', function(event) {
    const userMenu = document.getElementById('userMenu');
    const userDropdown = document.querySelector('.user-dropdown');
    
    if (!userDropdown.contains(event.target)) {
        userMenu.classList.remove('show');
    }
});

// PWA Install function
function installPWA(event) {
    event.preventDefault();
    
    // Check if PWA install prompt is available
    if (window.pwaInstaller && window.pwaInstaller.deferredPrompt) {
        window.pwaInstaller.installApp();
    } else {
        // Show manual install instructions
        alert('To install our app: \n\nAndroid: Tap the menu button (⋮) and select "Add to Home Screen"\n\niOS: Tap the share button (⬆) and select "Add to Home Screen"');
    }
}

// Set active navigation based on current page
document.addEventListener('DOMContentLoaded', function() {
    const currentPath = window.location.pathname;
    const navLinks = document.querySelectorAll('.navbar-nav .nav-link');
    
    navLinks.forEach(link => {
        const href = link.getAttribute('href');
        if (href && currentPath.includes(href)) {
            link.classList.add('active');
        }
    });
});

// Initialize PWA Installer
window.addEventListener('load', () => {
    // Load PWA installer script
    const script = document.createElement('script');
    script.src = '/pwa_installer.js';
    script.defer = true;
    document.head.appendChild(script);
});

// WhatsApp tracking
document.addEventListener('DOMContentLoaded', function() {
    const whatsappLinks = document.querySelectorAll('.navbar-whatsapp, a[href*="wa.me"]');
    
    whatsappLinks.forEach(link => {
        link.addEventListener('click', function() {
            // Track WhatsApp interaction
            if (typeof gtag !== 'undefined') {
                gtag('event', 'whatsapp_click', {
                    'event_category': 'Contact',
                    'event_label': 'Navigation Support',
                    'value': 1
                });
            }
        });
    });
});
</script>
