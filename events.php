<?php
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
        $check_stmt = $db->prepare("SELECT id FROM event_registrations WHERE event_id = ? AND ((user_id = ?) OR (user_id IS NULL AND email = ?))");
        $check_stmt->bind_param('iis', $event_id, $user_id, $email);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();

        if ($check_result->num_rows === 0) {
            $stmt = $db->prepare("INSERT INTO event_registrations (event_id, user_id, name, email, phone, guests) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param('iisssi', $event_id, $user_id, $name, $email, $phone, $guests);
            if ($stmt->execute()) {
                $registration_success = 'Successfully registered for the event!';
            } else {
                $registration_error = 'Failed to register. Please try again.';
            }
            $stmt->close();
        } else {
            $registration_error = 'You are already registered for this event.';
        }
        $check_stmt->close();
    } else {
        $registration_error = 'Please provide all required information.';
    }
}

// Get upcoming events
$upcoming_events = $db->query("SELECT e.*, COUNT(er.id) as registrations FROM events e LEFT JOIN event_registrations er ON e.id = er.event_id WHERE e.event_date >= CURDATE() ORDER BY e.event_date");

// Get past events
$past_events = $db->query("SELECT e.*, COUNT(er.id) as registrations FROM events e LEFT JOIN event_registrations er ON e.id = er.event_id WHERE e.event_date < CURDATE() ORDER BY e.event_date DESC LIMIT 5");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Events - Salem Dominion Ministries</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
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
                    <li class="nav-item"><a class="nav-link active" href="events.php">Events</a></li>
                    <li class="nav-item"><a class="nav-link" href="sermons.php">Sermons</a></li>
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
    <section class="events-hero">
        <div class="container text-center">
            <h1 class="display-4 fw-bold mb-4">Church Events</h1>
            <p class="lead mb-4">Join us for worship services, fellowship gatherings, community outreach, and special celebrations.</p>
        </div>
    </section>

    <!-- Upcoming Events -->
    <section class="py-5">
        <div class="container">
            <h2 class="text-center mb-5"><i class="fas fa-calendar-check text-primary"></i> Upcoming Events</h2>

            <?php if (isset($registration_success)): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?php echo htmlspecialchars($registration_success); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if (isset($registration_error)): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?php echo htmlspecialchars($registration_error); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <div class="row g-4">
                <?php if ($upcoming_events->num_rows > 0): ?>
                    <?php while ($event = $upcoming_events->fetch_assoc()): ?>
                        <div class="col-lg-6 col-xl-4">
                            <div class="card event-card h-100">
                                <div class="position-relative">
                                    <img src="https://images.unsplash.com/photo-1511795409834-ef04bbd61622?ixlib=rb-4.0.3&w=400" class="card-img-top" alt="Event Image" style="height: 200px; object-fit: cover;">
                                    <div class="event-category">
                                        <?php echo ucfirst(htmlspecialchars($event['category'])); ?>
                                    </div>
                                </div>
                                <div class="card-body d-flex flex-column">
                                    <div class="event-date mb-3">
                                        <div class="fw-bold fs-4"><?php echo date('j', strtotime($event['event_date'])); ?></div>
                                        <div><?php echo date('M Y', strtotime($event['event_date'])); ?></div>
                                        <div class="small"><?php echo date('g:i A', strtotime($event['start_time'])); ?></div>
                                    </div>
                                    <h5 class="card-title"><?php echo htmlspecialchars($event['title']); ?></h5>
                                    <p class="card-text flex-grow-1"><?php echo htmlspecialchars(substr($event['description'], 0, 120)); ?>...</p>
                                    <div class="mb-3">
                                        <small class="text-muted">
                                            <i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($event['location']); ?><br>
                                            <i class="fas fa-users"></i> <?php echo $event['registrations']; ?> registered
                                            <?php if ($event['max_attendees']): ?>
                                                (max: <?php echo $event['max_attendees']; ?>)
                                            <?php endif; ?>
                                        </small>
                                    </div>
                                    <button type="button" class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#registerModal"
                                            data-event-id="<?php echo $event['id']; ?>" data-event-title="<?php echo htmlspecialchars($event['title']); ?>">
                                        <i class="fas fa-user-plus"></i> Register
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="col-12 text-center py-5">
                        <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                        <h4 class="text-muted">No upcoming events</h4>
                        <p class="text-muted">Check back soon for new events and activities!</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Past Events -->
    <section class="py-5 bg-light">
        <div class="container">
            <h2 class="text-center mb-5"><i class="fas fa-history text-secondary"></i> Recent Events</h2>
            <div class="row g-4">
                <?php if ($past_events->num_rows > 0): ?>
                    <?php while ($event = $past_events->fetch_assoc()): ?>
                        <div class="col-lg-6 col-xl-4">
                            <div class="card event-card h-100">
                                <div class="position-relative">
                                    <img src="https://images.unsplash.com/photo-1511795409834-ef04bbd61622?ixlib=rb-4.0.3&w=400" class="card-img-top opacity-75" alt="Event Image" style="height: 200px; object-fit: cover;">
                                    <div class="event-category">
                                        <?php echo ucfirst(htmlspecialchars($event['category'])); ?>
                                    </div>
                                </div>
                                <div class="card-body d-flex flex-column">
                                    <div class="event-date mb-3" style="background: linear-gradient(135deg, #6c757d 0%, #495057 100%);">
                                        <div class="fw-bold fs-4"><?php echo date('j', strtotime($event['event_date'])); ?></div>
                                        <div><?php echo date('M Y', strtotime($event['event_date'])); ?></div>
                                        <div class="small">Past Event</div>
                                    </div>
                                    <h5 class="card-title"><?php echo htmlspecialchars($event['title']); ?></h5>
                                    <p class="card-text flex-grow-1"><?php echo htmlspecialchars(substr($event['description'], 0, 120)); ?>...</p>
                                    <div class="mb-3">
                                        <small class="text-muted">
                                            <i class="fas fa-users"></i> <?php echo $event['registrations']; ?> attended
                                        </small>
                                    </div>
                                    <button class="btn btn-outline-secondary w-100" disabled>
                                        <i class="fas fa-check"></i> Event Completed
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="col-12 text-center py-5">
                        <i class="fas fa-calendar-check fa-3x text-muted mb-3"></i>
                        <h4 class="text-muted">No past events to show</h4>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Registration Modal -->
    <div class="modal fade registration-modal" id="registerModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Register for Event</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="">
                    <div class="modal-body">
                        <input type="hidden" name="register_event" value="1">
                        <input type="hidden" name="event_id" id="modalEventId">

                        <div class="mb-3">
                            <h6 id="modalEventTitle"></h6>
                        </div>

                        <?php if (!isset($_SESSION['user_id'])): ?>
                            <div class="mb-3">
                                <label for="registerName" class="form-label">Full Name *</label>
                                <input type="text" class="form-control" id="registerName" name="name" required>
                            </div>
                            <div class="mb-3">
                                <label for="registerEmail" class="form-label">Email *</label>
                                <input type="email" class="form-control" id="registerEmail" name="email" required>
                            </div>
                            <div class="mb-3">
                                <label for="registerPhone" class="form-label">Phone</label>
                                <input type="tel" class="form-control" id="registerPhone" name="phone">
                            </div>
                        <?php endif; ?>

                        <div class="mb-3">
                            <label for="registerGuests" class="form-label">Number of Additional Guests</label>
                            <select class="form-control" id="registerGuests" name="guests">
                                <option value="0">Just me</option>
                                <option value="1">1 guest</option>
                                <option value="2">2 guests</option>
                                <option value="3">3 guests</option>
                                <option value="4">4 guests</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Register</button>
                    </div>
                </form>
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
        // Handle registration modal
        const registerModal = document.getElementById('registerModal');
        registerModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const eventId = button.getAttribute('data-event-id');
            const eventTitle = button.getAttribute('data-event-title');

            document.getElementById('modalEventId').value = eventId;
            document.getElementById('modalEventTitle').textContent = eventTitle;
        });
    </script>
</body>
</html>