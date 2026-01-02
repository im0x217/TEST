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
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;500;700;900&family=Rajdhani:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body { 
            font-family: 'Rajdhani', sans-serif;
            background: #0a0e27;
            color: #fff;
            position: relative;
            overflow-x: hidden;
        }
        
        /* Static Tech Grid Background - Optimized */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: 
                linear-gradient(rgba(0, 255, 255, 0.02) 1px, transparent 1px),
                linear-gradient(90deg, rgba(0, 255, 255, 0.02) 1px, transparent 1px);
            background-size: 50px 50px;
            z-index: 0;
            pointer-events: none;
        }
        
        /* Optimized Cyan Border Effect */
        .cyber-border {
            position: relative;
            border: 2px solid rgba(0, 255, 255, 0.4);
            box-shadow: 0 0 5px rgba(0, 255, 255, 0.2);
        }
        
        /* Corner decoration */
        .cyber-corner {
            position: relative;
        }
        
        .cyber-corner::before,
        .cyber-corner::after {
            content: '';
            position: absolute;
            width: 20px;
            height: 20px;
            border: 2px solid #00ffff;
        }
        
        .cyber-corner::before {
            top: -2px;
            left: -2px;
            border-right: none;
            border-bottom: none;
        }
        
        .cyber-corner::after {
            bottom: -2px;
            right: -2px;
            border-left: none;
            border-top: none;
        }
        
        /* Glowing text */
        .glow-text {
           Optimized glowing text */
        .glow-text {
            text-shadow: 0 0 8px rgba(0, 255, 255, 0.6);
            font-family: 'Orbitron', sans-serif;
        }
        
        /* Background overlay - Removed expensive backdrop-filter */
        .tech-overlay {
            background: linear-gradient(135deg, rgba(10, 14, 39, 0.95) 0%, rgba(20, 30, 60, 0.95) 100%
        /* Article content styling */
        .article-content {
            line-height: 1.9;
            font-size: 1.125rem;
            color: #e0e0e0;
        }
        
        .article-content p {
            margin-bottom: 1.5rem;
        }
        
        .article-content h2,
        .article-content h3 {
            color: #00ffff;
            margin-top: 2rem;
            margin-bottom: 1rem;
            font-weight: bold;
        }
        
        h1, h2, h3 { font-family: 'Orbitron', sans-serif; }
        
        /* Optimized hover effect */
        .hover-glow {
            transition: box-shadow 0.2s ease;
        }
        
        .hover-glow:hover {
            box-shadow: 0 0 10px rgba(0, 255, 255, 0.4);
        }
        
        /* Prevent text selection on UI elements */
        header, nav, button, .cyber-border, .cyber-corner, a, h1, h2, h3, .glow-text, .tech-overlay, .hover-glow { 
            user-select: none; 
            -webkit-user-select: none;
        }
        .article-content, .article-content p { 
            user-select: text;
            -webkit-user-select: text;
        }
        /* Fix cursor types */
        a, button { cursor: pointer; }
        header, nav, h1, h2, h3, .glow-text, .tech-overlay { cursor: default; }
    </style>
</head>
<body>

    <!-- Header -->
    <header class="relative z-10 border-b-2 border-cyan-500/30 shadow-2xl">
        <div class="tech-overlay py-6">
            <div class="container mx-auto px-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-4xl font-black glow-text tracking-wider">
                            <a href="/EnglishNewsApp/" class="hover:opacity-80 transition">
                                <?php echo strtoupper(sanitize($settings['site_name'] ?? 'ENGLISH NEWS')); ?>
                            </a>
                        </h1>
                        <p class="text-cyan-400 text-sm tracking-widest font-light mt-1">
                            <?php echo strtoupper(sanitize($settings['site_tagline'] ?? 'PROJECT NEWS')); ?>
                        </p>
                    </div>
                    
                    <a href="/EnglishNewsApp/" class="cyber-border px-6 py-3 rounded-lg tech-overlay flex items-center gap-3 hover-glow font-semibold tracking-wider">
                        <i class="fas fa-arrow-left text-cyan-400"></i>
                        <span class="text-cyan-400">BACK</span>
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="relative z-10 container mx-auto px-4 py-8">
        <div class="max-w-5xl mx-auto">
            
            <!-- Article -->
            <article class="cyber-border cyber-corner tech-overlay rounded-lg overflow-hidden mb-8">
                
                <!-- Featured Image -->
                <?php if (!empty($article['image_path'])): ?>
                    <div class="relative">
                        <img src="/EnglishNewsApp/assets/<?php echo sanitize($article['image_path']); ?>" 
                             alt="<?php echo sanitize($article['title']); ?>"
                             class="w-full h-[500px] object-cover opacity-90">
                        <div class="absolute inset-0 bg-gradient-to-t from-[#0a0e27] via-transparent to-transparent"></div>
                    </div>
                <?php else: ?>
                    <div class="w-full h-[500px] bg-gradient-to-br from-cyan-900 to-blue-900 flex items-center justify-center">
                        <i class="fas fa-newspaper text-cyan-400 text-9xl opacity-20"></i>
                    </div>
                <?php endif; ?>
                
                <!-- Article Content -->
                <div class="p-8 lg:p-12">
                    
                    <!-- Category Badge -->
                    <div class="flex items-center gap-4 mb-6">
                        <span class="cyber-border px-4 py-1 rounded text-sm font-bold text-cyan-400 tracking-wider">
                            BREAKING NEWS
                        </span>
                        <span class="text-cyan-500 text-sm font-mono">
                            ID: #<?php echo str_pad($article['id'], 5, '0', STR_PAD_LEFT); ?>
                        </span>
                    </div>
                    
                    <!-- Title -->
                    <h1 class="text-4xl lg:text-5xl font-black glow-text mb-6 leading-tight uppercase tracking-wide border-l-4 border-cyan-500 pl-6">
                        <?php echo sanitize($article['title']); ?>
                    </h1>
                    
                    <!-- Meta Info -->
                    <div class="flex flex-wrap items-center gap-6 text-cyan-400 mb-8 pb-8 border-b-2 border-cyan-500/30 font-mono text-sm">
                        <div class="flex items-center gap-2">
                            <i class="far fa-calendar text-lg"></i>
                            <span><?php echo formatDate($article['created_at'], 'Y.m.d'); ?></span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i class="far fa-clock text-lg"></i>
                            <span><?php echo formatDate($article['created_at'], 'H:i'); ?></span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i class="far fa-eye text-lg"></i>
                            <span><?php echo number_format($article['views']); ?> VIEWS</span>
                        </div>
                        <?php if (!empty($article['updated_at']) && $article['updated_at'] !== $article['created_at']): ?>
                        <div class="flex items-center gap-2">
                            <i class="fas fa-sync text-lg"></i>
                            <span>UPDATED: <?php echo formatDate($article['updated_at'], 'Y.m.d'); ?></span>
                        </div>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Article Body -->
                    <div class="article-content">
                        <?php echo $article['content']; ?>
                    </div>
                    
                    <!-- Divider -->
                    <div class="my-12 h-px bg-gradient-to-r from-transparent via-cyan-500 to-transparent"></div>
                    
                    <!-- Tags -->
                    <div class="mb-8">
                        <div class="flex flex-wrap gap-2">
                            <span class="cyber-border px-4 py-2 rounded text-xs font-semibold text-cyan-400 tracking-wider">
                                #BREAKING
                            </span>
                            <span class="cyber-border px-4 py-2 rounded text-xs font-semibold text-cyan-400 tracking-wider">
                                #NEWS
                            </span>
                            <span class="cyber-border px-4 py-2 rounded text-xs font-semibold text-cyan-400 tracking-wider">
                                #LATEST
                            </span>
                        </div>
                    </div>
                    
                    <!-- Social Share -->
                    <div class="cyber-border tech-overlay rounded-lg p-6">
                        <h3 class="text-xl font-bold glow-text mb-4 flex items-center gap-2 tracking-wider">
                            <i class="fas fa-share-nodes text-cyan-400"></i>
                            SHARE THIS ARTICLE
                        </h3>
                        <div class="flex flex-wrap gap-3">
                            <a href="https://twitter.com/intent/tweet?text=<?php echo urlencode($article['title']); ?>&url=<?php echo urlencode($_SERVER['REQUEST_URI']); ?>" 
                               target="_blank"
                               class="cyber-border px-6 py-3 rounded-lg flex items-center gap-3 hover-glow font-semibold tracking-wider">
                                <i class="fab fa-x-twitter text-cyan-400"></i>
                                <span class="text-cyan-400">TWITTER</span>
                            </a>
                            <a href="https://t.me/share/url?url=<?php echo urlencode($_SERVER['REQUEST_URI']); ?>&text=<?php echo urlencode($article['title']); ?>" 
                               target="_blank"
                               class="cyber-border px-6 py-3 rounded-lg flex items-center gap-3 hover-glow font-semibold tracking-wider">
                                <i class="fab fa-telegram text-cyan-400"></i>
                                <span class="text-cyan-400">TELEGRAM</span>
                            </a>
                            <button onclick="navigator.clipboard.writeText(window.location.href); alert('Link copied to clipboard!');"
                                    class="cyber-border px-6 py-3 rounded-lg flex items-center gap-3 hover-glow font-semibold tracking-wider">
                                <i class="fas fa-link text-cyan-400"></i>
                                <span class="text-cyan-400">COPY LINK</span>
                            </button>
                        </div>
                    </div>
                </div>
            </article>

            <!-- Related News -->
            <?php if (!empty($relatedNews)): ?>
                <div class="mb-12">
                    <h2 class="text-3xl font-bold glow-text mb-6 tracking-wider flex items-center gap-3">
                        <i class="fas fa-newspaper text-cyan-400"></i>
                        MORE ARTICLES
                    </h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <?php foreach ($relatedNews as $related): ?>
                            <article class="cyber-border tech-overlay rounded-lg overflow-hidden hover-glow group">
                                <a href="/EnglishNewsApp/news-detail.php?slug=<?php echo urlencode($related['slug']); ?>" class="block">
                                    <?php if (!empty($related['image_path'])): ?>
                                        <img src="/EnglishNewsApp/assets/<?php echo sanitize($related['image_path']); ?>" 
                                             alt="<?php echo sanitize($related['title']); ?>"
                                             class="w-full h-44 object-cover opacity-80 group-hover:opacity-100 transition">
                                    <?php else: ?>
                                        <div class="w-full h-44 bg-gradient-to-br from-cyan-900 to-blue-900 flex items-center justify-center">
                                            <i class="fas fa-newspaper text-cyan-400 text-4xl opacity-30"></i>
                                        </div>
                                    <?php endif; ?>
                                </a>
                                
                                <div class="p-5">
                                    <p class="text-xs text-cyan-400 mb-2 font-mono">
                                        <?php echo formatDate($related['created_at'], 'Y.m.d'); ?>
                                    </p>
                                    <h3 class="text-lg font-bold text-white leading-tight group-hover:text-cyan-400 transition uppercase">
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
    <footer class="relative z-10 border-t-2 border-cyan-500/30 mt-12">
        <div class="tech-overlay py-8">
            <div class="container mx-auto px-4 text-center">
                <p class="text-cyan-400 text-sm tracking-wider font-mono">
                    &copy; <?php echo date('Y'); ?> <?php echo strtoupper(sanitize($settings['site_name'] ?? 'ENGLISH NEWS HUB')); ?> - ALL RIGHTS RESERVED
                </p>
                <p class="text-cyan-600 text-xs mt-2 font-mono">
                    SYSTEM STATUS: <span class="text-green-400">ONLINE</span>
                </p>
            </div>
        </div>
    </footer>

</body>
</html>
