&lt;?php
// Database Configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'salemdominionmin_dominion-ministries');
define('DB_PASSWORD', '3zpnMk6T2REJjYGHGzBZ');
define('DB_NAME', 'salemdominionmin_dominion-ministries');
define('DB_CHARSET', 'utf8mb4');
define('DB_PORT', 3306);

// Application Configuration
define('APP_URL', 'http://localhost/salem-dominion-ministries');
define('APP_ENV', 'development');

// Security
define('JWT_SECRET', 'your_jwt_secret_key_here');
define('UPLOAD_MAX_SIZE', 10485760);

// Email Configuration
define('MAIL_HOST', 'smtp.gmail.com');
define('MAIL_PORT', 587);
define('MAIL_USERNAME', 'visit@salemdominionministries.com');
define('MAIL_PASSWORD', 'Lovely2God');
define('MAIL_FROM', 'visit@salemdominionministries.com');

// Upload directories (relative to root)
define('UPLOAD_DIR', 'uploads/');
define('AVATAR_DIR', 'uploads/avatars/');
define('GALLERY_DIR', 'uploads/gallery/');
define('NEWS_DIR', 'uploads/news/');
define('BLOG_DIR', 'uploads/blog/');
define('SERMON_DIR', 'uploads/sermons/');

// Create upload directories if they don't exist
$dirs = [UPLOAD_DIR, AVATAR_DIR, GALLERY_DIR, NEWS_DIR, BLOG_DIR, SERMON_DIR];
foreach ($dirs as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
}
?&gt;