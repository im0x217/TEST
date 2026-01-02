<?php
/**
 * Delete News Article
 */

require_once '../config/database.php';
require_once '../includes/functions.php';

requireLogin('/EnglishNewsApp/admin/login.php');

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) {
    redirect('/EnglishNewsApp/admin/news-manage.php');
}

$db = getDB();

// Fetch article to get image path
$stmt = $db->prepare("SELECT image_path FROM news WHERE id = ?");
$stmt->execute([$id]);
$article = $stmt->fetch();

if ($article) {
    try {
        $deleteStmt = $db->prepare("DELETE FROM news WHERE id = ?");
        $deleteStmt->execute([$id]);
        
        // Delete image file if exists
        if (!empty($article['image_path'])) {
            deleteFile($article['image_path']);
        }
        
        setFlashMessage('success', 'Article deleted');
    } catch (PDOException $e) {
        error_log('Delete news error: ' . $e->getMessage());
        setFlashMessage('error', 'Failed to delete article');
    }
}

redirect('/EnglishNewsApp/admin/news-manage.php');
