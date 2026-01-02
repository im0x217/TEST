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
                        primary: '#3b82f6',
                        secondary: '#8b5cf6',
                        accent: '#06b6d4'
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
        .article-content { line-height: 1.8; }
        .article-content p { margin-bottom: 1.5rem; }
        .article-content h2, .article-content h3 { margin-top: 2rem; margin-bottom: 1rem; font-weight: bold; }
        /* Prevent text selection on UI elements */
        header, nav, button, a, h1, h2, h3, .gradient-bg, .hover-lift { 
            user-select: none; 
            -webkit-user-select: none;
        }
        .article-content, .article-content p { 
            user-select: text;
            -webkit-user-select: text;
        }
        /* Fix cursor types */
        a, button { cursor: pointer; }
        header, nav, h1, h2, h3, .gradient-bg { cursor: default; }
    </style>
</head>
<body class="bg-gray-50">

    <!-- Header -->
    <header class="gradient-bg text-white shadow-xl">
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
                
                <a href="/EnglishNewsApp/" class="bg-white/10 hover:bg-white/20 backdrop-blur-lg px-6 py-3 rounded-full border border-white/20 transition flex items-center gap-2">
                    <i class="fas fa-arrow-left"></i>
                    Back to Home
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="container mx-auto px-4 py-12">
        <div class="max-w-4xl mx-auto">
            
            <!-- Article Header -->
            <article class="bg-white rounded-3xl shadow-xl overflow-hidden mb-12">
                
                <!-- Featured Image -->
                <?php if (!empty($article['image_path'])): ?>
                    <img src="/EnglishNewsApp/assets/<?php echo sanitize($article['image_path']); ?>" 
                         alt="<?php echo sanitize($article['title']); ?>"
                         class="w-full h-96 object-cover">
                <?php else: ?>
                    <div class="w-full h-96 bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center">
                        <i class="fas fa-newspaper text-white text-8xl opacity-30"></i>
                    </div>
                <?php endif; ?>
                
                <!-- Article Content -->
                <div class="p-8 lg:p-12">
                    <!-- Title -->
                    <h1 class="text-4xl lg:text-5xl font-bold text-gray-900 mb-6 leading-tight">
                        <?php echo sanitize($article['title']); ?>
                    </h1>
                    
                    <!-- Meta Info -->
                    <div class="flex flex-wrap items-center gap-6 text-gray-600 mb-8 pb-8 border-b border-gray-200">
                        <div class="flex items-center gap-2">
                            <i class="far fa-calendar text-blue-600"></i>
                            <span><?php echo formatDate($article['created_at'], 'F j, Y'); ?></span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i class="far fa-clock text-blue-600"></i>
                            <span><?php echo formatDate($article['created_at'], 'g:i A'); ?></span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i class="far fa-eye text-blue-600"></i>
                            <span><?php echo number_format($article['views']); ?> views</span>
                        </div>
                    </div>
                    
                    <!-- Article Body -->
                    <div class="article-content text-gray-700 text-lg">
                        <?php echo $article['content']; ?>
                    </div>
                    
                    <!-- Social Share -->
                    <div class="mt-12 pt-8 border-t border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Share this article:</h3>
                        <div class="flex gap-3">
                            <a href="https://twitter.com/intent/tweet?text=<?php echo urlencode($article['title']); ?>&url=<?php echo urlencode($_SERVER['REQUEST_URI']); ?>" 
                               target="_blank"
                               class="bg-black hover:bg-gray-800 text-white px-6 py-3 rounded-full transition flex items-center gap-2">
                                <i class="fab fa-x-twitter"></i>
                                Share on X
                            </a>
                            <a href="https://t.me/share/url?url=<?php echo urlencode($_SERVER['REQUEST_URI']); ?>&text=<?php echo urlencode($article['title']); ?>" 
                               target="_blank"
                               class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-full transition flex items-center gap-2">
                                <i class="fab fa-telegram"></i>
                                Share on Telegram
                            </a>
                        </div>
                    </div>
                </div>
            </article>

            <!-- Related News -->
            <?php if (!empty($relatedNews)): ?>
                <div class="mb-12">
                    <h2 class="text-3xl font-bold text-gray-800 mb-8">Related Articles</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <?php foreach ($relatedNews as $related): ?>
                            <article class="bg-white rounded-2xl overflow-hidden shadow-lg hover-lift">
                                <a href="/EnglishNewsApp/news-detail.php?slug=<?php echo urlencode($related['slug']); ?>" class="block">
                                    <?php if (!empty($related['image_path'])): ?>
                                        <img src="/EnglishNewsApp/assets/<?php echo sanitize($related['image_path']); ?>" 
                                             alt="<?php echo sanitize($related['title']); ?>"
                                             class="w-full h-40 object-cover">
                                    <?php else: ?>
                                        <div class="w-full h-40 bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center">
                                            <i class="fas fa-newspaper text-white text-4xl opacity-50"></i>
                                        </div>
                                    <?php endif; ?>
                                </a>
                                
                                <div class="p-5">
                                    <p class="text-sm text-gray-500 mb-2">
                                        <i class="far fa-calendar mr-1"></i>
                                        <?php echo formatDate($related['created_at'], 'M j, Y'); ?>
                                    </p>
                                    <h3 class="text-lg font-bold text-gray-800 leading-tight hover:text-blue-600 transition">
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
