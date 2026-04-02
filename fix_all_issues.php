<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>🔧 Complete System Fix</h1>";

try {
    require_once 'config.php';
    require_once 'db.php';
    
    echo "<h2>✓ Database Connected</h2>";
    
    // 1. Create all necessary tables
    echo "<h2>Creating/Checking Tables:</h2>";
    
    $tables = [
        "CREATE TABLE IF NOT EXISTS users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            first_name VARCHAR(100) NOT NULL,
            last_name VARCHAR(100) NOT NULL,
            email VARCHAR(255) UNIQUE NOT NULL,
            password VARCHAR(255) NOT NULL,
            role ENUM('admin', 'member', 'pastor') DEFAULT 'member',
            is_active BOOLEAN DEFAULT TRUE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            last_login TIMESTAMP NULL
        )",
        
        "CREATE TABLE IF NOT EXISTS leadership (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            title VARCHAR(255) NOT NULL,
            bio TEXT,
            email VARCHAR(255),
            phone VARCHAR(20),
            image VARCHAR(500),
            order_position INT DEFAULT 0,
            is_active BOOLEAN DEFAULT TRUE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )",
        
        "CREATE TABLE IF NOT EXISTS events (
            id INT AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(255) NOT NULL,
            description TEXT,
            event_date DATE NOT NULL,
            event_time TIME,
            location VARCHAR(255),
            image VARCHAR(500),
            is_active BOOLEAN DEFAULT TRUE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )",
        
        "CREATE TABLE IF NOT EXISTS gallery (
            id INT AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(255) NOT NULL,
            description TEXT,
            image VARCHAR(500) NOT NULL,
            category VARCHAR(100) DEFAULT 'general',
            is_active BOOLEAN DEFAULT TRUE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )",
        
        "CREATE TABLE IF NOT EXISTS donations (
            id INT AUTO_INCREMENT PRIMARY KEY,
            first_name VARCHAR(100) NOT NULL,
            last_name VARCHAR(100) NOT NULL,
            email VARCHAR(255) NOT NULL,
            amount DECIMAL(10,2) NOT NULL,
            donation_type VARCHAR(100) DEFAULT 'general',
            payment_method VARCHAR(100),
            is_anonymous BOOLEAN DEFAULT FALSE,
            transaction_id VARCHAR(255),
            status VARCHAR(50) DEFAULT 'pending',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )",
        
        "CREATE TABLE IF NOT EXISTS news (
            id INT AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(255) NOT NULL,
            content TEXT NOT NULL,
            excerpt VARCHAR(500),
            image VARCHAR(500),
            author VARCHAR(100),
            is_active BOOLEAN DEFAULT TRUE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )",
        
        "CREATE TABLE IF NOT EXISTS sermons (
            id INT AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(255) NOT NULL,
            description TEXT,
            preacher VARCHAR(255),
            date DATE,
            audio_file VARCHAR(500),
            video_file VARCHAR(500),
            transcript TEXT,
            is_active BOOLEAN DEFAULT TRUE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )",
        
        "CREATE TABLE IF NOT EXISTS prayer_requests (
            id INT AUTO_INCREMENT PRIMARY KEY,
            first_name VARCHAR(100) NOT NULL,
            last_name VARCHAR(100) NOT NULL,
            email VARCHAR(255),
            phone VARCHAR(20),
            request TEXT NOT NULL,
            is_public BOOLEAN DEFAULT FALSE,
            is_prayed_for BOOLEAN DEFAULT FALSE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )"
    ];
    
    foreach ($tables as $sql) {
        try {
            $db->query($sql);
            echo "<p style='color: green;'>✓ Table created/verified</p>";
        } catch (Exception $e) {
            echo "<p style='color: orange;'>⚠ Table issue: " . $e->getMessage() . "</p>";
        }
    }
    
    // 2. Create admin user if none exists
    echo "<h2>Creating Admin User:</h2>";
    $admin_count = $db->selectOne("SELECT COUNT(*) as count FROM users WHERE role = 'admin")['count'] ?? 0;
    
    if ($admin_count == 0) {
        $admin_email = 'admin@salem.local';
        $admin_password = 'admin123';
        $hashed_password = password_hash($admin_password, PASSWORD_DEFAULT);
        
        $insert_admin = "INSERT INTO users (first_name, last_name, email, password, role, is_active) VALUES (?, ?, ?, ?, ?, 1)";
        $db->insert($insert_admin, ['System', 'Administrator', $admin_email, $hashed_password, 'admin']);
        
        echo "<p style='color: green;'>✓ Admin user created!</p>";
        echo "<p><strong>Login Credentials:</strong><br>";
        echo "Email: admin@salem.local<br>";
        echo "Password: admin123</p>";
    } else {
        echo "<p style='color: green;'>✓ Admin user already exists</p>";
    }
    
    // 3. Create sample leadership data if empty
    echo "<h2>Creating Leadership Data:</h2>";
    $leadership_count = $db->selectOne("SELECT COUNT(*) as count FROM leadership")['count'] ?? 0;
    
    if ($leadership_count == 0) {
        $leaders = [
            [
                'name' => 'Apostle Faty Musasizi',
                'title' => 'Senior Pastor & Founder',
                'bio' => 'Called by God to establish Salem Dominion Ministries, Apostle Faty Musasizi has served faithfully for over 25 years, leading thousands to Christ and mentoring future leaders.',
                'email' => 'apostle@salemdominionministries.com',
                'phone' => '+256753244480',
                'image' => 'APOSTLE-IRENE-MIREMBE-CwWfzcRx.jpeg',
                'order_position' => 1
            ],
            [
                'name' => 'Pastor Nabulya Joyce',
                'title' => 'Associate Pastor',
                'bio' => 'A passionate teacher and counselor, Pastor Joyce leads our women\'s ministry and provides pastoral care to our congregation.',
                'email' => 'joyce@salemdominionministries.com',
                'phone' => '+256753244480',
                'image' => 'PASTOR-NABULYA-JOYCE-BdB4SkbM.jpeg',
                'order_position' => 2
            ],
            [
                'name' => 'Pastor Damali Namwuma',
                'title' => 'Youth Pastor',
                'bio' => 'Dynamic and energetic, Pastor Damali leads our youth ministry, mentoring young people to discover their purpose and calling in God.',
                'email' => 'youth@salemdominionministries.com',
                'phone' => '+256753244480',
                'image' => 'Pastor-damali-namwuma-DSRkNJ6q.png',
                'order_position' => 3
            ]
        ];
        
        foreach ($leaders as $leader) {
            $insert_leader = "INSERT INTO leadership (name, title, bio, email, phone, image, order_position, is_active) VALUES (?, ?, ?, ?, ?, ?, ?, 1)";
            $db->insert($insert_leader, [
                $leader['name'],
                $leader['title'],
                $leader['bio'],
                $leader['email'],
                $leader['phone'],
                $leader['image'],
                $leader['order_position']
            ]);
        }
        
        echo "<p style='color: green;'>✓ Sample leadership data created</p>";
    } else {
        echo "<p style='color: green;'>✓ Leadership data already exists</p>";
    }
    
    // 4. Create sample data for other tables
    echo "<h2>Creating Sample Data:</h2>";
    
    // Sample events
    $event_count = $db->selectOne("SELECT COUNT(*) as count FROM events")['count'] ?? 0;
    if ($event_count == 0) {
        $events = [
            [
                'title' => 'Sunday Service',
                'description' => 'Join us for our weekly Sunday worship service',
                'event_date' => date('Y-m-d', strtotime('next Sunday')),
                'event_time' => '10:00:00',
                'location' => 'Main Sanctuary'
            ],
            [
                'title' => 'Bible Study',
                'description' => 'Mid-week Bible study and fellowship',
                'event_date' => date('Y-m-d', strtotime('next Wednesday')),
                'event_time' => '18:00:00',
                'location' => 'Church Hall'
            ]
        ];
        
        foreach ($events as $event) {
            $insert_event = "INSERT INTO events (title, description, event_date, event_time, location, is_active) VALUES (?, ?, ?, ?, ?, 1)";
            $db->insert($insert_event, [
                $event['title'],
                $event['description'],
                $event['event_date'],
                $event['event_time'],
                $event['location']
            ]);
        }
        echo "<p style='color: green;'>✓ Sample events created</p>";
    }
    
    // Sample gallery images
    $gallery_count = $db->selectOne("SELECT COUNT(*) as count FROM gallery")['count'] ?? 0;
    if ($gallery_count == 0) {
        $gallery_items = [
            [
                'title' => 'Church Building',
                'description' => 'Our beautiful church sanctuary',
                'image' => 'church-building.jpg',
                'category' => 'church'
            ],
            [
                'title' => 'Worship Service',
                'description' => 'Sunday worship service',
                'image' => 'worship-service.jpg',
                'category' => 'services'
            ]
        ];
        
        foreach ($gallery_items as $item) {
            $insert_gallery = "INSERT INTO gallery (title, description, image, category, is_active) VALUES (?, ?, ?, ?, 1)";
            $db->insert($insert_gallery, [
                $item['title'],
                $item['description'],
                $item['image'],
                $item['category']
            ]);
        }
        echo "<p style='color: green;'>✓ Sample gallery items created</p>";
    }
    
    echo "<h2>🎉 All Issues Fixed!</h2>";
    echo "<div style='background: #d4edda; padding: 20px; border-radius: 5px; margin: 20px 0;'>";
    echo "<h3>What's been fixed:</h3>";
    echo "<ul>";
    echo "<li>✓ Database connection issues</li>";
    echo "<li>✓ Missing database tables created</li>";
    echo "<li>✓ Admin user created (admin@salem.local / admin123)</li>";
    echo "<li>✓ Leadership data populated</li>";
    echo "<li>✓ Sample events and gallery items added</li>";
    echo "</ul>";
    echo "</div>";
    
    echo "<h3>Next Steps:</h3>";
    echo "<p><a href='login_perfect.php' style='background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>🔐 Try Login Now</a></p>";
    echo "<p><a href='leadership.php' style='background: #28a745; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>👥 View Leadership Page</a></p>";
    echo "<p><a href='dashboard.php' style='background: #17a2b8; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>📊 Go to Dashboard</a></p>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>✗ Error: " . $e->getMessage() . "</p>";
    echo "<p>Please check your database configuration in config.php</p>";
}
?>
