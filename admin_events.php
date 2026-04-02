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
                $stmt = $db-&gt;prepare("INSERT INTO events (title, description, event_date, event_time, location, max_attendees, registration_deadline, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())");
                $stmt-&gt;bind_param('sssssis', $title, $description, $event_date, $event_time, $location, $max_attendees, $registration_deadline);
                if ($stmt-&gt;execute()) {
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
                $stmt = $db-&gt;prepare("UPDATE events SET title = ?, description = ?, event_date = ?, event_time = ?, location = ?, max_attendees = ?, registration_deadline = ? WHERE id = ?");
                $stmt-&gt;bind_param('sssssisi', $title, $description, $event_date, $event_time, $location, $max_attendees, $registration_deadline, $id);
                if ($stmt-&gt;execute()) {
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
            $db-&gt;query("DELETE FROM event_registrations WHERE event_id = $id");
            // Then delete event
            $stmt = $db-&gt;prepare("DELETE FROM events WHERE id = ?");
            $stmt-&gt;bind_param('i', $id);
            if ($stmt-&gt;execute()) {
                $message = 'Event deleted successfully.';
                $message_type = 'success';
            } else {
                $message = 'Error deleting event.';
                $message_type = 'error';
            }
        } elseif ($action === 'remove_registration') {
            $registration_id = intval($_POST['registration_id']);
            $stmt = $db-&gt;prepare("DELETE FROM event_registrations WHERE id = ?");
            $stmt-&gt;bind_param('i', $registration_id);
            if ($stmt-&gt;execute()) {
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
$events_result = $db-&gt;query("
    SELECT e.*,
           COUNT(er.id) as registered_count
    FROM events e
    LEFT JOIN event_registrations er ON e.id = er.event_id
    GROUP BY e.id
    ORDER BY e.event_date ASC
");
?&gt;
&lt;!DOCTYPE html&gt;
&lt;html lang="en"&gt;
&lt;head&gt;
    &lt;meta charset="UTF-8"&gt;
    &lt;meta name="viewport" content="width=device-width, initial-scale=1.0"&gt;
    &lt;title&gt;Event Management - Salem Dominion Ministries&lt;/title&gt;
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
                        &lt;a class="nav-link active" href="admin_events.php"&gt;
                            &lt;i class="fas fa-calendar"&gt;&lt;/i&gt; Events
                        &lt;/a&gt;
                        &lt;a class="nav-link" href="admin_sermons.php"&gt;
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
                    &lt;h2&gt;&lt;i class="fas fa-calendar text-primary"&gt;&lt;/i&gt; Event Management&lt;/h2&gt;
                    &lt;button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createEventModal"&gt;
                        &lt;i class="fas fa-plus"&gt;&lt;/i&gt; Create Event
                    &lt;/button&gt;
                &lt;/div&gt;

                &lt;?php if ($message): ?&gt;
                    &lt;div class="alert alert-&lt;?php echo $message_type === 'success' ? 'success' : 'danger'; ?&gt; alert-dismissible fade show" role="alert"&gt;
                        &lt;?php echo htmlspecialchars($message); ?&gt;
                        &lt;button type="button" class="btn-close" data-bs-dismiss="alert"&gt;&lt;/button&gt;
                    &lt;/div&gt;
                &lt;?php endif; ?&gt;

                &lt;!-- Events List --&gt;
                &lt;div class="row"&gt;
                    &lt;?php if ($events_result-&gt;num_rows &gt; 0): ?&gt;
                        &lt;?php while ($event = $events_result-&gt;fetch_assoc()): ?&gt;
                            &lt;div class="col-lg-6 mb-4"&gt;
                                &lt;div class="card event-card h-100"&gt;
                                    &lt;div class="card-body"&gt;
                                        &lt;div class="d-flex justify-content-between align-items-start mb-2"&gt;
                                            &lt;h5 class="card-title mb-0"&gt;&lt;?php echo htmlspecialchars($event['title']); ?&gt;&lt;/h5&gt;
                                            &lt;div class="btn-group" role="group"&gt;
                                                &lt;button class="btn btn-outline-primary btn-sm" onclick="editEvent(&lt;?php echo $event['id']; ?&gt;, '&lt;?php echo htmlspecialchars(addslashes($event['title'])); ?&gt;', '&lt;?php echo htmlspecialchars(addslashes($event['description'])); ?&gt;', '&lt;?php echo $event['event_date']; ?&gt;', '&lt;?php echo $event['event_time']; ?&gt;', '&lt;?php echo htmlspecialchars(addslashes($event['location'])); ?&gt;', &lt;?php echo $event['max_attendees']; ?&gt;, '&lt;?php echo $event['registration_deadline']; ?&gt;')"&gt;
                                                    &lt;i class="fas fa-edit"&gt;&lt;/i&gt;
                                                &lt;/button&gt;
                                                &lt;button class="btn btn-outline-info btn-sm" onclick="viewRegistrations(&lt;?php echo $event['id']; ?&gt;, '&lt;?php echo htmlspecialchars(addslashes($event['title'])); ?&gt;')"&gt;
                                                    &lt;i class="fas fa-users"&gt;&lt;/i&gt;
                                                &lt;/button&gt;
                                                &lt;form method="post" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this event? This will also remove all registrations.')"&gt;
                                                    &lt;input type="hidden" name="action" value="delete_event"&gt;
                                                    &lt;input type="hidden" name="event_id" value="&lt;?php echo $event['id']; ?&gt;"&gt;
                                                    &lt;button type="submit" class="btn btn-outline-danger btn-sm"&gt;
                                                        &lt;i class="fas fa-trash"&gt;&lt;/i&gt;
                                                    &lt;/button&gt;
                                                &lt;/form&gt;
                                            &lt;/div&gt;
                                        &lt;/div&gt;
                                        &lt;p class="card-text text-muted"&gt;&lt;?php echo htmlspecialchars(substr($event['description'], 0, 100)) . (strlen($event['description']) &gt; 100 ? '...' : ''); ?&gt;&lt;/p&gt;
                                        &lt;div class="row text-muted small"&gt;
                                            &lt;div class="col-6"&gt;
                                                &lt;i class="fas fa-calendar"&gt;&lt;/i&gt; &lt;?php echo date('M j, Y', strtotime($event['event_date'])); ?&gt;&lt;br&gt;
                                                &lt;i class="fas fa-clock"&gt;&lt;/i&gt; &lt;?php echo $event['event_time'] ?: 'TBD'; ?&gt;
                                            &lt;/div&gt;
                                            &lt;div class="col-6"&gt;
                                                &lt;i class="fas fa-map-marker-alt"&gt;&lt;/i&gt; &lt;?php echo htmlspecialchars($event['location']); ?&gt;&lt;br&gt;
                                                &lt;i class="fas fa-users"&gt;&lt;/i&gt; &lt;?php echo $event['registered_count']; ?&gt;/&lt;?php echo $event['max_attendees'] ?: '∞'; ?&gt; registered
                                            &lt;/div&gt;
                                        &lt;/div&gt;
                                        &lt;?php if ($event['registration_deadline']): ?&gt;
                                            &lt;div class="mt-2"&gt;
                                                &lt;small class="text-muted"&gt;
                                                    Registration deadline: &lt;?php echo date('M j, Y', strtotime($event['registration_deadline'])); ?&gt;
                                                &lt;/small&gt;
                                            &lt;/div&gt;
                                        &lt;?php endif; ?&gt;
                                    &lt;/div&gt;
                                &lt;/div&gt;
                            &lt;/div&gt;
                        &lt;?php endwhile; ?&gt;
                    &lt;?php else: ?&gt;
                        &lt;div class="col-12"&gt;
                            &lt;div class="text-center py-5"&gt;
                                &lt;i class="fas fa-calendar fa-3x text-muted mb-3"&gt;&lt;/i&gt;
                                &lt;h4 class="text-muted"&gt;No Events Yet&lt;/h4&gt;
                                &lt;p class="text-muted"&gt;Create your first event to get started.&lt;/p&gt;
                            &lt;/div&gt;
                        &lt;/div&gt;
                    &lt;?php endif; ?&gt;
                &lt;/div&gt;
            &lt;/div&gt;
        &lt;/div&gt;
    &lt;/div&gt;

    &lt;!-- Create Event Modal --&gt;
    &lt;div class="modal fade" id="createEventModal" tabindex="-1"&gt;
        &lt;div class="modal-dialog modal-lg"&gt;
            &lt;div class="modal-content"&gt;
                &lt;div class="modal-header"&gt;
                    &lt;h5 class="modal-title"&gt;&lt;i class="fas fa-plus"&gt;&lt;/i&gt; Create Event&lt;/h5&gt;
                    &lt;button type="button" class="btn-close" data-bs-dismiss="modal"&gt;&lt;/button&gt;
                &lt;/div&gt;
                &lt;form method="post"&gt;
                    &lt;div class="modal-body"&gt;
                        &lt;input type="hidden" name="action" value="create_event"&gt;
                        &lt;div class="row"&gt;
                            &lt;div class="col-md-8 mb-3"&gt;
                                &lt;label for="title" class="form-label"&gt;Event Title *&lt;/label&gt;
                                &lt;input type="text" class="form-control" id="title" name="title" required&gt;
                            &lt;/div&gt;
                            &lt;div class="col-md-4 mb-3"&gt;
                                &lt;label for="max_attendees" class="form-label"&gt;Max Attendees&lt;/label&gt;
                                &lt;input type="number" class="form-control" id="max_attendees" name="max_attendees" placeholder="Unlimited"&gt;
                            &lt;/div&gt;
                        &lt;/div&gt;
                        &lt;div class="mb-3"&gt;
                            &lt;label for="description" class="form-label"&gt;Description&lt;/label&gt;
                            &lt;textarea class="form-control" id="description" name="description" rows="3"&gt;&lt;/textarea&gt;
                        &lt;/div&gt;
                        &lt;div class="row"&gt;
                            &lt;div class="col-md-6 mb-3"&gt;
                                &lt;label for="event_date" class="form-label"&gt;Event Date *&lt;/label&gt;
                                &lt;input type="date" class="form-control" id="event_date" name="event_date" required&gt;
                            &lt;/div&gt;
                            &lt;div class="col-md-6 mb-3"&gt;
                                &lt;label for="event_time" class="form-label"&gt;Event Time&lt;/label&gt;
                                &lt;input type="time" class="form-control" id="event_time" name="event_time"&gt;
                            &lt;/div&gt;
                        &lt;/div&gt;
                        &lt;div class="row"&gt;
                            &lt;div class="col-md-6 mb-3"&gt;
                                &lt;label for="location" class="form-label"&gt;Location *&lt;/label&gt;
                                &lt;input type="text" class="form-control" id="location" name="location" required&gt;
                            &lt;/div&gt;
                            &lt;div class="col-md-6 mb-3"&gt;
                                &lt;label for="registration_deadline" class="form-label"&gt;Registration Deadline&lt;/label&gt;
                                &lt;input type="date" class="form-control" id="registration_deadline" name="registration_deadline"&gt;
                            &lt;/div&gt;
                        &lt;/div&gt;
                    &lt;/div&gt;
                    &lt;div class="modal-footer"&gt;
                        &lt;button type="button" class="btn btn-secondary" data-bs-dismiss="modal"&gt;Cancel&lt;/button&gt;
                        &lt;button type="submit" class="btn btn-primary"&gt;&lt;i class="fas fa-save"&gt;&lt;/i&gt; Create Event&lt;/button&gt;
                    &lt;/div&gt;
                &lt;/form&gt;
            &lt;/div&gt;
        &lt;/div&gt;
    &lt;/div&gt;

    &lt;!-- Edit Event Modal --&gt;
    &lt;div class="modal fade" id="editEventModal" tabindex="-1"&gt;
        &lt;div class="modal-dialog modal-lg"&gt;
            &lt;div class="modal-content"&gt;
                &lt;div class="modal-header"&gt;
                    &lt;h5 class="modal-title"&gt;&lt;i class="fas fa-edit"&gt;&lt;/i&gt; Edit Event&lt;/h5&gt;
                    &lt;button type="button" class="btn-close" data-bs-dismiss="modal"&gt;&lt;/button&gt;
                &lt;/div&gt;
                &lt;form method="post"&gt;
                    &lt;div class="modal-body"&gt;
                        &lt;input type="hidden" name="action" value="update_event"&gt;
                        &lt;input type="hidden" name="event_id" id="edit_event_id"&gt;
                        &lt;div class="row"&gt;
                            &lt;div class="col-md-8 mb-3"&gt;
                                &lt;label for="edit_title" class="form-label"&gt;Event Title *&lt;/label&gt;
                                &lt;input type="text" class="form-control" id="edit_title" name="title" required&gt;
                            &lt;/div&gt;
                            &lt;div class="col-md-4 mb-3"&gt;
                                &lt;label for="edit_max_attendees" class="form-label"&gt;Max Attendees&lt;/label&gt;
                                &lt;input type="number" class="form-control" id="edit_max_attendees" name="max_attendees" placeholder="Unlimited"&gt;
                            &lt;/div&gt;
                        &lt;/div&gt;
                        &lt;div class="mb-3"&gt;
                            &lt;label for="edit_description" class="form-label"&gt;Description&lt;/label&gt;
                            &lt;textarea class="form-control" id="edit_description" name="description" rows="3"&gt;&lt;/textarea&gt;
                        &lt;/div&gt;
                        &lt;div class="row"&gt;
                            &lt;div class="col-md-6 mb-3"&gt;
                                &lt;label for="edit_event_date" class="form-label"&gt;Event Date *&lt;/label&gt;
                                &lt;input type="date" class="form-control" id="edit_event_date" name="event_date" required&gt;
                            &lt;/div&gt;
                            &lt;div class="col-md-6 mb-3"&gt;
                                &lt;label for="edit_event_time" class="form-label"&gt;Event Time&lt;/label&gt;
                                &lt;input type="time" class="form-control" id="edit_event_time" name="event_time"&gt;
                            &lt;/div&gt;
                        &lt;/div&gt;
                        &lt;div class="row"&gt;
                            &lt;div class="col-md-6 mb-3"&gt;
                                &lt;label for="edit_location" class="form-label"&gt;Location *&lt;/label&gt;
                                &lt;input type="text" class="form-control" id="edit_location" name="location" required&gt;
                            &lt;/div&gt;
                            &lt;div class="col-md-6 mb-3"&gt;
                                &lt;label for="edit_registration_deadline" class="form-label"&gt;Registration Deadline&lt;/label&gt;
                                &lt;input type="date" class="form-control" id="edit_registration_deadline" name="registration_deadline"&gt;
                            &lt;/div&gt;
                        &lt;/div&gt;
                    &lt;/div&gt;
                    &lt;div class="modal-footer"&gt;
                        &lt;button type="button" class="btn btn-secondary" data-bs-dismiss="modal"&gt;Cancel&lt;/button&gt;
                        &lt;button type="submit" class="btn btn-primary"&gt;&lt;i class="fas fa-save"&gt;&lt;/i&gt; Update Event&lt;/button&gt;
                    &lt;/div&gt;
                &lt;/form&gt;
            &lt;/div&gt;
        &lt;/div&gt;
    &lt;/div&gt;

    &lt;!-- Registrations Modal --&gt;
    &lt;div class="modal fade" id="registrationsModal" tabindex="-1"&gt;
        &lt;div class="modal-dialog modal-lg"&gt;
            &lt;div class="modal-content"&gt;
                &lt;div class="modal-header"&gt;
                    &lt;h5 class="modal-title"&gt;&lt;i class="fas fa-users"&gt;&lt;/i&gt; Event Registrations&lt;/h5&gt;
                    &lt;button type="button" class="btn-close" data-bs-dismiss="modal"&gt;&lt;/button&gt;
                &lt;/div&gt;
                &lt;div class="modal-body"&gt;
                    &lt;h6 id="eventTitle"&gt;&lt;/h6&gt;
                    &lt;div id="registrationsList" class="registration-list"&gt;
                        &lt;!-- Registrations will be loaded here --&gt;
                    &lt;/div&gt;
                &lt;/div&gt;
            &lt;/div&gt;
        &lt;/div&gt;
    &lt;/div&gt;

    &lt;script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"&gt;&lt;/script&gt;
    &lt;script&gt;
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
            fetch('admin_events.php?action=get_registrations&amp;event_id=' + eventId)
                .then(response =&gt; response.text())
                .then(data =&gt; {
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
                document.write('&lt;div class="text-center py-3"&gt;&lt;i class="fas fa-spinner fa-spin"&gt;&lt;/i&gt; Loading registrations...&lt;/div&gt;');
            }
        }
    &lt;/script&gt;
&lt;/body&gt;
&lt;/html&gt;