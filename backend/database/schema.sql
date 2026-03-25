-- Salem Dominion Ministries Database Schema
-- Created for complete church management system

-- Create database if it doesn't exist
CREATE DATABASE IF NOT EXISTS salem_dominion_ministries CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE salem_dominion_ministries;

-- Users table for authentication and user management
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    phone VARCHAR(20),
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('admin', 'pastor', 'member', 'visitor') DEFAULT 'visitor',
    avatar_url VARCHAR(500),
    is_active BOOLEAN DEFAULT TRUE,
    email_verified BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_role (role)
);

-- Events table for church events
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
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_event_date (event_date),
    INDEX idx_status (status)
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
    FOREIGN KEY (event_id) REFERENCES events(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    UNIQUE KEY unique_registration (event_id, email),
    INDEX idx_event_id (event_id)
);

-- Sermons table
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
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_sermon_date (sermon_date),
    INDEX idx_status (status),
    INDEX idx_featured (is_featured)
);

-- Blog posts table
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
    FOREIGN KEY (author_id) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_slug (slug),
    INDEX idx_status (status),
    INDEX idx_published_at (published_at),
    INDEX idx_featured (is_featured)
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
    INDEX idx_created_at (created_at)
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
    FOREIGN KEY (prayer_request_id) REFERENCES prayer_requests(id) ON DELETE CASCADE,
    INDEX idx_prayer_request_id (prayer_request_id)
);

-- Donations table
CREATE TABLE donations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    donor_name VARCHAR(255) NOT NULL,
    donor_email VARCHAR(255),
    donor_phone VARCHAR(20),
    amount DECIMAL(10,2) NOT NULL,
    donation_type ENUM('tithe', 'offering', 'special', 'building_fund', 'missions', 'other') DEFAULT 'offering',
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
    FOREIGN KEY (processed_by) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_donor_email (donor_email),
    INDEX idx_status (status),
    INDEX idx_created_at (created_at)
);

-- Contact messages table
CREATE TABLE contact_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    subject VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    message_type ENUM('general', 'prayer', 'testimony', 'feedback', 'complaint', 'other') DEFAULT 'general',
    status ENUM('unread', 'read', 'responded', 'closed') DEFAULT 'unread',
    priority ENUM('low', 'medium', 'high', 'urgent') DEFAULT 'medium',
    assigned_to INT,
    response TEXT,
    responded_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (assigned_to) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_status (status),
    INDEX idx_priority (priority),
    INDEX idx_created_at (created_at)
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
    FOREIGN KEY (uploaded_by) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_file_type (file_type),
    INDEX idx_status (status),
    INDEX idx_featured (is_featured)
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
    INDEX idx_is_active (is_active)
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
    INDEX idx_featured (is_featured)
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
    FOREIGN KEY (author_id) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_slug (slug),
    INDEX idx_status (status),
    INDEX idx_published_at (published_at),
    INDEX idx_featured (is_featured),
    INDEX idx_breaking (is_breaking)
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
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_user_id (user_id),
    INDEX idx_action (action),
    INDEX idx_created_at (created_at)
);

-- Insert default admin user (password: admin123)
INSERT INTO users (first_name, last_name, email, password_hash, role, is_active, email_verified) 
VALUES ('Admin', 'User', 'admin@salemministries.org', '$2a$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', TRUE, TRUE);

-- Insert default service times
INSERT INTO service_times (service_name, day_of_week, start_time, end_time, location, description) VALUES
('Sunday Morning Service', 'sunday', '09:00:00', '11:30:00', 'Main Sanctuary', 'Join us for powerful worship and life-changing messages'),
('Sunday Evening Service', 'sunday', '18:00:00', '19:30:00', 'Main Sanctuary', 'Evening fellowship and prayer service'),
('Wednesday Bible Study', 'wednesday', '19:00:00', '20:30:00', 'Fellowship Hall', 'Mid-week spiritual growth and fellowship'),
('Friday Prayer Meeting', 'friday', '20:00:00', '21:30:00', 'Prayer Room', 'Corporate prayer and intercession');

-- Insert default ministries
INSERT INTO ministries (name, description, leader_name, meeting_day, meeting_time, location) VALUES
('Children Ministry', 'Spiritual education and fun activities for children ages 3-12', 'Sarah Johnson', 'Sunday', '09:00 AM', 'Children Wing'),
('Youth Ministry', 'Empowering teenagers to live for Christ', 'Michael Brown', 'Friday', '19:00 PM', 'Youth Center'),
('Women Ministry', 'Fellowship and spiritual growth for women', 'Mary Davis', 'Tuesday', '18:00 PM', 'Fellowship Hall'),
('Men Ministry', 'Building strong men of faith', 'James Wilson', 'Thursday', '19:00 PM', 'Conference Room'),
('Music Ministry', 'Leading worship through music and song', 'David Smith', 'Saturday', '15:00 PM', 'Sanctuary');
