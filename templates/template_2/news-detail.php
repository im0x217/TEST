<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo sanitize($article['title']); ?> - <?php echo sanitize($settings['site_name'] ?? 'English News Hub'); ?></title>
    <meta name="description" content="<?php echo truncateText($article['content'], 160); ?>">
    
    <!-- Open Graph / Social Media -->
    <meta property="og:title" content="<?php echo sanitize($article['title']); ?>">
    <meta property="og:description" content="<?php echo truncateText($article['content'], 160); ?>">
    <?php if (!empty($article['image_path'])): ?>
    <meta property="og:image" content="<?php echo sanitize($_SERVER['HTTP_HOST'] . '/assets/' . $article['image_path']); ?>">
    <?php endif; ?>
    
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
        .article-content { line-height: 1.9; font-size: 1.125rem; }
        .article-content p { margin-bottom: 1.75rem; }
        .article-content h2, .article-content h3 { margin-top: 2.5rem; margin-bottom: 1.25rem; font-weight: bold; }
        .dropcap::first-letter { 
            float: left; 
            font-size: 4rem; 
            line-height: 1; 
            margin: 0 0.5rem 0 0;
            color: #f97316;
            font-weight: bold;
        }
        /* Prevent text selection on UI elements */
        header, nav, button, a, h1, h2, h3, .magazine-gradient, .hover-scale { 
            user-select: none; 
            -webkit-user-select: none;
        }
        .article-content, .article-content p { 
            user-select: text;
            -webkit-user-select: text;
        }
        /* Fix cursor types */
        a, button { cursor: pointer; }
        header, nav, h1, h2, h3, .magazine-gradient { cursor: default; }
    </style>
</head>
<body class="bg-amber-50">

    <!-- Header -->
    <header class="magazine-gradient text-white shadow-2xl">
        <div class="container mx-auto px-4 py-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-4xl font-black tracking-tight">
                        <a href="/EnglishNewsApp/" class="hover:opacity-90 transition">
                            <?php echo sanitize($settings['site_name'] ?? 'English News Hub'); ?>
                        </a>
                    </h1>
                    <p class="text-orange-100 text-sm mt-1 italic">
                        <?php echo sanitize($settings['site_tagline'] ?? 'Your Daily Source for Breaking News'); ?>
                    </p>
                </div>
                
                <a href="/EnglishNewsApp/" class="bg-white/20 hover:bg-white/30 backdrop-blur-lg px-6 py-3 rounded-full border border-white/30 transition flex items-center gap-2 font-semibold">
                    <i class="fas fa-arrow-left"></i>
                    Back Home
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="container mx-auto px-4 py-12">
        <div class="max-w-4xl mx-auto">
            
            <!-- Article -->
            <article class="bg-white rounded-3xl shadow-2xl overflow-hidden mb-12">
                
                <!-- Featured Image -->
                <?php if (!empty($article['image_path'])): ?>
                    <div class="relative">
                        <img src="/EnglishNewsApp/assets/<?php echo sanitize($article['image_path']); ?>" 
                             alt="<?php echo sanitize($article['title']); ?>"
                             class="w-full h-[500px] object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                    </div>
                <?php else: ?>
                    <div class="w-full h-[500px] bg-gradient-to-br from-orange-500 to-red-600 flex items-center justify-center">
                        <i class="fas fa-newspaper text-white text-9xl opacity-30"></i>
                    </div>
                <?php endif; ?>
                
                <!-- Article Content -->
                <div class="p-8 lg:p-12">
                    
                    <!-- Category & Meta -->
                    <div class="flex items-center gap-4 mb-6">
                        <span class="bg-orange-500 text-white text-sm font-bold px-4 py-1 rounded-full">
                            BREAKING NEWS
                        </span>
                        <span class="text-gray-500 text-sm">
                            <?php echo formatDate($article['created_at'], 'F j, Y'); ?>
                        </span>
                    </div>
                    
                    <!-- Title -->
                    <h1 class="text-4xl lg:text-5xl font-black text-gray-900 mb-6 leading-tight border-l-4 border-orange-500 pl-6">
                        <?php echo sanitize($article['title']); ?>
                    </h1>
                    
                    <!-- Meta Info -->
                    <div class="flex flex-wrap items-center gap-6 text-gray-600 mb-8 pb-8 border-b-2 border-orange-200">
                        <div class="flex items-center gap-2">
                            <i class="far fa-clock text-orange-500 text-lg"></i>
                            <span class="font-semibold"><?php echo formatDate($article['created_at'], 'g:i A'); ?></span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i class="far fa-eye text-orange-500 text-lg"></i>
                            <span class="font-semibold"><?php echo number_format($article['views']); ?> views</span>
                        </div>
                        <?php if (!empty($article['updated_at']) && $article['updated_at'] !== $article['created_at']): ?>
                        <div class="flex items-center gap-2">
                            <i class="fas fa-sync text-orange-500 text-lg"></i>
                            <span class="font-semibold">Updated <?php echo formatDate($article['updated_at'], 'M j'); ?></span>
                        </div>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Article Body -->
                    <div class="article-content text-gray-800 dropcap">
                        <?php echo $article['content']; ?>
                    </div>
                    
                    <!-- Tags/Keywords -->
                    <div class="mt-12 pt-8 border-t-2 border-gray-200">
                        <div class="flex flex-wrap gap-2 mb-6">
                            <span class="bg-orange-100 text-orange-700 px-4 py-2 rounded-full text-sm font-semibold">
                                #BreakingNews
                            </span>
                            <span class="bg-orange-100 text-orange-700 px-4 py-2 rounded-full text-sm font-semibold">
                                #EnglishNews
                            </span>
                            <span class="bg-orange-100 text-orange-700 px-4 py-2 rounded-full text-sm font-semibold">
                                #Latest
                            </span>
                        </div>
                    </div>
                    
                    <!-- Social Share -->
                    <div class="mt-8 bg-gradient-to-r from-orange-50 to-red-50 rounded-2xl p-6">
                        <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                            <i class="fas fa-share-nodes text-orange-500 mr-2"></i>
                            Share This Article
                        </h3>
                        <div class="flex flex-wrap gap-3">
                            <a href="https://twitter.com/intent/tweet?text=<?php echo urlencode($article['title']); ?>&url=<?php echo urlencode($_SERVER['REQUEST_URI']); ?>" 
                               target="_blank"
                               class="bg-black hover:bg-gray-800 text-white px-6 py-3 rounded-full transition flex items-center gap-2 font-semibold">
                                <i class="fab fa-x-twitter"></i>
                                Twitter/X
                            </a>
                            <a href="https://t.me/share/url?url=<?php echo urlencode($_SERVER['REQUEST_URI']); ?>&text=<?php echo urlencode($article['title']); ?>" 
                               target="_blank"
                               class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-full transition flex items-center gap-2 font-semibold">
                                <i class="fab fa-telegram"></i>
                                Telegram
                            </a>
                            <button onclick="navigator.clipboard.writeText(window.location.href); alert('Link copied!');"
                                    class="bg-gray-700 hover:bg-gray-800 text-white px-6 py-3 rounded-full transition flex items-center gap-2 font-semibold">
                                <i class="fas fa-link"></i>
                                Copy Link
                            </button>
                        </div>
                    </div>
                </div>
            </article>

            <!-- Related News -->
            <?php if (!empty($relatedNews)): ?>
                <div class="mb-12">
                    <div class="mb-8">
                        <h2 class="text-4xl font-black text-gray-900 mb-2">More Stories</h2>
                        <div class="h-1 w-24 bg-gradient-to-r from-orange-500 to-red-500 rounded-full"></div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <?php foreach ($relatedNews as $related): ?>
                            <article class="bg-white rounded-2xl overflow-hidden shadow-xl hover-scale">
                                <a href="/EnglishNewsApp/news-detail.php?slug=<?php echo urlencode($related['slug']); ?>" class="block">
                                    <?php if (!empty($related['image_path'])): ?>
                                        <img src="/EnglishNewsApp/assets/<?php echo sanitize($related['image_path']); ?>" 
                                             alt="<?php echo sanitize($related['title']); ?>"
                                             class="w-full h-44 object-cover">
                                    <?php else: ?>
                                        <div class="w-full h-44 bg-gradient-to-br from-orange-400 to-red-500 flex items-center justify-center">
                                            <i class="fas fa-newspaper text-white text-4xl opacity-50"></i>
                                        </div>
                                    <?php endif; ?>
                                </a>
                                
                                <div class="p-5">
                                    <p class="text-sm text-gray-500 mb-2 font-semibold">
                                        <i class="far fa-calendar mr-1"></i>
                                        <?php echo formatDate($related['created_at'], 'M j, Y'); ?>
                                    </p>
                                    <h3 class="text-lg font-bold text-gray-900 leading-tight hover:text-orange-600 transition">
                                        <a href="/EnglishNewsApp/news-detail.php?slug=<?php echo urlencode($related['slug']); ?>">
                                            <?php echo sanitize($related['title']); ?>
                                        </a>
                                    </h3>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
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
