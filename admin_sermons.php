<?php
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

                $stmt = $db->prepare("INSERT INTO sermons (title, speaker, series, description, sermon_date, scripture, audio_file, video_file, duration, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");
                $stmt->bind_param('sssssssss', $title, $speaker, $series, $description, $sermon_date, $scripture, $audio_file, $video_file, $duration);
                if ($stmt->execute()) {
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
                $stmt = $db->prepare("UPDATE sermons SET title = ?, speaker = ?, series = ?, description = ?, sermon_date = ?, scripture = ?, duration = ? WHERE id = ?");
                $stmt->bind_param('sssssssi', $title, $speaker, $series, $description, $sermon_date, $scripture, $duration, $id);
                if ($stmt->execute()) {
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
            $stmt = $db->prepare("SELECT audio_file, video_file FROM sermons WHERE id = ?");
            $stmt->bind_param('i', $id);
            $stmt->execute();
            $result = $stmt->get_result();
            $sermon = $result->fetch_assoc();
            
            // Delete files
            if ($sermon['audio_file'] && file_exists($sermon['audio_file'])) {
                unlink($sermon['audio_file']);
            }
            if ($sermon['video_file'] && file_exists($sermon['video_file'])) {
                unlink($sermon['video_file']);
            }
            
            // Delete from database
            $stmt = $db->prepare("DELETE FROM sermons WHERE id = ?");
            $stmt->bind_param('i', $id);
            if ($stmt->execute()) {
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
$sermons_result = $db->query("SELECT * FROM sermons ORDER BY sermon_date DESC, created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sermon Management - Salem Dominion Ministries</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
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
                    <li class="nav-item"><a class="nav-link" href="dashboard.php">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 px-0">
                <div class="sidebar p-3">
                    <h6 class="text-white-50 mb-3">ADMIN PANEL</h6>
                    <nav class="nav flex-column">
                        <a class="nav-link" href="admin_dashboard.php">
                            <i class="fas fa-tachometer-alt"></i> Dashboard
                        </a>
                        <a class="nav-link" href="admin_users.php">
                            <i class="fas fa-users"></i> Users
                        </a>
                        <a class="nav-link" href="admin_news.php">
                            <i class="fas fa-newspaper"></i> News
                        </a>
                        <a class="nav-link" href="admin_events.php">
                            <i class="fas fa-calendar"></i> Events
                        </a>
                        <a class="nav-link active" href="admin_sermons.php">
                            <i class="fas fa-microphone"></i> Sermons
                        </a>
                        <a class="nav-link" href="admin_ministries.php">
                            <i class="fas fa-praying-hands"></i> Ministries
                        </a>
                        <a class="nav-link" href="admin_gallery.php">
                            <i class="fas fa-images"></i> Gallery
                        </a>
                        <a class="nav-link" href="admin_donations.php">
                            <i class="fas fa-hand-holding-heart"></i> Donations
                        </a>
                        <a class="nav-link" href="admin_prayer_requests.php">
                            <i class="fas fa-pray"></i> Prayer Requests
                        </a>
                    </nav>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 px-4 py-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2><i class="fas fa-microphone text-primary"></i> Sermon Management</h2>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#uploadSermonModal">
                        <i class="fas fa-upload"></i> Upload Sermon
                    </button>
                </div>

                <?php if ($message): ?>
                    <div class="alert alert-<?php echo $message_type === 'success' ? 'success' : 'danger'; ?> alert-dismissible fade show" role="alert">
                        <?php echo htmlspecialchars($message); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <!-- Sermons List -->
                <div class="row">
                    <?php if ($sermons_result->num_rows > 0): ?>
                        <?php while ($sermon = $sermons_result->fetch_assoc()): ?>
                            <div class="col-lg-6 mb-4">
                                <div class="card sermon-card h-100">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <h5 class="card-title mb-0"><?php echo htmlspecialchars($sermon['title']); ?></h5>
                                            <div class="btn-group" role="group">
                                                <button class="btn btn-outline-primary btn-sm" onclick="editSermon(<?php echo $sermon['id']; ?>, '<?php echo htmlspecialchars(addslashes($sermon['title'])); ?>', '<?php echo htmlspecialchars(addslashes($sermon['speaker'])); ?>', '<?php echo htmlspecialchars(addslashes($sermon['series'])); ?>', '<?php echo htmlspecialchars(addslashes($sermon['description'])); ?>', '<?php echo $sermon['sermon_date']; ?>', '<?php echo htmlspecialchars(addslashes($sermon['scripture'])); ?>', '<?php echo htmlspecialchars(addslashes($sermon['duration'])); ?>')">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <form method="post" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this sermon? This will also delete the associated media files.')">
                                                    <input type="hidden" name="action" value="delete_sermon">
                                                    <input type="hidden" name="sermon_id" value="<?php echo $sermon['id']; ?>">
                                                    <button type="submit" class="btn btn-outline-danger btn-sm">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                        <div class="mb-2">
                                            <small class="text-muted">
                                                <i class="fas fa-user-tie"></i> <?php echo htmlspecialchars($sermon['speaker']); ?>
                                                <?php if ($sermon['series']): ?>
                                                    | <i class="fas fa-list"></i> <?php echo htmlspecialchars($sermon['series']); ?>
                                                <?php endif; ?>
                                            </small>
                                        </div>
                                        <p class="card-text text-muted small"><?php echo htmlspecialchars(substr($sermon['description'], 0, 100)) . (strlen($sermon['description']) > 100 ? '...' : ''); ?></p>
                                        <div class="row text-muted small">
                                            <div class="col-6">
                                                <i class="fas fa-calendar"></i> <?php echo date('M j, Y', strtotime($sermon['sermon_date'])); ?><br>
                                                <?php if ($sermon['scripture']): ?>
                                                    <i class="fas fa-book"></i> <?php echo htmlspecialchars($sermon['scripture']); ?>
                                                <?php endif; ?>
                                            </div>
                                            <div class="col-6">
                                                <?php if ($sermon['duration']): ?>
                                                    <i class="fas fa-clock"></i> <?php echo htmlspecialchars($sermon['duration']); ?><br>
                                                <?php endif; ?>
                                                <i class="fas fa-eye"></i> <?php echo $sermon['views']; ?> views
                                            </div>
                                        </div>
                                        <div class="mt-3">
                                            <?php if ($sermon['audio_file']): ?>
                                                <span class="badge bg-success me-1"><i class="fas fa-volume-up"></i> Audio</span>
                                            <?php endif; ?>
                                            <?php if ($sermon['video_file']): ?>
                                                <span class="badge bg-info"><i class="fas fa-video"></i> Video</span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <div class="col-12">
                            <div class="text-center py-5">
                                <i class="fas fa-microphone fa-3x text-muted mb-3"></i>
                                <h4 class="text-muted">No Sermons Yet</h4>
                                <p class="text-muted">Upload your first sermon to get started.</p>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Upload Sermon Modal -->
    <div class="modal fade" id="uploadSermonModal" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-upload"></i> Upload Sermon</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="post" enctype="multipart/form-data">
                    <div class="modal-body">
                        <input type="hidden" name="action" value="upload_sermon">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="title" class="form-label">Sermon Title *</label>
                                        <input type="text" class="form-control" id="title" name="title" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="speaker" class="form-label">Speaker *</label>
                                        <input type="text" class="form-control" id="speaker" name="speaker" required>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="series" class="form-label">Series (Optional)</label>
                                        <input type="text" class="form-control" id="series" name="series">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="sermon_date" class="form-label">Sermon Date *</label>
                                        <input type="date" class="form-control" id="sermon_date" name="sermon_date" required>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="scripture" class="form-label">Scripture Reference</label>
                                        <input type="text" class="form-control" id="scripture" name="scripture" placeholder="e.g., John 3:16">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="duration" class="form-label">Duration</label>
                                        <input type="text" class="form-control" id="duration" name="duration" placeholder="e.g., 45 minutes">
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Audio File (MP3, etc.)</label>
                                    <div class="file-upload-area" id="audioUploadArea">
                                        <i class="fas fa-volume-up fa-2x text-muted mb-2"></i>
                                        <p class="mb-1">Drag & drop audio file here</p>
                                        <p class="small text-muted">or</p>
                                        <input type="file" class="form-control" id="audio_file" name="audio_file" accept="audio/*" style="display: none;">
                                        <button type="button" class="btn btn-outline-primary btn-sm" onclick="document.getElementById('audio_file').click()">Choose File</button>
                                        <div id="audioFileName" class="mt-2 small text-success"></div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Video File (MP4, etc.)</label>
                                    <div class="file-upload-area" id="videoUploadArea">
                                        <i class="fas fa-video fa-2x text-muted mb-2"></i>
                                        <p class="mb-1">Drag & drop video file here</p>
                                        <p class="small text-muted">or</p>
                                        <input type="file" class="form-control" id="video_file" name="video_file" accept="video/*" style="display: none;">
                                        <button type="button" class="btn btn-outline-primary btn-sm" onclick="document.getElementById('video_file').click()">Choose File</button>
                                        <div id="videoFileName" class="mt-2 small text-success"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-upload"></i> Upload Sermon</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Sermon Modal -->
    <div class="modal fade" id="editSermonModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-edit"></i> Edit Sermon</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="post">
                    <div class="modal-body">
                        <input type="hidden" name="action" value="update_sermon">
                        <input type="hidden" name="sermon_id" id="edit_sermon_id">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="edit_title" class="form-label">Sermon Title *</label>
                                <input type="text" class="form-control" id="edit_title" name="title" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="edit_speaker" class="form-label">Speaker *</label>
                                <input type="text" class="form-control" id="edit_speaker" name="speaker" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="edit_series" class="form-label">Series (Optional)</label>
                                <input type="text" class="form-control" id="edit_series" name="series">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="edit_sermon_date" class="form-label">Sermon Date *</label>
                                <input type="date" class="form-control" id="edit_sermon_date" name="sermon_date" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="edit_scripture" class="form-label">Scripture Reference</label>
                                <input type="text" class="form-control" id="edit_scripture" name="scripture" placeholder="e.g., John 3:16">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="edit_duration" class="form-label">Duration</label>
                                <input type="text" class="form-control" id="edit_duration" name="duration" placeholder="e.g., 45 minutes">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="edit_description" class="form-label">Description</label>
                            <textarea class="form-control" id="edit_description" name="description" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Update Sermon</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
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

            area.addEventListener('dragover', (e) => {
                e.preventDefault();
                area.classList.add('dragover');
            });

            area.addEventListener('dragleave', () => {
                area.classList.remove('dragover');
            });

            area.addEventListener('drop', (e) => {
                e.preventDefault();
                area.classList.remove('dragover');
                const files = e.dataTransfer.files;
                if (files.length > 0) {
                    input.files = files;
                    display.textContent = files[0].name;
                }
            });

            input.addEventListener('change', () => {
                if (input.files.length > 0) {
                    display.textContent = input.files[0].name;
                } else {
                    display.textContent = '';
                }
            });
        }

        setupFileUpload('audioUploadArea', 'audio_file', 'audioFileName');
        setupFileUpload('videoUploadArea', 'video_file', 'videoFileName');
    </script>
</body>
</html>