&lt;?php
session_start();
require_once 'db.php';

if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit;
}

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $errors[] = 'Please fill in all fields.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Please enter a valid email address.';
    } else {
        $stmt = $db-&gt;prepare("SELECT id, first_name, last_name, password_hash, role, is_active FROM users WHERE email = ?");
        $stmt-&gt;bind_param('s', $email);
        $stmt-&gt;execute();
        $result = $stmt-&gt;get_result();

        if ($result-&gt;num_rows === 1) {
            $user = $result-&gt;fetch_assoc();

            if (!$user['is_active']) {
                $errors[] = 'Your account is not active. Please contact an administrator.';
            } elseif (password_verify($password, $user['password_hash'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['first_name'] . ' ' . $user['last_name'];
                $_SESSION['user_role'] = $user['role'];

                // Update last login
                $db-&gt;query("UPDATE users SET last_login = NOW() WHERE id = " . $user['id']);

                header('Location: dashboard.php');
                exit;
            } else {
                $errors[] = 'Invalid email or password.';
            }
        } else {
            $errors[] = 'Invalid email or password.';
        }
        $stmt-&gt;close();
    }
}
?&gt;
&lt;!DOCTYPE html&gt;
&lt;html lang="en"&gt;
&lt;head&gt;
    &lt;meta charset="UTF-8"&gt;
    &lt;meta name="viewport" content="width=device-width, initial-scale=1.0"&gt;
    &lt;title&gt;Login - Salem Dominion Ministries&lt;/title&gt;
    &lt;link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"&gt;
    &lt;link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet"&gt;
    &lt;style&gt;
        .login-container {
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            overflow: hidden;
            max-width: 400px;
            width: 100%;
        }
        .login-header {
            background: #343a40;
            color: white;
            padding: 2rem;
            text-align: center;
        }
        .login-body {
            padding: 2rem;
        }
    &lt;/style&gt;
&lt;/head&gt;
&lt;body&gt;
    &lt;div class="login-container"&gt;
        &lt;div class="login-card"&gt;
            &lt;div class="login-header"&gt;
                &lt;i class="fas fa-church fa-3x mb-3"&gt;&lt;/i&gt;
                &lt;h3&gt;Salem Dominion Ministries&lt;/h3&gt;
                &lt;p&gt;Welcome Back&lt;/p&gt;
            &lt;/div&gt;
            &lt;div class="login-body"&gt;
                &lt;?php if (!empty($errors)): ?&gt;
                    &lt;div class="alert alert-danger"&gt;
                        &lt;ul class="mb-0"&gt;
                            &lt;?php foreach ($errors as $error): ?&gt;
                                &lt;li&gt;&lt;?php echo htmlspecialchars($error); ?&gt;&lt;/li&gt;
                            &lt;?php endforeach; ?&gt;
                        &lt;/ul&gt;
                    &lt;/div&gt;
                &lt;?php endif; ?&gt;

                &lt;?php if ($success): ?&gt;
                    &lt;div class="alert alert-success"&gt;
                        &lt;?php echo htmlspecialchars($success); ?&gt;
                    &lt;/div&gt;
                &lt;?php endif; ?&gt;

                &lt;form method="POST" action=""&gt;
                    &lt;div class="mb-3"&gt;
                        &lt;label for="email" class="form-label"&gt;&lt;i class="fas fa-envelope"&gt;&lt;/i&gt; Email Address&lt;/label&gt;
                        &lt;input type="email" class="form-control" id="email" name="email" required
                               value="&lt;?php echo htmlspecialchars($_POST['email'] ?? ''); ?&gt;"&gt;
                    &lt;/div&gt;
                    &lt;div class="mb-3"&gt;
                        &lt;label for="password" class="form-label"&gt;&lt;i class="fas fa-lock"&gt;&lt;/i&gt; Password&lt;/label&gt;
                        &lt;input type="password" class="form-control" id="password" name="password" required&gt;
                    &lt;/div&gt;
                    &lt;div class="d-grid"&gt;
                        &lt;button type="submit" class="btn btn-primary btn-lg"&gt;
                            &lt;i class="fas fa-sign-in-alt"&gt;&lt;/i&gt; Login
                        &lt;/button&gt;
                    &lt;/div&gt;
                &lt;/form&gt;

                &lt;div class="text-center mt-3"&gt;
                    &lt;a href="forgot_password.php" class="text-decoration-none"&gt;Forgot Password?&lt;/a&gt;
                &lt;/div&gt;

                &lt;hr&gt;

                &lt;div class="text-center"&gt;
                    &lt;p class="mb-0"&gt;Don't have an account? &lt;a href="register.php" class="text-decoration-none"&gt;Register here&lt;/a&gt;&lt;/p&gt;
                &lt;/div&gt;
            &lt;/div&gt;
        &lt;/div&gt;
    &lt;/div&gt;

    &lt;script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"&gt;&lt;/script&gt;
&lt;/body&gt;
&lt;/html&gt;