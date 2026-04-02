<?php
// Complete Authentication System
require_once 'config.php';
require_once 'db.php';

session_start();

// Handle Login
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];
    
    if ($action === 'login') {
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        
        $errors = [];
        
        if (empty($email) || empty($password)) {
            $errors[] = 'Please fill in all fields.';
        }
        
        if (empty($errors)) {
            try {
                $user = $db->selectOne("SELECT * FROM users WHERE email = ? AND is_active = 1", [$email]);
                
                if ($user && password_verify($password, $user['password_hash'])) {
                    // Update last login
                    $db->query("UPDATE users SET last_login = NOW() WHERE id = ?", [$user['id']]);
                    
                    // Set session
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_email'] = $user['email'];
                    $_SESSION['user_name'] = $user['first_name'] . ' ' . $user['last_name'];
                    $_SESSION['user_role'] = $user['role'];
                    $_SESSION['user_avatar'] = $user['avatar_url'];
                    $_SESSION['logged_in'] = true;
                    
                    // Redirect based on role
                    if ($user['role'] === 'admin') {
                        header('Location: admin_dashboard.php');
                    } else {
                        header('Location: dashboard.php');
                    }
                    exit;
                } else {
                    $errors[] = 'Invalid email or password.';
                }
            } catch (Exception $e) {
                $errors[] = 'Login failed. Please try again.';
            }
        }
        
        if (!empty($errors)) {
            $_SESSION['login_errors'] = $errors;
            header('Location: login.php');
            exit;
        }
    }
    
    // Handle Logout
    if ($action === 'logout') {
        session_destroy();
        header('Location: index.php');
        exit;
    }
}

// Check if user is logged in
function is_logged_in() {
    return isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
}

// Get current user info
function get_current_user() {
    if (is_logged_in()) {
        return [
            'id' => $_SESSION['user_id'],
            'email' => $_SESSION['user_email'],
            'name' => $_SESSION['user_name'],
            'role' => $_SESSION['user_role'],
            'avatar' => $_SESSION['user_avatar']
        ];
    }
    return null;
}

// Check if user has specific role
function has_role($role) {
    $user = get_current_user();
    return $user && $user['role'] === $role;
}

// Check if user is admin
function is_admin() {
    return has_role('admin');
}

// Redirect if not logged in
function require_login() {
    if (!is_logged_in()) {
        header('Location: login.php');
        exit;
    }
}

// Redirect if not admin
function require_admin() {
    require_login();
    if (!is_admin()) {
        header('Location: dashboard.php');
        exit;
    }
}
?>
