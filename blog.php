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
}
$count_stmt->execute();
$total_result = $count_stmt->get_result();
$total_posts = $total_result->fetch_assoc()['total'];
$total_pages = ceil($total_posts / $per_page);

// Get categories
$categories = $db->query("SELECT category, COUNT(*) as count FROM blog_posts WHERE status = 'published' AND category IS NOT NULL AND category != '' GROUP BY category ORDER BY count DESC");

// Get featured posts
$featured_posts = $db->query("SELECT bp.*, u.first_name, u.last_name FROM blog_posts bp LEFT JOIN users u ON bp.author_id = u.id WHERE bp.status = 'published' AND bp.is_featured = 1 ORDER BY bp.published_at DESC LIMIT 3");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog - Salem Dominion Ministries</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .blog-hero {
            background: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)), url('https://images.unsplash.com/photo-1519451241324-20b4ea2c4220?ixlib=rb-4.0.3');
            background-size: cover;
            background-position: center;
            color: white;
            padding: 80px 0;
        }
        .navbar-brand {
            font-weight: bold;
            font-size: 1.5rem;
        }
        .blog-card {
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            border: none;
            border-radius: 15px;
            transition: transform 0.3s ease;
            overflow: hidden;
        }
        .blog-card:hover {
            transform: translateY(-5px);
        }
        .blog-card img {
            height: 200px;
            object-fit: cover;
        }
        .featured-blog {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 15px;
        }
        .category-badge {
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .category-badge:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        .pagination .page-link {
            color: #007bff;
            border-color: #dee2e6;
        }
        .pagination .page-item.active .page-link {
            background-color: #007bff;
            border-color: #007bff;
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
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="about.php">About</a></li>
                    <li class="nav-item"><a class="nav-link" href="ministries.php">Ministries</a></li>
                    <li class="nav-item"><a class="nav-link" href="events.php">Events</a></li>
                    <li class="nav-item"><a class="nav-link" href="sermons.php">Sermons</a></li>
                    <li class="nav-item"><a class="nav-link" href="news.php">News</a></li>
                    <li class="nav-item"><a class="nav-link" href="gallery.php">Gallery</a></li>
                    <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
                </ul>
                <ul class="navbar-nav">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li class="nav-item"><a class="nav-link" href="dashboard.php">Dashboard</a></li>
                        <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
                    <?php else: ?>
                        <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
                        <li class="nav-item"><a class="nav-link" href="register.php">Register</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="blog-hero">
        <div class="container text-center">
            <h1 class="display-4 fw-bold mb-4">Blog & Articles</h1>
            <p class="lead mb-4">Inspiring thoughts, spiritual growth, and community stories from our church family.</p>
        </div>
    </section>

    <!-- Search and Filter -->
    <section class="py-4 bg-light">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <form method="GET" action="" class="mb-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <input type="text" class="form-control" name="search" placeholder="Search articles..."
                                       value="<?php echo htmlspecialchars($search); ?>">
                            </div>
                            <div class="col-md-4">
                                <select class="form-control" name="category">
                                    <option value="">All Categories</option>
                                    <?php
                                    $categories->data_seek(0);
                                    while ($cat = $categories->fetch_assoc()):
                                    ?>
                                    <option value="<?php echo htmlspecialchars($cat['category']); ?>" <?php echo $category_filter === $cat['category'] ? 'selected' : ''; ?>>
                                        <?php echo ucfirst(htmlspecialchars($cat['category'])); ?> (<?php echo $cat['count']; ?>)
                                    </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- Category Filter -->
                    <div class="d-flex flex-wrap gap-2 justify-content-center">
                        <a href="blog.php" class="badge bg-primary category-badge px-3 py-2 text-decoration-none">All</a>
                        <?php
                        $categories->data_seek(0);
                        while ($cat = $categories->fetch_assoc()):
                        ?>
                        <a href="blog.php?category=<?php echo urlencode($cat['category']); ?>"
                           class="badge bg-secondary category-badge px-3 py-2 text-decoration-none <?php echo $category_filter === $cat['category'] ? 'bg-primary' : ''; ?>">
                            <?php echo ucfirst(htmlspecialchars($cat['category'])); ?>
                        </a>
                        <?php endwhile; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Posts -->
    <?php if ($page === 1 && !$category_filter && !$search && $featured_posts->num_rows > 0): ?>
    <section class="py-5">
        <div class="container">
            <h2 class="text-center mb-5"><i class="fas fa-star text-warning"></i> Featured Articles</h2>
            <div class="row g-4">
                <?php while ($post = $featured_posts->fetch_assoc()): ?>
                    <div class="col-lg-4">
                        <div class="card blog-card featured-blog h-100">
                            <div class="card-body d-flex flex-column p-4">
                                <div class="mb-3">
                                    <span class="badge bg-light text-dark mb-2"><?php echo ucfirst(htmlspecialchars($post['category'] ?? 'General')); ?></span>
                                </div>
                                <h4 class="card-title"><?php echo htmlspecialchars($post['title']); ?></h4>
                                <p class="card-text flex-grow-1"><?php echo htmlspecialchars($post['excerpt'] ?: substr($post['content'], 0, 150) . '...'); ?></p>
                                <div class="mt-auto">
                                    <small class="text-light opacity-75 d-block mb-2">
                                        <i class="fas fa-user"></i> <?php echo htmlspecialchars(($post['first_name'] ?? '') . ' ' . ($post['last_name'] ?? '') ?: 'Church Staff'); ?> &bull;
                                        <i class="fas fa-calendar"></i> <?php echo date('M j, Y', strtotime($post['published_at'] ?? $post['created_at'])); ?>
                                    </small>
                                    <a href="blog_post.php?id=<?php echo $post['id']; ?>" class="btn btn-light">Read More</a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- Blog Grid -->
    <section class="py-5 bg-light">
        <div class="container">
            <h2 class="text-center mb-5"><i class="fas fa-blog text-primary"></i> Latest Articles</h2>

            <div class="row g-4">
                <?php if ($blog_result->num_rows > 0): ?>
                    <?php while ($post = $blog_result->fetch_assoc()): ?>
                        <div class="col-lg-6 col-xl-4">
                            <div class="card blog-card h-100">
                                <?php if ($post['featured_image_url']): ?>
                                    <img src="<?php echo htmlspecialchars($post['featured_image_url']); ?>" class="card-img-top" alt="Blog Image">
                                <?php else: ?>
                                    <img src="https://images.unsplash.com/photo-1519451241324-20b4ea2c4220?ixlib=rb-4.0.3&w=400" class="card-img-top" alt="Blog Image" style="height: 200px; object-fit: cover;">
                                <?php endif; ?>
                                <div class="card-body d-flex flex-column">
                                    <div class="mb-2">
                                        <span class="badge bg-primary"><?php echo ucfirst(htmlspecialchars($post['category'] ?? 'General')); ?></span>
                                    </div>
                                    <h5 class="card-title"><?php echo htmlspecialchars($post['title']); ?></h5>
                                    <p class="card-text flex-grow-1"><?php echo htmlspecialchars($post['excerpt'] ?: substr(strip_tags($post['content']), 0, 120) . '...'); ?></p>
                                    <div class="mt-auto">
                                        <small class="text-muted d-block mb-2">
                                            <i class="fas fa-user"></i> <?php echo htmlspecialchars(($post['first_name'] ?? '') . ' ' . ($post['last_name'] ?? '') ?: 'Church Staff'); ?> &bull;
                                            <i class="fas fa-calendar"></i> <?php echo date('M j, Y', strtotime($post['published_at'] ?? $post['created_at'])); ?> &bull;
                                            <i class="fas fa-eye"></i> <?php echo $post['views_count']; ?> views
                                        </small>
                                        <a href="blog_post.php?id=<?php echo $post['id']; ?>" class="btn btn-primary btn-sm">Read More</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="col-12 text-center py-5">
                        <i class="fas fa-blog fa-3x text-muted mb-3"></i>
                        <h4 class="text-muted">No articles found</h4>
                        <p class="text-muted">Try adjusting your search or filter criteria.</p>
                        <a href="blog.php" class="btn btn-primary">View All Articles</a>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Pagination -->
            <?php if ($total_pages > 1): ?>
                <div class="d-flex justify-content-center mt-5">
                    <nav>
                        <ul class="pagination">
                            <?php if ($page > 1): ?>
                                <li class="page-item">
                                    <a class="page-link" href="?page=<?php echo $page - 1; ?>&category=<?php echo urlencode($category_filter); ?>&search=<?php echo urlencode($search); ?>">Previous</a>
                                </li>
                            <?php endif; ?>

                            <?php for ($i = max(1, $page - 2); $i <= min($total_pages, $page + 2); $i++): ?>
                                <li class="page-item <?php echo $i === $page ? 'active' : ''; ?>">
                                    <a class="page-link" href="?page=<?php echo $i; ?>&category=<?php echo urlencode($category_filter); ?>&search=<?php echo urlencode($search); ?>"><?php echo $i; ?></a>
                                </li>
                            <?php endfor; ?>

                            <?php if ($page < $total_pages): ?>
                                <li class="page-item">
                                    <a class="page-link" href="?page=<?php echo $page + 1; ?>&category=<?php echo urlencode($category_filter); ?>&search=<?php echo urlencode($search); ?>">Next</a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </nav>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-light py-4">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h5>Salem Dominion Ministries</h5>
                    <p>Serving our community with faith, hope, and love.</p>
                </div>
                <div class="col-md-4">
                    <h5>Quick Links</h5>
                    <ul class="list-unstyled">
                        <li><a href="index.php" class="text-light">Home</a></li>
                        <li><a href="about.php" class="text-light">About Us</a></li>
                        <li><a href="donate.php" class="text-light">Donate</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h5>Contact Info</h5>
                    <p><i class="fas fa-envelope"></i> visit@salemdominionministries.com</p>
                    <p><i class="fas fa-phone"></i> Contact us for service times</p>
                </div>
            </div>
            <hr>
            <p class="text-center mb-0">&copy; 2026 Salem Dominion Ministries. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>