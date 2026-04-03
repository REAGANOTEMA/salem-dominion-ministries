<?php
// Perfect Admin Login System - Works with Forced Database Connection
require_once 'config_force.php';

// Initialize variables to prevent undefined errors
$login_error = '';
$login_success = '';

// Handle login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (!empty($email) && !empty($password)) {
        try {
            $db = new Database();
            
            // Try to find user by email
            $user = $db->selectOne("SELECT * FROM users WHERE email = ? AND is_active = 1", [$email]);
            
            if ($user && password_verify($password, $user['password'])) {
                // Set session variables
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['first_name'] = $user['first_name'];
                $_SESSION['last_name'] = $user['last_name'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['is_active'] = $user['is_active'];
                
                // Update last login
                $update_login = $db->prepare("UPDATE users SET last_login = CURRENT_TIMESTAMP WHERE id = ?");
                $update_login->bind_param('i', $user['id']);
                $update_login->execute();
                
                // Redirect based on role
                if ($user['role'] === 'admin') {
                    header('Location: admin_dashboard.php');
                } else {
                    header('Location: dashboard.php');
                }
                exit;
                
            } else {
                $login_error = 'Invalid email or password';
            }
            
        } catch (Exception $e) {
            $login_error = 'Login failed. Please try again.';
        }
    } else {
        $login_error = 'Please enter email and password';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Admin Login - Salem Dominion Ministries - Access your church management portal">
    <meta name="keywords" content="admin, login, church management, Salem Dominion">
    <meta name="author" content="Salem Dominion Ministries">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
    <link rel="shortcut icon" type="image/x-icon" href="/favicon.ico">
    <link rel="apple-touch-icon" href="/favicon.ico">
    <link rel="icon" sizes="192x192" href="/favicon.ico">
    <link rel="icon" sizes="512x512" href="/favicon.ico">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <!-- AOS Animation -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;0,900;1,400&family=Montserrat:wght@100;200;300;400;500;600;700;800;900&family=Great+Vibes&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #dc2626;
            --secondary-color: #ea580c;
            --accent-color: #f59e0b;
            --dark-color: #450a0a;
            --light-color: #fef2f2;
            --text-color: #7f1d1d;
            --gradient-primary: linear-gradient(135deg, #dc2626 0%, #991b1b 100%);
            --gradient-secondary: linear-gradient(135deg, #ea580c 0%, #dc2626 100%);
            --gradient-accent: linear-gradient(135deg, #f59e0b 0%, #ea580c 100%);
            --gradient-dark: linear-gradient(135deg, #450a0a 0%, #7f1d1d 100%);
            --gradient-hero: linear-gradient(135deg, rgba(220, 38, 38, 0.95) 0%, rgba(153, 27, 27, 0.95) 100%);
            --shadow-sm: 0 2px 4px rgba(220, 38, 38, 0.1);
            --shadow-md: 0 4px 6px rgba(220, 38, 38, 0.1);
            --shadow-lg: 0 10px 15px rgba(220, 38, 38, 0.1);
            --shadow-xl: 0 20px 25px rgba(220, 38, 38, 0.1);
            --transition-smooth: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            --border-radius: 12px;
            --border-radius-lg: 20px;
            --font-primary: 'Montserrat', sans-serif;
        }
        
        body {
            font-family: var(--font-primary);
            background: linear-gradient(135deg, #dc2626 0%, #991b1b 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            padding: 20px;
            position: relative;
            overflow: hidden;
        }
        
        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="%23dc2626" fill-opacity="0.1" d="M0,96L48,112C96,128,192,160C384,160,480,128C576,128,672,122.7C768,117,864,138.7C960,160,1056,128C1152,117,1248,160L1440,320Z"></path></svg>') no-repeat center bottom;
            background-size: cover;
            opacity: 0.3;
        }
        
        .login-container {
            background: white;
            border-radius: var(--border-radius-lg);
            box-shadow: var(--shadow-xl);
            padding: 3rem;
            width: 100%;
            max-width: 400px;
            position: relative;
            z-index: 10;
            animation: fadeInUp 0.6s ease-out;
        }
        
        .login-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .login-header h2 {
            color: var(--primary-color);
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        
        .login-header p {
            color: var(--text-color);
            font-size: 1rem;
            margin-bottom: 0;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-group label {
            display: block;
            color: var(--text-color);
            font-weight: 600;
            margin-bottom: 0.5rem;
        }
        
        .form-control {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 2px solid #e5e7eb;
            border-radius: var(--border-radius);
            font-size: 1rem;
            transition: var(--transition-smooth);
        }
        
        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(220, 38, 38, 0.2);
            outline: none;
        }
        
        .btn-login {
            width: 100%;
            padding: 0.75rem 1rem;
            background: var(--gradient-primary);
            color: white;
            border: none;
            border-radius: var(--border-radius);
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition-smooth);
            position: relative;
            overflow: hidden;
        }
        
        .btn-login::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }
        
        .btn-login:hover::before {
            left: 100%;
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }
        
        .alert-danger {
            background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%);
            color: var(--primary-color);
            padding: 1rem;
            border-radius: var(--border-radius);
            border: 1px solid var(--primary-color);
            margin-bottom: 1rem;
            text-align: center;
            font-weight: 600;
        }
        
        .back-link {
            text-align: center;
            margin-top: 1.5rem;
        }
        
        .back-link a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 600;
            transition: var(--transition-smooth);
        }
        
        .back-link a:hover {
            color: var(--secondary-color);
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <h2>Admin Login</h2>
            <p>Access your church management portal</p>
        </div>
        
        <?php if (!empty($login_error)): ?>
            <div class="alert-danger">
                <?php echo htmlspecialchars($login_error); ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" class="form-control" value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" class="form-control" required>
            </div>
            
            <button type="submit" class="btn-login">
                Sign In to Admin Panel
            </button>
        </form>
        
        <div class="back-link">
            <a href="index_mind_blowing.php">
                <i class="fas fa-arrow-left"></i> Back to Homepage
            </a>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
