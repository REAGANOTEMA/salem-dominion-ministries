&lt;?php
session_start();
require_once 'db.php';

// Get recent news
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$per_page = 9;
$offset = ($page - 1) * $per_page;

$category_filter = isset($_GET['category']) ? $_GET['category'] : '';
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

$query = "SELECT n.*, u.username FROM news n LEFT JOIN users u ON n.author_id = u.id WHERE 1=1";
$count_query = "SELECT COUNT(*) as total FROM news n WHERE 1=1";

$params = [];
$types = '';

if ($category_filter) {
    $query .= " AND n.category = ?";
    $count_query .= " AND n.category = ?";
    $params[] = $category_filter;
    $types .= 's';
}

if ($search) {
    $query .= " AND (n.title LIKE ? OR n.content LIKE ? OR n.excerpt LIKE ?)";
    $count_query .= " AND (n.title LIKE ? OR n.content LIKE ? OR n.excerpt LIKE ?)";
    $search_param = "%$search%";
    $params = array_merge($params, [$search_param, $search_param, $search_param]);
    $types .= 'sss';
}

$query .= " ORDER BY n.created_at DESC LIMIT ? OFFSET ?";
$params[] = $per_page;
$params[] = $offset;
$types .= 'ii';

$stmt = $db-&gt;prepare($query);
if (!empty($params)) {
    $stmt-&gt;bind_param($types, ...$params);
}
$stmt-&gt;execute();
$news_result = $stmt-&gt;get_result();

$count_stmt = $db-&gt;prepare($count_query);
if (!empty($params) && strlen($types) &gt; 2) {
    $count_params = array_slice($params, 0, -2); // Remove LIMIT and OFFSET
    $count_types = substr($types, 0, -2);
    $count_stmt-&gt;bind_param($count_types, ...$count_params);
}
$count_stmt-&gt;execute();
$total_result = $count_stmt-&gt;get_result();
$total_news = $total_result-&gt;fetch_assoc()['total'];
$total_pages = ceil($total_news / $per_page);

// Get news categories
$categories = $db-&gt;query("SELECT category, COUNT(*) as count FROM news GROUP BY category ORDER BY category");

// Get featured news (latest 3)
$featured_news = $db-&gt;query("SELECT n.*, u.username FROM news n LEFT JOIN users u ON n.author_id = u.id ORDER BY n.created_at DESC LIMIT 3");
?&gt;
&lt;!DOCTYPE html&gt;
&lt;html lang="en"&gt;
&lt;head&gt;
    &lt;meta charset="UTF-8"&gt;
    &lt;meta name="viewport" content="width=device-width, initial-scale=1.0"&gt;
    &lt;title&gt;News &amp; Announcements - Salem Dominion Ministries&lt;/title&gt;
    &lt;link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"&gt;
    &lt;link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet"&gt;
    &lt;style&gt;
        .news-hero {
            background: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)), url('https://images.unsplash.com/photo-1504711434969-e33886168f5c?ixlib=rb-4.0.3');
            background-size: cover;
            background-position: center;
            color: white;
            padding: 80px 0;
        }
        .navbar-brand {
            font-weight: bold;
            font-size: 1.5rem;
        }
        .news-card {
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            border: none;
            border-radius: 15px;
            transition: transform 0.3s ease;
            overflow: hidden;
        }
        .news-card:hover {
            transform: translateY(-5px);
        }
        .featured-news {
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
    &lt;/style&gt;
&lt;/head&gt;
&lt;body&gt;
    &lt;!-- Navigation --&gt;
    &lt;nav class="navbar navbar-expand-lg navbar-dark bg-dark"&gt;
        &lt;div class="container"&gt;
            &lt;a class="navbar-brand" href="index.php"&gt;
                &lt;i class="fas fa-church"&gt;&lt;/i&gt; Salem Dominion Ministries
            &lt;/a&gt;
            &lt;button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"&gt;
                &lt;span class="navbar-toggler-icon"&gt;&lt;/span&gt;
            &lt;/button&gt;
            &lt;div class="collapse navbar-collapse" id="navbarNav"&gt;
                &lt;ul class="navbar-nav me-auto"&gt;
                    &lt;li class="nav-item"&gt;&lt;a class="nav-link" href="index.php"&gt;Home&lt;/a&gt;&lt;/li&gt;
                    &lt;li class="nav-item"&gt;&lt;a class="nav-link" href="about.php"&gt;About&lt;/a&gt;&lt;/li&gt;
                    &lt;li class="nav-item"&gt;&lt;a class="nav-link" href="ministries.php"&gt;Ministries&lt;/a&gt;&lt;/li&gt;
                    &lt;li class="nav-item"&gt;&lt;a class="nav-link" href="events.php"&gt;Events&lt;/a&gt;&lt;/li&gt;
                    &lt;li class="nav-item"&gt;&lt;a class="nav-link" href="sermons.php"&gt;Sermons&lt;/a&gt;&lt;/li&gt;
                    &lt;li class="nav-item"&gt;&lt;a class="nav-link active" href="news.php"&gt;News&lt;/a&gt;&lt;/li&gt;
                    &lt;li class="nav-item"&gt;&lt;a class="nav-link" href="gallery.php"&gt;Gallery&lt;/a&gt;&lt;/li&gt;
                    &lt;li class="nav-item"&gt;&lt;a class="nav-link" href="contact.php"&gt;Contact&lt;/a&gt;&lt;/li&gt;
                &lt;/ul&gt;
                &lt;ul class="navbar-nav"&gt;
                    &lt;?php if (isset($_SESSION['user_id'])): ?&gt;
                        &lt;li class="nav-item"&gt;&lt;a class="nav-link" href="dashboard.php"&gt;Dashboard&lt;/a&gt;&lt;/li&gt;
                        &lt;li class="nav-item"&gt;&lt;a class="nav-link" href="logout.php"&gt;Logout&lt;/a&gt;&lt;/li&gt;
                    &lt;?php else: ?&gt;
                        &lt;li class="nav-item"&gt;&lt;a class="nav-link" href="login.php"&gt;Login&lt;/a&gt;&lt;/li&gt;
                        &lt;li class="nav-item"&gt;&lt;a class="nav-link" href="register.php"&gt;Register&lt;/a&gt;&lt;/li&gt;
                    &lt;?php endif; ?&gt;
                &lt;/ul&gt;
            &lt;/div&gt;
        &lt;/div&gt;
    &lt;/nav&gt;

    &lt;!-- Hero Section --&gt;
    &lt;section class="news-hero"&gt;
        &lt;div class="container text-center"&gt;
            &lt;h1 class="display-4 fw-bold mb-4"&gt;News &amp; Announcements&lt;/h1&gt;
            &lt;p class="lead mb-4"&gt;Stay connected with the latest updates, announcements, and happenings in our church community.&lt;/p&gt;
        &lt;/div&gt;
    &lt;/section&gt;

    &lt;!-- Search and Filter --&gt;
    &lt;section class="py-4 bg-light"&gt;
        &lt;div class="container"&gt;
            &lt;div class="row"&gt;
                &lt;div class="col-lg-8 mx-auto"&gt;
                    &lt;form method="GET" action="" class="mb-4"&gt;
                        &lt;div class="row g-3"&gt;
                            &lt;div class="col-md-6"&gt;
                                &lt;input type="text" class="form-control" name="search" placeholder="Search news..."
                                       value="&lt;?php echo htmlspecialchars($search); ?&gt;"&gt;
                            &lt;/div&gt;
                            &lt;div class="col-md-4"&gt;
                                &lt;select class="form-control" name="category"&gt;
                                    &lt;option value=""&gt;All Categories&lt;/option&gt;
                                    &lt;?php
                                    $categories-&gt;data_seek(0);
                                    while ($cat = $categories-&gt;fetch_assoc()):
                                    ?&gt;
                                    &lt;option value="&lt;?php echo htmlspecialchars($cat['category']); ?&gt;" &lt;?php echo $category_filter === $cat['category'] ? 'selected' : ''; ?&gt;&gt;
                                        &lt;?php echo ucfirst(htmlspecialchars($cat['category'])); ?&gt; (&lt;?php echo $cat['count']; ?&gt;)
                                    &lt;/option&gt;
                                    &lt;?php endwhile; ?&gt;
                                &lt;/select&gt;
                            &lt;/div&gt;
                            &lt;div class="col-md-2"&gt;
                                &lt;button type="submit" class="btn btn-primary w-100"&gt;
                                    &lt;i class="fas fa-search"&gt;&lt;/i&gt;
                                &lt;/button&gt;
                            &lt;/div&gt;
                        &lt;/div&gt;
                    &lt;/form&gt;

                    &lt;!-- Category Filter --&gt;
                    &lt;div class="d-flex flex-wrap gap-2 justify-content-center"&gt;
                        &lt;a href="news.php" class="badge bg-primary category-badge px-3 py-2 text-decoration-none"&gt;All&lt;/a&gt;
                        &lt;?php
                        $categories-&gt;data_seek(0);
                        while ($cat = $categories-&gt;fetch_assoc()):
                        ?&gt;
                        &lt;a href="news.php?category=&lt;?php echo urlencode($cat['category']); ?&gt;"
                           class="badge bg-secondary category-badge px-3 py-2 text-decoration-none &lt;?php echo $category_filter === $cat['category'] ? 'bg-primary' : ''; ?&gt;"&gt;
                            &lt;?php echo ucfirst(htmlspecialchars($cat['category'])); ?&gt;
                        &lt;/a&gt;
                        &lt;?php endwhile; ?&gt;
                    &lt;/div&gt;
                &lt;/div&gt;
            &lt;/div&gt;
        &lt;/div&gt;
    &lt;/section&gt;

    &lt;!-- Featured News --&gt;
    &lt;?php if ($page === 1 && !$category_filter && !$search): ?&gt;
    &lt;section class="py-5"&gt;
        &lt;div class="container"&gt;
            &lt;h2 class="text-center mb-5"&gt;&lt;i class="fas fa-star text-warning"&gt;&lt;/i&gt; Featured News&lt;/h2&gt;
            &lt;div class="row g-4"&gt;
                &lt;?php while ($news = $featured_news-&gt;fetch_assoc()): ?&gt;
                    &lt;div class="col-lg-4"&gt;
                        &lt;div class="card news-card featured-news h-100"&gt;
                            &lt;div class="card-body d-flex flex-column p-4"&gt;
                                &lt;div class="mb-3"&gt;
                                    &lt;span class="badge bg-light text-dark mb-2"&gt;&lt;?php echo ucfirst(htmlspecialchars($news['category'])); ?&gt;&lt;/span&gt;
                                &lt;/div&gt;
                                &lt;h4 class="card-title"&gt;&lt;?php echo htmlspecialchars($news['title']); ?&gt;&lt;/h4&gt;
                                &lt;p class="card-text flex-grow-1"&gt;&lt;?php echo htmlspecialchars($news['excerpt'] ?: substr($news['content'], 0, 150) . '...'); ?&gt;&lt;/p&gt;
                                &lt;div class="mt-auto"&gt;
                                    &lt;small class="text-light opacity-75 d-block mb-2"&gt;
                                        &lt;i class="fas fa-user"&gt;&lt;/i&gt; &lt;?php echo htmlspecialchars($news['username'] ?: 'Church Staff'); ?&gt; &amp;bull;
                                        &lt;i class="fas fa-calendar"&gt;&lt;/i&gt; &lt;?php echo date('M j, Y', strtotime($news['created_at'])); ?&gt;
                                    &lt;/small&gt;
                                    &lt;a href="news_article.php?id=&lt;?php echo $news['id']; ?&gt;" class="btn btn-light"&gt;Read More&lt;/a&gt;
                                &lt;/div&gt;
                            &lt;/div&gt;
                        &lt;/div&gt;
                    &lt;/div&gt;
                &lt;?php endwhile; ?&gt;
            &lt;/div&gt;
        &lt;/div&gt;
    &lt;/section&gt;
    &lt;?php endif; ?&gt;

    &lt;!-- News Grid --&gt;
    &lt;section class="py-5 bg-light"&gt;
        &lt;div class="container"&gt;
            &lt;h2 class="text-center mb-5"&gt;&lt;i class="fas fa-newspaper text-primary"&gt;&lt;/i&gt; Latest News&lt;/h2&gt;

            &lt;div class="row g-4"&gt;
                &lt;?php if ($news_result-&gt;num_rows &gt; 0): ?&gt;
                    &lt;?php while ($news = $news_result-&gt;fetch_assoc()): ?&gt;
                        &lt;div class="col-lg-6 col-xl-4"&gt;
                            &lt;div class="card news-card h-100"&gt;
                                &lt;img src="https://images.unsplash.com/photo-1504711434969-e33886168f5c?ixlib=rb-4.0.3&amp;w=400" class="card-img-top" alt="News Image" style="height: 200px; object-fit: cover;"&gt;
                                &lt;div class="card-body d-flex flex-column"&gt;
                                    &lt;div class="mb-2"&gt;
                                        &lt;span class="badge bg-primary"&gt;&lt;?php echo ucfirst(htmlspecialchars($news['category'])); ?&gt;&lt;/span&gt;
                                    &lt;/div&gt;
                                    &lt;h5 class="card-title"&gt;&lt;?php echo htmlspecialchars($news['title']); ?&gt;&lt;/h5&gt;
                                    &lt;p class="card-text flex-grow-1"&gt;&lt;?php echo htmlspecialchars($news['excerpt'] ?: substr(strip_tags($news['content']), 0, 120) . '...'); ?&gt;&lt;/p&gt;
                                    &lt;div class="mt-auto"&gt;
                                        &lt;small class="text-muted d-block mb-2"&gt;
                                            &lt;i class="fas fa-user"&gt;&lt;/i&gt; &lt;?php echo htmlspecialchars($news['username'] ?: 'Church Staff'); ?&gt; &amp;bull;
                                            &lt;i class="fas fa-calendar"&gt;&lt;/i&gt; &lt;?php echo date('M j, Y', strtotime($news['created_at'])); ?&gt;
                                        &lt;/small&gt;
                                        &lt;a href="news_article.php?id=&lt;?php echo $news['id']; ?&gt;" class="btn btn-primary btn-sm"&gt;Read More&lt;/a&gt;
                                    &lt;/div&gt;
                                &lt;/div&gt;
                            &lt;/div&gt;
                        &lt;/div&gt;
                    &lt;?php endwhile; ?&gt;
                &lt;?php else: ?&gt;
                    &lt;div class="col-12 text-center py-5"&gt;
                        &lt;i class="fas fa-newspaper fa-3x text-muted mb-3"&gt;&lt;/i&gt;
                        &lt;h4 class="text-muted"&gt;No news found&lt;/h4&gt;
                        &lt;p class="text-muted"&gt;Try adjusting your search or filter criteria.&lt;/p&gt;
                        &lt;a href="news.php" class="btn btn-primary"&gt;View All News&lt;/a&gt;
                    &lt;/div&gt;
                &lt;?php endif; ?&gt;
            &lt;/div&gt;

            &lt;!-- Pagination --&gt;
            &lt;?php if ($total_pages &gt; 1): ?&gt;
                &lt;div class="d-flex justify-content-center mt-5"&gt;
                    &lt;nav&gt;
                        &lt;ul class="pagination"&gt;
                            &lt;?php if ($page &gt; 1): ?&gt;
                                &lt;li class="page-item"&gt;
                                    &lt;a class="page-link" href="?page=&lt;?php echo $page - 1; ?&gt;&amp;category=&lt;?php echo urlencode($category_filter); ?&gt;&amp;search=&lt;?php echo urlencode($search); ?&gt;"&gt;Previous&lt;/a&gt;
                                &lt;/li&gt;
                            &lt;?php endif; ?&gt;

                            &lt;?php for ($i = max(1, $page - 2); $i &lt;= min($total_pages, $page + 2); $i++): ?&gt;
                                &lt;li class="page-item &lt;?php echo $i === $page ? 'active' : ''; ?&gt;"&gt;
                                    &lt;a class="page-link" href="?page=&lt;?php echo $i; ?&gt;&amp;category=&lt;?php echo urlencode($category_filter); ?&gt;&amp;search=&lt;?php echo urlencode($search); ?&gt;"&gt;&lt;?php echo $i; ?&gt;&lt;/a&gt;
                                &lt;/li&gt;
                            &lt;?php endfor; ?&gt;

                            &lt;?php if ($page &lt; $total_pages): ?&gt;
                                &lt;li class="page-item"&gt;
                                    &lt;a class="page-link" href="?page=&lt;?php echo $page + 1; ?&gt;&amp;category=&lt;?php echo urlencode($category_filter); ?&gt;&amp;search=&lt;?php echo urlencode($search); ?&gt;"&gt;Next&lt;/a&gt;
                                &lt;/li&gt;
                            &lt;?php endif; ?&gt;
                        &lt;/ul&gt;
                    &lt;/nav&gt;
                &lt;/div&gt;
            &lt;?php endif; ?&gt;
        &lt;/div&gt;
    &lt;/section&gt;

    &lt;!-- Footer --&gt;
    &lt;footer class="bg-dark text-light py-4"&gt;
        &lt;div class="container"&gt;
            &lt;div class="row"&gt;
                &lt;div class="col-md-4"&gt;
                    &lt;h5&gt;Salem Dominion Ministries&lt;/h5&gt;
                    &lt;p&gt;Serving our community with faith, hope, and love.&lt;/p&gt;
                &lt;/div&gt;
                &lt;div class="col-md-4"&gt;
                    &lt;h5&gt;Quick Links&lt;/h5&gt;
                    &lt;ul class="list-unstyled"&gt;
                        &lt;li&gt;&lt;a href="index.php" class="text-light"&gt;Home&lt;/a&gt;&lt;/li&gt;
                        &lt;li&gt;&lt;a href="about.php" class="text-light"&gt;About Us&lt;/a&gt;&lt;/li&gt;
                        &lt;li&gt;&lt;a href="donate.php" class="text-light"&gt;Donate&lt;/a&gt;&lt;/li&gt;
                    &lt;/ul&gt;
                &lt;/div&gt;
                &lt;div class="col-md-4"&gt;
                    &lt;h5&gt;Contact Info&lt;/h5&gt;
                    &lt;p&gt;&lt;i class="fas fa-envelope"&gt;&lt;/i&gt; visit@salemdominionministries.com&lt;/p&gt;
                    &lt;p&gt;&lt;i class="fas fa-phone"&gt;&lt;/i&gt; Contact us for service times&lt;/p&gt;
                &lt;/div&gt;
            &lt;/div&gt;
            &lt;hr&gt;
            &lt;p class="text-center mb-0"&gt;&amp;copy; 2026 Salem Dominion Ministries. All rights reserved.&lt;/p&gt;
        &lt;/div&gt;
    &lt;/footer&gt;

    &lt;script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"&gt;&lt;/script&gt;
&lt;/body&gt;
&lt;/html&gt;