<?php
/**
 * Add News Article
 */

define('ADMIN_PAGE', true);

require_once '../config/database.php';
require_once '../includes/functions.php';

requireLogin('/EnglishNewsApp/admin/login.php');

$pageTitle = 'Add News Article';

$db = getDB();
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $slugInput = trim($_POST['slug'] ?? '');
    $content = $_POST['content'] ?? '';
    $status = $_POST['status'] ?? 'published';

    if (!$title || !$content) {
        $error = 'Title and content are required.';
    } else {
        $slug = $slugInput ? generateSlug($slugInput) : generateSlug($title);

        // Handle image upload if exists
        $imagePath = null;
        if (!empty($_FILES['image']['name'])) {
            $upload = handleImageUpload($_FILES['image'], '../assets/uploads/');
            if ($upload['success']) {
                $imagePath = $upload['path'];
            } else {
                $error = $upload['message'];
            }
        }

        if (!$error) {
            try {
                $stmt = $db->prepare("INSERT INTO news (title, slug, content, image_path, status) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([$title, $slug, $content, $imagePath, $status]);
                setFlashMessage('success', 'Article created successfully');
                redirect('/EnglishNewsApp/admin/news-manage.php');
            } catch (PDOException $e) {
                error_log('Add news error: ' . $e->getMessage());
                $error = 'Failed to create article. Ensure slug is unique.';
            }
        }
    }
}

include 'includes/header.php';
?>

<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Add News Article</h1>
            <p class="text-gray-600">Create a new article for the site</p>
        </div>
        <a href="/EnglishNewsApp/admin/news-manage.php" class="text-blue-600 hover:text-blue-700 font-semibold">
            <i class="fas fa-arrow-left mr-2"></i>Back to list
        </a>
    </div>

    <?php if ($error): ?>
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded" role="alert">
            <?php echo htmlspecialchars($error); ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="" enctype="multipart/form-data" class="bg-white rounded-xl shadow-lg p-8 space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Title</label>
                <input type="text" name="title" required value="<?php echo sanitize($_POST['title'] ?? ''); ?>" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Custom Slug (optional)</label>
                <input type="text" name="slug" value="<?php echo sanitize($_POST['slug'] ?? ''); ?>" placeholder="auto-generated if empty" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Content (HTML allowed)</label>
            <textarea name="content" rows="10" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"><?php echo htmlspecialchars($_POST['content'] ?? ''); ?></textarea>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Featured Image</label>
                <input type="file" name="image" accept="image/*" class="w-full">
                <p class="text-xs text-gray-500 mt-1">JPG, PNG, GIF, WebP. Max 5MB.</p>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Status</label>
                <select name="status" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="published" <?php echo (($_POST['status'] ?? '') === 'draft') ? '' : 'selected'; ?>>Published</option>
                    <option value="draft" <?php echo (($_POST['status'] ?? '') === 'draft') ? 'selected' : ''; ?>>Draft</option>
                </select>
            </div>
        </div>

        <div class="flex items-center justify-end gap-4">
            <a href="/EnglishNewsApp/admin/news-manage.php" class="px-5 py-3 text-gray-600 hover:text-gray-800 font-semibold">Cancel</a>
            <button type="submit" class="bg-gradient-to-r from-blue-500 to-purple-600 hover:from-blue-600 hover:to-purple-700 text-white px-8 py-3 rounded-lg font-semibold shadow-lg transition">
                <i class="fas fa-save mr-2"></i>Create Article
            </button>
        </div>
    </form>
</div>

<?php include 'includes/footer.php'; ?>
