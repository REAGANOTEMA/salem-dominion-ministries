<?php
require_once 'config.php';
require_once 'db.php';

echo "=== SALEM DOMINION MINISTRIES - SYSTEM STATUS ===\n\n";

// Database Connection
echo "📊 DATABASE CONNECTION:\n";
try {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME, DB_PORT);
    if ($conn->connect_error) {
        echo "❌ Database Connection: FAILED\n";
        echo "Error: " . $conn->connect_error . "\n";
    } else {
        echo "✅ Database Connection: SUCCESS\n";
        echo "   Host: " . DB_HOST . "\n";
        echo "   Database: " . DB_NAME . "\n";
        echo "   User: " . DB_USER . "\n";
        
        // Check tables
        $result = $conn->query("SHOW TABLES");
        $table_count = $result->num_rows;
        echo "   Tables: " . $table_count . "\n\n";
        
        // List important tables
        $important_tables = ['users', 'events', 'donations', 'contact_messages', 'sermons', 'gallery', 'news', 'prayer_requests'];
        echo "📋 IMPORTANT TABLES:\n";
        foreach ($important_tables as $table) {
            $check = $conn->query("SHOW TABLES LIKE '$table'");
            if ($check->num_rows > 0) {
                $count = $conn->query("SELECT COUNT(*) as count FROM $table")->fetch_assoc()['count'];
                echo "✅ $table: $count records\n";
            } else {
                echo "❌ $table: MISSING\n";
            }
        }
        $conn->close();
    }
} catch (Exception $e) {
    echo "❌ Database Exception: " . $e->getMessage() . "\n";
}

echo "\n🌍 ENVIRONMENT:\n";
echo "Environment: " . APP_ENV . "\n";
echo "Debug Mode: " . (DEBUG_MODE ? 'ON' : 'OFF') . "\n";
echo "App URL: " . APP_URL . "\n";

// Check critical files
echo "\n📁 CRITICAL FILES:\n";
$critical_files = [
    'index.php' => 'Homepage',
    'login_perfect.php' => 'Login Page',
    'dashboard.php' => 'User Dashboard',
    'admin_dashboard.php' => 'Admin Dashboard',
    'about.php' => 'About Page',
    'contact.php' => 'Contact Page',
    'donate.php' => 'Donation Page',
    'config.php' => 'Configuration',
    'db.php' => 'Database Class',
    '.env' => 'Environment Variables'
];

foreach ($critical_files as $file => $description) {
    if (file_exists($file)) {
        echo "✅ $file - $description\n";
    } else {
        echo "❌ $file - $description (MISSING)\n";
    }
}

// Check directories
echo "\n📁 IMPORTANT DIRECTORIES:\n";
$directories = [
    'uploads/' => 'Upload Directory',
    'uploads/avatars/' => 'Avatar Uploads',
    'uploads/gallery/' => 'Gallery Images',
    'uploads/sermons/' => 'Sermon Files',
    'components/' => 'Components',
    'assets/' => 'Assets'
];

foreach ($directories as $dir => $description) {
    if (is_dir($dir)) {
        echo "✅ $dir - $description\n";
    } else {
        echo "❌ $dir - $description (MISSING)\n";
    }
}

echo "\n🔧 RECOMMENDATIONS:\n";
echo "1. Test all pages in browser\n";
echo "2. Verify user login functionality\n";
echo "3. Check admin dashboard access\n";
echo "4. Test donation and contact forms\n";
echo "5. Verify email configuration\n";

echo "\n=== END STATUS REPORT ===\n";
?>
