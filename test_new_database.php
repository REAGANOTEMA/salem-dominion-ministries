<?php
// Database Connection Test with New Credentials
header('Content-Type: text/html; charset=utf-8');

echo "<!DOCTYPE html>
<html>
<head>
    <title>Database Test - New Credentials</title>
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

// Test with new credentials
$new_credentials = [
    'host' => 'localhost',
    'user' => 'salemdominionmin_db',
    'password' => 'RwdT68fQ8FRgMcsrLyBB',
    'database' => 'salemdominionmin_db',
    'port' => 3306
];

echo "<div class='warning'>";
echo "<h2>🔍 Testing New Database Credentials</h2>";
echo "<p><strong>Host:</strong> " . htmlspecialchars($new_credentials['host']) . "</p>";
echo "<p><strong>User:</strong> " . htmlspecialchars($new_credentials['user']) . "</p>";
echo "<p><strong>Database:</strong> " . htmlspecialchars($new_credentials['database']) . "</p>";
echo "<p><strong>Password:</strong> " . str_repeat('*', strlen($new_credentials['password'])) . "</p>";
echo "<p><strong>Port:</strong> " . htmlspecialchars($new_credentials['port']) . "</p>";

try {
    $conn = new mysqli($new_credentials['host'], $new_credentials['user'], $new_credentials['password'], $new_credentials['database'], $new_credentials['port']);
    
    if ($conn->connect_error) {
        echo "<div class='error'>";
        echo "<h3>❌ Connection Failed</h3>";
        echo "<p><strong>Error:</strong> " . htmlspecialchars($conn->connect_error) . "</p>";
        echo "</div>";
    } else {
        echo "<div class='success'>";
        echo "<h3>✅ Connection Successful!</h3>";
        echo "<p><strong>Status:</strong> Connected successfully to new database!</p>";
        
        // Test database operations
        $result = $conn->query("SELECT VERSION() as version");
        if ($result && $row = $result->fetch_assoc()) {
            echo "<p><strong>MySQL Version:</strong> " . htmlspecialchars($row['version']) . "</p>";
        }
        
        // Test table access
        $tables = $conn->query("SHOW TABLES");
        if ($tables) {
            echo "<p><strong>Tables Found:</strong> " . $tables->num_rows . "</p>";
            echo "<p><strong>Database Ready:</strong> All systems operational!</p>";
        }
        
        echo "</div>";
    }
    
    $conn->close();
    
} catch (Exception $e) {
    echo "<div class='error'>";
    echo "<h3>❌ Exception Error</h3>";
    echo "<p><strong>Error:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "</div>";
}

echo "</div>";

echo "
        <div class='success'>
            <h2>🎉 Database Configuration Status</h2>";
            
if ($conn && !$conn->connect_error) {
    echo "<p><strong>✅ SUCCESS:</strong> New database credentials are working!</p>";
    echo "<p><strong>📁 Files Updated:</strong> .env and config.php updated</p>";
    echo "<p><strong>🌐 Ready for Production:</strong> Upload to hosting server</p>";
} else {
    echo "<p><strong>❌ ISSUE:</strong> Connection still failing</p>";
    echo "<p><strong>🔧 Check:</strong> Database exists and user permissions</p>";
}

echo "
            <div class='code-block'>
// New Working Configuration:
define('DB_HOST', 'localhost');
define('DB_USER', 'salemdominionmin_db');
define('DB_PASSWORD', 'RwdT68fQ8FRgMcsrLyBB');
define('DB_NAME', 'salemdominionmin_db');
define('DB_PORT', 3306);
            </div>
        </div>
        
        <div style='text-align: center; margin-top: 40px; padding: 30px; background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%); border-radius: 15px; color: white;'>
            <h3 style='color: white; margin-bottom: 20px;'>🔍 Database Test Complete</h3>
            <p style='color: white; margin-bottom: 20px;'>Test results show above!</p>
            <a href='index.php' class='btn' style='background: white; color: #0ea5e9;'>🏠 Back to Website</a>
        </div>
    </div>
</body>
</html>";
?>
