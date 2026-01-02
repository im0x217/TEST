<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo sanitize($settings['site_name'] ?? 'English News Hub'); ?> - <?php echo sanitize($settings['site_tagline'] ?? 'Breaking News'); ?></title>
    <meta name="description" content="<?php echo sanitize($settings['site_tagline'] ?? 'Your daily source for breaking news'); ?>">
    
    <!-- TailwindCSS via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    
    <!-- Custom Tailwind Configuration -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#3b82f6',
                        secondary: '#8b5cf6',
                        accent: '#06b6d4'
                    },
                    fontFamily: {
                        sans: ['Inter', 'system-ui', 'sans-serif']
                    }
                }
            }
        }
    </script>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        body { font-family: 'Inter', sans-serif; }
        .gradient-bg { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .hover-lift { transition: transform 0.3s ease, box-shadow 0.3s ease; }
        .hover-lift:hover { transform: translateY(-5px); box-shadow: 0 20px 40px rgba(0,0,0,0.15); }
        .stats-float { animation: float 3s ease-in-out infinite; }
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
        /* Prevent text selection on UI elements */
        header, nav, button, .hover-lift, a, .stats-float, h1, h2, h3, .gradient-bg { 
            user-select: none; 
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
        }
        /* Fix cursor types */
        a, button { cursor: pointer; }
        header, nav, h1, h2, h3, .gradient-bg, .hover-lift, .stats-float { cursor: default; }
    </style>
</head>
<body class="bg-gray-50">

    <!-- Header -->
    <header class="gradient-bg text-white shadow-xl sticky top-0 z-50">
        <div class="container mx-auto px-4 py-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold tracking-tight">
                        <a href="/EnglishNewsApp/" class="hover:opacity-90 transition">
                            <?php echo sanitize($settings['site_name'] ?? 'English News Hub'); ?>
                        </a>
                    </h1>
                    <p class="text-blue-100 text-sm mt-1">
                        <?php echo sanitize($settings['site_tagline'] ?? 'Your Daily Source for Breaking News'); ?>
                    </p>
                </div>
                
                <!-- Statistics Badge (Top Right) -->
                <div class="hidden lg:block stats-float">
                    <div class="bg-white/10 backdrop-blur-lg rounded-2xl p-4 border border-white/20">
                        <div class="flex items-center gap-6">
                            <?php foreach (array_slice($statistics, 0, 3) as $stat): ?>
                                <div class="text-center">
                                    <div class="text-2xl font-bold text-white">
                                        <?php echo sanitize($stat['number_value']); ?>
                                    </div>
                                    <div class="text-xs text-blue-100 mt-1">
                                        <?php echo sanitize($stat['label_name']); ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="container mx-auto px-4 py-12">
        
        <!-- Social Media Section (Middle of Page) -->
        <div class="text-center mb-16">
            <h2 class="text-3xl font-bold text-gray-800 mb-4">Stay Connected</h2>
            <p class="text-gray-600 mb-8">Follow us on social media for instant updates</p>
            
            <div class="flex justify-center items-center gap-6">
                <?php if (!empty($settings['social_x'])): ?>
                    <a href="<?php echo sanitize($settings['social_x']); ?>" 
                       target="_blank" 
                       class="bg-black hover:bg-gray-800 text-white w-16 h-16 rounded-full flex items-center justify-center text-2xl transition hover-lift">
                        <i class="fab fa-x-twitter"></i>
                    </a>
                <?php endif; ?>
                
                <?php if (!empty($settings['social_discord'])): ?>
                    <a href="<?php echo sanitize($settings['social_discord']); ?>" 
                       target="_blank" 
                       class="bg-indigo-600 hover:bg-indigo-700 text-white w-16 h-16 rounded-full flex items-center justify-center text-2xl transition hover-lift">
                        <i class="fab fa-discord"></i>
                    </a>
                <?php endif; ?>
                
                <?php if (!empty($settings['social_telegram'])): ?>
                    <a href="<?php echo sanitize($settings['social_telegram']); ?>" 
                       target="_blank" 
                       class="bg-blue-500 hover:bg-blue-600 text-white w-16 h-16 rounded-full flex items-center justify-center text-2xl transition hover-lift">
                        <i class="fab fa-telegram"></i>
                    </a>
                <?php endif; ?>
            </div>
        </div>

        <!-- Statistics Section (Mobile) -->
        <div class="lg:hidden mb-12">
            <div class="bg-gradient-to-r from-blue-500 to-purple-600 rounded-2xl p-6 shadow-xl">
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                    <?php foreach ($statistics as $stat): ?>
                        <div class="text-center text-white">
                            <div class="text-2xl font-bold"><?php echo sanitize($stat['number_value']); ?></div>
                            <div class="text-xs mt-1 text-blue-100"><?php echo sanitize($stat['label_name']); ?></div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- News Section -->
        <div class="mb-12">
            <div class="flex items-center justify-between mb-8">
                <h2 class="text-4xl font-bold text-gray-800">Latest News</h2>
                <div class="text-sm text-gray-500">
                    <i class="fas fa-newspaper mr-2"></i>
                    <?php echo count($news); ?> Articles
                </div>
            </div>

            <?php if (empty($news)): ?>
                <div class="text-center py-20">
                    <i class="fas fa-inbox text-6xl text-gray-300 mb-4"></i>
                    <p class="text-xl text-gray-500">No news articles available yet.</p>
                </div>
            <?php else: ?>
                <!-- 3-Column News Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <?php foreach ($news as $article): ?>
                        <article class="bg-white rounded-2xl overflow-hidden shadow-lg hover-lift">
                            <!-- Image -->
                            <a href="/EnglishNewsApp/news-detail.php?slug=<?php echo urlencode($article['slug']); ?>" class="block">
                                <?php if (!empty($article['image_path'])): ?>
                                    <img src="/EnglishNewsApp/assets/<?php echo sanitize($article['image_path']); ?>" 
                                         alt="<?php echo sanitize($article['title']); ?>"
                                         class="w-full h-48 object-cover">
                                <?php else: ?>
                                    <div class="w-full h-48 bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center">
                                        <i class="fas fa-newspaper text-white text-5xl opacity-50"></i>
                                    </div>
                                <?php endif; ?>
                            </a>
                            
                            <!-- Content -->
                            <div class="p-6">
                                <div class="flex items-center gap-4 text-sm text-gray-500 mb-3">
                                    <span class="flex items-center">
                                        <i class="far fa-calendar mr-1"></i>
                                        <?php echo formatDate($article['created_at'], 'M j, Y'); ?>
                                    </span>
                                    <span class="flex items-center">
                                        <i class="far fa-eye mr-1"></i>
                                        <?php echo number_format($article['views']); ?>
                                    </span>
                                </div>
                                
                                <h3 class="text-xl font-bold text-gray-800 mb-3 leading-tight hover:text-blue-600 transition">
                                    <a href="/EnglishNewsApp/news-detail.php?slug=<?php echo urlencode($article['slug']); ?>">
                                        <?php echo sanitize($article['title']); ?>
                                    </a>
                                </h3>
                                
                                <p class="text-gray-600 mb-4 leading-relaxed">
                                    <?php echo truncateText($article['content'], 120); ?>
                                </p>
                                
                                <a href="/EnglishNewsApp/news-detail.php?slug=<?php echo urlencode($article['slug']); ?>" 
                                   class="inline-flex items-center text-blue-600 hover:text-blue-700 font-semibold transition">
                                    Read More
                                    <i class="fas fa-arrow-right ml-2"></i>
                                </a>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- All Statistics (Desktop - Bottom) -->
        <div class="hidden lg:block mt-16">
            <div class="bg-gradient-to-r from-blue-600 via-purple-600 to-pink-600 rounded-3xl p-8 shadow-2xl">
                <h3 class="text-2xl font-bold text-white text-center mb-8">Our Impact</h3>
                <div class="grid grid-cols-2 md:grid-cols-5 gap-8">
                    <?php foreach ($statistics as $stat): ?>
                        <div class="text-center text-white">
                            <i class="fas fa-<?php echo sanitize($stat['icon'] ?? 'chart-line'); ?> text-4xl mb-3 opacity-80"></i>
                            <div class="text-3xl font-bold"><?php echo sanitize($stat['number_value']); ?></div>
                            <div class="text-sm mt-2 text-blue-100"><?php echo sanitize($stat['label_name']); ?></div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white mt-20">
        <div class="container mx-auto px-4 py-12">
            <div class="text-center">
                <h3 class="text-2xl font-bold mb-4"><?php echo sanitize($settings['site_name'] ?? 'English News Hub'); ?></h3>
                <p class="text-gray-400 mb-6"><?php echo sanitize($settings['site_tagline'] ?? 'Your Daily Source for Breaking News'); ?></p>
                
                <div class="flex justify-center gap-4 mb-8">
                    <?php if (!empty($settings['social_x'])): ?>
                        <a href="<?php echo sanitize($settings['social_x']); ?>" target="_blank" class="hover:text-blue-400 transition">
                            <i class="fab fa-x-twitter text-xl"></i>
                        </a>
                    <?php endif; ?>
                    <?php if (!empty($settings['social_discord'])): ?>
                        <a href="<?php echo sanitize($settings['social_discord']); ?>" target="_blank" class="hover:text-blue-400 transition">
                            <i class="fab fa-discord text-xl"></i>
                        </a>
                    <?php endif; ?>
                    <?php if (!empty($settings['social_telegram'])): ?>
                        <a href="<?php echo sanitize($settings['social_telegram']); ?>" target="_blank" class="hover:text-blue-400 transition">
                            <i class="fab fa-telegram text-xl"></i>
                        </a>
                    <?php endif; ?>
                </div>
                
                <p class="text-gray-500 text-sm">
                    &copy; <?php echo date('Y'); ?> <?php echo sanitize($settings['site_name'] ?? 'English News Hub'); ?>. All rights reserved.
                </p>
            </div>
        </div>
    </footer>

</body>
</html>
