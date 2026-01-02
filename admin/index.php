<?php
/**
 * Admin Dashboard
 * Overview and quick stats
 * 
 * @version 1.0
 * @date 2026
 */

define('ADMIN_PAGE', true);

require_once '../config/database.php';
require_once '../includes/functions.php';

requireLogin('/EnglishNewsApp/admin/login.php');

$pageTitle = 'Dashboard';

// Fetch statistics
$db = getDB();

try {
    // Total news count
    $stmt = $db->query("SELECT COUNT(*) as total FROM news");
    $totalNews = $stmt->fetch()['total'];
    
    // Published news count
    $stmt = $db->query("SELECT COUNT(*) as total FROM news WHERE status = 'published'");
    $publishedNews = $stmt->fetch()['total'];
    
    // Draft news count
    $draftNews = $totalNews - $publishedNews;
    
    // Total views
    $stmt = $db->query("SELECT SUM(views) as total FROM news");
    $totalViews = $stmt->fetch()['total'] ?? 0;
    
    // Active template
    $activeTemplate = getActiveTemplate();
    
    // Recent news
    $stmt = $db->query("SELECT id, title, slug, status, views, created_at FROM news ORDER BY created_at DESC LIMIT 5");
    $recentNews = $stmt->fetchAll();
    
    // Statistics count
    $stmt = $db->query("SELECT COUNT(*) as total FROM statistics WHERE is_visible = 1");
    $visibleStats = $stmt->fetch()['total'];
    
} catch (PDOException $e) {
    error_log("Dashboard error: " . $e->getMessage());
}

include 'includes/header.php';
?>

<div class="space-y-8">
    <!-- Page Header -->
    <div>
        <h1 class="text-3xl font-bold text-gray-800 mb-2">Dashboard</h1>
        <p class="text-gray-600">Welcome back! Here's an overview of your CMS.</p>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total News -->
        <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-gray-600 uppercase">Total Articles</p>
                    <p class="text-3xl font-bold text-gray-800 mt-2"><?php echo number_format($totalNews); ?></p>
                </div>
                <div class="w-14 h-14 bg-blue-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-newspaper text-blue-500 text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Published News -->
        <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-gray-600 uppercase">Published</p>
                    <p class="text-3xl font-bold text-gray-800 mt-2"><?php echo number_format($publishedNews); ?></p>
                </div>
                <div class="w-14 h-14 bg-green-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-check-circle text-green-500 text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Draft News -->
        <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-yellow-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-gray-600 uppercase">Drafts</p>
                    <p class="text-3xl font-bold text-gray-800 mt-2"><?php echo number_format($draftNews); ?></p>
                </div>
                <div class="w-14 h-14 bg-yellow-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-file-alt text-yellow-500 text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Total Views -->
        <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-purple-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-gray-600 uppercase">Total Views</p>
                    <p class="text-3xl font-bold text-gray-800 mt-2"><?php echo number_format($totalViews); ?></p>
                </div>
                <div class="w-14 h-14 bg-purple-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-eye text-purple-500 text-2xl"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Recent News -->
        <div class="lg:col-span-2 bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-bold text-gray-800">Recent Articles</h2>
                <a href="/EnglishNewsApp/admin/news-add.php" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition text-sm font-semibold">
                    <i class="fas fa-plus mr-1"></i>
                    Add New
                </a>
            </div>

            <div class="space-y-4">
                <?php if (empty($recentNews)): ?>
                    <p class="text-gray-500 text-center py-8">No articles yet. Create your first one!</p>
                <?php else: ?>
                    <?php foreach ($recentNews as $news): ?>
                        <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg hover:shadow-md transition">
                            <div class="flex-1">
                                <h3 class="font-semibold text-gray-800 mb-1"><?php echo sanitize($news['title']); ?></h3>
                                <div class="flex items-center gap-4 text-sm text-gray-500">
                                    <span>
                                        <i class="far fa-calendar mr-1"></i>
                                        <?php echo formatDate($news['created_at'], 'M j, Y'); ?>
                                    </span>
                                    <span>
                                        <i class="far fa-eye mr-1"></i>
                                        <?php echo number_format($news['views']); ?>
                                    </span>
                                    <span class="px-2 py-1 rounded text-xs font-semibold <?php echo $news['status'] === 'published' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700'; ?>">
                                        <?php echo ucfirst($news['status']); ?>
                                    </span>
                                </div>
                            </div>
                            <a href="/EnglishNewsApp/admin/news-edit.php?id=<?php echo $news['id']; ?>" class="text-blue-500 hover:text-blue-600 font-semibold text-sm">
                                Edit <i class="fas fa-chevron-right ml-1"></i>
                            </a>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <div class="mt-6 text-center">
                <a href="/EnglishNewsApp/admin/news-manage.php" class="text-blue-500 hover:text-blue-600 font-semibold">View
                    View All Articles <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="space-y-6">
            <!-- Active Template -->
            <div class="bg-gradient-to-br from-purple-600 to-blue-600 rounded-xl shadow-lg p-6 text-white">
                <h3 class="text-lg font-bold mb-4">Active Template</h3>
                <div class="bg-white/20 backdrop-blur rounded-lg p-4 mb-4">
                    <div class="flex items-center gap-3">
                        <i class="fas fa-palette text-3xl"></i>
                        <div>
                            <p class="text-2xl font-bold">
                                <?php 
                                $templateNames = [
                                    'template_1' => 'Modern Blue',
                                    'template_2' => 'Magazine Orange',
                                    'template_3' => 'Tech Dark'
                                ];
                                echo $templateNames[$activeTemplate] ?? 'Template 1';
                                ?>
                            </p>
                            <p class="text-sm opacity-90"><?php echo $activeTemplate; ?></p>
                        </div>
                    </div>
                </div>
                <a href="/EnglishNewsApp/admin/settings.php" class="block text-center bg-white/20 hover:bg-white/30 backdrop-blur rounded-lg py-2 transition font-semibold">
                    Change Template
                </a>
            </div>

            <!-- Quick Links -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Quick Actions</h3>
                <div class="space-y-3">
                    <a href="/EnglishNewsApp/admin/news-add.php" class="flex items-center gap-3 p-3 bg-blue-50 hover:bg-blue-100 rounded-lg transition">
                        <i class="fas fa-plus-circle text-blue-500 text-xl"></i>
                        <span class="font-semibold text-gray-800">Add News Article</span>
                    </a>
                    <a href="/EnglishNewsApp/admin/statistics.php" class="flex items-center gap-3 p-3 bg-green-50 hover:bg-green-100 rounded-lg transition">
                        <i class="fas fa-chart-bar text-green-500 text-xl"></i>
                        <span class="font-semibold text-gray-800">Manage Statistics</span>
                    </a>
                    <a href="/EnglishNewsApp/admin/settings.php" class="flex items-center gap-3 p-3 bg-purple-50 hover:bg-purple-100 rounded-lg transition">
                        <i class="fas fa-cog text-purple-500 text-xl"></i>
                        <span class="font-semibold text-gray-800">Site Settings</span>
                    </a>
                    <a href="/" target="_blank" class="flex items-center gap-3 p-3 bg-gray-50 hover:bg-gray-100 rounded-lg transition">
                        <i class="fas fa-external-link-alt text-gray-500 text-xl"></i>
                        <span class="font-semibold text-gray-800">View Website</span>
                    </a>
                </div>
            </div>

            <!-- Statistics Summary -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Site Statistics</h3>
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Visible Stats</span>
                        <span class="font-bold text-gray-800"><?php echo $visibleStats; ?></span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Templates</span>
                        <span class="font-bold text-gray-800">3</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
