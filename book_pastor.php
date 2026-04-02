<?php
// Pastor Booking System with Calendar and WhatsApp Integration
require_once 'auth_system.php';
require_once 'db.php';

$errors = [];
$success = '';

// Handle booking form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];
    
    if ($action === 'submit_booking') {
        // Collect and validate form data
        $client_name = trim($_POST['client_name'] ?? '');
        $client_email = trim($_POST['client_email'] ?? '');
        $client_phone = trim($_POST['client_phone'] ?? '');
        $booking_date = $_POST['booking_date'] ?? '';
        $start_time = $_POST['start_time'] ?? '';
        $end_time = $_POST['end_time'] ?? '';
        $booking_type = $_POST['booking_type'] ?? 'counseling';
        $subject = trim($_POST['subject'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $prayer_points = trim($_POST['prayer_points'] ?? '');
        $special_requests = trim($_POST['special_requests'] ?? '');
        
        // Validation
        if (empty($client_name)) {
            $errors[] = 'Your name is required.';
        }
        
        if (empty($client_email) || !filter_var($client_email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Valid email address is required.';
        }
        
        if (empty($client_phone)) {
            $errors[] = 'Phone number is required.';
        }
        
        if (empty($booking_date)) {
            $errors[] = 'Booking date is required.';
        }
        
        if (empty($start_time)) {
            $errors[] = 'Start time is required.';
        }
        
        if (empty($errors)) {
            try {
                // Generate unique booking reference
                $booking_reference = 'BK' . date('Y') . strtoupper(substr(md5(uniqid()), 0, 6));
                
                // Calculate duration in minutes
                $start = new DateTime($booking_date . ' ' . $start_time);
                $end = new DateTime($booking_date . ' ' . $end_time);
                $duration = ($end->getTimestamp() - $start->getTimestamp()) / 60;
                
                // Insert booking into database
                $stmt = $conn->prepare("INSERT INTO pastor_bookings (booking_reference, client_name, client_email, client_phone, booking_date, start_time, end_time, duration_minutes, booking_type, subject, description, prayer_points, special_requests, status, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending', NOW())");
                
                $stmt->bind_param('sssssssissssss', $booking_reference, $client_name, $client_email, $client_phone, $booking_date, $start_time, $end_time, $duration, $booking_type, $subject, $description, $prayer_points, $special_requests);
                
                if ($stmt->execute()) {
                    $success = 'Your booking request has been submitted! We will contact you soon to confirm the appointment.';
                    
                    // Send WhatsApp message
                    sendWhatsAppNotification($booking_reference, $client_name, $client_phone, $booking_date, $start_time, $booking_type, $subject);
                    
                } else {
                    $errors[] = 'Failed to submit booking. Please try again.';
                }
                $stmt->close();
                
            } catch (Exception $e) {
                $errors[] = 'Database error: ' . $e->getMessage();
            }
        }
    }
}

// Get available pastors
try {
    $pastors = $db->select("SELECT id, first_name, last_name FROM users WHERE role = 'pastor' AND is_active = 1");
    
    // Get pastor availability
    $availability = $db->select("SELECT day_of_week, start_time, end_time FROM pastor_booking_availability WHERE is_active = 1 ORDER BY FIELD(day_of_week, 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday')");
    
    // Get existing bookings for the next 30 days
    $existing_bookings = $db->select("SELECT booking_date, start_time, end_time FROM pastor_bookings WHERE booking_date >= CURDATE() AND booking_date <= DATE_ADD(CURDATE(), INTERVAL 30 DAY) AND status != 'cancelled' ORDER BY booking_date, start_time");
    
} catch (Exception $e) {
    $pastors = [];
    $availability = [];
    $existing_bookings = [];
}

// WhatsApp notification function
function sendWhatsAppNotification($booking_reference, $client_name, $client_phone, $booking_date, $start_time, $booking_type, $subject) {
    $church_phone = '+256753244480'; // Your church WhatsApp number
    $church_name = 'Salem Dominion Ministries';
    
    $message = "🙏 *New Pastor Booking Request*\n\n";
    $message .= "*Church:* $church_name\n";
    $message .= "*Reference:* $booking_reference\n";
    $message .= "*Client:* $client_name\n";
    $message .= "*Phone:* $client_phone\n";
    $message .= "*Date:* " . date('F j, Y', strtotime($booking_date)) . "\n";
    $message .= "*Time:* " . date('g:i A', strtotime($start_time)) . "\n";
    $message .= "*Type:* " . ucfirst($booking_type) . "\n";
    $message .= "*Subject:* $subject\n\n";
    $message .= "Please follow up with the client to confirm this appointment.\n";
    $message .= "God bless! 🙏";
    
    // WhatsApp API URL (you can use Twilio or other WhatsApp API)
    $whatsapp_url = "https://wa.me/" . str_replace('+', '', $church_phone) . "?text=" . urlencode($message);
    
    // Log the WhatsApp URL for now (you can implement actual API call later)
    error_log("WhatsApp notification URL: " . $whatsapp_url);
    
    return $whatsapp_url;
}

// Generate time slots based on availability
function generateTimeSlots($availability, $existing_bookings, $selected_date) {
    $time_slots = [];
    $day_of_week = strtolower(date('l', strtotime($selected_date)));
    
    // Get availability for the selected day
    $day_availability = array_filter($availability, function($slot) use ($day_of_week) {
        return $slot['day_of_week'] === $day_of_week;
    });
    
    if (empty($day_availability)) {
        return $time_slots;
    }
    
    foreach ($day_availability as $avail) {
        $start_time = new DateTime($avail['start_time']);
        $end_time = new DateTime($avail['end_time']);
        
        // Generate 30-minute slots
        while ($start_time < $end_time) {
            $slot_end = clone $start_time;
            $slot_end->add(new DateInterval('PT30M'));
            
            if ($slot_end <= $end_time) {
                $slot_time = $start_time->format('H:i');
                $slot_end_time = $slot_end->format('H:i');
                
                // Check if slot is available (not booked)
                $is_available = true;
                foreach ($existing_bookings as $booking) {
                    if ($booking['booking_date'] === $selected_date) {
                        $booking_start = new DateTime($booking['start_time']);
                        $booking_end = new DateTime($booking['end_time']);
                        
                        if (($start_time >= $booking_start && $start_time < $booking_end) ||
                            ($slot_end > $booking_start && $slot_end <= $booking_end)) {
                            $is_available = false;
                            break;
                        }
                    }
                }
                
                if ($is_available) {
                    $time_slots[] = [
                        'time' => $slot_time,
                        'end_time' => $slot_end_time,
                        'display' => date('g:i A', strtotime($slot_time)) . ' - ' . date('g:i A', strtotime($slot_end_time))
                    ];
                }
                
                $start_time->add(new DateInterval('PT30M'));
            } else {
                break;
            }
        }
    }
    
    return $time_slots;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book a Pastor - Salem Dominion Ministries</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        }
        
        .booking-header {
            background: linear-gradient(135deg, #7c3aed 0%, #6d28d0 100%);
            color: white;
            padding: 80px 0 60px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        
        .booking-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1440 320'%3E%3Cpath fill='%23ffffff' fill-opacity='0.1' d='M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,122.7C672,117,768,139,864,138.7C960,139,1056,117,1152,96C1248,75,1344,53,1392,42.7L1440,32L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z'%3E%3C/path%3E%3C/svg%3E") no-repeat;
            background-size: cover;
            opacity: 0.3;
        }
        
        .booking-header h1 {
            font-size: 3rem;
            font-weight: bold;
            margin-bottom: 20px;
            position: relative;
            z-index: 1;
        }
        
        .booking-header p {
            font-size: 1.2rem;
            margin-bottom: 30px;
            position: relative;
            z-index: 1;
        }
        
        .booking-form {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            padding: 40px;
            margin: -30px auto 50px;
            max-width: 800px;
            position: relative;
            z-index: 10;
        }
        
        .form-control, .form-select {
            border-radius: 10px;
            border: 2px solid #e5e7eb;
            padding: 12px 20px;
            margin-bottom: 20px;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: #7c3aed;
            box-shadow: 0 0 0 0.2rem rgba(124, 58, 237, 0.25);
        }
        
        .btn-book {
            background: linear-gradient(135deg, #7c3aed 0%, #6d28d0 100%);
            border: none;
            border-radius: 10px;
            padding: 12px 30px;
            font-weight: 600;
            color: white;
            transition: all 0.3s ease;
        }
        
        .btn-book:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(124, 58, 237, 0.3);
            color: white;
        }
        
        .booking-type-card {
            background: #f8fafc;
            border: 2px solid #e5e7eb;
            border-radius: 15px;
            padding: 25px;
            text-align: center;
            margin-bottom: 20px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .booking-type-card:hover, .booking-type-card.selected {
            border-color: #7c3aed;
            background: linear-gradient(135deg, #f3e8ff 0%, #e9d5ff 100%);
            transform: translateY(-3px);
        }
        
        .booking-type-card i {
            font-size: 2.5rem;
            color: #7c3aed;
            margin-bottom: 15px;
        }
        
        .time-slot {
            display: inline-block;
            background: #f8fafc;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            padding: 10px 15px;
            margin: 5px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 0.9rem;
        }
        
        .time-slot:hover, .time-slot.selected {
            border-color: #7c3aed;
            background: #7c3aed;
            color: white;
        }
        
        .time-slot.disabled {
            background: #e5e7eb;
            color: #9ca3af;
            cursor: not-allowed;
            opacity: 0.6;
        }
        
        .alert {
            border-radius: 10px;
            border: none;
            margin-bottom: 20px;
        }
        
        .pastor-info {
            background: linear-gradient(135deg, #f3e8ff 0%, #e9d5ff 100%);
            border-left: 4px solid #7c3aed;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 30px;
        }
        
        .whatsapp-preview {
            background: #25d366;
            color: white;
            padding: 15px;
            border-radius: 10px;
            margin-top: 20px;
            font-family: monospace;
            font-size: 0.9rem;
            white-space: pre-wrap;
        }
        
        @media (max-width: 768px) {
            .booking-header h1 {
                font-size: 2rem;
            }
            
            .booking-form {
                padding: 30px 20px;
                margin: -20px 15px 30px;
            }
            
            .time-slot {
                font-size: 0.8rem;
                padding: 8px 12px;
            }
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
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="index.php">
                    <i class="fas fa-home"></i> Home
                </a>
                <a class="nav-link" href="donations.php">
                    <i class="fas fa-donate"></i> Give
                </a>
                <a class="nav-link active" href="book_pastor.php">
                    <i class="fas fa-calendar"></i> Book Pastor
                </a>
                <a class="nav-link" href="gallery.php">
                    <i class="fas fa-images"></i> Gallery
                </a>
                <a class="nav-link" href="events.php">
                    <i class="fas fa-calendar"></i> Events
                </a>
                <a class="nav-link" href="contact.php">
                    <i class="fas fa-envelope"></i> Contact
                </a>
            </div>
        </div>
    </nav>

    <!-- Booking Header -->
    <div class="booking-header">
        <div class="container">
            <h1><i class="fas fa-user-tie"></i> Book a Pastor</h1>
            <p>Schedule a personal appointment with our pastors for counseling, prayer, or guidance</p>
        </div>
    </div>

    <!-- Booking Form -->
    <div class="container">
        <div class="booking-form">
            <h2 class="text-center mb-4">Schedule Your Appointment</h2>
            
            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger">
                    <?php foreach ($errors as $error): ?>
                        <div><?php echo htmlspecialchars($error); ?></div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="alert alert-success">
                    <?php echo htmlspecialchars($success); ?>
                </div>
            <?php endif; ?>
            
            <div class="pastor-info">
                <h5><i class="fas fa-info-circle"></i> How It Works</h5>
                <p class="mb-0">1. Choose your appointment type and preferred date/time<br>
                2. Fill in your contact information and reason for visit<br>
                3. Submit your booking request<br>
                4. We'll send confirmation details to your WhatsApp and email<br>
                5. Pastor will contact you to confirm the appointment</p>
            </div>
            
            <form method="POST" action="book_pastor.php">
                <input type="hidden" name="action" value="submit_booking">
                
                <!-- Booking Type -->
                <h4 class="mb-3"><i class="fas fa-hand-holding-heart"></i> Appointment Type</h4>
                <div class="row">
                    <div class="col-md-4">
                        <div class="booking-type-card" onclick="selectBookingType('counseling')">
                            <i class="fas fa-comments"></i>
                            <h6>Counseling</h6>
                            <small class="text-muted">Personal guidance and support</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="booking-type-card" onclick="selectBookingType('prayer')">
                            <i class="fas fa-pray"></i>
                            <h6>Prayer</h6>
                            <small class="text-muted">Prayer and spiritual support</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="booking-type-card" onclick="selectBookingType('guidance')">
                            <i class="fas fa-compass"></i>
                            <h6>Guidance</h6>
                            <small class="text-muted">Life direction and advice</small>
                        </div>
                    </div>
                </div>
                
                <div class="row mt-3">
                    <div class="col-md-4">
                        <div class="booking-type-card" onclick="selectBookingType('deliverance')">
                            <i class="fas fa-cross"></i>
                            <h6>Deliverance</h6>
                            <small class="text-muted">Spiritual deliverance ministry</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="booking-type-card" onclick="selectBookingType('thanksgiving')">
                            <i class="fas fa-heart"></i>
                            <h6>Thanksgiving</h6>
                            <small class="text-muted">Give thanks and testimony</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="booking-type-card" onclick="selectBookingType('general')">
                            <i class="fas fa-users"></i>
                            <h6>General</h6>
                            <small class="text-muted">Other pastoral needs</small>
                        </div>
                    </div>
                </div>
                
                <input type="hidden" id="booking_type" name="booking_type" value="counseling">
                
                <!-- Date and Time Selection -->
                <h4 class="mb-3 mt-4"><i class="fas fa-calendar-alt"></i> Select Date & Time</h4>
                <div class="row">
                    <div class="col-md-6">
                        <label for="booking_date" class="form-label">Preferred Date *</label>
                        <input type="text" class="form-control" id="booking_date" name="booking_date" required
                               placeholder="Select a date" readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Available Time Slots *</label>
                        <div id="time_slots" class="mt-2">
                            <p class="text-muted">Please select a date first to see available time slots</p>
                        </div>
                        <input type="hidden" id="start_time" name="start_time" required>
                        <input type="hidden" id="end_time" name="end_time" required>
                    </div>
                </div>
                
                <!-- Personal Information -->
                <h4 class="mb-3 mt-4"><i class="fas fa-user"></i> Your Information</h4>
                <div class="row">
                    <div class="col-md-6">
                        <label for="client_name" class="form-label">Full Name *</label>
                        <input type="text" class="form-control" id="client_name" name="client_name" required
                               value="<?php echo htmlspecialchars($_POST['client_name'] ?? ''); ?>">
                    </div>
                    <div class="col-md-6">
                        <label for="client_email" class="form-label">Email Address *</label>
                        <input type="email" class="form-control" id="client_email" name="client_email" required
                               value="<?php echo htmlspecialchars($_POST['client_email'] ?? ''); ?>">
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <label for="client_phone" class="form-label">Phone Number *</label>
                        <input type="tel" class="form-control" id="client_phone" name="client_phone" required
                               value="<?php echo htmlspecialchars($_POST['client_phone'] ?? ''); ?>">
                    </div>
                    <div class="col-md-6">
                        <label for="subject" class="form-label">Subject *</label>
                        <input type="text" class="form-control" id="subject" name="subject" required
                               placeholder="Brief description of your visit"
                               value="<?php echo htmlspecialchars($_POST['subject'] ?? ''); ?>">
                    </div>
                </div>
                
                <!-- Visit Details -->
                <h4 class="mb-3 mt-4"><i class="fas fa-clipboard"></i> Visit Details</h4>
                <div class="mb-3">
                    <label for="description" class="form-label">Description *</label>
                    <textarea class="form-control" id="description" name="description" rows="4" required
                              placeholder="Please describe what you'd like to discuss with the pastor..."><?php echo htmlspecialchars($_POST['description'] ?? ''); ?></textarea>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <label for="prayer_points" class="form-label">Prayer Points (Optional)</label>
                        <textarea class="form-control" id="prayer_points" name="prayer_points" rows="2"
                                  placeholder="Specific prayer requests..."><?php echo htmlspecialchars($_POST['prayer_points'] ?? ''); ?></textarea>
                    </div>
                    <div class="col-md-6">
                        <label for="special_requests" class="form-label">Special Requests (Optional)</label>
                        <textarea class="form-control" id="special_requests" name="special_requests" rows="2"
                                  placeholder="Any special accommodations..."><?php echo htmlspecialchars($_POST['special_requests'] ?? ''); ?></textarea>
                    </div>
                </div>
                
                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-book btn-lg">
                        <i class="fas fa-calendar-check"></i> Submit Booking Request
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Include Footer -->
    <?php include 'components/ultimate_footer_new.php'; ?>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.js"></script>
    <script>
        // Initialize date picker
        flatpickr("#booking_date", {
            minDate: "today",
            maxDate: new Date().fpAdd(30, "days"),
            dateFormat: "Y-m-d",
            disable: [
                function(date) {
                    // Disable Sundays
                    return date.getDay() === 0;
                }
            ],
            onChange: function(selectedDates, dateStr) {
                if (dateStr) {
                    loadTimeSlots(dateStr);
                } else {
                    document.getElementById('time_slots').innerHTML = '<p class="text-muted">Please select a date first to see available time slots</p>';
                }
            }
        });
        
        // Booking type selection
        function selectBookingType(type) {
            document.querySelectorAll('.booking-type-card').forEach(card => {
                card.classList.remove('selected');
            });
            event.currentTarget.classList.add('selected');
            document.getElementById('booking_type').value = type;
        }
        
        // Load time slots for selected date
        function loadTimeSlots(date) {
            // This would normally call an API, but for now we'll simulate it
            const timeSlots = [
                { time: '09:00', end_time: '09:30', display: '9:00 AM - 9:30 AM' },
                { time: '09:30', end_time: '10:00', display: '9:30 AM - 10:00 AM' },
                { time: '10:00', end_time: '10:30', display: '10:00 AM - 10:30 AM' },
                { time: '10:30', end_time: '11:00', display: '10:30 AM - 11:00 AM' },
                { time: '11:00', end_time: '11:30', display: '11:00 AM - 11:30 AM' },
                { time: '14:00', end_time: '14:30', display: '2:00 PM - 2:30 PM' },
                { time: '14:30', end_time: '15:00', display: '2:30 PM - 3:00 PM' },
                { time: '15:00', end_time: '15:30', display: '3:00 PM - 3:30 PM' },
                { time: '15:30', end_time: '16:00', display: '3:30 PM - 4:00 PM' },
                { time: '16:00', end_time: '16:30', display: '4:00 PM - 4:30 PM' },
                { time: '16:30', end_time: '17:00', display: '4:30 PM - 5:00 PM' }
            ];
            
            let slotsHtml = '';
            timeSlots.forEach(slot => {
                slotsHtml += `<span class="time-slot" onclick="selectTimeSlot('${slot.time}', '${slot.end_time}')">${slot.display}</span>`;
            });
            
            document.getElementById('time_slots').innerHTML = slotsHtml;
        }
        
        // Time slot selection
        function selectTimeSlot(startTime, endTime) {
            document.querySelectorAll('.time-slot').forEach(slot => {
                slot.classList.remove('selected');
            });
            event.currentTarget.classList.add('selected');
            document.getElementById('start_time').value = startTime;
            document.getElementById('end_time').value = endTime;
        }
        
        // Set default selections
        document.addEventListener('DOMContentLoaded', function() {
            // Select default booking type
            document.querySelector('.booking-type-card').classList.add('selected');
        });
    </script>
</body>
</html>
