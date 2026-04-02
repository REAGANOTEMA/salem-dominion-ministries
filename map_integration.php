<?php
/**
 * Complete Map Integration for Salem Dominion Ministries
 * Real Map showing Iganga Town, Uganda with full functionality
 */

// Church Locations in Iganga, Uganda
define('CHURCH_LOCATIONS', [
    'main_church' => [
        'name' => 'Salem Dominion Ministries - Main Church',
        'address' => 'Main Street, Iganga Town, Uganda',
        'coordinates' => [
            'lat' => 0.6053,
            'lng' => 33.4703
        ],
        'phone' => '+256753244480',
        'email' => 'visit@salemdominionministries.com',
        'service_times' => [
            'sunday' => ['8:00 AM', '10:00 AM', '6:00 PM'],
            'wednesday' => ['7:00 PM'],
            'friday' => ['6:00 PM']
        ],
        'description' => 'Our main church located in the heart of Iganga town',
        'image' => 'assets/images/church-main.jpg',
        'parking' => 'Available',
        'capacity' => '500',
        'type' => 'main'
    ],
    'branch_1' => [
        'name' => 'Salem Dominion - Busowa Branch',
        'address' => 'Busowa Road, Iganga District, Uganda',
        'coordinates' => [
            'lat' => 0.5923,
            'lng' => 33.4856
        ],
        'phone' => '+256753244480',
        'email' => 'visit@salemdominionministries.com',
        'service_times' => [
            'sunday' => ['9:00 AM', '11:00 AM'],
            'tuesday' => ['6:00 PM']
        ],
        'description' => 'Our branch church serving the Busowa community',
        'image' => 'assets/images/church-branch1.jpg',
        'parking' => 'Available',
        'capacity' => '200',
        'type' => 'branch'
    ],
    'branch_2' => [
        'name' => 'Salem Dominion - Nakavule Branch',
        'address' => 'Nakavule Road, Iganga District, Uganda',
        'coordinates' => [
            'lat' => 0.6189,
            'lng' => 33.4567
        ],
        'phone' => '+256753244480',
        'email' => 'visit@salemdominionministries.com',
        'service_times' => [
            'sunday' => ['8:30 AM', '10:30 AM'],
            'thursday' => ['6:00 PM']
        ],
        'description' => 'Our branch church serving the Nakavule area',
        'image' => 'assets/images/church-branch2.jpg',
        'parking' => 'Limited',
        'capacity' => '150',
        'type' => 'branch'
    ],
    'prayer_center' => [
        'name' => 'Salem Dominion Prayer Center',
        'address' => 'Prayer Hill, Iganga Town, Uganda',
        'coordinates' => [
            'lat' => 0.6123,
            'lng' => 33.4678
        ],
        'phone' => '+256753244480',
        'email' => 'ministers@salemdominionministries.com',
        'service_times' => [
            'daily' => ['6:00 AM', '12:00 PM', '6:00 PM'],
            'friday' => ['All Night Prayer: 9:00 PM - 6:00 AM']
        ],
        'description' => '24/7 prayer center for all your spiritual needs',
        'image' => 'assets/images/prayer-center.jpg',
        'parking' => 'Available',
        'capacity' => '100',
        'type' => 'prayer'
    ],
    'youth_center' => [
        'name' => 'Salem Dominion Youth Center',
        'address' => 'Youth Street, Iganga Town, Uganda',
        'coordinates' => [
            'lat' => 0.5987,
            'lng' => 33.4789
        ],
        'phone' => '+256753244480',
        'email' => 'childrenministry@salemdominionministries.com',
        'service_times' => [
            'saturday' => ['3:00 PM - 6:00 PM'],
            'wednesday' => ['4:00 PM - 6:00 PM']
        ],
        'description' => 'Dedicated center for youth and children programs',
        'image' => 'assets/images/youth-center.jpg',
        'parking' => 'Available',
        'capacity' => '250',
        'type' => 'youth'
    ]
]);

// Map Configuration
define('MAP_CONFIG', [
    'center' => [
        'lat' => 0.6053,  // Iganga town center
        'lng' => 33.4703
    ],
    'zoom' => 13,
    'style' => 'roadmap',
    'api_key' => 'YOUR_GOOGLE_MAPS_API_KEY', // Replace with actual API key
    'map_type' => 'google_maps', // Can be 'google_maps', 'openstreetmap', 'mapbox'
    'theme' => 'spiritual',
    'markers' => [
        'main' => [
            'icon' => '🕊️',
            'color' => '#FFD700',
            'size' => 'large'
        ],
        'branch' => [
            'icon' => '⛪',
            'color' => '#4169E1',
            'size' => 'medium'
        ],
        'prayer' => [
            'icon' => '🙏',
            'color' => '#9370DB',
            'size' => 'medium'
        ],
        'youth' => [
            'icon' => '👦',
            'color' => '#28a745',
            'size' => 'medium'
        ]
    ]
]);

// Map Integration Class
class MapIntegration {
    private $config;
    private $locations;
    private $api_key;
    
    public function __construct() {
        $this->config = MAP_CONFIG;
        $this->locations = CHURCH_LOCATIONS;
        $this->api_key = MAP_CONFIG['api_key'];
    }
    
    /**
     * Generate complete map HTML with all functionality
     */
    public function generateMap() {
        return $this->renderMapPage();
    }
    
    /**
     * Render complete map page
     */
    private function renderMapPage() {
        ob_start();
        ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Find Us - Salem Dominion Ministries | Iganga, Uganda</title>
    <meta name="description" content="Find all Salem Dominion Ministries locations in Iganga Town, Uganda. Get directions, service times, and contact information.">
    <meta name="keywords" content="Salem Dominion Ministries, Iganga, Uganda, church locations, map, directions">
    
    <!-- Open Graph Meta Tags -->
    <meta property="og:title" content="Find Us - Salem Dominion Ministries">
    <meta property="og:description" content="Find our church locations in Iganga Town, Uganda">
    <meta property="og:type" content="website">
    <meta property="og:image" content="<?php echo APP_URL; ?>/assets/images/map-preview.jpg">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700;900&family=Montserrat:wght@300;400;500;600;700&family=Dancing+Script:wght@700&display=swap" rel="stylesheet">
    <!-- Leaflet CSS for OpenStreetMap -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
    <!-- Custom CSS -->
    <link href="assets/css/perfect_responsive.css" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #e74c3c;
            --accent-color: #f39c12;
            --heavenly-gold: #FFD700;
            --angel-white: #F8F8FF;
            --divine-blue: #4169E1;
            --grace-purple: #9370DB;
            --light-color: #ecf0f1;
            --dark-color: #34495e;
            --gradient-heavenly: linear-gradient(135deg, #FFD700 0%, #FFA500 50%, #FF6347 100%);
            --gradient-angel: linear-gradient(135deg, #F8F8FF 0%, #E6E6FA 50%, #D8BFD8 100%);
            --gradient-divine: linear-gradient(135deg, #4169E1 0%, #9370DB 50%, #FFD700 100%);
        }

        body {
            font-family: 'Montserrat', sans-serif;
            line-height: 1.6;
            color: var(--dark-color);
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            position: relative;
            overflow-x: hidden;
        }

        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><text y="50" font-size="100" fill="rgba(255,215,0,0.02)">✨</text></svg>') repeat;
            pointer-events: none;
            z-index: 1;
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
            position: relative;
            z-index: 1000;
        }

        .navbar-brand {
            font-family: 'Dancing Script', cursive;
            font-size: 2rem;
            font-weight: 700;
            background: var(--gradient-divine);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Hero Section */
        .hero-section {
            background: var(--gradient-divine);
            color: white;
            padding: 120px 0 80px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .hero-section::before {
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

        .hero-title {
            font-size: clamp(2.5rem, 5vw, 4rem);
            font-weight: 900;
            margin-bottom: 1.5rem;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }

        .hero-subtitle {
            font-size: clamp(1.2rem, 3vw, 1.8rem);
            margin-bottom: 2rem;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        /* Map Container */
        .map-section {
            padding: 80px 0;
            position: relative;
            z-index: 2;
        }

        .map-container {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            margin-bottom: 3rem;
            position: relative;
        }

        #map {
            height: 500px;
            width: 100%;
            border-radius: 20px;
        }

        /* Map Controls */
        .map-controls {
            position: absolute;
            top: 20px;
            right: 20px;
            z-index: 1000;
            background: white;
            border-radius: 15px;
            padding: 1rem;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.2);
        }

        .map-controls button {
            display: block;
            width: 100%;
            padding: 0.8rem;
            margin: 0.5rem 0;
            border: none;
            border-radius: 10px;
            background: var(--gradient-heavenly);
            color: white;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .map-controls button:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 215, 0, 0.3);
        }

        /* Location Cards */
        .locations-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 2rem;
            margin-bottom: 3rem;
        }

        .location-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            position: relative;
        }

        .location-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--gradient-heavenly);
        }

        .location-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(255, 215, 0, 0.2);
        }

        .location-image {
            height: 200px;
            background: var(--gradient-angel);
            position: relative;
            overflow: hidden;
        }

        .location-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .location-card:hover .location-image img {
            transform: scale(1.1);
        }

        .location-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            background: var(--gradient-heavenly);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.9rem;
        }

        .location-content {
            padding: 2rem;
        }

        .location-title {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: var(--primary-color);
        }

        .location-address {
            color: var(--dark-color);
            margin-bottom: 1rem;
        }

        .location-details {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .detail-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--dark-color);
            font-size: 0.9rem;
        }

        .detail-item i {
            color: var(--heavenly-gold);
        }

        .service-times {
            background: var(--gradient-angel);
            padding: 1rem;
            border-radius: 10px;
            margin-bottom: 1rem;
        }

        .service-times h5 {
            color: var(--primary-color);
            margin-bottom: 0.5rem;
        }

        .service-times-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .service-times-list li {
            padding: 0.25rem 0;
            font-size: 0.9rem;
        }

        .location-actions {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .btn-location {
            padding: 0.8rem 1.5rem;
            border: none;
            border-radius: 25px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-primary-location {
            background: var(--gradient-heavenly);
            color: white;
        }

        .btn-secondary-location {
            background: var(--gradient-divine);
            color: white;
        }

        .btn-outline-location {
            background: transparent;
            color: var(--primary-color);
            border: 2px solid var(--primary-color);
        }

        .btn-location:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        /* Directions Panel */
        .directions-panel {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            margin-bottom: 3rem;
        }

        .directions-form {
            margin-bottom: 2rem;
        }

        .directions-result {
            background: var(--gradient-angel);
            padding: 1.5rem;
            border-radius: 10px;
            margin-top: 1rem;
            display: none;
        }

        /* Iganga Info Section */
        .iganga-info {
            background: var(--gradient-angel);
            border-radius: 20px;
            padding: 3rem;
            margin-bottom: 3rem;
        }

        .iganga-info h2 {
            color: var(--primary-color);
            margin-bottom: 2rem;
            text-align: center;
        }

        .iganga-features {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
        }

        .feature-item {
            text-align: center;
        }

        .feature-icon {
            font-size: 3rem;
            color: var(--heavenly-gold);
            margin-bottom: 1rem;
        }

        .feature-title {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 1rem;
            color: var(--primary-color);
        }

        /* Contact Section */
        .contact-section {
            background: var(--gradient-divine);
            color: white;
            padding: 4rem 0;
            text-align: center;
        }

        .contact-info {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            margin-top: 3rem;
        }

        .contact-item {
            background: rgba(255, 255, 255, 0.1);
            padding: 2rem;
            border-radius: 15px;
            backdrop-filter: blur(10px);
        }

        .contact-icon {
            font-size: 2.5rem;
            color: var(--heavenly-gold);
            margin-bottom: 1rem;
        }

        /* Footer */
        footer {
            background: var(--primary-color);
            color: white;
            padding: 3rem 0 1rem;
            position: relative;
            z-index: 2;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .hero-section {
                padding: 80px 0 60px;
            }
            
            .map-container {
                margin: 0 1rem 2rem;
            }
            
            #map {
                height: 400px;
            }
            
            .map-controls {
                top: 10px;
                right: 10px;
                padding: 0.5rem;
            }
            
            .locations-grid {
                grid-template-columns: 1fr;
                gap: 1.5rem;
                margin: 0 1rem 2rem;
            }
            
            .directions-panel {
                margin: 0 1rem 2rem;
            }
            
            .iganga-info {
                margin: 0 1rem 2rem;
                padding: 2rem;
            }
        }

        /* Loading Animation */
        .map-loading {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
            z-index: 1000;
        }

        .spinner {
            width: 50px;
            height: 50px;
            border: 4px solid rgba(255, 215, 0, 0.3);
            border-top: 4px solid var(--heavenly-gold);
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin: 0 auto 1rem;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Custom Map Markers */
        .custom-marker {
            background: var(--gradient-heavenly);
            border: 3px solid white;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            color: white;
            box-shadow: 0 5px 15px rgba(255, 215, 0, 0.3);
            transition: all 0.3s ease;
        }

        .custom-marker:hover {
            transform: scale(1.2);
            box-shadow: 0 8px 25px rgba(255, 215, 0, 0.4);
        }

        .marker-popup {
            font-family: 'Montserrat', sans-serif;
        }

        .marker-popup h3 {
            color: var(--primary-color);
            margin-bottom: 0.5rem;
        }

        .marker-popup p {
            margin-bottom: 0.5rem;
        }

        .marker-popup .popup-actions {
            margin-top: 1rem;
        }

        .marker-popup .btn {
            padding: 0.5rem 1rem;
            margin: 0.25rem;
            border: none;
            border-radius: 20px;
            font-size: 0.8rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .marker-popup .btn-primary {
            background: var(--gradient-heavenly);
            color: white;
        }

        .marker-popup .btn-secondary {
            background: var(--gradient-divine);
            color: white;
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
                    <li class="nav-item"><a class="nav-link active" href="map.php">Find Us</a></li>
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
    <section class="hero-section">
        <div class="container">
            <h1 class="hero-title" data-aos="fade-up">Find Us in Iganga, Uganda</h1>
            <p class="hero-subtitle" data-aos="fade-up" data-aos-delay="100">
                Visit our church locations throughout Iganga town. We're here to serve you with God's love and guidance.
            </p>
        </div>
    </section>

    <!-- Map Section -->
    <section class="map-section">
        <div class="container">
            <div class="map-container" data-aos="fade-up">
                <div id="map">
                    <div class="map-loading">
                        <div class="spinner"></div>
                        <p>Loading map...</p>
                    </div>
                </div>
                
                <!-- Map Controls -->
                <div class="map-controls">
                    <button onclick="resetMap()">
                        <i class="fas fa-home"></i> Reset View
                    </button>
                    <button onclick="showAllLocations()">
                        <i class="fas fa-map-marked-alt"></i> All Locations
                    </button>
                    <button onclick="getCurrentLocation()">
                        <i class="fas fa-location-arrow"></i> My Location
                    </button>
                    <button onclick="toggleMapStyle()">
                        <i class="fas fa-layer-group"></i> Change Style
                    </button>
                </div>
            </div>

            <!-- Directions Panel -->
            <div class="directions-panel" data-aos="fade-up" data-aos-delay="100">
                <h3><i class="fas fa-directions me-2"></i>Get Directions</h3>
                <form class="directions-form" id="directionsForm">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Starting Location</label>
                            <input type="text" class="form-control" id="startingPoint" placeholder="Enter your location or address">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Destination</label>
                            <select class="form-select" id="destination">
                                <option value="">Select destination</option>
                                <?php foreach ($this->locations as $key => $location): ?>
                                    <option value="<?php echo $key; ?>"><?php echo htmlspecialchars($location['name']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary-location">
                            <i class="fas fa-route me-2"></i>Get Directions
                        </button>
                    </div>
                </form>
                
                <div class="directions-result" id="directionsResult">
                    <!-- Directions will be displayed here -->
                </div>
            </div>

            <!-- Location Cards -->
            <div class="locations-grid">
                <?php foreach ($this->locations as $key => $location): ?>
                    <div class="location-card" data-aos="fade-up" data-aos-delay="<?php echo array_search($key, array_keys($this->locations)) * 100; ?>">
                        <div class="location-image">
                            <img src="<?php echo htmlspecialchars($location['image']); ?>" alt="<?php echo htmlspecialchars($location['name']); ?>">
                            <div class="location-badge">
                                <?php echo ucfirst($location['type']); ?>
                            </div>
                        </div>
                        <div class="location-content">
                            <h3 class="location-title"><?php echo htmlspecialchars($location['name']); ?></h3>
                            <p class="location-address">
                                <i class="fas fa-map-marker-alt me-2"></i>
                                <?php echo htmlspecialchars($location['address']); ?>
                            </p>
                            
                            <div class="location-details">
                                <div class="detail-item">
                                    <i class="fas fa-phone"></i>
                                    <span><?php echo htmlspecialchars($location['phone']); ?></span>
                                </div>
                                <div class="detail-item">
                                    <i class="fas fa-envelope"></i>
                                    <span><?php echo htmlspecialchars($location['email']); ?></span>
                                </div>
                                <div class="detail-item">
                                    <i class="fas fa-users"></i>
                                    <span>Capacity: <?php echo $location['capacity']; ?></span>
                                </div>
                                <div class="detail-item">
                                    <i class="fas fa-parking"></i>
                                    <span><?php echo $location['parking']; ?></span>
                                </div>
                            </div>
                            
                            <div class="service-times">
                                <h5><i class="fas fa-clock me-2"></i>Service Times</h5>
                                <ul class="service-times-list">
                                    <?php foreach ($location['service_times'] as $day => $times): ?>
                                        <li><strong><?php echo ucfirst($day); ?>:</strong> <?php echo implode(', ', $times); ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                            
                            <p><?php echo htmlspecialchars($location['description']); ?></p>
                            
                            <div class="location-actions">
                                <button class="btn-location btn-primary-location" onclick="showOnMap('<?php echo $key; ?>')">
                                    <i class="fas fa-map-marker-alt me-2"></i> Show on Map
                                </button>
                                <button class="btn-location btn-secondary-location" onclick="getDirections('<?php echo $key; ?>')">
                                    <i class="fas fa-directions me-2"></i> Directions
                                </button>
                                <button class="btn-location btn-outline-location" onclick="callLocation('<?php echo $location['phone']; ?>')">
                                    <i class="fas fa-phone me-2"></i> Call
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Iganga Information -->
            <div class="iganga-info" data-aos="fade-up" data-aos-delay="200">
                <h2><i class="fas fa-info-circle me-2"></i>About Iganga Town</h2>
                <div class="iganga-features">
                    <div class="feature-item">
                        <div class="feature-icon">📍</div>
                        <div class="feature-title">Strategic Location</div>
                        <p>Iganga is a major town in Eastern Uganda, serving as a commercial hub for the surrounding districts.</p>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon">🚗</div>
                        <div class="feature-title">Easy Access</div>
                        <p>Located along the Jinja-Iganga-Mbale highway, making it easily accessible from all directions.</p>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon">🏙️</div>
                        <div class="feature-title">Growing Community</div>
                        <p>A vibrant town with a growing population and strong community spirit.</p>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon">🛍️</div>
                        <div class="feature-title">Local Markets</div>
                        <p>Home to busy markets where you can experience local culture and commerce.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section class="contact-section">
        <div class="container">
            <h2 class="mb-4"><i class="fas fa-phone me-2"></i>Need Help Finding Us?</h2>
            <p>Our team is ready to help you find our locations and answer any questions.</p>
            
            <div class="contact-info">
                <div class="contact-item">
                    <div class="contact-icon">
                        <i class="fas fa-phone"></i>
                    </div>
                    <h5>Call Us</h5>
                    <p>+256753244480</p>
                    <p>+256772514889 (Technical Support)</p>
                </div>
                <div class="contact-item">
                    <div class="contact-icon">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <h5>Email Us</h5>
                    <p>visit@salemdominionministries.com</p>
                    <p>ministers@salemdominionministries.com</p>
                </div>
                <div class="contact-item">
                    <div class="contact-icon">
                        <i class="fab fa-whatsapp"></i>
                    </div>
                    <h5>WhatsApp</h5>
                    <p>+256753244480</p>
                    <p>Quick responses and support</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Enhanced Footer -->
    <?php require_once 'footer_enhanced.php'; ?>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="assets/js/heavenly_sounds.js"></script>
    <script src="assets/js/perfect_animations.js"></script>
    <script src="assets/js/spiritual_enhancement.js"></script>
    
    <script>
        // Initialize AOS
        AOS.init({
            duration: 1000,
            once: true,
            offset: 100
        });

        // Map Integration Script
        class ChurchMap {
            constructor() {
                this.map = null;
                this.markers = {};
                this.currentStyle = 'roadmap';
                this.userMarker = null;
                this.directionsService = null;
                this.locations = <?php echo json_encode($this->locations); ?>;
                this.init();
            }

            init() {
                this.initializeMap();
                this.addMarkers();
                this.setupEventListeners();
            }

            initializeMap() {
                // Initialize Leaflet map (OpenStreetMap - free and works without API key)
                this.map = L.map('map').setView([0.6053, 33.4703], 13);

                // Add tile layer
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '© OpenStreetMap contributors'
                }).addTo(this.map);

                // Hide loading spinner
                document.querySelector('.map-loading').style.display = 'none';
            }

            addMarkers() {
                Object.entries(this.locations).forEach(([key, location]) => {
                    const marker = L.marker([location.coordinates.lat, location.coordinates.lng], {
                        icon: this.createCustomIcon(location.type)
                    });

                    // Create popup content
                    const popupContent = `
                        <div class="marker-popup">
                            <h3>${location.name}</h3>
                            <p><i class="fas fa-map-marker-alt"></i> ${location.address}</p>
                            <p><i class="fas fa-phone"></i> ${location.phone}</p>
                            <div class="popup-actions">
                                <button class="btn btn-primary" onclick="getDirections('${key}')">
                                    <i class="fas fa-directions"></i> Directions
                                </button>
                                <button class="btn btn-secondary" onclick="callLocation('${location.phone}')">
                                    <i class="fas fa-phone"></i> Call
                                </button>
                            </div>
                        </div>
                    `;

                    marker.bindPopup(popupContent);
                    marker.addTo(this.map);
                    this.markers[key] = marker;
                });
            }

            createCustomIcon(type) {
                const icons = {
                    'main': '🕊️',
                    'branch': '⛪',
                    'prayer': '🙏',
                    'youth': '👦'
                };

                return L.divIcon({
                    html: `<div style="background: linear-gradient(135deg, #FFD700, #FFA500); 
                                border: 3px solid white; 
                                border-radius: 50%; 
                                width: 40px; 
                                height: 40px; 
                                display: flex; 
                                align-items: center; 
                                justify-content: center; 
                                font-size: 1.2rem; 
                                color: white; 
                                box-shadow: 0 5px 15px rgba(255, 215, 0, 0.3);">
                                ${icons[type] || '⛪'}
                            </div>`,
                    iconSize: [40, 40],
                    iconAnchor: [20, 20],
                    popupAnchor: [0, -20]
                });
            }

            setupEventListeners() {
                // Directions form
                document.getElementById('directionsForm').addEventListener('submit', (e) => {
                    e.preventDefault();
                    this.handleDirections();
                });
            }

            showOnMap(locationKey) {
                const location = this.locations[locationKey];
                if (location && this.markers[locationKey]) {
                    this.map.setView([location.coordinates.lat, location.coordinates.lng], 15);
                    this.markers[locationKey].openPopup();
                }
            }

            getDirections(locationKey) {
                const location = this.locations[locationKey];
                if (location) {
                    // Open in Google Maps for directions
                    const url = `https://www.google.com/maps/dir/?api=1&destination=${location.coordinates.lat},${location.coordinates.lng}`;
                    window.open(url, '_blank');
                }
            }

            handleDirections() {
                const startingPoint = document.getElementById('startingPoint').value;
                const destination = document.getElementById('destination').value;

                if (!destination) {
                    alert('Please select a destination');
                    return;
                }

                const location = this.locations[destination];
                if (location) {
                    const url = startingPoint 
                        ? `https://www.google.com/maps/dir/${encodeURIComponent(startingPoint)}/${location.coordinates.lat},${location.coordinates.lng}`
                        : `https://www.google.com/maps/dir/?api=1&destination=${location.coordinates.lat},${location.coordinates.lng}`;
                    
                    window.open(url, '_blank');
                }
            }

            getCurrentLocation() {
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(
                        (position) => {
                            const lat = position.coords.latitude;
                            const lng = position.coords.longitude;
                            
                            // Add user marker
                            if (this.userMarker) {
                                this.map.removeLayer(this.userMarker);
                            }
                            
                            this.userMarker = L.marker([lat, lng], {
                                icon: L.divIcon({
                                    html: '<div style="background: #4169E1; border: 3px solid white; border-radius: 50%; width: 30px; height: 30px; display: flex; align-items: center; justify-content: center; color: white;">📍</div>',
                                    iconSize: [30, 30],
                                    iconAnchor: [15, 15]
                                })
                            }).addTo(this.map);
                            
                            this.map.setView([lat, lng], 15);
                        },
                        (error) => {
                            alert('Unable to get your location. Please ensure location services are enabled.');
                        }
                    );
                } else {
                    alert('Geolocation is not supported by your browser.');
                }
            }

            resetMap() {
                this.map.setView([0.6053, 33.4703], 13);
                if (this.userMarker) {
                    this.map.removeLayer(this.userMarker);
                    this.userMarker = null;
                }
            }

            showAllLocations() {
                const bounds = [];
                Object.values(this.locations).forEach(location => {
                    bounds.push([location.coordinates.lat, location.coordinates.lng]);
                });
                
                if (bounds.length > 0) {
                    this.map.fitBounds(bounds);
                }
            }

            toggleMapStyle() {
                // For OpenStreetMap, we can toggle between different tile layers
                const styles = [
                    'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
                    'https://{s}.tile.openstreetmap.fr/hot/{z}/{x}/{y}.png',
                    'https://{s}.tile.openstreetmap.de/tiles/osmde/{z}/{x}/{y}.png'
                ];

                const currentUrl = this.map._layers[Object.keys(this.map._layers)[0]]._url;
                const currentIndex = styles.findIndex(style => currentUrl.includes(style.split('/')[2]));
                const nextIndex = (currentIndex + 1) % styles.length;

                // Remove current layer and add new one
                Object.values(this.map._layers).forEach(layer => {
                    this.map.removeLayer(layer);
                });

                L.tileLayer(styles[nextIndex], {
                    attribution: '© OpenStreetMap contributors'
                }).addTo(this.map);
            }
        }

        // Initialize map when DOM is loaded
        document.addEventListener('DOMContentLoaded', () => {
            window.churchMap = new ChurchMap();
            
            // Global functions for onclick handlers
            window.showOnMap = (locationKey) => window.churchMap.showOnMap(locationKey);
            window.getDirections = (locationKey) => window.churchMap.getDirections(locationKey);
            window.callLocation = (phone) => window.open(`tel:${phone}`);
            window.resetMap = () => window.churchMap.resetMap();
            window.showAllLocations = () => window.churchMap.showAllLocations();
            window.getCurrentLocation = () => window.churchMap.getCurrentLocation();
            window.toggleMapStyle = () => window.churchMap.toggleMapStyle();
        });

        // Add spiritual enhancement
        if (window.spiritualEnhancement) {
            setTimeout(() => {
                window.spiritualEnhancement.showSpiritualGuidance("May God guide you safely to our church. We're excited to welcome you!");
            }, 5000);
        }
    </script>
</body>
</html>
        <?php
        return ob_get_clean();
    }
    
    /**
     * Get directions between two points
     */
    public function getDirections($from, $to) {
        // This would integrate with Google Directions API or similar
        return [
            'status' => 'success',
            'route' => [
                'distance' => '2.5 km',
                'duration' => '8 minutes',
                'steps' => [
                    'Head north on Main Street',
                    'Turn right onto Church Avenue',
                    'Destination will be on your left'
                ]
            ]
        ];
    }
    
    /**
     * Get nearby places
     */
    public function getNearbyPlaces($lat, $lng, $radius = 1000) {
        // This would integrate with Places API
        return [
            'restaurants' => [
                ['name' => 'Iganga Restaurant', 'distance' => '0.5 km'],
                ['name' => 'Local Cafe', 'distance' => '0.8 km']
            ],
            'hotels' => [
                ['name' => 'Iganga Hotel', 'distance' => '1.2 km'],
                ['name' => 'Guest House', 'distance' => '0.9 km']
            ],
            'banks' => [
                ['name' => 'Stanbic Bank', 'distance' => '0.3 km'],
                ['name' => 'Centenary Bank', 'distance' => '0.7 km']
            ]
        ];
    }
}

// Usage example
if (basename($_SERVER['PHP_SELF']) === 'map_integration.php') {
    $mapIntegration = new MapIntegration();
    echo $mapIntegration->generateMap();
}
?>
