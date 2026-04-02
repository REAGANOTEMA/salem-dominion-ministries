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

        if ($action === 'create_event') {
            $title = trim($_POST['title']);
            $description = trim($_POST['description']);
            $event_date = $_POST['event_date'];
            $event_time = $_POST['event_time'];
            $location = trim($_POST['location']);
            $max_attendees = intval($_POST['max_attendees']);
            $registration_deadline = $_POST['registration_deadline'];

            if (empty($title) || empty($event_date) || empty($location)) {
                $message = 'Title, date, and location are required.';
                $message_type = 'error';
            } else {
                $stmt = $db->prepare("INSERT INTO events (title, description, event_date, event_time, location, max_attendees, registration_deadline, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())");
                $stmt->bind_param('sssssis', $title, $description, $event_date, $event_time, $location, $max_attendees, $registration_deadline);
                if ($stmt->execute()) {
                    $message = 'Event created successfully.';
                    $message_type = 'success';
                } else {
                    $message = 'Error creating event.';
                    $message_type = 'error';
                }
            }
        } elseif ($action === 'update_event') {
            $id = intval($_POST['event_id']);
            $title = trim($_POST['title']);
            $description = trim($_POST['description']);
            $event_date = $_POST['event_date'];
            $event_time = $_POST['event_time'];
            $location = trim($_POST['location']);
            $max_attendees = intval($_POST['max_attendees']);
            $registration_deadline = $_POST['registration_deadline'];

            if (empty($title) || empty($event_date) || empty($location)) {
                $message = 'Title, date, and location are required.';
                $message_type = 'error';
            } else {
                $stmt = $db->prepare("UPDATE events SET title = ?, description = ?, event_date = ?, event_time = ?, location = ?, max_attendees = ?, registration_deadline = ? WHERE id = ?");
                $stmt->bind_param('sssssisi', $title, $description, $event_date, $event_time, $location, $max_attendees, $registration_deadline, $id);
                if ($stmt->execute()) {
                    $message = 'Event updated successfully.';
                    $message_type = 'success';
                } else {
                    $message = 'Error updating event.';
                    $message_type = 'error';
                }
            }
        } elseif ($action === 'delete_event') {
            $id = intval($_POST['event_id']);
            // First delete registrations
            $db->query("DELETE FROM event_registrations WHERE event_id = $id");
            // Then delete event
            $stmt = $db->prepare("DELETE FROM events WHERE id = ?");
            $stmt->bind_param('i', $id);
            if ($stmt->execute()) {
                $message = 'Event deleted successfully.';
                $message_type = 'success';
            } else {
                $message = 'Error deleting event.';
                $message_type = 'error';
            }
        } elseif ($action === 'remove_registration') {
            $registration_id = intval($_POST['registration_id']);
            $stmt = $db->prepare("DELETE FROM event_registrations WHERE id = ?");
            $stmt->bind_param('i', $registration_id);
            if ($stmt->execute()) {
                $message = 'Registration removed successfully.';
                $message_type = 'success';
            } else {
                $message = 'Error removing registration.';
                $message_type = 'error';
            }
        }
    }
}

// Get all events with registration counts
$events_result = $db->query("
    SELECT e.*,
           COUNT(er.id) as registered_count
    FROM events e
    LEFT JOIN event_registrations er ON e.id = er.event_id
    GROUP BY e.id
    ORDER BY e.event_date ASC
");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Management - Salem Dominion Ministries</title>
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
        .event-card {
            transition: transform 0.2s ease;
        }
        .event-card:hover {
            transform: translateY(-2px);
        }
        .modal-lg {
            max-width: 900px;
        }
        .registration-list {
            max-height: 300px;
            overflow-y: auto;
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
                        <a class="nav-link active" href="admin_events.php">
                            <i class="fas fa-calendar"></i> Events
                        </a>
                        <a class="nav-link" href="admin_sermons.php">
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
                    <h2><i class="fas fa-calendar text-primary"></i> Event Management</h2>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createEventModal">
                        <i class="fas fa-plus"></i> Create Event
                    </button>
                </div>

                <?php if ($message): ?>
                    <div class="alert alert-<?php echo $message_type === 'success' ? 'success' : 'danger'; ?> alert-dismissible fade show" role="alert">
                        <?php echo htmlspecialchars($message); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <!-- Events List -->
                <div class="row">
                    <?php if ($events_result->num_rows > 0): ?>
                        <?php while ($event = $events_result->fetch_assoc()): ?>
                            <div class="col-lg-6 mb-4">
                                <div class="card event-card h-100">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <h5 class="card-title mb-0"><?php echo htmlspecialchars($event['title']); ?></h5>
                                            <div class="btn-group" role="group">
                                                <button class="btn btn-outline-primary btn-sm" onclick="editEvent(<?php echo $event['id']; ?>, '<?php echo htmlspecialchars(addslashes($event['title'])); ?>', '<?php echo htmlspecialchars(addslashes($event['description'])); ?>', '<?php echo $event['event_date']; ?>', '<?php echo $event['event_time']; ?>', '<?php echo htmlspecialchars(addslashes($event['location'])); ?>', <?php echo $event['max_attendees']; ?>, '<?php echo $event['registration_deadline']; ?>')">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="btn btn-outline-info btn-sm" onclick="viewRegistrations(<?php echo $event['id']; ?>, '<?php echo htmlspecialchars(addslashes($event['title'])); ?>')">
                                                    <i class="fas fa-users"></i>
                                                </button>
                                                <form method="post" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this event? This will also remove all registrations.')">
                                                    <input type="hidden" name="action" value="delete_event">
                                                    <input type="hidden" name="event_id" value="<?php echo $event['id']; ?>">
                                                    <button type="submit" class="btn btn-outline-danger btn-sm">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                        <p class="card-text text-muted"><?php echo htmlspecialchars(substr($event['description'], 0, 100)) . (strlen($event['description']) > 100 ? '...' : ''); ?></p>
                                        <div class="row text-muted small">
                                            <div class="col-6">
                                                <i class="fas fa-calendar"></i> <?php echo date('M j, Y', strtotime($event['event_date'])); ?><br>
                                                <i class="fas fa-clock"></i> <?php echo $event['event_time'] ?: 'TBD'; ?>
                                            </div>
                                            <div class="col-6">
                                                <i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($event['location']); ?><br>
                                                <i class="fas fa-users"></i> <?php echo $event['registered_count']; ?>/<?php echo $event['max_attendees'] ?: 'âˆž'; ?> registered
                                            </div>
                                        </div>
                                        <?php if ($event['registration_deadline']): ?>
                                            <div class="mt-2">
                                                <small class="text-muted">
                                                    Registration deadline: <?php echo date('M j, Y', strtotime($event['registration_deadline'])); ?>
                                                </small>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <div class="col-12">
                            <div class="text-center py-5">
                                <i class="fas fa-calendar fa-3x text-muted mb-3"></i>
                                <h4 class="text-muted">No Events Yet</h4>
                                <p class="text-muted">Create your first event to get started.</p>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Create Event Modal -->
    <div class="modal fade" id="createEventModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-plus"></i> Create Event</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="post">
                    <div class="modal-body">
                        <input type="hidden" name="action" value="create_event">
                        <div class="row">
                            <div class="col-md-8 mb-3">
                                <label for="title" class="form-label">Event Title *</label>
                                <input type="text" class="form-control" id="title" name="title" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="max_attendees" class="form-label">Max Attendees</label>
                                <input type="number" class="form-control" id="max_attendees" name="max_attendees" placeholder="Unlimited">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="event_date" class="form-label">Event Date *</label>
                                <input type="date" class="form-control" id="event_date" name="event_date" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="event_time" class="form-label">Event Time</label>
                                <input type="time" class="form-control" id="event_time" name="event_time">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="location" class="form-label">Location *</label>
                                <input type="text" class="form-control" id="location" name="location" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="registration_deadline" class="form-label">Registration Deadline</label>
                                <input type="date" class="form-control" id="registration_deadline" name="registration_deadline">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Create Event</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Event Modal -->
    <div class="modal fade" id="editEventModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-edit"></i> Edit Event</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="post">
                    <div class="modal-body">
                        <input type="hidden" name="action" value="update_event">
                        <input type="hidden" name="event_id" id="edit_event_id">
                        <div class="row">
                            <div class="col-md-8 mb-3">
                                <label for="edit_title" class="form-label">Event Title *</label>
                                <input type="text" class="form-control" id="edit_title" name="title" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="edit_max_attendees" class="form-label">Max Attendees</label>
                                <input type="number" class="form-control" id="edit_max_attendees" name="max_attendees" placeholder="Unlimited">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="edit_description" class="form-label">Description</label>
                            <textarea class="form-control" id="edit_description" name="description" rows="3"></textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="edit_event_date" class="form-label">Event Date *</label>
                                <input type="date" class="form-control" id="edit_event_date" name="event_date" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="edit_event_time" class="form-label">Event Time</label>
                                <input type="time" class="form-control" id="edit_event_time" name="event_time">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="edit_location" class="form-label">Location *</label>
                                <input type="text" class="form-control" id="edit_location" name="location" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="edit_registration_deadline" class="form-label">Registration Deadline</label>
                                <input type="date" class="form-control" id="edit_registration_deadline" name="registration_deadline">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Update Event</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Registrations Modal -->
    <div class="modal fade" id="registrationsModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-users"></i> Event Registrations</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <h6 id="eventTitle"></h6>
                    <div id="registrationsList" class="registration-list">
                        <!-- Registrations will be loaded here -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function editEvent(id, title, description, eventDate, eventTime, location, maxAttendees, registrationDeadline) {
            document.getElementById('edit_event_id').value = id;
            document.getElementById('edit_title').value = title;
            document.getElementById('edit_description').value = description;
            document.getElementById('edit_event_date').value = eventDate;
            document.getElementById('edit_event_time').value = eventTime;
            document.getElementById('edit_location').value = location;
            document.getElementById('edit_max_attendees').value = maxAttendees;
            document.getElementById('edit_registration_deadline').value = registrationDeadline;
            
            new bootstrap.Modal(document.getElementById('editEventModal')).show();
        }

        function viewRegistrations(eventId, eventTitle) {
            document.getElementById('eventTitle').textContent = eventTitle;
            
            // Load registrations via AJAX
            fetch('admin_events.php?action=get_registrations&event_id=' + eventId)
                .then(response => response.text())
                .then(data => {
                    document.getElementById('registrationsList').innerHTML = data;
                });
            
            new bootstrap.Modal(document.getElementById('registrationsModal')).show();
        }

        // Handle AJAX request for registrations
        if (window.location.search.includes('action=get_registrations')) {
            const urlParams = new URLSearchParams(window.location.search);
            const eventId = urlParams.get('event_id');
            
            if (eventId) {
                // This would be handled by PHP, but for now we'll show a placeholder
                document.write('<div class="text-center py-3"><i class="fas fa-spinner fa-spin"></i> Loading registrations...</div>');
            }
        }
    </script>
</body>
</html>