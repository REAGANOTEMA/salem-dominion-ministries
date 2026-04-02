<?php
/**
 * Salem Dominion Ministries - Database Setup Script
 * Run this script once to set up your database on hosting platform
 */

// Database configuration - update these values
$db_host = 'localhost'; // or your hosting database host
$db_user = 'root'; // your hosting database username  
$db_password = ''; // your hosting database password
$db_name = 'salem_dominion_ministries'; // your database name

echo "<h2>🗄️ Salem Dominion Ministries - Database Setup</h2>";

try {
    // Connect to MySQL
    $conn = new mysqli($db_host, $db_user, $db_password);
    
    if ($conn->connect_error) {
        die("<p style='color: red;'>❌ Connection failed: " . $conn->connect_error . "</p>");
    }
    
    echo "<p style='color: green;'>✅ Connected to MySQL successfully</p>";
    
    // Create database if it doesn't exist
    $conn->query("CREATE DATABASE IF NOT EXISTS $db_name CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    $conn->select_db($db_name);
    echo "<p style='color: green;'>✅ Database '$db_name' ready</p>";
    
    // Read and execute the schema
    $schema_file = __DIR__ . '/backend/database/unified_schema_fixed.sql';
    if (!file_exists($schema_file)) {
        die("<p style='color: red;'>❌ Schema file not found: $schema_file</p>");
    }
    
    $schema = file_get_contents($schema_file);
    
    // Split into individual statements
    $statements = array_filter(array_map('trim', explode(';', $schema)));
    
    $success_count = 0;
    $error_count = 0;
    
    foreach ($statements as $statement) {
        if (empty($statement)) continue;
        
        // Skip CREATE DATABASE statement since we already created it
        if (stripos($statement, 'CREATE DATABASE') !== false) continue;
        
        if ($conn->query($statement)) {
            $success_count++;
            echo "<p style='color: green;'>✅ Table created successfully</p>";
        } else {
            $error_count++;
            echo "<p style='color: orange;'>⚠️ Warning: " . $conn->error . "</p>";
        }
    }
    
    echo "<h3>📊 Setup Summary:</h3>";
    echo "<p>✅ Successful statements: $success_count</p>";
    echo "<p>⚠️ Warnings: $error_count</p>";
    
    if ($success_count > 0) {
        echo "<h3 style='color: green;'>🎉 Database Setup Complete!</h3>";
        echo "<p>Your Salem Dominion Ministries database is ready for use.</p>";
        echo "<p><strong>Next Steps:</strong></p>";
        echo "<ol>";
        echo "<li>Remove or rename this deploy.php file for security</li>";
        echo "<li>Test your website at your domain</li>";
        echo "<li>Check that all pages load correctly</li>";
        echo "<li>Test the contact forms and registration</li>";
        echo "</ol>";
    }
    
    $conn->close();
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error: " . $e->getMessage() . "</p>";
}
?>

<style>
body { font-family: Arial, sans-serif; max-width: 800px; margin: 50px auto; padding: 20px; }
h2 { color: #2c3e50; border-bottom: 2px solid #3498db; padding-bottom: 10px; }
h3 { color: #27ae60; margin-top: 20px; }
p { margin: 10px 0; line-height: 1.6; }
ol { margin-left: 20px; }
</style>
