<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Login Debug Mode</h1>";

// Include required files
require_once 'session_helper.php';
secure_session_start();
require_once 'db.php';

echo "<h2>Environment Check:</h2>";
echo "<p>Session Status: " . session_status() . "</p>";
echo "<p>Session ID: " . session_id() . "</p>";

// Check if user is already logged in
if (isset($_SESSION['user_id'])) {
    echo "<p style='color: green;'>Already logged in as user ID: " . $_SESSION['user_id'] . "</p>";
    echo "<p><a href='dashboard.php'>Go to Dashboard</a></p>";
    exit;
}

// Handle login
$login_error = '';
$login_success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo "<h2>Login Attempt:</h2>";
    
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    
    echo "<p>Email: " . htmlspecialchars($email) . "</p>";
    echo "<p>Password: " . (empty($password) ? 'Not provided' : 'Provided (' . strlen($password) . ' chars)') . "</p>";
    
    if (!empty($email) && !empty($password)) {
        try {
            echo "<h3>Database Query:</h3>";
            
            // Use prepared statement directly
            $stmt = $db->prepare("SELECT * FROM users WHERE email = ? AND is_active = 1");
            if ($stmt === false) {
                throw new Exception("Prepare failed: " . $db->error);
            }
            
            echo "<p>✓ Statement prepared</p>";
            
            $stmt->bind_param('s', $email);
            echo "<p>✓ Parameter bound</p>";
            
            $stmt->execute();
            echo "<p>✓ Query executed</p>";
            
            $result = $stmt->get_result();
            echo "<p>✓ Result obtained</p>";
            
            $user = $result->fetch_assoc();
            $stmt->close();
            
            if ($user) {
                echo "<h3>User Found:</h3>";
                echo "<p>ID: " . $user['id'] . "</p>";
                echo "<p>Name: " . $user['first_name'] . ' ' . $user['last_name'] . "</p>";
                echo "<p>Email: " . $user['email'] . "</p>";
                echo "<p>Role: " . $user['role'] . "</p>";
                echo "<p>Active: " . ($user['is_active'] ? 'Yes' : 'No') . "</p>";
                
                echo "<h3>Password Verification:</h3>";
                if (password_verify($password, $user['password'])) {
                    echo "<p style='color: green;'>✓ Password verified successfully</p>";
                    
                    // Set session variables
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['first_name'] = $user['first_name'];
                    $_SESSION['last_name'] = $user['last_name'];
                    $_SESSION['email'] = $user['email'];
                    $_SESSION['role'] = $user['role'];
                    $_SESSION['is_active'] = $user['is_active'];
                    
                    echo "<p style='color: green;'>✓ Session variables set</p>";
                    
                    // Update last login
                    $update_login = $db->prepare("UPDATE users SET last_login = CURRENT_TIMESTAMP WHERE id = ?");
                    $update_login->bind_param('i', $user['id']);
                    $update_login->execute();
                    $update_login->close();
                    
                    echo "<p style='color: green;'>✓ Last login updated</p>";
                    
                    // Redirect based on role
                    if ($user['role'] === 'admin') {
                        echo "<p>Redirecting to admin dashboard...</p>";
                        header('Location: admin_dashboard.php');
                    } else {
                        echo "<p>Redirecting to dashboard...</p>";
                        header('Location: dashboard.php');
                    }
                    exit;
                    
                } else {
                    echo "<p style='color: red;'>✗ Password verification failed</p>";
                    $login_error = 'Invalid email or password';
                }
            } else {
                echo "<p style='color: red;'>✗ User not found or inactive</p>";
                $login_error = 'Invalid email or password';
            }
        } catch (Exception $e) {
            echo "<p style='color: red;'>✗ Exception: " . $e->getMessage() . "</p>";
            $login_error = 'Login failed. Please try again.';
        }
    } else {
        echo "<p style='color: orange;'>Please enter email and password</p>";
        $login_error = 'Please enter email and password';
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login Debug</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .form-group { margin: 10px 0; }
        input[type="email"], input[type="password"] { padding: 5px; width: 200px; }
        button { padding: 10px 20px; background: #007bff; color: white; border: none; cursor: pointer; }
        .error { color: red; margin: 10px 0; }
        .success { color: green; margin: 10px 0; }
    </style>
</head>
<body>
    <h2>Login Form (Debug Mode)</h2>
    
    <?php if ($login_error): ?>
        <div class="error"><?php echo htmlspecialchars($login_error); ?></div>
    <?php endif; ?>
    
    <?php if ($login_success): ?>
        <div class="success"><?php echo htmlspecialchars($login_success); ?></div>
    <?php endif; ?>

    <form method="post">
        <div class="form-group">
            <label>Email:</label><br>
            <input type="email" name="email" value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" required>
        </div>
        <div class="form-group">
            <label>Password:</label><br>
            <input type="password" name="password" required>
        </div>
        <div class="form-group">
            <button type="submit">Login</button>
        </div>
    </form>
    
    <hr>
    <p><a href="login_perfect.php">Back to Normal Login</a></p>
    <p><a href="test_db.php">Test Database</a></p>
    <p><a href="test_login.php">Test Login System</a></p>
</body>
</html>
