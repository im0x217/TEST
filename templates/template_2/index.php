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
                        primary: '#f97316',
                        secondary: '#dc2626',
                        accent: '#fb923c'
                    },
                    fontFamily: {
                        sans: ['Georgia', 'serif']
                    }
                }
            }
        }
    </script>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Merriweather:wght@300;400;700;900&family=Open+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    
    <style>
        body { font-family: 'Open Sans', sans-serif; }
        h1, h2, h3 { font-family: 'Merriweather', serif; }
        .magazine-gradient { background: linear-gradient(135deg, #ff6b6b 0%, #ff8e53 100%); }
        .hover-scale { transition: transform 0.3s ease, box-shadow 0.3s ease; }
        .hover-scale:hover { transform: scale(1.02); box-shadow: 0 20px 40px rgba(0,0,0,0.2); }
        .sidebar-sticky { position: sticky; top: 20px; }
        .news-border { border-left: 4px solid #f97316; }
        /* Prevent text selection on UI elements */
        header, nav, button, a, h1, h2, h3, .magazine-gradient, .hover-scale { 
            user-select: none; 
            -webkit-user-select: none;
        }
        /* Fix cursor types */
        a, button { cursor: pointer; }
        header, nav, h1, h2, h3, .magazine-gradient, .sidebar-sticky { cursor: default; }
    </style>
</head>
<body class="bg-amber-50">

    <!-- Header -->
    <header class="magazine-gradient text-white shadow-2xl">
        <div class="container mx-auto px-4 py-8">
            <div class="text-center">
                <h1 class="text-5xl font-black tracking-tight mb-2">
                    <a href="/EnglishNewsApp/" class="hover:opacity-90 transition">
                        <?php echo sanitize($settings['site_name'] ?? 'English News Hub'); ?>
                    </a>
                </h1>
                <p class="text-orange-100 text-lg font-light italic">
                    <?php echo sanitize($settings['site_tagline'] ?? 'Your Daily Source for Breaking News'); ?>
                </p>
                <div class="mt-4 pt-4 border-t border-white/30 text-sm">
                    <?php echo date('l, F j, Y'); ?>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content with Sidebar Layout -->
    <main class="container mx-auto px-4 py-12">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            
            <!-- Sidebar (Left) - Statistics -->
            <aside class="lg:col-span-1">
                <div class="sidebar-sticky">
                    <!-- Statistics Box -->
                    <div class="bg-white rounded-2xl shadow-xl p-6 mb-8 border-t-4 border-orange-500">
                        <h3 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
                            <i class="fas fa-chart-line text-orange-500 mr-3"></i>
                            Our Stats
                        </h3>
                        
                        <div class="space-y-6">
                            <?php foreach ($statistics as $stat): ?>
                                <div class="border-b border-gray-100 pb-4 last:border-0 last:pb-0">
                                    <div class="flex items-center gap-3 mb-2">
                                        <i class="fas fa-<?php echo sanitize($stat['icon'] ?? 'chart-bar'); ?> text-2xl text-orange-500"></i>
                                        <div>
                                            <div class="text-3xl font-black text-gray-800">
                                                <?php echo sanitize($stat['number_value']); ?>
                                            </div>
                                            <div class="text-sm text-gray-600 font-semibold">
                                                <?php echo sanitize($stat['label_name']); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Social Media -->
                    <div class="bg-gradient-to-br from-orange-500 to-red-600 rounded-2xl shadow-xl p-6 text-white">
                        <h3 class="text-xl font-bold mb-4 flex items-center">
                            <i class="fas fa-share-nodes mr-2"></i>
                            Follow Us
                        </h3>
                        
                        <div class="space-y-3">
                            <?php if (!empty($settings['social_x'])): ?>
                                <a href="<?php echo sanitize($settings['social_x']); ?>" 
                                   target="_blank" 
                                   class="flex items-center gap-3 bg-white/20 hover:bg-white/30 backdrop-blur px-4 py-3 rounded-lg transition">
                                    <i class="fab fa-x-twitter text-xl"></i>
                                    <span class="font-semibold">Twitter/X</span>
                                </a>
                            <?php endif; ?>
                            
                            <?php if (!empty($settings['social_discord'])): ?>
                                <a href="<?php echo sanitize($settings['social_discord']); ?>" 
                                   target="_blank" 
                                   class="flex items-center gap-3 bg-white/20 hover:bg-white/30 backdrop-blur px-4 py-3 rounded-lg transition">
                                    <i class="fab fa-discord text-xl"></i>
                                    <span class="font-semibold">Discord</span>
                                </a>
                            <?php endif; ?>
                            
                            <?php if (!empty($settings['social_telegram'])): ?>
                                <a href="<?php echo sanitize($settings['social_telegram']); ?>" 
                                   target="_blank" 
                                   class="flex items-center gap-3 bg-white/20 hover:bg-white/30 backdrop-blur px-4 py-3 rounded-lg transition">
                                    <i class="fab fa-telegram text-xl"></i>
                                    <span class="font-semibold">Telegram</span>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </aside>

            <!-- Main Content Area (Right) -->
            <div class="lg:col-span-3">
                
                <!-- Social Media Section (Mobile/Tablet) -->
                <div class="lg:hidden text-center mb-12 bg-white rounded-2xl p-8 shadow-xl">
                    <h2 class="text-3xl font-bold text-gray-800 mb-4">Connect With Us</h2>
                    <p class="text-gray-600 mb-6">Stay updated with breaking news</p>
                    
                    <div class="flex justify-center items-center gap-4">
                        <?php if (!empty($settings['social_x'])): ?>
                            <a href="<?php echo sanitize($settings['social_x']); ?>" 
                               target="_blank" 
                               class="bg-black hover:bg-gray-800 text-white w-14 h-14 rounded-full flex items-center justify-center text-xl transition hover-scale">
                                <i class="fab fa-x-twitter"></i>
                            </a>
                        <?php endif; ?>
                        
                        <?php if (!empty($settings['social_discord'])): ?>
                            <a href="<?php echo sanitize($settings['social_discord']); ?>" 
                               target="_blank" 
                               class="bg-indigo-600 hover:bg-indigo-700 text-white w-14 h-14 rounded-full flex items-center justify-center text-xl transition hover-scale">
                                <i class="fab fa-discord"></i>
                            </a>
                        <?php endif; ?>
                        
                        <?php if (!empty($settings['social_telegram'])): ?>
                            <a href="<?php echo sanitize($settings['social_telegram']); ?>" 
                               target="_blank" 
                               class="bg-blue-500 hover:bg-blue-600 text-white w-14 h-14 rounded-full flex items-center justify-center text-xl transition hover-scale">
                                <i class="fab fa-telegram"></i>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- News Header -->
                <div class="mb-8">
                    <h2 class="text-4xl font-black text-gray-900 mb-2">Latest Headlines</h2>
                    <div class="h-1 w-24 bg-gradient-to-r from-orange-500 to-red-500 rounded-full"></div>
                </div>

                <?php if (empty($news)): ?>
                    <div class="text-center py-20 bg-white rounded-2xl shadow-xl">
                        <i class="fas fa-newspaper text-6xl text-gray-300 mb-4"></i>
                        <p class="text-xl text-gray-500">No news articles published yet.</p>
                    </div>
                <?php else: ?>
                    <!-- 2-Column Magazine-Style Grid -->
                    <div class="space-y-8">
                        <?php foreach ($news as $index => $article): ?>
                            <?php if ($index === 0): ?>
                                <!-- Featured Article (Full Width) -->
                                <article class="bg-white rounded-2xl overflow-hidden shadow-2xl hover-scale news-border">
                                    <div class="grid grid-cols-1 md:grid-cols-2">
                                        <a href="/EnglishNewsApp/news-detail.php?slug=<?php echo urlencode($article['slug']); ?>" class="block">
                                            <?php if (!empty($article['image_path'])): ?>
                                                <img src="/EnglishNewsApp/assets/<?php echo sanitize($article['image_path']); ?>" 
                                                     alt="<?php echo sanitize($article['title']); ?>"
                                                     class="w-full h-80 object-cover">
                                            <?php else: ?>
                                                <div class="w-full h-80 bg-gradient-to-br from-orange-500 to-red-600 flex items-center justify-center">
                                                    <i class="fas fa-newspaper text-white text-6xl opacity-50"></i>
                                                </div>
                                            <?php endif; ?>
                                        </a>
                                        
                                        <div class="p-8 flex flex-col justify-center">
                                            <div class="inline-block bg-orange-500 text-white text-xs font-bold px-3 py-1 rounded-full mb-4 w-fit">
                                                FEATURED
                                            </div>
                                            
                                            <h3 class="text-3xl font-black text-gray-900 mb-4 leading-tight hover:text-orange-600 transition">
                                                <a href="/EnglishNewsApp/news-detail.php?slug=<?php echo urlencode($article['slug']); ?>">
                                                    <?php echo sanitize($article['title']); ?>
                                                </a>
                                            </h3>
                                            
                                            <p class="text-gray-700 mb-6 leading-relaxed">
                                                <?php echo truncateText($article['content'], 180); ?>
                                            </p>
                                            
                                            <div class="flex items-center gap-6 text-sm text-gray-500 mb-6">
                                                <span><i class="far fa-calendar mr-1"></i><?php echo formatDate($article['created_at'], 'M j, Y'); ?></span>
                                                <span><i class="far fa-eye mr-1"></i><?php echo number_format($article['views']); ?></span>
                                            </div>
                                            
                                            <a href="/EnglishNewsApp/news-detail.php?slug=<?php echo urlencode($article['slug']); ?>" 
                                               class="inline-flex items-center bg-orange-500 hover:bg-orange-600 text-white font-bold px-6 py-3 rounded-full transition w-fit">
                                                Read Full Story
                                                <i class="fas fa-arrow-right ml-2"></i>
                                            </a>
                                        </div>
                                    </div>
                                </article>
                            <?php else: ?>
                                <!-- Regular Articles (2-Column Grid) -->
                                <?php if ($index === 1): ?>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                <?php endif; ?>
                                
                                <article class="bg-white rounded-2xl overflow-hidden shadow-xl hover-scale">
                                    <a href="/EnglishNewsApp/news-detail.php?slug=<?php echo urlencode($article['slug']); ?>" class="block">
                                        <?php if (!empty($article['image_path'])): ?>
                                            <img src="/EnglishNewsApp/assets/<?php echo sanitize($article['image_path']); ?>" 
                                                 alt="<?php echo sanitize($article['title']); ?>"
                                                 class="w-full h-56 object-cover">
                                        <?php else: ?>
                                            <div class="w-full h-56 bg-gradient-to-br from-orange-400 to-red-500 flex items-center justify-center">
                                                <i class="fas fa-newspaper text-white text-5xl opacity-50"></i>
                                            </div>
                                        <?php endif; ?>
                                    </a>
                                    
                                    <div class="p-6">
                                        <div class="flex items-center gap-4 text-sm text-gray-500 mb-3">
                                            <span><i class="far fa-calendar mr-1"></i><?php echo formatDate($article['created_at'], 'M j'); ?></span>
                                            <span><i class="far fa-eye mr-1"></i><?php echo number_format($article['views']); ?></span>
                                        </div>
                                        
                                        <h3 class="text-xl font-bold text-gray-900 mb-3 leading-tight hover:text-orange-600 transition">
                                            <a href="/EnglishNewsApp/news-detail.php?slug=<?php echo urlencode($article['slug']); ?>">
                                                <?php echo sanitize($article['title']); ?>
                                            </a>
                                        </h3>
                                        
                                        <p class="text-gray-600 mb-4 leading-relaxed text-sm">
                                            <?php echo truncateText($article['content'], 100); ?>
                                        </p>
                                        
                                        <a href="/EnglishNewsApp/news-detail.php?slug=<?php echo urlencode($article['slug']); ?>" 
                                           class="inline-flex items-center text-orange-600 hover:text-orange-700 font-bold transition text-sm">
                                            Continue Reading
                                            <i class="fas fa-chevron-right ml-2 text-xs"></i>
                                        </a>
                                    </div>
                                </article>
                                
                                <?php if ($index === count($news) - 1 && $index > 0): ?>
                                    </div>
                                <?php endif; ?>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="magazine-gradient text-white mt-20">
        <div class="container mx-auto px-4 py-12">
            <div class="text-center">
                <h3 class="text-3xl font-black mb-4"><?php echo sanitize($settings['site_name'] ?? 'English News Hub'); ?></h3>
                <p class="text-orange-100 mb-6 italic"><?php echo sanitize($settings['site_tagline'] ?? 'Your Daily Source for Breaking News'); ?></p>
                
                <div class="flex justify-center gap-6 mb-8">
                    <?php if (!empty($settings['social_x'])): ?>
                        <a href="<?php echo sanitize($settings['social_x']); ?>" target="_blank" class="hover:text-orange-200 transition text-2xl">
                            <i class="fab fa-x-twitter"></i>
                        </a>
                    <?php endif; ?>
                    <?php if (!empty($settings['social_discord'])): ?>
                        <a href="<?php echo sanitize($settings['social_discord']); ?>" target="_blank" class="hover:text-orange-200 transition text-2xl">
                            <i class="fab fa-discord"></i>
                        </a>
                    <?php endif; ?>
                    <?php if (!empty($settings['social_telegram'])): ?>
                        <a href="<?php echo sanitize($settings['social_telegram']); ?>" target="_blank" class="hover:text-orange-200 transition text-2xl">
                            <i class="fab fa-telegram"></i>
                        </a>
                    <?php endif; ?>
                </div>
                
                <div class="border-t border-white/30 pt-6">
                    <p class="text-orange-100 text-sm">
                        &copy; <?php echo date('Y'); ?> <?php echo sanitize($settings['site_name'] ?? 'English News Hub'); ?>. All rights reserved.
                    </p>
                </div>
            </div>
        </div>
    </footer>

</body>
</html>
