<?php
// Complete error suppression to prevent unwanted output
error_reporting(0);
ini_set('display_errors', 0);
ini_set('log_errors', 0);

// Buffer output to catch any accidental output
ob_start();

// Database connection
try {
    $conn = new mysqli('localhost', 'root', '', 'salem_dominion');
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    // Create users table if it doesn't exist
    $conn->query("CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        first_name VARCHAR(100) NOT NULL,
        last_name VARCHAR(100) NOT NULL,
        email VARCHAR(255) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        phone VARCHAR(20),
        role ENUM('member', 'pastor', 'admin') DEFAULT 'member',
        is_active BOOLEAN DEFAULT TRUE,
        bio TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )");
    
    // Check if admin user already exists
    $check_admin = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $check_admin->bind_param('s', 'reaganotema2022@gmail.com');
    $check_admin->execute();
    $result = $check_admin->get_result();
    
    if ($result->num_rows == 0) {
        // Insert admin user
        $hashed_password = password_hash('ReagaN23#', PASSWORD_DEFAULT);
        
        $insert_admin = $conn->prepare("INSERT INTO users (first_name, last_name, email, password, role, is_active) VALUES (?, ?, ?, ?, 'admin', TRUE)");
        $insert_admin->bind_param('ssss', 'Reagan', 'Otema', 'reaganotema2022@gmail.com', $hashed_password);
        $insert_admin->execute();
        
        echo "<div style='background: #d4edda; color: #155724; padding: 20px; margin: 20px; border-radius: 10px; font-family: Arial, sans-serif;'>";
        echo "<h2 style='color: #155724;'>✅ Admin Account Created Successfully!</h2>";
        echo "<p><strong>Email:</strong> reaganotema2022@gmail.com</p>";
        echo "<p><strong>Password:</strong> ReagaN23#</p>";
        echo "<p><strong>Role:</strong> Administrator</p>";
        echo "<hr style='margin: 20px 0;'>";
        echo "<h3>Next Steps:</h3>";
        echo "<ol>";
        echo "<li><a href='login.php' style='color: #155724; text-decoration: underline;'>Login to your admin dashboard</a></li>";
        echo "<li>Manage users and permissions</li>";
        echo "<li>Access all admin features</li>";
        echo "</ol>";
        echo "</div>";
    } else {
        echo "<div style='background: #f8d7da; color: #721c24; padding: 20px; margin: 20px; border-radius: 10px; font-family: Arial, sans-serif;'>";
        echo "<h2 style='color: #721c24;'>ℹ️ Admin Account Already Exists</h2>";
        echo "<p>The admin account reaganotema2022@gmail.com is already set up.</p>";
        echo "<p><a href='login.php' style='color: #721c24; text-decoration: underline;'>Click here to login</a></p>";
        echo "</div>";
    }
    
    $conn->close();
    
} catch (Exception $e) {
    echo "<div style='background: #f8d7da; color: #721c24; padding: 20px; margin: 20px; border-radius: 10px; font-family: Arial, sans-serif;'>";
    echo "<h2 style='color: #721c24;'>❌ Database Error</h2>";
    echo "<p>" . $e->getMessage() . "</p>";
    echo "</div>";
}

// Clean any buffered output
ob_end_flush();
?>
