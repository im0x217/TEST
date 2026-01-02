<?php
/**
 * Delete Media
 */

require_once '../config/database.php';
require_once '../includes/functions.php';

requireLogin('/EnglishNewsApp/admin/login.php');

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) {
    redirect('/EnglishNewsApp/admin/media-manage.php');
}

$db = getDB();

// Fetch media to get file path
$stmt = $db->prepare("SELECT file_path FROM media WHERE id = ?");
$stmt->execute([$id]);
$media = $stmt->fetch();

if ($media) {
    try {
        $deleteStmt = $db->prepare("DELETE FROM media WHERE id = ?");
        $deleteStmt->execute([$id]);
        
        // Delete file if exists
        if (!empty($media['file_path'])) {
            deleteFile($media['file_path']);
        }
        
        setFlashMessage('success', 'Media deleted successfully');
    } catch (PDOException $e) {
        error_log('Delete media error: ' . $e->getMessage());
        setFlashMessage('error', 'Failed to delete media');
    }
}

redirect('/EnglishNewsApp/admin/media-manage.php');
