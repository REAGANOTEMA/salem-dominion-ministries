<?php
// Enhanced Database Connection Test with Multiple Fallbacks
header('Content-Type: text/html; charset=utf-8');

echo "<!DOCTYPE html>
<html>
<head>
    <title>🔧 Enhanced Database Test - Salem Dominion Ministries</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: linear-gradient(135deg, #f0fdf4 0%, #e0f2fe 100%); }
        .container { max-width: 1000px; margin: 0 auto; background: white; padding: 40px; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); }
        h1 { color: #16a34a; text-align: center; margin-bottom: 30px; font-size: 2.5rem; }
        .success { background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%); padding: 20px; border-radius: 10px; margin: 15px 0; border-left: 5px solid #16a34a; }
        .error { background: linear-gradient(135deg, #fef2f2 0%, #fecaca 100%); padding: 20px; border-radius: 10px; margin: 15px 0; border-left: 5px solid #dc2626; }
        .warning { background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%); padding: 20px; border-radius: 10px; margin: 15px 0; border-left: 5px solid #f59e0b; }
        .btn { background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%); color: white; padding: 12px 25px; text-decoration: none; border-radius: 25px; display: inline-block; margin: 8px; font-weight: 600; transition: all 0.3s ease; }
        .btn:hover { transform: translateY(-2px); box-shadow: 0 10px 25px rgba(14, 165, 233, 0.3); }
        .code-block { background: #1e293b; color: #e2e8f0; padding: 15px; border-radius: 10px; font-family: monospace; font-size: 0.85rem; margin: 10px 0; overflow-x: auto; }
    </style>
</head>
<body>
    <div class='container'>
        <h1>🔧 Enhanced Database Connection Test</h1>";

// Test multiple connection attempts with different configurations
$test_configs = [
    'Test 1: Root with Empty Password' => [
        'host' => 'localhost',
        'user' => 'root',
        'password' => '',
        'database' => 'salem_dominion_ministries',
        'port' => 3306
    ],
    'Test 2: Root with Password' => [
        'host' => 'localhost',
        'user' => 'root',
        'password' => 'ReagaN23#',
        'database' => 'salem_dominion_ministries',
        'port' => 3306
    ],
    'Test 3: Production User' => [
        'host' => 'localhost',
        'user' => 'salemdominionmin_db',
        'password' => 'RwdT68fQ8FRgMcsrLyBB',
        'database' => 'salemdominionmin_db',
        'port' => 3306
    ],
    'Test 4: Alternative Port 3307' => [
        'host' => 'localhost',
        'user' => 'root',
        'password' => '',
        'database' => 'salem_dominion_ministries',
        'port' => 3307
    ]
];

$working_config = null;
$connection_results = [];

foreach ($test_configs as $test_name => $config) {
    echo "<div class='warning'>";
    echo "<h2>🔍 $test_name</h2>";
    echo "<p><strong>Host:</strong> " . htmlspecialchars($config['host']) . "</p>";
    echo "<p><strong>User:</strong> " . htmlspecialchars($config['user']) . "</p>";
    echo "<p><strong>Password:</strong> " . (empty($config['password']) ? '(empty)' : str_repeat('*', strlen($config['password']))) . "</p>";
    echo "<p><strong>Database:</strong> " . htmlspecialchars($config['database']) . "</p>";
    echo "<p><strong>Port:</strong> " . htmlspecialchars($config['port']) . "</p>";
    
    try {
        $conn = new mysqli($config['host'], $config['user'], $config['password'], $config['database'], $config['port']);
        
        if ($conn->connect_error) {
            echo "<div class='error'>";
            echo "<h3>❌ Failed</h3>";
            echo "<p><strong>Error:</strong> " . htmlspecialchars($conn->connect_error) . "</p>";
            
            // Provide specific guidance based on error
            $error = strtolower($conn->connect_error);
            if (strpos($error, 'access denied') !== false) {
                echo "<p><strong>Guidance:</strong> Check username/password or user permissions</p>";
            } elseif (strpos($error, 'can\'t connect') !== false) {
                echo "<p><strong>Guidance:</strong> MySQL service not running or wrong host/port</p>";
            } elseif (strpos($error, 'unknown database') !== false) {
                echo "<p><strong>Guidance:</strong> Database doesn't exist</p>";
            }
            
            echo "</div>";
            $connection_results[$test_name] = 'FAILED: ' . $conn->connect_error;
        } else {
            echo "<div class='success'>";
            echo "<h3>✅ SUCCESS!</h3>";
            echo "<p><strong>Status:</strong> Connected successfully!</p>";
            
            // Test basic operations
            $version_result = $conn->query("SELECT VERSION() as version");
            if ($version_result && $row = $version_result->fetch_assoc()) {
                echo "<p><strong>MySQL Version:</strong> " . htmlspecialchars($row['version']) . "</p>";
            }
            
            // Test table creation
            $test_result = $conn->query("CREATE TABLE IF NOT EXISTS test_table (id INT PRIMARY KEY)");
            if ($test_result) {
                echo "<p><strong>Write Access:</strong> ✅ Can create tables</p>";
                $conn->query("DROP TABLE IF EXISTS test_table");
            }
            
            echo "</div>";
            $working_config = $config;
            $connection_results[$test_name] = 'SUCCESS';
        }
        
        $conn->close();
        
    } catch (Exception $e) {
        echo "<div class='error'>";
        echo "<h3>❌ Exception</h3>";
        echo "<p><strong>Error:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
        echo "</div>";
        $connection_results[$test_name] = 'EXCEPTION: ' . $e->getMessage();
    }
    
    echo "</div>";
}

echo "
        <div class='success'>
            <h2>🎯 Test Results Summary</h2>";
            
if ($working_config) {
    echo "<div class='code-block'>";
    echo "<strong>✅ WORKING CONFIGURATION FOUND:</strong><br><br>";
    echo "Host: " . htmlspecialchars($working_config['host']) . "<br>";
    echo "User: " . htmlspecialchars($working_config['user']) . "<br>";
    echo "Password: " . (empty($working_config['password']) ? '(empty)' : str_repeat('*', strlen($working_config['password']))) . "<br>";
    echo "Database: " . htmlspecialchars($working_config['database']) . "<br>";
    echo "Port: " . htmlspecialchars($working_config['port']) . "<br><br>";
    echo "<strong>UPDATE YOUR CONFIG.PHP:</strong><br>";
    echo "define('DB_HOST', '" . $working_config['host'] . "');<br>";
    echo "define('DB_USER', '" . $working_config['user'] . "');<br>";
    echo "define('DB_PASSWORD', '" . $working_config['password'] . "');<br>";
    echo "define('DB_NAME', '" . $working_config['database'] . "');<br>";
    echo "define('DB_PORT', " . $working_config['port'] . ");<br>";
    echo "</div>";
} else {
    echo "<div class='error'>";
    echo "<h3>❌ ALL CONNECTIONS FAILED</h3>";
    echo "<p><strong>Issue:</strong> MySQL service may be down or completely misconfigured</p>";
    echo "</div>";
}

echo "
            <div class='warning'>
                <h2>🔧 All Test Results:</h2>
                <div class='code-block'>";
foreach ($connection_results as $test_name => $result) {
    echo "$test_name: $result<br>";
}
echo "
                </div>
            </div>

        <div style='text-align: center; margin-top: 40px; padding: 30px; background: linear-gradient(135deg, #16a34a 0%, #15803d 100%); border-radius: 15px; color: white;'>
            <h3 style='color: white; margin-bottom: 20px;'>🔧 Enhanced Test Complete</h3>
            <p style='color: white; margin-bottom: 20px;'>Use the working configuration above!</p>
            <a href='index.php' class='btn' style='background: white; color: #16a34a;'>🏠 Back to Website</a>
        </div>
    </div>
</body>
</html>";
?>
