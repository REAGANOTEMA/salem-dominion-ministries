<?php
// Enable all error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Complete System Diagnosis</h1>";

// 1. Check PHP Configuration
echo "<h2>PHP Configuration:</h2>";
echo "<p>PHP Version: " . phpversion() . "</p>";
echo "<p>Memory Limit: " . ini_get('memory_limit') . "</p>";
echo "<p>Max Execution Time: " . ini_get('max_execution_time') . "s</p>";
echo "<p>Upload Max Filesize: " . ini_get('upload_max_filesize') . "</p>";
echo "<p>Post Max Size: " . ini_get('post_max_size') . "</p>";

// 2. Check Database Connection
echo "<h2>Database Connection:</h2>";
try {
    require_once 'config.php';
    echo "<p style='color: green;'>✓ Config loaded</p>";
    echo "<p>Environment: " . APP_ENV . "</p>";
    echo "<p>Database Host: " . DB_HOST . "</p>";
    echo "<p>Database Name: " . DB_NAME . "</p>";
    echo "<p>Database User: " . DB_USER . "</p>";
    
    require_once 'db.php';
    echo "<p style='color: green;'>✓ Database connection established</p>";
    
    // Test basic query
    $test_result = $db->query("SELECT 1 as test");
    if ($test_result) {
        echo "<p style='color: green;'>✓ Basic query successful</p>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>✗ Database Error: " . $e->getMessage() . "</p>";
}

// 3. Check Required Tables
echo "<h2>Database Tables:</h2>";
$required_tables = ['users', 'leadership', 'events', 'gallery', 'donations', 'news', 'sermons', 'prayer_requests'];

foreach ($required_tables as $table) {
    try {
        $result = $db->query("SHOW TABLES LIKE '$table'");
        if ($result->num_rows > 0) {
            echo "<p style='color: green;'>✓ $table table exists</p>";
            
            // Get record count
            $count_result = $db->query("SELECT COUNT(*) as count FROM $table");
            $count = $count_result->fetch_assoc()['count'];
            echo "<span> - Records: $count</span>";
            
            if ($count == 0 && $table == 'users') {
                echo "<span style='color: orange;'> ⚠ No users - login won't work!</span>";
            }
            if ($count == 0 && $table == 'leadership') {
                echo "<span style='color: orange;'> ⚠ No leadership data - leadership page empty!</span>";
            }
        } else {
            echo "<p style='color: red;'>✗ $table table missing</p>";
        }
        echo "</p>";
    } catch (Exception $e) {
        echo "<p style='color: red;'>✗ Error checking $table: " . $e->getMessage() . "</p>";
    }
}

// 4. Check Session
echo "<h2>Session Status:</h2>";
if (session_status() === PHP_SESSION_NONE) {
    echo "<p style='color: orange;'>Session not started</p>";
    session_start();
    echo "<p style='color: green;'>✓ Session started</p>";
} else {
    echo "<p style='color: green;'>✓ Session active</p>";
}
echo "<p>Session ID: " . session_id() . "</p>";

// 5. Check File Permissions
echo "<h2>File Permissions:</h2>";
$important_files = [
    'config.php' => 'Configuration',
    'db.php' => 'Database Class',
    'session_helper.php' => 'Session Helper',
    'login_perfect.php' => 'Login Page',
    'leadership.php' => 'Leadership Page',
    'dashboard.php' => 'Dashboard',
    'assets/' => 'Assets Directory',
    'uploads/' => 'Uploads Directory'
];

foreach ($important_files as $file => $description) {
    if (file_exists($file)) {
        if (is_readable($file)) {
            echo "<p style='color: green;'>✓ $description - Readable</p>";
        } else {
            echo "<p style='color: red;'>✗ $description - Not readable</p>";
        }
    } else {
        echo "<p style='color: red;'>✗ $description - File missing</p>";
    }
}

// 6. Check Assets
echo "<h2>Assets Check:</h2>";
$asset_files = [
    'assets/logo-DEFqnQ4s.jpeg',
    'assets/favicon.ico',
    'assets/bootstrap.min.css'
];

foreach ($asset_files as $asset) {
    if (file_exists($asset)) {
        echo "<p style='color: green;'>✓ $asset exists</p>";
    } else {
        echo "<p style='color: orange;'>⚠ $asset missing</p>";
    }
}

// 7. Quick Solutions
echo "<h2>Quick Solutions:</h2>";
echo "<div style='background: #f0f8ff; padding: 15px; border-radius: 5px;'>";
echo "<h3>If login is not working:</h3>";
echo "<p>1. <a href='check_users.php'>Check/Create Users</a></p>";
echo "<p>2. <a href='login_debug.php'>Test Login (Debug Mode)</a></p>";
echo "<p>3. <a href='setup_admin.php'>Setup Admin Account</a></p>";

echo "<h3>If leadership page is empty:</h3>";
echo "<p>1. <a href='leadership.php'>Check Leadership Page</a></p>";
echo "<p>2. Leadership data should auto-populate if table is empty</p>";

echo "<h3>Test other pages:</h3>";
echo "<p><a href='index.php'>Homepage</a></p>";
echo "<p><a href='about.php'>About Page</a></p>";
echo "<p><a href='events.php'>Events Page</a></p>";
echo "<p><a href='gallery.php'>Gallery Page</a></p>";
echo "</div>";

// 8. Create Test User Button
echo "<h2>Create Test Admin User:</h2>";
if ($_POST['create_test_admin']) {
    try {
        $email = 'admin@salem.test';
        $password = 'admin123';
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        // Check if user exists
        $existing = $db->query("SELECT id FROM users WHERE email = '$email'");
        if ($existing->num_rows == 0) {
            $stmt = $db->prepare("INSERT INTO users (first_name, last_name, email, password, role, is_active, created_at) VALUES (?, ?, ?, ?, ?, 1, CURRENT_TIMESTAMP)");
            $stmt->bind_param('sssss', 'Admin', 'User', $email, $hashed_password, 'admin');
            
            if ($stmt->execute()) {
                echo "<p style='color: green;'>✓ Test admin user created!</p>";
                echo "<p>Login with: admin@salem.test / admin123</p>";
            } else {
                echo "<p style='color: red;'>✗ Failed to create user</p>";
            }
            $stmt->close();
        } else {
            echo "<p style='color: orange;'>⚠ Admin user already exists</p>";
        }
    } catch (Exception $e) {
        echo "<p style='color: red;'>✗ Error: " . $e->getMessage() . "</p>";
    }
} else {
    echo "<form method='post'>";
    echo "<button type='submit' name='create_test_admin' style='background: #007bff; color: white; padding: 10px 20px; border: none; cursor: pointer;'>Create Test Admin User</button>";
    echo "</form>";
    echo "<p>This will create: admin@salem.test / admin123</p>";
}

echo "<hr>";
echo "<p><small>Diagnosis completed. Use the links above to fix specific issues.</small></p>";
?>
