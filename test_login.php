<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>Login System Test</h2>";

// Test session
echo "<h3>Session Test:</h3>";
if (session_status() === PHP_SESSION_NONE) {
    echo "<p>Session is not started</p>";
    session_start();
    echo "<p>Session started</p>";
} else {
    echo "<p>Session is already active</p>";
}
echo "<p>Session ID: " . session_id() . "</p>";

// Test database connection
echo "<h3>Database Test:</h3>";
try {
    require_once 'config.php';
    require_once 'db.php';
    
    echo "<p style='color: green;'>✓ Database files loaded</p>";
    
    // Test the $db object
    if (isset($db)) {
        echo "<p style='color: green;'>✓ Database object created</p>";
        
        // Test a simple query
        $result = $db->query("SELECT 1 as test");
        if ($result) {
            echo "<p style='color: green;'>✓ Database query successful</p>";
        } else {
            echo "<p style='color: red;'>✗ Database query failed</p>";
        }
    } else {
        echo "<p style='color: red;'>✗ Database object not created</p>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>✗ Database Error: " . $e->getMessage() . "</p>";
}

// Test POST data
echo "<h3>POST Data Test:</h3>";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo "<p>Request method: POST</p>";
    echo "<p>Email: " . ($_POST['email'] ?? 'Not set') . "</p>";
    echo "<p>Password: " . (empty($_POST['password']) ? 'Not set' : 'Provided') . "</p>";
} else {
    echo "<p>Request method: " . $_SERVER['REQUEST_METHOD'] . "</p>";
}

// Show test form
echo "<h3>Test Login Form:</h3>";
?>
<form method="post">
    <p>Email: <input type="email" name="email" value="test@example.com"></p>
    <p>Password: <input type="password" name="password" value="test123"></p>
    <p><input type="submit" value="Test Login"></p>
</form>

<?php
// Test actual login logic if POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo "<h3>Login Logic Test:</h3>";
    
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (!empty($email) && !empty($password)) {
        try {
            echo "<p>Looking for user: " . htmlspecialchars($email) . "</p>";
            
            $user = $db->selectOne("SELECT * FROM users WHERE email = ? AND is_active = 1", [$email]);
            
            if ($user) {
                echo "<p style='color: green;'>✓ User found</p>";
                echo "<p>User ID: " . $user['id'] . "</p>";
                echo "<p>Name: " . $user['first_name'] . ' ' . $user['last_name'] . "</p>";
                echo "<p>Role: " . $user['role'] . "</p>";
                echo "<p>Active: " . ($user['is_active'] ? 'Yes' : 'No') . "</p>";
                
                if (password_verify($password, $user['password'])) {
                    echo "<p style='color: green;'>✓ Password verification successful</p>";
                } else {
                    echo "<p style='color: red;'>✗ Password verification failed</p>";
                    echo "<p>Hashed password in DB: " . $user['password'] . "</p>";
                    echo "<p>Password provided: " . $password . "</p>";
                }
            } else {
                echo "<p style='color: red;'>✗ User not found or inactive</p>";
            }
        } catch (Exception $e) {
            echo "<p style='color: red;'>✗ Login Error: " . $e->getMessage() . "</p>";
        }
    } else {
        echo "<p style='color: orange;'>Please provide email and password</p>";
    }
}
?>
