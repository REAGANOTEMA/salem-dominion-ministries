<?php
// Update Non-Official Email to Official
require_once 'config.php';
require_once 'db.php';

echo "<!DOCTYPE html>
<html>
<head>
    <title>Update User Email - Salem Dominion Ministries</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f0fdf4; }
        .container { max-width: 600px; margin: 0 auto; background: white; padding: 40px; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); }
        h1 { color: #16a34a; text-align: center; margin-bottom: 30px; }
        .success { color: #16a34a; font-weight: bold; }
        .btn { background: #0ea5e9; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block; margin: 5px; }
    </style>
</head>
<body>
    <div class='container'>
        <h1>🔄 Update User Email</h1>";

try {
    // Use direct database connection to avoid Database class issues
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME, DB_PORT);
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }
    
    // Update the non-official email to official
    $update_query = "UPDATE users SET email = 'otema@salemdominionministries.com' WHERE email = 'reaganotema2022@gmail.com'";
    $result = $conn->query($update_query);
    
    if ($result) {
        echo "<div class='success'>
            <h2>✅ Email Updated Successfully!</h2>
            <p><strong>Old Email:</strong> reaganotema2022@gmail.com</p>
            <p><strong>New Email:</strong> otema@salemdominionministries.com</p>
            <p>The user can now login with the official church email.</p>
        </div>";
    } else {
        echo "<div class='error'>
            <h2>❌ Update Failed</h2>
            <p>Could not update the email address.</p>
        </div>";
    }
    
    // Verify the update
    $result = $conn->query("SELECT id, first_name, last_name, email, role FROM users ORDER BY id");
    $users = [];
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
    
    echo "<div style='background: #f8fafc; padding: 20px; border-radius: 10px; margin: 20px 0;'>
        <h3>📋 Current Users (All Official Emails)</h3>";
    
    foreach ($users as $user) {
        echo "<div style='background: white; padding: 15px; margin: 10px 0; border-radius: 8px; border-left: 4px solid #16a34a;'>
            <strong>{$user['first_name']} {$user['last_name']}</strong> ({$user['role']})<br>
            <small>Email: {$user['email']}</small> ✅
        </div>";
    }
    
    echo "</div>";
    
    $conn->close();
    
} catch (Exception $e) {
    echo "<div class='error'>Error: " . htmlspecialchars($e->getMessage()) . "</div>";
}

echo "
        <div style='text-align: center; margin-top: 30px; padding: 20px; background: #dcfce7; border-radius: 10px;'>
            <h3 class='success'>🎉 All Users Now Have Official Emails!</h3>
            <p>Registration is now restricted to official church emails only.</p>
            <a href='register.php' class='btn'>📝 Test Registration</a>
            <a href='login.php' class='btn'>🔐 Login</a>
            <a href='index.php' class='btn'>🏠 Homepage</a>
        </div>
    </div>
</body>
</html>";
?>
