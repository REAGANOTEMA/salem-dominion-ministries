<?php
require_once 'auth_system.php';

// Redirect if already logged in
if (is_logged_in()) {
    $user = get_current_user();
    if ($user['role'] === 'admin') {
        header('Location: admin_dashboard.php');
    } else {
        header('Location: dashboard.php');
    }
    exit;
}

$errors = $_SESSION['login_errors'] ?? [];
unset($_SESSION['login_errors']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Salem Dominion Ministries</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .login-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            overflow: hidden;
            max-width: 900px;
            width: 100%;
            margin: 20px;
        }
        
        .login-left {
            background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%);
            color: white;
            padding: 60px 40px;
            text-align: center;
        }
        
        .login-right {
            padding: 60px 40px;
        }
        
        .church-logo {
            font-size: 3rem;
            margin-bottom: 20px;
        }
        
        .form-control {
            border-radius: 10px;
            border: 2px solid #e5e7eb;
            padding: 12px 20px;
            margin-bottom: 20px;
        }
        
        .form-control:focus {
            border-color: #0ea5e9;
            box-shadow: 0 0 0 0.2rem rgba(14, 165, 233, 0.25);
        }
        
        .btn-login {
            background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%);
            border: none;
            border-radius: 10px;
            padding: 12px 30px;
            font-weight: 600;
            width: 100%;
            transition: all 0.3s ease;
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(14, 165, 233, 0.3);
        }
        
        .alert {
            border-radius: 10px;
            border: none;
            margin-bottom: 20px;
        }
        
        .official-emails {
            background: #f8fafc;
            padding: 15px;
            border-radius: 10px;
            margin-top: 20px;
            font-size: 0.9rem;
        }
        
        .official-emails h6 {
            color: #16a34a;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="row g-0">
            <div class="col-md-5">
                <div class="login-left">
                    <div class="church-logo">
                        <i class="fas fa-church"></i>
                    </div>
                    <h2>Salem Dominion Ministries</h2>
                    <p class="lead">Welcome to our spiritual family</p>
                    <div class="mt-4">
                        <p><i class="fas fa-pray"></i> Join us in worship</p>
                        <p><i class="fas fa-bible"></i> Grow in faith</p>
                        <p><i class="fas fa-users"></i> Connect with community</p>
                    </div>
                </div>
            </div>
            <div class="col-md-7">
                <div class="login-right">
                    <h3 class="mb-4">Sign In</h3>
                    
                    <?php if (!empty($errors)): ?>
                        <div class="alert alert-danger">
                            <?php foreach ($errors as $error): ?>
                                <div><?php echo htmlspecialchars($error); ?></div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                    
                    <form method="POST" action="auth_system.php">
                        <input type="hidden" name="action" value="login">
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">
                                <i class="fas fa-envelope"></i> Email Address
                            </label>
                            <input type="email" class="form-control" id="email" name="email" required
                                   placeholder="your.email@salemdominionministries.com">
                        </div>
                        
                        <div class="mb-3">
                            <label for="password" class="form-label">
                                <i class="fas fa-lock"></i> Password
                            </label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="remember">
                            <label class="form-check-label" for="remember">
                                Remember me
                            </label>
                        </div>
                        
                        <button type="submit" class="btn btn-primary btn-login">
                            <i class="fas fa-sign-in-alt"></i> Sign In
                        </button>
                    </form>
                    
                    <div class="official-emails">
                        <h6><i class="fas fa-shield-alt"></i> Official Church Emails Only</h6>
                        <div class="row">
                            <div class="col-6">
                                <small>• visit@salemdominionministries.com</small><br>
                                <small>• ministers@salemdominionministries.com</small><br>
                                <small>• childrenministry@salemdominionministries.com</small>
                            </div>
                            <div class="col-6">
                                <small>• admin@salemdominionministries.com</small><br>
                                <small>• pastor@salemdominionministries.com</small><br>
                                <small>• otema@salemdominionministries.com</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="text-center mt-4">
                        <small>Don't have an account? <a href="register.php">Contact Administrator</a></small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
