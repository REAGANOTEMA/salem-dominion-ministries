<?php
session_start();
require_once 'db.php';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $booking_reference = 'BK' . strtoupper(uniqid());
    $client_name = $_POST['client_name'];
    $client_email = $_POST['client_email'];
    $client_phone = $_POST['client_phone'];
    $booking_date = $_POST['booking_date'];
    $start_time = $_POST['start_time'];
    $duration = $_POST['duration'];
    $booking_type = $_POST['booking_type'];
    $subject = $_POST['subject'];
    $description = $_POST['description'];
    $prayer_points = $_POST['prayer_points'];
    
    // Calculate end time
    $end_time = date('H:i:s', strtotime($start_time) + ($duration * 60));
    
    // Generate Google Meet link (in production, use Google Meet API)
    $google_meet_link = 'https://meet.google.com/' . substr(md5(uniqid()), 0, 10);
    $meet_join_code = substr($google_meet_link, -10);
    
    // Insert booking
    $sql = "INSERT INTO pastor_bookings (booking_reference, pastor_id, client_name, client_email, client_phone, booking_date, start_time, end_time, duration_minutes, booking_type, google_meet_link, google_meet_id, meet_join_code, subject, description, prayer_points, status) VALUES (?, 2, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending')";
    $params = [$booking_reference, $client_name, $client_email, $client_phone, $booking_date, $start_time, $end_time, $duration, $booking_type, $google_meet_link, $meet_join_code, $meet_join_code, $subject, $description, $prayer_points];
    
    $db->query($sql, $params);
    
    // Send confirmation email (in production, implement email sending)
    $success_message = "Your booking has been confirmed! Reference: $booking_reference. Check your email for details.";
    
    // Redirect to confirmation page
    header("Location: pastor_booking_confirmation.php?ref=$booking_reference");
    exit;
}

// Get available time slots
$available_slots = $db->query("SELECT * FROM pastor_booking_availability WHERE is_active = 1 ORDER BY FIELD(day_of_week, 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'), start_time");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book a Pastor - Salem Dominion Ministries</title>
    
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

        /* Booking Header */
        .booking-header {
            background: var(--gradient-church);
            color: white;
            padding: 100px 0 50px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .booking-header::before {
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

        .booking-title {
            font-size: 3rem;
            font-weight: 900;
            margin-bottom: 1rem;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }

        /* Booking Form */
        .booking-container {
            max-width: 800px;
            margin: -50px auto 50px;
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            position: relative;
        }

        .booking-form {
            padding: 3rem;
        }

        .form-section {
            margin-bottom: 2rem;
        }

        .form-section-title {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            color: var(--primary-color);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .form-section-title i {
            color: var(--accent-color);
        }

        .form-control, .form-select {
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            padding: 12px 15px;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--accent-color);
            box-shadow: 0 0 0 0.2rem rgba(243, 156, 18, 0.25);
        }

        .form-label {
            font-weight: 600;
            color: var(--dark-color);
            margin-bottom: 0.5rem;
        }

        /* Booking Types */
        .booking-types {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .booking-type-card {
            background: white;
            border: 2px solid #e0e0e0;
            border-radius: 15px;
            padding: 1.5rem;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .booking-type-card:hover {
            border-color: var(--accent-color);
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .booking-type-card.active {
            border-color: var(--accent-color);
            background: linear-gradient(135deg, rgba(243, 156, 18, 0.1), rgba(243, 156, 18, 0.05));
        }

        .booking-type-icon {
            font-size: 2.5rem;
            color: var(--accent-color);
            margin-bottom: 1rem;
        }

        /* Time Slots */
        .time-slots {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
            gap: 0.5rem;
        }

        .time-slot {
            background: white;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            padding: 0.8rem;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .time-slot:hover {
            border-color: var(--accent-color);
            background: rgba(243, 156, 18, 0.1);
        }

        .time-slot.selected {
            border-color: var(--accent-color);
            background: var(--accent-color);
            color: white;
        }

        .time-slot.disabled {
            background: #f5f5f5;
            color: #ccc;
            cursor: not-allowed;
            opacity: 0.6;
        }

        /* Submit Button */
        .btn-submit {
            background: var(--gradient-primary);
            color: white;
            border: none;
            padding: 15px 40px;
            font-size: 1.1rem;
            font-weight: 600;
            border-radius: 50px;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .btn-submit:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        }

        /* Progress Steps */
        .progress-steps {
            display: flex;
            justify-content: space-between;
            margin-bottom: 3rem;
            position: relative;
        }

        .progress-step {
            flex: 1;
            text-align: center;
            position: relative;
        }

        .progress-step::before {
            content: '';
            position: absolute;
            top: 20px;
            left: 50%;
            right: -50%;
            height: 2px;
            background: #e0e0e0;
            z-index: 1;
        }

        .progress-step:last-child::before {
            display: none;
        }

        .progress-step.active::before {
            background: var(--accent-color);
        }

        .step-number {
            width: 40px;
            height: 40px;
            background: white;
            border: 2px solid #e0e0e0;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 0.5rem;
            font-weight: 700;
            position: relative;
            z-index: 2;
        }

        .progress-step.active .step-number {
            background: var(--accent-color);
            border-color: var(--accent-color);
            color: white;
        }

        .progress-step.completed .step-number {
            background: #28a745;
            border-color: #28a745;
            color: white;
        }

        .step-label {
            font-size: 0.9rem;
            color: #666;
        }

        .progress-step.active .step-label {
            color: var(--accent-color);
            font-weight: 600;
        }

        /* Available Times */
        .availability-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .day-availability {
            background: white;
            border: 2px solid #e0e0e0;
            border-radius: 15px;
            padding: 1.5rem;
        }

        .day-title {
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 1rem;
            text-align: center;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .booking-title {
                font-size: 2rem;
            }
            
            .booking-form {
                padding: 2rem 1.5rem;
            }
            
            .booking-types {
                grid-template-columns: 1fr;
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

    <!-- Booking Header -->
    <header class="booking-header">
        <div class="container">
            <h1 class="booking-title" data-aos="fade-up">Book a Pastor</h1>
            <p class="lead" data-aos="fade-up" data-aos-delay="100">Schedule a one-on-one session with our pastor for guidance, counseling, or prayer</p>
        </div>
    </header>

    <!-- Booking Form -->
    <div class="container">
        <div class="booking-container" data-aos="fade-up">
            <form class="booking-form" method="POST" id="bookingForm">
                <!-- Progress Steps -->
                <div class="progress-steps">
                    <div class="progress-step active" id="step1">
                        <div class="step-number">1</div>
                        <div class="step-label">Personal Info</div>
                    </div>
                    <div class="progress-step" id="step2">
                        <div class="step-number">2</div>
                        <div class="step-label">Booking Type</div>
                    </div>
                    <div class="progress-step" id="step3">
                        <div class="step-number">3</div>
                        <div class="step-label">Schedule</div>
                    </div>
                    <div class="progress-step" id="step4">
                        <div class="step-number">4</div>
                        <div class="step-label">Details</div>
                    </div>
                </div>

                <!-- Step 1: Personal Information -->
                <div class="form-section" id="section1">
                    <h3 class="form-section-title">
                        <i class="fas fa-user"></i> Personal Information
                    </h3>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Full Name *</label>
                            <input type="text" class="form-control" name="client_name" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email Address *</label>
                            <input type="email" class="form-control" name="client_email" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Phone Number *</label>
                            <input type="tel" class="form-control" name="client_phone" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Are you a church member?</label>
                            <select class="form-select" name="is_member">
                                <option value="">Select...</option>
                                <option value="yes">Yes</option>
                                <option value="no">No</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Step 2: Booking Type -->
                <div class="form-section" id="section2" style="display: none;">
                    <h3 class="form-section-title">
                        <i class="fas fa-calendar-check"></i> Booking Type
                    </h3>
                    <div class="booking-types">
                        <div class="booking-type-card" data-type="counseling">
                            <div class="booking-type-icon">
                                <i class="fas fa-comments"></i>
                            </div>
                            <h5>Counseling</h5>
                            <p class="small">Personal guidance and life advice</p>
                        </div>
                        <div class="booking-type-card" data-type="prayer">
                            <div class="booking-type-icon">
                                <i class="fas fa-praying-hands"></i>
                            </div>
                            <h5>Prayer</h5>
                            <p class="small">Personal prayer session</p>
                        </div>
                        <div class="booking-type-card" data-type="guidance">
                            <div class="booking-type-icon">
                                <i class="fas fa-compass"></i>
                            </div>
                            <h5>Spiritual Guidance</h5>
                            <p class="small">Biblical guidance and mentorship</p>
                        </div>
                        <div class="booking-type-card" data-type="deliverance">
                            <div class="booking-type-icon">
                                <i class="fas fa-cross"></i>
                            </div>
                            <h5>Deliverance</h5>
                            <p class="small">Spiritual deliverance ministry</p>
                        </div>
                        <div class="booking-type-card" data-type="thanksgiving">
                            <div class="booking-type-icon">
                                <i class="fas fa-heart"></i>
                            </div>
                            <h5>Thanksgiving</h5>
                            <p class="small">Share testimonies and thanks</p>
                        </div>
                        <div class="booking-type-card" data-type="general">
                            <div class="booking-type-icon">
                                <i class="fas fa-phone"></i>
                            </div>
                            <h5>General</h5>
                            <p class="small">General conversation</p>
                        </div>
                    </div>
                    <input type="hidden" name="booking_type" id="selectedType" required>
                </div>

                <!-- Step 3: Schedule -->
                <div class="form-section" id="section3" style="display: none;">
                    <h3 class="form-section-title">
                        <i class="fas fa-calendar-alt"></i> Schedule
                    </h3>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Preferred Date *</label>
                            <input type="date" class="form-control" name="booking_date" id="bookingDate" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Duration *</label>
                            <select class="form-select" name="duration" id="duration">
                                <option value="">Select duration...</option>
                                <option value="30">30 minutes</option>
                                <option value="45">45 minutes</option>
                                <option value="60">1 hour</option>
                                <option value="90">1.5 hours</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Available Time Slots</label>
                        <div class="time-slots" id="timeSlots">
                            <!-- Time slots will be populated dynamically -->
                        </div>
                    </div>
                    
                    <input type="hidden" name="start_time" id="selectedTime" required>
                </div>

                <!-- Step 4: Details -->
                <div class="form-section" id="section4" style="display: none;">
                    <h3 class="form-section-title">
                        <i class="fas fa-info-circle"></i> Additional Details
                    </h3>
                    
                    <div class="mb-3">
                        <label class="form-label">Subject/Topic *</label>
                        <input type="text" class="form-control" name="subject" placeholder="Brief description of what you'd like to discuss" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea class="form-control" name="description" rows="4" placeholder="Provide more details about your situation or what you'd like to discuss..."></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Prayer Points</label>
                        <textarea class="form-control" name="prayer_points" rows="3" placeholder="Specific prayer requests or points you'd like the pastor to pray about..."></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Special Requests</label>
                        <textarea class="form-control" name="special_requests" rows="2" placeholder="Any special requirements or preferences..."></textarea>
                    </div>
                </div>

                <!-- Navigation Buttons -->
                <div class="d-flex justify-content-between mt-4">
                    <button type="button" class="btn btn-secondary" id="prevBtn" onclick="changeStep(-1)" style="display: none;">Previous</button>
                    <button type="button" class="btn btn-primary" id="nextBtn" onclick="changeStep(1)">Next</button>
                    <button type="submit" class="btn-submit" id="submitBtn" style="display: none;">Book Appointment</button>
                </div>
            </form>
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

        // Form navigation
        let currentStep = 1;
        const totalSteps = 4;

        function changeStep(direction) {
            // Validate current step before moving forward
            if (direction > 0 && !validateStep(currentStep)) {
                return;
            }

            // Hide current section
            document.getElementById(`section${currentStep}`).style.display = 'none';
            document.getElementById(`step${currentStep}`).classList.remove('active');
            
            // Move to next/previous step
            currentStep += direction;
            
            // Show new section
            document.getElementById(`section${currentStep}`).style.display = 'block';
            document.getElementById(`step${currentStep}`).classList.add('active');
            
            // Mark previous steps as completed
            for (let i = 1; i < currentStep; i++) {
                document.getElementById(`step${i}`).classList.add('completed');
            }
            
            // Update buttons
            updateButtons();
            
            // Initialize step-specific functionality
            if (currentStep === 3) {
                initializeTimeSlots();
            }
        }

        function updateButtons() {
            const prevBtn = document.getElementById('prevBtn');
            const nextBtn = document.getElementById('nextBtn');
            const submitBtn = document.getElementById('submitBtn');
            
            prevBtn.style.display = currentStep > 1 ? 'block' : 'none';
            
            if (currentStep === totalSteps) {
                nextBtn.style.display = 'none';
                submitBtn.style.display = 'block';
            } else {
                nextBtn.style.display = 'block';
                submitBtn.style.display = 'none';
            }
        }

        function validateStep(step) {
            let isValid = true;
            
            switch(step) {
                case 1:
                    const name = document.querySelector('input[name="client_name"]').value;
                    const email = document.querySelector('input[name="client_email"]').value;
                    const phone = document.querySelector('input[name="client_phone"]').value;
                    
                    if (!name || !email || !phone) {
                        alert('Please fill in all required fields');
                        isValid = false;
                    }
                    break;
                    
                case 2:
                    const selectedType = document.getElementById('selectedType').value;
                    if (!selectedType) {
                        alert('Please select a booking type');
                        isValid = false;
                    }
                    break;
                    
                case 3:
                    const date = document.getElementById('bookingDate').value;
                    const time = document.getElementById('selectedTime').value;
                    const duration = document.getElementById('duration').value;
                    
                    if (!date || !time || !duration) {
                        alert('Please select date, time, and duration');
                        isValid = false;
                    }
                    break;
            }
            
            return isValid;
        }

        // Booking type selection
        document.querySelectorAll('.booking-type-card').forEach(card => {
            card.addEventListener('click', function() {
                document.querySelectorAll('.booking-type-card').forEach(c => c.classList.remove('active'));
                this.classList.add('active');
                document.getElementById('selectedType').value = this.dataset.type;
            });
        });

        // Time slots initialization
        function initializeTimeSlots() {
            const timeSlotsContainer = document.getElementById('timeSlots');
            const timeSlots = [
                '09:00', '09:30', '10:00', '10:30', '11:00', '11:30',
                '14:00', '14:30', '15:00', '15:30', '16:00', '16:30'
            ];
            
            timeSlotsContainer.innerHTML = '';
            
            timeSlots.forEach(time => {
                const slot = document.createElement('div');
                slot.className = 'time-slot';
                slot.textContent = time;
                slot.addEventListener('click', function() {
                    document.querySelectorAll('.time-slot').forEach(s => s.classList.remove('selected'));
                    this.classList.add('selected');
                    document.getElementById('selectedTime').value = this.textContent + ':00';
                });
                timeSlotsContainer.appendChild(slot);
            });
        }

        // Set minimum date to today
        document.getElementById('bookingDate').min = new Date().toISOString().split('T')[0];

        // Form submission
        document.getElementById('bookingForm').addEventListener('submit', function(e) {
            if (!validateStep(4)) {
                e.preventDefault();
                return;
            }
            
            // Show loading state
            const submitBtn = document.getElementById('submitBtn');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Booking...';
        });
    </script>
</body>
</html>
