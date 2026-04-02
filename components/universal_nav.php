<?php
// Universal Navigation Component
require_once 'auth_system.php';

// Check if user is logged in
$user_is_logged_in = is_logged_in();
$current_user = $user_is_logged_in ? get_current_user() : null;
$is_admin = $user_is_logged_in ? is_admin() : false;
?>

<style>
/* Universal Navigation Styles */
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

@media (max-width: 768px) {
    .navbar-nav .nav-link {
        padding: 10px 15px;
        margin: 2px 0;
    }
    
    .donate-btn, .book-pastor-btn {
        margin: 5px 0;
        display: inline-block;
    }
}
</style>

<nav class="navbar navbar-expand-lg navbar-dark universal-nav">
    <div class="container">
        <a class="navbar-brand" href="index.php">
            <i class="fas fa-church"></i> Salem Dominion Ministries
        </a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link" href="index.php">
                        <i class="fas fa-home"></i> Home
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="about.php">
                        <i class="fas fa-church"></i> About
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
                    <a class="nav-link" href="prayer.php">
                        <i class="fas fa-pray"></i> Prayer
                    </a>
                </li>
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
                
                <?php if ($user_is_logged_in): ?>
                    <!-- User Menu -->
                    <li class="nav-item user-dropdown">
                        <a class="nav-link" href="#" onclick="toggleUserMenu(event)">
                            <?php if ($current_user['avatar'] && file_exists($current_user['avatar'])): ?>
                                <img src="<?php echo htmlspecialchars($current_user['avatar']); ?>" alt="Avatar" class="user-avatar">
                            <?php else: ?>
                                <i class="fas fa-user-circle fa-2x"></i>
                            <?php endif; ?>
                            <?php echo htmlspecialchars($current_user['name']); ?>
                            <i class="fas fa-chevron-down ms-2"></i>
                        </a>
                        
                        <div class="user-menu" id="userMenu">
                            <a href="dashboard.php">
                                <i class="fas fa-tachometer-alt"></i> Dashboard
                            </a>
                            <a href="profile.php">
                                <i class="fas fa-user"></i> Profile
                            </a>
                            <?php if ($is_admin): ?>
                                <a href="admin_dashboard.php">
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
                        <a class="nav-link" href="login_complete.php">
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
</script>
