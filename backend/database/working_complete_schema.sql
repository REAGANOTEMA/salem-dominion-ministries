-- ============================================
-- SALEM DOMINION MINISTRIES - SIMPLIFIED WORKING SCHEMA
-- Step-by-step creation to ensure all tables work
-- ============================================

-- Create database if it doesn't exist
CREATE DATABASE IF NOT EXISTS salem_dominion_ministries CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE salem_dominion_ministries;

-- ============================================
-- STEP 1: USER MANAGEMENT TABLES
-- ============================================

-- Users table
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('admin', 'pastor', 'member', 'visitor', 'teacher') DEFAULT 'visitor',
    is_active BOOLEAN DEFAULT TRUE,
    email_verified BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- User sessions
CREATE TABLE user_sessions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    session_token VARCHAR(255) UNIQUE NOT NULL,
    expires_at TIMESTAMP NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Password resets
CREATE TABLE password_resets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    token VARCHAR(255) UNIQUE NOT NULL,
    expires_at TIMESTAMP NOT NULL,
    used BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ============================================
-- STEP 2: CHURCH OPERATIONS TABLES
-- ============================================

-- Events
CREATE TABLE events (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    event_date DATETIME NOT NULL,
    location VARCHAR(255),
    status ENUM('upcoming', 'ongoing', 'completed', 'cancelled') DEFAULT 'upcoming',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Event registrations
CREATE TABLE event_registrations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    event_id INT NOT NULL,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL,
    registration_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('registered', 'confirmed', 'cancelled') DEFAULT 'registered'
);

-- Sermons
CREATE TABLE sermons (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    preacher VARCHAR(255),
    bible_reference VARCHAR(100),
    sermon_date DATE NOT NULL,
    description TEXT,
    status ENUM('draft', 'published', 'archived') DEFAULT 'draft',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Blog posts
CREATE TABLE blog_posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE NOT NULL,
    excerpt TEXT,
    content LONGTEXT,
    status ENUM('draft', 'published', 'archived') DEFAULT 'draft',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Prayer requests
CREATE TABLE prayer_requests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    requester_name VARCHAR(255) NOT NULL,
    requester_email VARCHAR(255),
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    is_public BOOLEAN DEFAULT TRUE,
    status ENUM('pending', 'praying', 'answered', 'closed') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Prayer responses
CREATE TABLE prayer_responses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    prayer_request_id INT NOT NULL,
    responder_name VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Donations
CREATE TABLE donations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    donor_name VARCHAR(255) NOT NULL,
    donor_email VARCHAR(255),
    amount DECIMAL(10,2) NOT NULL,
    donation_type ENUM('tithe', 'offering', 'special', 'building_fund', 'missions', 'children_ministry', 'other') DEFAULT 'offering',
    status ENUM('pending', 'completed', 'failed', 'refunded') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Contact messages
CREATE TABLE contact_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    subject VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    status ENUM('unread', 'read', 'responded', 'closed') DEFAULT 'unread',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Gallery
CREATE TABLE gallery (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    file_url VARCHAR(500) NOT NULL,
    file_type ENUM('image', 'video') DEFAULT 'image',
    status ENUM('draft', 'published', 'archived') DEFAULT 'draft',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Service times
CREATE TABLE service_times (
    id INT AUTO_INCREMENT PRIMARY KEY,
    service_name VARCHAR(255) NOT NULL,
    day_of_week ENUM('sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday') NOT NULL,
    start_time TIME NOT NULL,
    end_time TIME NOT NULL,
    location VARCHAR(255),
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Ministries
CREATE TABLE ministries (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    leader_name VARCHAR(255),
    meeting_day VARCHAR(100),
    meeting_time VARCHAR(100),
    location VARCHAR(255),
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Testimonials
CREATE TABLE testimonials (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255),
    occupation VARCHAR(255),
    testimonial TEXT NOT NULL,
    rating INT CHECK (rating >= 1 AND rating <= 5),
    is_approved BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- News
CREATE TABLE news (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE NOT NULL,
    excerpt TEXT,
    content LONGTEXT,
    status ENUM('draft', 'published', 'archived') DEFAULT 'draft',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Activity logs
CREATE TABLE activity_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    action VARCHAR(255) NOT NULL,
    table_name VARCHAR(100),
    record_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ============================================
-- STEP 3: CHILDREN'S MINISTRY TABLES
-- ============================================

-- Children classes
CREATE TABLE children_classes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    class_name VARCHAR(100) NOT NULL,
    age_group VARCHAR(50) NOT NULL,
    description TEXT,
    capacity INT DEFAULT 30,
    meeting_time VARCHAR(100),
    meeting_room VARCHAR(100),
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Children registration
CREATE TABLE children_registration (
    id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    date_of_birth DATE NOT NULL,
    age INT NOT NULL,
    gender ENUM('male', 'female') NOT NULL,
    parent_name VARCHAR(255) NOT NULL,
    parent_email VARCHAR(255) NOT NULL,
    parent_phone VARCHAR(20) NOT NULL,
    class_id INT,
    status ENUM('active', 'inactive', 'graduated') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Children events
CREATE TABLE children_events (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    event_date DATETIME NOT NULL,
    location VARCHAR(255),
    age_group VARCHAR(100),
    status ENUM('draft', 'published', 'cancelled', 'completed') DEFAULT 'draft',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Children lessons
CREATE TABLE children_lessons (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    bible_verse VARCHAR(255),
    lesson_content LONGTEXT,
    age_group VARCHAR(50),
    class_id INT,
    lesson_date DATE,
    status ENUM('draft', 'published') DEFAULT 'draft',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Children attendance
CREATE TABLE children_attendance (
    id INT AUTO_INCREMENT PRIMARY KEY,
    child_id INT NOT NULL,
    class_id INT NOT NULL,
    attendance_date DATE NOT NULL,
    attended BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Children gallery
CREATE TABLE children_gallery (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    file_url VARCHAR(500) NOT NULL,
    file_type ENUM('image', 'video') DEFAULT 'image',
    status ENUM('draft', 'published') DEFAULT 'draft',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Children teachers
CREATE TABLE children_teachers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    bio TEXT,
    experience_years INT DEFAULT 0,
    status ENUM('active', 'inactive', 'on_leave') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Children parents
CREATE TABLE children_parents (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    full_name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    church_member BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Children resources
CREATE TABLE children_resources (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    resource_type ENUM('lesson_plan', 'activity_sheet', 'coloring_page', 'video', 'song', 'story', 'game') DEFAULT 'lesson_plan',
    age_group VARCHAR(50),
    status ENUM('draft', 'published') DEFAULT 'draft',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Children news
CREATE TABLE children_news (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE,
    content LONGTEXT,
    status ENUM('draft', 'published') DEFAULT 'draft',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ============================================
-- STEP 4: INSERT SAMPLE DATA
-- ============================================

-- Admin user
INSERT INTO users (first_name, last_name, email, password_hash, role, is_active, email_verified) 
VALUES ('Admin', 'User', 'admin@salemministries.com', '$2a$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', TRUE, TRUE);

-- Service times
INSERT INTO service_times (service_name, day_of_week, start_time, end_time, location) VALUES
('1st Service', 'sunday', '08:00:00', '09:30:00', 'Main Sanctuary'),
('2nd Service', 'sunday', '10:30:00', '12:00:00', 'Main Sanctuary'),
('Prayers', 'wednesday', '17:30:00', '18:30:00', 'Prayer Room');

-- Ministries
INSERT INTO ministries (name, description, leader_name, meeting_day, meeting_time, location) VALUES
('Children Ministry', 'Spiritual education for children ages 0-13', 'Children Ministry Director', 'Sunday', '08:00 AM & 10:30 AM', 'Children Wing'),
('Youth Ministry', 'Empowering teenagers to live for Christ', 'Youth Director', 'Friday', '17:00 PM', 'Youth Center'),
('Women Ministry', 'Fellowship and spiritual growth for women', 'Women Ministry Leader', 'Tuesday', '18:00 PM', 'Fellowship Hall');

-- Children classes
INSERT INTO children_classes (class_name, age_group, description, capacity, meeting_time, meeting_room) VALUES
('Nursery Class', '0-2 years', 'Safe environment for infants and toddlers', 15, 'Sunday 8:00 AM & 10:30 AM', 'Nursery Room'),
('Beginner Class', '3-5 years', 'Preschoolers learn about Jesus', 25, 'Sunday 8:00 AM & 10:30 AM', 'Classroom 1'),
('Primary Class', '6-8 years', 'Early elementary Bible truths', 30, 'Sunday 8:00 AM & 10:30 AM', 'Classroom 2'),
('Junior Class', '9-11 years', 'Upper elementary deeper study', 30, 'Sunday 8:00 AM & 10:30 AM', 'Classroom 3'),
('Pre-Teen Class', '12-13 years', 'Tweens navigate faith challenges', 25, 'Sunday 8:00 AM & 10:30 AM', 'Youth Room');

-- Sample events
INSERT INTO events (title, description, event_date, location, status) VALUES
('Sunday Service', 'Join us for powerful worship', '2024-03-31 10:30:00', 'Main Sanctuary', 'upcoming'),
('Bible Study', 'Deep dive into God''s Word', '2024-03-27 18:00:00', 'Fellowship Hall', 'upcoming');

-- Sample sermons
INSERT INTO sermons (title, preacher, bible_reference, sermon_date, description, status) VALUES
('The Power of Faith', 'Pastor John Smith', 'Hebrews 11:1', '2024-03-24', 'Discover transformative faith in God', 'published'),
('Walking in Love', 'Pastor Mary Johnson', '1 Corinthians 13', '2024-03-17', 'Learn to love others as Christ', 'published');

-- ============================================
-- STEP 5: VERIFICATION
-- ============================================

-- Show all tables
SHOW TABLES;

-- Count tables
SELECT COUNT(*) as total_tables FROM information_schema.tables 
WHERE table_schema = 'salem_dominion_ministries' AND table_type = 'BASE TABLE';

-- Verify key tables exist
SELECT 'Tables Created Successfully!' as message,
       (SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = 'salem_dominion_ministries' AND table_type = 'BASE TABLE') as table_count;
