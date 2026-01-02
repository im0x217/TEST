<?php
/**
 * Main Frontend Entry Point
 * Routes to active template based on database setting
 * 
 * @version 1.0
 * @date 2026
 */

require_once 'config/database.php';
require_once 'includes/functions.php';

// Get active template from database
$activeTemplate = getActiveTemplate();

// Fetch published news
$db = getDB();
try {
    $stmt = $db->prepare("
        SELECT id, title, slug, content, image_path, views, created_at 
        FROM news 
        WHERE status = 'published' 
        ORDER BY created_at DESC 
        LIMIT 12
    ");
    $stmt->execute();
    $newsArticles = $stmt->fetchAll();
} catch (PDOException $e) {
    error_log("Error fetching news: " . $e->getMessage());
    $newsArticles = [];
}

// Fetch visible statistics
try {
    $stmt = $db->prepare("
        SELECT label_name, number_value, icon, display_order 
        FROM statistics 
        WHERE is_visible = 1 
        ORDER BY display_order ASC
    ");
    $stmt->execute();
    $statistics = $stmt->fetchAll();
} catch (PDOException $e) {
    error_log("Error fetching statistics: " . $e->getMessage());
    $statistics = [];
}

// Fetch media (photos and videos)
$photos = [];
$videos = [];
try {
    $stmt = $db->prepare("SELECT id, title, type, file_path, thumbnail_path, created_at FROM media WHERE type = 'photo' ORDER BY created_at DESC LIMIT 6");
    $stmt->execute();
    $photos = $stmt->fetchAll();

    $stmt = $db->prepare("SELECT id, title, type, file_path, thumbnail_path, created_at FROM media WHERE type = 'video' ORDER BY created_at DESC LIMIT 6");
    $stmt->execute();
    $videos = $stmt->fetchAll();
} catch (PDOException $e) {
    error_log("Error fetching media: " . $e->getMessage());
}

// Fetch all site settings
$settings = getAllSettings();

// Prepare data for template
$templateData = [
    'news' => $newsArticles,
    'statistics' => $statistics,
    'settings' => $settings,
    'photos' => $photos,
    'videos' => $videos,
    'currentPage' => 'home'
];

// Render active template
renderTemplate($activeTemplate, 'index.php', $templateData);
