<?php
/**
 * Admin Panel Header
 * Included on all admin pages for consistent navigation
 */

if (!defined('ADMIN_PAGE')) {
    die('Direct access not permitted');
}

$currentPage = basename($_SERVER['PHP_SELF'], '.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle ?? 'Admin Panel'; ?> - CMS</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        body { font-family: 'Inter', sans-serif; }
        .sidebar-link.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
    </style>
</head>
<body class="bg-gray-50">

    <!-- Top Navigation -->
    <nav class="bg-white shadow-lg sticky top-0 z-50">
        <div class="px-6 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-purple-600 to-blue-600 rounded-lg flex items-center justify-center">
                            <i class="fas fa-shield-halved text-white"></i>
                        </div>
                        <div>
                            <h1 class="text-xl font-bold text-gray-800">Admin Panel</h1>
                            <p class="text-xs text-gray-500">Content Management System</p>
                        </div>
                    </div>
                </div>
                
                <div class="flex items-center gap-4">
                    <a href="/" target="_blank" class="text-gray-600 hover:text-purple-600 transition">
                        <i class="fas fa-external-link-alt mr-2"></i>
                        View Site
                    </a>
                    <div class="h-6 w-px bg-gray-300"></div>
                    <div class="flex items-center gap-3">
                        <div class="text-right">
                            <p class="text-sm font-semibold text-gray-800">
                                <?php echo sanitize($_SESSION['admin_username'] ?? 'Admin'); ?>
                            </p>
                            <p class="text-xs text-gray-500">Administrator</p>
                        </div>
                        <a href="/EnglishNewsApp/admin/logout.php" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg transition text-sm font-semibold">
                            <i class="fas fa-sign-out-alt mr-1"></i>
                            Logout
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <div class="flex">
        <!-- Sidebar -->
        <aside class="w-64 bg-white shadow-lg min-h-screen">
            <nav class="p-4 space-y-2">
                <a href="/EnglishNewsApp/admin/" class="sidebar-link <?php echo $currentPage === 'index' ? 'active' : ''; ?> flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-gray-100 transition">
                    <i class="fas fa-chart-line w-5"></i>
                    <span class="font-semibold">Dashboard</span>
                </a>
                
                <a href="/EnglishNewsApp/admin/news-manage.php" class="sidebar-link <?php echo strpos($currentPage, 'news') !== false ? 'active' : ''; ?> flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-gray-100 transition">
                    <i class="fas fa-newspaper w-5"></i>
                    <span class="font-semibold">News Articles</span>
                </a>
                
                <a href="/EnglishNewsApp/admin/statistics.php" class="sidebar-link <?php echo $currentPage === 'statistics' ? 'active' : ''; ?> flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-gray-100 transition">
                    <i class="fas fa-chart-bar w-5"></i>
                    <span class="font-semibold">Statistics</span>
                </a>
                
                <a href="/EnglishNewsApp/admin/media-manage.php" class="sidebar-link <?php echo strpos($currentPage, 'media') !== false ? 'active' : ''; ?> flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-gray-100 transition">
                    <i class="fas fa-images w-5"></i>
                    <span class="font-semibold">Media Gallery</span>
                </a>
                
                <a href="/EnglishNewsApp/admin/settings.php" class="sidebar-link <?php echo $currentPage === 'settings' ? 'active' : ''; ?> flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-gray-100 transition">
                    <i class="fas fa-cog w-5"></i>
                    <span class="font-semibold">Settings</span>
                </a>
                
                <div class="pt-4 mt-4 border-t border-gray-200">
                    <a href="/" target="_blank" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-gray-100 transition text-gray-600">
                        <i class="fas fa-globe w-5"></i>
                        <span class="font-semibold">Visit Website</span>
                    </a>
                </div>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-8">
            <?php echo displayFlashMessage(); ?>
