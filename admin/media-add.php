<?php
/**
 * Add Media (Photo/Video)
 */

define('ADMIN_PAGE', true);

require_once '../config/database.php';
require_once '../includes/functions.php';

requireLogin('/EnglishNewsApp/admin/login.php');

$pageTitle = 'Add Media';
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $type = $_POST['type'] ?? 'photo';
    
    if (empty($title)) {
        $error = 'Please enter a title';
    } elseif (!isset($_FILES['media_file'])) {
        $error = 'Please select a file';
    } else {
        // Handle file upload into assets/media (supports photo/video)
        $uploadResult = handleMediaUpload($_FILES['media_file'], $type, '../assets/media/');
        
        if ($uploadResult['success']) {
            $db = getDB();
            try {
                $stmt = $db->prepare("
                    INSERT INTO media (title, type, file_path, created_at) 
                    VALUES (?, ?, ?, NOW())
                ");
                $stmt->execute([$title, $type, $uploadResult['path']]);
                
                setFlashMessage('success', 'Media added successfully');
                redirect('/EnglishNewsApp/admin/media-manage.php');
            } catch (PDOException $e) {
                error_log('Insert media error: ' . $e->getMessage());
                $error = 'Failed to save media';
                if (!empty($uploadResult['path'])) {
                    deleteFile($uploadResult['path']);
                }
            }
        } else {
            $error = $uploadResult['message'] ?? 'Failed to upload file';
        }
    }
}

include 'includes/header.php';
?>

<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <!-- Back Button -->
        <a href="/EnglishNewsApp/admin/media-manage.php" class="text-blue-600 hover:text-blue-700 font-semibold mb-6 inline-block">
            <i class="fas fa-arrow-left mr-2"></i>Back to Media
        </a>
        
        <!-- Form Card -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-6">
                <i class="fas fa-plus-circle text-blue-500 mr-3"></i>Add New Media
            </h1>
            
            <?php if (!empty($error)): ?>
                <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded">
                    <i class="fas fa-exclamation-circle mr-2"></i><?php echo $error; ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" enctype="multipart/form-data">
                <div class="space-y-6">
                    <!-- Title -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Title *</label>
                        <input type="text" name="title" value="<?php echo sanitize($_POST['title'] ?? ''); ?>" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               required>
                    </div>
                    
                    <!-- Type -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Type *</label>
                        <select name="type" id="mediaType" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            <option value="photo" <?php echo ($_POST['type'] ?? 'photo') === 'photo' ? 'selected' : ''; ?>>Photo</option>
                            <option value="video" <?php echo ($_POST['type'] ?? '') === 'video' ? 'selected' : ''; ?>>Video</option>
                        </select>
                    </div>
                    
                    <!-- File Upload -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">File *</label>
                        <input type="file" name="media_file" id="mediaFile" accept="image/*,video/*"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                               required>
                        <p class="text-sm text-gray-500 mt-2">Max 50MB. Photos: JPG, PNG, GIF, WebP. Videos: MP4, WebM, OGG</p>
                    </div>
                </div>
                
                <!-- Actions -->
                <div class="flex gap-3 mt-8">
                    <button type="submit" class="flex-1 bg-gradient-to-r from-blue-500 to-purple-600 hover:from-blue-600 hover:to-purple-700 text-white px-6 py-3 rounded-lg transition font-semibold">
                        <i class="fas fa-upload mr-2"></i>Upload Media
                    </button>
                    <a href="/EnglishNewsApp/admin/media-manage.php" class="px-5 py-3 text-gray-600 hover:text-gray-800 font-semibold">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
