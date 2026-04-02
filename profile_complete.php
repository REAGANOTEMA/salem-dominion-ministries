<?php
require_once 'auth_system.php';
require_once 'db.php';

require_login();

$user = get_current_user();
$errors = [];
$success = '';

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];
    
    if ($action === 'update_profile') {
        $first_name = trim($_POST['first_name'] ?? '');
        $last_name = trim($_POST['last_name'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $bio = trim($_POST['bio'] ?? '');
        
        if (empty($first_name) || empty($last_name)) {
            $errors[] = 'First name and last name are required.';
        }
        
        if (empty($errors)) {
            try {
                $db->query("UPDATE users SET first_name = ?, last_name = ?, phone = ?, bio = ?, updated_at = NOW() WHERE id = ?", 
                         [$first_name, $last_name, $phone, $bio, $user['id']]);
                
                // Update session
                $_SESSION['user_name'] = $first_name . ' ' . $last_name;
                
                $success = 'Profile updated successfully!';
            } catch (Exception $e) {
                $errors[] = 'Failed to update profile. Please try again.';
            }
        }
    }
    
    if ($action === 'upload_avatar') {
        if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
            $file = $_FILES['avatar'];
            
            // Validate file type
            $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            
            $file_extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            
            if (!in_array($file['type'], $allowed_types) || !in_array($file_extension, $allowed_extensions)) {
                $errors[] = 'Invalid file type. Only JPEG, PNG, GIF, and WebP images are allowed.';
            } elseif ($file['size'] > 5 * 1024 * 1024) { // 5MB limit
                $errors[] = 'File size too large. Maximum size is 5MB.';
            } else {
                // Create upload directory if it doesn't exist
                $upload_dir = 'uploads/avatars/';
                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0755, true);
                }
                
                // Generate unique filename
                $filename = uniqid() . '_' . basename($file['name']);
                $filepath = $upload_dir . $filename;
                
                // Delete old avatar if exists
                if ($user['avatar'] && file_exists($user['avatar'])) {
                    unlink($user['avatar']);
                }
                
                if (move_uploaded_file($file['tmp_name'], $filepath)) {
                    try {
                        $db->query("UPDATE users SET avatar_url = ?, updated_at = NOW() WHERE id = ?", [$filepath, $user['id']]);
                        $_SESSION['user_avatar'] = $filepath;
                        $success = 'Avatar updated successfully!';
                    } catch (Exception $e) {
                        $errors[] = 'Failed to update avatar in database.';
                        unlink($filepath); // Clean up uploaded file
                    }
                } else {
                    $errors[] = 'Failed to upload avatar. Please try again.';
                }
            }
        } else {
            $errors[] = 'Please select an image to upload.';
        }
    }
}

// Get current user data
$current_user = $db->selectOne("SELECT * FROM users WHERE id = ?", [$user['id']]);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - Salem Dominion Ministries</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: #f8fafc;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .profile-header {
            background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%);
            color: white;
            padding: 60px 0 40px;
            margin-bottom: 30px;
        }
        
        .avatar-container {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .avatar-img {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            border: 5px solid white;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            object-fit: cover;
        }
        
        .avatar-upload {
            margin-top: 15px;
        }
        
        .profile-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            padding: 30px;
            margin-bottom: 30px;
        }
        
        .form-control, .form-select {
            border-radius: 10px;
            border: 2px solid #e5e7eb;
            padding: 12px 20px;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: #0ea5e9;
            box-shadow: 0 0 0 0.2rem rgba(14, 165, 233, 0.25);
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%);
            border: none;
            border-radius: 10px;
            padding: 12px 30px;
            font-weight: 600;
        }
        
        .btn-outline-primary {
            border-radius: 10px;
            padding: 12px 30px;
            font-weight: 600;
        }
        
        .role-badge {
            background: #16a34a;
            color: white;
            padding: 8px 20px;
            border-radius: 20px;
            font-size: 0.9rem;
            display: inline-block;
        }
        
        .alert {
            border-radius: 10px;
            border: none;
        }
        
        .stats-card {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            padding: 20px;
            border-radius: 10px;
            text-align: center;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <i class="fas fa-church"></i> Salem Dominion Ministries
            </a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="dashboard.php">
                    <i class="fas fa-home"></i> Dashboard
                </a>
                <a class="nav-link active" href="profile.php">
                    <i class="fas fa-user"></i> Profile
                </a>
                <?php if (is_admin()): ?>
                    <a class="nav-link" href="admin_dashboard.php">
                        <i class="fas fa-cog"></i> Admin
                    </a>
                <?php endif; ?>
                <a class="nav-link" href="auth_system.php?action=logout">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>
        </div>
    </nav>

    <div class="profile-header">
        <div class="container text-center">
            <h1><i class="fas fa-user-circle"></i> My Profile</h1>
            <p class="lead">Manage your account settings and information</p>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <div class="profile-card">
                    <div class="avatar-container">
                        <?php if ($current_user['avatar_url'] && file_exists($current_user['avatar_url'])): ?>
                            <img src="<?php echo htmlspecialchars($current_user['avatar_url']); ?>" alt="Avatar" class="avatar-img">
                        <?php else: ?>
                            <img src="assets/default-avatar.png" alt="Avatar" class="avatar-img">
                        <?php endif; ?>
                    </div>
                    
                    <div class="text-center">
                        <h4><?php echo htmlspecialchars($current_user['first_name'] . ' ' . $current_user['last_name']); ?></h4>
                        <span class="role-badge">
                            <i class="fas fa-user-tag"></i> <?php echo ucfirst($current_user['role']); ?>
                        </span>
                        <p class="text-muted mt-2"><?php echo htmlspecialchars($current_user['email']); ?></p>
                        <?php if ($current_user['phone']): ?>
                            <p class="text-muted"><?php echo htmlspecialchars($current_user['phone']); ?></p>
                        <?php endif; ?>
                    </div>
                    
                    <div class="avatar-upload">
                        <form method="POST" action="profile.php" enctype="multipart/form-data">
                            <input type="hidden" name="action" value="upload_avatar">
                            <div class="mb-3">
                                <label for="avatar" class="form-label">Update Avatar</label>
                                <input type="file" class="form-control" id="avatar" name="avatar" accept="image/*">
                            </div>
                            <button type="submit" class="btn btn-primary btn-sm w-100">
                                <i class="fas fa-upload"></i> Upload Avatar
                            </button>
                        </form>
                    </div>
                </div>
                
                <div class="stats-card">
                    <h6><i class="fas fa-calendar"></i> Member Since</h6>
                    <p class="mb-0"><?php echo date('F j, Y', strtotime($current_user['created_at'])); ?></p>
                </div>
                
                <?php if ($current_user['last_login']): ?>
                    <div class="stats-card">
                        <h6><i class="fas fa-sign-in-alt"></i> Last Login</h6>
                        <p class="mb-0"><?php echo date('M j, Y g:i A', strtotime($current_user['last_login'])); ?></p>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="col-md-8">
                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger">
                        <?php foreach ($errors as $error): ?>
                            <div><?php echo htmlspecialchars($error); ?></div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                
                <?php if ($success): ?>
                    <div class="alert alert-success">
                        <?php echo htmlspecialchars($success); ?>
                    </div>
                <?php endif; ?>
                
                <div class="profile-card">
                    <h4><i class="fas fa-edit"></i> Edit Profile Information</h4>
                    
                    <form method="POST" action="profile.php">
                        <input type="hidden" name="action" value="update_profile">
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="first_name" class="form-label">First Name</label>
                                <input type="text" class="form-control" id="first_name" name="first_name" 
                                       value="<?php echo htmlspecialchars($current_user['first_name']); ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="last_name" class="form-label">Last Name</label>
                                <input type="text" class="form-control" id="last_name" name="last_name" 
                                       value="<?php echo htmlspecialchars($current_user['last_name']); ?>" required>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" class="form-control" id="email" 
                                   value="<?php echo htmlspecialchars($current_user['email']); ?>" readonly>
                            <small class="form-text text-muted">Email cannot be changed. Contact administrator if needed.</small>
                        </div>
                        
                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone Number</label>
                            <input type="tel" class="form-control" id="phone" name="phone" 
                                   value="<?php echo htmlspecialchars($current_user['phone'] ?? ''); ?>">
                        </div>
                        
                        <div class="mb-3">
                            <label for="bio" class="form-label">Bio</label>
                            <textarea class="form-control" id="bio" name="bio" rows="4" 
                                      placeholder="Tell us about yourself..."><?php echo htmlspecialchars($current_user['bio'] ?? ''); ?></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label for="role" class="form-label">Role</label>
                            <input type="text" class="form-control" id="role" 
                                   value="<?php echo ucfirst($current_user['role']); ?>" readonly>
                            <small class="form-text text-muted">Role is assigned by administrator.</small>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update Profile
                        </button>
                        <a href="dashboard.php" class="btn btn-outline-primary">
                            <i class="fas fa-arrow-left"></i> Back to Dashboard
                        </a>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
