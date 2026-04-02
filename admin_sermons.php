&lt;?php
session_start();
require_once 'db.php';

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

// Create uploads directory if it doesn't exist
$sermons_dir = 'uploads/sermons';
if (!is_dir($sermons_dir)) {
    mkdir($sermons_dir, 0755, true);
}

// Handle form submissions
$message = '';
$message_type = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];

        if ($action === 'upload_sermon') {
            $title = trim($_POST['title']);
            $speaker = trim($_POST['speaker']);
            $series = trim($_POST['series']);
            $description = trim($_POST['description']);
            $sermon_date = $_POST['sermon_date'];
            $scripture = trim($_POST['scripture']);
            $duration = trim($_POST['duration']);

            if (empty($title) || empty($speaker)) {
                $message = 'Title and speaker are required.';
                $message_type = 'error';
            } else {
                $audio_file = '';
                $video_file = '';

                // Handle audio file upload
                if (isset($_FILES['audio_file']) && $_FILES['audio_file']['error'] === UPLOAD_ERR_OK) {
                    $audio_name = time() . '_audio_' . basename($_FILES['audio_file']['name']);
                    $audio_path = $sermons_dir . '/' . $audio_name;
                    if (move_uploaded_file($_FILES['audio_file']['tmp_name'], $audio_path)) {
                        $audio_file = $audio_path;
                    }
                }

                // Handle video file upload
                if (isset($_FILES['video_file']) && $_FILES['video_file']['error'] === UPLOAD_ERR_OK) {
                    $video_name = time() . '_video_' . basename($_FILES['video_file']['name']);
                    $video_path = $sermons_dir . '/' . $video_name;
                    if (move_uploaded_file($_FILES['video_file']['tmp_name'], $video_path)) {
                        $video_file = $video_path;
                    }
                }

                $stmt = $db-&gt;prepare("INSERT INTO sermons (title, speaker, series, description, sermon_date, scripture, audio_file, video_file, duration, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");
                $stmt-&gt;bind_param('sssssssss', $title, $speaker, $series, $description, $sermon_date, $scripture, $audio_file, $video_file, $duration);
                if ($stmt-&gt;execute()) {
                    $message = 'Sermon uploaded successfully.';
                    $message_type = 'success';
                } else {
                    $message = 'Error uploading sermon.';
                    $message_type = 'error';
                }
            }
        } elseif ($action === 'update_sermon') {
            $id = intval($_POST['sermon_id']);
            $title = trim($_POST['title']);
            $speaker = trim($_POST['speaker']);
            $series = trim($_POST['series']);
            $description = trim($_POST['description']);
            $sermon_date = $_POST['sermon_date'];
            $scripture = trim($_POST['scripture']);
            $duration = trim($_POST['duration']);

            if (empty($title) || empty($speaker)) {
                $message = 'Title and speaker are required.';
                $message_type = 'error';
            } else {
                $stmt = $db-&gt;prepare("UPDATE sermons SET title = ?, speaker = ?, series = ?, description = ?, sermon_date = ?, scripture = ?, duration = ? WHERE id = ?");
                $stmt-&gt;bind_param('sssssssi', $title, $speaker, $series, $description, $sermon_date, $scripture, $duration, $id);
                if ($stmt-&gt;execute()) {
                    $message = 'Sermon updated successfully.';
                    $message_type = 'success';
                } else {
                    $message = 'Error updating sermon.';
                    $message_type = 'error';
                }
            }
        } elseif ($action === 'delete_sermon') {
            $id = intval($_POST['sermon_id']);
            
            // Get file paths before deleting
            $stmt = $db-&gt;prepare("SELECT audio_file, video_file FROM sermons WHERE id = ?");
            $stmt-&gt;bind_param('i', $id);
            $stmt-&gt;execute();
            $result = $stmt-&gt;get_result();
            $sermon = $result-&gt;fetch_assoc();
            
            // Delete files
            if ($sermon['audio_file'] && file_exists($sermon['audio_file'])) {
                unlink($sermon['audio_file']);
            }
            if ($sermon['video_file'] && file_exists($sermon['video_file'])) {
                unlink($sermon['video_file']);
            }
            
            // Delete from database
            $stmt = $db-&gt;prepare("DELETE FROM sermons WHERE id = ?");
            $stmt-&gt;bind_param('i', $id);
            if ($stmt-&gt;execute()) {
                $message = 'Sermon deleted successfully.';
                $message_type = 'success';
            } else {
                $message = 'Error deleting sermon.';
                $message_type = 'error';
            }
        }
    }
}

// Get all sermons
$sermons_result = $db-&gt;query("SELECT * FROM sermons ORDER BY sermon_date DESC, created_at DESC");
?&gt;
&lt;!DOCTYPE html&gt;
&lt;html lang="en"&gt;
&lt;head&gt;
    &lt;meta charset="UTF-8"&gt;
    &lt;meta name="viewport" content="width=device-width, initial-scale=1.0"&gt;
    &lt;title&gt;Sermon Management - Salem Dominion Ministries&lt;/title&gt;
    &lt;link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"&gt;
    &lt;link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet"&gt;
    &lt;style&gt;
        .sidebar {
            min-height: calc(100vh - 76px);
            background: #343a40;
        }
        .sidebar .nav-link {
            color: rgba(255,255,255,.75);
            padding: 12px 20px;
            border-radius: 5px;
            margin-bottom: 5px;
        }
        .sidebar .nav-link:hover {
            color: white;
            background: rgba(255,255,255,.1);
        }
        .sidebar .nav-link.active {
            color: white;
            background: #0d6efd;
        }
        .sermon-card {
            transition: transform 0.2s ease;
        }
        .sermon-card:hover {
            transform: translateY(-2px);
        }
        .modal-xl {
            max-width: 1200px;
        }
        .file-upload-area {
            border: 2px dashed #dee2e6;
            border-radius: 5px;
            padding: 20px;
            text-align: center;
            transition: border-color 0.3s ease;
        }
        .file-upload-area:hover {
            border-color: #0d6efd;
        }
        .file-upload-area.dragover {
            border-color: #0d6efd;
            background-color: #f8f9fa;
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
                    &lt;li class="nav-item"&gt;&lt;a class="nav-link" href="news.php"&gt;News&lt;/a&gt;&lt;/li&gt;
                    &lt;li class="nav-item"&gt;&lt;a class="nav-link" href="gallery.php"&gt;Gallery&lt;/a&gt;&lt;/li&gt;
                    &lt;li class="nav-item"&gt;&lt;a class="nav-link" href="contact.php"&gt;Contact&lt;/a&gt;&lt;/li&gt;
                &lt;/ul&gt;
                &lt;ul class="navbar-nav"&gt;
                    &lt;li class="nav-item"&gt;&lt;a class="nav-link" href="dashboard.php"&gt;Dashboard&lt;/a&gt;&lt;/li&gt;
                    &lt;li class="nav-item"&gt;&lt;a class="nav-link" href="logout.php"&gt;Logout&lt;/a&gt;&lt;/li&gt;
                &lt;/ul&gt;
            &lt;/div&gt;
        &lt;/div&gt;
    &lt;/nav&gt;

    &lt;div class="container-fluid"&gt;
        &lt;div class="row"&gt;
            &lt;!-- Sidebar --&gt;
            &lt;div class="col-md-3 col-lg-2 px-0"&gt;
                &lt;div class="sidebar p-3"&gt;
                    &lt;h6 class="text-white-50 mb-3"&gt;ADMIN PANEL&lt;/h6&gt;
                    &lt;nav class="nav flex-column"&gt;
                        &lt;a class="nav-link" href="admin_dashboard.php"&gt;
                            &lt;i class="fas fa-tachometer-alt"&gt;&lt;/i&gt; Dashboard
                        &lt;/a&gt;
                        &lt;a class="nav-link" href="admin_users.php"&gt;
                            &lt;i class="fas fa-users"&gt;&lt;/i&gt; Users
                        &lt;/a&gt;
                        &lt;a class="nav-link" href="admin_news.php"&gt;
                            &lt;i class="fas fa-newspaper"&gt;&lt;/i&gt; News
                        &lt;/a&gt;
                        &lt;a class="nav-link" href="admin_events.php"&gt;
                            &lt;i class="fas fa-calendar"&gt;&lt;/i&gt; Events
                        &lt;/a&gt;
                        &lt;a class="nav-link active" href="admin_sermons.php"&gt;
                            &lt;i class="fas fa-microphone"&gt;&lt;/i&gt; Sermons
                        &lt;/a&gt;
                        &lt;a class="nav-link" href="admin_ministries.php"&gt;
                            &lt;i class="fas fa-praying-hands"&gt;&lt;/i&gt; Ministries
                        &lt;/a&gt;
                        &lt;a class="nav-link" href="admin_gallery.php"&gt;
                            &lt;i class="fas fa-images"&gt;&lt;/i&gt; Gallery
                        &lt;/a&gt;
                        &lt;a class="nav-link" href="admin_donations.php"&gt;
                            &lt;i class="fas fa-hand-holding-heart"&gt;&lt;/i&gt; Donations
                        &lt;/a&gt;
                        &lt;a class="nav-link" href="admin_prayer_requests.php"&gt;
                            &lt;i class="fas fa-pray"&gt;&lt;/i&gt; Prayer Requests
                        &lt;/a&gt;
                    &lt;/nav&gt;
                &lt;/div&gt;
            &lt;/div&gt;

            &lt;!-- Main Content --&gt;
            &lt;div class="col-md-9 col-lg-10 px-4 py-4"&gt;
                &lt;div class="d-flex justify-content-between align-items-center mb-4"&gt;
                    &lt;h2&gt;&lt;i class="fas fa-microphone text-primary"&gt;&lt;/i&gt; Sermon Management&lt;/h2&gt;
                    &lt;button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#uploadSermonModal"&gt;
                        &lt;i class="fas fa-upload"&gt;&lt;/i&gt; Upload Sermon
                    &lt;/button&gt;
                &lt;/div&gt;

                &lt;?php if ($message): ?&gt;
                    &lt;div class="alert alert-&lt;?php echo $message_type === 'success' ? 'success' : 'danger'; ?&gt; alert-dismissible fade show" role="alert"&gt;
                        &lt;?php echo htmlspecialchars($message); ?&gt;
                        &lt;button type="button" class="btn-close" data-bs-dismiss="alert"&gt;&lt;/button&gt;
                    &lt;/div&gt;
                &lt;?php endif; ?&gt;

                &lt;!-- Sermons List --&gt;
                &lt;div class="row"&gt;
                    &lt;?php if ($sermons_result-&gt;num_rows &gt; 0): ?&gt;
                        &lt;?php while ($sermon = $sermons_result-&gt;fetch_assoc()): ?&gt;
                            &lt;div class="col-lg-6 mb-4"&gt;
                                &lt;div class="card sermon-card h-100"&gt;
                                    &lt;div class="card-body"&gt;
                                        &lt;div class="d-flex justify-content-between align-items-start mb-2"&gt;
                                            &lt;h5 class="card-title mb-0"&gt;&lt;?php echo htmlspecialchars($sermon['title']); ?&gt;&lt;/h5&gt;
                                            &lt;div class="btn-group" role="group"&gt;
                                                &lt;button class="btn btn-outline-primary btn-sm" onclick="editSermon(&lt;?php echo $sermon['id']; ?&gt;, '&lt;?php echo htmlspecialchars(addslashes($sermon['title'])); ?&gt;', '&lt;?php echo htmlspecialchars(addslashes($sermon['speaker'])); ?&gt;', '&lt;?php echo htmlspecialchars(addslashes($sermon['series'])); ?&gt;', '&lt;?php echo htmlspecialchars(addslashes($sermon['description'])); ?&gt;', '&lt;?php echo $sermon['sermon_date']; ?&gt;', '&lt;?php echo htmlspecialchars(addslashes($sermon['scripture'])); ?&gt;', '&lt;?php echo htmlspecialchars(addslashes($sermon['duration'])); ?&gt;')"&gt;
                                                    &lt;i class="fas fa-edit"&gt;&lt;/i&gt;
                                                &lt;/button&gt;
                                                &lt;form method="post" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this sermon? This will also delete the associated media files.')"&gt;
                                                    &lt;input type="hidden" name="action" value="delete_sermon"&gt;
                                                    &lt;input type="hidden" name="sermon_id" value="&lt;?php echo $sermon['id']; ?&gt;"&gt;
                                                    &lt;button type="submit" class="btn btn-outline-danger btn-sm"&gt;
                                                        &lt;i class="fas fa-trash"&gt;&lt;/i&gt;
                                                    &lt;/button&gt;
                                                &lt;/form&gt;
                                            &lt;/div&gt;
                                        &lt;/div&gt;
                                        &lt;div class="mb-2"&gt;
                                            &lt;small class="text-muted"&gt;
                                                &lt;i class="fas fa-user-tie"&gt;&lt;/i&gt; &lt;?php echo htmlspecialchars($sermon['speaker']); ?&gt;
                                                &lt;?php if ($sermon['series']): ?&gt;
                                                    | &lt;i class="fas fa-list"&gt;&lt;/i&gt; &lt;?php echo htmlspecialchars($sermon['series']); ?&gt;
                                                &lt;?php endif; ?&gt;
                                            &lt;/small&gt;
                                        &lt;/div&gt;
                                        &lt;p class="card-text text-muted small"&gt;&lt;?php echo htmlspecialchars(substr($sermon['description'], 0, 100)) . (strlen($sermon['description']) &gt; 100 ? '...' : ''); ?&gt;&lt;/p&gt;
                                        &lt;div class="row text-muted small"&gt;
                                            &lt;div class="col-6"&gt;
                                                &lt;i class="fas fa-calendar"&gt;&lt;/i&gt; &lt;?php echo date('M j, Y', strtotime($sermon['sermon_date'])); ?&gt;&lt;br&gt;
                                                &lt;?php if ($sermon['scripture']): ?&gt;
                                                    &lt;i class="fas fa-book"&gt;&lt;/i&gt; &lt;?php echo htmlspecialchars($sermon['scripture']); ?&gt;
                                                &lt;?php endif; ?&gt;
                                            &lt;/div&gt;
                                            &lt;div class="col-6"&gt;
                                                &lt;?php if ($sermon['duration']): ?&gt;
                                                    &lt;i class="fas fa-clock"&gt;&lt;/i&gt; &lt;?php echo htmlspecialchars($sermon['duration']); ?&gt;&lt;br&gt;
                                                &lt;?php endif; ?&gt;
                                                &lt;i class="fas fa-eye"&gt;&lt;/i&gt; &lt;?php echo $sermon['views']; ?&gt; views
                                            &lt;/div&gt;
                                        &lt;/div&gt;
                                        &lt;div class="mt-3"&gt;
                                            &lt;?php if ($sermon['audio_file']): ?&gt;
                                                &lt;span class="badge bg-success me-1"&gt;&lt;i class="fas fa-volume-up"&gt;&lt;/i&gt; Audio&lt;/span&gt;
                                            &lt;?php endif; ?&gt;
                                            &lt;?php if ($sermon['video_file']): ?&gt;
                                                &lt;span class="badge bg-info"&gt;&lt;i class="fas fa-video"&gt;&lt;/i&gt; Video&lt;/span&gt;
                                            &lt;?php endif; ?&gt;
                                        &lt;/div&gt;
                                    &lt;/div&gt;
                                &lt;/div&gt;
                            &lt;/div&gt;
                        &lt;?php endwhile; ?&gt;
                    &lt;?php else: ?&gt;
                        &lt;div class="col-12"&gt;
                            &lt;div class="text-center py-5"&gt;
                                &lt;i class="fas fa-microphone fa-3x text-muted mb-3"&gt;&lt;/i&gt;
                                &lt;h4 class="text-muted"&gt;No Sermons Yet&lt;/h4&gt;
                                &lt;p class="text-muted"&gt;Upload your first sermon to get started.&lt;/p&gt;
                            &lt;/div&gt;
                        &lt;/div&gt;
                    &lt;?php endif; ?&gt;
                &lt;/div&gt;
            &lt;/div&gt;
        &lt;/div&gt;
    &lt;/div&gt;

    &lt;!-- Upload Sermon Modal --&gt;
    &lt;div class="modal fade" id="uploadSermonModal" tabindex="-1"&gt;
        &lt;div class="modal-dialog modal-xl"&gt;
            &lt;div class="modal-content"&gt;
                &lt;div class="modal-header"&gt;
                    &lt;h5 class="modal-title"&gt;&lt;i class="fas fa-upload"&gt;&lt;/i&gt; Upload Sermon&lt;/h5&gt;
                    &lt;button type="button" class="btn-close" data-bs-dismiss="modal"&gt;&lt;/button&gt;
                &lt;/div&gt;
                &lt;form method="post" enctype="multipart/form-data"&gt;
                    &lt;div class="modal-body"&gt;
                        &lt;input type="hidden" name="action" value="upload_sermon"&gt;
                        &lt;div class="row"&gt;
                            &lt;div class="col-md-8"&gt;
                                &lt;div class="row"&gt;
                                    &lt;div class="col-md-6 mb-3"&gt;
                                        &lt;label for="title" class="form-label"&gt;Sermon Title *&lt;/label&gt;
                                        &lt;input type="text" class="form-control" id="title" name="title" required&gt;
                                    &lt;/div&gt;
                                    &lt;div class="col-md-6 mb-3"&gt;
                                        &lt;label for="speaker" class="form-label"&gt;Speaker *&lt;/label&gt;
                                        &lt;input type="text" class="form-control" id="speaker" name="speaker" required&gt;
                                    &lt;/div&gt;
                                &lt;/div&gt;
                                &lt;div class="row"&gt;
                                    &lt;div class="col-md-6 mb-3"&gt;
                                        &lt;label for="series" class="form-label"&gt;Series (Optional)&lt;/label&gt;
                                        &lt;input type="text" class="form-control" id="series" name="series"&gt;
                                    &lt;/div&gt;
                                    &lt;div class="col-md-6 mb-3"&gt;
                                        &lt;label for="sermon_date" class="form-label"&gt;Sermon Date *&lt;/label&gt;
                                        &lt;input type="date" class="form-control" id="sermon_date" name="sermon_date" required&gt;
                                    &lt;/div&gt;
                                &lt;/div&gt;
                                &lt;div class="row"&gt;
                                    &lt;div class="col-md-6 mb-3"&gt;
                                        &lt;label for="scripture" class="form-label"&gt;Scripture Reference&lt;/label&gt;
                                        &lt;input type="text" class="form-control" id="scripture" name="scripture" placeholder="e.g., John 3:16"&gt;
                                    &lt;/div&gt;
                                    &lt;div class="col-md-6 mb-3"&gt;
                                        &lt;label for="duration" class="form-label"&gt;Duration&lt;/label&gt;
                                        &lt;input type="text" class="form-control" id="duration" name="duration" placeholder="e.g., 45 minutes"&gt;
                                    &lt;/div&gt;
                                &lt;/div&gt;
                                &lt;div class="mb-3"&gt;
                                    &lt;label for="description" class="form-label"&gt;Description&lt;/label&gt;
                                    &lt;textarea class="form-control" id="description" name="description" rows="3"&gt;&lt;/textarea&gt;
                                &lt;/div&gt;
                            &lt;/div&gt;
                            &lt;div class="col-md-4"&gt;
                                &lt;div class="mb-3"&gt;
                                    &lt;label class="form-label"&gt;Audio File (MP3, etc.)&lt;/label&gt;
                                    &lt;div class="file-upload-area" id="audioUploadArea"&gt;
                                        &lt;i class="fas fa-volume-up fa-2x text-muted mb-2"&gt;&lt;/i&gt;
                                        &lt;p class="mb-1"&gt;Drag &amp; drop audio file here&lt;/p&gt;
                                        &lt;p class="small text-muted"&gt;or&lt;/p&gt;
                                        &lt;input type="file" class="form-control" id="audio_file" name="audio_file" accept="audio/*" style="display: none;"&gt;
                                        &lt;button type="button" class="btn btn-outline-primary btn-sm" onclick="document.getElementById('audio_file').click()"&gt;Choose File&lt;/button&gt;
                                        &lt;div id="audioFileName" class="mt-2 small text-success"&gt;&lt;/div&gt;
                                    &lt;/div&gt;
                                &lt;/div&gt;
                                &lt;div class="mb-3"&gt;
                                    &lt;label class="form-label"&gt;Video File (MP4, etc.)&lt;/label&gt;
                                    &lt;div class="file-upload-area" id="videoUploadArea"&gt;
                                        &lt;i class="fas fa-video fa-2x text-muted mb-2"&gt;&lt;/i&gt;
                                        &lt;p class="mb-1"&gt;Drag &amp; drop video file here&lt;/p&gt;
                                        &lt;p class="small text-muted"&gt;or&lt;/p&gt;
                                        &lt;input type="file" class="form-control" id="video_file" name="video_file" accept="video/*" style="display: none;"&gt;
                                        &lt;button type="button" class="btn btn-outline-primary btn-sm" onclick="document.getElementById('video_file').click()"&gt;Choose File&lt;/button&gt;
                                        &lt;div id="videoFileName" class="mt-2 small text-success"&gt;&lt;/div&gt;
                                    &lt;/div&gt;
                                &lt;/div&gt;
                            &lt;/div&gt;
                        &lt;/div&gt;
                    &lt;/div&gt;
                    &lt;div class="modal-footer"&gt;
                        &lt;button type="button" class="btn btn-secondary" data-bs-dismiss="modal"&gt;Cancel&lt;/button&gt;
                        &lt;button type="submit" class="btn btn-primary"&gt;&lt;i class="fas fa-upload"&gt;&lt;/i&gt; Upload Sermon&lt;/button&gt;
                    &lt;/div&gt;
                &lt;/form&gt;
            &lt;/div&gt;
        &lt;/div&gt;
    &lt;/div&gt;

    &lt;!-- Edit Sermon Modal --&gt;
    &lt;div class="modal fade" id="editSermonModal" tabindex="-1"&gt;
        &lt;div class="modal-dialog modal-lg"&gt;
            &lt;div class="modal-content"&gt;
                &lt;div class="modal-header"&gt;
                    &lt;h5 class="modal-title"&gt;&lt;i class="fas fa-edit"&gt;&lt;/i&gt; Edit Sermon&lt;/h5&gt;
                    &lt;button type="button" class="btn-close" data-bs-dismiss="modal"&gt;&lt;/button&gt;
                &lt;/div&gt;
                &lt;form method="post"&gt;
                    &lt;div class="modal-body"&gt;
                        &lt;input type="hidden" name="action" value="update_sermon"&gt;
                        &lt;input type="hidden" name="sermon_id" id="edit_sermon_id"&gt;
                        &lt;div class="row"&gt;
                            &lt;div class="col-md-6 mb-3"&gt;
                                &lt;label for="edit_title" class="form-label"&gt;Sermon Title *&lt;/label&gt;
                                &lt;input type="text" class="form-control" id="edit_title" name="title" required&gt;
                            &lt;/div&gt;
                            &lt;div class="col-md-6 mb-3"&gt;
                                &lt;label for="edit_speaker" class="form-label"&gt;Speaker *&lt;/label&gt;
                                &lt;input type="text" class="form-control" id="edit_speaker" name="speaker" required&gt;
                            &lt;/div&gt;
                        &lt;/div&gt;
                        &lt;div class="row"&gt;
                            &lt;div class="col-md-6 mb-3"&gt;
                                &lt;label for="edit_series" class="form-label"&gt;Series (Optional)&lt;/label&gt;
                                &lt;input type="text" class="form-control" id="edit_series" name="series"&gt;
                            &lt;/div&gt;
                            &lt;div class="col-md-6 mb-3"&gt;
                                &lt;label for="edit_sermon_date" class="form-label"&gt;Sermon Date *&lt;/label&gt;
                                &lt;input type="date" class="form-control" id="edit_sermon_date" name="sermon_date" required&gt;
                            &lt;/div&gt;
                        &lt;/div&gt;
                        &lt;div class="row"&gt;
                            &lt;div class="col-md-6 mb-3"&gt;
                                &lt;label for="edit_scripture" class="form-label"&gt;Scripture Reference&lt;/label&gt;
                                &lt;input type="text" class="form-control" id="edit_scripture" name="scripture" placeholder="e.g., John 3:16"&gt;
                            &lt;/div&gt;
                            &lt;div class="col-md-6 mb-3"&gt;
                                &lt;label for="edit_duration" class="form-label"&gt;Duration&lt;/label&gt;
                                &lt;input type="text" class="form-control" id="edit_duration" name="duration" placeholder="e.g., 45 minutes"&gt;
                            &lt;/div&gt;
                        &lt;/div&gt;
                        &lt;div class="mb-3"&gt;
                            &lt;label for="edit_description" class="form-label"&gt;Description&lt;/label&gt;
                            &lt;textarea class="form-control" id="edit_description" name="description" rows="3"&gt;&lt;/textarea&gt;
                        &lt;/div&gt;
                    &lt;/div&gt;
                    &lt;div class="modal-footer"&gt;
                        &lt;button type="button" class="btn btn-secondary" data-bs-dismiss="modal"&gt;Cancel&lt;/button&gt;
                        &lt;button type="submit" class="btn btn-primary"&gt;&lt;i class="fas fa-save"&gt;&lt;/i&gt; Update Sermon&lt;/button&gt;
                    &lt;/div&gt;
                &lt;/form&gt;
            &lt;/div&gt;
        &lt;/div&gt;
    &lt;/div&gt;

    &lt;script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"&gt;&lt;/script&gt;
    &lt;script&gt;
        function editSermon(id, title, speaker, series, description, sermonDate, scripture, duration) {
            document.getElementById('edit_sermon_id').value = id;
            document.getElementById('edit_title').value = title;
            document.getElementById('edit_speaker').value = speaker;
            document.getElementById('edit_series').value = series;
            document.getElementById('edit_description').value = description;
            document.getElementById('edit_sermon_date').value = sermonDate;
            document.getElementById('edit_scripture').value = scripture;
            document.getElementById('edit_duration').value = duration;
            
            new bootstrap.Modal(document.getElementById('editSermonModal')).show();
        }

        // File upload drag and drop functionality
        function setupFileUpload(areaId, inputId, displayId) {
            const area = document.getElementById(areaId);
            const input = document.getElementById(inputId);
            const display = document.getElementById(displayId);

            area.addEventListener('dragover', (e) =&gt; {
                e.preventDefault();
                area.classList.add('dragover');
            });

            area.addEventListener('dragleave', () =&gt; {
                area.classList.remove('dragover');
            });

            area.addEventListener('drop', (e) =&gt; {
                e.preventDefault();
                area.classList.remove('dragover');
                const files = e.dataTransfer.files;
                if (files.length &gt; 0) {
                    input.files = files;
                    display.textContent = files[0].name;
                }
            });

            input.addEventListener('change', () =&gt; {
                if (input.files.length &gt; 0) {
                    display.textContent = input.files[0].name;
                } else {
                    display.textContent = '';
                }
            });
        }

        setupFileUpload('audioUploadArea', 'audio_file', 'audioFileName');
        setupFileUpload('videoUploadArea', 'video_file', 'videoFileName');
    &lt;/script&gt;
&lt;/body&gt;
&lt;/html&gt;