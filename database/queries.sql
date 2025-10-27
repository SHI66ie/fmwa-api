-- FMWA Common Database Queries
-- Quick reference for frequently used queries

-- ============================================
-- USER QUERIES
-- ============================================

-- Get all active users
SELECT * FROM users WHERE status = 'active' ORDER BY full_name;

-- Get user by username
SELECT * FROM users WHERE username = 'admin';

-- Count users by role
SELECT role, COUNT(*) as count FROM users GROUP BY role;

-- Update user password (replace with actual hashed password)
UPDATE users SET password = '$2y$10$...' WHERE username = 'admin';

-- Create new user
INSERT INTO users (username, email, password, full_name, role, status)
VALUES ('newuser', 'user@example.com', '$2y$10$...', 'New User', 'editor', 'active');

-- ============================================
-- POST QUERIES
-- ============================================

-- Get all published posts
SELECT p.*, u.full_name as author_name 
FROM posts p 
INNER JOIN users u ON p.author_id = u.id 
WHERE p.status = 'published' 
ORDER BY p.published_at DESC;

-- Get posts by category
SELECT p.*, c.name as category_name
FROM posts p
INNER JOIN post_categories pc ON p.id = pc.post_id
INNER JOIN categories c ON pc.category_id = c.id
WHERE c.slug = 'news' AND p.status = 'published'
ORDER BY p.published_at DESC;

-- Get featured posts
SELECT * FROM posts 
WHERE status = 'published' AND is_featured = 1 
ORDER BY published_at DESC 
LIMIT 5;

-- Count posts by status
SELECT status, COUNT(*) as count FROM posts GROUP BY status;

-- Get most viewed posts
SELECT * FROM posts 
WHERE status = 'published' 
ORDER BY views DESC 
LIMIT 10;

-- Search posts
SELECT * FROM posts 
WHERE status = 'published' 
AND (title LIKE '%keyword%' OR content LIKE '%keyword%')
ORDER BY published_at DESC;

-- ============================================
-- CATEGORY QUERIES
-- ============================================

-- Get all categories with post counts
SELECT c.*, COUNT(pc.post_id) as post_count
FROM categories c
LEFT JOIN post_categories pc ON c.id = pc.category_id
GROUP BY c.id
ORDER BY c.display_order;

-- Get category by slug
SELECT * FROM categories WHERE slug = 'news';

-- ============================================
-- MEDIA QUERIES
-- ============================================

-- Get all media files
SELECT * FROM media ORDER BY created_at DESC;

-- Get images only
SELECT * FROM media WHERE file_type = 'image' ORDER BY created_at DESC;

-- Get media by user
SELECT m.*, u.full_name as uploader_name
FROM media m
INNER JOIN users u ON m.uploaded_by = u.id
WHERE u.username = 'admin'
ORDER BY m.created_at DESC;

-- ============================================
-- VISITOR STATISTICS
-- ============================================

-- Get daily visitor count
SELECT visit_date, COUNT(*) as visits, COUNT(DISTINCT visitor_ip) as unique_visitors
FROM visitor_stats
GROUP BY visit_date
ORDER BY visit_date DESC
LIMIT 30;

-- Get most visited pages
SELECT page_url, COUNT(*) as visits
FROM visitor_stats
GROUP BY page_url
ORDER BY visits DESC
LIMIT 10;

-- Get visitor statistics by device
SELECT device_type, COUNT(*) as count
FROM visitor_stats
GROUP BY device_type;

-- Get visitor statistics by browser
SELECT browser, COUNT(*) as count
FROM visitor_stats
GROUP BY browser
ORDER BY count DESC;

-- ============================================
-- ACTIVITY LOG
-- ============================================

-- Get recent activities
SELECT a.*, u.username
FROM activity_log a
LEFT JOIN users u ON a.user_id = u.id
ORDER BY a.created_at DESC
LIMIT 50;

-- Get activities by user
SELECT * FROM activity_log 
WHERE user_id = 1 
ORDER BY created_at DESC;

-- Get activities by action type
SELECT action, COUNT(*) as count
FROM activity_log
GROUP BY action
ORDER BY count DESC;

-- ============================================
-- SETTINGS
-- ============================================

-- Get all settings
SELECT * FROM settings WHERE is_autoload = 1;

-- Get specific setting
SELECT setting_value FROM settings WHERE setting_key = 'site_name';

-- Update setting
UPDATE settings SET setting_value = 'New Value' WHERE setting_key = 'site_name';

-- ============================================
-- MAINTENANCE QUERIES
-- ============================================

-- Optimize all tables
OPTIMIZE TABLE users, posts, categories, post_categories, media, pages, settings, visitor_stats, activity_log;

-- Check table sizes
SELECT 
    table_name AS 'Table',
    ROUND(((data_length + index_length) / 1024 / 1024), 2) AS 'Size (MB)'
FROM information_schema.TABLES
WHERE table_schema = 'fmwa_db'
ORDER BY (data_length + index_length) DESC;

-- Backup database (run from command line)
-- mysqldump -u root -p fmwa_db > backup.sql

-- Restore database (run from command line)
-- mysql -u root -p fmwa_db < backup.sql

-- ============================================
-- CLEANUP QUERIES
-- ============================================

-- Delete old visitor stats (older than 1 year)
DELETE FROM visitor_stats WHERE visit_date < DATE_SUB(NOW(), INTERVAL 1 YEAR);

-- Delete old activity logs (older than 6 months)
DELETE FROM activity_log WHERE created_at < DATE_SUB(NOW(), INTERVAL 6 MONTH);

-- Delete spam comments
DELETE FROM comments WHERE status = 'spam';

-- Delete draft posts older than 30 days
DELETE FROM posts WHERE status = 'draft' AND created_at < DATE_SUB(NOW(), INTERVAL 30 DAY);

-- ============================================
-- REPORTING QUERIES
-- ============================================

-- Monthly post statistics
SELECT 
    DATE_FORMAT(published_at, '%Y-%m') as month,
    COUNT(*) as posts_published
FROM posts
WHERE status = 'published'
GROUP BY month
ORDER BY month DESC;

-- User activity summary
SELECT 
    u.username,
    u.full_name,
    COUNT(p.id) as total_posts,
    SUM(p.views) as total_views
FROM users u
LEFT JOIN posts p ON u.id = p.author_id
GROUP BY u.id
ORDER BY total_posts DESC;

-- Category popularity
SELECT 
    c.name,
    COUNT(pc.post_id) as post_count,
    SUM(p.views) as total_views
FROM categories c
LEFT JOIN post_categories pc ON c.id = pc.category_id
LEFT JOIN posts p ON pc.post_id = p.id
GROUP BY c.id
ORDER BY post_count DESC;

-- ============================================
-- VIEWS
-- ============================================

-- Use the published posts view
SELECT * FROM vw_published_posts LIMIT 10;

-- Use the visitor summary view
SELECT * FROM vw_visitor_summary WHERE visit_date >= DATE_SUB(NOW(), INTERVAL 7 DAY);
