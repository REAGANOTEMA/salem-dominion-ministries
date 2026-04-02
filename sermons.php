&lt;?php
session_start();
require_once 'db.php';

// Get recent sermons
$recent_sermons = $db-&gt;query("SELECT s.*, u.username FROM sermons s LEFT JOIN users u ON s.speaker_id = u.id ORDER BY s.sermon_date DESC LIMIT 12");

// Get sermon series
$sermon_series = $db-&gt;query("SELECT series, COUNT(*) as count FROM sermons GROUP BY series ORDER BY series");

// Handle view count update (AJAX)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_view'])) {
    $sermon_id = intval($_POST['sermon_id']);
    $db-&gt;query("UPDATE sermons SET views = views + 1 WHERE id = $sermon_id");
    exit;
}
?&gt;
&lt;!DOCTYPE html&gt;
&lt;html lang="en"&gt;
&lt;head&gt;
    &lt;meta charset="UTF-8"&gt;
    &lt;meta name="viewport" content="width=device-width, initial-scale=1.0"&gt;
    &lt;title&gt;Sermons - Salem Dominion Ministries&lt;/title&gt;
    &lt;link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"&gt;
    &lt;link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet"&gt;
    &lt;style&gt;
        .sermons-hero {
            background: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)), url('https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?ixlib=rb-4.0.3');
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
                    &lt;li class="nav-item"&gt;&lt;a class="nav-link active" href="sermons.php"&gt;Sermons&lt;/a&gt;&lt;/li&gt;
                    &lt;li class="nav-item"&gt;&lt;a class="nav-link" href="news.php"&gt;News&lt;/a&gt;&lt;/li&gt;
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
    &lt;section class="sermons-hero"&gt;
        &lt;div class="container text-center"&gt;
            &lt;h1 class="display-4 fw-bold mb-4"&gt;Sermons&lt;/h1&gt;
            &lt;p class="lead mb-4"&gt;"All Scripture is God-breathed and is useful for teaching, rebuking, correcting and training in righteousness." - 2 Timothy 3:16&lt;/p&gt;
        &lt;/div&gt;
    &lt;/section&gt;

    &lt;!-- Sermon Series Filter --&gt;
    &lt;section class="py-4"&gt;
        &lt;div class="container"&gt;
            &lt;div class="series-filter"&gt;
                &lt;h4 class="mb-3"&gt;&lt;i class="fas fa-filter text-primary"&gt;&lt;/i&gt; Browse by Series&lt;/h4&gt;
                &lt;div class="row g-3"&gt;
                    &lt;div class="col-auto"&gt;
                        &lt;span class="badge bg-primary series-badge px-3 py-2" data-series="all"&gt;All Sermons&lt;/span&gt;
                    &lt;/div&gt;
                    &lt;?php while ($series = $sermon_series-&gt;fetch_assoc()): ?&gt;
                        &lt;div class="col-auto"&gt;
                            &lt;span class="badge bg-secondary series-badge px-3 py-2" data-series="&lt;?php echo htmlspecialchars($series['series']); ?&gt;"&gt;
                                &lt;?php echo htmlspecialchars($series['series']); ?&gt; (&lt;?php echo $series['count']; ?&gt;)
                            &lt;/span&gt;
                        &lt;/div&gt;
                    &lt;?php endwhile; ?&gt;
                &lt;/div&gt;
            &lt;/div&gt;
        &lt;/div&gt;
    &lt;/section&gt;

    &lt;!-- Sermons Grid --&gt;
    &lt;section class="py-5 bg-light"&gt;
        &lt;div class="container"&gt;
            &lt;h2 class="text-center mb-5"&gt;&lt;i class="fas fa-microphone text-primary"&gt;&lt;/i&gt; Recent Sermons&lt;/h2&gt;

            &lt;div class="row g-4" id="sermonsContainer"&gt;
                &lt;?php if ($recent_sermons-&gt;num_rows &gt; 0): ?&gt;
                    &lt;?php while ($sermon = $recent_sermons-&gt;fetch_assoc()): ?&gt;
                        &lt;div class="col-lg-6 col-xl-4 sermon-item" data-series="&lt;?php echo htmlspecialchars($sermon['series']); ?&gt;"&gt;
                            &lt;div class="card sermon-card h-100"&gt;
                                &lt;div class="sermon-thumbnail"&gt;
                                    &lt;?php if ($sermon['video_url']): ?&gt;
                                        &lt;div class="play-button" onclick="playSermon('&lt;?php echo htmlspecialchars($sermon['video_url']); ?&gt;', &lt;?php echo $sermon['id']; ?&gt;)"&gt;
                                            &lt;i class="fas fa-play"&gt;&lt;/i&gt;
                                        &lt;/div&gt;
                                    &lt;?php elseif ($sermon['audio_url']): ?&gt;
                                        &lt;div class="play-button" onclick="playAudio('&lt;?php echo htmlspecialchars($sermon['audio_url']); ?&gt;', &lt;?php echo $sermon['id']; ?&gt;)"&gt;
                                            &lt;i class="fas fa-volume-up"&gt;&lt;/i&gt;
                                        &lt;/div&gt;
                                    &lt;?php else: ?&gt;
                                        &lt;i class="fas fa-bible fa-3x"&gt;&lt;/i&gt;
                                    &lt;?php endif; ?&gt;
                                &lt;/div&gt;
                                &lt;div class="card-body d-flex flex-column"&gt;
                                    &lt;div class="mb-2"&gt;
                                        &lt;span class="badge bg-primary mb-2"&gt;&lt;?php echo htmlspecialchars($sermon['series']); ?&gt;&lt;/span&gt;
                                    &lt;/div&gt;
                                    &lt;h5 class="card-title"&gt;&lt;?php echo htmlspecialchars($sermon['title']); ?&gt;&lt;/h5&gt;
                                    &lt;p class="card-text flex-grow-1"&gt;&lt;?php echo htmlspecialchars(substr($sermon['description'], 0, 100)); ?&gt;...&lt;/p&gt;
                                    &lt;div class="mb-3"&gt;
                                        &lt;small class="text-muted d-block"&gt;
                                            &lt;i class="fas fa-user"&gt;&lt;/i&gt; &lt;?php echo htmlspecialchars($sermon['speaker_name'] ?: $sermon['username'] ?: 'Church Staff'); ?&gt;
                                        &lt;/small&gt;
                                        &lt;small class="text-muted d-block"&gt;
                                            &lt;i class="fas fa-calendar"&gt;&lt;/i&gt; &lt;?php echo date('M j, Y', strtotime($sermon['sermon_date'])); ?&gt;
                                            &lt;i class="fas fa-eye ms-2"&gt;&lt;/i&gt; &lt;?php echo $sermon['views']; ?&gt; views
                                        &lt;/small&gt;
                                        &lt;?php if ($sermon['scripture_reference']): ?&gt;
                                            &lt;small class="text-muted d-block"&gt;
                                                &lt;i class="fas fa-book"&gt;&lt;/i&gt; &lt;?php echo htmlspecialchars($sermon['scripture_reference']); ?&gt;
                                            &lt;/small&gt;
                                        &lt;?php endif; ?&gt;
                                    &lt;/div&gt;
                                    &lt;div class="d-flex gap-2"&gt;
                                        &lt;?php if ($sermon['video_url']): ?&gt;
                                            &lt;button class="btn btn-primary btn-sm flex-fill" onclick="playSermon('&lt;?php echo htmlspecialchars($sermon['video_url']); ?&gt;', &lt;?php echo $sermon['id']; ?&gt;)"&gt;
                                                &lt;i class="fas fa-play"&gt;&lt;/i&gt; Watch
                                            &lt;/button&gt;
                                        &lt;?php endif; ?&gt;
                                        &lt;?php if ($sermon['audio_url']): ?&gt;
                                            &lt;button class="btn btn-success btn-sm flex-fill" onclick="playAudio('&lt;?php echo htmlspecialchars($sermon['audio_url']); ?&gt;', &lt;?php echo $sermon['id']; ?&gt;)"&gt;
                                                &lt;i class="fas fa-volume-up"&gt;&lt;/i&gt; Listen
                                            &lt;/button&gt;
                                        &lt;?php endif; ?&gt;
                                        &lt;?php if ($sermon['pdf_url']): ?&gt;
                                            &lt;a href="&lt;?php echo htmlspecialchars($sermon['pdf_url']); ?&gt;" class="btn btn-outline-secondary btn-sm flex-fill" target="_blank"&gt;
                                                &lt;i class="fas fa-file-pdf"&gt;&lt;/i&gt; Notes
                                            &lt;/a&gt;
                                        &lt;?php endif; ?&gt;
                                    &lt;/div&gt;
                                &lt;/div&gt;
                            &lt;/div&gt;
                        &lt;/div&gt;
                    &lt;?php endwhile; ?&gt;
                &lt;?php else: ?&gt;
                    &lt;div class="col-12 text-center py-5"&gt;
                        &lt;i class="fas fa-microphone-slash fa-3x text-muted mb-3"&gt;&lt;/i&gt;
                        &lt;h4 class="text-muted"&gt;No sermons available&lt;/h4&gt;
                        &lt;p class="text-muted"&gt;Sermons will be added soon. Check back later!&lt;/p&gt;
                    &lt;/div&gt;
                &lt;?php endif; ?&gt;
            &lt;/div&gt;
        &lt;/div&gt;
    &lt;/section&gt;

    &lt;!-- Media Player Modal --&gt;
    &lt;div class="modal fade" id="mediaModal" tabindex="-1"&gt;
        &lt;div class="modal-dialog modal-lg"&gt;
            &lt;div class="modal-content"&gt;
                &lt;div class="modal-header"&gt;
                    &lt;h5 class="modal-title" id="mediaModalTitle"&gt;Sermon&lt;/h5&gt;
                    &lt;button type="button" class="btn-close" data-bs-dismiss="modal"&gt;&lt;/button&gt;
                &lt;/div&gt;
                &lt;div class="modal-body p-0"&gt;
                    &lt;div id="mediaContainer"&gt;
                        &lt;!-- Media content will be loaded here --&gt;
                    &lt;/div&gt;
                &lt;/div&gt;
            &lt;/div&gt;
        &lt;/div&gt;
    &lt;/div&gt;

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
    &lt;script&gt;
        // Series filtering
        document.querySelectorAll('.series-badge').forEach(badge =&gt; {
            badge.addEventListener('click', function() {
                const series = this.dataset.series;
                const sermons = document.querySelectorAll('.sermon-item');

                // Update active state
                document.querySelectorAll('.series-badge').forEach(b =&gt; {
                    b.classList.remove('bg-primary');
                    b.classList.add('bg-secondary');
                });
                this.classList.remove('bg-secondary');
                this.classList.add('bg-primary');

                // Filter sermons
                sermons.forEach(sermon =&gt; {
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
                &lt;div class="ratio ratio-16x9"&gt;
                    &lt;iframe src="${videoUrl}" allowfullscreen&gt;&lt;/iframe&gt;
                &lt;/div&gt;
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
                &lt;audio controls class="w-100"&gt;
                    &lt;source src="${audioUrl}" type="audio/mpeg"&gt;
                    Your browser does not support the audio element.
                &lt;/audio&gt;
            `;
            modal.show();
        }

        function updateViewCount(sermonId) {
            fetch('', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'update_view=1&amp;sermon_id=' + sermonId
            });
        }
    &lt;/script&gt;
&lt;/body&gt;
&lt;/html&gt;