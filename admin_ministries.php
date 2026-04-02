&lt;?php
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
                $stmt = $db-&gt;prepare("INSERT INTO ministries (name, description, leader, contact_email, meeting_schedule, icon, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())");
                $stmt-&gt;bind_param('ssssss', $name, $description, $leader, $contact_email, $meeting_schedule, $icon);
                if ($stmt-&gt;execute()) {
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
                $stmt = $db-&gt;prepare("UPDATE ministries SET name = ?, description = ?, leader = ?, contact_email = ?, meeting_schedule = ?, icon = ? WHERE id = ?");
                $stmt-&gt;bind_param('ssssssi', $name, $description, $leader, $contact_email, $meeting_schedule, $icon, $id);
                if ($stmt-&gt;execute()) {
                    $message = 'Ministry updated successfully.';
                    $message_type = 'success';
                } else {
                    $message = 'Error updating ministry.';
                    $message_type = 'error';
                }
            }
        } elseif ($action === 'delete_ministry') {
            $id = intval($_POST['ministry_id']);
            $stmt = $db-&gt;prepare("DELETE FROM ministries WHERE id = ?");
            $stmt-&gt;bind_param('i', $id);
            if ($stmt-&gt;execute()) {
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
$ministries_result = $db-&gt;query("SELECT * FROM ministries ORDER BY name ASC");
?&gt;
&lt;!DOCTYPE html&gt;
&lt;html lang="en"&gt;
&lt;head&gt;
    &lt;meta charset="UTF-8"&gt;
    &lt;meta name="viewport" content="width=device-width, initial-scale=1.0"&gt;
    &lt;title&gt;Ministry Management - Salem Dominion Ministries&lt;/title&gt;
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
                        &lt;a class="nav-link" href="admin_sermons.php"&gt;
                            &lt;i class="fas fa-microphone"&gt;&lt;/i&gt; Sermons
                        &lt;/a&gt;
                        &lt;a class="nav-link active" href="admin_ministries.php"&gt;
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
                    &lt;h2&gt;&lt;i class="fas fa-praying-hands text-primary"&gt;&lt;/i&gt; Ministry Management&lt;/h2&gt;
                    &lt;button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createMinistryModal"&gt;
                        &lt;i class="fas fa-plus"&gt;&lt;/i&gt; Add Ministry
                    &lt;/button&gt;
                &lt;/div&gt;

                &lt;?php if ($message): ?&gt;
                    &lt;div class="alert alert-&lt;?php echo $message_type === 'success' ? 'success' : 'danger'; ?&gt; alert-dismissible fade show" role="alert"&gt;
                        &lt;?php echo htmlspecialchars($message); ?&gt;
                        &lt;button type="button" class="btn-close" data-bs-dismiss="alert"&gt;&lt;/button&gt;
                    &lt;/div&gt;
                &lt;?php endif; ?&gt;

                &lt;!-- Ministries List --&gt;
                &lt;div class="row"&gt;
                    &lt;?php if ($ministries_result-&gt;num_rows &gt; 0): ?&gt;
                        &lt;?php while ($ministry = $ministries_result-&gt;fetch_assoc()): ?&gt;
                            &lt;div class="col-lg-6 mb-4"&gt;
                                &lt;div class="card ministry-card h-100"&gt;
                                    &lt;div class="card-body"&gt;
                                        &lt;div class="d-flex justify-content-between align-items-start mb-3"&gt;
                                            &lt;div class="d-flex align-items-center"&gt;
                                                &lt;?php if ($ministry['icon']): ?&gt;
                                                    &lt;i class="&lt;?php echo htmlspecialchars($ministry['icon']); ?&gt; fa-2x text-primary me-3"&gt;&lt;/i&gt;
                                                &lt;?php else: ?&gt;
                                                    &lt;i class="fas fa-praying-hands fa-2x text-primary me-3"&gt;&lt;/i&gt;
                                                &lt;?php endif; ?&gt;
                                                &lt;div&gt;
                                                    &lt;h5 class="card-title mb-0"&gt;&lt;?php echo htmlspecialchars($ministry['name']); ?&gt;&lt;/h5&gt;
                                                    &lt;?php if ($ministry['leader']): ?&gt;
                                                        &lt;small class="text-muted"&gt;Led by &lt;?php echo htmlspecialchars($ministry['leader']); ?&gt;&lt;/small&gt;
                                                    &lt;?php endif; ?&gt;
                                                &lt;/div&gt;
                                            &lt;/div&gt;
                                            &lt;div class="btn-group" role="group"&gt;
                                                &lt;button class="btn btn-outline-primary btn-sm" onclick="editMinistry(&lt;?php echo $ministry['id']; ?&gt;, '&lt;?php echo htmlspecialchars(addslashes($ministry['name'])); ?&gt;', '&lt;?php echo htmlspecialchars(addslashes($ministry['description'])); ?&gt;', '&lt;?php echo htmlspecialchars(addslashes($ministry['leader'])); ?&gt;', '&lt;?php echo htmlspecialchars(addslashes($ministry['contact_email'])); ?&gt;', '&lt;?php echo htmlspecialchars(addslashes($ministry['meeting_schedule'])); ?&gt;', '&lt;?php echo htmlspecialchars(addslashes($ministry['icon'])); ?&gt;')"&gt;
                                                    &lt;i class="fas fa-edit"&gt;&lt;/i&gt;
                                                &lt;/button&gt;
                                                &lt;form method="post" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this ministry?')"&gt;
                                                    &lt;input type="hidden" name="action" value="delete_ministry"&gt;
                                                    &lt;input type="hidden" name="ministry_id" value="&lt;?php echo $ministry['id']; ?&gt;"&gt;
                                                    &lt;button type="submit" class="btn btn-outline-danger btn-sm"&gt;
                                                        &lt;i class="fas fa-trash"&gt;&lt;/i&gt;
                                                    &lt;/button&gt;
                                                &lt;/form&gt;
                                            &lt;/div&gt;
                                        &lt;/div&gt;
                                        &lt;p class="card-text text-muted"&gt;&lt;?php echo htmlspecialchars(substr($ministry['description'], 0, 150)) . (strlen($ministry['description']) &gt; 150 ? '...' : ''); ?&gt;&lt;/p&gt;
                                        &lt;div class="row text-muted small"&gt;
                                            &lt;div class="col-6"&gt;
                                                &lt;?php if ($ministry['meeting_schedule']): ?&gt;
                                                    &lt;i class="fas fa-calendar"&gt;&lt;/i&gt; &lt;?php echo htmlspecialchars($ministry['meeting_schedule']); ?&gt;&lt;br&gt;
                                                &lt;?php endif; ?&gt;
                                                &lt;?php if ($ministry['contact_email']): ?&gt;
                                                    &lt;i class="fas fa-envelope"&gt;&lt;/i&gt; &lt;?php echo htmlspecialchars($ministry['contact_email']); ?&gt;
                                                &lt;?php endif; ?&gt;
                                            &lt;/div&gt;
                                            &lt;div class="col-6 text-end"&gt;
                                                &lt;small&gt;Created &lt;?php echo date('M j, Y', strtotime($ministry['created_at'])); ?&gt;&lt;/small&gt;
                                            &lt;/div&gt;
                                        &lt;/div&gt;
                                    &lt;/div&gt;
                                &lt;/div&gt;
                            &lt;/div&gt;
                        &lt;?php endwhile; ?&gt;
                    &lt;?php else: ?&gt;
                        &lt;div class="col-12"&gt;
                            &lt;div class="text-center py-5"&gt;
                                &lt;i class="fas fa-praying-hands fa-3x text-muted mb-3"&gt;&lt;/i&gt;
                                &lt;h4 class="text-muted"&gt;No Ministries Yet&lt;/h4&gt;
                                &lt;p class="text-muted"&gt;Create your first ministry to get started.&lt;/p&gt;
                            &lt;/div&gt;
                        &lt;/div&gt;
                    &lt;?php endif; ?&gt;
                &lt;/div&gt;
            &lt;/div&gt;
        &lt;/div&gt;
    &lt;/div&gt;

    &lt;!-- Create Ministry Modal --&gt;
    &lt;div class="modal fade" id="createMinistryModal" tabindex="-1"&gt;
        &lt;div class="modal-dialog modal-lg"&gt;
            &lt;div class="modal-content"&gt;
                &lt;div class="modal-header"&gt;
                    &lt;h5 class="modal-title"&gt;&lt;i class="fas fa-plus"&gt;&lt;/i&gt; Create Ministry&lt;/h5&gt;
                    &lt;button type="button" class="btn-close" data-bs-dismiss="modal"&gt;&lt;/button&gt;
                &lt;/div&gt;
                &lt;form method="post"&gt;
                    &lt;div class="modal-body"&gt;
                        &lt;input type="hidden" name="action" value="create_ministry"&gt;
                        &lt;div class="row"&gt;
                            &lt;div class="col-md-8 mb-3"&gt;
                                &lt;label for="name" class="form-label"&gt;Ministry Name *&lt;/label&gt;
                                &lt;input type="text" class="form-control" id="name" name="name" required&gt;
                            &lt;/div&gt;
                            &lt;div class="col-md-4 mb-3"&gt;
                                &lt;label for="icon" class="form-label"&gt;Icon Class&lt;/label&gt;
                                &lt;input type="text" class="form-control" id="icon" name="icon" placeholder="fas fa-praying-hands"&gt;
                                &lt;small class="form-text text-muted"&gt;FontAwesome icon class&lt;/small&gt;
                            &lt;/div&gt;
                        &lt;/div&gt;
                        &lt;div class="mb-3"&gt;
                            &lt;label for="description" class="form-label"&gt;Description *&lt;/label&gt;
                            &lt;textarea class="form-control" id="description" name="description" rows="3" required&gt;&lt;/textarea&gt;
                        &lt;/div&gt;
                        &lt;div class="row"&gt;
                            &lt;div class="col-md-6 mb-3"&gt;
                                &lt;label for="leader" class="form-label"&gt;Ministry Leader&lt;/label&gt;
                                &lt;input type="text" class="form-control" id="leader" name="leader"&gt;
                            &lt;/div&gt;
                            &lt;div class="col-md-6 mb-3"&gt;
                                &lt;label for="contact_email" class="form-label"&gt;Contact Email&lt;/label&gt;
                                &lt;input type="email" class="form-control" id="contact_email" name="contact_email"&gt;
                            &lt;/div&gt;
                        &lt;/div&gt;
                        &lt;div class="mb-3"&gt;
                            &lt;label for="meeting_schedule" class="form-label"&gt;Meeting Schedule&lt;/label&gt;
                            &lt;input type="text" class="form-control" id="meeting_schedule" name="meeting_schedule" placeholder="e.g., Every Sunday at 2 PM"&gt;
                        &lt;/div&gt;
                        &lt;div class="text-center"&gt;
                            &lt;div class="icon-preview" id="iconPreview"&gt;
                                &lt;i class="fas fa-praying-hands fa-3x"&gt;&lt;/i&gt;
                            &lt;/div&gt;
                            &lt;small class="text-muted"&gt;Icon Preview&lt;/small&gt;
                        &lt;/div&gt;
                    &lt;/div&gt;
                    &lt;div class="modal-footer"&gt;
                        &lt;button type="button" class="btn btn-secondary" data-bs-dismiss="modal"&gt;Cancel&lt;/button&gt;
                        &lt;button type="submit" class="btn btn-primary"&gt;&lt;i class="fas fa-save"&gt;&lt;/i&gt; Create Ministry&lt;/button&gt;
                    &lt;/div&gt;
                &lt;/form&gt;
            &lt;/div&gt;
        &lt;/div&gt;
    &lt;/div&gt;

    &lt;!-- Edit Ministry Modal --&gt;
    &lt;div class="modal fade" id="editMinistryModal" tabindex="-1"&gt;
        &lt;div class="modal-dialog modal-lg"&gt;
            &lt;div class="modal-content"&gt;
                &lt;div class="modal-header"&gt;
                    &lt;h5 class="modal-title"&gt;&lt;i class="fas fa-edit"&gt;&lt;/i&gt; Edit Ministry&lt;/h5&gt;
                    &lt;button type="button" class="btn-close" data-bs-dismiss="modal"&gt;&lt;/button&gt;
                &lt;/div&gt;
                &lt;form method="post"&gt;
                    &lt;div class="modal-body"&gt;
                        &lt;input type="hidden" name="action" value="update_ministry"&gt;
                        &lt;input type="hidden" name="ministry_id" id="edit_ministry_id"&gt;
                        &lt;div class="row"&gt;
                            &lt;div class="col-md-8 mb-3"&gt;
                                &lt;label for="edit_name" class="form-label"&gt;Ministry Name *&lt;/label&gt;
                                &lt;input type="text" class="form-control" id="edit_name" name="name" required&gt;
                            &lt;/div&gt;
                            &lt;div class="col-md-4 mb-3"&gt;
                                &lt;label for="edit_icon" class="form-label"&gt;Icon Class&lt;/label&gt;
                                &lt;input type="text" class="form-control" id="edit_icon" name="icon" placeholder="fas fa-praying-hands"&gt;
                                &lt;small class="form-text text-muted"&gt;FontAwesome icon class&lt;/small&gt;
                            &lt;/div&gt;
                        &lt;/div&gt;
                        &lt;div class="mb-3"&gt;
                            &lt;label for="edit_description" class="form-label"&gt;Description *&lt;/label&gt;
                            &lt;textarea class="form-control" id="edit_description" name="description" rows="3" required&gt;&lt;/textarea&gt;
                        &lt;/div&gt;
                        &lt;div class="row"&gt;
                            &lt;div class="col-md-6 mb-3"&gt;
                                &lt;label for="edit_leader" class="form-label"&gt;Ministry Leader&lt;/label&gt;
                                &lt;input type="text" class="form-control" id="edit_leader" name="leader"&gt;
                            &lt;/div&gt;
                            &lt;div class="col-md-6 mb-3"&gt;
                                &lt;label for="edit_contact_email" class="form-label"&gt;Contact Email&lt;/label&gt;
                                &lt;input type="email" class="form-control" id="edit_contact_email" name="contact_email"&gt;
                            &lt;/div&gt;
                        &lt;/div&gt;
                        &lt;div class="mb-3"&gt;
                            &lt;label for="edit_meeting_schedule" class="form-label"&gt;Meeting Schedule&lt;/label&gt;
                            &lt;input type="text" class="form-control" id="edit_meeting_schedule" name="meeting_schedule" placeholder="e.g., Every Sunday at 2 PM"&gt;
                        &lt;/div&gt;
                        &lt;div class="text-center"&gt;
                            &lt;div class="icon-preview" id="editIconPreview"&gt;
                                &lt;i class="fas fa-praying-hands fa-3x"&gt;&lt;/i&gt;
                            &lt;/div&gt;
                            &lt;small class="text-muted"&gt;Icon Preview&lt;/small&gt;
                        &lt;/div&gt;
                    &lt;/div&gt;
                    &lt;div class="modal-footer"&gt;
                        &lt;button type="button" class="btn btn-secondary" data-bs-dismiss="modal"&gt;Cancel&lt;/button&gt;
                        &lt;button type="submit" class="btn btn-primary"&gt;&lt;i class="fas fa-save"&gt;&lt;/i&gt; Update Ministry&lt;/button&gt;
                    &lt;/div&gt;
                &lt;/form&gt;
            &lt;/div&gt;
        &lt;/div&gt;
    &lt;/div&gt;

    &lt;script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"&gt;&lt;/script&gt;
    &lt;script&gt;
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
                preview.innerHTML = `&lt;i class="${iconClass} fa-3x"&gt;&lt;/i&gt;`;
            });
            
            // Initial preview
            const iconClass = input.value || 'fas fa-praying-hands';
            preview.innerHTML = `&lt;i class="${iconClass} fa-3x"&gt;&lt;/i&gt;`;
        }

        // Set up icon preview for create modal
        document.getElementById('icon').addEventListener('input', function() {
            const iconClass = this.value || 'fas fa-praying-hands';
            document.getElementById('iconPreview').innerHTML = `&lt;i class="${iconClass} fa-3x"&gt;&lt;/i&gt;`;
        });
    &lt;/script&gt;
&lt;/body&gt;
&lt;/html&gt;