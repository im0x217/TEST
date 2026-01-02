-- =============================================
-- English News CMS Database Schema
-- Version: 1.0 (2026)
-- Description: Lightweight CMS with multi-template support
-- =============================================

-- Create Database
CREATE DATABASE IF NOT EXISTS english_news_cms 
CHARACTER SET utf8mb4 
COLLATE utf8mb4_unicode_ci;

USE english_news_cms;

-- =============================================
-- Table: admin_users
-- Purpose: Store admin login credentials
-- =============================================
CREATE TABLE IF NOT EXISTS admin_users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    last_login TIMESTAMP NULL DEFAULT NULL,
    INDEX idx_username (username)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert default admin user (password: admin123 - CHANGE THIS!)
INSERT INTO admin_users (username, password, email) VALUES 
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@example.com');
-- Note: Password is hashed using PHP password_hash(). Change after first login!

-- =============================================
-- Table: news
-- Purpose: Store all news articles
-- =============================================
CREATE TABLE IF NOT EXISTS news (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    content TEXT NOT NULL,
    image_path VARCHAR(255) DEFAULT NULL,
    status ENUM('draft', 'published') DEFAULT 'published',
    views INT UNSIGNED DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_slug (slug),
    INDEX idx_status (status),
    INDEX idx_created_at (created_at DESC),
    FULLTEXT idx_search (title, content)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Sample news data
INSERT INTO news (title, slug, content, image_path, status) VALUES 
(
    'Welcome to Our News Platform',
    'welcome-to-our-news-platform',
    '<p>We are excited to launch our new English news platform. Stay tuned for the latest updates and breaking news!</p><p>Our team is dedicated to bringing you accurate, timely, and engaging content on the topics that matter most.</p>',
    'uploads/sample-news.jpg',
    'published'
),
(
    'Getting Started with Our CMS',
    'getting-started-with-our-cms',
    '<p>This is a sample article to demonstrate the CMS capabilities. You can easily manage content, switch themes, and customize your website.</p><p>The admin panel provides full control over your content and website appearance.</p>',
    'uploads/sample-cms.jpg',
    'published'
);

-- =============================================
-- Table: site_settings
-- Purpose: Store global configuration (key-value pairs)
-- =============================================
CREATE TABLE IF NOT EXISTS site_settings (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(100) NOT NULL UNIQUE,
    setting_value TEXT NOT NULL,
    description VARCHAR(255) DEFAULT NULL,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_setting_key (setting_key)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert default site settings
INSERT INTO site_settings (setting_key, setting_value, description) VALUES 
('site_name', 'English News Hub', 'Website name displayed in title'),
('site_tagline', 'Your Daily Source for Breaking News', 'Website tagline/description'),
('active_template', 'template_1', 'Active frontend template (template_1 or template_2)'),
('social_x', 'https://x.com/yournews', 'X (Twitter) profile URL'),
('social_discord', 'https://discord.gg/yournews', 'Discord server invite URL'),
('social_telegram', 'https://t.me/yournews', 'Telegram channel URL'),
('news_per_page', '10', 'Number of news items per page'),
('site_email', 'contact@englishnews.com', 'Contact email address'),
('enable_comments', '0', 'Enable/disable comments (1=enabled, 0=disabled)'),
('maintenance_mode', '0', 'Maintenance mode (1=enabled, 0=disabled)');

-- =============================================
-- Table: statistics
-- Purpose: Store dynamic statistics for homepage
-- =============================================
CREATE TABLE IF NOT EXISTS statistics (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    label_name VARCHAR(100) NOT NULL,
    number_value VARCHAR(50) NOT NULL,
    icon VARCHAR(50) DEFAULT 'chart-line',
    display_order INT UNSIGNED DEFAULT 0,
    is_visible TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_visible_order (is_visible, display_order)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert sample statistics
INSERT INTO statistics (label_name, number_value, icon, display_order, is_visible) VALUES 
('Total Articles', '1,250+', 'newspaper', 1, 1),
('Daily Readers', '50K+', 'users', 2, 1),
('Countries Reached', '85+', 'globe', 3, 1),
('Years Active', '5+', 'calendar', 4, 1),
('Expert Writers', '20+', 'pen', 5, 0);

-- =============================================
-- Table: media
-- Purpose: Store photos and videos for gallery
-- =============================================
CREATE TABLE IF NOT EXISTS media (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    type ENUM('photo', 'video') NOT NULL DEFAULT 'photo',
    file_path VARCHAR(255) NOT NULL,
    thumbnail_path VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_type (type),
    INDEX idx_created_at (created_at DESC)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- Table: page_views (Optional - for analytics)
-- Purpose: Track page views and visitor analytics
-- =============================================
CREATE TABLE IF NOT EXISTS page_views (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    news_id INT UNSIGNED DEFAULT NULL,
    ip_address VARCHAR(45) DEFAULT NULL,
    user_agent TEXT DEFAULT NULL,
    viewed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_news_id (news_id),
    INDEX idx_viewed_at (viewed_at),
    FOREIGN KEY (news_id) REFERENCES news(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- Views for easier data retrieval
-- =============================================

-- View: Published news with view counts
CREATE OR REPLACE VIEW published_news AS
SELECT 
    id,
    title,
    slug,
    content,
    image_path,
    views,
    created_at,
    updated_at
FROM news
WHERE status = 'published'
ORDER BY created_at DESC;

-- View: Visible statistics
CREATE OR REPLACE VIEW visible_statistics AS
SELECT 
    id,
    label_name,
    number_value,
    icon,
    display_order
FROM statistics
WHERE is_visible = 1
ORDER BY display_order ASC;

-- =============================================
-- Stored Procedures (Optional - for advanced operations)
-- =============================================

DELIMITER //

-- Procedure: Increment news view count
CREATE PROCEDURE IF NOT EXISTS increment_news_views(IN news_id_param INT)
BEGIN
    UPDATE news 
    SET views = views + 1 
    WHERE id = news_id_param;
END //

-- Procedure: Get site setting by key
CREATE PROCEDURE IF NOT EXISTS get_setting(IN key_param VARCHAR(100))
BEGIN
    SELECT setting_value 
    FROM site_settings 
    WHERE setting_key = key_param;
END //

DELIMITER ;

-- =============================================
-- Create database user (Optional - for security)
-- Run this on production with a strong password
-- =============================================
-- CREATE USER IF NOT EXISTS 'news_cms_user'@'localhost' IDENTIFIED BY 'STRONG_PASSWORD_HERE';
-- GRANT SELECT, INSERT, UPDATE, DELETE ON english_news_cms.* TO 'news_cms_user'@'localhost';
-- FLUSH PRIVILEGES;

-- =============================================
-- End of Schema
-- =============================================
