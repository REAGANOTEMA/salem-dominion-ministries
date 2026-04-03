<?php
// Load environment variables from .env file
function loadEnv($file) {
    if (!file_exists($file)) {
        return;
    }
    
    $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos($line, '#') === 0) {
            continue; // Skip comments
        }
        
        if (strpos($line, '=') === false) {
            continue; // Skip lines without =
        }
        
        list($key, $value) = explode('=', $line, 2);
        $key = trim($key);
        $value = trim($value);
        
        // Remove quotes if present
        if ((substr($value, 0, 1) === '"' && substr($value, -1) === '"') ||
            (substr($value, 0, 1) === "'" && substr($value, -1) === "'")) {
            $value = substr($value, 1, -1);
        }
        
        // Set environment variable
        putenv("$key=$value");
        $_ENV[$key] = $value;
    }
}

// Load .env file
loadEnv(__DIR__ . '/.env');

// Smart Database Configuration
// Automatically detects environment and uses appropriate settings

// Environment Detection
$server_name = $_SERVER['SERVER_NAME'] ?? 'localhost';
$http_host = $_SERVER['HTTP_HOST'] ?? 'localhost';

$is_localhost = (
    $server_name === 'localhost' || 
    $server_name === '127.0.0.1' ||
    strpos($server_name ?? '', '.local') !== false ||
    strpos($http_host ?? '', '.local') !== false ||
    $server_name === 'salemdominionministries.localhost'
);

// FORCE PRODUCTION SETTINGS FOR HOSTING
// This ensures production database credentials are used on hosting
$force_production = false;

// Check if we're on hosting (not localhost)
if (!$is_localhost) {
    $force_production = true;
}

// Database Configuration based on environment with enhanced error handling
if ($is_localhost && !$force_production) {
    // Localhost (XAMPP) Settings - Enhanced with multiple fallbacks
    define('DB_HOST', $_ENV['DB_HOST'] ?? 'localhost');
    define('DB_USER', $_ENV['DB_USER'] ?? 'root');
    define('DB_PASSWORD', $_ENV['DB_PASSWORD'] ?? '');
    define('DB_NAME', $_ENV['DB_NAME'] ?? 'salem_dominion_ministries');
    define('DB_CHARSET', $_ENV['DB_CHARSET'] ?? 'utf8mb4');
    define('DB_PORT', $_ENV['DB_PORT'] ?? 3306);
    
    // Enhanced phpMyAdmin Alternative Connection
    define('PHPMYADMIN_HOST', $_ENV['PHPMYADMIN_HOST'] ?? 'localhost');
    define('PHPMYADMIN_USER', $_ENV['PHPMYADMIN_USER'] ?? 'root');
    define('PHPMYADMIN_PASSWORD', $_ENV['PHPMYADMIN_PASSWORD'] ?? 'ReagaN23#');
    define('PHPMYADMIN_DB_NAME', $_ENV['PHPMYADMIN_DB_NAME'] ?? 'salem_dominion_ministries');
    define('PHPMYADMIN_DB_PORT', $_ENV['PHPMYADMIN_DB_PORT'] ?? 3306);
    
    // Local Application Configuration
    define('APP_URL', $_ENV['APP_URL'] ?? 'http://localhost/salem-dominion-ministries');
    define('APP_ENV', $_ENV['APP_ENV'] ?? 'development');
    define('DEBUG_MODE', true);
    
    // Local Email Configuration (disabled for development)
    define('MAIL_HOST', $_ENV['MAIL_HOST'] ?? 'smtp.gmail.com');
    define('MAIL_PORT', $_ENV['MAIL_PORT'] ?? 587);
    define('MAIL_USERNAME', $_ENV['MAIL_USERNAME'] ?? 'visit@salemdominionministries.com');
    define('MAIL_PASSWORD', $_ENV['MAIL_PASSWORD'] ?? 'Lovely2God');
    define('MAIL_FROM', $_ENV['MAIL_FROM'] ?? 'visit@salemdominionministries.com');
    define('MAIL_ENABLED', false); // Disabled for localhost
    
} else {
    // Production (Hosting Platform) Settings - Enhanced with multiple attempts
    define('DB_HOST', $_ENV['PROD_DB_HOST'] ?? 'localhost');
    define('DB_USER', $_ENV['PROD_DB_USER'] ?? 'salemdominionmin_db');
    define('DB_PASSWORD', $_ENV['PROD_DB_PASSWORD'] ?? 'RwdT68fQ8FRgMcsrLyBB');
    define('DB_NAME', $_ENV['PROD_DB_NAME'] ?? 'salemdominionmin_db');
    define('DB_CHARSET', 'utf8mb4');
    define('DB_PORT', 3306);
    
    // Production phpMyAdmin Alternative Connection
    define('PHPMYADMIN_HOST', $_ENV['PHPMYADMIN_HOST'] ?? 'localhost');
    define('PHPMYADMIN_USER', $_ENV['PHPMYADMIN_USER'] ?? 'root');
    define('PHPMYADMIN_PASSWORD', $_ENV['PHPMYADMIN_PASSWORD'] ?? 'ReagaN23#');
    define('PHPMYADMIN_DB_NAME', $_ENV['PHPMYADMIN_DB_NAME'] ?? 'salem_dominion_ministries');
    define('PHPMYADMIN_DB_PORT', $_ENV['PHPMYADMIN_DB_PORT'] ?? 3306);
    
    // Production Application Configuration
    define('APP_URL', 'https://salemdominionministries.com');
    define('APP_ENV', $_ENV['APP_ENV'] ?? 'production');
    define('DEBUG_MODE', false); // Disable debug in production Email Configuration (enabled for production)
    define('MAIL_HOST', 'smtp.gmail.com');
    define('MAIL_PORT', 587);
    define('MAIL_USERNAME', 'visit@salemdominionministries.com');
    define('MAIL_PASSWORD', 'Lovely2God');
    define('MAIL_FROM', 'visit@salemdominionministries.com');
    define('MAIL_ENABLED', true);
}

// Security Configuration
define('JWT_SECRET', 'salem_dominion_jwt_secret_key_2026_secure');
define('UPLOAD_MAX_SIZE', 10485760); // 10MB
define('SESSION_LIFETIME', 7200); // 2 hours
define('PASSWORD_MIN_LENGTH', 8);

// Upload directories (relative to root)
define('UPLOAD_DIR', 'uploads/');
define('AVATAR_DIR', 'uploads/avatars/');
define('GALLERY_DIR', 'uploads/gallery/');
define('NEWS_DIR', 'uploads/news/');
define('BLOG_DIR', 'uploads/blog/');
define('SERMON_DIR', 'uploads/sermons/');

// Church Configuration
define('CHURCH_NAME', $_ENV['CHURCH_NAME'] ?? 'Salem Dominion Ministries');
define('CHURCH_EMAIL', $_ENV['CHURCH_EMAIL'] ?? 'visit@salemdominionministries.com');
define('CHURCH_PHONE', $_ENV['CHURCH_PHONE'] ?? '+256 753 244480');
define('CHURCH_ADDRESS', $_ENV['CHURCH_ADDRESS'] ?? '123 Church Street, City, State');

// Social Media Configuration
define('FACEBOOK_URL', 'https://facebook.com/salemdominionministries');
define('TWITTER_URL', 'https://twitter.com/salemdominionministries');
define('INSTAGRAM_URL', 'https://instagram.com/salemdominionministries');
define('YOUTUBE_URL', 'https://youtube.com/salemdominionministries');

// Google Meet Configuration for Pastor Booking
define('GOOGLE_MEET_ENABLED', true);
define('BOOKING_BUFFER_TIME', 30); // minutes between bookings
define('MAX_BOOKING_DURATION', 120); // maximum booking duration in minutes

// Error Reporting Configuration
if (DEBUG_MODE) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    ini_set('log_errors', 1);
    ini_set('error_log', UPLOAD_DIR . 'error.log');
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
    ini_set('log_errors', 1);
    ini_set('error_log', UPLOAD_DIR . 'error.log');
}

// Create upload directories if they don't exist
$dirs = [UPLOAD_DIR, AVATAR_DIR, GALLERY_DIR, NEWS_DIR, BLOG_DIR, SERMON_DIR];
foreach ($dirs as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
}

// Database Connection Test Function (for debugging)
function testDatabaseConnection() {
    try {
        $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME, DB_PORT);
        $conn->set_charset(DB_CHARSET);
        
        if ($conn->connect_error) {
            throw new Exception("Connection failed: " . $conn->connect_error);
        }
        
        // Test a simple query
        $result = $conn->query("SELECT 1 as test");
        if ($result && $result->fetch_assoc()['test'] == 1) {
            $conn->close();
            return true;
        }
        
        $conn->close();
        return false;
    } catch (Exception $e) {
        if (DEBUG_MODE) {
            error_log("Database Connection Error: " . $e->getMessage());
        }
        return false;
    }
}

// Environment Information Function
function getEnvironmentInfo() {
    return [
        'environment' => APP_ENV,
        'debug_mode' => DEBUG_MODE,
        'database_host' => DB_HOST,
        'database_name' => DB_NAME,
        'database_user' => DB_USER,
        'app_url' => APP_URL,
        'mail_enabled' => MAIL_ENABLED,
        'server_name' => $_SERVER['SERVER_NAME'] ?? 'unknown',
        'http_host' => $_SERVER['HTTP_HOST'] ?? 'unknown',
        'document_root' => $_SERVER['DOCUMENT_ROOT'] ?? 'unknown'
    ];
}

// Auto-detect and set correct timezone
date_default_timezone_set('UTC');

// Set secure session parameters (only if session hasn't started)
if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.cookie_httponly', 1);
    ini_set('session.cookie_secure', !DEBUG_MODE);
    ini_set('session.cookie_samesite', 'Strict');
    ini_set('session.use_strict_mode', 1);
    session_start();
}

// Log environment info for debugging (only in development)
if (DEBUG_MODE) {
    error_log("Environment Info: " . json_encode(getEnvironmentInfo()));
    
    // Test database connection on first load
    if (!isset($_SESSION['db_tested'])) {
        $db_test = testDatabaseConnection();
        error_log("Database Connection Test: " . ($db_test ? "SUCCESS" : "FAILED"));
        $_SESSION['db_tested'] = true;
    }
}

?>
