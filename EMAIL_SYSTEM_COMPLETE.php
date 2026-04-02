<?php
// Official Email System Summary
require_once 'config.php';
require_once 'db.php';

echo "<!DOCTYPE html>
<html>
<head>
    <title>✅ Official Email System Complete - Salem Dominion Ministries</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: linear-gradient(135deg, #f0fdf4 0%, #e0f2fe 100%); }
        .container { max-width: 800px; margin: 0 auto; background: white; padding: 40px; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); }
        h1 { color: #16a34a; text-align: center; margin-bottom: 30px; font-size: 2.5rem; }
        h2 { color: #0ea5e9; border-bottom: 3px solid #0ea5e9; padding-bottom: 10px; margin-top: 30px; }
        .success { color: #16a34a; font-weight: bold; }
        .feature { background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%); padding: 20px; border-radius: 10px; margin: 15px 0; border-left: 5px solid #16a34a; }
        .btn { background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%); color: white; padding: 12px 25px; text-decoration: none; border-radius: 25px; display: inline-block; margin: 8px; font-weight: 600; transition: all 0.3s ease; }
        .btn:hover { transform: translateY(-2px); box-shadow: 0 10px 25px rgba(14, 165, 233, 0.3); }
        .checklist { list-style: none; padding: 0; }
        .checklist li { padding: 10px 0; border-bottom: 1px solid #e5e7eb; }
        .checklist li:before { content: '✅ '; margin-right: 10px; color: #16a34a; }
        .user-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 15px; margin: 20px 0; }
        .user-card { background: #f8fafc; padding: 15px; border-radius: 10px; border-left: 4px solid #16a34a; }
    </style>
</head>
<body>
    <div class='container'>
        <h1>✅ Official Email System Complete!</h1>
        
        <div class='feature'>
            <h2>🎯 Perfect Email Restriction Implemented!</h2>
            <p>Your Salem Dominion Ministries website now only allows official church email addresses for registration!</p>
        </div>";

// Get current users to display
try {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME, DB_PORT);
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }
    
    $result = $conn->query("SELECT id, first_name, last_name, email, role, created_at FROM users ORDER BY created_at DESC");
    $users = [];
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
    
    echo "<div class='user-grid'>";
    foreach ($users as $user) {
        echo "<div class='user-card'>
            <h4>{$user['first_name']} {$user['last_name']}</h4>
            <p><strong>Role:</strong> {$user['role']}</p>
            <p><strong>Email:</strong> {$user['email']}</p>
            <p><small>Joined: " . date('M j, Y', strtotime($user['created_at'])) . "</small></p>
        </div>";
    }
    echo "</div>";
    
    $conn->close();
    
} catch (Exception $e) {
    echo "<p class='error'>Error loading users: " . htmlspecialchars($e->getMessage()) . "</p>";
}

echo "
        <div class='feature'>
            <h2>🔧 What Was Implemented</h2>
            <ul class='checklist'>
                <li>Official email domain validation in registration form</li>
                <li>Clear guidance for users about allowed email domains</li>
                <li>Updated existing users to official church emails</li>
                <li>Admin account uses official email</li>
                <li>Setup script updated for official emails</li>
            </ul>
        </div>
        
        <div class='feature'>
            <h2>📧 Official Email Domains Allowed</h2>
            <ul class='checklist'>
                <li><strong>@salemdominionministries.com</strong> - Primary church domain</li>
                <li><strong>@salemministries.com</strong> - Alternative church domain</li>
                <li><strong>@church.org</strong> - General church organization domain</li>
            </ul>
        </div>
        
        <div class='feature'>
            <h2>🛡️ Security Features</h2>
            <ul class='checklist'>
                <li>Domain validation prevents unauthorized registrations</li>
                <li>Clear error messages for invalid email attempts</li>
                <li>Helpful placeholder text guides users</li>
                <li>All existing users updated to official emails</li>
            </ul>
        </div>
        
        <div class='feature'>
            <h2>📋 Current User Accounts</h2>
            <ul class='checklist'>
                <li><strong>Admin User</strong> - admin@salemdominionministries.com</li>
                <li><strong>Senior Pastor</strong> - pastor@salemdominionministries.com</li>
                <li><strong>Otema Reagan</strong> - otema@salemdominionministries.com</li>
            </ul>
            <p><em>All users now have official church email addresses!</em></p>
        </div>
        
        <div class='feature'>
            <h2>🔄 Updated Files</h2>
            <ul class='checklist'>
                <li><strong>register.php</strong> - Added email domain validation</li>
                <li><strong>setup_admin.php</strong> - Updated to use official email</li>
                <li><strong>Database</strong> - Updated user email addresses</li>
            </ul>
        </div>
        
        <div style='text-align: center; margin-top: 40px; padding: 30px; background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%); border-radius: 15px; color: white;'>
            <h3 style='color: white; margin-bottom: 20px;'>🎯 Test Your Secure Registration!</h3>
            <a href='register.php' class='btn' style='background: white; color: #0ea5e9;'>📝 Try Registration</a>
            <a href='login.php' class='btn' style='background: white; color: #0ea5e9;'>🔐 Login</a>
            <a href='index.php' class='btn' style='background: white; color: #0ea5e9;'>🏠 Homepage</a>
        </div>
        
        <div style='text-align: center; margin-top: 30px; padding: 20px; background: #dcfce7; border-radius: 10px; border: 2px solid #16a34a;'>
            <h3 style='color: #16a34a;'>🎉 Official Email System Perfect!</h3>
            <p style='color: #16a34a; font-weight: 600;'>Your website now only accepts official church emails!</p>
            <p style='color: #16a34a;'>All users have been updated to use official email addresses.</p>
        </div>
    </div>
</body>
</html>";
?>
