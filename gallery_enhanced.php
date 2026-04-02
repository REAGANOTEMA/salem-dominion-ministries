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

// Clean any buffered output
ob_end_clean();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
        /* Gallery Styles */
        :root {
            --midnight-blue: #0f172a;
            --ocean-blue: #0ea5e9;
            --sky-blue: #38bdf8;
            --ice-blue: #7dd3fc;
            --snow-white: #ffffff;
            --pearl-white: #f8fafc;
            --heavenly-gold: #fbbf24;
            --divine-light: #fef3c7;
        }

        body {
            font-family: 'Montserrat', sans-serif;
            background: var(--gradient-heaven);
            color: var(--midnight-blue);
            margin: 0;
            padding: 0;
        }

        .hero-section {
            background: linear-gradient(135deg, var(--midnight-blue) 0%, var(--ocean-blue) 50%, var(--sky-blue) 100%);
            color: var(--snow-white);
            padding: 100px 0 50px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .hero-section::before {
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

        .gallery-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 50px 20px;
        }

        .gallery-filters {
            text-align: center;
            margin-bottom: 40px;
        }

        .filter-btn {
            background: var(--snow-white);
            border: 2px solid var(--ice-blue);
            color: var(--midnight-blue);
            padding: 8px 20px;
            margin: 5px;
            border-radius: 25px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .filter-btn:hover, .filter-btn.active {
            background: var(--ocean-blue);
            color: var(--snow-white);
            border-color: var(--ocean-blue);
            transform: translateY(-2px);
        }

        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 30px;
            margin-bottom: 50px;
        }

        .gallery-item {
            background: var(--snow-white);
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.1);
            transition: all 0.3s ease;
            position: relative;
        }

        .gallery-item:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(15, 23, 42, 0.2);
        }

        .gallery-item.featured {
            border: 3px solid var(--heavenly-gold);
        }

        .gallery-image {
            width: 100%;
            height: 250px;
            object-fit: cover;
            cursor: pointer;
            transition: transform 0.3s ease;
        }

        .gallery-item:hover .gallery-image {
            transform: scale(1.05);
        }

        .gallery-info {
            padding: 20px;
        }

        .gallery-title {
            font-family: 'Playfair Display', serif;
            font-size: 1.2rem;
            font-weight: 700;
            color: var(--midnight-blue);
            margin-bottom: 10px;
        }

        .gallery-description {
            color: var(--midnight-blue);
            opacity: 0.7;
            font-size: 0.9rem;
            margin-bottom: 15px;
        }

        .gallery-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.8rem;
            color: var(--midnight-blue);
            opacity: 0.5;
        }

        .gallery-category {
            background: var(--ice-blue);
            color: var(--midnight-blue);
            padding: 3px 10px;
            border-radius: 15px;
            font-size: 0.7rem;
            font-weight: 600;
        }

        .upload-section {
            background: var(--snow-white);
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 50px;
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.1);
        }

        .upload-btn {
            background: var(--gradient-ocean);
            color: var(--snow-white);
            border: none;
            padding: 12px 30px;
            border-radius: 25px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .upload-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(14, 165, 233, 0.3);
        }

        /* Lightbox */
        .lightbox {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.95);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            cursor: pointer;
        }

        .lightbox.active {
            display: flex;
        }

        .lightbox-image {
            max-width: 90%;
            max-height: 90%;
            object-fit: contain;
            border-radius: 10px;
        }

        .lightbox-close {
            position: absolute;
            top: 20px;
            right: 40px;
            color: var(--snow-white);
            font-size: 2rem;
            cursor: pointer;
            z-index: 10000;
        }

        .delete-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            background: rgba(220, 38, 38, 0.9);
            color: var(--snow-white);
            border: none;
            padding: 8px 12px;
            border-radius: 5px;
            cursor: pointer;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .gallery-item:hover .delete-btn {
            opacity: 1;
        }

        .empty-gallery {
            text-align: center;
            padding: 60px 20px;
            color: var(--midnight-blue);
            opacity: 0.6;
        }

        .empty-gallery i {
            font-size: 4rem;
            color: var(--ice-blue);
            margin-bottom: 20px;
        }

        @media (max-width: 768px) {
            .gallery-grid {
                grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
                gap: 20px;
            }
            
            .gallery-container {
                padding: 30px 15px;
            }
        }
    </style>
</head>
<body>
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <h1 class="display-4 fw-bold mb-4" data-aos="fade-up">Church Gallery</h1>
            <p class="lead mb-0" data-aos="fade-up" data-aos-delay="100">Capturing moments of faith, fellowship, and worship</p>
        </div>
    </section>

    <!-- Gallery Container -->
    <div class="gallery-container">
        <!-- Upload Section (for logged-in users) -->
        <?php if (isset($_SESSION['user_id'])): ?>
        <div class="upload-section" data-aos="fade-up">
            <h3 class="mb-4">Upload New Image</h3>
            <form id="uploadForm" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="imageTitle" class="form-label">Title</label>
                        <input type="text" class="form-control" id="imageTitle" name="title" placeholder="Enter image title">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="imageCategory" class="form-label">Category</label>
                        <select class="form-control" id="imageCategory" name="category">
                            <option value="general">General</option>
                            <option value="services">Services</option>
                            <option value="events">Events</option>
                            <option value="youth">Youth</option>
                            <option value="children">Children</option>
                            <option value="outreach">Outreach</option>
                            <option value="fellowship">Fellowship</option>
                        </select>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="imageDescription" class="form-label">Description</label>
                    <textarea class="form-control" id="imageDescription" name="description" rows="3" placeholder="Enter image description"></textarea>
                </div>
                <div class="mb-3">
                    <label for="imageFile" class="form-label">Image File</label>
                    <input type="file" class="form-control" id="imageFile" name="image" accept="image/*" required>
                    <small class="text-muted">Maximum file size: 10MB. Accepted formats: JPEG, PNG, GIF, WebP</small>
                </div>
                <div class="mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="isFeatured" name="is_featured">
                        <label class="form-check-label" for="isFeatured">
                            Feature this image
                        </label>
                    </div>
                </div>
                <button type="submit" class="upload-btn">
                    <i class="fas fa-upload me-2"></i>Upload Image
                </button>
            </form>
        </div>
        <?php endif; ?>

        <!-- Gallery Filters -->
        <div class="gallery-filters" data-aos="fade-up" data-aos-delay="200">
            <button class="filter-btn active" data-category="all">All Images</button>
            <?php foreach ($categories as $category): ?>
                <button class="filter-btn" data-category="<?php echo strtolower($category); ?>">
                    <?php echo ucfirst($category); ?>
                </button>
            <?php endforeach; ?>
        </div>

        <!-- Gallery Grid -->
        <div class="gallery-grid" id="galleryGrid">
            <?php if (empty($gallery_images)): ?>
                <div class="empty-gallery" data-aos="fade-up">
                    <i class="fas fa-images"></i>
                    <h3>No Images Yet</h3>
                    <p>Be the first to upload an image to our gallery!</p>
                </div>
            <?php else: ?>
                <?php foreach ($gallery_images as $image): ?>
                    <div class="gallery-item <?php echo $image['is_featured'] ? 'featured' : ''; ?>" 
                         data-category="<?php echo strtolower($image['category'] ?? 'general'); ?>" 
                         data-aos="fade-up" data-aos-delay="<?php echo (array_search($image, $gallery_images) * 100); ?>">
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <?php 
                            $user = $db->selectOne("SELECT role FROM users WHERE id = ?", [$_SESSION['user_id']]);
                            if ($user && ($user['role'] === 'admin' || $user['role'] === 'pastor')): 
                            ?>
                                <form method="POST" style="position: absolute; top: 10px; right: 10px; z-index: 10;">
                                    <button type="submit" name="delete_image" value="<?php echo $image['id']; ?>" 
                                            class="delete-btn" onclick="return confirm('Are you sure you want to delete this image?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            <?php endif; ?>
                        <?php endif; ?>
                        <img src="<?php echo htmlspecialchars($image['file_url']); ?>" 
                             alt="<?php echo htmlspecialchars($image['title']); ?>" 
                             class="gallery-image"
                             onclick="openLightbox('<?php echo htmlspecialchars($image['file_url']); ?>')">
                        <div class="gallery-info">
                            <h3 class="gallery-title"><?php echo htmlspecialchars($image['title']); ?></h3>
                            <?php if (!empty($image['description'])): ?>
                                <p class="gallery-description"><?php echo htmlspecialchars($image['description']); ?></p>
                            <?php endif; ?>
                            <div class="gallery-meta">
                                <span class="gallery-category"><?php echo ucfirst($image['category'] ?? 'General'); ?></span>
                                <span><?php echo date('M j, Y', strtotime($image['created_at'])); ?></span>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <!-- Lightbox -->
    <div class="lightbox" id="lightbox" onclick="closeLightbox()">
        <span class="lightbox-close" onclick="closeLightbox()">&times;</span>
        <img src="" alt="Gallery Image" class="lightbox-image" id="lightboxImage">
    </div>

    <!-- Navigation (include your existing navigation) -->
    <?php include_once 'components/navigation.php'; ?>

    <!-- Footer -->
    <?php include_once 'components/ultimate_footer_clean.php'; ?>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    
    <script>
        // Initialize AOS
        AOS.init({
            duration: 1200,
            once: true,
            offset: 100,
            easing: 'ease-in-out'
        });

        // Gallery Filter
        document.querySelectorAll('.filter-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                // Remove active class from all buttons
                document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
                // Add active class to clicked button
                this.classList.add('active');
                
                const category = this.dataset.category;
                const items = document.querySelectorAll('.gallery-item');
                
                items.forEach(item => {
                    if (category === 'all' || item.dataset.category === category) {
                        item.style.display = 'block';
                    } else {
                        item.style.display = 'none';
                    }
                });
            });
        });

        // Lightbox
        function openLightbox(imageSrc) {
            const lightbox = document.getElementById('lightbox');
            const lightboxImage = document.getElementById('lightboxImage');
            
            lightboxImage.src = imageSrc;
            lightbox.classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeLightbox() {
            const lightbox = document.getElementById('lightbox');
            lightbox.classList.remove('active');
            document.body.style.overflow = 'auto';
        }

        // Upload Form
        document.getElementById('uploadForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const submitBtn = this.querySelector('.upload-btn');
            const originalText = submitBtn.innerHTML;
            
            // Show loading state
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Uploading...';
            submitBtn.disabled = true;
            
            fetch('gallery_upload.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Show success message
                    alert('Image uploaded successfully!');
                    // Reset form
                    this.reset();
                    // Reload page to show new image
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                } else {
                    alert('Upload failed: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Upload failed. Please try again.');
            })
            .finally(() => {
                // Reset button
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            });
        });

        // Keyboard navigation for lightbox
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeLightbox();
            }
        });
    </script>
</body>
</html>
