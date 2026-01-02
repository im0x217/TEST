<?php
/**
 * Media Management (Photos & Videos)
 */

define('ADMIN_PAGE', true);

require_once '../config/database.php';
require_once '../includes/functions.php';

requireLogin('/EnglishNewsApp/admin/login.php');

$pageTitle = 'Manage Media';

// Fetch all media
$db = getDB();
try {
    $stmt = $db->query("
        SELECT id, title, type, file_path, thumbnail_path, created_at 
        FROM media 
        ORDER BY created_at DESC
    ");
    $mediaItems = $stmt->fetchAll();
} catch (PDOException $e) {
    error_log('Fetch media error: ' . $e->getMessage());
    $mediaItems = [];
}

include 'includes/header.php';
?>

<div class="container mx-auto px-4 py-8">
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-800 mb-2">📸 Media Gallery</h1>
                <p class="text-gray-600">Manage photos and videos</p>
            </div>
            <a href="/EnglishNewsApp/admin/media-add.php" class="bg-gradient-to-r from-blue-500 to-purple-600 hover:from-blue-600 hover:to-purple-700 text-white px-6 py-3 rounded-lg transition font-semibold shadow-lg">
                <i class="fas fa-plus mr-2"></i>Add Media
            </a>
        </div>
    </div>

    <!-- Media Grid -->
    <?php if (empty($mediaItems)): ?>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
            <i class="fas fa-images text-gray-300 text-6xl mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-700 mb-2">No Media Yet</h3>
            <p class="text-gray-500 mb-6">Start building your gallery by adding photos and videos</p>
            <a href="/EnglishNewsApp/admin/media-add.php" class="inline-flex items-center bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-lg transition font-semibold">
                <i class="fas fa-plus mr-2"></i>Add First Media
            </a>
        </div>
    <?php else: ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            <?php foreach ($mediaItems as $media): ?>
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-lg transition group">
                    <!-- Media Preview -->
                    <div class="relative aspect-video bg-gray-100">
                        <?php if ($media['type'] === 'photo'): ?>
                            <img src="/EnglishNewsApp/assets/<?php echo sanitize($media['file_path']); ?>" 
                                 alt="<?php echo sanitize($media['title']); ?>"
                                 class="w-full h-full object-cover">
                        <?php else: ?>
                            <video class="w-full h-full object-cover" controls>
                                <source src="/EnglishNewsApp/assets/<?php echo sanitize($media['file_path']); ?>">
                            </video>
                        <?php endif; ?>
                        
                        <!-- Type Badge -->
                        <div class="absolute top-3 left-3">
                            <span class="px-3 py-1 rounded-full text-xs font-semibold <?php echo $media['type'] === 'photo' ? 'bg-blue-500' : 'bg-purple-500'; ?> text-white">
                                <i class="fas fa-<?php echo $media['type'] === 'photo' ? 'image' : 'video'; ?> mr-1"></i>
                                <?php echo ucfirst($media['type']); ?>
                            </span>
                        </div>
                    </div>
                    
                    <!-- Media Info -->
                    <div class="p-4">
                        <h3 class="font-semibold text-gray-800 mb-2 line-clamp-1"><?php echo sanitize($media['title']); ?></h3>
                        <p class="text-sm text-gray-500 mb-4">
                            <i class="far fa-clock mr-1"></i>
                            <?php echo formatDate($media['created_at']); ?>
                        </p>
                        
                        <!-- Actions -->
                        <div class="flex gap-2">
                            <a href="/EnglishNewsApp/admin/media-delete.php?id=<?php echo $media['id']; ?>" 
                               onclick="return confirm('Delete this media? This cannot be undone.');"
                               class="flex-1 text-center bg-red-50 hover:bg-red-100 text-red-600 px-3 py-2 rounded-lg transition font-semibold text-sm">
                                <i class="fas fa-trash mr-1"></i>Delete
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
