<?php
/**
 * News Detail Page Entry Point
 * Routes to active template based on database setting
 * 
 * @version 1.0
 * @date 2026
 */

require_once 'config/database.php';
require_once 'includes/functions.php';

// Get slug from URL
$slug = $_GET['slug'] ?? null;

if (!$slug) {
    redirect('/EnglishNewsApp/');
}

// Get active template from database
$activeTemplate = getActiveTemplate();

// Fetch news article by slug
$db = getDB();
try {
    $stmt = $db->prepare("
        SELECT id, title, slug, content, image_path, views, created_at, updated_at 
        FROM news 
        WHERE slug = ? AND status = 'published'
    ");
    $stmt->execute([$slug]);
    $article = $stmt->fetch();
    
    if (!$article) {
        redirect('/EnglishNewsApp/');
    }
    
    // Increment view counter
    $updateStmt = $db->prepare("UPDATE news SET views = views + 1 WHERE id = ?");
    $updateStmt->execute([$article['id']]);
    
} catch (PDOException $e) {
    error_log("Error fetching article: " . $e->getMessage());
    redirect('/EnglishNewsApp/');
}

// Fetch related news (latest 3 excluding current)
try {
    $stmt = $db->prepare("
        SELECT id, title, slug, image_path, created_at 
        FROM news 
        WHERE status = 'published' AND id != ? 
        ORDER BY created_at DESC 
        LIMIT 3
    ");
    $stmt->execute([$article['id']]);
    $relatedNews = $stmt->fetchAll();
} catch (PDOException $e) {
    error_log("Error fetching related news: " . $e->getMessage());
    $relatedNews = [];
}

// Fetch all site settings
$settings = getAllSettings();

// Prepare data for template
$templateData = [
    'article' => $article,
    'relatedNews' => $relatedNews,
    'settings' => $settings,
    'currentPage' => 'detail'
];

// Render active template
renderTemplate($activeTemplate, 'news-detail.php', $templateData);
