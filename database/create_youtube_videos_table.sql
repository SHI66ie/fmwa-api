-- Create YouTube Videos Table
-- Run this script to add the missing youtube_videos table to your database

USE womenaffairsgov_fmwa_db;

-- ============================================
-- YouTube Videos Table
-- ============================================
CREATE TABLE youtube_videos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT DEFAULT NULL,
    video_id VARCHAR(20) NOT NULL UNIQUE,
    video_url VARCHAR(500) NOT NULL,
    thumbnail_url VARCHAR(500) DEFAULT NULL,
    duration VARCHAR(20) DEFAULT NULL,
    status ENUM('active', 'inactive') DEFAULT 'active',
    featured BOOLEAN DEFAULT FALSE,
    display_order INT DEFAULT 0,
    created_by INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_video_id (video_id),
    INDEX idx_status (status),
    INDEX idx_featured (featured),
    INDEX idx_created_by (created_by)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert sample data for testing
INSERT INTO youtube_videos (title, description, video_id, video_url, thumbnail_url, created_by)
VALUES ('Civil Service Anthem', 'Official Civil Service Anthem video', 'DDfjkhRuLKA', 'https://www.youtube.com/watch?v=DDfjkhRuLKA', 'https://img.youtube.com/vi/DDfjkhRuLKA/maxresdefault.jpg', 1);

-- Table created successfully
SELECT 'YouTube videos table created successfully' as status;
