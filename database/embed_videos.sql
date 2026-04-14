-- Embedded Videos Table
-- Stores iframe and embed codes from various sources

CREATE TABLE IF NOT EXISTS `embed_videos` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `title` VARCHAR(255) DEFAULT NULL COMMENT 'Video title',
    `description` TEXT DEFAULT NULL COMMENT 'Video description',
    `embed_code` TEXT NOT NULL COMMENT 'Full iframe embed code',
    `source_url` VARCHAR(500) DEFAULT NULL COMMENT 'Extracted source URL',
    `uploaded_by` INT NOT NULL COMMENT 'Admin user ID who uploaded',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_uploaded_by` (`uploaded_by`),
    INDEX `idx_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Embedded videos from various sources';
