<?php
// Create All Official Email Accounts
require_once 'config.php';
require_once 'db.php';

echo "<!DOCTYPE html>
<html>
<head>
    <title>Create Official Email Accounts - Salem Dominion Ministries</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f0fdf4; }
        .container { max-width: 800px; margin: 0 auto; background: white; padding: 40px; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); }
        h1 { color: #16a34a; text-align: center; margin-bottom: 30px; }
        .success { color: #16a34a; font-weight: bold; }
        .error { color: #dc2626; font-weight: bold; }
        .account-card { background: #f8fafc; padding: 20px; margin: 15px 0; border-radius: 10px; border-left: 4px solid #16a34a; }
        .btn { background: #0ea5e9; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block; margin: 5px; }
    </style>
</head>
<body>
    <div class='container'>
        <h1>🎯 Create Official Email Accounts</h1>";

// Official email accounts to create
$official_accounts = [
    ['visit@salemdominionministries.com', 'Visitor', 'Team', 'member'],
    ['ministers@salemdominionministries.com', 'Ministry', 'Team', 'pastor'],
    ['childrenministry@salemdominionministries.com', 'Children', 'Ministry', 'pastor'],
    ['admin@salemdominionministries.com', 'Admin', 'User', 'admin'],
    ['pastor@salemdominionministries.com', 'Senior', 'Pastor', 'pastor'],
    ['otema@salemdominionministries.com', 'Otema', 'Reagan', 'member']
];

try {
    // Use direct database connection
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME, DB_PORT);
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }
    
    echo "<h2>📧 Creating Official Email Accounts...</h2>";
    
    foreach ($official_accounts as $account) {
        $email = $account[0];
        $first_name = $account[1];
        $last_name = $account[2];
        $role = $account[3];
        
        // Check if user already exists
        $check_stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $check_stmt->bind_param('s', $email);
        $check_stmt->execute();
        $result = $check_stmt->get_result();
        
        if ($result->num_rows > 0) {
            echo "<div class='account-card'>
                <h3 style='color: #f59e0b;'>⚠️ Account Already Exists</h3>
                <p><strong>Email:</strong> {$email}</p>
                <p><strong>Name:</strong> {$first_name} {$last_name}</p>
                <p><strong>Role:</strong> {$role}</p>
                <p><em>This account already exists in the system.</em></p>
            </div>";
        } else {
            // Create new account
            $password = 'Lovely2God'; // Default password for all accounts
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            
            $insert_stmt = $conn->prepare("INSERT INTO users (first_name, last_name, email, password_hash, role, is_active) VALUES (?, ?, ?, ?, ?, TRUE)");
            $insert_stmt->bind_param('sssss', $first_name, $last_name, $email, $password_hash, $role);
            
            if ($insert_stmt->execute()) {
                echo "<div class='account-card'>
                    <h3 style='color: #16a34a;'>✅ Account Created Successfully</h3>
                    <p><strong>Email:</strong> {$email}</p>
                    <p><strong>Name:</strong> {$first_name} {$last_name}</p>
                    <p><strong>Role:</strong> {$role}</p>
                    <p><strong>Password:</strong> {$password}</p>
                    <p><em>Account created and ready to use!</em></p>
                </div>";
            } else {
                echo "<div class='account-card'>
                    <h3 style='color: #dc2626;'>❌ Account Creation Failed</h3>
                    <p><strong>Email:</strong> {$email}</p>
                    <p><strong>Error:</strong> Could not create account</p>
                </div>";
            }
            $insert_stmt->close();
        }
        $check_stmt->close();
    }
    
    // Display all current users
    echo "<h2>📋 All Current Users</h2>";
    $users_result = $conn->query("SELECT id, first_name, last_name, email, role, is_active, created_at FROM users ORDER BY created_at DESC");
    
    while ($user = $users_result->fetch_assoc()) {
        $status = $user['is_active'] ? '✅ Active' : '❌ Inactive';
        echo "<div class='account-card'>
            <p><strong>{$user['first_name']} {$user['last_name']}</strong> ({$user['role']})</p>
            <p><small>Email: {$user['email']}</small></p>
            <p><small>Status: {$status}</small></p>
            <p><small>Created: " . date('M j, Y', strtotime($user['created_at'])) . "</small></p>
        </div>";
    }
    
    $conn->close();
    
} catch (Exception $e) {
    echo "<div class='error'>Error: " . htmlspecialchars($e->getMessage()) . "</div>";
}

echo "
        <div style='text-align: center; margin-top: 30px; padding: 20px; background: #dcfce7; border-radius: 10px;'>
            <h3 class='success'>🎉 Official Email Accounts Setup Complete!</h3>
            <p>All your official church email accounts are now ready!</p>
            <p><strong>Login Credentials:</strong></p>
            <p>Email: Your official email<br>Password: Lovely2God</p>
            <a href='login.php' class='btn'>🔐 Login Now</a>
            <a href='index.php' class='btn'>🏠 Homepage</a>
        </div>
    </div>
</body>
</html>";
?>
