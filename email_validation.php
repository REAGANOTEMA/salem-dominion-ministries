<?php
// Email Validation and Update Script
require_once 'config.php';
require_once 'db.php';

echo "<!DOCTYPE html>
<html>
<head>
    <title>Official Email Validation - Salem Dominion Ministries</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f0fdf4; }
        .container { max-width: 800px; margin: 0 auto; background: white; padding: 40px; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); }
        h1 { color: #16a34a; text-align: center; margin-bottom: 30px; }
        .success { color: #16a34a; font-weight: bold; }
        .warning { color: #f59e0b; font-weight: bold; }
        .error { color: #dc2626; font-weight: bold; }
        .user-list { background: #f8fafc; padding: 20px; border-radius: 10px; margin: 20px 0; }
        .user-item { background: white; padding: 15px; margin: 10px 0; border-radius: 8px; border-left: 4px solid #0ea5e9; }
        .official { border-left-color: #16a34a; }
        .non-official { border-left-color: #f59e0b; }
        .btn { background: #0ea5e9; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block; margin: 5px; }
        .btn:hover { background: #0284c7; }
    </style>
</head>
<body>
    <div class='container'>
        <h1>🔍 Official Email Validation</h1>";

// Official email domains
$official_domains = ['salemdominionministries.com', 'salemministries.com', 'church.org'];

// Get all users
try {
    $users = $db->select("SELECT id, first_name, last_name, email, role, created_at FROM users ORDER BY created_at DESC");
    
    echo "<div class='user-list'>
        <h2>📋 User Email Analysis</h2>";
    
    $official_count = 0;
    $non_official_count = 0;
    
    foreach ($users as $user) {
        $email_domain = substr(strrchr($user['email'], '@'), 1);
        $is_official = in_array($email_domain, $official_domains);
        
        if ($is_official) {
            $official_count++;
            $status_class = 'official';
            $status_text = '✅ Official';
        } else {
            $non_official_count++;
            $status_class = 'non-official';
            $status_text = '⚠️ Non-Official';
        }
        
        echo "<div class='user-item {$status_class}'>
            <strong>{$user['first_name']} {$user['last_name']}</strong> ({$user['role']})<br>
            <small>Email: {$user['email']}</small><br>
            <small>Status: {$status_text}</small><br>
            <small>Created: " . date('M j, Y', strtotime($user['created_at'])) . "</small>
        </div>";
    }
    
    echo "</div>";
    
    echo "<div style='background: #f8fafc; padding: 20px; border-radius: 10px; margin: 20px 0;'>
        <h3>📊 Summary</h3>
        <p><strong>Total Users:</strong> " . count($users) . "</p>
        <p><strong>Official Emails:</strong> <span class='success'>{$official_count}</span></p>
        <p><strong>Non-Official Emails:</strong> <span class='warning'>{$non_official_count}</span></p>
    </div>";
    
    if ($non_official_count > 0) {
        echo "<div style='background: #fef3c7; padding: 20px; border-radius: 10px; margin: 20px 0; border: 2px solid #f59e0b;'>
            <h3 class='warning'>⚠️ Action Required</h3>
            <p>Found {$non_official_count} user(s) with non-official email addresses.</p>
            <p><strong>Recommendations:</strong></p>
            <ul>
                <li>Contact these users to get official church email addresses</li>
                <li>Update their email addresses in the database</li>
                <li>Or consider adding their domains to the official list if they are legitimate church members</li>
            </ul>
        </div>";
    }
    
} catch (Exception $e) {
    echo "<div class='error'>Error: " . htmlspecialchars($e->getMessage()) . "</div>";
}

echo "
        <div style='text-align: center; margin-top: 30px; padding: 20px; background: #dcfce7; border-radius: 10px;'>
            <h3 class='success'>✅ Email Validation Complete!</h3>
            <p>Registration now only accepts official church emails!</p>
            <a href='register.php' class='btn'>📝 Test Registration</a>
            <a href='admin_users.php' class='btn'>👥 Manage Users</a>
            <a href='index.php' class='btn'>🏠 Homepage</a>
        </div>
    </div>
</body>
</html>";
?>
