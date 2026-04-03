<?php
session_start();
require_once 'db.php';

// Pagination
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$per_page = 9;
$offset = ($page - 1) * $per_page;

// Search and filter
$category_filter = isset($_GET['category']) ? $_GET['category'] : '';
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

// Build query
$query = "SELECT bp.*, u.first_name, u.last_name FROM blog_posts bp LEFT JOIN users u ON bp.author_id = u.id WHERE bp.status = 'published'";
$count_query = "SELECT COUNT(*) as total FROM blog_posts bp WHERE bp.status = 'published'";

$params = [];
$types = '';

if ($category_filter) {
    $query .= " AND bp.category = ?";
    $count_query .= " AND bp.category = ?";
    $params[] = $category_filter;
    $types .= 's';
}

if ($search) {
    $query .= " AND (bp.title LIKE ? OR bp.content LIKE ? OR bp.excerpt LIKE ?)";
    $count_query .= " AND (bp.title LIKE ? OR bp.content LIKE ? OR bp.excerpt LIKE ?)";
    $search_param = "%$search%";
    $params = array_merge($params, [$search_param, $search_param, $search_param]);
    $types .= 'sss';
}

$query .= " ORDER BY bp.published_at DESC, bp.created_at DESC LIMIT ? OFFSET ?";
$params[] = $per_page;
$params[] = $offset;
$types .= 'ii';

try {
    $stmt = $db->prepare($query);
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    $blog_result = $stmt->get_result();

    $count_stmt = $db->prepare($count_query);
    if (!empty($params) && strlen($types) > 2) {
        $count_params = array_slice($params, 0, -2);
        $count_types = substr($types, 0, -2);
        $count_stmt->bind_param($count_types, ...$count_params);
    } elseif (!empty($params)) {
        $count_stmt->bind_param(substr($types, 0, -2), ...array_slice($params, 0, -2));
    }
    $count_stmt->execute();
    $total_result = $count_stmt->get_result();
    $total_posts = $total_result->fetch_assoc()['total'];
    $total_pages = ceil($total_posts / $per_page);
} catch (Exception $e) {
    $blog_result = [];
    $total_posts = 0;
    $total_pages = 1;
}

// Get categories
try {
    $categories = $db->query("SELECT category, COUNT(*) as count FROM blog_posts WHERE status = 'published' AND category IS NOT NULL AND category != '' GROUP BY category ORDER BY count DESC");
} catch (Exception $e) {
    $categories = [];
}

// Get featured posts
try {
    $featured_posts = $db->query("SELECT bp.*, u.first_name, u.last_name FROM blog_posts bp LEFT JOIN users u ON bp.author_id = u.id WHERE bp.status = 'published' AND bp.is_featured = 1 ORDER BY bp.published_at DESC LIMIT 3");
} catch (Exception $e) {
    $featured_posts = [];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Blog & Articles - Salem Dominion Ministries - Inspiring thoughts, spiritual growth, and community stories">
    <title>Blog - Salem Dominion Ministries</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- AOS Animation -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;0,900;1,400&family=Montserrat:wght@100;200;300;400;500;600;700;800;900&family=Great+Vibes&display=swap" rel="stylesheet">
    
    <style>
        /* ICONIC DESIGN SYSTEM */
        :root {
            --midnight-blue: #0f172a;
            --ocean-blue: #0ea5e9;
            --sky-blue: #38bdf8;
            --ice-blue: #7dd3fc;
            --snow-white: #ffffff;
            --pearl-white: #f8fafc;
            --heavenly-gold: #fbbf24;
            --divine-light: #fef3c7;
            --shadow-divine: 0 20px 40px rgba(15, 23, 42, 0.15);
            --shadow-soft: 0 10px 25px rgba(15, 23, 42, 0.08);
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
        }

        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: 
                radial-gradient(circle at 20% 80%, rgba(251, 191, 36, 0.03) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(14, 165, 233, 0.03) 0%, transparent 50%);
            pointer-events: none;
            z-index: 1;
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: 'Playfair Display', serif;
            font-weight: 700;
            line-height: 1.2;
        }

        .font-divine {
            font-family: 'Great Vibes', cursive;
            color: var(--heavenly-gold);
        }

        /* Navigation */
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
        }

        .navbar-brand img {
            height: 50px;
            width: auto;
            border-radius: 50%;
            background: var(--gradient-heaven);
            padding: 8px;
            box-shadow: 0 0 30px rgba(251, 191, 36, 0.3);
        }

        .navbar-nav .nav-link {
            color: var(--midnight-blue) !important;
            font-weight: 400;
            font-size: 0.95rem;
            margin: 0 12px;
            transition: all 0.3s ease;
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

        /* Hero Section */
        .hero {
            background: var(--gradient-ocean);
            min-height: 50vh;
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
            0% { transform: translateY(100vh) translateX(0); opacity: 0; }
            10% { opacity: 0.6; }
            90% { opacity: 0.6; }
            100% { transform: translateY(-100vh) translateX(100px); opacity: 0; }
        }

        .hero-content {
            position: relative;
            z-index: 10;
            text-align: center;
            color: var(--snow-white);
            max-width: 800px;
            padding: 0 20px;
        }

        .hero-title {
            font-size: clamp(2.5rem, 6vw, 4rem);
            font-weight: 900;
            margin-bottom: 1.5rem;
            text-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
        }

        .hero-subtitle {
            font-family: 'Great Vibes', cursive;
            font-size: clamp(1.8rem, 4vw, 2.5rem);
            opacity: 0.95;
        }

        /* Sections */
        .section {
            padding: 80px 0;
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
            font-size: clamp(2rem, 5vw, 3rem);
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
            font-size: 1.1rem;
            margin-bottom: 3rem;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
            color: var(--ocean-blue);
        }

        /* Search & Filter */
        .search-section {
            background: var(--gradient-heaven);
            padding: 3rem;
            border-radius: 25px;
            margin-bottom: 3rem;
            box-shadow: var(--shadow-soft);
        }

        .form-control {
            padding: 12px 15px;
            border: 2px solid var(--ice-blue);
            border-radius: 15px;
            background: var(--snow-white);
            transition: all 0.3s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--ocean-blue);
            box-shadow: 0 0 20px rgba(14, 165, 233, 0.2);
        }

        .btn-search {
            background: var(--gradient-ocean);
            color: var(--snow-white);
            border: none;
            padding: 12px 25px;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-search:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(14, 165, 233, 0.3);
            color: var(--snow-white);
        }

        .category-badges {
            display: flex;
            flex-wrap: wrap;
            gap: 0.75rem;
            justify-content: center;
            margin-top: 2rem;
        }

        .category-badge {
            padding: 10px 20px;
            border-radius: 50px;
            background: var(--snow-white);
            color: var(--midnight-blue);
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
            border: 2px solid var(--ice-blue);
        }

        .category-badge:hover,
        .category-badge.active {
            background: var(--gradient-ocean);
            color: var(--snow-white);
            border-color: var(--ocean-blue);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(14, 165, 233, 0.3);
        }

        /* Blog Cards */
        .blog-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 2rem;
            margin-top: 3rem;
        }

        .blog-card {
            background: var(--snow-white);
            border-radius: 25px;
            overflow: hidden;
            box-shadow: var(--shadow-soft);
            border: 1px solid rgba(125, 211, 252, 0.2);
            transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .blog-card:hover {
            transform: translateY(-15px);
            box-shadow: var(--shadow-divine);
        }

        .blog-image {
            height: 220px;
            overflow: hidden;
            position: relative;
        }

        .blog-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .blog-card:hover .blog-image img {
            transform: scale(1.1);
        }

        .blog-category {
            position: absolute;
            top: 15px;
            left: 15px;
            background: var(--gradient-ocean);
            color: var(--snow-white);
            padding: 6px 16px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .blog-content {
            padding: 2rem;
        }

        .blog-title {
            font-size: 1.4rem;
            font-weight: 700;
            color: var(--midnight-blue);
            margin-bottom: 1rem;
            line-height: 1.3;
        }

        .blog-excerpt {
            font-size: 0.95rem;
            color: var(--ocean-blue);
            margin-bottom: 1.5rem;
            line-height: 1.6;
        }

        .blog-meta {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem;
            background: var(--pearl-white);
            border-radius: 15px;
            margin-bottom: 1.5rem;
        }

        .blog-author {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .author-avatar {
            width: 40px;
            height: 40px;
            background: var(--gradient-ocean);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--snow-white);
            font-weight: 600;
            font-size: 0.9rem;
        }

        .author-name {
            font-weight: 600;
            font-size: 0.9rem;
            color: var(--midnight-blue);
        }

        .blog-date {
            font-size: 0.85rem;
            color: var(--heavenly-gold);
        }

        .blog-views {
            font-size: 0.85rem;
            color: var(--midnight-blue);
            opacity: 0.7;
        }

        .btn-read-more {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 25px;
            background: var(--gradient-ocean);
            color: var(--snow-white);
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-read-more:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(14, 165, 233, 0.3);
            color: var(--snow-white);
        }

        /* Featured Blog */
        .featured-section {
            background: var(--gradient-heaven);
            padding: 80px 0;
        }

        .featured-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 2rem;
            margin-top: 3rem;
        }

        .featured-card {
            background: var(--gradient-ocean);
            border-radius: 25px;
            padding: 2.5rem;
            color: var(--snow-white);
            transition: all 0.5s ease;
        }

        .featured-card:hover {
            transform: translateY(-10px);
            box-shadow: var(--shadow-divine);
        }

        .featured-card .blog-category {
            background: var(--heavenly-gold);
            color: var(--midnight-blue);
        }

        .featured-card .blog-title {
            color: var(--snow-white);
        }

        .featured-card .blog-excerpt {
            color: rgba(255, 255, 255, 0.9);
        }

        .featured-card .blog-meta {
            background: rgba(255, 255, 255, 0.1);
        }

        .featured-card .author-name,
        .featured-card .blog-views {
            color: var(--snow-white);
        }

        .featured-card .btn-read-more {
            background: var(--snow-white);
            color: var(--midnight-blue);
        }

        /* Pagination */
        .pagination {
            display: flex;
            justify-content: center;
            gap: 0.5rem;
            margin-top: 3rem;
        }

        .page-link {
            background: var(--snow-white);
            color: var(--midnight-blue);
            border: 2px solid var(--ice-blue);
            padding: 10px 16px;
            border-radius: 15px;
            font-weight: 600;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .page-link:hover {
            background: var(--gradient-ocean);
            color: var(--snow-white);
            border-color: var(--ocean-blue);
            transform: translateY(-2px);
        }

        .page-item.active .page-link {
            background: var(--gradient-ocean);
            color: var(--snow-white);
            border-color: var(--ocean-blue);
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
        }

        .empty-state i {
            font-size: 4rem;
            color: var(--ice-blue);
            margin-bottom: 1.5rem;
        }

        .empty-state h4 {
            color: var(--midnight-blue);
            margin-bottom: 1rem;
        }

        .empty-state p {
            color: var(--ocean-blue);
            margin-bottom: 2rem;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .section {
                padding: 60px 0;
            }

            .blog-grid,
            .featured-grid {
                grid-template-columns: 1fr;
            }

            .search-section {
                padding: 2rem;
            }

            .hero-title {
                font-size: 2.5rem;
            }

            .hero-subtitle {
                font-size: 1.8rem;
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
                    <li class="nav-item"><a class="nav-link active" href="blog.php">Blog</a></li>
                    <li class="nav-item"><a class="nav-link" href="news.php">News</a></li>
                    <li class="nav-item"><a class="nav-link" href="gallery.php">Gallery</a></li>
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
        <div class="hero-particles" id="heroParticles"></div>
        <div class="hero-content" data-aos="fade-up">
            <h1 class="hero-title">Blog & Articles</h1>
            <p class="hero-subtitle">Inspiring thoughts, spiritual growth, and community stories</p>
        </div>
    </section>

    <!-- Search & Filter Section -->
    <section class="section section-heaven">
        <div class="container">
            <div class="search-section" data-aos="fade-up">
                <form method="GET" action="">
                    <div class="row g-3">
                        <div class="col-md-5">
                            <input type="text" class="form-control" name="search" placeholder="Search articles..." value="<?php echo htmlspecialchars($search); ?>">
                        </div>
                        <div class="col-md-4">
                            <select class="form-control" name="category">
                                <option value="">All Categories</option>
                                <?php if ($categories): ?>
                                    <?php $categories->data_seek(0); ?>
                                    <?php while ($cat = $categories->fetch_assoc()): ?>
                                        <option value="<?php echo htmlspecialchars($cat['category']); ?>" <?php echo $category_filter === $cat['category'] ? 'selected' : ''; ?>>
                                            <?php echo ucfirst(htmlspecialchars($cat['category'])); ?> (<?php echo $cat['count']; ?>)
                                        </option>
                                    <?php endwhile; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn-search w-100">
                                <i class="fas fa-search me-2"></i>Search
                            </button>
                        </div>
                    </div>
                </form>

                <!-- Category Badges -->
                <div class="category-badges">
                    <a href="blog.php" class="category-badge <?php echo !$category_filter ? 'active' : ''; ?>">All</a>
                    <?php if ($categories): ?>
                        <?php $categories->data_seek(0); ?>
                        <?php while ($cat = $categories->fetch_assoc()): ?>
                            <a href="blog.php?category=<?php echo urlencode($cat['category']); ?>" 
                               class="category-badge <?php echo $category_filter === $cat['category'] ? 'active' : ''; ?>">
                                <?php echo ucfirst(htmlspecialchars($cat['category'])); ?>
                            </a>
                        <?php endwhile; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Posts -->
    <?php if ($page === 1 && !$category_filter && !$search && $featured_posts && $featured_posts->num_rows > 0): ?>
    <section class="featured-section">
        <div class="container">
            <h2 class="section-title" data-aos="fade-up"><i class="fas fa-star text-warning"></i> Featured Articles</h2>
            <p class="section-subtitle" data-aos="fade-up">Handpicked content to inspire your faith journey</p>
            
            <div class="featured-grid">
                <?php while ($post = $featured_posts->fetch_assoc()): ?>
                    <div class="featured-card" data-aos="fade-up" data-aos-delay="200">
                        <span class="blog-category"><?php echo ucfirst(htmlspecialchars($post['category'] ?? 'General')); ?></span>
                        <h3 class="blog-title"><?php echo htmlspecialchars($post['title']); ?></h3>
                        <p class="blog-excerpt"><?php echo htmlspecialchars($post['excerpt'] ?: substr($post['content'], 0, 150) . '...'); ?></p>
                        <div class="blog-meta">
                            <div class="blog-author">
                                <div class="author-avatar">
                                    <?php echo strtoupper(substr($post['first_name'] ?? 'A', 0) . substr($post['last_name'] ?? 'M', 0)); ?>
                                </div>
                                <span class="author-name">
                                    <?php echo htmlspecialchars(($post['first_name'] ?? 'Church') . ' ' . ($post['last_name'] ?? 'Staff')); ?>
                                </span>
                            </div>
                            <span class="blog-date">
                                <i class="fas fa-calendar me-1"></i>
                                <?php echo date('M j, Y', strtotime($post['published_at'] ?? $post['created_at'])); ?>
                            </span>
                        </div>
                        <a href="blog_post.php?id=<?php echo $post['id']; ?>" class="btn-read-more">
                            Read More <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- Blog Grid -->
    <section class="section section-light">
        <div class="container">
            <h2 class="section-title" data-aos="fade-up"><i class="fas fa-blog"></i> Latest Articles</h2>
            <p class="section-subtitle" data-aos="fade-up">Fresh perspectives on faith, life, and community</p>

            <div class="blog-grid">
                <?php if ($blog_result && $blog_result->num_rows > 0): ?>
                    <?php while ($post = $blog_result->fetch_assoc()): ?>
                        <article class="blog-card" data-aos="fade-up" data-aos-delay="200">
                            <div class="blog-image">
                                <?php if ($post['featured_image_url']): ?>
                                    <img src="<?php echo htmlspecialchars($post['featured_image_url']); ?>" alt="<?php echo htmlspecialchars($post['title']); ?>">
                                <?php else: ?>
                                    <img src="https://images.unsplash.com/photo-1519451241324-20b4ea2c4220?ixlib=rb-4.0.3&w=400" alt="Blog Image">
                                <?php endif; ?>
                                <span class="blog-category"><?php echo ucfirst(htmlspecialchars($post['category'] ?? 'General')); ?></span>
                            </div>
                            <div class="blog-content">
                                <h3 class="blog-title"><?php echo htmlspecialchars($post['title']); ?></h3>
                                <p class="blog-excerpt"><?php echo htmlspecialchars($post['excerpt'] ?: substr(strip_tags($post['content']), 0, 120) . '...'); ?></p>
                                <div class="blog-meta">
                                    <div class="blog-author">
                                        <div class="author-avatar">
                                            <?php echo strtoupper(substr($post['first_name'] ?? 'C', 0) . substr($post['last_name'] ?? 'S', 0)); ?>
                                        </div>
                                        <div>
                                            <span class="author-name">
                                                <?php echo htmlspecialchars(($post['first_name'] ?? 'Church') . ' ' . ($post['last_name'] ?? 'Staff')); ?>
                                            </span>
                                        </div>
                                    </div>
                                    <span class="blog-date">
                                        <?php echo date('M j, Y', strtotime($post['published_at'] ?? $post['created_at'])); ?>
                                    </span>
                                    <span class="blog-views">
                                        <i class="fas fa-eye me-1"></i><?php echo $post['views_count'] ?? 0; ?>
                                    </span>
                                </div>
                                <a href="blog_post.php?id=<?php echo $post['id']; ?>" class="btn-read-more">
                                    Read More <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        </article>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="col-12">
                        <div class="empty-state" data-aos="fade-up">
                            <i class="fas fa-blog"></i>
                            <h4>No articles found</h4>
                            <p>Try adjusting your search or filter criteria.</p>
                            <a href="blog.php" class="btn-read-more">View All Articles</a>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Pagination -->
            <?php if ($total_pages > 1): ?>
                <nav>
                    <ul class="pagination">
                        <?php if ($page > 1): ?>
                            <li class="page-item">
                                <a class="page-link" href="?page=<?php echo $page - 1; ?>&category=<?php echo urlencode($category_filter); ?>&search=<?php echo urlencode($search); ?>">
                                    <i class="fas fa-chevron-left"></i> Previous
                                </a>
                            </li>
                        <?php endif; ?>

                        <?php for ($i = max(1, $page - 2); $i <= min($total_pages, $page + 2); $i++): ?>
                            <li class="page-item <?php echo $i === $page ? 'active' : ''; ?>">
                                <a class="page-link" href="?page=<?php echo $i; ?>&category=<?php echo urlencode($category_filter); ?>&search=<?php echo urlencode($search); ?>">
                                    <?php echo $i; ?>
                                </a>
                            </li>
                        <?php endfor; ?>

                        <?php if ($page < $total_pages): ?>
                            <li class="page-item">
                                <a class="page-link" href="?page=<?php echo $page + 1; ?>&category=<?php echo urlencode($category_filter); ?>&search=<?php echo urlencode($search); ?>">
                                    Next <i class="fas fa-chevron-right"></i>
                                </a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </nav>
            <?php endif; ?>
        </div>
    </section>

    <!-- Ultimate Footer -->
    <?php require_once 'components/ultimate_footer_clean.php'; ?>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap/5.3.0/dist/js/bootstrap.bundle.min.js"></script>
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

        // Create particles
        function createParticles() {
            const container = document.getElementById('heroParticles');
            for (let i = 0; i < 15; i++) {
                const particle = document.createElement('div');
                particle.className = 'particle';
                particle.style.left = Math.random() * 100 + '%';
                particle.style.animationDelay = Math.random() * 20 + 's';
                particle.style.animationDuration = (15 + Math.random() * 10) + 's';
                container.appendChild(particle);
            }
        }
        createParticles();

        // Navbar scroll effect
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar');
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });
    </script>
</body>
</html>