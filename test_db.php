<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>Database Connection Test</h2>";

try {
    require_once 'config.php';
    
    echo "<p><strong>Environment:</strong> " . APP_ENV . "</p>";
    echo "<p><strong>Database Host:</strong> " . DB_HOST . "</p>";
    echo "<p><strong>Database Name:</strong> " . DB_NAME . "</p>";
    echo "<p><strong>Database User:</strong> " . DB_USER . "</p>";
    
    // Test database connection
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME, DB_PORT);
    
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }
    
    echo "<p style='color: green;'><strong>✓ Database Connection: SUCCESS</strong></p>";
    
    // Check if users table exists
    $result = $conn->query("SHOW TABLES LIKE 'users'");
    if ($result->num_rows > 0) {
        echo "<p style='color: green;'><strong>✓ Users table exists</strong></p>";
        
        // Count users
        $count_result = $conn->query("SELECT COUNT(*) as count FROM users");
        $count = $count_result->fetch_assoc()['count'];
        echo "<p><strong>Total users:</strong> " . $count . "</p>";
        
        // Show sample users (without passwords)
        if ($count > 0) {
            echo "<h3>Sample Users:</h3>";
            $users_result = $conn->query("SELECT id, first_name, last_name, email, role, is_active FROM users LIMIT 5");
            echo "<table border='1'><tr><th>ID</th><th>Name</th><th>Email</th><th>Role</th><th>Active</th></tr>";
            while ($row = $users_result->fetch_assoc()) {
                echo "<tr><td>" . $row['id'] . "</td><td>" . $row['first_name'] . ' ' . $row['last_name'] . "</td><td>" . $row['email'] . "</td><td>" . $row['role'] . "</td><td>" . ($row['is_active'] ? 'Yes' : 'No') . "</td></tr>";
            }
            echo "</table>";
        }
    } else {
        echo "<p style='color: red;'><strong>✗ Users table does not exist</strong></p>";
    }
    
    $conn->close();
    
} catch (Exception $e) {
    echo "<p style='color: red;'><strong>✗ Error:</strong> " . $e->getMessage() . "</p>";
}
?>
