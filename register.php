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
    $first_name = trim($_POST['first_name'] ?? '');
    $last_name = trim($_POST['last_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // Validation
    if (empty($first_name) || empty($last_name) || empty($email) || empty($password)) {
        $errors[] = 'Please fill in all required fields.';
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Please enter a valid email address.';
    }

    if (strlen($password) < 6) {
        $errors[] = 'Password must be at least 6 characters long.';
    }

    if ($password !== $confirm_password) {
        $errors[] = 'Passwords do not match.';
    }

    // Check if email already exists
    if (empty($errors)) {
        $stmt = $db-&gt;prepare("SELECT id FROM users WHERE email = ?");
        $stmt-&gt;bind_param('s', $email);
        $stmt-&gt;execute();
        if ($stmt-&gt;get_result()-&gt;num_rows > 0) {
            $errors[] = 'Email address is already registered.';
        }
        $stmt-&gt;close();
    }

    if (empty($errors)) {
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $db-&gt;prepare("INSERT INTO users (first_name, last_name, email, phone, password_hash, role) VALUES (?, ?, ?, ?, ?, 'member')");
        $stmt-&gt;bind_param('sssss', $first_name, $last_name, $email, $phone, $password_hash);

        if ($stmt-&gt;execute()) {
            $success = 'Registration successful! You can now login.';
        } else {
            $errors[] = 'Registration failed. Please try again.';
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
    &lt;title&gt;Register - Salem Dominion Ministries&lt;/title&gt;
    &lt;link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"&gt;
    &lt;link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet"&gt;
    &lt;style&gt;
        .register-container {
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 0;
        }
        .register-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            overflow: hidden;
            max-width: 500px;
            width: 100%;
        }
        .register-header {
            background: #343a40;
            color: white;
            padding: 2rem;
            text-align: center;
        }
        .register-body {
            padding: 2rem;
        }
    &lt;/style&gt;
&lt;/head&gt;
&lt;body&gt;
    &lt;div class="register-container"&gt;
        &lt;div class="register-card"&gt;
            &lt;div class="register-header"&gt;
                &lt;i class="fas fa-church fa-3x mb-3"&gt;&lt;/i&gt;
                &lt;h3&gt;Salem Dominion Ministries&lt;/h3&gt;
                &lt;p&gt;Join Our Community&lt;/p&gt;
            &lt;/div&gt;
            &lt;div class="register-body"&gt;
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
                        &lt;a href="login.php" class="alert-link"&gt;Click here to login&lt;/a&gt;
                    &lt;/div&gt;
                &lt;?php endif; ?&gt;

                &lt;form method="POST" action=""&gt;
                    &lt;div class="row"&gt;
                        &lt;div class="col-md-6 mb-3"&gt;
                            &lt;label for="first_name" class="form-label"&gt;&lt;i class="fas fa-user"&gt;&lt;/i&gt; First Name *&lt;/label&gt;
                            &lt;input type="text" class="form-control" id="first_name" name="first_name" required
                                   value="&lt;?php echo htmlspecialchars($_POST['first_name'] ?? ''); ?&gt;"&gt;
                        &lt;/div&gt;
                        &lt;div class="col-md-6 mb-3"&gt;
                            &lt;label for="last_name" class="form-label"&gt;&lt;i class="fas fa-user"&gt;&lt;/i&gt; Last Name *&lt;/label&gt;
                            &lt;input type="text" class="form-control" id="last_name" name="last_name" required
                                   value="&lt;?php echo htmlspecialchars($_POST['last_name'] ?? ''); ?&gt;"&gt;
                        &lt;/div&gt;
                    &lt;/div&gt;
                    &lt;div class="mb-3"&gt;
                        &lt;label for="email" class="form-label"&gt;&lt;i class="fas fa-envelope"&gt;&lt;/i&gt; Email Address *&lt;/label&gt;
                        &lt;input type="email" class="form-control" id="email" name="email" required
                               value="&lt;?php echo htmlspecialchars($_POST['email'] ?? ''); ?&gt;"&gt;
                    &lt;/div&gt;
                    &lt;div class="mb-3"&gt;
                        &lt;label for="phone" class="form-label"&gt;&lt;i class="fas fa-phone"&gt;&lt;/i&gt; Phone Number&lt;/label&gt;
                        &lt;input type="tel" class="form-control" id="phone" name="phone"
                               value="&lt;?php echo htmlspecialchars($_POST['phone'] ?? ''); ?&gt;"&gt;
                    &lt;/div&gt;
                    &lt;div class="mb-3"&gt;
                        &lt;label for="password" class="form-label"&gt;&lt;i class="fas fa-lock"&gt;&lt;/i&gt; Password *&lt;/label&gt;
                        &lt;input type="password" class="form-control" id="password" name="password" required minlength="6"&gt;
                        &lt;div class="form-text"&gt;Password must be at least 6 characters long.&lt;/div&gt;
                    &lt;/div&gt;
                    &lt;div class="mb-3"&gt;
                        &lt;label for="confirm_password" class="form-label"&gt;&lt;i class="fas fa-lock"&gt;&lt;/i&gt; Confirm Password *&lt;/label&gt;
                        &lt;input type="password" class="form-control" id="confirm_password" name="confirm_password" required&gt;
                    &lt;/div&gt;
                    &lt;div class="d-grid"&gt;
                        &lt;button type="submit" class="btn btn-primary btn-lg"&gt;
                            &lt;i class="fas fa-user-plus"&gt;&lt;/i&gt; Register
                        &lt;/button&gt;
                    &lt;/div&gt;
                &lt;/form&gt;

                &lt;hr&gt;

                &lt;div class="text-center"&gt;
                    &lt;p class="mb-0"&gt;Already have an account? &lt;a href="login.php" class="text-decoration-none"&gt;Login here&lt;/a&gt;&lt;/p&gt;
                &lt;/div&gt;
            &lt;/div&gt;
        &lt;/div&gt;
    &lt;/div&gt;

    &lt;script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"&gt;&lt;/script&gt;
&lt;/body&gt;
&lt;/html&gt;