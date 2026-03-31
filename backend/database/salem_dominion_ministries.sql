-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 31, 2026 at 09:30 AM
-- Server version: 8.0.45
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `salem_dominion_ministries`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_logs`
--

CREATE TABLE `activity_logs` (
  `id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `action` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `table_name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `record_id` int DEFAULT NULL,
  `old_values` json DEFAULT NULL,
  `new_values` json DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `blog_posts`
--

CREATE TABLE `blog_posts` (
  `id` int NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `excerpt` text COLLATE utf8mb4_unicode_ci,
  `content` longtext COLLATE utf8mb4_unicode_ci,
  `featured_image_url` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `author_id` int DEFAULT NULL,
  `category` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tags` json DEFAULT NULL,
  `meta_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_description` text COLLATE utf8mb4_unicode_ci,
  `views_count` int DEFAULT '0',
  `likes_count` int DEFAULT '0',
  `status` enum('draft','published','archived') COLLATE utf8mb4_unicode_ci DEFAULT 'draft',
  `is_featured` tinyint(1) DEFAULT '0',
  `published_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `blog_posts`
--

INSERT INTO `blog_posts` (`id`, `title`, `slug`, `excerpt`, `content`, `featured_image_url`, `author_id`, `category`, `tags`, `meta_title`, `meta_description`, `views_count`, `likes_count`, `status`, `is_featured`, `published_at`, `created_at`, `updated_at`) VALUES
(1, '5 Ways to Strengthen Your Prayer Life', '5-ways-strengthen-prayer-life', 'Discover practical methods to deepen your prayer connection with God', 'Prayer is the foundation of our relationship with God...', NULL, 1, 'Spiritual Growth', NULL, NULL, NULL, 0, 0, 'published', 0, '2026-03-31 07:30:21', '2026-03-31 07:30:21', '2026-03-31 07:30:21'),
(2, 'Understanding God\'s Purpose for Your Life', 'understanding-gods-purpose', 'Find clarity and direction in your divine calling', 'God has a unique plan and purpose for each of us...', NULL, 1, 'Purpose', NULL, NULL, NULL, 0, 0, 'published', 0, '2026-03-31 07:30:21', '2026-03-31 07:30:21', '2026-03-31 07:30:21');

-- --------------------------------------------------------

--
-- Table structure for table `children_attendance`
--

CREATE TABLE `children_attendance` (
  `id` int NOT NULL,
  `child_id` int NOT NULL,
  `class_id` int NOT NULL,
  `lesson_id` int DEFAULT NULL,
  `attendance_date` date NOT NULL,
  `check_in_time` time DEFAULT NULL,
  `check_out_time` time DEFAULT NULL,
  `attended` tinyint(1) DEFAULT '1',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `marked_by` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `children_classes`
--

CREATE TABLE `children_classes` (
  `id` int NOT NULL,
  `class_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `age_group` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `capacity` int DEFAULT '30',
  `current_enrollment` int DEFAULT '0',
  `teacher_id` int DEFAULT NULL,
  `meeting_time` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meeting_room` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `children_classes`
--

INSERT INTO `children_classes` (`id`, `class_name`, `age_group`, `description`, `capacity`, `current_enrollment`, `teacher_id`, `meeting_time`, `meeting_room`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Nursery Class', '0-2 years', 'A safe and nurturing environment for infants and toddlers where they experience God\'s love through gentle care and simple songs.', 15, 0, NULL, 'Sunday 8:00 AM & 10:30 AM', 'Nursery Room', 'active', '2026-03-31 07:30:21', '2026-03-31 07:30:21'),
(2, 'Beginner Class', '3-5 years', 'Preschoolers learn about Jesus through interactive Bible stories, songs, games, and hands-on activities designed for their age.', 25, 0, NULL, 'Sunday 8:00 AM & 10:30 AM', 'Classroom 1', 'active', '2026-03-31 07:30:21', '2026-03-31 07:30:21'),
(3, 'Primary Class', '6-8 years', 'Early elementary children explore foundational Bible truths through engaging lessons, memory verses, and creative activities.', 30, 0, NULL, 'Sunday 8:00 AM & 10:30 AM', 'Classroom 2', 'active', '2026-03-31 07:30:21', '2026-03-31 07:30:21'),
(4, 'Junior Class', '9-11 years', 'Upper elementary children dive deeper into God\'s Word with practical applications, discussions, and service projects.', 30, 0, NULL, 'Sunday 8:00 AM & 10:30 AM', 'Classroom 3', 'active', '2026-03-31 07:30:21', '2026-03-31 07:30:21'),
(5, 'Pre-Teen Class', '12-13 years', 'Tweens navigate faith and life challenges through relevant Bible studies, mentorship, and meaningful discussions.', 25, 0, NULL, 'Sunday 8:00 AM & 10:30 AM', 'Youth Room', 'active', '2026-03-31 07:30:21', '2026-03-31 07:30:21');

-- --------------------------------------------------------

--
-- Table structure for table `children_events`
--

CREATE TABLE `children_events` (
  `id` int NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `event_date` datetime NOT NULL,
  `end_date` datetime DEFAULT NULL,
  `location` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `age_group` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `max_participants` int DEFAULT NULL,
  `registration_fee` decimal(10,2) DEFAULT '0.00',
  `image_url` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('draft','published','cancelled','completed') COLLATE utf8mb4_unicode_ci DEFAULT 'draft',
  `event_type` enum('regular_class','special_event','camp','outing','competition') COLLATE utf8mb4_unicode_ci DEFAULT 'regular_class',
  `created_by` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `children_events`
--

INSERT INTO `children_events` (`id`, `title`, `description`, `event_date`, `end_date`, `location`, `age_group`, `max_participants`, `registration_fee`, `image_url`, `status`, `event_type`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'Children\'s Christmas Celebration', 'Join us for a special Christmas celebration with carols, nativity play, and festive activities for all ages.', '2024-12-15 10:00:00', NULL, 'Main Church Hall', 'All Ages', NULL, 0.00, NULL, 'published', 'special_event', NULL, '2026-03-31 07:30:21', '2026-03-31 07:30:21'),
(2, 'Vacation Bible School', 'A week-long adventure filled with Bible stories, games, crafts, and friendship-building activities.', '2024-08-05 09:00:00', NULL, 'Church Grounds', '6-12 years', NULL, 0.00, NULL, 'published', 'camp', NULL, '2026-03-31 07:30:21', '2026-03-31 07:30:21'),
(3, 'Children\'s Sports Day', 'Fun-filled day of sports, games, and team-building activities promoting Christian values and sportsmanship.', '2024-09-20 09:00:00', NULL, 'Church Field', '8-13 years', NULL, 0.00, NULL, 'published', 'outing', NULL, '2026-03-31 07:30:21', '2026-03-31 07:30:21'),
(4, 'Bible Quiz Competition', 'Test your Bible knowledge in this exciting competition with prizes and recognition for all participants.', '2024-10-25 14:00:00', NULL, 'Main Hall', '9-13 years', NULL, 0.00, NULL, 'published', 'competition', NULL, '2026-03-31 07:30:21', '2026-03-31 07:30:21');

-- --------------------------------------------------------

--
-- Table structure for table `children_gallery`
--

CREATE TABLE `children_gallery` (
  `id` int NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `file_url` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_type` enum('image','video') COLLATE utf8mb4_unicode_ci DEFAULT 'image',
  `file_size` bigint DEFAULT NULL,
  `dimensions` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `event_id` int DEFAULT NULL,
  `class_id` int DEFAULT NULL,
  `tags` json DEFAULT NULL,
  `is_featured` tinyint(1) DEFAULT '0',
  `status` enum('draft','published') COLLATE utf8mb4_unicode_ci DEFAULT 'draft',
  `uploaded_by` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `children_lessons`
--

CREATE TABLE `children_lessons` (
  `id` int NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `bible_verse` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lesson_content` longtext COLLATE utf8mb4_unicode_ci,
  `lesson_objectives` text COLLATE utf8mb4_unicode_ci,
  `age_group` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `class_id` int DEFAULT NULL,
  `lesson_date` date DEFAULT NULL,
  `teacher_id` int DEFAULT NULL,
  `materials_needed` text COLLATE utf8mb4_unicode_ci,
  `activity_description` text COLLATE utf8mb4_unicode_ci,
  `prayer_points` text COLLATE utf8mb4_unicode_ci,
  `memory_verse` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image_url` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `video_url` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('draft','published') COLLATE utf8mb4_unicode_ci DEFAULT 'draft',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `children_lessons`
--

INSERT INTO `children_lessons` (`id`, `title`, `bible_verse`, `lesson_content`, `lesson_objectives`, `age_group`, `class_id`, `lesson_date`, `teacher_id`, `materials_needed`, `activity_description`, `prayer_points`, `memory_verse`, `image_url`, `video_url`, `status`, `created_at`, `updated_at`) VALUES
(1, 'God Created Everything', 'Genesis 1:1', 'In this lesson, children learn about the creation story and how God made everything good and beautiful.', NULL, '3-5 years', NULL, '2024-03-10', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'published', '2026-03-31 07:30:21', '2026-03-31 07:30:21'),
(2, 'Jesus Loves Children', 'Mark 10:14', 'Children discover Jesus\' special love for them through the story of Jesus blessing the little children.', NULL, '6-8 years', NULL, '2024-03-10', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'published', '2026-03-31 07:30:21', '2026-03-31 07:30:21'),
(3, 'The Good Samaritan', 'Luke 10:25-37', 'Children learn about loving and helping others through the parable of the Good Samaritan.', NULL, '9-11 years', NULL, '2024-03-10', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'published', '2026-03-31 07:30:21', '2026-03-31 07:30:21'),
(4, 'Walking with Jesus', 'Micah 6:8', 'Pre-teens explore what it means to live out their faith through justice, mercy, and humility.', NULL, '12-13 years', NULL, '2024-03-10', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'published', '2026-03-31 07:30:21', '2026-03-31 07:30:21');

-- --------------------------------------------------------

--
-- Table structure for table `children_news`
--

CREATE TABLE `children_news` (
  `id` int NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `content` longtext COLLATE utf8mb4_unicode_ci,
  `excerpt` text COLLATE utf8mb4_unicode_ci,
  `author` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image_url` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('draft','published') COLLATE utf8mb4_unicode_ci DEFAULT 'draft',
  `category` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tags` json DEFAULT NULL,
  `target_audience` enum('parents','teachers','everyone') COLLATE utf8mb4_unicode_ci DEFAULT 'everyone',
  `views_count` int DEFAULT '0',
  `likes_count` int DEFAULT '0',
  `is_featured` tinyint(1) DEFAULT '0',
  `is_breaking` tinyint(1) DEFAULT '0',
  `published_at` datetime DEFAULT NULL,
  `created_by` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `children_parents`
--

CREATE TABLE `children_parents` (
  `id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `full_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `occupation` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `church_member` tinyint(1) DEFAULT '0',
  `member_since` date DEFAULT NULL,
  `willingness_to_volunteer` tinyint(1) DEFAULT '0',
  `volunteer_areas` text COLLATE utf8mb4_unicode_ci,
  `communication_preferences` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `children_registration`
--

CREATE TABLE `children_registration` (
  `id` int NOT NULL,
  `first_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_of_birth` date NOT NULL,
  `age` int NOT NULL,
  `gender` enum('male','female') COLLATE utf8mb4_unicode_ci NOT NULL,
  `parent_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `parent_email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `parent_phone` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `emergency_contact` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `medical_info` text COLLATE utf8mb4_unicode_ci,
  `allergies` text COLLATE utf8mb4_unicode_ci,
  `special_needs` text COLLATE utf8mb4_unicode_ci,
  `class_id` int DEFAULT NULL,
  `registration_date` date DEFAULT (curdate()),
  `status` enum('active','inactive','graduated') COLLATE utf8mb4_unicode_ci DEFAULT 'active',
  `photo_url` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `children_resources`
--

CREATE TABLE `children_resources` (
  `id` int NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `resource_type` enum('lesson_plan','activity_sheet','coloring_page','video','song','story','game') COLLATE utf8mb4_unicode_ci DEFAULT 'lesson_plan',
  `file_url` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_size` bigint DEFAULT NULL,
  `age_group` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tags` json DEFAULT NULL,
  `download_count` int DEFAULT '0',
  `is_free` tinyint(1) DEFAULT '1',
  `status` enum('draft','published') COLLATE utf8mb4_unicode_ci DEFAULT 'draft',
  `created_by` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `children_teachers`
--

CREATE TABLE `children_teachers` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `bio` text COLLATE utf8mb4_unicode_ci,
  `experience_years` int DEFAULT '0',
  `qualifications` text COLLATE utf8mb4_unicode_ci,
  `background_check` tinyint(1) DEFAULT '0',
  `background_check_date` date DEFAULT NULL,
  `training_completed` tinyint(1) DEFAULT '0',
  `preferred_age_group` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `specialties` text COLLATE utf8mb4_unicode_ci,
  `status` enum('active','inactive','on_leave') COLLATE utf8mb4_unicode_ci DEFAULT 'active',
  `hire_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contact_messages`
--

CREATE TABLE `contact_messages` (
  `id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subject` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `message_type` enum('general','prayer','testimony','feedback','complaint','children_ministry','booking_inquiry','other') COLLATE utf8mb4_unicode_ci DEFAULT 'general',
  `status` enum('unread','read','responded','closed') COLLATE utf8mb4_unicode_ci DEFAULT 'unread',
  `priority` enum('low','medium','high','urgent') COLLATE utf8mb4_unicode_ci DEFAULT 'medium',
  `assigned_to` int DEFAULT NULL,
  `response` text COLLATE utf8mb4_unicode_ci,
  `responded_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `contact_messages`
--

INSERT INTO `contact_messages` (`id`, `name`, `email`, `phone`, `subject`, `message`, `message_type`, `status`, `priority`, `assigned_to`, `response`, `responded_at`, `created_at`, `updated_at`) VALUES
(1, 'New Visitor', 'visitor@example.com', NULL, 'Information Request', 'I would like to know more about your church services', 'general', 'unread', 'medium', NULL, NULL, NULL, '2026-03-31 07:30:22', '2026-03-31 07:30:22'),
(2, 'Mary Johnson', 'mary@example.com', NULL, 'Prayer Request', 'Please pray for my family', 'prayer', 'unread', 'high', NULL, NULL, NULL, '2026-03-31 07:30:22', '2026-03-31 07:30:22');

-- --------------------------------------------------------

--
-- Table structure for table `donations`
--

CREATE TABLE `donations` (
  `id` int NOT NULL,
  `donor_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `donor_email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `donor_phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `donation_type` enum('tithe','offering','special','building_fund','missions','children_ministry','other') COLLATE utf8mb4_unicode_ci DEFAULT 'offering',
  `payment_method` enum('cash','bank_transfer','credit_card','mobile_money','online') COLLATE utf8mb4_unicode_ci DEFAULT 'online',
  `transaction_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_recurring` tinyint(1) DEFAULT '0',
  `recurring_frequency` enum('weekly','monthly','quarterly','yearly') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `purpose` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `status` enum('pending','completed','failed','refunded') COLLATE utf8mb4_unicode_ci DEFAULT 'pending',
  `processed_by` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `donations`
--

INSERT INTO `donations` (`id`, `donor_name`, `donor_email`, `donor_phone`, `amount`, `donation_type`, `payment_method`, `transaction_id`, `is_recurring`, `recurring_frequency`, `purpose`, `notes`, `status`, `processed_by`, `created_at`, `updated_at`) VALUES
(1, 'Anonymous Member', 'anonymous@salemministries.com', NULL, 100.00, 'offering', 'online', NULL, 0, NULL, 'General offering', NULL, 'completed', NULL, '2026-03-31 07:30:21', '2026-03-31 07:30:21'),
(2, 'John Smith', 'john@example.com', NULL, 50.00, 'tithe', 'online', NULL, 0, NULL, 'Monthly tithe', NULL, 'completed', NULL, '2026-03-31 07:30:21', '2026-03-31 07:30:21');

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` int NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `event_date` datetime NOT NULL,
  `end_date` datetime DEFAULT NULL,
  `location` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `event_type` enum('service','meeting','conference','fellowship','outreach','other') COLLATE utf8mb4_unicode_ci DEFAULT 'service',
  `max_attendees` int DEFAULT NULL,
  `is_recurring` tinyint(1) DEFAULT '0',
  `recurring_pattern` json DEFAULT NULL,
  `featured_image_url` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('upcoming','ongoing','completed','cancelled') COLLATE utf8mb4_unicode_ci DEFAULT 'upcoming',
  `created_by` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `title`, `description`, `event_date`, `end_date`, `location`, `event_type`, `max_attendees`, `is_recurring`, `recurring_pattern`, `featured_image_url`, `status`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'Sunday Service', 'Join us for powerful worship and life-changing message', '2024-03-31 10:30:00', NULL, 'Main Sanctuary', 'service', NULL, 0, NULL, NULL, 'upcoming', 1, '2026-03-31 07:30:21', '2026-03-31 07:30:21'),
(2, 'Bible Study', 'Deep dive into God\'s Word with fellowship', '2024-03-27 18:00:00', NULL, 'Fellowship Hall', 'meeting', NULL, 0, NULL, NULL, 'upcoming', 1, '2026-03-31 07:30:21', '2026-03-31 07:30:21'),
(3, 'Youth Conference', 'Annual youth gathering with speakers and worship', '2024-04-15 09:00:00', NULL, 'Youth Center', 'conference', NULL, 0, NULL, NULL, 'upcoming', 1, '2026-03-31 07:30:21', '2026-03-31 07:30:21');

-- --------------------------------------------------------

--
-- Table structure for table `event_registrations`
--

CREATE TABLE `event_registrations` (
  `id` int NOT NULL,
  `event_id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `first_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `number_of_guests` int DEFAULT '1',
  `special_requests` text COLLATE utf8mb4_unicode_ci,
  `registration_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `status` enum('registered','confirmed','cancelled') COLLATE utf8mb4_unicode_ci DEFAULT 'registered'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gallery`
--

CREATE TABLE `gallery` (
  `id` int NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `file_url` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_type` enum('image','video') COLLATE utf8mb4_unicode_ci DEFAULT 'image',
  `file_size` bigint DEFAULT NULL,
  `dimensions` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tags` json DEFAULT NULL,
  `is_featured` tinyint(1) DEFAULT '0',
  `status` enum('draft','published','archived') COLLATE utf8mb4_unicode_ci DEFAULT 'draft',
  `uploaded_by` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `gallery`
--

INSERT INTO `gallery` (`id`, `title`, `description`, `file_url`, `file_type`, `file_size`, `dimensions`, `category`, `tags`, `is_featured`, `status`, `uploaded_by`, `created_at`, `updated_at`) VALUES
(1, 'Sunday Service', 'Congregation worshiping together', '/assets/gallery/sunday-service-1.jpg', 'image', NULL, NULL, 'services', NULL, 0, 'published', 1, '2026-03-31 07:30:22', '2026-03-31 07:30:22'),
(2, 'Youth Event', 'Youth fellowship activities', '/assets/gallery/youth-event-1.jpg', 'image', NULL, NULL, 'youth', NULL, 0, 'published', 1, '2026-03-31 07:30:22', '2026-03-31 07:30:22');

-- --------------------------------------------------------

--
-- Table structure for table `ministries`
--

CREATE TABLE `ministries` (
  `id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `leader_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `leader_email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meeting_day` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meeting_time` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `location` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `requirements` text COLLATE utf8mb4_unicode_ci,
  `featured_image_url` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ministries`
--

INSERT INTO `ministries` (`id`, `name`, `description`, `leader_name`, `leader_email`, `meeting_day`, `meeting_time`, `location`, `requirements`, `featured_image_url`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Children Ministry', 'Spiritual education and fun activities for children ages 0-13', 'Children Ministry Director', NULL, 'Sunday', '08:00 AM & 10:30 AM', 'Children Wing', NULL, NULL, 1, '2026-03-31 07:30:21', '2026-03-31 07:30:21'),
(2, 'Youth Ministry', 'Empowering teenagers to live for Christ', 'Youth Director', NULL, 'Friday', '17:00 PM', 'Youth Center', NULL, NULL, 1, '2026-03-31 07:30:21', '2026-03-31 07:30:21'),
(3, 'Women Ministry', 'Fellowship and spiritual growth for women', 'Women Ministry Leader', NULL, 'Tuesday', '18:00 PM', 'Fellowship Hall', NULL, NULL, 1, '2026-03-31 07:30:21', '2026-03-31 07:30:21'),
(4, 'Men Ministry', 'Building strong men of faith', 'Men Ministry Leader', NULL, 'Thursday', '19:00 PM', 'Conference Room', NULL, NULL, 1, '2026-03-31 07:30:21', '2026-03-31 07:30:21'),
(5, 'Music Ministry', 'Leading worship through music and song', 'Music Director', NULL, 'Saturday', '15:00 PM', 'Sanctuary', NULL, NULL, 1, '2026-03-31 07:30:21', '2026-03-31 07:30:21');

-- --------------------------------------------------------

--
-- Table structure for table `news`
--

CREATE TABLE `news` (
  `id` int NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `excerpt` text COLLATE utf8mb4_unicode_ci,
  `content` longtext COLLATE utf8mb4_unicode_ci,
  `featured_image_url` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `author_id` int DEFAULT NULL,
  `category` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tags` json DEFAULT NULL,
  `meta_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_description` text COLLATE utf8mb4_unicode_ci,
  `views_count` int DEFAULT '0',
  `likes_count` int DEFAULT '0',
  `is_featured` tinyint(1) DEFAULT '0',
  `is_breaking` tinyint(1) DEFAULT '0',
  `status` enum('draft','published','archived') COLLATE utf8mb4_unicode_ci DEFAULT 'draft',
  `published_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `news`
--

INSERT INTO `news` (`id`, `title`, `slug`, `excerpt`, `content`, `featured_image_url`, `author_id`, `category`, `tags`, `meta_title`, `meta_description`, `views_count`, `likes_count`, `is_featured`, `is_breaking`, `status`, `published_at`, `created_at`, `updated_at`) VALUES
(1, 'Church Anniversary Celebration', 'church-anniversary-celebration', 'Join us as we celebrate 10 years of God\'s faithfulness', 'We are excited to celebrate a decade of ministry...', NULL, 1, 'Announcements', NULL, NULL, NULL, 0, 0, 0, 0, 'published', '2026-03-31 07:30:23', '2026-03-31 07:30:23', '2026-03-31 07:30:23'),
(2, 'New Youth Program Launch', 'new-youth-program-launch', 'Exciting new programs for our youth ministry', 'Starting next month, we are launching new programs...', NULL, 1, 'Youth', NULL, NULL, NULL, 0, 0, 0, 0, 'published', '2026-03-31 07:30:23', '2026-03-31 07:30:23', '2026-03-31 07:30:23');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expires_at` timestamp NOT NULL,
  `used` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pastor_bookings`
--

CREATE TABLE `pastor_bookings` (
  `id` int NOT NULL,
  `booking_reference` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pastor_id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `client_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `client_email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `client_phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `booking_date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `duration_minutes` int NOT NULL,
  `booking_type` enum('counseling','prayer','guidance','deliverance','thanksgiving','general') COLLATE utf8mb4_unicode_ci DEFAULT 'counseling',
  `google_meet_link` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `google_meet_id` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meet_join_code` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('pending','confirmed','in_progress','completed','cancelled','no_show') COLLATE utf8mb4_unicode_ci DEFAULT 'pending',
  `confirmation_sent` tinyint(1) DEFAULT '0',
  `reminder_sent` tinyint(1) DEFAULT '0',
  `subject` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `prayer_points` text COLLATE utf8mb4_unicode_ci,
  `special_requests` text COLLATE utf8mb4_unicode_ci,
  `assigned_pastor` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `internal_notes` text COLLATE utf8mb4_unicode_ci,
  `confirmed_by` int DEFAULT NULL,
  `confirmed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pastor_booking_availability`
--

CREATE TABLE `pastor_booking_availability` (
  `id` int NOT NULL,
  `pastor_id` int NOT NULL,
  `day_of_week` enum('sunday','monday','tuesday','wednesday','thursday','friday','saturday') COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `is_available` tinyint(1) DEFAULT '1',
  `booking_duration_minutes` int DEFAULT '30',
  `max_bookings_per_day` int DEFAULT '5',
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pastor_booking_availability`
--

INSERT INTO `pastor_booking_availability` (`id`, `pastor_id`, `day_of_week`, `start_time`, `end_time`, `is_available`, `booking_duration_minutes`, `max_bookings_per_day`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 2, 'monday', '09:00:00', '12:00:00', 1, 30, 4, 1, '2026-03-31 07:30:21', '2026-03-31 07:30:21'),
(2, 2, 'monday', '14:00:00', '17:00:00', 1, 30, 4, 1, '2026-03-31 07:30:21', '2026-03-31 07:30:21'),
(3, 2, 'tuesday', '09:00:00', '12:00:00', 1, 30, 4, 1, '2026-03-31 07:30:21', '2026-03-31 07:30:21'),
(4, 2, 'tuesday', '14:00:00', '17:00:00', 1, 30, 4, 1, '2026-03-31 07:30:21', '2026-03-31 07:30:21'),
(5, 2, 'wednesday', '09:00:00', '12:00:00', 1, 30, 4, 1, '2026-03-31 07:30:21', '2026-03-31 07:30:21'),
(6, 2, 'thursday', '09:00:00', '12:00:00', 1, 30, 4, 1, '2026-03-31 07:30:21', '2026-03-31 07:30:21'),
(7, 2, 'thursday', '14:00:00', '17:00:00', 1, 30, 4, 1, '2026-03-31 07:30:21', '2026-03-31 07:30:21'),
(8, 2, 'friday', '09:00:00', '12:00:00', 1, 30, 4, 1, '2026-03-31 07:30:21', '2026-03-31 07:30:21');

-- --------------------------------------------------------

--
-- Table structure for table `prayer_requests`
--

CREATE TABLE `prayer_requests` (
  `id` int NOT NULL,
  `requester_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `requester_email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `requester_phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `request_type` enum('general','healing','guidance','protection','thanksgiving','deliverance','family','financial','spiritual_growth','other') COLLATE utf8mb4_unicode_ci DEFAULT 'general',
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `prayer_points` text COLLATE utf8mb4_unicode_ci,
  `is_anonymous` tinyint(1) DEFAULT '0',
  `is_public` tinyint(1) DEFAULT '1',
  `is_urgent` tinyint(1) DEFAULT '0',
  `prayer_count` int DEFAULT '0',
  `status` enum('pending','praying','answered','archived') COLLATE utf8mb4_unicode_ci DEFAULT 'pending',
  `answered_date` timestamp NULL DEFAULT NULL,
  `assigned_to` int DEFAULT NULL,
  `internal_notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `prayer_requests`
--

INSERT INTO `prayer_requests` (`id`, `requester_name`, `requester_email`, `requester_phone`, `request_type`, `title`, `description`, `prayer_points`, `is_anonymous`, `is_public`, `is_urgent`, `prayer_count`, `status`, `answered_date`, `assigned_to`, `internal_notes`, `created_at`, `updated_at`) VALUES
(1, 'John Doe', 'john@example.com', NULL, 'healing', 'Healing for Family Member', 'Please pray for my mother\'s recovery from surgery', NULL, 0, 1, 0, 0, 'pending', NULL, NULL, NULL, '2026-03-31 07:30:21', '2026-03-31 07:30:21'),
(2, 'Jane Smith', 'jane@example.com', NULL, 'guidance', 'Career Decision', 'Seeking God\'s guidance for a job opportunity', NULL, 0, 1, 0, 0, 'pending', NULL, NULL, NULL, '2026-03-31 07:30:21', '2026-03-31 07:30:21');

-- --------------------------------------------------------

--
-- Table structure for table `prayer_responses`
--

CREATE TABLE `prayer_responses` (
  `id` int NOT NULL,
  `prayer_request_id` int NOT NULL,
  `responder_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `responder_email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_anonymous` tinyint(1) DEFAULT '1',
  `prayer_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sermons`
--

CREATE TABLE `sermons` (
  `id` int NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `preacher` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bible_reference` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sermon_date` date NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `video_url` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `audio_url` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `transcript` text COLLATE utf8mb4_unicode_ci,
  `featured_image_url` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sermon_series` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tags` json DEFAULT NULL,
  `views_count` int DEFAULT '0',
  `is_featured` tinyint(1) DEFAULT '0',
  `status` enum('draft','published','archived') COLLATE utf8mb4_unicode_ci DEFAULT 'draft',
  `created_by` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sermons`
--

INSERT INTO `sermons` (`id`, `title`, `preacher`, `bible_reference`, `sermon_date`, `description`, `video_url`, `audio_url`, `transcript`, `featured_image_url`, `sermon_series`, `tags`, `views_count`, `is_featured`, `status`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'The Power of Faith', 'Pastor John Smith', 'Hebrews 11:1', '2024-03-24', 'Discover the transformative power of unwavering faith in God', NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 'published', 1, '2026-03-31 07:30:21', '2026-03-31 07:30:21'),
(2, 'Walking in Love', 'Pastor Mary Johnson', '1 Corinthians 13', '2024-03-17', 'Learn how to love others as Christ loves us', NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 'published', 1, '2026-03-31 07:30:21', '2026-03-31 07:30:21');

-- --------------------------------------------------------

--
-- Table structure for table `service_times`
--

CREATE TABLE `service_times` (
  `id` int NOT NULL,
  `service_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `day_of_week` enum('sunday','monday','tuesday','wednesday','thursday','friday','saturday') COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `location` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `service_times`
--

INSERT INTO `service_times` (`id`, `service_name`, `day_of_week`, `start_time`, `end_time`, `location`, `description`, `is_active`, `created_at`, `updated_at`) VALUES
(1, '1st Service', 'sunday', '08:00:00', '09:30:00', 'Main Sanctuary', 'Morning Worship Service', 1, '2026-03-31 07:30:21', '2026-03-31 07:30:21'),
(2, '2nd Service', 'sunday', '10:30:00', '12:00:00', 'Main Sanctuary', 'Main Worship Service', 1, '2026-03-31 07:30:21', '2026-03-31 07:30:21'),
(3, 'Prayers', 'wednesday', '17:30:00', '18:30:00', 'Prayer Room', 'Mid-week Prayer Meeting', 1, '2026-03-31 07:30:21', '2026-03-31 07:30:21'),
(4, 'Youth Fellowship', 'friday', '17:00:00', '18:30:00', 'Youth Center', 'Youth Ministry Gathering', 1, '2026-03-31 07:30:21', '2026-03-31 07:30:21');

-- --------------------------------------------------------

--
-- Table structure for table `testimonials`
--

CREATE TABLE `testimonials` (
  `id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `occupation` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `testimonial` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `rating` int DEFAULT NULL,
  `photo_url` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_featured` tinyint(1) DEFAULT '0',
  `is_approved` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ;

--
-- Dumping data for table `testimonials`
--

INSERT INTO `testimonials` (`id`, `name`, `email`, `occupation`, `testimonial`, `rating`, `photo_url`, `is_featured`, `is_approved`, `created_at`, `updated_at`) VALUES
(1, 'Sarah Williams', 'sarah@example.com', 'Teacher', 'This church has transformed my spiritual life. The teachings are practical and life-changing.', 5, NULL, 0, 1, '2026-03-31 07:30:22', '2026-03-31 07:30:22'),
(2, 'Michael Brown', 'michael@example.com', 'Engineer', 'I found a loving community here that supports me in my faith journey.', 5, NULL, 0, 1, '2026-03-31 07:30:22', '2026-03-31 07:30:22');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `first_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password_hash` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('admin','pastor','member','visitor','teacher') COLLATE utf8mb4_unicode_ci DEFAULT 'visitor',
  `avatar_url` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  `email_verified` tinyint(1) DEFAULT '0',
  `last_login` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `email`, `phone`, `password_hash`, `role`, `avatar_url`, `is_active`, `email_verified`, `last_login`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'User', 'admin@salemministries.com', NULL, '$2a$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', NULL, 1, 1, NULL, '2026-03-31 07:30:21', '2026-03-31 07:30:21'),
(2, 'Senior', 'Pastor', 'pastor@salemdominionministries.com', NULL, '$2a$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'pastor', NULL, 1, 1, NULL, '2026-03-31 07:30:21', '2026-03-31 07:30:21');

-- --------------------------------------------------------

--
-- Table structure for table `user_sessions`
--

CREATE TABLE `user_sessions` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `session_token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expires_at` timestamp NOT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_action` (`action`),
  ADD KEY `idx_created_at` (`created_at`),
  ADD KEY `idx_table` (`table_name`);

--
-- Indexes for table `blog_posts`
--
ALTER TABLE `blog_posts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `idx_slug` (`slug`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_published_at` (`published_at`),
  ADD KEY `idx_featured` (`is_featured`),
  ADD KEY `idx_category` (`category`);

--
-- Indexes for table `children_attendance`
--
ALTER TABLE `children_attendance`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_attendance_date` (`attendance_date`),
  ADD KEY `idx_child` (`child_id`),
  ADD KEY `idx_class` (`class_id`),
  ADD KEY `idx_lesson` (`lesson_id`);

--
-- Indexes for table `children_classes`
--
ALTER TABLE `children_classes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_age_group` (`age_group`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_teacher` (`teacher_id`);

--
-- Indexes for table `children_events`
--
ALTER TABLE `children_events`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_event_date` (`event_date`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_type` (`event_type`),
  ADD KEY `idx_age_group` (`age_group`);

--
-- Indexes for table `children_gallery`
--
ALTER TABLE `children_gallery`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_category` (`category`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_featured` (`is_featured`),
  ADD KEY `idx_type` (`file_type`);

--
-- Indexes for table `children_lessons`
--
ALTER TABLE `children_lessons`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_lesson_date` (`lesson_date`),
  ADD KEY `idx_class` (`class_id`),
  ADD KEY `idx_teacher` (`teacher_id`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_age_group` (`age_group`);

--
-- Indexes for table `children_news`
--
ALTER TABLE `children_news`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_published_at` (`published_at`),
  ADD KEY `idx_featured` (`is_featured`),
  ADD KEY `idx_audience` (`target_audience`),
  ADD KEY `idx_category` (`category`);

--
-- Indexes for table `children_parents`
--
ALTER TABLE `children_parents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_email` (`email`),
  ADD KEY `idx_church_member` (`church_member`),
  ADD KEY `idx_volunteer` (`willingness_to_volunteer`);

--
-- Indexes for table `children_registration`
--
ALTER TABLE `children_registration`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_age` (`age`),
  ADD KEY `idx_class` (`class_id`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_parent_email` (`parent_email`);

--
-- Indexes for table `children_resources`
--
ALTER TABLE `children_resources`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_type` (`resource_type`),
  ADD KEY `idx_age_group` (`age_group`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_category` (`category`),
  ADD KEY `idx_free` (`is_free`);

--
-- Indexes for table `children_teachers`
--
ALTER TABLE `children_teachers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_user` (`user_id`),
  ADD KEY `idx_background_check` (`background_check`);

--
-- Indexes for table `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_priority` (`priority`),
  ADD KEY `idx_created_at` (`created_at`),
  ADD KEY `idx_type` (`message_type`);

--
-- Indexes for table `donations`
--
ALTER TABLE `donations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_donor_email` (`donor_email`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_created_at` (`created_at`),
  ADD KEY `idx_type` (`donation_type`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_event_date` (`event_date`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_type` (`event_type`);

--
-- Indexes for table `event_registrations`
--
ALTER TABLE `event_registrations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_registration` (`event_id`,`email`),
  ADD KEY `idx_event_id` (`event_id`),
  ADD KEY `idx_status` (`status`);

--
-- Indexes for table `gallery`
--
ALTER TABLE `gallery`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_file_type` (`file_type`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_featured` (`is_featured`),
  ADD KEY `idx_category` (`category`);

--
-- Indexes for table `ministries`
--
ALTER TABLE `ministries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_is_active` (`is_active`),
  ADD KEY `idx_name` (`name`);

--
-- Indexes for table `news`
--
ALTER TABLE `news`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `idx_slug` (`slug`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_published_at` (`published_at`),
  ADD KEY `idx_featured` (`is_featured`),
  ADD KEY `idx_breaking` (`is_breaking`),
  ADD KEY `idx_category` (`category`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `token` (`token`),
  ADD KEY `idx_token` (`token`),
  ADD KEY `idx_user` (`user_id`);

--
-- Indexes for table `pastor_bookings`
--
ALTER TABLE `pastor_bookings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `booking_reference` (`booking_reference`),
  ADD KEY `idx_booking_date` (`booking_date`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_client_email` (`client_email`),
  ADD KEY `idx_reference` (`booking_reference`),
  ADD KEY `idx_pastor_id` (`pastor_id`);

--
-- Indexes for table `pastor_booking_availability`
--
ALTER TABLE `pastor_booking_availability`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_pastor` (`pastor_id`),
  ADD KEY `idx_day` (`day_of_week`),
  ADD KEY `idx_active` (`is_active`);

--
-- Indexes for table `prayer_requests`
--
ALTER TABLE `prayer_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_is_public` (`is_public`),
  ADD KEY `idx_created_at` (`created_at`),
  ADD KEY `idx_type` (`request_type`),
  ADD KEY `idx_urgent` (`is_urgent`);

--
-- Indexes for table `prayer_responses`
--
ALTER TABLE `prayer_responses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_prayer_request_id` (`prayer_request_id`);

--
-- Indexes for table `sermons`
--
ALTER TABLE `sermons`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_sermon_date` (`sermon_date`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_featured` (`is_featured`),
  ADD KEY `idx_series` (`sermon_series`);

--
-- Indexes for table `service_times`
--
ALTER TABLE `service_times`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_day_of_week` (`day_of_week`),
  ADD KEY `idx_is_active` (`is_active`);

--
-- Indexes for table `testimonials`
--
ALTER TABLE `testimonials`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_is_approved` (`is_approved`),
  ADD KEY `idx_featured` (`is_featured`),
  ADD KEY `idx_rating` (`rating`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_email` (`email`),
  ADD KEY `idx_role` (`role`),
  ADD KEY `idx_active` (`is_active`);

--
-- Indexes for table `user_sessions`
--
ALTER TABLE `user_sessions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `session_token` (`session_token`),
  ADD KEY `idx_token` (`session_token`),
  ADD KEY `idx_user` (`user_id`),
  ADD KEY `idx_expires` (`expires_at`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `blog_posts`
--
ALTER TABLE `blog_posts`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `children_attendance`
--
ALTER TABLE `children_attendance`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `children_classes`
--
ALTER TABLE `children_classes`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `children_events`
--
ALTER TABLE `children_events`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `children_gallery`
--
ALTER TABLE `children_gallery`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `children_lessons`
--
ALTER TABLE `children_lessons`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `children_news`
--
ALTER TABLE `children_news`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `children_parents`
--
ALTER TABLE `children_parents`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `children_registration`
--
ALTER TABLE `children_registration`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `children_resources`
--
ALTER TABLE `children_resources`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `children_teachers`
--
ALTER TABLE `children_teachers`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `contact_messages`
--
ALTER TABLE `contact_messages`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `donations`
--
ALTER TABLE `donations`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `event_registrations`
--
ALTER TABLE `event_registrations`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gallery`
--
ALTER TABLE `gallery`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `ministries`
--
ALTER TABLE `ministries`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `news`
--
ALTER TABLE `news`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pastor_bookings`
--
ALTER TABLE `pastor_bookings`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pastor_booking_availability`
--
ALTER TABLE `pastor_booking_availability`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `prayer_requests`
--
ALTER TABLE `prayer_requests`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `prayer_responses`
--
ALTER TABLE `prayer_responses`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sermons`
--
ALTER TABLE `sermons`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `service_times`
--
ALTER TABLE `service_times`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `testimonials`
--
ALTER TABLE `testimonials`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `user_sessions`
--
ALTER TABLE `user_sessions`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
