<?php
// Create Communications Tables
require_once 'config.php';
require_once 'db.php';

echo "<!DOCTYPE html>
<html>
<head>
    <title>Setup Communications System - Salem Dominion Ministries</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f0fdf4; }
        .container { max-width: 800px; margin: 0 auto; background: white; padding: 40px; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); }
        h1 { color: #16a34a; text-align: center; margin-bottom: 30px; }
        .success { color: #16a34a; font-weight: bold; }
        .error { color: #dc2626; font-weight: bold; }
        .sql-block { background: #1e293b; color: #e2e8f0; padding: 20px; border-radius: 8px; font-family: 'Courier New', monospace; margin: 15px 0; overflow-x: auto; }
        .btn { background: #0ea5e9; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block; margin: 10px 5px; }
    </style>
</head>
<body>
    <div class='container'>
        <h1>📧 Setup Communications System</h1>";

try {
    // Use direct database connection
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME, DB_PORT);
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }
    
    // Create admin_communications table
    $sql1 = "CREATE TABLE IF NOT EXISTS admin_communications (
        id int NOT NULL AUTO_INCREMENT,
        title varchar(255) NOT NULL,
        content text NOT NULL,
        target_audience enum('all','admins','pastors','members') DEFAULT 'all',
        priority enum('high','normal','low') DEFAULT 'normal',
        sent_by int NOT NULL,
        sent_at timestamp DEFAULT CURRENT_TIMESTAMP,
        created_at timestamp DEFAULT CURRENT_TIMESTAMP,
        updated_at timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (id),
        KEY idx_sent_by (sent_by),
        KEY idx_sent_at (sent_at)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
    
    if ($conn->query($sql1)) {
        echo "<div class='success'>✅ Admin Communications table created successfully!</div>";
    } else {
        echo "<div class='error'>❌ Error creating admin_communications table: " . $conn->error . "</div>";
    }
    
    // Create user_communications table
    $sql2 = "CREATE TABLE IF NOT EXISTS user_communications (
        id int NOT NULL AUTO_INCREMENT,
        communication_id int NOT NULL,
        user_id int NOT NULL,
        status enum('sent','read','archived') DEFAULT 'sent',
        received_at timestamp DEFAULT CURRENT_TIMESTAMP,
        read_at timestamp NULL DEFAULT NULL,
        PRIMARY KEY (id),
        KEY idx_communication_id (communication_id),
        KEY idx_user_id (user_id),
        KEY idx_status (status),
        FOREIGN KEY (communication_id) REFERENCES admin_communications(id) ON DELETE CASCADE,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
    
    if ($conn->query($sql2)) {
        echo "<div class='success'>✅ User Communications table created successfully!</div>";
    } else {
        echo "<div class='error'>❌ Error creating user_communications table: " . $conn->error . "</div>";
    }
    
    // Add bio column to users table if it doesn't exist
    $sql3 = "ALTER TABLE users ADD COLUMN bio TEXT AFTER avatar_url";
    $result = $conn->query($sql3);
    if ($result) {
        echo "<div class='success'>✅ Bio column added to users table!</div>";
    } else {
        // Column might already exist, check if it's there
        $columns = $conn->query("SHOW COLUMNS FROM users LIKE 'bio'");
        if ($columns->num_rows > 0) {
            echo "<div class='success'>✅ Bio column already exists in users table!</div>";
        }
    }
    
    echo "<div class='sql-block'>
        <h3>📋 Tables Created:</h3>
        <strong>admin_communications:</strong> Stores admin messages<br>
        <strong>user_communications:</strong> Tracks which users received messages<br>
        <strong>users:</strong> Enhanced with bio column
    </div>";
    
    $conn->close();
    
} catch (Exception $e) {
    echo "<div class='error'>Error: " . htmlspecialchars($e->getMessage()) . "</div>";
}

echo "
        <div style='text-align: center; margin-top: 30px; padding: 20px; background: #dcfce7; border-radius: 10px;'>
            <h3 class='success'>🎉 Communications System Ready!</h3>
            <p>Database tables created successfully!</p>
            <a href='admin_communications.php' class='btn'>📧 Admin Communications</a>
            <a href='profile_complete.php' class='btn'>👤 User Profile</a>
            <a href='login_complete.php' class='btn'>🔐 Login System</a>
        </div>
    </div>
</body>
</html>";
?>
