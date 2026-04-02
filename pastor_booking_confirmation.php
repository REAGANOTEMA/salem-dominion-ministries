<?php
session_start();
require_once 'db.php';

$booking_reference = $_GET['ref'] ?? '';

if ($booking_reference) {
    $booking = $db->selectOne("SELECT * FROM pastor_bookings WHERE booking_reference = ?", [$booking_reference]);
} else {
    $booking = null;
}

if (!$booking) {
    header('Location: pastor_booking.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Confirmation - Salem Dominion Ministries</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- AOS Animation -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700;900&family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #e74c3c;
            --accent-color: #f39c12;
            --light-color: #ecf0f1;
            --dark-color: #34495e;
            --gradient-primary: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --gradient-secondary: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            --gradient-church: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
        }

        body {
            font-family: 'Montserrat', sans-serif;
            line-height: 1.6;
            color: var(--dark-color);
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: 'Playfair Display', serif;
            font-weight: 700;
        }

        /* Navigation */
        .navbar {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            box-shadow: 0 2px 20px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            padding: 1rem 0;
        }

        .navbar-brand {
            font-family: 'Dancing Script', cursive;
            font-size: 2rem;
            font-weight: 700;
            background: var(--gradient-church);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Confirmation Header */
        .confirmation-header {
            background: var(--gradient-church);
            color: white;
            padding: 100px 0 50px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .confirmation-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: -50%;
            width: 200%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
            animation: shimmer 3s infinite;
        }

        @keyframes shimmer {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }

        /* Success Animation */
        .success-icon {
            font-size: 5rem;
            color: #28a745;
            margin-bottom: 2rem;
            animation: successPulse 2s ease-in-out;
        }

        @keyframes successPulse {
            0% { transform: scale(0); opacity: 0; }
            50% { transform: scale(1.2); opacity: 1; }
            100% { transform: scale(1); opacity: 1; }
        }

        /* Confirmation Card */
        .confirmation-card {
            max-width: 800px;
            margin: -50px auto 50px;
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            position: relative;
        }

        .confirmation-content {
            padding: 3rem;
        }

        .booking-details {
            background: #f8f9fa;
            border-radius: 15px;
            padding: 2rem;
            margin-bottom: 2rem;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 0;
            border-bottom: 1px solid #e0e0e0;
        }

        .detail-row:last-child {
            border-bottom: none;
        }

        .detail-label {
            font-weight: 600;
            color: var(--dark-color);
        }

        .detail-value {
            color: var(--primary-color);
        }

        /* Google Meet Card */
        .meet-card {
            background: linear-gradient(135deg, #4285f4, #0f9d58);
            color: white;
            border-radius: 15px;
            padding: 2rem;
            text-align: center;
            margin-bottom: 2rem;
        }

        .meet-link {
            display: inline-block;
            background: rgba(255, 255, 255, 0.2);
            border: 2px solid white;
            border-radius: 10px;
            padding: 1rem 2rem;
            color: white;
            text-decoration: none;
            font-weight: 600;
            margin-top: 1rem;
            transition: all 0.3s ease;
        }

        .meet-link:hover {
            background: white;
            color: var(--primary-color);
            transform: translateY(-2px);
        }

        /* Action Buttons */
        .action-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn-action {
            padding: 12px 30px;
            border-radius: 50px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-primary {
            background: var(--gradient-primary);
            color: white;
        }

        .btn-secondary {
            background: white;
            color: var(--primary-color);
            border: 2px solid var(--primary-color);
        }

        .btn-action:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        }

        /* Calendar Integration */
        .calendar-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
            margin-top: 2rem;
            flex-wrap: wrap;
        }

        .calendar-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.8rem 1.5rem;
            background: white;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            color: var(--dark-color);
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .calendar-btn:hover {
            border-color: var(--accent-color);
            transform: translateY(-2px);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .confirmation-content {
                padding: 2rem 1.5rem;
            }
            
            .action-buttons {
                flex-direction: column;
                align-items: center;
            }
            
            .btn-action {
                width: 100%;
                max-width: 300px;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <i class="fas fa-church"></i> Salem Dominion Ministries
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto">
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

    <!-- Confirmation Header -->
    <header class="confirmation-header">
        <div class="container">
            <div class="success-icon" data-aos="zoom-in">
                <i class="fas fa-check-circle"></i>
            </div>
            <h1 class="display-4 fw-bold mb-3" data-aos="fade-up">Booking Confirmed!</h1>
            <p class="lead" data-aos="fade-up" data-aos-delay="100">Your appointment has been successfully scheduled</p>
        </div>
    </header>

    <!-- Confirmation Content -->
    <div class="container">
        <div class="confirmation-card" data-aos="fade-up">
            <div class="confirmation-content">
                <!-- Booking Reference -->
                <div class="text-center mb-4">
                    <h3 class="text-primary">Booking Reference</h3>
                    <h2 class="display-6 fw-bold"><?php echo htmlspecialchars($booking['booking_reference']); ?></h2>
                    <p class="text-muted">Please save this reference for future use</p>
                </div>

                <!-- Google Meet Information -->
                <div class="meet-card" data-aos="fade-up" data-aos-delay="100">
                    <h4><i class="fas fa-video me-2"></i> Google Meet Session</h4>
                    <p class="mb-3">Your virtual meeting link is ready</p>
                    <div class="mb-3">
                        <strong>Join Code:</strong> <?php echo htmlspecialchars($booking['meet_join_code']); ?>
                    </div>
                    <a href="<?php echo htmlspecialchars($booking['google_meet_link']); ?>" class="meet-link" target="_blank">
                        <i class="fas fa-external-link-alt me-2"></i> Join Meeting
                    </a>
                </div>

                <!-- Booking Details -->
                <div class="booking-details" data-aos="fade-up" data-aos-delay="200">
                    <h4 class="mb-4"><i class="fas fa-info-circle me-2"></i> Booking Details</h4>
                    
                    <div class="detail-row">
                        <span class="detail-label">Name:</span>
                        <span class="detail-value"><?php echo htmlspecialchars($booking['client_name']); ?></span>
                    </div>
                    
                    <div class="detail-row">
                        <span class="detail-label">Email:</span>
                        <span class="detail-value"><?php echo htmlspecialchars($booking['client_email']); ?></span>
                    </div>
                    
                    <div class="detail-row">
                        <span class="detail-label">Phone:</span>
                        <span class="detail-value"><?php echo htmlspecialchars($booking['client_phone']); ?></span>
                    </div>
                    
                    <div class="detail-row">
                        <span class="detail-label">Booking Type:</span>
                        <span class="detail-value"><?php echo ucfirst(str_replace('_', ' ', $booking['booking_type'])); ?></span>
                    </div>
                    
                    <div class="detail-row">
                        <span class="detail-label">Date:</span>
                        <span class="detail-value"><?php echo date('F j, Y', strtotime($booking['booking_date'])); ?></span>
                    </div>
                    
                    <div class="detail-row">
                        <span class="detail-label">Time:</span>
                        <span class="detail-value"><?php echo date('g:i A', strtotime($booking['start_time'])); ?> - <?php echo date('g:i A', strtotime($booking['end_time'])); ?></span>
                    </div>
                    
                    <div class="detail-row">
                        <span class="detail-label">Duration:</span>
                        <span class="detail-value"><?php echo $booking['duration_minutes']; ?> minutes</span>
                    </div>
                    
                    <?php if ($booking['subject']): ?>
                    <div class="detail-row">
                        <span class="detail-label">Subject:</span>
                        <span class="detail-value"><?php echo htmlspecialchars($booking['subject']); ?></span>
                    </div>
                    <?php endif; ?>
                    
                    <div class="detail-row">
                        <span class="detail-label">Status:</span>
                        <span class="badge bg-warning">Pending Confirmation</span>
                    </div>
                </div>

                <!-- Calendar Integration -->
                <div class="text-center mb-4" data-aos="fade-up" data-aos-delay="300">
                    <h5><i class="fas fa-calendar-plus me-2"></i> Add to Your Calendar</h5>
                    <div class="calendar-buttons">
                        <a href="#" class="calendar-btn" onclick="addToGoogleCalendar(); return false;">
                            <i class="fab fa-google"></i> Google Calendar
                        </a>
                        <a href="#" class="calendar-btn" onclick="addToOutlook(); return false;">
                            <i class="fab fa-microsoft"></i> Outlook
                        </a>
                        <a href="#" class="calendar-btn" onclick="downloadICS(); return false;">
                            <i class="fas fa-download"></i> Download ICS
                        </a>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="action-buttons" data-aos="fade-up" data-aos-delay="400">
                    <a href="index.php" class="btn-action btn-primary">
                        <i class="fas fa-home"></i> Back to Home
                    </a>
                    <a href="contact.php" class="btn-action btn-secondary">
                        <i class="fas fa-phone"></i> Contact Office
                    </a>
                    <a href="pastor_booking.php" class="btn-action btn-secondary">
                        <i class="fas fa-calendar-plus"></i> Book Another
                    </a>
                </div>

                <!-- Important Information -->
                <div class="alert alert-info mt-4" data-aos="fade-up" data-aos-delay="500">
                    <h5><i class="fas fa-info-circle me-2"></i> Important Information</h5>
                    <ul class="mb-0">
                        <li>You will receive a confirmation email with all details and the Google Meet link</li>
                        <li>Please join the meeting 5 minutes before the scheduled time</li>
                        <li>If you need to reschedule, please contact the church office at least 24 hours in advance</li>
                        <li>For technical issues with the meeting, call our support team</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        // Initialize AOS
        AOS.init({
            duration: 1000,
            once: true,
            offset: 100
        });

        // Calendar functions
        function addToGoogleCalendar() {
            const event = {
                title: 'Pastor Appointment - <?php echo htmlspecialchars($booking['booking_type']); ?>',
                start: '<?php echo date('Y-m-d\TH:i:s', strtotime($booking['booking_date'] . ' ' . $booking['start_time'])); ?>',
                end: '<?php echo date('Y-m-d\TH:i:s', strtotime($booking['booking_date'] . ' ' . $booking['end_time'])); ?>',
                details: 'Booking Reference: <?php echo htmlspecialchars($booking['booking_reference']); ?>\nGoogle Meet: <?php echo htmlspecialchars($booking['google_meet_link']); ?>\nJoin Code: <?php echo htmlspecialchars($booking['meet_join_code']); ?>',
                location: '<?php echo htmlspecialchars($booking['google_meet_link']); ?>'
            };

            const url = `https://calendar.google.com/calendar/render?action=TEMPLATE&text=${encodeURIComponent(event.title)}&dates=${event.start}/${event.end}&details=${encodeURIComponent(event.details)}&location=${encodeURIComponent(event.location)}`;
            window.open(url, '_blank');
        }

        function addToOutlook() {
            const event = {
                subject: 'Pastor Appointment - <?php echo htmlspecialchars($booking['booking_type']); ?>',
                start: '<?php echo date('Y-m-d\TH:i:s', strtotime($booking['booking_date'] . ' ' . $booking['start_time'])); ?>',
                end: '<?php echo date('Y-m-d\TH:i:s', strtotime($booking['booking_date'] . ' ' . $booking['end_time'])); ?>',
                body: 'Booking Reference: <?php echo htmlspecialchars($booking['booking_reference']); ?>\nGoogle Meet: <?php echo htmlspecialchars($booking['google_meet_link']); ?>\nJoin Code: <?php echo htmlspecialchars($booking['meet_join_code']); ?>',
                location: '<?php echo htmlspecialchars($booking['google_meet_link']); ?>'
            };

            const url = `https://outlook.live.com/calendar/0/deeplink/compose?subject=${encodeURIComponent(event.subject)}&startdt=${event.start}&enddt=${event.end}&body=${encodeURIComponent(event.body)}&location=${encodeURIComponent(event.location)}`;
            window.open(url, '_blank');
        }

        function downloadICS() {
            const icsContent = `BEGIN:VCALENDAR
VERSION:2.0
PRODID:-//Salem Dominion Ministries//Pastor Booking//EN
BEGIN:VEVENT
UID:<?php echo $booking['booking_reference']; ?>@salemdominionministries.com
DTSTART:<?php echo date('Ymd\THis', strtotime($booking['booking_date'] . ' ' . $booking['start_time'])); ?>
DTEND:<?php echo date('Ymd\THis', strtotime($booking['booking_date'] . ' ' . $booking['end_time'])); ?>
SUMMARY:Pastor Appointment - <?php echo htmlspecialchars($booking['booking_type']); ?>
DESCRIPTION:Booking Reference: <?php echo htmlspecialchars($booking['booking_reference']); ?>\\nGoogle Meet: <?php echo htmlspecialchars($booking['google_meet_link']); ?>\\nJoin Code: <?php echo htmlspecialchars($booking['meet_join_code']); ?>
LOCATION:<?php echo htmlspecialchars($booking['google_meet_link']); ?>
STATUS:CONFIRMED
END:VEVENT
END:VCALENDAR`;

            const blob = new Blob([icsContent], { type: 'text/calendar' });
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `pastor-appointment-${<?php echo $booking['booking_reference']; ?>}.ics`;
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            window.URL.revokeObjectURL(url);
        }

        // Print functionality
        function printConfirmation() {
            window.print();
        }
    </script>
</body>
</html>
