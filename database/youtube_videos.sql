-- YouTube Videos Table
-- Stores YouTube video information for embedding

CREATE TABLE IF NOT EXISTS `youtube_videos` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `video_id` VARCHAR(20) NOT NULL COMMENT 'YouTube video ID',
    `title` VARCHAR(255) DEFAULT NULL COMMENT 'Video title',
    `description` TEXT DEFAULT NULL COMMENT 'Video description',
    `youtube_url` VARCHAR(500) NOT NULL COMMENT 'Full YouTube URL',
    `thumbnail_url` VARCHAR(500) DEFAULT NULL COMMENT 'YouTube thumbnail URL',
    `uploaded_by` INT NOT NULL COMMENT 'Admin user ID who uploaded',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_video_id` (`video_id`),
    INDEX `idx_uploaded_by` (`uploaded_by`),
    INDEX `idx_created_at` (`created_at`),
    UNIQUE KEY `unique_video_id` (`video_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='YouTube videos for embedding';
