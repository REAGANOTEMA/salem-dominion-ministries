<?php
// Admin Gallery Management
// Start output buffering to catch any early output
ob_start();

require_once 'config.php';
require_once 'db.php';

// Check if user is logged in and is admin/pastor
if (!isset($_SESSION['user_id'])) {
    // Clean buffer and redirect
    ob_end_clean();
    header('Location: login_perfect.php');
    exit;
}

$user = $db->selectOne("SELECT role FROM users WHERE id = ?", [$_SESSION['user_id']]);
if (!$user || !in_array($user['role'], ['admin', 'pastor'])) {
    // Clean buffer and redirect
    ob_end_clean();
    header('Location: dashboard.php');
    exit;
}

// Handle gallery operations
$message = '';
$message_type = '';

// Handle status update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $image_id = intval($_POST['image_id']);
    $new_status = $_POST['status'];
    
    try {
        $result = $db->update("UPDATE gallery SET status = ? WHERE id = ?", [$new_status, $image_id]);
        if ($result > 0) {
            $message = 'Image status updated successfully!';
            $message_type = 'success';
        }
    } catch (Exception $e) {
        $message = 'Failed to update image status: ' . $e->getMessage();
        $message_type = 'error';
    }
}

// Handle featured toggle
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['toggle_featured'])) {
    $image_id = intval($_POST['image_id']);
    $is_featured = isset($_POST['is_featured']) ? 1 : 0;
    
    try {
        $result = $db->update("UPDATE gallery SET is_featured = ? WHERE id = ?", [$is_featured, $image_id]);
        if ($result > 0) {
            $message = 'Image featured status updated successfully!';
            $message_type = 'success';
        }
    } catch (Exception $e) {
        $message = 'Failed to update featured status: ' . $e->getMessage();
        $message_type = 'error';
    }
}

// Get all gallery images with pagination
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$per_page = 20;
$offset = ($page - 1) * $per_page;

$status_filter = isset($_GET['status']) ? $_GET['status'] : 'all';

// Build query
$where_clause = "1=1";
$params = [];

if ($status_filter !== 'all') {
    $where_clause = "status = ?";
    $params[] = $status_filter;
}

// Get total count
$total_result = $db->select("SELECT COUNT(*) as total FROM gallery WHERE $where_clause", $params);
$total_images = $total_result[0]['total'];
$total_pages = ceil($total_images / $per_page);

// Get images
$images = $db->select("SELECT * FROM gallery WHERE $where_clause ORDER BY created_at DESC LIMIT ? OFFSET ?", 
                      array_merge($params, [$per_page, $offset]));

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
    <title>Gallery Management - Salem Dominion Ministries</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <style>
        :root {
            --midnight-blue: #0f172a;
            --ocean-blue: #0ea5e9;
            --sky-blue: #38bdf8;
            --ice-blue: #7dd3fc;
            --snow-white: #ffffff;
            --heavenly-gold: #fbbf24;
        }

        body {
            font-family: 'Montserrat', sans-serif;
            background: #f8fafc;
            color: var(--midnight-blue);
        }

        .admin-header {
            background: linear-gradient(135deg, var(--midnight-blue) 0%, var(--ocean-blue) 100%);
            color: var(--snow-white);
            padding: 30px 0;
            margin-bottom: 30px;
        }

        .gallery-table {
            background: var(--snow-white);
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            overflow: hidden;
        }

        .gallery-table th {
            background: var(--ice-blue);
            color: var(--midnight-blue);
            font-weight: 600;
            border: none;
            padding: 15px;
        }

        .gallery-table td {
            padding: 15px;
            vertical-align: middle;
        }

        .thumbnail {
            width: 80px;
            height: 60px;
            object-fit: cover;
            border-radius: 5px;
            cursor: pointer;
            transition: transform 0.2s;
        }

        .thumbnail:hover {
            transform: scale(1.05);
        }

        .status-badge {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .status-published {
            background: #dcfce7;
            color: #16a34a;
        }

        .status-draft {
            background: #fef3c7;
            color: #d97706;
        }

        .status-archived {
            background: #f1f5f9;
            color: #64748b;
        }

        .featured-star {
            color: var(--heavenly-gold);
            font-size: 1.2rem;
        }

        .action-buttons {
            display: flex;
            gap: 5px;
            flex-wrap: wrap;
        }

        .btn-sm {
            padding: 5px 10px;
            font-size: 0.8rem;
        }

        .filter-tabs {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        .filter-tab {
            background: var(--snow-white);
            border: 2px solid var(--ice-blue);
            color: var(--midnight-blue);
            padding: 8px 16px;
            border-radius: 20px;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .filter-tab:hover, .filter-tab.active {
            background: var(--ocean-blue);
            color: var(--snow-white);
            text-decoration: none;
        }

        .stats-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: var(--snow-white);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            text-align: center;
        }

        .stat-number {
            font-size: 2rem;
            font-weight: 700;
            color: var(--ocean-blue);
        }

        .stat-label {
            color: var(--midnight-blue);
            opacity: 0.7;
            font-size: 0.9rem;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 9999;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.8);
        }

        .modal-content {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            max-width: 90%;
            max-height: 90%;
        }

        .modal-image {
            max-width: 100%;
            max-height: 80vh;
            border-radius: 10px;
        }

        .modal-close {
            position: absolute;
            top: 20px;
            right: 40px;
            color: var(--snow-white);
            font-size: 2rem;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <!-- Admin Header -->
    <div class="admin-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h1 class="mb-0"><i class="fas fa-images me-3"></i>Gallery Management</h1>
                    <p class="mb-0 opacity-75">Manage church gallery images</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <a href="admin_dashboard.php" class="btn btn-light me-2">
                        <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
                    </a>
                    <a href="gallery_enhanced.php" class="btn btn-success">
                        <i class="fas fa-eye me-2"></i>View Gallery
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <!-- Message Alert -->
        <?php if ($message): ?>
            <div class="alert alert-<?php echo $message_type === 'success' ? 'success' : 'danger'; ?> alert-dismissible fade show" role="alert">
                <?php echo htmlspecialchars($message); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- Statistics -->
        <div class="stats-cards">
            <div class="stat-card">
                <div class="stat-number"><?php echo $total_images; ?></div>
                <div class="stat-label">Total Images</div>
            </div>
            <?php
            $published_count = $db->select("SELECT COUNT(*) as count FROM gallery WHERE status = 'published'")[0]['count'];
            $draft_count = $db->select("SELECT COUNT(*) as count FROM gallery WHERE status = 'draft'")[0]['count'];
            $featured_count = $db->select("SELECT COUNT(*) as count FROM gallery WHERE is_featured = 1")[0]['count'];
            ?>
            <div class="stat-card">
                <div class="stat-number"><?php echo $published_count; ?></div>
                <div class="stat-label">Published</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo $draft_count; ?></div>
                <div class="stat-label">Drafts</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo $featured_count; ?></div>
                <div class="stat-label">Featured</div>
            </div>
        </div>

        <!-- Filter Tabs -->
        <div class="filter-tabs">
            <a href="?status=all" class="filter-tab <?php echo $status_filter === 'all' ? 'active' : ''; ?>">
                All Images
            </a>
            <a href="?status=published" class="filter-tab <?php echo $status_filter === 'published' ? 'active' : ''; ?>">
                Published
            </a>
            <a href="?status=draft" class="filter-tab <?php echo $status_filter === 'draft' ? 'active' : ''; ?>">
                Drafts
            </a>
            <a href="?status=archived" class="filter-tab <?php echo $status_filter === 'archived' ? 'active' : ''; ?>">
                Archived
            </a>
        </div>

        <!-- Gallery Table -->
        <div class="gallery-table">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Title</th>
                            <th>Category</th>
                            <th>Status</th>
                            <th>Featured</th>
                            <th>Uploaded</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($images)): ?>
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <i class="fas fa-images fa-3x text-muted mb-3"></i>
                                    <p class="text-muted mb-0">No images found</p>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($images as $image): ?>
                                <tr>
                                    <td>
                                        <img src="<?php echo htmlspecialchars($image['file_url']); ?>" 
                                             alt="<?php echo htmlspecialchars($image['title']); ?>" 
                                             class="thumbnail"
                                             onclick="openModal('<?php echo htmlspecialchars($image['file_url']); ?>')">
                                    </td>
                                    <td>
                                        <div>
                                            <strong><?php echo htmlspecialchars($image['title']); ?></strong>
                                            <?php if (!empty($image['description'])): ?>
                                                <br><small class="text-muted"><?php echo htmlspecialchars(substr($image['description'], 0, 50)) . '...'; ?></small>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary"><?php echo ucfirst($image['category'] ?? 'General'); ?></span>
                                    </td>
                                    <td>
                                        <span class="status-badge status-<?php echo $image['status']; ?>">
                                            <?php echo ucfirst($image['status']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <form method="POST" style="display: inline;">
                                            <input type="hidden" name="image_id" value="<?php echo $image['id']; ?>">
                                            <input type="hidden" name="is_featured" value="<?php echo $image['is_featured'] ? 0 : 1; ?>">
                                            <button type="submit" name="toggle_featured" class="btn btn-sm btn-link p-0">
                                                <i class="fas fa-star featured-star <?php echo $image['is_featured'] ? '' : 'opacity-25'; ?>"></i>
                                            </button>
                                        </form>
                                    </td>
                                    <td>
                                        <small><?php echo date('M j, Y', strtotime($image['created_at'])); ?></small>
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <?php if ($image['status'] === 'draft'): ?>
                                                <form method="POST" style="display: inline;">
                                                    <input type="hidden" name="image_id" value="<?php echo $image['id']; ?>">
                                                    <input type="hidden" name="status" value="published">
                                                    <button type="submit" name="update_status" class="btn btn-sm btn-success">
                                                        <i class="fas fa-check"></i> Publish
                                                    </button>
                                                </form>
                                            <?php endif; ?>
                                            
                                            <?php if ($image['status'] === 'published'): ?>
                                                <form method="POST" style="display: inline;">
                                                    <input type="hidden" name="image_id" value="<?php echo $image['id']; ?>">
                                                    <input type="hidden" name="status" value="draft">
                                                    <button type="submit" name="update_status" class="btn btn-sm btn-warning">
                                                        <i class="fas fa-edit"></i> Draft
                                                    </button>
                                                </form>
                                            <?php endif; ?>
                                            
                                            <form method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this image?')">
                                                <input type="hidden" name="image_id" value="<?php echo $image['id']; ?>">
                                                <input type="hidden" name="status" value="archived">
                                                <button type="submit" name="update_status" class="btn btn-sm btn-danger">
                                                    <i class="fas fa-trash"></i> Delete
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        <?php if ($total_pages > 1): ?>
            <nav aria-label="Gallery pagination" class="mt-4">
                <ul class="pagination justify-content-center">
                    <?php if ($page > 1): ?>
                        <li class="page-item">
                            <a class="page-link" href="?status=<?php echo $status_filter; ?>&page=<?php echo $page - 1; ?>">Previous</a>
                        </li>
                    <?php endif; ?>
                    
                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <li class="page-item <?php echo $i === $page ? 'active' : ''; ?>">
                            <a class="page-link" href="?status=<?php echo $status_filter; ?>&page=<?php echo $i; ?>"><?php echo $i; ?></a>
                        </li>
                    <?php endfor; ?>
                    
                    <?php if ($page < $total_pages): ?>
                        <li class="page-item">
                            <a class="page-link" href="?status=<?php echo $status_filter; ?>&page=<?php echo $page + 1; ?>">Next</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </nav>
        <?php endif; ?>
    </div>

    <!-- Image Modal -->
    <div id="imageModal" class="modal" onclick="closeModal()">
        <span class="modal-close" onclick="closeModal()">&times;</span>
        <div class="modal-content">
            <img src="" alt="Gallery Image" class="modal-image" id="modalImage">
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        function openModal(imageSrc) {
            document.getElementById('modalImage').src = imageSrc;
            document.getElementById('imageModal').style.display = 'block';
        }

        function closeModal() {
            document.getElementById('imageModal').style.display = 'none';
        }

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeModal();
            }
        });
    </script>
</body>
</html>
