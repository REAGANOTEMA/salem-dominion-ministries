&lt;?php
session_start();
require_once 'db.php';

// Handle event registration
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register_event'])) {
    $event_id = intval($_POST['event_id']);
    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $guests = intval($_POST['guests'] ?? 0);

    if ($event_id && ((!$user_id && $name && $email) || $user_id)) {
        // Check if already registered
        $check_stmt = $db-&gt;prepare("SELECT id FROM event_registrations WHERE event_id = ? AND ((user_id = ?) OR (user_id IS NULL AND email = ?))");
        $check_stmt-&gt;bind_param('iis', $event_id, $user_id, $email);
        $check_stmt-&gt;execute();
        $check_result = $check_stmt-&gt;get_result();

        if ($check_result-&gt;num_rows === 0) {
            $stmt = $db-&gt;prepare("INSERT INTO event_registrations (event_id, user_id, name, email, phone, guests) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt-&gt;bind_param('iisssi', $event_id, $user_id, $name, $email, $phone, $guests);
            if ($stmt-&gt;execute()) {
                $registration_success = 'Successfully registered for the event!';
            } else {
                $registration_error = 'Failed to register. Please try again.';
            }
            $stmt-&gt;close();
        } else {
            $registration_error = 'You are already registered for this event.';
        }
        $check_stmt-&gt;close();
    } else {
        $registration_error = 'Please provide all required information.';
    }
}

// Get upcoming events
$upcoming_events = $db-&gt;query("SELECT e.*, COUNT(er.id) as registrations FROM events e LEFT JOIN event_registrations er ON e.id = er.event_id WHERE e.event_date &gt;= CURDATE() ORDER BY e.event_date");

// Get past events
$past_events = $db-&gt;query("SELECT e.*, COUNT(er.id) as registrations FROM events e LEFT JOIN event_registrations er ON e.id = er.event_id WHERE e.event_date &lt; CURDATE() ORDER BY e.event_date DESC LIMIT 5");
?&gt;
&lt;!DOCTYPE html&gt;
&lt;html lang="en"&gt;
&lt;head&gt;
    &lt;meta charset="UTF-8"&gt;
    &lt;meta name="viewport" content="width=device-width, initial-scale=1.0"&gt;
    &lt;title&gt;Events - Salem Dominion Ministries&lt;/title&gt;
    &lt;link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"&gt;
    &lt;link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet"&gt;
    &lt;style&gt;
        .events-hero {
            background: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)), url('https://images.unsplash.com/photo-1511795409834-ef04bbd61622?ixlib=rb-4.0.3');
            background-size: cover;
            background-position: center;
            color: white;
            padding: 80px 0;
        }
        .navbar-brand {
            font-weight: bold;
            font-size: 1.5rem;
        }
        .event-card {
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            border: none;
            border-radius: 15px;
            transition: transform 0.3s ease;
            overflow: hidden;
        }
        .event-card:hover {
            transform: translateY(-5px);
        }
        .event-date {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px;
            text-align: center;
            border-radius: 10px;
        }
        .event-category {
            position: absolute;
            top: 10px;
            right: 10px;
            background: rgba(0,0,0,0.8);
            color: white;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
        }
        .registration-modal .modal-dialog {
            max-width: 500px;
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
                    &lt;li class="nav-item"&gt;&lt;a class="nav-link active" href="events.php"&gt;Events&lt;/a&gt;&lt;/li&gt;
                    &lt;li class="nav-item"&gt;&lt;a class="nav-link" href="sermons.php"&gt;Sermons&lt;/a&gt;&lt;/li&gt;
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
    &lt;section class="events-hero"&gt;
        &lt;div class="container text-center"&gt;
            &lt;h1 class="display-4 fw-bold mb-4"&gt;Church Events&lt;/h1&gt;
            &lt;p class="lead mb-4"&gt;Join us for worship services, fellowship gatherings, community outreach, and special celebrations.&lt;/p&gt;
        &lt;/div&gt;
    &lt;/section&gt;

    &lt;!-- Upcoming Events --&gt;
    &lt;section class="py-5"&gt;
        &lt;div class="container"&gt;
            &lt;h2 class="text-center mb-5"&gt;&lt;i class="fas fa-calendar-check text-primary"&gt;&lt;/i&gt; Upcoming Events&lt;/h2&gt;

            &lt;?php if (isset($registration_success)): ?&gt;
                &lt;div class="alert alert-success alert-dismissible fade show" role="alert"&gt;
                    &lt;?php echo htmlspecialchars($registration_success); ?&gt;
                    &lt;button type="button" class="btn-close" data-bs-dismiss="alert"&gt;&lt;/button&gt;
                &lt;/div&gt;
            &lt;?php endif; ?&gt;

            &lt;?php if (isset($registration_error)): ?&gt;
                &lt;div class="alert alert-danger alert-dismissible fade show" role="alert"&gt;
                    &lt;?php echo htmlspecialchars($registration_error); ?&gt;
                    &lt;button type="button" class="btn-close" data-bs-dismiss="alert"&gt;&lt;/button&gt;
                &lt;/div&gt;
            &lt;?php endif; ?&gt;

            &lt;div class="row g-4"&gt;
                &lt;?php if ($upcoming_events-&gt;num_rows &gt; 0): ?&gt;
                    &lt;?php while ($event = $upcoming_events-&gt;fetch_assoc()): ?&gt;
                        &lt;div class="col-lg-6 col-xl-4"&gt;
                            &lt;div class="card event-card h-100"&gt;
                                &lt;div class="position-relative"&gt;
                                    &lt;img src="https://images.unsplash.com/photo-1511795409834-ef04bbd61622?ixlib=rb-4.0.3&amp;w=400" class="card-img-top" alt="Event Image" style="height: 200px; object-fit: cover;"&gt;
                                    &lt;div class="event-category"&gt;
                                        &lt;?php echo ucfirst(htmlspecialchars($event['category'])); ?&gt;
                                    &lt;/div&gt;
                                &lt;/div&gt;
                                &lt;div class="card-body d-flex flex-column"&gt;
                                    &lt;div class="event-date mb-3"&gt;
                                        &lt;div class="fw-bold fs-4"&gt;&lt;?php echo date('j', strtotime($event['event_date'])); ?&gt;&lt;/div&gt;
                                        &lt;div&gt;&lt;?php echo date('M Y', strtotime($event['event_date'])); ?&gt;&lt;/div&gt;
                                        &lt;div class="small"&gt;&lt;?php echo date('g:i A', strtotime($event['start_time'])); ?&gt;&lt;/div&gt;
                                    &lt;/div&gt;
                                    &lt;h5 class="card-title"&gt;&lt;?php echo htmlspecialchars($event['title']); ?&gt;&lt;/h5&gt;
                                    &lt;p class="card-text flex-grow-1"&gt;&lt;?php echo htmlspecialchars(substr($event['description'], 0, 120)); ?&gt;...&lt;/p&gt;
                                    &lt;div class="mb-3"&gt;
                                        &lt;small class="text-muted"&gt;
                                            &lt;i class="fas fa-map-marker-alt"&gt;&lt;/i&gt; &lt;?php echo htmlspecialchars($event['location']); ?&gt;&lt;br&gt;
                                            &lt;i class="fas fa-users"&gt;&lt;/i&gt; &lt;?php echo $event['registrations']; ?&gt; registered
                                            &lt;?php if ($event['max_attendees']): ?&gt;
                                                (max: &lt;?php echo $event['max_attendees']; ?&gt;)
                                            &lt;?php endif; ?&gt;
                                        &lt;/small&gt;
                                    &lt;/div&gt;
                                    &lt;button type="button" class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#registerModal"
                                            data-event-id="&lt;?php echo $event['id']; ?&gt;" data-event-title="&lt;?php echo htmlspecialchars($event['title']); ?&gt;"&gt;
                                        &lt;i class="fas fa-user-plus"&gt;&lt;/i&gt; Register
                                    &lt;/button&gt;
                                &lt;/div&gt;
                            &lt;/div&gt;
                        &lt;/div&gt;
                    &lt;?php endwhile; ?&gt;
                &lt;?php else: ?&gt;
                    &lt;div class="col-12 text-center py-5"&gt;
                        &lt;i class="fas fa-calendar-times fa-3x text-muted mb-3"&gt;&lt;/i&gt;
                        &lt;h4 class="text-muted"&gt;No upcoming events&lt;/h4&gt;
                        &lt;p class="text-muted"&gt;Check back soon for new events and activities!&lt;/p&gt;
                    &lt;/div&gt;
                &lt;?php endif; ?&gt;
            &lt;/div&gt;
        &lt;/div&gt;
    &lt;/section&gt;

    &lt;!-- Past Events --&gt;
    &lt;section class="py-5 bg-light"&gt;
        &lt;div class="container"&gt;
            &lt;h2 class="text-center mb-5"&gt;&lt;i class="fas fa-history text-secondary"&gt;&lt;/i&gt; Recent Events&lt;/h2&gt;
            &lt;div class="row g-4"&gt;
                &lt;?php if ($past_events-&gt;num_rows &gt; 0): ?&gt;
                    &lt;?php while ($event = $past_events-&gt;fetch_assoc()): ?&gt;
                        &lt;div class="col-lg-6 col-xl-4"&gt;
                            &lt;div class="card event-card h-100"&gt;
                                &lt;div class="position-relative"&gt;
                                    &lt;img src="https://images.unsplash.com/photo-1511795409834-ef04bbd61622?ixlib=rb-4.0.3&amp;w=400" class="card-img-top opacity-75" alt="Event Image" style="height: 200px; object-fit: cover;"&gt;
                                    &lt;div class="event-category"&gt;
                                        &lt;?php echo ucfirst(htmlspecialchars($event['category'])); ?&gt;
                                    &lt;/div&gt;
                                &lt;/div&gt;
                                &lt;div class="card-body d-flex flex-column"&gt;
                                    &lt;div class="event-date mb-3" style="background: linear-gradient(135deg, #6c757d 0%, #495057 100%);"&gt;
                                        &lt;div class="fw-bold fs-4"&gt;&lt;?php echo date('j', strtotime($event['event_date'])); ?&gt;&lt;/div&gt;
                                        &lt;div&gt;&lt;?php echo date('M Y', strtotime($event['event_date'])); ?&gt;&lt;/div&gt;
                                        &lt;div class="small"&gt;Past Event&lt;/div&gt;
                                    &lt;/div&gt;
                                    &lt;h5 class="card-title"&gt;&lt;?php echo htmlspecialchars($event['title']); ?&gt;&lt;/h5&gt;
                                    &lt;p class="card-text flex-grow-1"&gt;&lt;?php echo htmlspecialchars(substr($event['description'], 0, 120)); ?&gt;...&lt;/p&gt;
                                    &lt;div class="mb-3"&gt;
                                        &lt;small class="text-muted"&gt;
                                            &lt;i class="fas fa-users"&gt;&lt;/i&gt; &lt;?php echo $event['registrations']; ?&gt; attended
                                        &lt;/small&gt;
                                    &lt;/div&gt;
                                    &lt;button class="btn btn-outline-secondary w-100" disabled&gt;
                                        &lt;i class="fas fa-check"&gt;&lt;/i&gt; Event Completed
                                    &lt;/button&gt;
                                &lt;/div&gt;
                            &lt;/div&gt;
                        &lt;/div&gt;
                    &lt;?php endwhile; ?&gt;
                &lt;?php else: ?&gt;
                    &lt;div class="col-12 text-center py-5"&gt;
                        &lt;i class="fas fa-calendar-check fa-3x text-muted mb-3"&gt;&lt;/i&gt;
                        &lt;h4 class="text-muted"&gt;No past events to show&lt;/h4&gt;
                    &lt;/div&gt;
                &lt;?php endif; ?&gt;
            &lt;/div&gt;
        &lt;/div&gt;
    &lt;/section&gt;

    &lt;!-- Registration Modal --&gt;
    &lt;div class="modal fade registration-modal" id="registerModal" tabindex="-1"&gt;
        &lt;div class="modal-dialog"&gt;
            &lt;div class="modal-content"&gt;
                &lt;div class="modal-header"&gt;
                    &lt;h5 class="modal-title"&gt;Register for Event&lt;/h5&gt;
                    &lt;button type="button" class="btn-close" data-bs-dismiss="modal"&gt;&lt;/button&gt;
                &lt;/div&gt;
                &lt;form method="POST" action=""&gt;
                    &lt;div class="modal-body"&gt;
                        &lt;input type="hidden" name="register_event" value="1"&gt;
                        &lt;input type="hidden" name="event_id" id="modalEventId"&gt;

                        &lt;div class="mb-3"&gt;
                            &lt;h6 id="modalEventTitle"&gt;&lt;/h6&gt;
                        &lt;/div&gt;

                        &lt;?php if (!isset($_SESSION['user_id'])): ?&gt;
                            &lt;div class="mb-3"&gt;
                                &lt;label for="registerName" class="form-label"&gt;Full Name *&lt;/label&gt;
                                &lt;input type="text" class="form-control" id="registerName" name="name" required&gt;
                            &lt;/div&gt;
                            &lt;div class="mb-3"&gt;
                                &lt;label for="registerEmail" class="form-label"&gt;Email *&lt;/label&gt;
                                &lt;input type="email" class="form-control" id="registerEmail" name="email" required&gt;
                            &lt;/div&gt;
                            &lt;div class="mb-3"&gt;
                                &lt;label for="registerPhone" class="form-label"&gt;Phone&lt;/label&gt;
                                &lt;input type="tel" class="form-control" id="registerPhone" name="phone"&gt;
                            &lt;/div&gt;
                        &lt;?php endif; ?&gt;

                        &lt;div class="mb-3"&gt;
                            &lt;label for="registerGuests" class="form-label"&gt;Number of Additional Guests&lt;/label&gt;
                            &lt;select class="form-control" id="registerGuests" name="guests"&gt;
                                &lt;option value="0"&gt;Just me&lt;/option&gt;
                                &lt;option value="1"&gt;1 guest&lt;/option&gt;
                                &lt;option value="2"&gt;2 guests&lt;/option&gt;
                                &lt;option value="3"&gt;3 guests&lt;/option&gt;
                                &lt;option value="4"&gt;4 guests&lt;/option&gt;
                            &lt;/select&gt;
                        &lt;/div&gt;
                    &lt;/div&gt;
                    &lt;div class="modal-footer"&gt;
                        &lt;button type="button" class="btn btn-secondary" data-bs-dismiss="modal"&gt;Cancel&lt;/button&gt;
                        &lt;button type="submit" class="btn btn-primary"&gt;Register&lt;/button&gt;
                    &lt;/div&gt;
                &lt;/form&gt;
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
        // Handle registration modal
        const registerModal = document.getElementById('registerModal');
        registerModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const eventId = button.getAttribute('data-event-id');
            const eventTitle = button.getAttribute('data-event-title');

            document.getElementById('modalEventId').value = eventId;
            document.getElementById('modalEventTitle').textContent = eventTitle;
        });
    &lt;/script&gt;
&lt;/body&gt;
&lt;/html&gt;