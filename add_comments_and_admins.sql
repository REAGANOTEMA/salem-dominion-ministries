-- Add comments table for blog posts
CREATE TABLE IF NOT EXISTS blog_comments (
  id int NOT NULL AUTO_INCREMENT,
  post_id int NOT NULL,
  user_id int DEFAULT NULL,
  author_name varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  author_email varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  comment text COLLATE utf8mb4_unicode_ci NOT NULL,
  is_approved tinyint(1) DEFAULT '0',
  parent_id int DEFAULT NULL,
  created_at timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY idx_post_id (post_id),
  KEY idx_user_id (user_id),
  KEY idx_approved (is_approved),
  KEY idx_parent (parent_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Add admin users
INSERT INTO users (first_name, last_name, email, phone, password_hash, role, avatar_url, is_active, email_verified, last_login, created_at, updated_at) VALUES
('Faty', 'Musasizi', 'faty@salemdominionministries.com', NULL, '$2a$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', NULL, 1, 1, NULL, NOW(), NOW()),
('Reagan', 'Otema', 'reagan@salemdominionministries.com', NULL, '$2a$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', NULL, 1, 1, NULL, NOW(), NOW())
ON DUPLICATE KEY UPDATE
first_name = VALUES(first_name),
last_name = VALUES(last_name),
role = VALUES(role),
is_active = VALUES(is_active),
email_verified = VALUES(email_verified);