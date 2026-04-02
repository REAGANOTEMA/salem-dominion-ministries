<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>User Database Check</h1>";

try {
    require_once 'config.php';
    require_once 'db.php';
    
    echo "<h2>Database Connection:</h2>";
    echo "<p style='color: green;'>✓ Connected to database: " . DB_NAME . "</p>";
    
    // Check if users table exists
    $table_check = $db->query("SHOW TABLES LIKE 'users'");
    if ($table_check->num_rows > 0) {
        echo "<p style='color: green;'>✓ Users table exists</p>";
        
        // Get user count
        $count_result = $db->query("SELECT COUNT(*) as count FROM users");
        $count = $count_result->fetch_assoc()['count'];
        echo "<p><strong>Total Users:</strong> " . $count . "</p>";
        
        if ($count > 0) {
            echo "<h3>All Users:</h3>";
            echo "<table border='1' cellpadding='5'>";
            echo "<tr><th>ID</th><th>First Name</th><th>Last Name</th><th>Email</th><th>Role</th><th>Active</th><th>Created</th><th>Last Login</th></tr>";
            
            $users_result = $db->query("SELECT id, first_name, last_name, email, role, is_active, created_at, last_login FROM users ORDER BY created_at DESC");
            while ($row = $users_result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row['id'] . "</td>";
                echo "<td>" . htmlspecialchars($row['first_name']) . "</td>";
                echo "<td>" . htmlspecialchars($row['last_name']) . "</td>";
                echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                echo "<td>" . $row['role'] . "</td>";
                echo "<td>" . ($row['is_active'] ? 'Yes' : 'No') . "</td>";
                echo "<td>" . $row['created_at'] . "</td>";
                echo "<td>" . ($row['last_login'] ?: 'Never') . "</td>";
                echo "</tr>";
            }
            echo "</table>";
            
            echo "<h3>Create Test User (if needed):</h3>";
            echo "<form method='post'>";
            echo "<p>First Name: <input type='text' name='first_name' value='Test'></p>";
            echo "<p>Last Name: <input type='text' name='last_name' value='User'></p>";
            echo "<p>Email: <input type='email' name='email' value='test@example.com'></p>";
            echo "<p>Password: <input type='password' name='password' value='test123'></p>";
            echo "<p>Role: <select name='role'><option value='member'>Member</option><option value='admin'>Admin</option></select></p>";
            echo "<p><button type='submit' name='create_user'>Create User</button></p>";
            echo "</form>";
            
            // Handle user creation
            if (isset($_POST['create_user'])) {
                $first_name = trim($_POST['first_name']);
                $last_name = trim($_POST['last_name']);
                $email = trim($_POST['email']);
                $password = $_POST['password'];
                $role = $_POST['role'];
                
                if (!empty($first_name) && !empty($last_name) && !empty($email) && !empty($password)) {
                    // Check if email already exists
                    $existing = $db->query("SELECT id FROM users WHERE email = '" . $db->real_escape_string($email) . "'");
                    if ($existing->num_rows == 0) {
                        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                        $insert_sql = "INSERT INTO users (first_name, last_name, email, password, role, is_active, created_at) VALUES (?, ?, ?, ?, ?, 1, CURRENT_TIMESTAMP)";
                        
                        $stmt = $db->prepare($insert_sql);
                        $stmt->bind_param('sssss', $first_name, $last_name, $email, $hashed_password, $role);
                        
                        if ($stmt->execute()) {
                            echo "<p style='color: green;'>✓ User created successfully!</p>";
                            echo "<p>You can now login with: " . htmlspecialchars($email) . " / " . htmlspecialchars($password) . "</p>";
                        } else {
                            echo "<p style='color: red;'>✗ Failed to create user: " . $stmt->error . "</p>";
                        }
                        $stmt->close();
                    } else {
                        echo "<p style='color: orange;'>⚠ Email already exists</p>";
                    }
                } else {
                    echo "<p style='color: orange;'>⚠ Please fill all fields</p>";
                }
            }
            
        } else {
            echo "<p style='color: orange;'>⚠ No users found in database</p>";
            echo "<p>You need to create a user first using the form above.</p>";
        }
        
    } else {
        echo "<p style='color: red;'>✗ Users table does not exist</p>";
        echo "<p>You need to run the database setup script first.</p>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>✗ Error: " . $e->getMessage() . "</p>";
}
?>

<hr>
<p><a href="login_debug.php">Test Login (Debug Mode)</a></p>
<p><a href="login_perfect.php">Normal Login Page</a></p>
