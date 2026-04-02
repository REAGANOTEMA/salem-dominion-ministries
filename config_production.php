<?php
// Production-Ready Database Configuration for Hosting
// This file bypasses environment detection and uses production settings

// Load environment variables from .env file (if exists)
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

// FORCE PRODUCTION DATABASE SETTINGS
// This bypasses environment detection and uses production credentials
define('DB_HOST', $_ENV['PROD_DB_HOST'] ?? 'localhost');
define('DB_USER', $_ENV['PROD_DB_USER'] ?? 'salemdominionmin_db');
define('DB_PASSWORD', $_ENV['PROD_DB_PASSWORD'] ?? '22uHzNYEHwUsFKdVz3wT');
define('DB_NAME', $_ENV['PROD_DB_NAME'] ?? 'salem_dominion_ministries');
define('DB_CHARSET', $_ENV['PROD_DB_CHARSET'] ?? 'utf8mb4');
define('DB_PORT', $_ENV['PROD_DB_PORT'] ?? 3306);

// Production Application Configuration
define('APP_URL', $_ENV['APP_URL'] ?? 'https://salemdominionministries.com');
define('APP_ENV', $_ENV['APP_ENV'] ?? 'production');
define('DEBUG_MODE', false); // Disable debug in production

// Production Email Configuration
define('MAIL_HOST', $_ENV['MAIL_HOST'] ?? 'smtp.gmail.com');
define('MAIL_PORT', $_ENV['MAIL_PORT'] ?? 587);
define('MAIL_USERNAME', $_ENV['MAIL_USERNAME'] ?? 'visit@salemdominionministries.com');
define('MAIL_PASSWORD', $_ENV['MAIL_PASSWORD'] ?? 'Lovely2God');
define('MAIL_FROM', $_ENV['MAIL_FROM'] ?? 'visit@salemdominionministries.com');
define('MAIL_ENABLED', true); // Enable email in production

// Security Configuration
define('JWT_SECRET', $_ENV['JWT_SECRET'] ?? 'your_jwt_secret_key_here');
define('UPLOAD_MAX_SIZE', $_ENV['UPLOAD_MAX_SIZE'] ?? 10485760);

// Church Information
define('CHURCH_NAME', $_ENV['CHURCH_NAME'] ?? 'Salem Dominion Ministries');
define('CHURCH_EMAIL', $_ENV['CHURCH_EMAIL'] ?? 'visit@salemdominionministries.com');
define('CHURCH_PHONE', $_ENV['CHURCH_PHONE'] ?? '+256 753 244480');
define('CHURCH_ADDRESS', $_ENV['CHURCH_ADDRESS'] ?? '123 Church Street, City, State');

// Database Connection Test Function
function testDatabaseConnection() {
    try {
        $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME, DB_PORT);
        if ($conn->connect_error) {
            return [
                'success' => false,
                'error' => $conn->connect_error,
                'details' => 'Check database credentials and server status'
            ];
        }
        
        // Test if database exists and is accessible
        $result = $conn->query("SELECT 1");
        if ($result === false) {
            $conn->close();
            return [
                'success' => false,
                'error' => $conn->error,
                'details' => 'Database query failed - check permissions'
            ];
        }
        
        $conn->close();
        return [
            'success' => true,
            'message' => 'Database connection successful',
            'details' => 'Connected to: ' . DB_USER . '@' . DB_HOST . ':' . DB_PORT . '/' . DB_NAME
        ];
    } catch (Exception $e) {
        return [
            'success' => false,
            'error' => $e->getMessage(),
            'details' => 'Exception occurred during connection'
        ];
    }
}

// Auto-test connection if this file is accessed directly
if (basename($_SERVER['PHP_SELF']) === 'config_production.php') {
    header('Content-Type: application/json');
    echo json_encode(testDatabaseConnection());
    exit;
}
?>
