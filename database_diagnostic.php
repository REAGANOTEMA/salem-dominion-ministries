<?php
// Comprehensive Database Connection Diagnostic
header('Content-Type: text/html; charset=utf-8');

echo "<!DOCTYPE html>
<html>
<head>
    <title>🔍 Database Diagnostic - Salem Dominion Ministries</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: linear-gradient(135deg, #f0fdf4 0%, #e0f2fe 100%); }
        .container { max-width: 1000px; margin: 0 auto; background: white; padding: 40px; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); }
        h1 { color: #16a34a; text-align: center; margin-bottom: 30px; font-size: 2.5rem; }
        .success { background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%); padding: 20px; border-radius: 10px; margin: 15px 0; border-left: 5px solid #16a34a; }
        .error { background: linear-gradient(135deg, #fef2f2 0%, #fecaca 100%); padding: 20px; border-radius: 10px; margin: 15px 0; border-left: 5px solid #dc2626; }
        .warning { background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%); padding: 20px; border-radius: 10px; margin: 15px 0; border-left: 5px solid #f59e0b; }
        .info { background: linear-gradient(135deg, #e0f2fe 0%, #bae6fd 100%); padding: 20px; border-radius: 10px; margin: 15px 0; border-left: 5px solid #0ea5e9; }
        .btn { background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%); color: white; padding: 12px 25px; text-decoration: none; border-radius: 25px; display: inline-block; margin: 8px; font-weight: 600; transition: all 0.3s ease; }
        .btn:hover { transform: translateY(-2px); box-shadow: 0 10px 25px rgba(14, 165, 233, 0.3); }
        .code-block { background: #1e293b; color: #e2e8f0; padding: 15px; border-radius: 10px; font-family: monospace; font-size: 0.85rem; margin: 10px 0; overflow-x: auto; }
        .file-list { background: #f8fafc; padding: 15px; border-radius: 10px; font-family: monospace; font-size: 0.9rem; }
        .checklist { list-style: none; padding: 0; }
        .checklist li { padding: 10px 0; border-bottom: 1px solid #e5e7eb; }
        .checklist li:before { content: '✅ '; margin-right: 10px; color: #16a34a; }
    </style>
</head>
<body>
    <div class='container'>
        <h1>🔍 Comprehensive Database Diagnostic</h1>";

// Test multiple configurations systematically
$configurations = [
    'New Production' => [
        'host' => 'localhost',
        'user' => 'salemdominionmin_db',
        'password' => 'RwdT68fQ8FRgMcsrLyBB',
        'database' => 'salemdominionmin_db',
        'port' => 3306
    ],
    'Alternative Host 127.0.0.1' => [
        'host' => '127.0.0.1',
        'user' => 'salemdominionmin_db',
        'password' => 'RwdT68fQ8FRgMcsrLyBB',
        'database' => 'salemdominionmin_db',
        'port' => 3306
    ],
    'Root User Fallback' => [
        'host' => 'localhost',
        'user' => 'root',
        'password' => '',
        'database' => 'salemdominionmin_db',
        'port' => 3306
    ],
    'Root with Password' => [
        'host' => 'localhost',
        'user' => 'root',
        'password' => 'ReagaN23#',
        'database' => 'salemdominionmin_db',
        'port' => 3306
    ]
];

$working_config = null;
$any_success = false;

foreach ($configurations as $configName => $config) {
    echo "<div class='info'>";
    echo "<h2>🔍 Testing: $configName</h2>";
    echo "<p><strong>Host:</strong> " . htmlspecialchars($config['host']) . "</p>";
    echo "<p><strong>User:</strong> " . htmlspecialchars($config['user']) . "</p>";
    echo "<p><strong>Database:</strong> " . htmlspecialchars($config['database']) . "</p>";
    echo "<p><strong>Port:</strong> " . htmlspecialchars($config['port']) . "</p>";
    
    try {
        $conn = new mysqli($config['host'], $config['user'], $config['password'], $config['database'], $config['port']);
        
        if ($conn->connect_error) {
            echo "<div class='error'>";
            echo "<h3>❌ Connection Failed</h3>";
            echo "<p><strong>Error:</strong> " . htmlspecialchars($conn->connect_error) . "</p>";
            
            // Analyze specific error
            $error = $conn->connect_error;
            if (strpos($error, 'Access denied') !== false) {
                echo "<p><strong>Analysis:</strong> Wrong username, password, or permissions</p>";
            } elseif (strpos($error, 'Unknown database') !== false) {
                echo "<p><strong>Analysis:</strong> Database doesn't exist</p>";
            } elseif (strpos($error, "Can't connect") !== false) {
                echo "<p><strong>Analysis:</strong> MySQL server not running or wrong host/port</p>";
            }
            
            echo "</div>";
        } else {
            echo "<div class='success'>";
            echo "<h3>✅ Connection Successful!</h3>";
            echo "<p><strong>Status:</strong> Connected successfully!</p>";
            
            // Test database operations
            $result = $conn->query("SELECT VERSION() as version");
            if ($result && $row = $result->fetch_assoc()) {
                echo "<p><strong>MySQL Version:</strong> " . htmlspecialchars($row['version']) . "</p>";
            }
            
            // Test table access
            $tables = $conn->query("SHOW TABLES");
            if ($tables) {
                echo "<p><strong>Tables Found:</strong> " . $tables->num_rows . "</p>";
            }
            
            // Test create table
            $test_table = $conn->query("CREATE TABLE IF NOT EXISTS test_connection (id INT PRIMARY KEY)");
            if ($test_table) {
                echo "<p><strong>Write Access:</strong> ✅ Can create tables</p>";
                $conn->query("DROP TABLE IF EXISTS test_connection");
            }
            
            echo "</div>";
            $working_config = $config;
            $any_success = true;
        }
        
        $conn->close();
        
    } catch (Exception $e) {
        echo "<div class='error'>";
        echo "<h3>❌ Exception Error</h3>";
        echo "<p><strong>Error:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
        echo "</div>";
    }
    
    echo "</div>";
}

echo "
        <div class='warning'>
            <h2>🎯 Diagnostic Results</h2>";
            
if ($any_success) {
    echo "<div class='success'>";
    echo "<h3>✅ Working Configuration Found!</h3>";
    echo "<p><strong>Configuration:</strong> " . htmlspecialchars($working_config['host']) . " | " . htmlspecialchars($working_config['user']) . " | " . $working_config['database']) . "</p>";
    echo "<p><strong>Action:</strong> Update your config.php with these settings</p>";
    echo "</div>";
} else {
    echo "<div class='error'>";
    echo "<h3>❌ All Configurations Failed</h3>";
    echo "<p><strong>Issue:</strong> Database server may be down or credentials completely wrong</p>";
    echo "<p><strong>Action:</strong> Contact hosting support immediately</p>";
    echo "</div>";
}

echo "
            <div class='code-block'>
// Working Configuration to Use in config.php:
define('DB_HOST', '" . ($working_config['host'] ?? 'localhost') . "');
define('DB_USER', '" . ($working_config['user'] ?? 'salemdominionmin_db') . "');
define('DB_PASSWORD', '" . ($working_config['password'] ?? 'RwdT68fQ8FRgMcsrLyBB') . "');
define('DB_NAME', '" . ($working_config['database'] ?? 'salemdominionmin_db') . "');
define('DB_PORT', " . ($working_config['port'] ?? 3306) . ");
            </div>
        </div>

        <div class='info'>
            <h2>🔧 Additional Troubleshooting Steps</h2>
            <ul class='checklist'>
                <li><strong>Check MySQL Service:</strong> Ensure MySQL server is running</li>
                <li><strong>Verify Database Exists:</strong> Check in phpMyAdmin</li>
                <li><strong>User Permissions:</strong> Grant ALL PRIVILEGES to user</li>
                <li><strong>Firewall Issues:</strong> Check if port 3306 is blocked</li>
                <li><strong>Host Configuration:</strong> Try different database hosts</li>
                <li><strong>Hosting Support:</strong> Contact your hosting provider</li>
            </ul>
        </div>

        <div style='text-align: center; margin-top: 40px; padding: 30px; background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%); border-radius: 15px; color: white;'>
            <h3 style='color: white; margin-bottom: 20px;'>🔍 Diagnostic Complete</h3>
            <p style='color: white; margin-bottom: 20px;'>Use the working configuration above!</p>
            <a href='index.php' class='btn' style='background: white; color: #0ea5e9;'>🏠 Back to Website</a>
        </div>
    </div>
</body>
</html>";
?>
