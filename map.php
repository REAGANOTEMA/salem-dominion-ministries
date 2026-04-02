<?php
// Complete error suppression to prevent unwanted output
error_reporting(0);
ini_set('display_errors', 0);
ini_set('log_errors', 0);

// Buffer output to catch any accidental output
ob_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Find Us - Salem Dominion Ministries</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Montserrat:wght@300;400;500;600;700&family=Great+Vibes&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-blue: #1e3a8a;
            --light-blue: #3b82f6;
            --sky-blue: #60a5fa;
            --pale-blue: #dbeafe;
            --white: #ffffff;
            --heavenly-gold: #fbbf24;
            --text-dark: #1f2937;
            --text-light: #6b7280;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Montserrat', sans-serif;
            line-height: 1.6;
            color: var(--text-dark);
            background: var(--white);
        }

        /* Navigation */
        .navbar {
            background: var(--white) !important;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 1rem 0;
        }

        .navbar-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            font-family: 'Great Vibes', cursive;
            font-size: 2rem;
            font-weight: 700;
            color: var(--primary-blue) !important;
        }

        .navbar-brand img {
            height: 45px;
            width: auto;
            border-radius: 50%;
            background: var(--pale-blue);
            padding: 5px;
            box-shadow: 0 0 20px rgba(59, 130, 246, 0.3);
        }

        .navbar-nav .nav-link {
            color: var(--text-dark) !important;
            font-weight: 500;
            margin: 0 10px;
            transition: all 0.3s ease;
        }

        .navbar-nav .nav-link:hover,
        .navbar-nav .nav-link.active {
            color: var(--primary-blue) !important;
        }

        /* Hero Section */
        .hero-section {
            background: linear-gradient(135deg, var(--primary-blue) 0%, var(--light-blue) 50%, var(--sky-blue) 100%);
            color: var(--white);
            padding: 100px 0 60px;
            text-align: center;
        }

        .hero-title {
            font-family: 'Playfair Display', serif;
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .hero-subtitle {
            font-size: 1.2rem;
            opacity: 0.9;
        }

        /* Map Container */
        .map-section {
            padding: 80px 0;
            background: var(--white);
        }

        #map {
            height: 500px;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            border: 2px solid var(--pale-blue);
        }

        /* Contact Info */
        .contact-section {
            padding: 60px 0;
            background: linear-gradient(135deg, var(--pale-blue) 0%, var(--white) 100%);
        }

        .contact-card {
            background: var(--white);
            border-radius: 16px;
            padding: 2rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            border: 1px solid var(--pale-blue);
            transition: all 0.3s ease;
            height: 100%;
        }

        .contact-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
        }

        .contact-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, var(--light-blue), var(--sky-blue));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            color: var(--white);
            font-size: 1.5rem;
        }

        .contact-title {
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--primary-blue);
            margin-bottom: 1rem;
            text-align: center;
        }

        .contact-details {
            text-align: center;
        }

        .contact-details p {
            margin-bottom: 0.5rem;
            color: var(--text-light);
        }

        .contact-details strong {
            color: var(--text-dark);
        }

        /* Directions Section */
        .directions-section {
            padding: 60px 0;
            background: var(--white);
        }

        .directions-card {
            background: var(--white);
            border-radius: 16px;
            padding: 2rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            border: 1px solid var(--pale-blue);
        }

        .direction-item {
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            margin-bottom: 1.5rem;
            padding: 1rem;
            background: var(--pale-blue);
            border-radius: 12px;
        }

        .direction-number {
            width: 30px;
            height: 30px;
            background: var(--primary-blue);
            color: var(--white);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            flex-shrink: 0;
        }

        .direction-text {
            flex: 1;
            color: var(--text-dark);
        }

        /* Action Buttons */
        .action-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
            margin-top: 2rem;
        }

        .btn-action {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 15px 30px;
            border-radius: 50px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            border: none;
            font-size: 1rem;
        }

        .btn-primary {
            background: var(--primary-blue);
            color: var(--white);
            box-shadow: 0 4px 15px rgba(30, 58, 138, 0.3);
        }

        .btn-primary:hover {
            background: var(--light-blue);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(59, 130, 246, 0.4);
            color: var(--white);
        }

        .btn-secondary {
            background: var(--white);
            color: var(--primary-blue);
            border: 2px solid var(--primary-blue);
        }

        .btn-secondary:hover {
            background: var(--primary-blue);
            color: var(--white);
            transform: translateY(-2px);
        }

        .btn-gold {
            background: var(--heavenly-gold);
            color: var(--text-dark);
            box-shadow: 0 4px 15px rgba(251, 191, 36, 0.3);
        }

        .btn-gold:hover {
            background: #f59e0b;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(251, 191, 36, 0.4);
            color: var(--text-dark);
        }

        /* Footer */
        .footer {
            background: var(--primary-blue);
            color: var(--white);
            padding: 2rem 0 1rem;
            text-align: center;
        }

        .footer p {
            margin-bottom: 0.5rem;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .hero-title {
                font-size: 2rem;
            }
            
            .hero-subtitle {
                font-size: 1rem;
            }
            
            #map {
                height: 350px;
            }
            
            .contact-card,
            .directions-card {
                padding: 1.5rem;
            }
            
            .action-buttons {
                flex-direction: column;
                align-items: center;
            }
            
            .btn-action {
                width: 250px;
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <img src="public/images/logo.png" alt="Salem Dominion Ministries">
                <span>Salem Dominion Ministries</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="about.php">About</a></li>
                    <li class="nav-item"><a class="nav-link" href="ministries.php">Ministries</a></li>
                    <li class="nav-item"><a class="nav-link" href="events.php">Events</a></li>
                    <li class="nav-item"><a class="nav-link" href="sermons.php">Sermons</a></li>
                    <li class="nav-item"><a class="nav-link" href="news.php">News</a></li>
                    <li class="nav-item"><a class="nav-link" href="gallery.php">Gallery</a></li>
                    <li class="nav-item"><a class="nav-link active" href="map.php">Find Us</a></li>
                    <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <h1 class="hero-title">Find Us</h1>
            <p class="hero-subtitle">Visit Salem Dominion Ministries in Iganga, Uganda</p>
        </div>
    </section>

    <!-- Map Section -->
    <section class="map-section">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div id="map"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Information -->
    <section class="contact-section">
        <div class="container">
            <h2 class="text-center mb-5" style="font-family: 'Playfair Display', serif; color: var(--primary-blue);">Contact Information</h2>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="contact-card">
                        <div class="contact-icon">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <h3 class="contact-title">Location</h3>
                        <div class="contact-details">
                            <p><strong>Church:</strong> Salem Dominion Ministries</p>
                            <p><strong>Address:</strong> Main Street, Iganga Town</p>
                            <p><strong>Area:</strong> Near Iganga Market</p>
                            <p><strong>Country:</strong> Uganda</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="contact-card">
                        <div class="contact-icon">
                            <i class="fas fa-phone"></i>
                        </div>
                        <h3 class="contact-title">Phone</h3>
                        <div class="contact-details">
                            <p><strong>Main:</strong> +256753244480</p>
                            <p><strong>Office:</strong> +256753244480</p>
                            <p><strong>WhatsApp:</strong> +256753244480</p>
                            <p><strong>Emergency:</strong> +256753244480</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="contact-card">
                        <div class="contact-icon">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <h3 class="contact-title">Email</h3>
                        <div class="contact-details">
                            <p><strong>General:</strong> apostle@salemdominionministries.com</p>
                            <p><strong>Prayer:</strong> prayer@salemdominionministries.com</p>
                            <p><strong>Info:</strong> info@salemdominionministries.com</p>
                            <p><strong>Senior Pastor:</strong> Apostle Faty Musasizi</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Directions -->
    <section class="directions-section">
        <div class="container">
            <h2 class="text-center mb-5" style="font-family: 'Playfair Display', serif; color: var(--primary-blue);">How to Find Us</h2>
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <div class="directions-card">
                        <h3 class="text-center mb-4" style="color: var(--primary-blue);">Directions from Iganga Town Center</h3>
                        
                        <div class="direction-item">
                            <div class="direction-number">1</div>
                            <div class="direction-text">
                                Start from Iganga Town Center market area
                            </div>
                        </div>
                        
                        <div class="direction-item">
                            <div class="direction-number">2</div>
                            <div class="direction-text">
                                Head north on Main Street for approximately 500 meters
                            </div>
                        </div>
                        
                        <div class="direction-item">
                            <div class="direction-number">3</div>
                            <div class="direction-text">
                                Look for the large blue gate with "Salem Dominion Ministries" sign
                            </div>
                        </div>
                        
                        <div class="direction-item">
                            <div class="direction-number">4</div>
                            <div class="direction-text">
                                Turn right at the gate and proceed to the church entrance
                            </div>
                        </div>
                        
                        <div class="direction-item">
                            <div class="direction-number">5</div>
                            <div class="direction-text">
                            Welcome! You've arrived at Salem Dominion Ministries
                            </div>
                        </div>
                        
                        <div class="action-buttons">
                            <a href="tel:+256753244480" class="btn-action btn-primary">
                                <i class="fas fa-phone"></i> Call for Directions
                            </a>
                            <a href="https://wa.me/256753244480" class="btn-action btn-gold" target="_blank">
                                <i class="fab fa-whatsapp"></i> WhatsApp Us
                            </a>
                            <a href="contact.php" class="btn-action btn-secondary">
                                <i class="fas fa-envelope"></i> Send Message
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Service Times Reminder -->
    <section class="contact-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <div class="directions-card text-center">
                        <h3 style="color: var(--primary-blue); margin-bottom: 1rem;">Service Times</h3>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <p style="margin-bottom: 0.5rem;"><strong>Sunday Service:</strong></p>
                                <p style="color: var(--text-light); margin-bottom: 0;">10:00 AM - 12:00 PM</p>
                            </div>
                            <div class="col-md-6">
                                <p style="margin-bottom: 0.5rem;"><strong>Bible Study:</strong></p>
                                <p style="color: var(--text-light); margin-bottom: 0;">Wednesday 6:00 PM - 7:30 PM</p>
                            </div>
                            <div class="col-md-6">
                                <p style="margin-bottom: 0.5rem;"><strong>Prayer Meeting:</strong></p>
                                <p style="color: var(--text-light); margin-bottom: 0;">Friday 5:00 PM - 6:00 PM</p>
                            </div>
                            <div class="col-md-6">
                                <p style="margin-bottom: 0.5rem;"><strong>Youth Service:</strong></p>
                                <p style="color: var(--text-light); margin-bottom: 0;">Saturday 3:00 PM - 5:00 PM</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <p>&copy; 2026 Salem Dominion Ministries. All rights reserved.</p>
            <p>Main Street, Iganga Town, Uganda | +256753244480 | apostle@salemdominionministries.com</p>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    
    <script>
        // Initialize the map
        document.addEventListener('DOMContentLoaded', function() {
            // Iganga, Uganda coordinates (approximate)
            const churchLat = 0.6100;
            const churchLng = 33.4700;
            
            // Create the map
            const map = L.map('map').setView([churchLat, churchLng], 15);
            
            // Add OpenStreetMap tiles
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);
            
            // Custom church icon
            const churchIcon = L.divIcon({
                html: '<div style="background: linear-gradient(135deg, #1e3a8a, #3b82f6); color: white; width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 20px; border: 3px solid #fbbf24; box-shadow: 0 4px 10px rgba(0,0,0,0.3);"><i class="fas fa-church"></i></div>',
                iconSize: [40, 40],
                iconAnchor: [20, 40],
                popupAnchor: [0, -40],
                className: 'custom-church-icon'
            });
            
            // Add church marker
            const churchMarker = L.marker([churchLat, churchLng], { icon: churchIcon }).addTo(map);
            
            // Add popup to marker
            churchMarker.bindPopup(`
                <div style="text-align: center; padding: 10px;">
                    <h4 style="margin: 0 0 10px 0; color: #1e3a8a;">Salem Dominion Ministries</h4>
                    <p style="margin: 5px 0; color: #6b7280;">Main Street, Iganga Town, Uganda</p>
                    <p style="margin: 5px 0; color: #6b7280;">Near Iganga Market</p>
                    <p style="margin: 5px 0;"><strong>Senior Pastor:</strong> Apostle Faty Musasizi</p>
                    <p style="margin: 5px 0;"><strong>Phone:</strong> +256753244480</p>
                    <div style="margin-top: 10px;">
                        <a href="tel:+256753244480" style="background: #1e3a8a; color: white; padding: 5px 10px; border-radius: 5px; text-decoration: none; margin: 2px;">
                            <i class="fas fa-phone"></i> Call
                        </a>
                        <a href="https://wa.me/256753244480" style="background: #25d366; color: white; padding: 5px 10px; border-radius: 5px; text-decoration: none; margin: 2px;" target="_blank">
                            <i class="fab fa-whatsapp"></i> WhatsApp
                        </a>
                    </div>
                </div>
            `).openPopup();
            
            // Add some nearby landmarks for reference
            const landmarks = [
                {
                    name: "Iganga Market",
                    lat: 0.6080,
                    lng: 33.4680,
                    icon: 'fa-shopping-basket'
                },
                {
                    name: "Iganga Town Center",
                    lat: 0.6050,
                    lng: 33.4650,
                    icon: 'fa-city'
                },
                {
                    name: "Main Bus Stop",
                    lat: 0.6120,
                    lng: 33.4720,
                    icon: 'fa-bus'
                }
            ];
            
            landmarks.forEach(landmark => {
                const landmarkIcon = L.divIcon({
                    html: `<div style="background: #60a5fa; color: white; width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 14px; border: 2px solid #3b82f6;"><i class="fas ${landmark.icon}"></i></div>`,
                    iconSize: [30, 30],
                    iconAnchor: [15, 15],
                    className: 'landmark-icon'
                });
                
                L.marker([landmark.lat, landmark.lng], { icon: landmarkIcon })
                    .addTo(map)
                    .bindPopup(`<strong>${landmark.name}</strong><br>Landmark reference`);
            });
            
            // Add circle around church to show walking distance
            L.circle([churchLat, churchLng], {
                color: '#3b82f6',
                fillColor: '#dbeafe',
                fillOpacity: 0.3,
                radius: 500 // 500 meters
            }).addTo(map);
            
            // Add walking directions hint
            const walkingPath = [
                [0.6050, 33.4650], // Town center
                [0.6070, 33.4670], // Half way
                [0.6100, 33.4700]  // Church
            ];
            
            L.polyline(walkingPath, {
                color: '#fbbf24',
                weight: 4,
                opacity: 0.7,
                dashArray: '10, 10'
            }).addTo(map);
        });
        
        // Smooth scroll for navigation
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    </script>
</body>
</html>
<?php
// Clean any buffered output
ob_end_clean();
?>
