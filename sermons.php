<?php
// Complete error suppression to prevent unwanted output
error_reporting(0);
ini_set('display_errors', 0);
ini_set('log_errors', 0);

// Buffer output to catch any accidental output
ob_start();

session_start();
require_once 'db.php';

// Get recent sermons
$recent_sermons = $db->query("SELECT s.*, u.first_name, u.last_name FROM sermons s LEFT JOIN users u ON s.created_by = u.id WHERE s.status = 'published' ORDER BY s.sermon_date DESC LIMIT 12");

// Get sermon series
$sermon_series = $db->query("SELECT sermon_series as series, COUNT(*) as count FROM sermons WHERE sermon_series IS NOT NULL AND sermon_series != '' GROUP BY sermon_series ORDER BY series");

// Handle view count update (AJAX)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_view'])) {
    $sermon_id = intval($_POST['sermon_id']);
    $db->query("UPDATE sermons SET views = views + 1 WHERE id = $sermon_id");
    exit;
}

// Clean any buffered output
ob_end_clean();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sermons - Salem Dominion Ministries</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .sermons-hero {
            background: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)), url('public/images/hero/hero-worship.jpg');
            background-size: cover;
            background-position: center;
            color: white;
            padding: 80px 0;
        }
        .navbar-brand {
            font-weight: bold;
            font-size: 1.5rem;
        }
        .sermon-card {
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            border: none;
            border-radius: 15px;
            transition: transform 0.3s ease;
            overflow: hidden;
        }
        .sermon-card:hover {
            transform: translateY(-5px);
        }
        .sermon-thumbnail {
            height: 200px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
        }
        .play-button {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: rgba(255,255,255,0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            transition: all 0.3s ease;
        }
        .sermon-card:hover .play-button {
            background: rgba(255,255,255,0.3);
            transform: scale(1.1);
        }
        .series-filter {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 30px;
        }
        .series-badge {
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .series-badge:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand heavenly-logo" href="index.php">
                <img src="public/images/logo.png" alt="Salem Dominion Ministries" class="logo-img">
                <span class="brand-text">Salem Dominion Ministries</span>
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
                    <li class="nav-item"><a class="nav-link active" href="sermons.php">Sermons</a></li>
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
    <section class="sermons-hero">
        <div class="container text-center">
            <h1 class="display-4 fw-bold mb-4">Sermons by Apostle Faty Musasizi</h1>
            <p class="lead mb-4">"All Scripture is God-breathed and is useful for teaching, rebuking, correcting and training in righteousness." - 2 Timothy 3:16</p>
            <p class="mb-4">Listen to powerful teachings from Senior Pastor Apostle Faty Musasizi and our ministry team</p>
        </div>
    </section>

    <!-- Sermon Series Filter -->
    <section class="py-4">
        <div class="container">
            <div class="series-filter">
                <h4 class="mb-3"><i class="fas fa-filter text-primary"></i> Browse by Series</h4>
                <div class="row g-3">
                    <div class="col-auto">
                        <span class="badge bg-primary series-badge px-3 py-2" data-series="all">All Sermons</span>
                    </div>
                    <?php while ($series = $sermon_series->fetch_assoc()): ?>
                        <div class="col-auto">
                            <span class="badge bg-secondary series-badge px-3 py-2" data-series="<?php echo htmlspecialchars($series['series']); ?>">
                                <?php echo htmlspecialchars($series['series']); ?> (<?php echo $series['count']; ?>)
                            </span>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>
        </div>
    </section>

    <!-- Sermons Grid -->
    <section class="py-5 bg-light">
        <div class="container">
            <h2 class="text-center mb-5"><i class="fas fa-microphone text-primary"></i> Recent Sermons</h2>

            <div class="row g-4" id="sermonsContainer">
                <?php if ($recent_sermons->num_rows > 0): ?>
                    <?php while ($sermon = $recent_sermons->fetch_assoc()): ?>
                        <div class="col-lg-6 col-xl-4 sermon-item" data-series="<?php echo htmlspecialchars($sermon['sermon_series'] ?? ''); ?>">
                            <div class="card sermon-card h-100">
                                <div class="sermon-thumbnail">
                                    <?php if ($sermon['video_url']): ?>
                                        <div class="play-button" onclick="playSermon('<?php echo htmlspecialchars($sermon['video_url']); ?>', <?php echo $sermon['id']; ?>)">
                                            <i class="fas fa-play"></i>
                                        </div>
                                    <?php elseif ($sermon['audio_url']): ?>
                                        <div class="play-button" onclick="playAudio('<?php echo htmlspecialchars($sermon['audio_url']); ?>', <?php echo $sermon['id']; ?>)">
                                            <i class="fas fa-volume-up"></i>
                                        </div>
                                    <?php else: ?>
                                        <i class="fas fa-bible fa-3x"></i>
                                    <?php endif; ?>
                                </div>
                                <div class="card-body d-flex flex-column">
                                    <div class="mb-2">
                                        <?php if ($sermon['sermon_series']): ?>
                                        <span class="badge bg-primary mb-2"><?php echo htmlspecialchars($sermon['sermon_series']); ?></span>
                                        <?php endif; ?>
                                    </div>
                                    <h5 class="card-title"><?php echo htmlspecialchars($sermon['title']); ?></h5>
                                    <p class="card-text flex-grow-1"><?php echo htmlspecialchars(substr($sermon['description'] ?? '', 0, 100)); ?>...</p>
                                    <div class="mb-3">
                                        <small class="text-muted d-block">
                                            <i class="fas fa-user"></i> <?php echo htmlspecialchars($sermon['preacher'] ?: ($sermon['first_name'] . ' ' . $sermon['last_name']) ?: 'Church Staff'); ?>
                                        </small>
                                        <small class="text-muted d-block">
                                            <i class="fas fa-calendar"></i> <?php echo date('M j, Y', strtotime($sermon['sermon_date'])); ?>
                                            <i class="fas fa-eye ms-2"></i> <?php echo $sermon['views_count']; ?> views
                                        </small>
                                        <?php if ($sermon['bible_reference']): ?>
                                            <small class="text-muted d-block">
                                                <i class="fas fa-book"></i> <?php echo htmlspecialchars($sermon['bible_reference']); ?>
                                            </small>
                                        <?php endif; ?>
                                    </div>
                                    <div class="d-flex gap-2">
                                        <?php if ($sermon['video_url']): ?>
                                            <button class="btn btn-primary btn-sm flex-fill" onclick="playSermon('<?php echo htmlspecialchars($sermon['video_url']); ?>', <?php echo $sermon['id']; ?>)">
                                                <i class="fas fa-play"></i> Watch
                                            </button>
                                        <?php endif; ?>
                                        <?php if ($sermon['audio_url']): ?>
                                            <button class="btn btn-success btn-sm flex-fill" onclick="playAudio('<?php echo htmlspecialchars($sermon['audio_url']); ?>', <?php echo $sermon['id']; ?>)">
                                                <i class="fas fa-volume-up"></i> Listen
                                            </button>
                                        <?php endif; ?>
                                        <?php if ($sermon['pdf_url']): ?>
                                            <a href="<?php echo htmlspecialchars($sermon['pdf_url']); ?>" class="btn btn-outline-secondary btn-sm flex-fill" target="_blank">
                                                <i class="fas fa-file-pdf"></i> Notes
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="col-12 text-center py-5">
                        <i class="fas fa-microphone-slash fa-3x text-muted mb-3"></i>
                        <h4 class="text-muted">No sermons available</h4>
                        <p class="text-muted">Sermons will be added soon. Check back later!</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Media Player Modal -->
    <div class="modal fade" id="mediaModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="mediaModalTitle">Sermon</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-0">
                    <div id="mediaContainer">
                        <!-- Media content will be loaded here -->
                    </div>
                </div>
            </div>
        </div>
    </div>

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
    <script>
        // Series filtering
        document.querySelectorAll('.series-badge').forEach(badge => {
            badge.addEventListener('click', function() {
                const series = this.dataset.series;
                const sermons = document.querySelectorAll('.sermon-item');

                // Update active state
                document.querySelectorAll('.series-badge').forEach(b => {
                    b.classList.remove('bg-primary');
                    b.classList.add('bg-secondary');
                });
                this.classList.remove('bg-secondary');
                this.classList.add('bg-primary');

                // Filter sermons
                sermons.forEach(sermon => {
                    if (series === 'all' || sermon.dataset.series === series) {
                        sermon.style.display = 'block';
                    } else {
                        sermon.style.display = 'none';
                    }
                });
            });
        });

        // Play sermon functions
        function playSermon(videoUrl, sermonId) {
            updateViewCount(sermonId);
            const modal = new bootstrap.Modal(document.getElementById('mediaModal'));
            const container = document.getElementById('mediaContainer');
            const title = document.getElementById('mediaModalTitle');

            title.textContent = 'Watch Sermon';
            container.innerHTML = `
                <div class="ratio ratio-16x9">
                    <iframe src="${videoUrl}" allowfullscreen></iframe>
                </div>
            `;
            modal.show();
        }

        function playAudio(audioUrl, sermonId) {
            updateViewCount(sermonId);
            const modal = new bootstrap.Modal(document.getElementById('mediaModal'));
            const container = document.getElementById('mediaContainer');
            const title = document.getElementById('mediaModalTitle');

            title.textContent = 'Listen to Sermon';
            container.innerHTML = `
                <audio controls class="w-100">
                    <source src="${audioUrl}" type="audio/mpeg">
                    Your browser does not support the audio element.
                </audio>
            `;
            modal.show();
        }

        function updateViewCount(sermonId) {
            fetch('', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'update_view=1&sermon_id=' + sermonId
            });
        }
    </script>
</body>
</html>