<?php
/**
 * News Management - Listing Page
 * View and manage all news articles
 * 
 * @version 1.0
 * @date 2026
 */

define('ADMIN_PAGE', true);

require_once '../config/database.php';
require_once '../includes/functions.php';

requireLogin('/EnglishNewsApp/admin/login.php');

$pageTitle = 'Manage News';

// Fetch all news
$db = getDB();
try {
    $stmt = $db->query("SELECT * FROM news ORDER BY created_at DESC");
    $allNews = $stmt->fetchAll();
} catch (PDOException $e) {
    error_log("Error fetching news: " . $e->getMessage());
    $allNews = [];
}

include 'includes/header.php';
?>

<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-800 mb-2">News Articles</h1>
            <p class="text-gray-600">Manage all your news content</p>
        </div>
        <a href="/EnglishNewsApp/admin/news-add.php" class="bg-gradient-to-r from-blue-500 to-purple-600 hover:from-blue-600 hover:to-purple-700 text-white px-6 py-3 rounded-lg transition font-semibold shadow-lg">
            <i class="fas fa-plus mr-2"></i>
            Add New Article
        </a>
    </div>

    <!-- News Table -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <?php if (empty($allNews)): ?>
            <div class="text-center py-16">
                <i class="fas fa-newspaper text-6xl text-gray-300 mb-4"></i>
                <p class="text-xl text-gray-500 mb-4">No articles yet</p>
                <a href="/EnglishNewsApp/admin/news-add.php" class="inline-flex items-center bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-lg transition font-semibold">
                    <i class="fas fa-plus mr-2"></i>
                    Create Your First Article
                </a>
            </div>
        <?php else: ?>
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Title</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Views</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php foreach ($allNews as $news): ?>
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <?php if (!empty($news['image_path'])): ?>
                                        <img src="/EnglishNewsApp/assets/<?php echo sanitize($news['image_path']); ?>" 
                                             alt="<?php echo sanitize($news['title']); ?>"
                                             class="w-12 h-12 rounded object-cover">
                                    <?php else: ?>
                                        <div class="w-12 h-12 bg-gradient-to-br from-gray-300 to-gray-400 rounded flex items-center justify-center">
                                            <i class="fas fa-newspaper text-white"></i>
                                        </div>
                                    <?php endif; ?>
                                    <div>
                                        <p class="font-semibold text-gray-800"><?php echo sanitize($news['title']); ?></p>
                                        <p class="text-sm text-gray-500"><?php echo sanitize($news['slug']); ?></p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 rounded-full text-xs font-semibold <?php echo $news['status'] === 'published' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700'; ?>">
                                    <?php echo ucfirst($news['status']); ?>
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-gray-800 font-semibold">
                                    <i class="far fa-eye text-gray-400 mr-1"></i>
                                    <?php echo number_format($news['views']); ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                <?php echo formatDate($news['created_at'], 'M j, Y'); ?>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="/news-detail.php?slug=<?php echo urlencode($news['slug']); ?>" 
                                       target="_blank"
                                       class="text-gray-500 hover:text-blue-500 transition"
                                       title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="/EnglishNewsApp/admin/news-edit.php?id=<?php echo $news['id']; ?>" 
                                       class="text-blue-500 hover:text-blue-600 transition"
                                       title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="/EnglishNewsApp/admin/news-delete.php?id=<?php echo $news['id']; ?>" 
                                       onclick="return confirm('Are you sure you want to delete this article?');"
                                       class="text-red-500 hover:text-red-600 transition"
                                       title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
