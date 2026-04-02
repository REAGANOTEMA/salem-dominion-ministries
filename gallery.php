<?php
// Enhanced Gallery Page with Database Integration
require_once 'config.php';
require_once 'db.php';

session_start();

// Get gallery images from database
$gallery_images = [];
try {
    $gallery_images = $db->select("SELECT * FROM gallery WHERE status = 'published' ORDER BY created_at DESC");
} catch (Exception $e) {
    $gallery_images = [];
}

// Auto-cleanup expired content
try {
    // Use direct connection for cleanup to avoid Database class issues
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME, DB_PORT);
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }
    $conn->query("UPDATE gallery SET status = 'archived' WHERE auto_expire = TRUE AND expires_at < NOW()");
    $conn->close();
} catch (Exception $e) {
    // Silent error handling
}

// Get categories for filter
$categories = [];
try {
    $category_result = $db->select("SELECT DISTINCT category FROM gallery WHERE status = 'published' AND category IS NOT NULL ORDER BY category");
    $categories = array_column($category_result, 'category');
} catch (Exception $e) {
    $categories = [];
}

// Handle image deletion (admin only)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_image']) && isset($_SESSION['user_id'])) {
    try {
        $user = $db->selectOne("SELECT role FROM users WHERE id = ?", [$_SESSION['user_id']]);
        if ($user && ($user['role'] === 'admin' || $user['role'] === 'pastor')) {
            $image_id = intval($_POST['delete_image']);
            $image = $db->selectOne("SELECT file_url FROM gallery WHERE id = ?", [$image_id]);
            
            if ($image) {
                // Delete file from filesystem
                $file_path = __DIR__ . '/' . $image['file_url'];
                if (file_exists($file_path)) {
                    unlink($file_path);
                }
                
                // Delete from database
                $db->delete("DELETE FROM gallery WHERE id = ?", [$image_id]);
                
                // Refresh gallery
                $gallery_images = $db->select("SELECT * FROM gallery WHERE status = 'published' ORDER BY created_at DESC");
            }
        }
    } catch (Exception $e) {
        // Handle error silently
    }
}

// Handle image upload
$upload_success = '';
$upload_error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['gallery_image'])) {
    $user_id = $_SESSION['user_id'] ?? 1; // Default to user 1 if not logged in
    $file = $_FILES['gallery_image'];

    if ($file['error'] === UPLOAD_ERR_OK) {
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        
        $file_extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        
        if (in_array($file['type'], $allowed_types) && in_array($file_extension, $allowed_extensions)) {
            $upload_dir = 'uploads/gallery/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }

            $filename = uniqid() . '_' . basename($file['name']);
            $filepath = $upload_dir . $filename;

            if (move_uploaded_file($file['tmp_name'], $filepath)) {
                try {
                    $title = trim($_POST['title'] ?? '');
                    $description = trim($_POST['description'] ?? '');
                    
                    $result = $db->insert(
                        "INSERT INTO gallery (uploaded_by, file_url, title, description, file_type, status) VALUES (?, ?, ?, ?, 'image', 'published')",
                        [$user_id, $filepath, $title, $description]
                    );
                    
                    if ($result) {
                        $upload_success = 'Image uploaded successfully!';
                        // Refresh gallery
                        $gallery_images = $db->select("SELECT * FROM gallery WHERE status = 'published' ORDER BY created_at DESC");
                    }
                } catch (Exception $e) {
                    $upload_error = 'Failed to save image to database.';
                    // Delete uploaded file if database insert failed
                    unlink($filepath);
                }
            } else {
                $upload_error = 'Failed to upload image. Please check folder permissions.';
            }
        } else {
            $upload_error = 'Invalid file type. Only JPEG, PNG, GIF, and WebP images are allowed.';
        }
    } else {
        $upload_error = 'Upload error occurred. Please try again.';
    }
}

// Calculate total images
$total_images = count($gallery_images);

// Clean any buffered output (only if buffer exists)
if (ob_get_length() > 0) {
    ob_end_clean();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Gallery - Salem Dominion Ministries - View our church events, services, and community photos">
    <title>Gallery - Salem Dominion Ministries</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- AOS Animation -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;0,900;1,400&family=Montserrat:wght@100;200;300;400;500;600;700;800;900&family=Great+Vibes&display=swap" rel="stylesheet">
    
    <style>
        /* ICONIC DESIGN SYSTEM - Top Notch Colors Only */
        :root {
            /* Primary Palette - Ultra Premium */
            --midnight-blue: #0f172a;
            --ocean-blue: #0ea5e9;
            --sky-blue: #38bdf8;
            --ice-blue: #7dd3fc;
            --snow-white: #ffffff;
            --pearl-white: #f8fafc;
            
            /* Divine Accents */
            --heavenly-gold: #fbbf24;
            --divine-light: #fef3c7;
            
            /* Shadows & Effects */
            --shadow-divine: 0 20px 40px rgba(15, 23, 42, 0.15);
            --shadow-heavenly: 0 25px 50px rgba(251, 191, 36, 0.2);
            --shadow-soft: 0 10px 25px rgba(15, 23, 42, 0.08);
            --shadow-glow: 0 0 40px rgba(14, 165, 233, 0.3);
            
            /* Gradients - Iconic */
            --gradient-ocean: linear-gradient(135deg, var(--midnight-blue) 0%, var(--ocean-blue) 50%, var(--sky-blue) 100%);
            --gradient-heaven: linear-gradient(135deg, var(--snow-white) 0%, var(--pearl-white) 50%, var(--ice-blue) 100%);
            --gradient-divine: linear-gradient(135deg, var(--heavenly-gold) 0%, var(--divine-light) 100%);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Montserrat', sans-serif;
            font-weight: 300;
            line-height: 1.6;
            color: var(--midnight-blue);
            background: var(--snow-white);
            overflow-x: hidden;
            position: relative;
        }

        /* Divine Background Pattern */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: 
                radial-gradient(circle at 20% 80%, rgba(251, 191, 36, 0.03) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(14, 165, 233, 0.03) 0%, transparent 50%),
                radial-gradient(circle at 40% 40%, rgba(56, 189, 248, 0.02) 0%, transparent 50%);
            pointer-events: none;
            z-index: 1;
        }

        /* Typography - Iconic */
        h1, h2, h3, h4, h5, h6 {
            font-family: 'Playfair Display', serif;
            font-weight: 700;
            line-height: 1.2;
            color: var(--midnight-blue);
        }

        .font-divine {
            font-family: 'Great Vibes', cursive;
            color: var(--heavenly-gold);
        }

        /* Navigation - Iconic */
        .navbar {
            background: rgba(255, 255, 255, 0.95) !important;
            backdrop-filter: blur(20px);
            box-shadow: var(--shadow-soft);
            padding: 1rem 0;
            transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            z-index: 1000;
        }

        .navbar.scrolled {
            padding: 0.5rem 0;
            box-shadow: var(--shadow-divine);
        }

        .navbar-brand {
            display: flex;
            align-items: center;
            gap: 15px;
            font-family: 'Great Vibes', cursive;
            font-size: 2.2rem;
            font-weight: 700;
            color: var(--midnight-blue) !important;
            text-decoration: none !important;
            transition: all 0.3s ease;
        }

        .navbar-brand:hover {
            transform: scale(1.02);
        }

        .navbar-brand img {
            height: 50px;
            width: auto;
            border-radius: 50%;
            background: var(--gradient-heaven);
            padding: 8px;
            box-shadow: 0 0 30px rgba(251, 191, 36, 0.3);
            transition: all 0.5s ease;
        }

        .navbar-brand:hover img {
            transform: scale(1.1) rotate(5deg);
            box-shadow: 0 0 40px rgba(251, 191, 36, 0.5);
        }

        .navbar-nav .nav-link {
            color: var(--midnight-blue) !important;
            font-weight: 400;
            font-size: 0.95rem;
            margin: 0 12px;
            transition: all 0.3s ease;
            position: relative;
            text-decoration: none !important;
        }

        .navbar-nav .nav-link:hover,
        .navbar-nav .nav-link.active {
            color: var(--ocean-blue) !important;
            font-weight: 500;
        }

        .navbar-nav .nav-link::after {
            content: '';
            position: absolute;
            bottom: -8px;
            left: 50%;
            width: 0;
            height: 2px;
            background: var(--gradient-divine);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            transform: translateX(-50%);
        }

        .navbar-nav .nav-link:hover::after,
        .navbar-nav .nav-link.active::after {
            width: 100%;
        }

        /* Hero Section - Mindblowing */
        .hero {
            background: var(--gradient-ocean);
            min-height: 60vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 300%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
            animation: divineShimmer 15s infinite;
        }

        @keyframes divineShimmer {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }

        .hero-particles {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
        }

        .particle {
            position: absolute;
            width: 4px;
            height: 4px;
            background: var(--heavenly-gold);
            border-radius: 50%;
            opacity: 0.6;
            animation: float 20s infinite linear;
        }

        @keyframes float {
            0% {
                transform: translateY(100vh) translateX(0);
                opacity: 0;
            }
            10% {
                opacity: 0.6;
            }
            90% {
                opacity: 0.6;
            }
            100% {
                transform: translateY(-100vh) translateX(100px);
                opacity: 0;
            }
        }

        .hero-content {
            position: relative;
            z-index: 10;
            text-align: center;
            color: var(--snow-white);
            max-width: 800px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .hero-logo {
            margin-bottom: 2rem;
            animation: logoFloat 8s ease-in-out infinite;
        }

        @keyframes logoFloat {
            0%, 100% { transform: translateY(0) scale(1); }
            50% { transform: translateY(-15px) scale(1.05); }
        }

        .hero-logo img {
            height: 120px;
            width: auto;
            border-radius: 50%;
            background: var(--snow-white);
            padding: 15px;
            box-shadow: 0 0 50px rgba(251, 191, 36, 0.4);
            transition: all 0.5s ease;
        }

        .hero-logo:hover img {
            transform: scale(1.1) rotate(10deg);
            box-shadow: 0 0 70px rgba(251, 191, 36, 0.6);
        }

        .hero-title {
            font-family: 'Playfair Display', serif;
            font-size: clamp(2.5rem, 6vw, 4rem);
            font-weight: 900;
            margin-bottom: 1.5rem;
            text-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
            letter-spacing: -0.02em;
            animation: titleGlow 4s ease-in-out infinite alternate;
        }

        @keyframes titleGlow {
            0% { text-shadow: 0 4px 20px rgba(0, 0, 0, 0.3); }
            100% { text-shadow: 0 4px 30px rgba(251, 191, 36, 0.4); }
        }

        .hero-subtitle {
            font-family: 'Great Vibes', cursive;
            font-size: clamp(1.8rem, 4vw, 2.5rem);
            font-weight: 400;
            margin-bottom: 2rem;
            opacity: 0.95;
            letter-spacing: 0.05em;
            animation: subtitleFloat 6s ease-in-out infinite;
        }

        @keyframes subtitleFloat {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }

        /* Sections - Iconic Design */
        .section {
            padding: 100px 0;
            position: relative;
        }

        .section-light {
            background: var(--snow-white);
        }

        .section-heaven {
            background: var(--gradient-heaven);
        }

        .section-title {
            font-family: 'Playfair Display', serif;
            font-size: clamp(2.5rem, 6vw, 3.5rem);
            font-weight: 900;
            text-align: center;
            margin-bottom: 1rem;
            position: relative;
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: -15px;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 4px;
            background: var(--gradient-divine);
            border-radius: 2px;
        }

        .section-subtitle {
            text-align: center;
            font-size: 1.2rem;
            margin-bottom: 4rem;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
            font-weight: 300;
        }

        /* Gallery Grid */
        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 2rem;
            margin-top: 4rem;
        }

        .gallery-item {
            background: var(--snow-white);
            border-radius: 25px;
            overflow: hidden;
            box-shadow: var(--shadow-soft);
            border: 1px solid rgba(125, 211, 252, 0.2);
            transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            cursor: pointer;
        }

        .gallery-item:hover {
            transform: translateY(-15px);
        }

        .gallery-image {
            position: relative;
            overflow: hidden;
            border-radius: 10px;
            height: 300px;
            cursor: pointer;
        }

        .gallery-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .gallery-image:hover img {
            transform: scale(1.05);
        }

.gallery-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(to bottom, transparent 0%, rgba(15, 23, 42, 0.8) 100%);
    opacity: 0;
    transition: all 0.3s ease;
    display: flex;
    align-items: flex-end;
    padding: 2rem;
}

.gallery-item:hover .gallery-overlay {
    opacity: 1;
}
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(to bottom, transparent 0%, rgba(15, 23, 42, 0.8) 100%);
            opacity: 0;
            transition: all 0.3s ease;
            display: flex;
            align-items: flex-end;
            padding: 2rem;
        }

        .gallery-item:hover .gallery-overlay {
            opacity: 1;
        }

        .gallery-info {
            color: var(--snow-white);
            z-index: 2;
        }

        .gallery-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--snow-white);
            margin-bottom: 0.5rem;
        }

        .gallery-description {
            font-size: 0.9rem;
            color: rgba(255, 255, 255, 0.9);
            margin-bottom: 0.5rem;
            line-height: 1.4;
        }

        .gallery-meta {
            font-size: 0.8rem;
            color: rgba(255, 255, 255, 0.7);
        }

        /* Content Type Badges */
        .content-type-badge {
            position: absolute;
            top: 10px;
            left: 10px;
            background: rgba(15, 23, 42, 0.8);
            color: white;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 0.7rem;
            font-weight: 600;
            z-index: 3;
        }

        .content-type-badge.writing {
            background: rgba(34, 197, 94, 0.8);
        }

        .content-type-badge.mixed {
            background: rgba(168, 85, 247, 0.8);
        }

        /* Writing Preview Styles */
        .writing-preview {
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 20px;
            text-align: center;
        }

        .writing-icon {
            font-size: 3rem;
            margin-bottom: 15px;
            opacity: 0.7;
        }

        .writing-preview-text {
            font-size: 0.9rem;
            color: #475569;
            line-height: 1.4;
            font-style: italic;
        }

        .writing-excerpt {
            font-style: italic;
            color: rgba(255, 255, 255, 0.9);
            margin: 5px 0;
            line-height: 1.3;
        }

        /* Expired Content Styles */
        .gallery-item.expired {
            opacity: 0.6;
            border: 2px dashed #ef4444;
        }

        .expired-badge {
            background: rgba(239, 68, 68, 0.8);
            color: white;
            padding: 2px 6px;
            border-radius: 8px;
            font-size: 0.7rem;
        }

        .expire-time {
            background: rgba(251, 191, 36, 0.8);
            color: white;
            padding: 2px 6px;
            border-radius: 8px;
            font-size: 0.7rem;
        }

        .gallery-actions {
            position: absolute;
            top: 10px;
            right: 10px;
            display: flex;
            gap: 5px;
            opacity: 0;
            transition: all 0.3s ease;
        }

        .gallery-item:hover .gallery-actions {
            opacity: 1;
        }

        .gallery-action-btn {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--snow-white);
            background: rgba(15, 23, 42, 0.8);
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 0.9rem;
        }

        .gallery-action-btn:hover {
            background: var(--heavenly-gold);
            color: var(--midnight-blue);
            transform: scale(1.1);
        }

        .gallery-action-btn.delete:hover {
            background: #dc2626;
            color: var(--snow-white);
        }

        /* Upload Section */
        .upload-section {
            background: var(--gradient-heaven);
            padding: 4rem;
            border-radius: 30px;
            margin-bottom: 4rem;
            border: 1px solid var(--ice-blue);
        }

        .upload-form {
            background: var(--snow-white);
            padding: 3rem;
            border-radius: 25px;
            box-shadow: var(--shadow-soft);
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: var(--midnight-blue);
        }

        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid var(--ice-blue);
            border-radius: 15px;
            background: var(--snow-white);
            color: var(--midnight-blue);
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--ocean-blue);
            box-shadow: 0 0 20px rgba(14, 165, 233, 0.2);
        }

        .btn-upload {
            background: var(--gradient-ocean);
            color: var(--snow-white);
            border: none;
            padding: 12px 30px;
            border-radius: 50px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-upload:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(14, 165, 233, 0.3);
            color: var(--snow-white);
        }

        /* Alert Styles */
        .alert {
            padding: 1rem 1.5rem;
            border-radius: 15px;
            margin-bottom: 2rem;
            border: none;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .alert-success {
            background: linear-gradient(135deg, rgba(34, 197, 94, 0.1), rgba(34, 197, 94, 0.05));
            color: #16a34a;
            border: 1px solid rgba(34, 197, 94, 0.2);
        }

        .alert-danger {
            background: linear-gradient(135deg, rgba(239, 68, 68, 0.1), rgba(239, 68, 68, 0.05));
            color: #dc2626;
            border: 1px solid rgba(239, 68, 68, 0.2);
        }

        /* Lightbox Styles */
        .lightbox {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.9);
            z-index: 9999;
            cursor: pointer;
        }

        .lightbox.active {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .lightbox-content {
            max-width: 90%;
            max-height: 90%;
            position: relative;
        }

        .lightbox-content img {
            width: 100%;
            height: auto;
            border-radius: 10px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.5);
        }

        .lightbox-close {
            position: absolute;
            top: -40px;
            right: 0;
            background: var(--heavenly-gold);
            color: var(--midnight-blue);
            border: none;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            font-size: 1.5rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .lightbox-close:hover {
            transform: scale(1.1);
            box-shadow: 0 10px 25px rgba(251, 191, 36, 0.3);
        }

        /* Stats Section */
        .stats-bar {
            background: var(--gradient-heaven);
            padding: 2rem;
            border-radius: 20px;
            margin-bottom: 4rem;
            text-align: center;
            border: 1px solid var(--ice-blue);
        }

        .stats-content {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 3rem;
            flex-wrap: wrap;
        }

        .stat-item {
            text-align: center;
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: 900;
            color: var(--heavenly-gold);
            font-family: 'Playfair Display', serif;
        }

        .stat-label {
            color: var(--midnight-blue);
            font-weight: 600;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .hero {
                min-height: 50vh;
                padding: 2rem 0;
            }

            .hero-title {
                font-size: 2.5rem;
            }

            .hero-subtitle {
                font-size: 2rem;
            }

            .section {
                padding: 60px 0;
            }

            .gallery-grid {
                grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
                gap: 1.5rem;
            }

            .upload-section {
                padding: 2rem;
            }

            .upload-form {
                padding: 2rem;
            }

            .stats-content {
                gap: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <img src="assets/logo-DEFqnQ4s.jpeg" alt="Salem Dominion Ministries">
                <span>Salem Dominion Ministries</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="about.php">About</a></li>
                    <li class="nav-item"><a class="nav-link" href="ministries.php">Ministries</a></li>
                    <li class="nav-item"><a class="nav-link" href="events.php">Events</a></li>
                    <li class="nav-item"><a class="nav-link" href="sermons.php">Sermons</a></li>
                    <li class="nav-item"><a class="nav-link" href="news.php">News</a></li>
                    <li class="nav-item"><a class="nav-link active" href="gallery.php">Gallery</a></li>
                    <li class="nav-item"><a class="nav-link" href="leadership.php">Leadership</a></li>
                    <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
                    <li class="nav-item">
                        <a class="nav-link" href="login.php">
                            <i class="fas fa-sign-in-alt me-2"></i>Login
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero">
        <!-- Divine Particles -->
        <div class="hero-particles" id="heroParticles"></div>
        
        <div class="hero-content" data-aos="fade-up" data-aos-duration="1500">
            <div class="hero-logo">
                <img src="assets/logo-DEFqnQ4s.jpeg" alt="Salem Dominion Ministries">
            </div>
            <h1 class="hero-title">Gallery</h1>
            <p class="hero-subtitle">Capturing God's Faithfulness</p>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="section section-heaven">
        <div class="container">
            <div class="stats-bar" data-aos="fade-up">
                <div class="stats-content">
                    <div class="stat-item">
                        <div class="stat-number"><?php echo number_format($total_images ?? 0); ?></div>
                        <div class="stat-label">Photos</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number">∞</div>
                        <div class="stat-label">Events</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number">∞</div>
                        <div class="stat-label">Ministries</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number">∞</div>
                        <div class="stat-label">Memories</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Upload Section -->
    <section class="section section-light">
        <div class="container">
            <div class="upload-section" data-aos="fade-up">
                <h2 class="section-title">Share Your Photos</h2>
                <p class="section-subtitle">Help us capture our church journey by sharing your photos</p>
                
                <?php if ($upload_success): ?>
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i>
                        <?php echo htmlspecialchars($upload_success); ?>
                    </div>
                <?php endif; ?>
                
                <?php if ($upload_error): ?>
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle"></i>
                        <?php echo htmlspecialchars($upload_error); ?>
                    </div>
                <?php endif; ?>
                
                <form class="upload-form" method="POST" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="gallery_image" class="form-label">Choose Photo</label>
                                <input type="file" id="gallery_image" name="gallery_image" class="form-control" accept="image/*" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="title" class="form-label">Title (Optional)</label>
                                <input type="text" id="title" name="title" class="form-control" placeholder="Photo title">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="description" class="form-label">Description (Optional)</label>
                        <textarea id="description" name="description" class="form-control" rows="3" placeholder="Describe this photo..."></textarea>
                    </div>
                    <button type="submit" class="btn-upload">
                        <i class="fas fa-upload"></i>
                        Upload Photo
                    </button>
                </form>
            </div>
        </div>
    </section>

    <!-- Gallery Section -->
    <section class="section section-light">
        <div class="container">
            <h2 class="section-title" data-aos="fade-up">Our Gallery</h2>
            <p class="section-subtitle" data-aos="fade-up" data-aos-delay="100">Moments that capture our journey of faith</p>
            
            <div class="gallery-grid" id="galleryGrid">
                <?php if (!empty($gallery_images)): ?>
                    <?php foreach ($gallery_images as $index => $image): ?>
                        <?php 
                        // Get uploader information
                        $uploader_name = 'Church Member';
                        if ($image['uploaded_by']) {
                            $uploader = $db->selectOne("SELECT first_name, last_name FROM users WHERE id = ?", [$image['uploaded_by']]);
                            if ($uploader) {
                                $uploader_name = htmlspecialchars($uploader['first_name'] . ' ' . $uploader['last_name']);
                            }
                        }
                        
                        // Determine content display
                        $content_type = $image['content_type'] ?? 'image';
                        $is_expired = $image['auto_expire'] && $image['expires_at'] && strtotime($image['expires_at']) < time();
                        ?>
                        <div class="gallery-item <?php echo $is_expired ? 'expired' : ''; ?>" data-aos="fade-up" data-aos-delay="<?php echo 200 + ($index * 100); ?>">
                            <div class="gallery-actions">
                                <button class="gallery-action-btn" onclick="openLightbox(<?php echo $index; ?>)">
                                    <i class="fas fa-expand"></i>
                                </button>
                                <?php if ($content_type === 'writing'): ?>
                                    <span class="content-type-badge writing">📝 Writing</span>
                                <?php elseif ($content_type === 'mixed'): ?>
                                    <span class="content-type-badge mixed">🖼️+📝</span>
                                <?php else: ?>
                                    <span class="content-type-badge image">🖼️ Image</span>
                                <?php endif; ?>
                            </div>
                            <div class="gallery-image" onclick="openLightbox(<?php echo $index; ?>)">
                                <?php if ($content_type === 'writing'): ?>
                                    <!-- Writing-only content display -->
                                    <div class="writing-preview">
                                        <div class="writing-icon">📝</div>
                                        <div class="writing-preview-text">
                                            <?php echo htmlspecialchars(substr($image['writing_content'] ?? '', 0, 150)); ?><?php if (strlen($image['writing_content'] ?? '') > 150) echo '...'; ?>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <!-- Image content display -->
                                    <?php if (file_exists($image['file_url'])): ?>
                                        <img src="<?php echo htmlspecialchars($image['file_url']); ?>" alt="<?php echo htmlspecialchars($image['title'] ?? 'Gallery Image'); ?>" style="width: 100%; height: 100%; object-fit: cover;">
                                    <?php else: ?>
                                        <img src="assets/hero-worship-CWyaH0tr.jpg" alt="Gallery Image" style="width: 100%; height: 100%; object-fit: cover;">
                                    <?php endif; ?>
                                <?php endif; ?>
                                <div class="gallery-overlay">
                                    <div class="gallery-info">
                                        <div class="gallery-title"><?php echo htmlspecialchars($image['title'] ?? 'Church Event'); ?></div>
                                        <?php if (!empty($image['description'])): ?>
                                            <div class="gallery-description"><?php echo htmlspecialchars(substr($image['description'], 0, 100)); ?><?php if (strlen($image['description']) > 100) echo '...'; ?></div>
                                        <?php endif; ?>
                                        <?php if ($content_type === 'writing' && !empty($image['writing_content'])): ?>
                                            <div class="writing-excerpt">
                                                "<?php echo htmlspecialchars(substr($image['writing_content'], 0, 80)); ?><?php if (strlen($image['writing_content']) > 80) echo '...'; ?>"
                                            </div>
                                        <?php endif; ?>
                                        <div class="gallery-meta">
                                            <?php echo date('M j, Y', strtotime($image['created_at'])); ?> • 
                                            Uploaded by <?php echo $uploader_name; ?>
                                            <?php if (!empty($image['category'])): ?>
                                                • <?php echo ucfirst($image['category']); ?>
                                            <?php endif; ?>
                                            <?php if ($is_expired): ?>
                                                • <span class="expired-badge">⏰ Expired</span>
                                            <?php elseif ($image['auto_expire']): ?>
                                                • <span class="expire-time">⏰ Expires: <?php echo date('M j, H:i', strtotime($image['expires_at'])); ?></span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <!-- Sample Gallery Items -->
                    <div class="gallery-item" data-aos="fade-up" data-aos-delay="200">
                        <div class="gallery-actions">
                            <button class="gallery-action-btn" onclick="openLightbox(0)">
                                <i class="fas fa-expand"></i>
                            </button>
                        </div>
                        <div class="gallery-image" onclick="openLightbox(0)">
                            <img src="assets/hero-worship-CWyaH0tr.jpg" alt="Worship Service">
                            <div class="gallery-overlay">
                                <div class="gallery-info">
                                    <div class="gallery-title">Sunday Worship</div>
                                    <div class="gallery-meta">Powerful praise and worship service</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="gallery-item" data-aos="fade-up" data-aos-delay="300">
                        <div class="gallery-actions">
                            <button class="gallery-action-btn" onclick="openLightbox(1)">
                                <i class="fas fa-expand"></i>
                            </button>
                        </div>
                        <div class="gallery-image" onclick="openLightbox(1)">
                            <img src="assets/hero-community-CDAgPtPb.jpg" alt="Community Outreach">
                            <div class="gallery-overlay">
                                <div class="gallery-info">
                                    <div class="gallery-title">Community Outreach</div>
                                    <div class="gallery-meta">Serving our local community</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="gallery-item" data-aos="fade-up" data-aos-delay="400">
                        <div class="gallery-actions">
                            <button class="gallery-action-btn" onclick="openLightbox(2)">
                                <i class="fas fa-expand"></i>
                            </button>
                        </div>
                        <div class="gallery-image" onclick="openLightbox(2)">
                            <img src="assets/hero-choir-6lo-hX_h.jpg" alt="Choir Performance">
                            <div class="gallery-overlay">
                                <div class="gallery-info">
                                    <div class="gallery-title">Choir Ministry</div>
                                    <div class="gallery-meta">Beautiful voices in harmony</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="gallery-item" data-aos="fade-up" data-aos-delay="500">
                        <div class="gallery-actions">
                            <button class="gallery-action-btn" onclick="openLightbox(3)">
                                <i class="fas fa-expand"></i>
                            </button>
                        </div>
                        <div class="gallery-image" onclick="openLightbox(3)">
                            <img src="assets/logo-DEFqnQ4s.jpeg" alt="Church Logo">
                            <div class="gallery-overlay">
                                <div class="gallery-info">
                                    <div class="gallery-title">Our Church Logo</div>
                                    <div class="gallery-meta">Salem Dominion Ministries</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="gallery-item" data-aos="fade-up" data-aos-delay="600">
                        <div class="gallery-actions">
                            <button class="gallery-action-btn" onclick="openLightbox(4)">
                                <i class="fas fa-expand"></i>
                            </button>
                        </div>
                        <div class="gallery-image" onclick="openLightbox(4)">
                            <img src="assets/APOSTLE-IRENE-MIREMBE-CwWfzcRx.jpeg" alt="Leadership">
                            <div class="gallery-overlay">
                                <div class="gallery-info">
                                    <div class="gallery-title">Our Leadership</div>
                                    <div class="gallery-meta">Dedicated servant leaders</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="gallery-item" data-aos="fade-up" data-aos-delay="700">
                        <div class="gallery-actions">
                            <button class="gallery-action-btn" onclick="openLightbox(5)">
                                <i class="fas fa-expand"></i>
                            </button>
                        </div>
                        <div class="gallery-image" onclick="openLightbox(5)">
                            <img src="assets/PASTOR-NABULYA-JOYCE-BdB4SkbM.jpeg" alt="Women Ministry">
                            <div class="gallery-overlay">
                                <div class="gallery-info">
                                    <div class="gallery-title">Women Ministry</div>
                                    <div class="gallery-meta">Empowering women in faith</div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Lightbox -->
    <div class="lightbox" id="lightbox" onclick="closeLightbox()">
        <div class="lightbox-content">
            <button class="lightbox-close" onclick="closeLightbox()">×</button>
            <div id="lightbox-media"></div>
            <div id="lightbox-info" style="position: absolute; bottom: -80px; left: 0; right: 0; text-align: center; color: white; background: rgba(0,0,0,0.7); padding: 15px; border-radius: 0 0 10px 10px;">
                <h4 id="lightbox-title" style="margin: 0 0 5px 0;"></h4>
                <p id="lightbox-description" style="margin: 0 0 5px 0; font-size: 0.9rem;"></p>
                <p id="lightbox-writing" style="margin: 0 0 5px 0; font-style: italic; font-size: 0.95rem;"></p>
                <p id="lightbox-meta" style="margin: 0; font-size: 0.8rem; opacity: 0.8;"></p>
            </div>
        </div>
    </div>

    <!-- Ultimate Footer -->
    <?php require_once 'components/ultimate_footer_clean.php'; ?>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- AOS Animation -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    
    <script>
        // Gallery data for lightbox with uploader information
        const galleryData = <?php 
            $enhanced_gallery_data = [];
            foreach ($gallery_images as $image) {
                // Get uploader information for each image
                $uploader_name = 'Church Member';
                if ($image['uploaded_by']) {
                    $uploader = $db->selectOne("SELECT first_name, last_name FROM users WHERE id = ?", [$image['uploaded_by']]);
                    if ($uploader) {
                        $uploader_name = htmlspecialchars($uploader['first_name'] . ' ' . $uploader['last_name']);
                    }
                }
                
                $enhanced_gallery_data[] = array_merge($image, ['uploader_name' => $uploader_name]);
            }
            echo json_encode($enhanced_gallery_data);
        ?>;
        const sampleImages = [
            'assets/hero-worship-CWyaH0tr.jpg',
            'assets/hero-community-CDAgPtPb.jpg',
            'assets/hero-choir-6lo-hX_h.jpg',
            'assets/logo-DEFqnQ4s.jpeg',
            'assets/APOSTLE-IRENE-MIREMBE-CwWfzcRx.jpeg',
            'assets/PASTOR-NABULYA-JOYCE-BdB4SkbM.jpeg'
        ];
        
        // Initialize AOS
        AOS.init({
            duration: 1200,
            once: true,
            offset: 100,
            easing: 'ease-in-out'
        });

        // Create divine particles
        function createParticles() {
            const particlesContainer = document.getElementById('heroParticles');
            const particleCount = 15;
            
            if (particlesContainer) {
                for (let i = 0; i < particleCount; i++) {
                    const particle = document.createElement('div');
                    particle.className = 'particle';
                    particle.style.left = Math.random() * 100 + '%';
                    particle.style.animationDelay = Math.random() * 20 + 's';
                    particle.style.animationDuration = (15 + Math.random() * 10) + 's';
                    particlesContainer.appendChild(particle);
                }
            }
        }

        // Navbar scroll effect
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar');
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });

        // Initialize particles
        createParticles();

        // Lightbox functions
        function openLightbox(index) {
            const lightbox = document.getElementById('lightbox');
            const lightboxMedia = document.getElementById('lightbox-media');
            const lightboxTitle = document.getElementById('lightbox-title');
            const lightboxDescription = document.getElementById('lightbox-description');
            const lightboxWriting = document.getElementById('lightbox-writing');
            const lightboxMeta = document.getElementById('lightbox-meta');
            
            // Use real gallery data if available, otherwise use sample images
            if (galleryData.length > 0 && index < galleryData.length) {
                const imageData = galleryData[index];
                const contentType = imageData.content_type || 'image';
                
                // Clear previous content
                lightboxMedia.innerHTML = '';
                
                if (contentType === 'writing') {
                    // Show writing content
                    lightboxMedia.innerHTML = `
                        <div style="max-width: 600px; max-height: 70vh; overflow-y: auto; padding: 30px; background: white; border-radius: 10px; color: #333; line-height: 1.6;">
                            <div style="font-size: 1.1rem; white-space: pre-wrap;">${imageData.writing_content || 'No content available'}</div>
                        </div>
                    `;
                } else {
                    // Show image (for image or mixed content)
                    const img = document.createElement('img');
                    img.src = imageData.file_url || sampleImages[index % sampleImages.length];
                    img.alt = imageData.title || 'Gallery Image';
                    img.style.maxWidth = '90vw';
                    img.style.maxHeight = '70vh';
                    img.style.width = 'auto';
                    img.style.height = 'auto';
                    img.style.objectFit = 'contain';
                    img.style.borderRadius = '10px';
                    lightboxMedia.appendChild(img);
                }
                
                // Update text information
                lightboxTitle.textContent = imageData.title || 'Gallery Image';
                lightboxDescription.textContent = imageData.description || '';
                
                // Show writing content for mixed type
                if (contentType === 'mixed' && imageData.writing_content) {
                    lightboxWriting.textContent = imageData.writing_content;
                    lightboxWriting.style.display = 'block';
                } else {
                    lightboxWriting.style.display = 'none';
                }
                
                // Format date and uploader info
                const uploadDate = new Date(imageData.created_at).toLocaleDateString('en-US', { 
                    year: 'numeric', 
                    month: 'short', 
                    day: 'numeric' 
                });
                
                let metaInfo = `${uploadDate} • Uploaded by ${imageData.uploader_name || 'Church Member'}`;
                if (imageData.category) {
                    metaInfo += ` • ${imageData.category.charAt(0).toUpperCase() + imageData.category.slice(1)}`;
                }
                
                // Add expiration info
                if (imageData.auto_expire) {
                    const expiresDate = new Date(imageData.expires_at).toLocaleString('en-US', { 
                        month: 'short', 
                        day: 'numeric', 
                        hour: '2-digit', 
                        minute: '2-digit' 
                    });
                    const isExpired = new Date(imageData.expires_at) < new Date();
                    metaInfo += ` • ${isExpired ? '⏰ Expired' : '⏰ Expires: ' + expiresDate}`;
                }
                
                lightboxMeta.textContent = metaInfo;
            } else {
                // Fallback to sample images
                const img = document.createElement('img');
                img.src = sampleImages[index % sampleImages.length];
                img.alt = 'Sample Image';
                img.style.maxWidth = '90vw';
                img.style.maxHeight = '70vh';
                img.style.width = 'auto';
                img.style.height = 'auto';
                img.style.objectFit = 'contain';
                img.style.borderRadius = '10px';
                lightboxMedia.appendChild(img);
                
                lightboxTitle.textContent = 'Sample Image';
                lightboxDescription.textContent = 'This is a sample gallery image';
                lightboxWriting.style.display = 'none';
                lightboxMeta.textContent = 'Sample • Church Member';
            }
            
            lightbox.classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeLightbox() {
            const lightbox = document.getElementById('lightbox');
            lightbox.classList.remove('active');
            document.body.style.overflow = 'auto';
        }

        // Keyboard navigation for lightbox
        document.addEventListener('keydown', function(e) {
            const lightbox = document.getElementById('lightbox');
            if (lightbox.classList.contains('active')) {
                if (e.key === 'Escape') {
                    closeLightbox();
                }
            }
        });

        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Add parallax effect to hero section
        window.addEventListener('scroll', function() {
            const scrolled = window.pageYOffset;
            const heroContent = document.querySelector('.hero-content');
            if (heroContent) {
                heroContent.style.transform = `translateY(${scrolled * 0.5}px)`;
                heroContent.style.opacity = 1 - (scrolled / 600);
            }
        });

        // Auto-hide alerts after 5 seconds
        setTimeout(() => {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                alert.style.transition = 'opacity 0.5s';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            });
        }, 5000);
    </script>
</body>
</html>
