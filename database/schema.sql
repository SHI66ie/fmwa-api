-- FMWA Database Schema
-- Federal Ministry of Women Affairs Website Database
-- Created: 2025-01-14

-- Create database if not exists
CREATE DATABASE IF NOT EXISTS fmwa_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE fmwa_db;

-- Drop tables if they exist (for clean installation)
DROP TABLE IF EXISTS post_meta;
DROP TABLE IF EXISTS post_categories;
DROP TABLE IF EXISTS comments;
DROP TABLE IF EXISTS posts;
DROP TABLE IF EXISTS categories;
DROP TABLE IF EXISTS media;
DROP TABLE IF EXISTS pages;
DROP TABLE IF EXISTS settings;
DROP TABLE IF EXISTS users;
DROP TABLE IF EXISTS visitor_stats;
DROP TABLE IF EXISTS activity_log;

-- ============================================
-- Users Table
-- ============================================
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    role ENUM('admin', 'editor', 'author', 'subscriber') DEFAULT 'subscriber',
    status ENUM('active', 'inactive', 'suspended') DEFAULT 'active',
    avatar VARCHAR(255) DEFAULT NULL,
    bio TEXT DEFAULT NULL,
    phone VARCHAR(20) DEFAULT NULL,
    last_login DATETIME DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_username (username),
    INDEX idx_email (email),
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Categories Table
-- ============================================
CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(100) NOT NULL UNIQUE,
    description TEXT DEFAULT NULL,
    parent_id INT DEFAULT NULL,
    display_order INT DEFAULT 0,
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (parent_id) REFERENCES categories(id) ON DELETE SET NULL,
    INDEX idx_slug (slug),
    INDEX idx_parent (parent_id),
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Posts Table
-- ============================================
CREATE TABLE posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    content LONGTEXT NOT NULL,
    excerpt TEXT DEFAULT NULL,
    featured_image VARCHAR(255) DEFAULT NULL,
    author_id INT NOT NULL,
    status ENUM('draft', 'published', 'scheduled', 'archived') DEFAULT 'draft',
    post_type ENUM('post', 'news', 'event', 'page') DEFAULT 'post',
    views INT DEFAULT 0,
    allow_comments BOOLEAN DEFAULT TRUE,
    is_featured BOOLEAN DEFAULT FALSE,
    published_at DATETIME DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (author_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_slug (slug),
    INDEX idx_author (author_id),
    INDEX idx_status (status),
    INDEX idx_post_type (post_type),
    INDEX idx_published_at (published_at),
    FULLTEXT idx_search (title, content, excerpt)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Post Categories (Many-to-Many)
-- ============================================
CREATE TABLE post_categories (
    post_id INT NOT NULL,
    category_id INT NOT NULL,
    PRIMARY KEY (post_id, category_id),
    FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE,
    INDEX idx_post (post_id),
    INDEX idx_category (category_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Post Meta Table
-- ============================================
CREATE TABLE post_meta (
    id INT AUTO_INCREMENT PRIMARY KEY,
    post_id INT NOT NULL,
    meta_key VARCHAR(100) NOT NULL,
    meta_value LONGTEXT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE,
    INDEX idx_post (post_id),
    INDEX idx_meta_key (meta_key),
    UNIQUE KEY unique_post_meta (post_id, meta_key)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Comments Table
-- ============================================
CREATE TABLE comments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    post_id INT NOT NULL,
    user_id INT DEFAULT NULL,
    author_name VARCHAR(100) NOT NULL,
    author_email VARCHAR(100) NOT NULL,
    author_ip VARCHAR(45) DEFAULT NULL,
    content TEXT NOT NULL,
    parent_id INT DEFAULT NULL,
    status ENUM('pending', 'approved', 'spam', 'trash') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (parent_id) REFERENCES comments(id) ON DELETE CASCADE,
    INDEX idx_post (post_id),
    INDEX idx_user (user_id),
    INDEX idx_status (status),
    INDEX idx_parent (parent_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Media Table
-- ============================================
CREATE TABLE media (
    id INT AUTO_INCREMENT PRIMARY KEY,
    filename VARCHAR(255) NOT NULL,
    original_filename VARCHAR(255) NOT NULL,
    file_path VARCHAR(500) NOT NULL,
    file_url VARCHAR(500) NOT NULL,
    file_type VARCHAR(50) NOT NULL,
    file_size INT NOT NULL,
    mime_type VARCHAR(100) NOT NULL,
    width INT DEFAULT NULL,
    height INT DEFAULT NULL,
    title VARCHAR(255) DEFAULT NULL,
    alt_text VARCHAR(255) DEFAULT NULL,
    caption TEXT DEFAULT NULL,
    description TEXT DEFAULT NULL,
    uploaded_by INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (uploaded_by) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_filename (filename),
    INDEX idx_file_type (file_type),
    INDEX idx_uploaded_by (uploaded_by)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Pages Table
-- ============================================
CREATE TABLE pages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    content LONGTEXT NOT NULL,
    template VARCHAR(100) DEFAULT 'default',
    parent_id INT DEFAULT NULL,
    display_order INT DEFAULT 0,
    status ENUM('draft', 'published', 'private') DEFAULT 'draft',
    meta_title VARCHAR(255) DEFAULT NULL,
    meta_description TEXT DEFAULT NULL,
    meta_keywords VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (parent_id) REFERENCES pages(id) ON DELETE SET NULL,
    INDEX idx_slug (slug),
    INDEX idx_parent (parent_id),
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Settings Table
-- ============================================
CREATE TABLE settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(100) NOT NULL UNIQUE,
    setting_value LONGTEXT DEFAULT NULL,
    setting_type ENUM('string', 'number', 'boolean', 'json', 'text') DEFAULT 'string',
    description TEXT DEFAULT NULL,
    is_autoload BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_key (setting_key),
    INDEX idx_autoload (is_autoload)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Visitor Statistics Table
-- ============================================
CREATE TABLE visitor_stats (
    id INT AUTO_INCREMENT PRIMARY KEY,
    page_url VARCHAR(500) NOT NULL,
    visitor_ip VARCHAR(45) NOT NULL,
    user_agent TEXT DEFAULT NULL,
    referrer VARCHAR(500) DEFAULT NULL,
    country VARCHAR(100) DEFAULT NULL,
    city VARCHAR(100) DEFAULT NULL,
    device_type ENUM('desktop', 'mobile', 'tablet', 'other') DEFAULT 'other',
    browser VARCHAR(50) DEFAULT NULL,
    os VARCHAR(50) DEFAULT NULL,
    visit_date DATE NOT NULL,
    visit_time TIME NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_page (page_url(255)),
    INDEX idx_date (visit_date),
    INDEX idx_ip (visitor_ip)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Activity Log Table
-- ============================================
CREATE TABLE activity_log (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT DEFAULT NULL,
    action VARCHAR(100) NOT NULL,
    entity_type VARCHAR(50) DEFAULT NULL,
    entity_id INT DEFAULT NULL,
    description TEXT DEFAULT NULL,
    ip_address VARCHAR(45) DEFAULT NULL,
    user_agent TEXT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_user (user_id),
    INDEX idx_action (action),
    INDEX idx_entity (entity_type, entity_id),
    INDEX idx_created (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Insert Default Data
-- ============================================

-- Default Admin User (password: admin123)
INSERT INTO users (username, email, password, full_name, role, status) VALUES
('admin', 'admin@fmwa.gov.ng', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'System Administrator', 'admin', 'active');

-- Default Categories
INSERT INTO categories (name, slug, description, display_order, status) VALUES
('News', 'news', 'Latest news and updates from FMWA', 1, 'active'),
('Events', 'events', 'Upcoming and past events', 2, 'active'),
('Announcements', 'announcements', 'Official announcements', 3, 'active'),
('Press Releases', 'press-releases', 'Official press releases', 4, 'active'),
('Programs', 'programs', 'Ministry programs and initiatives', 5, 'active'),
('Reports', 'reports', 'Annual reports and publications', 6, 'active');

-- Default Settings
INSERT INTO settings (setting_key, setting_value, setting_type, description, is_autoload) VALUES
('site_name', 'Federal Ministry of Women Affairs', 'string', 'Website name', TRUE),
('site_description', 'Official website of the Federal Ministry of Women Affairs', 'text', 'Website description', TRUE),
('site_email', 'info@fmwa.gov.ng', 'string', 'Contact email', TRUE),
('site_phone', '+234-9-461-0000', 'string', 'Contact phone', TRUE),
('posts_per_page', '10', 'number', 'Number of posts per page', TRUE),
('allow_comments', 'true', 'boolean', 'Allow comments on posts', TRUE),
('comment_moderation', 'true', 'boolean', 'Moderate comments before publishing', TRUE),
('timezone', 'Africa/Lagos', 'string', 'Website timezone', TRUE),
('date_format', 'Y-m-d', 'string', 'Date format', TRUE),
('time_format', 'H:i:s', 'string', 'Time format', TRUE);

-- Sample Welcome Post
INSERT INTO posts (title, slug, content, excerpt, author_id, status, post_type, published_at) VALUES
('Welcome to FMWA Website', 'welcome-to-fmwa-website', 
'<h2>Welcome to the Federal Ministry of Women Affairs</h2>
<p>We are pleased to welcome you to the official website of the Federal Ministry of Women Affairs. This platform serves as a comprehensive resource for information about our ministry, programs, and initiatives aimed at empowering women and promoting gender equality in Nigeria.</p>
<h3>Our Mission</h3>
<p>To formulate, implement and monitor policies and programs that promote the advancement and empowerment of women in Nigeria.</p>
<h3>What You Can Find Here</h3>
<ul>
<li>Latest news and updates</li>
<li>Information about our programs and services</li>
<li>Resources and publications</li>
<li>Contact information</li>
</ul>
<p>We encourage you to explore our website and learn more about our work.</p>',
'Welcome to the official website of the Federal Ministry of Women Affairs. Learn about our mission, programs, and initiatives.',
1, 'published', 'post', NOW());

-- Link welcome post to News category
INSERT INTO post_categories (post_id, category_id) VALUES (1, 1);

-- ============================================
-- Create Views for Common Queries
-- ============================================

-- View for published posts with author info
CREATE OR REPLACE VIEW vw_published_posts AS
SELECT 
    p.id,
    p.title,
    p.slug,
    p.excerpt,
    p.featured_image,
    p.post_type,
    p.views,
    p.published_at,
    p.created_at,
    u.full_name as author_name,
    u.username as author_username,
    GROUP_CONCAT(c.name SEPARATOR ', ') as categories
FROM posts p
INNER JOIN users u ON p.author_id = u.id
LEFT JOIN post_categories pc ON p.id = pc.post_id
LEFT JOIN categories c ON pc.category_id = c.id
WHERE p.status = 'published' AND p.published_at <= NOW()
GROUP BY p.id, p.title, p.slug, p.excerpt, p.featured_image, p.post_type, p.views, p.published_at, p.created_at, u.full_name, u.username
ORDER BY p.published_at DESC;

-- View for visitor statistics summary
CREATE OR REPLACE VIEW vw_visitor_summary AS
SELECT 
    visit_date,
    COUNT(*) as total_visits,
    COUNT(DISTINCT visitor_ip) as unique_visitors,
    SUM(CASE WHEN device_type = 'mobile' THEN 1 ELSE 0 END) as mobile_visits,
    SUM(CASE WHEN device_type = 'desktop' THEN 1 ELSE 0 END) as desktop_visits,
    SUM(CASE WHEN device_type = 'tablet' THEN 1 ELSE 0 END) as tablet_visits
FROM visitor_stats
GROUP BY visit_date
ORDER BY visit_date DESC;

-- ============================================
-- Database Setup Complete
-- ============================================
