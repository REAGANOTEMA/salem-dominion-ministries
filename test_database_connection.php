<?php
// Simple Database Connection Test for Hosting
header('Content-Type: text/html; charset=utf-8');

echo "<!DOCTYPE html>
<html>
<head>
    <title>Database Connection Test - Salem Dominion Ministries</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: linear-gradient(135deg, #f0fdf4 0%, #e0f2fe 100%); }
        .container { max-width: 800px; margin: 0 auto; background: white; padding: 40px; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); }
        h1 { color: #16a34a; text-align: center; margin-bottom: 30px; font-size: 2rem; }
        .success { background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%); padding: 20px; border-radius: 10px; margin: 15px 0; border-left: 5px solid #16a34a; }
        .error { background: linear-gradient(135deg, #fef2f2 0%, #fecaca 100%); padding: 20px; border-radius: 10px; margin: 15px 0; border-left: 5px solid #dc2626; }
        .warning { background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%); padding: 20px; border-radius: 10px; margin: 15px 0; border-left: 5px solid #f59e0b; }
        .code-block { background: #1e293b; color: #e2e8f0; padding: 15px; border-radius: 10px; font-family: monospace; font-size: 0.85rem; margin: 10px 0; overflow-x: auto; }
        .btn { background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%); color: white; padding: 12px 25px; text-decoration: none; border-radius: 25px; display: inline-block; margin: 8px; font-weight: 600; transition: all 0.3s ease; }
        .btn:hover { transform: translateY(-2px); box-shadow: 0 10px 25px rgba(14, 165, 233, 0.3); }
    </style>
</head>
<body>
    <div class='container'>
        <h1>🔍 Database Connection Test</h1>";

// Test multiple database configurations
$configurations = [
    'Production Settings' => [
        'host' => 'localhost',
        'user' => 'salemdominionmin_db',
        'password' => '22uHzNYEHwUsFKdVz3wT',
        'database' => 'salem_dominion_ministries',
        'port' => 3306
    ],
    'Alternative Production' => [
        'host' => 'localhost',
        'user' => 'root',
        'password' => 'ReagaN23#',
        'database' => 'salem_dominion_ministries',
        'port' => 3306
    ],
    'Local Settings' => [
        'host' => 'localhost',
        'user' => 'root',
        'password' => '',
        'database' => 'salem_dominion_ministries',
        'port' => 3306
    ]
];

$anySuccess = false;

foreach ($configurations as $configName => $config) {
    echo "<div class='warning'>";
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
            echo "</div>";
        } else {
            echo "<div class='success'>";
            echo "<h3>✅ Connection Successful!</h3>";
            echo "<p><strong>Status:</strong> Connected successfully</p>";
            
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
            
            echo "</div>";
            $anySuccess = true;
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

if ($anySuccess) {
    echo "<div class='success'>";
    echo "<h2>🎉 At least one configuration works!</h2>";
    echo "<p>Use the successful configuration in your config.php file.</p>";
    echo "</div>";
} else {
    echo "<div class='error'>";
    echo "<h2>❌ All configurations failed</h2>";
    echo "<p>You need to get correct database credentials from your hosting provider.</p>";
    echo "</div>";
}

echo "
        <div class='warning'>
            <h2>🔧 Next Steps</h2>";
            
if ($anySuccess) {
    echo "<p><strong>✅ Update your config.php</strong> with the working configuration above.</p>";
} else {
    echo "<ul>";
    echo "<li><strong>1. Contact Hosting Support:</strong> Get correct database credentials</li>";
    echo "<li><strong>2. Check cPanel:</strong> Look for 'MySQL Databases' section</li>";
    echo "<li><strong>3. Update .env file:</strong> Use correct credentials</li>";
    echo "<li><strong>4. Test Again:</strong> Run this test after updating</li>";
    echo "</ul>";
}

echo "
            <div class='code-block'>
// Working Configuration Example:
define('DB_HOST', 'localhost');
define('DB_USER', 'salemdominionmin_db');
define('DB_PASSWORD', '22uHzNYEHwUsFKdVz3wT');
define('DB_NAME', 'salem_dominion_ministries');
define('DB_PORT', 3306);
            </div>
        </div>
        
        <div style='text-align: center; margin-top: 40px; padding: 30px; background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%); border-radius: 15px; color: white;'>
            <h3 style='color: white; margin-bottom: 20px;'>🔧 Database Connection Test Complete</h3>
            <p style='color: white; margin-bottom: 20px;'>Use the working configuration to fix your website!</p>
            <a href='index.php' class='btn' style='background: white; color: #0ea5e9;'>🏠 Back to Website</a>
            <a href='config.php' class='btn' style='background: white; color: #0ea5e9;'>🔧 Edit config.php</a>
        </div>
    </div>
</body>
</html>";
?>
