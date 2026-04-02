<?php
session_start();
require_once 'db.php';

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

// Handle form submissions
$message = '';
$message_type = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];

        if ($action === 'create_ministry') {
            $name = trim($_POST['name']);
            $description = trim($_POST['description']);
            $leader = trim($_POST['leader']);
            $contact_email = trim($_POST['contact_email']);
            $meeting_schedule = trim($_POST['meeting_schedule']);
            $icon = trim($_POST['icon']);

            if (empty($name) || empty($description)) {
                $message = 'Ministry name and description are required.';
                $message_type = 'error';
            } else {
                $stmt = $db->prepare("INSERT INTO ministries (name, description, leader, contact_email, meeting_schedule, icon, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())");
                $stmt->bind_param('ssssss', $name, $description, $leader, $contact_email, $meeting_schedule, $icon);
                if ($stmt->execute()) {
                    $message = 'Ministry created successfully.';
                    $message_type = 'success';
                } else {
                    $message = 'Error creating ministry.';
                    $message_type = 'error';
                }
            }
        } elseif ($action === 'update_ministry') {
            $id = intval($_POST['ministry_id']);
            $name = trim($_POST['name']);
            $description = trim($_POST['description']);
            $leader = trim($_POST['leader']);
            $contact_email = trim($_POST['contact_email']);
            $meeting_schedule = trim($_POST['meeting_schedule']);
            $icon = trim($_POST['icon']);

            if (empty($name) || empty($description)) {
                $message = 'Ministry name and description are required.';
                $message_type = 'error';
            } else {
                $stmt = $db->prepare("UPDATE ministries SET name = ?, description = ?, leader = ?, contact_email = ?, meeting_schedule = ?, icon = ? WHERE id = ?");
                $stmt->bind_param('ssssssi', $name, $description, $leader, $contact_email, $meeting_schedule, $icon, $id);
                if ($stmt->execute()) {
                    $message = 'Ministry updated successfully.';
                    $message_type = 'success';
                } else {
                    $message = 'Error updating ministry.';
                    $message_type = 'error';
                }
            }
        } elseif ($action === 'delete_ministry') {
            $id = intval($_POST['ministry_id']);
            $stmt = $db->prepare("DELETE FROM ministries WHERE id = ?");
            $stmt->bind_param('i', $id);
            if ($stmt->execute()) {
                $message = 'Ministry deleted successfully.';
                $message_type = 'success';
            } else {
                $message = 'Error deleting ministry.';
                $message_type = 'error';
            }
        }
    }
}

// Get all ministries
$ministries_result = $db->query("SELECT * FROM ministries ORDER BY name ASC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ministry Management - Salem Dominion Ministries</title>
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
        .ministry-card {
            transition: transform 0.2s ease;
        }
        .ministry-card:hover {
            transform: translateY(-2px);
        }
        .modal-lg {
            max-width: 900px;
        }
        .icon-preview {
            font-size: 2rem;
            color: #6c757d;
            margin-bottom: 10px;
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
                        <a class="nav-link" href="admin_sermons.php">
                            <i class="fas fa-microphone"></i> Sermons
                        </a>
                        <a class="nav-link active" href="admin_ministries.php">
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
                    <h2><i class="fas fa-praying-hands text-primary"></i> Ministry Management</h2>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createMinistryModal">
                        <i class="fas fa-plus"></i> Add Ministry
                    </button>
                </div>

                <?php if ($message): ?>
                    <div class="alert alert-<?php echo $message_type === 'success' ? 'success' : 'danger'; ?> alert-dismissible fade show" role="alert">
                        <?php echo htmlspecialchars($message); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <!-- Ministries List -->
                <div class="row">
                    <?php if ($ministries_result->num_rows > 0): ?>
                        <?php while ($ministry = $ministries_result->fetch_assoc()): ?>
                            <div class="col-lg-6 mb-4">
                                <div class="card ministry-card h-100">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start mb-3">
                                            <div class="d-flex align-items-center">
                                                <?php if ($ministry['icon']): ?>
                                                    <i class="<?php echo htmlspecialchars($ministry['icon']); ?> fa-2x text-primary me-3"></i>
                                                <?php else: ?>
                                                    <i class="fas fa-praying-hands fa-2x text-primary me-3"></i>
                                                <?php endif; ?>
                                                <div>
                                                    <h5 class="card-title mb-0"><?php echo htmlspecialchars($ministry['name']); ?></h5>
                                                    <?php if ($ministry['leader']): ?>
                                                        <small class="text-muted">Led by <?php echo htmlspecialchars($ministry['leader']); ?></small>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                            <div class="btn-group" role="group">
                                                <button class="btn btn-outline-primary btn-sm" onclick="editMinistry(<?php echo $ministry['id']; ?>, '<?php echo htmlspecialchars(addslashes($ministry['name'])); ?>', '<?php echo htmlspecialchars(addslashes($ministry['description'])); ?>', '<?php echo htmlspecialchars(addslashes($ministry['leader'])); ?>', '<?php echo htmlspecialchars(addslashes($ministry['contact_email'])); ?>', '<?php echo htmlspecialchars(addslashes($ministry['meeting_schedule'])); ?>', '<?php echo htmlspecialchars(addslashes($ministry['icon'])); ?>')">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <form method="post" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this ministry?')">
                                                    <input type="hidden" name="action" value="delete_ministry">
                                                    <input type="hidden" name="ministry_id" value="<?php echo $ministry['id']; ?>">
                                                    <button type="submit" class="btn btn-outline-danger btn-sm">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                        <p class="card-text text-muted"><?php echo htmlspecialchars(substr($ministry['description'], 0, 150)) . (strlen($ministry['description']) > 150 ? '...' : ''); ?></p>
                                        <div class="row text-muted small">
                                            <div class="col-6">
                                                <?php if ($ministry['meeting_schedule']): ?>
                                                    <i class="fas fa-calendar"></i> <?php echo htmlspecialchars($ministry['meeting_schedule']); ?><br>
                                                <?php endif; ?>
                                                <?php if ($ministry['contact_email']): ?>
                                                    <i class="fas fa-envelope"></i> <?php echo htmlspecialchars($ministry['contact_email']); ?>
                                                <?php endif; ?>
                                            </div>
                                            <div class="col-6 text-end">
                                                <small>Created <?php echo date('M j, Y', strtotime($ministry['created_at'])); ?></small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <div class="col-12">
                            <div class="text-center py-5">
                                <i class="fas fa-praying-hands fa-3x text-muted mb-3"></i>
                                <h4 class="text-muted">No Ministries Yet</h4>
                                <p class="text-muted">Create your first ministry to get started.</p>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Create Ministry Modal -->
    <div class="modal fade" id="createMinistryModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-plus"></i> Create Ministry</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="post">
                    <div class="modal-body">
                        <input type="hidden" name="action" value="create_ministry">
                        <div class="row">
                            <div class="col-md-8 mb-3">
                                <label for="name" class="form-label">Ministry Name *</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="icon" class="form-label">Icon Class</label>
                                <input type="text" class="form-control" id="icon" name="icon" placeholder="fas fa-praying-hands">
                                <small class="form-text text-muted">FontAwesome icon class</small>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description *</label>
                            <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="leader" class="form-label">Ministry Leader</label>
                                <input type="text" class="form-control" id="leader" name="leader">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="contact_email" class="form-label">Contact Email</label>
                                <input type="email" class="form-control" id="contact_email" name="contact_email">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="meeting_schedule" class="form-label">Meeting Schedule</label>
                            <input type="text" class="form-control" id="meeting_schedule" name="meeting_schedule" placeholder="e.g., Every Sunday at 2 PM">
                        </div>
                        <div class="text-center">
                            <div class="icon-preview" id="iconPreview">
                                <i class="fas fa-praying-hands fa-3x"></i>
                            </div>
                            <small class="text-muted">Icon Preview</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Create Ministry</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Ministry Modal -->
    <div class="modal fade" id="editMinistryModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-edit"></i> Edit Ministry</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="post">
                    <div class="modal-body">
                        <input type="hidden" name="action" value="update_ministry">
                        <input type="hidden" name="ministry_id" id="edit_ministry_id">
                        <div class="row">
                            <div class="col-md-8 mb-3">
                                <label for="edit_name" class="form-label">Ministry Name *</label>
                                <input type="text" class="form-control" id="edit_name" name="name" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="edit_icon" class="form-label">Icon Class</label>
                                <input type="text" class="form-control" id="edit_icon" name="icon" placeholder="fas fa-praying-hands">
                                <small class="form-text text-muted">FontAwesome icon class</small>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="edit_description" class="form-label">Description *</label>
                            <textarea class="form-control" id="edit_description" name="description" rows="3" required></textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="edit_leader" class="form-label">Ministry Leader</label>
                                <input type="text" class="form-control" id="edit_leader" name="leader">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="edit_contact_email" class="form-label">Contact Email</label>
                                <input type="email" class="form-control" id="edit_contact_email" name="contact_email">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="edit_meeting_schedule" class="form-label">Meeting Schedule</label>
                            <input type="text" class="form-control" id="edit_meeting_schedule" name="meeting_schedule" placeholder="e.g., Every Sunday at 2 PM">
                        </div>
                        <div class="text-center">
                            <div class="icon-preview" id="editIconPreview">
                                <i class="fas fa-praying-hands fa-3x"></i>
                            </div>
                            <small class="text-muted">Icon Preview</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Update Ministry</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function editMinistry(id, name, description, leader, contactEmail, meetingSchedule, icon) {
            document.getElementById('edit_ministry_id').value = id;
            document.getElementById('edit_name').value = name;
            document.getElementById('edit_description').value = description;
            document.getElementById('edit_leader').value = leader;
            document.getElementById('edit_contact_email').value = contactEmail;
            document.getElementById('edit_meeting_schedule').value = meetingSchedule;
            document.getElementById('edit_icon').value = icon;
            
            updateIconPreview('edit_icon', 'editIconPreview');
            new bootstrap.Modal(document.getElementById('editMinistryModal')).show();
        }

        function updateIconPreview(inputId, previewId) {
            const input = document.getElementById(inputId);
            const preview = document.getElementById(previewId);
            
            input.addEventListener('input', function() {
                const iconClass = this.value || 'fas fa-praying-hands';
                preview.innerHTML = `<i class="${iconClass} fa-3x"></i>`;
            });
            
            // Initial preview
            const iconClass = input.value || 'fas fa-praying-hands';
            preview.innerHTML = `<i class="${iconClass} fa-3x"></i>`;
        }

        // Set up icon preview for create modal
        document.getElementById('icon').addEventListener('input', function() {
            const iconClass = this.value || 'fas fa-praying-hands';
            document.getElementById('iconPreview').innerHTML = `<i class="${iconClass} fa-3x"></i>`;
        });
    </script>
</body>
</html>