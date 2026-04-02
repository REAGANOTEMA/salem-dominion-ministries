<?php
// Complete error suppression to prevent unwanted output
error_reporting(0);
ini_set('display_errors', 0);
ini_set('log_errors', 0);

// Buffer output to catch any accidental output
ob_start();

// Include session helper and start session safely
require_once 'session_helper.php';
secure_session_start();
require_once 'db.php';

// Get gallery images with error handling
try {
    $gallery_images = $db->query("SELECT g.*, u.first_name, u.last_name FROM gallery g LEFT JOIN users u ON g.uploaded_by = u.id WHERE g.status = 'published' ORDER BY g.created_at DESC");
    $total_images = $db->selectOne("SELECT COUNT(*) as count FROM gallery WHERE status = 'published'")['count'] ?? 0;
} catch (Exception $e) {
    $gallery_images = [];
    $total_images = 0;
}

// Handle image upload for members
$upload_success = '';
$upload_error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id']) && isset($_FILES['gallery_image'])) {
    $user_id = $_SESSION['user_id'];
    $file = $_FILES['gallery_image'];

    if ($file['error'] === UPLOAD_ERR_OK) {
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        if (in_array($file['type'], $allowed_types)) {
            $upload_dir = 'uploads/gallery/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }

            $filename = uniqid() . '_' . basename($file['name']);
            $filepath = $upload_dir . $filename;

            if (move_uploaded_file($file['tmp_name'], $filepath)) {
                $stmt = $db->prepare("INSERT INTO gallery (uploaded_by, file_url, title, description, file_type, status) VALUES (?, ?, ?, ?, 'image', 'published')");
                $title = trim($_POST['title'] ?? '');
                $description = trim($_POST['description'] ?? '');
                $stmt->bind_param('isss', $user_id, $filepath, $title, $description);
                $stmt->execute();
                $stmt->close();
                $upload_success = 'Image uploaded successfully!';
                
                // Refresh gallery data
                $gallery_images = $db->query("SELECT g.*, u.first_name, u.last_name FROM gallery g LEFT JOIN users u ON g.uploaded_by = u.id WHERE g.status = 'published' ORDER BY g.created_at DESC");
            } else {
                $upload_error = 'Failed to upload image.';
            }
        } else {
            $upload_error = 'Invalid file type. Only JPEG, PNG, GIF, and WebP are allowed.';
        }
    } else {
        $upload_error = 'Upload error occurred.';
    }
}

// Clean any buffered output
ob_end_clean();
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
            box-shadow: var(--shadow-divine);
        }

        .gallery-image {
            position: relative;
            height: 300px;
            overflow: hidden;
        }

        .gallery-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: all 0.5s ease;
        }

        .gallery-item:hover .gallery-image img {
            transform: scale(1.1);
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

        .gallery-info {
            color: var(--snow-white);
            z-index: 2;
        }

        .gallery-title {
            font-size: 1.3rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            font-family: 'Playfair Display', serif;
        }

        .gallery-meta {
            font-size: 0.9rem;
            opacity: 0.9;
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
                        <div class="stat-number"><?php echo number_format($total_images); ?></div>
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

    <!-- Upload Section (for logged-in users) -->
    <?php if (isset($_SESSION['user_id'])): ?>
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
    <?php endif; ?>

    <!-- Gallery Section -->
    <section class="section section-light">
        <div class="container">
            <h2 class="section-title" data-aos="fade-up">Our Gallery</h2>
            <p class="section-subtitle" data-aos="fade-up" data-aos-delay="100">Moments that capture our journey of faith</p>
            
            <div class="gallery-grid">
                <?php if ($gallery_images && $gallery_images->num_rows > 0): ?>
                    <?php while ($image = $gallery_images->fetch_assoc()): ?>
                        <div class="gallery-item" data-aos="fade-up" data-aos-delay="200" onclick="openLightbox('<?php echo htmlspecialchars($image['file_url']); ?>')">
                            <div class="gallery-image">
                                <img src="<?php echo htmlspecialchars($image['file_url']); ?>" alt="<?php echo htmlspecialchars($image['title'] ?? 'Gallery Image'); ?>">
                                <div class="gallery-overlay">
                                    <div class="gallery-info">
                                        <div class="gallery-title"><?php echo htmlspecialchars($image['title'] ?? 'Church Event'); ?></div>
                                        <div class="gallery-meta">
                                            <?php echo date('M j, Y', strtotime($image['created_at'])); ?> • 
                                            <?php echo htmlspecialchars(($image['first_name'] ?? 'Church') . ' ' . ($image['last_name'] ?? 'Team')); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <!-- Sample Gallery Items -->
                    <div class="gallery-item" data-aos="fade-up" data-aos-delay="200" onclick="openLightbox('assets/hero-worship-CWyaH0tr.jpg')">
                        <div class="gallery-image">
                            <img src="assets/hero-worship-CWyaH0tr.jpg" alt="Worship Service">
                            <div class="gallery-overlay">
                                <div class="gallery-info">
                                    <div class="gallery-title">Sunday Worship</div>
                                    <div class="gallery-meta">Powerful praise and worship service</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="gallery-item" data-aos="fade-up" data-aos-delay="300" onclick="openLightbox('assets/hero-community-CDAgPtPb.jpg')">
                        <div class="gallery-image">
                            <img src="assets/hero-community-CDAgPtPb.jpg" alt="Community Outreach">
                            <div class="gallery-overlay">
                                <div class="gallery-info">
                                    <div class="gallery-title">Community Outreach</div>
                                    <div class="gallery-meta">Serving our local community</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="gallery-item" data-aos="fade-up" data-aos-delay="400" onclick="openLightbox('assets/hero-choir-6lo-hX_h.jpg')">
                        <div class="gallery-image">
                            <img src="assets/hero-choir-6lo-hX_h.jpg" alt="Choir Performance">
                            <div class="gallery-overlay">
                                <div class="gallery-info">
                                    <div class="gallery-title">Choir Ministry</div>
                                    <div class="gallery-meta">Beautiful voices in harmony</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="gallery-item" data-aos="fade-up" data-aos-delay="500" onclick="openLightbox('assets/logo-DEFqnQ4s.jpeg')">
                        <div class="gallery-image">
                            <img src="assets/logo-DEFqnQ4s.jpeg" alt="Church Logo">
                            <div class="gallery-overlay">
                                <div class="gallery-info">
                                    <div class="gallery-title">Our Church Logo</div>
                                    <div class="gallery-meta">Salem Dominion Ministries</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="gallery-item" data-aos="fade-up" data-aos-delay="600" onclick="openLightbox('assets/APOSTLE-IRENE-MIREMBE-CwWfzcRx.jpeg')">
                        <div class="gallery-image">
                            <img src="assets/APOSTLE-IRENE-MIREMBE-CwWfzcRx.jpeg" alt="Leadership">
                            <div class="gallery-overlay">
                                <div class="gallery-info">
                                    <div class="gallery-title">Our Leadership</div>
                                    <div class="gallery-meta">Dedicated servant leaders</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="gallery-item" data-aos="fade-up" data-aos-delay="700" onclick="openLightbox('assets/PASTOR-NABULYA-JOYCE-BdB4SkbM.jpeg')">
                        <div class="gallery-image">
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
            <img id="lightbox-image" src="" alt="Gallery Image">
        </div>
    </div>

    <!-- Ultimate Footer -->
    <?php require_once 'components/ultimate_footer_enhanced.php'; ?>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- AOS Animation -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    
    <script>
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
        function openLightbox(imageSrc) {
            const lightbox = document.getElementById('lightbox');
            const lightboxImage = document.getElementById('lightbox-image');
            
            lightboxImage.src = imageSrc;
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
            if (e.key === 'Escape') {
                closeLightbox();
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
    </script>
</body>
</html>
