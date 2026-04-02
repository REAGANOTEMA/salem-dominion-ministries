<?php
// Database Connection Test Script
require_once 'config.php';

echo "<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Database Connection Test - Salem Dominion Ministries</title>
    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' rel='stylesheet'>
    <link href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css' rel='stylesheet'>
    <style>
        body { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; }
        .test-container { max-width: 800px; margin: 50px auto; }
        .card { border-radius: 20px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); }
        .status-success { color: #28a745; }
        .status-error { color: #dc3545; }
        .status-warning { color: #ffc107; }
        pre { background: #f8f9fa; padding: 1rem; border-radius: 10px; font-size: 0.9rem; }
    </style>
</head>
<body>
    <div class='container test-container'>
        <div class='card'>
            <div class='card-body p-5'>
                <h1 class='text-center mb-4'>
                    <i class='fas fa-database me-2'></i>
                    Database Connection Test
                </h1>
                
                <div class='alert alert-info'>
                    <i class='fas fa-info-circle me-2'></i>
                    Testing database connection for <strong>Salem Dominion Ministries</strong>
                </div>";

// Test 1: Environment Detection
echo "<div class='mb-4'>
        <h5><i class='fas fa-server me-2'></i> Environment Detection</h5>
        <div class='alert alert-success'>
            <strong>Environment:</strong> " . APP_ENV . "<br>
            <strong>Database Host:</strong> " . DB_HOST . "<br>
            <strong>Database Name:</strong> " . DB_NAME . "<br>
            <strong>Database User:</strong> " . DB_USER . "<br>
            <strong>App URL:</strong> " . APP_URL . "<br>
            <strong>Debug Mode:</strong> " . (DEBUG_MODE ? 'Enabled' : 'Disabled') . "
        </div>
      </div>";

// Test 2: Database Connection
echo "<div class='mb-4'>
        <h5><i class='fas fa-plug me-2'></i> Database Connection Test</h5>";

try {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME, DB_PORT);
    
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }
    
    $conn->set_charset(DB_CHARSET);
    
    echo "<div class='alert alert-success'>
            <i class='fas fa-check-circle me-2'></i>
            <strong>Connection Status:</strong> SUCCESS<br>
            <strong>MySQL Version:</strong> " . $conn->server_info . "<br>
            <strong>Character Set:</strong> " . $conn->character_set_name() . "
          </div>";
    
    // Test 3: Database Query
    echo "<h5><i class='fas fa-database me-2'></i> Database Query Test</h5>";
    
    $tables_test = $conn->query("SHOW TABLES");
    $table_count = $tables_test->num_rows;
    
    echo "<div class='alert alert-success'>
            <i class='fas fa-table me-2'></i>
            <strong>Tables Found:</strong> " . $table_count . "<br>
            <strong>Query Status:</strong> SUCCESS
          </div>";
    
    // Test 4: Key Tables Check
    $key_tables = ['users', 'ministries', 'events', 'sermons', 'news', 'gallery', 'service_times', 'pastor_bookings'];
    $missing_tables = [];
    
    foreach ($key_tables as $table) {
        $result = $conn->query("SHOW TABLES LIKE '$table'");
        if ($result->num_rows == 0) {
            $missing_tables[] = $table;
        }
    }
    
    if (empty($missing_tables)) {
        echo "<div class='alert alert-success'>
                <i class='fas fa-check-circle me-2'></i>
                <strong>Key Tables Check:</strong> All required tables found
              </div>";
    } else {
        echo "<div class='alert alert-warning'>
                <i class='fas fa-exclamation-triangle me-2'></i>
                <strong>Missing Tables:</strong> " . implode(', ', $missing_tables) . "
              </div>";
    }
    
    // Test 5: Sample Data Check
    echo "<h5><i class='fas fa-database me-2'></i> Sample Data Check</h5>";
    
    $data_checks = [
        'users' => 'SELECT COUNT(*) as count FROM users',
        'ministries' => 'SELECT COUNT(*) as count FROM ministries WHERE is_active = 1',
        'events' => 'SELECT COUNT(*) as count FROM events',
        'sermons' => 'SELECT COUNT(*) as count FROM sermons WHERE status = "published"',
        'news' => 'SELECT COUNT(*) as count FROM news WHERE status = "published"',
        'gallery' => 'SELECT COUNT(*) as count FROM gallery WHERE status = "published"',
        'service_times' => 'SELECT COUNT(*) as count FROM service_times WHERE is_active = 1'
    ];
    
    foreach ($data_checks as $table => $query) {
        $result = $conn->query($query);
        $count = $result->fetch_assoc()['count'];
        $status = $count > 0 ? 'success' : 'warning';
        $icon = $count > 0 ? 'check-circle' : 'exclamation-triangle';
        
        echo "<div class='alert alert-{$status}'>
                <i class='fas fa-{$icon} me-2'></i>
                <strong>{$table}:</strong> {$count} records
              </div>";
    }
    
    $conn->close();
    
} catch (Exception $e) {
    echo "<div class='alert alert-danger'>
            <i class='fas fa-times-circle me-2'></i>
            <strong>Connection Status:</strong> FAILED<br>
            <strong>Error:</strong> " . $e->getMessage() . "
          </div>";
}

echo "</div>";

// Test 6: Configuration Summary
echo "<div class='mb-4'>
        <h5><i class='fas fa-cog me-2'></i> Configuration Summary</h5>
        <pre>" . json_encode(getEnvironmentInfo(), JSON_PRETTY_PRINT) . "</pre>
      </div>";

// Test 7: File Permissions
echo "<div class='mb-4'>
        <h5><i class='fas fa-folder me-2'></i> File Permissions Check</h5>";

$dirs_to_check = [UPLOAD_DIR, AVATAR_DIR, GALLERY_DIR, NEWS_DIR, BLOG_DIR, SERMON_DIR];

foreach ($dirs_to_check as $dir) {
    if (is_dir($dir)) {
        if (is_writable($dir)) {
            echo "<div class='alert alert-success'>
                    <i class='fas fa-check-circle me-2'></i>
                    <strong>{$dir}:</strong> Writable ✓
                  </div>";
        } else {
            echo "<div class='alert alert-warning'>
                    <i class='fas fa-exclamation-triangle me-2'></i>
                    <strong>{$dir}:</strong> Not writable ⚠
                  </div>";
        }
    } else {
        echo "<div class='alert alert-danger'>
                <i class='fas fa-times-circle me-2'></i>
                <strong>{$dir}:</strong> Directory does not exist ✗
              </div>";
    }
}

echo "</div>";

echo "<div class='text-center mt-4'>
        <a href='index.php' class='btn btn-primary btn-lg me-2'>
            <i class='fas fa-home me-2'></i> Go to Homepage
        </a>
        <a href='admin_dashboard.php' class='btn btn-secondary btn-lg'>
            <i class='fas fa-cog me-2'></i> Admin Dashboard
        </a>
      </div>

    </div>
</div>
</body>
</html>";
?>
