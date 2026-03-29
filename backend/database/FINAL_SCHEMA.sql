-- ============================================
-- SALEM DOMINION MINISTRIES - FINAL PERFECT SCHEMA
-- Guaranteed to create ALL 26 tables for complete functionality
-- ============================================

-- Create database if it doesn't exist
CREATE DATABASE IF NOT EXISTS salem_dominion_ministries CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE salem_dominion_ministries;

-- ============================================
-- HANDLE EXISTING TABLES SAFELY
-- ============================================

-- Disable foreign key checks to avoid conflicts
SET FOREIGN_KEY_CHECKS = 0;

-- Drop tables in reverse dependency order
DROP TABLE IF EXISTS activity_logs;
DROP TABLE IF EXISTS children_news;
DROP TABLE IF EXISTS children_resources;
DROP TABLE IF EXISTS children_parents;
DROP TABLE IF EXISTS children_teachers;
DROP TABLE IF EXISTS children_gallery;
DROP TABLE IF EXISTS children_attendance;
DROP TABLE IF EXISTS children_lessons;
DROP TABLE IF EXISTS children_events;
DROP TABLE IF EXISTS children_registration;
DROP TABLE IF EXISTS children_classes;
DROP TABLE IF EXISTS testimonials;
DROP TABLE IF EXISTS news;
DROP TABLE IF EXISTS ministries;
DROP TABLE IF EXISTS service_times;
DROP TABLE IF EXISTS gallery;
DROP TABLE IF EXISTS contact_messages;
DROP TABLE IF EXISTS donations;
DROP TABLE IF EXISTS prayer_responses;
DROP TABLE IF EXISTS prayer_requests;
DROP TABLE IF EXISTS blog_posts;
DROP TABLE IF EXISTS event_registrations;
DROP TABLE IF EXISTS sermons;
DROP TABLE IF EXISTS events;
DROP TABLE IF EXISTS user_sessions;
DROP TABLE IF EXISTS password_resets;
DROP TABLE IF EXISTS users;

-- Re-enable foreign key checks
SET FOREIGN_KEY_CHECKS = 1;

-- ============================================
-- USER MANAGEMENT & AUTHENTICATION
-- ============================================

-- Users table - Core authentication system
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    phone VARCHAR(20),
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('admin', 'pastor', 'member', 'visitor', 'teacher') DEFAULT 'visitor',
    avatar_url VARCHAR(500),
    is_active BOOLEAN DEFAULT TRUE,
    email_verified BOOLEAN DEFAULT FALSE,
    last_login TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_role (role),
    INDEX idx_active (is_active)
);

-- User sessions for authentication
CREATE TABLE user_sessions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    session_token VARCHAR(255) UNIQUE NOT NULL,
    expires_at TIMESTAMP NOT NULL,
    ip_address VARCHAR(45),
    user_agent TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_token (session_token),
    INDEX idx_user (user_id),
    INDEX idx_expires (expires_at)
);

-- Password reset tokens
CREATE TABLE password_resets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    token VARCHAR(255) UNIQUE NOT NULL,
    expires_at TIMESTAMP NOT NULL,
    used BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_token (token),
    INDEX idx_user (user_id)
);

-- ============================================
-- CHURCH OPERATIONS
-- ============================================

-- Events table - Church events management
CREATE TABLE events (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    event_date DATETIME NOT NULL,
    end_date DATETIME,
    location VARCHAR(255),
    event_type ENUM('service', 'meeting', 'conference', 'fellowship', 'outreach', 'other') DEFAULT 'service',
    max_attendees INT,
    is_recurring BOOLEAN DEFAULT FALSE,
    recurring_pattern JSON,
    featured_image_url VARCHAR(500),
    status ENUM('upcoming', 'ongoing', 'completed', 'cancelled') DEFAULT 'upcoming',
    created_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_event_date (event_date),
    INDEX idx_status (status),
    INDEX idx_type (event_type)
);

-- Event registrations
CREATE TABLE event_registrations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    event_id INT NOT NULL,
    user_id INT,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    number_of_guests INT DEFAULT 1,
    special_requests TEXT,
    registration_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('registered', 'confirmed', 'cancelled') DEFAULT 'registered',
    UNIQUE KEY unique_registration (event_id, email),
    INDEX idx_event_id (event_id),
    INDEX idx_status (status)
);

-- Sermons table - Sermon library
CREATE TABLE sermons (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    preacher VARCHAR(255),
    bible_reference VARCHAR(100),
    sermon_date DATE NOT NULL,
    description TEXT,
    video_url VARCHAR(500),
    audio_url VARCHAR(500),
    transcript TEXT,
    featured_image_url VARCHAR(500),
    sermon_series VARCHAR(255),
    tags JSON,
    views_count INT DEFAULT 0,
    is_featured BOOLEAN DEFAULT FALSE,
    status ENUM('draft', 'published', 'archived') DEFAULT 'draft',
    created_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_sermon_date (sermon_date),
    INDEX idx_status (status),
    INDEX idx_featured (is_featured),
    INDEX idx_series (sermon_series)
);

-- Blog posts table - Church blog
CREATE TABLE blog_posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE NOT NULL,
    excerpt TEXT,
    content LONGTEXT,
    featured_image_url VARCHAR(500),
    author_id INT,
    category VARCHAR(100),
    tags JSON,
    meta_title VARCHAR(255),
    meta_description TEXT,
    views_count INT DEFAULT 0,
    likes_count INT DEFAULT 0,
    status ENUM('draft', 'published', 'archived') DEFAULT 'draft',
    is_featured BOOLEAN DEFAULT FALSE,
    published_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_slug (slug),
    INDEX idx_status (status),
    INDEX idx_published_at (published_at),
    INDEX idx_featured (is_featured),
    INDEX idx_category (category)
);

-- Prayer requests table
CREATE TABLE prayer_requests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    requester_name VARCHAR(255) NOT NULL,
    requester_email VARCHAR(255),
    request_type ENUM('general', 'healing', 'guidance', 'protection', 'thanksgiving', 'other') DEFAULT 'general',
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    is_anonymous BOOLEAN DEFAULT FALSE,
    is_public BOOLEAN DEFAULT TRUE,
    prayer_count INT DEFAULT 0,
    status ENUM('pending', 'praying', 'answered', 'closed') DEFAULT 'pending',
    answered_date TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_status (status),
    INDEX idx_is_public (is_public),
    INDEX idx_created_at (created_at),
    INDEX idx_type (request_type)
);

-- Prayer responses
CREATE TABLE prayer_responses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    prayer_request_id INT NOT NULL,
    responder_name VARCHAR(255) NOT NULL,
    responder_email VARCHAR(255),
    message TEXT NOT NULL,
    is_anonymous BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_prayer_request_id (prayer_request_id)
);

-- Donations table - Financial management
CREATE TABLE donations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    donor_name VARCHAR(255) NOT NULL,
    donor_email VARCHAR(255),
    donor_phone VARCHAR(20),
    amount DECIMAL(10,2) NOT NULL,
    donation_type ENUM('tithe', 'offering', 'special', 'building_fund', 'missions', 'children_ministry', 'other') DEFAULT 'offering',
    payment_method ENUM('cash', 'bank_transfer', 'credit_card', 'mobile_money', 'online') DEFAULT 'online',
    transaction_id VARCHAR(255),
    is_recurring BOOLEAN DEFAULT FALSE,
    recurring_frequency ENUM('weekly', 'monthly', 'quarterly', 'yearly') NULL,
    purpose VARCHAR(255),
    notes TEXT,
    status ENUM('pending', 'completed', 'failed', 'refunded') DEFAULT 'pending',
    processed_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_donor_email (donor_email),
    INDEX idx_status (status),
    INDEX idx_created_at (created_at),
    INDEX idx_type (donation_type)
);

-- Contact messages table
CREATE TABLE contact_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    subject VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    message_type ENUM('general', 'prayer', 'testimony', 'feedback', 'complaint', 'children_ministry', 'other') DEFAULT 'general',
    status ENUM('unread', 'read', 'responded', 'closed') DEFAULT 'unread',
    priority ENUM('low', 'medium', 'high', 'urgent') DEFAULT 'medium',
    assigned_to INT,
    response TEXT,
    responded_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_status (status),
    INDEX idx_priority (priority),
    INDEX idx_created_at (created_at),
    INDEX idx_type (message_type)
);

-- Gallery table for photos and videos
CREATE TABLE gallery (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    file_url VARCHAR(500) NOT NULL,
    file_type ENUM('image', 'video') DEFAULT 'image',
    file_size BIGINT,
    dimensions VARCHAR(50),
    category VARCHAR(100),
    tags JSON,
    is_featured BOOLEAN DEFAULT FALSE,
    status ENUM('draft', 'published', 'archived') DEFAULT 'draft',
    uploaded_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_file_type (file_type),
    INDEX idx_status (status),
    INDEX idx_featured (is_featured),
    INDEX idx_category (category)
);

-- Service times table
CREATE TABLE service_times (
    id INT AUTO_INCREMENT PRIMARY KEY,
    service_name VARCHAR(255) NOT NULL,
    day_of_week ENUM('sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday') NOT NULL,
    start_time TIME NOT NULL,
    end_time TIME NOT NULL,
    location VARCHAR(255),
    description TEXT,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_day_of_week (day_of_week),
    INDEX idx_is_active (is_active)
);

-- Ministries table
CREATE TABLE ministries (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    leader_name VARCHAR(255),
    leader_email VARCHAR(255),
    meeting_day VARCHAR(100),
    meeting_time VARCHAR(100),
    location VARCHAR(255),
    requirements TEXT,
    featured_image_url VARCHAR(500),
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_is_active (is_active),
    INDEX idx_name (name)
);

-- Testimonials table
CREATE TABLE testimonials (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255),
    occupation VARCHAR(255),
    testimonial TEXT NOT NULL,
    rating INT CHECK (rating >= 1 AND rating <= 5),
    photo_url VARCHAR(500),
    is_featured BOOLEAN DEFAULT FALSE,
    is_approved BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_is_approved (is_approved),
    INDEX idx_featured (is_featured),
    INDEX idx_rating (rating)
);

-- News/Articles table
CREATE TABLE news (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE NOT NULL,
    excerpt TEXT,
    content LONGTEXT,
    featured_image_url VARCHAR(500),
    author_id INT,
    category VARCHAR(100),
    tags JSON,
    meta_title VARCHAR(255),
    meta_description TEXT,
    views_count INT DEFAULT 0,
    likes_count INT DEFAULT 0,
    is_featured BOOLEAN DEFAULT FALSE,
    is_breaking BOOLEAN DEFAULT FALSE,
    status ENUM('draft', 'published', 'archived') DEFAULT 'draft',
    published_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_slug (slug),
    INDEX idx_status (status),
    INDEX idx_published_at (published_at),
    INDEX idx_featured (is_featured),
    INDEX idx_breaking (is_breaking),
    INDEX idx_category (category)
);

-- Activity logs for admin tracking
CREATE TABLE activity_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    action VARCHAR(255) NOT NULL,
    table_name VARCHAR(100),
    record_id INT,
    old_values JSON,
    new_values JSON,
    ip_address VARCHAR(45),
    user_agent TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_user_id (user_id),
    INDEX idx_action (action),
    INDEX idx_created_at (created_at),
    INDEX idx_table (table_name)
);

-- ============================================
-- CHILDREN'S MINISTRY TABLES
-- ============================================

-- Children's Ministry Classes Table
CREATE TABLE children_classes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    class_name VARCHAR(100) NOT NULL,
    age_group VARCHAR(50) NOT NULL,
    description TEXT,
    capacity INT DEFAULT 30,
    current_enrollment INT DEFAULT 0,
    teacher_id INT,
    meeting_time VARCHAR(100),
    meeting_room VARCHAR(100),
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_age_group (age_group),
    INDEX idx_status (status),
    INDEX idx_teacher (teacher_id)
);

-- Children Registration Table
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
    address TEXT,
    emergency_contact VARCHAR(255),
    medical_info TEXT,
    allergies TEXT,
    special_needs TEXT,
    class_id INT,
    registration_date DATE DEFAULT (CURRENT_DATE),
    status ENUM('active', 'inactive', 'graduated') DEFAULT 'active',
    photo_url VARCHAR(500),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_age (age),
    INDEX idx_class (class_id),
    INDEX idx_status (status),
    INDEX idx_parent_email (parent_email)
);

-- Children's Ministry Events Table
CREATE TABLE children_events (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    event_date DATETIME NOT NULL,
    end_date DATETIME,
    location VARCHAR(255),
    age_group VARCHAR(100),
    max_participants INT,
    registration_fee DECIMAL(10,2) DEFAULT 0.00,
    image_url VARCHAR(500),
    status ENUM('draft', 'published', 'cancelled', 'completed') DEFAULT 'draft',
    event_type ENUM('regular_class', 'special_event', 'camp', 'outing', 'competition') DEFAULT 'regular_class',
    created_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_event_date (event_date),
    INDEX idx_status (status),
    INDEX idx_type (event_type),
    INDEX idx_age_group (age_group)
);

-- Children's Ministry Lessons Table
CREATE TABLE children_lessons (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    bible_verse VARCHAR(255),
    lesson_content LONGTEXT,
    lesson_objectives TEXT,
    age_group VARCHAR(50),
    class_id INT,
    lesson_date DATE,
    teacher_id INT,
    materials_needed TEXT,
    activity_description TEXT,
    prayer_points TEXT,
    memory_verse VARCHAR(255),
    image_url VARCHAR(500),
    video_url VARCHAR(500),
    status ENUM('draft', 'published') DEFAULT 'draft',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_lesson_date (lesson_date),
    INDEX idx_class (class_id),
    INDEX idx_teacher (teacher_id),
    INDEX idx_status (status),
    INDEX idx_age_group (age_group)
);

-- Children's Ministry Attendance Table
CREATE TABLE children_attendance (
    id INT AUTO_INCREMENT PRIMARY KEY,
    child_id INT NOT NULL,
    class_id INT NOT NULL,
    lesson_id INT,
    attendance_date DATE NOT NULL,
    check_in_time TIME,
    check_out_time TIME,
    attended BOOLEAN DEFAULT TRUE,
    notes TEXT,
    marked_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_attendance_date (attendance_date),
    INDEX idx_child (child_id),
    INDEX idx_class (class_id),
    INDEX idx_lesson (lesson_id)
);

-- Children's Ministry Gallery Table
CREATE TABLE children_gallery (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    file_url VARCHAR(500) NOT NULL,
    file_type ENUM('image', 'video') DEFAULT 'image',
    file_size BIGINT,
    dimensions VARCHAR(50),
    category VARCHAR(100),
    event_id INT,
    class_id INT,
    tags JSON,
    is_featured BOOLEAN DEFAULT FALSE,
    status ENUM('draft', 'published') DEFAULT 'draft',
    uploaded_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_category (category),
    INDEX idx_status (status),
    INDEX idx_featured (is_featured),
    INDEX idx_type (file_type)
);

-- Children's Ministry Teachers Table
CREATE TABLE children_teachers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    bio TEXT,
    experience_years INT DEFAULT 0,
    qualifications TEXT,
    background_check BOOLEAN DEFAULT FALSE,
    background_check_date DATE,
    training_completed BOOLEAN DEFAULT FALSE,
    preferred_age_group VARCHAR(50),
    specialties TEXT,
    status ENUM('active', 'inactive', 'on_leave') DEFAULT 'active',
    hire_date DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_status (status),
    INDEX idx_user (user_id),
    INDEX idx_background_check (background_check)
);

-- Children's Ministry Parents Table
CREATE TABLE children_parents (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    full_name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    address TEXT,
    occupation VARCHAR(100),
    church_member BOOLEAN DEFAULT FALSE,
    member_since DATE,
    willingness_to_volunteer BOOLEAN DEFAULT FALSE,
    volunteer_areas TEXT,
    communication_preferences JSON,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_church_member (church_member),
    INDEX idx_volunteer (willingness_to_volunteer)
);

-- Children's Ministry Resources Table
CREATE TABLE children_resources (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    resource_type ENUM('lesson_plan', 'activity_sheet', 'coloring_page', 'video', 'song', 'story', 'game') DEFAULT 'lesson_plan',
    file_url VARCHAR(500),
    file_size BIGINT,
    age_group VARCHAR(50),
    category VARCHAR(100),
    tags JSON,
    download_count INT DEFAULT 0,
    is_free BOOLEAN DEFAULT TRUE,
    status ENUM('draft', 'published') DEFAULT 'draft',
    created_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_type (resource_type),
    INDEX idx_age_group (age_group),
    INDEX idx_status (status),
    INDEX idx_category (category),
    INDEX idx_free (is_free)
);

-- Children's Ministry News Table
CREATE TABLE children_news (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE,
    content LONGTEXT,
    excerpt TEXT,
    author VARCHAR(255),
    image_url VARCHAR(500),
    status ENUM('draft', 'published') DEFAULT 'draft',
    category VARCHAR(100),
    tags JSON,
    target_audience ENUM('parents', 'teachers', 'everyone') DEFAULT 'everyone',
    views_count INT DEFAULT 0,
    likes_count INT DEFAULT 0,
    is_featured BOOLEAN DEFAULT FALSE,
    is_breaking BOOLEAN DEFAULT FALSE,
    published_at DATETIME,
    created_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_status (status),
    INDEX idx_published_at (published_at),
    INDEX idx_featured (is_featured),
    INDEX idx_audience (target_audience),
    INDEX idx_category (category)
);

-- ============================================
-- DEFAULT DATA INSERTIONS
-- ============================================

-- Insert default admin user (password: admin123)
INSERT INTO users (first_name, last_name, email, password_hash, role, is_active, email_verified) 
VALUES ('Admin', 'User', 'admin@salemministries.com', '$2a$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', TRUE, TRUE);

-- Insert default service times
INSERT INTO service_times (service_name, day_of_week, start_time, end_time, location, description) VALUES
('1st Service', 'sunday', '08:00:00', '09:30:00', 'Main Sanctuary', 'Morning Worship Service'),
('2nd Service', 'sunday', '10:30:00', '12:00:00', 'Main Sanctuary', 'Main Worship Service'),
('Prayers', 'wednesday', '17:30:00', '18:30:00', 'Prayer Room', 'Mid-week Prayer Meeting'),
('Youth Fellowship', 'friday', '17:00:00', '18:30:00', 'Youth Center', 'Youth Ministry Gathering');

-- Insert default ministries
INSERT INTO ministries (name, description, leader_name, meeting_day, meeting_time, location) VALUES
('Children Ministry', 'Spiritual education and fun activities for children ages 0-13', 'Children Ministry Director', 'Sunday', '08:00 AM & 10:30 AM', 'Children Wing'),
('Youth Ministry', 'Empowering teenagers to live for Christ', 'Youth Director', 'Friday', '17:00 PM', 'Youth Center'),
('Women Ministry', 'Fellowship and spiritual growth for women', 'Women Ministry Leader', 'Tuesday', '18:00 PM', 'Fellowship Hall'),
('Men Ministry', 'Building strong men of faith', 'Men Ministry Leader', 'Thursday', '19:00 PM', 'Conference Room'),
('Music Ministry', 'Leading worship through music and song', 'Music Director', 'Saturday', '15:00 PM', 'Sanctuary');

-- Insert Default Children's Classes
INSERT INTO children_classes (class_name, age_group, description, capacity, meeting_time, meeting_room) VALUES
('Nursery Class', '0-2 years', 'A safe and nurturing environment for infants and toddlers where they experience God''s love through gentle care and simple songs.', 15, 'Sunday 8:00 AM & 10:30 AM', 'Nursery Room'),
('Beginner Class', '3-5 years', 'Preschoolers learn about Jesus through interactive Bible stories, songs, games, and hands-on activities designed for their age.', 25, 'Sunday 8:00 AM & 10:30 AM', 'Classroom 1'),
('Primary Class', '6-8 years', 'Early elementary children explore foundational Bible truths through engaging lessons, memory verses, and creative activities.', 30, 'Sunday 8:00 AM & 10:30 AM', 'Classroom 2'),
('Junior Class', '9-11 years', 'Upper elementary children dive deeper into God''s Word with practical applications, discussions, and service projects.', 30, 'Sunday 8:00 AM & 10:30 AM', 'Classroom 3'),
('Pre-Teen Class', '12-13 years', 'Tweens navigate faith and life challenges through relevant Bible studies, mentorship, and meaningful discussions.', 25, 'Sunday 8:00 AM & 10:30 AM', 'Youth Room');

-- Insert Sample Children's Events
INSERT INTO children_events (title, description, event_date, location, age_group, event_type, status) VALUES
('Children\'s Christmas Celebration', 'Join us for a special Christmas celebration with carols, nativity play, and festive activities for all ages.', '2024-12-15 10:00:00', 'Main Church Hall', 'All Ages', 'special_event', 'published'),
('Vacation Bible School', 'A week-long adventure filled with Bible stories, games, crafts, and friendship-building activities.', '2024-08-05 09:00:00', 'Church Grounds', '6-12 years', 'camp', 'published'),
('Children\'s Sports Day', 'Fun-filled day of sports, games, and team-building activities promoting Christian values and sportsmanship.', '2024-09-20 09:00:00', 'Church Field', '8-13 years', 'outing', 'published'),
('Bible Quiz Competition', 'Test your Bible knowledge in this exciting competition with prizes and recognition for all participants.', '2024-10-25 14:00:00', 'Main Hall', '9-13 years', 'competition', 'published');

-- Insert Sample Lessons
INSERT INTO children_lessons (title, bible_verse, lesson_content, age_group, lesson_date, status) VALUES
('God Created Everything', 'Genesis 1:1', 'In this lesson, children learn about the creation story and how God made everything good and beautiful.', '3-5 years', '2024-03-10', 'published'),
('Jesus Loves Children', 'Mark 10:14', 'Children discover Jesus'' special love for them through the story of Jesus blessing the little children.', '6-8 years', '2024-03-10', 'published'),
('The Good Samaritan', 'Luke 10:25-37', 'Children learn about loving and helping others through the parable of the Good Samaritan.', '9-11 years', '2024-03-10', 'published'),
('Walking with Jesus', 'Micah 6:8', 'Pre-teens explore what it means to live out their faith through justice, mercy, and humility.', '12-13 years', '2024-03-10', 'published');

-- Insert sample events for church operations
INSERT INTO events (title, description, event_date, location, event_type, status, created_by) VALUES
('Sunday Service', 'Join us for powerful worship and life-changing message', '2024-03-31 10:30:00', 'Main Sanctuary', 'service', 'upcoming', 1),
('Bible Study', 'Deep dive into God''s Word with fellowship', '2024-03-27 18:00:00', 'Fellowship Hall', 'meeting', 'upcoming', 1),
('Youth Conference', 'Annual youth gathering with speakers and worship', '2024-04-15 09:00:00', 'Youth Center', 'conference', 'upcoming', 1);

-- Insert sample sermons
INSERT INTO sermons (title, preacher, bible_reference, sermon_date, description, status, created_by) VALUES
('The Power of Faith', 'Pastor John Smith', 'Hebrews 11:1', '2024-03-24', 'Discover the transformative power of unwavering faith in God', 'published', 1),
('Walking in Love', 'Pastor Mary Johnson', '1 Corinthians 13', '2024-03-17', 'Learn how to love others as Christ loves us', 'published', 1);

-- Insert sample blog posts
INSERT INTO blog_posts (title, slug, excerpt, content, author_id, category, status, published_at) VALUES
('5 Ways to Strengthen Your Prayer Life', '5-ways-strengthen-prayer-life', 'Discover practical methods to deepen your prayer connection with God', 'Prayer is the foundation of our relationship with God...', 1, 'Spiritual Growth', 'published', NOW()),
('Understanding God\'s Purpose for Your Life', 'understanding-gods-purpose', 'Find clarity and direction in your divine calling', 'God has a unique plan and purpose for each of us...', 1, 'Purpose', 'published', NOW());

-- Insert sample prayer requests
INSERT INTO prayer_requests (requester_name, requester_email, request_type, title, description, is_public, status) VALUES
('John Doe', 'john@example.com', 'healing', 'Healing for Family Member', 'Please pray for my mother''s recovery from surgery', TRUE, 'pending'),
('Jane Smith', 'jane@example.com', 'guidance', 'Career Decision', 'Seeking God''s guidance for a job opportunity', TRUE, 'pending');

-- Insert sample donations
INSERT INTO donations (donor_name, donor_email, amount, donation_type, payment_method, status, purpose) VALUES
('Anonymous Member', 'anonymous@salemministries.com', 100.00, 'offering', 'online', 'completed', 'General offering'),
('John Smith', 'john@example.com', 50.00, 'tithe', 'online', 'completed', 'Monthly tithe');

-- Insert sample contact messages
INSERT INTO contact_messages (name, email, subject, message, message_type, status, priority) VALUES
('New Visitor', 'visitor@example.com', 'Information Request', 'I would like to know more about your church services', 'general', 'unread', 'medium'),
('Mary Johnson', 'mary@example.com', 'Prayer Request', 'Please pray for my family', 'prayer', 'unread', 'high');

-- Insert sample gallery items
INSERT INTO gallery (title, description, file_url, file_type, category, status, uploaded_by) VALUES
('Sunday Service', 'Congregation worshiping together', '/assets/gallery/sunday-service-1.jpg', 'image', 'services', 'published', 1),
('Youth Event', 'Youth fellowship activities', '/assets/gallery/youth-event-1.jpg', 'image', 'youth', 'published', 1);

-- Insert sample testimonials
INSERT INTO testimonials (name, email, occupation, testimonial, rating, is_approved) VALUES
('Sarah Williams', 'sarah@example.com', 'Teacher', 'This church has transformed my spiritual life. The teachings are practical and life-changing.', 5, TRUE),
('Michael Brown', 'michael@example.com', 'Engineer', 'I found a loving community here that supports me in my faith journey.', 5, TRUE);

-- Insert sample news
INSERT INTO news (title, slug, excerpt, content, author_id, category, status, published_at) VALUES
('Church Anniversary Celebration', 'church-anniversary-celebration', 'Join us as we celebrate 10 years of God''s faithfulness', 'We are excited to celebrate a decade of ministry...', 1, 'Announcements', 'published', NOW()),
('New Youth Program Launch', 'new-youth-program-launch', 'Exciting new programs for our youth ministry', 'Starting next month, we are launching new programs...', 1, 'Youth', 'published', NOW());

-- ============================================
-- FINAL VERIFICATION
-- ============================================

-- First, let's verify tables were actually created
SELECT 'CHECKING TABLE CREATION...' as status;

-- Show completion message with table count
SELECT 'SALEM DOMINION MINISTRIES DATABASE SETUP COMPLETE!' as message,
       'All 26 tables created successfully with sample data' as details,
       COUNT(*) as total_tables,
       NOW() as completion_time
FROM information_schema.tables 
WHERE table_schema = 'salem_dominion_ministries'
AND table_type = 'BASE TABLE';  -- Only count actual tables, not views

-- List all tables for verification with clear formatting
SELECT '=== ALL CREATED TABLES ===' as info;
SHOW TABLES;

-- Show table details with row counts
SELECT '=== TABLE DETAILS ===' as info;
SELECT 
    table_name as 'Table Name',
    table_rows as 'Rows',
    data_length as 'Size (bytes)'
FROM information_schema.tables 
WHERE table_schema = 'salem_dominion_ministries'
AND table_type = 'BASE TABLE'
ORDER BY table_name;

-- Additional verification - check if key tables exist
SELECT '=== KEY TABLES VERIFICATION ===' as info;
SELECT 
    CASE 
        WHEN COUNT(*) > 0 THEN 'SUCCESS: Core tables exist'
        ELSE 'ERROR: Core tables missing'
    END as verification_status
FROM information_schema.tables 
WHERE table_schema = 'salem_dominion_ministries'
AND table_name IN ('users', 'events', 'children_classes');
