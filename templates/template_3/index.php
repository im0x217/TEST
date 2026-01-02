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
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
            will-change: border-color, box-shadow;
        }
        
        .cyber-border:hover {
            border-color: rgba(0, 255, 255, 0.7);
            box-shadow: 0 0 10px rgba(0, 255, 255, 0.4);
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
        
        /* Optimized glowing text */
        .glow-text {
            text-shadow: 0 0 8px rgba(0, 255, 255, 0.6);
            font-family: 'Orbitron', sans-serif;
        }
        
        /* Background overlay - Removed expensive backdrop-filter */
        .tech-overlay {
            background: linear-gradient(135deg, rgba(10, 14, 39, 0.95) 0%, rgba(20, 30, 60, 0.95) 100%);
        }
        
        /* Removed pulse animation for better performance */
        .stat-pulse {
            opacity: 1;
        }
        
        /* Removed scanline animation for better performance */
        .scanline {
            position: relative;
        }
        
        /* Prevent text selection on UI elements */
        header, nav, button, .cyber-border, .cyber-corner, a, h1, h2, h3, .glow-text, .tech-overlay, .stat-pulse { 
            user-select: none; 
            -webkit-user-select: none;
        }
        /* Fix cursor types */
        a, button { cursor: pointer; }
        header, nav, h1, h2, h3, .glow-text, .tech-overlay, .stat-pulse { cursor: default; }
        
        .holo-effect::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(0, 255, 255, 0.2), transparent);
            transition: left 0.5s ease;
        }
        
        .holo-effect:hover::before {
            left: 100%;
        }
        
        h1, h2, h3 { font-family: 'Orbitron', sans-serif; }
    </style>
</head>
<body>

    <!-- Header -->
    <header class="relative z-10 border-b-2 border-cyan-500/30 shadow-2xl">
        <div class="tech-overlay py-8">
            <div class="container mx-auto px-4">
                <div class="text-center">
                    <h1 class="text-6xl font-black glow-text mb-2 tracking-wider">
                        <?php echo strtoupper(sanitize($settings['site_name'] ?? 'ENGLISH NEWS')); ?>
                    </h1>
                    <p class="text-cyan-400 text-xl tracking-widest font-light">
                        <?php echo strtoupper(sanitize($settings['site_tagline'] ?? 'PROJECT NEWS')); ?>
                    </p>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content with Sidebar -->
    <main class="relative z-10 container mx-auto px-4 py-8">
        <div class="grid grid-cols-1 xl:grid-cols-4 gap-6">
            
            <!-- Main Content Area (Left - 3 columns) -->
            <div class="xl:col-span-3 space-y-6">
                
                <!-- NEWS SECTION -->
                <section class="cyber-border cyber-corner tech-overlay p-6 rounded-lg scanline">
                    <h2 class="text-3xl font-bold glow-text mb-6 tracking-wider flex items-center gap-3">
                        <i class="fas fa-newspaper text-cyan-400"></i>
                        NEWS
                    </h2>
                    
                    <?php if (empty($news)): ?>
                        <div class="text-center py-20 text-cyan-400">
                            <i class="fas fa-inbox text-6xl mb-4 opacity-30"></i>
                            <p class="text-xl">NO DATA AVAILABLE</p>
                        </div>
                    <?php else: ?>
                        <!-- Top 2 Featured News (Large) -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <?php foreach (array_slice($news, 0, 2) as $article): ?>
                                <article class="cyber-border tech-overlay rounded-lg overflow-hidden holo-effect group">
                                    <a href="/EnglishNewsApp/news-detail.php?slug=<?php echo urlencode($article['slug']); ?>" class="block">
                                        <?php if (!empty($article['image_path'])): ?>
                                            <img src="/EnglishNewsApp/assets/<?php echo sanitize($article['image_path']); ?>" 
                                                 alt="<?php echo sanitize($article['title']); ?>"
                                                 class="w-full h-64 object-cover opacity-80 group-hover:opacity-100 transition">
                                        <?php else: ?>
                                            <div class="w-full h-64 bg-gradient-to-br from-cyan-900 to-blue-900 flex items-center justify-center">
                                                <i class="fas fa-newspaper text-cyan-400 text-6xl opacity-30"></i>
                                            </div>
                                        <?php endif; ?>
                                    </a>
                                    
                                    <div class="p-5">
                                        <div class="flex items-center gap-4 text-xs text-cyan-400 mb-3 font-mono">
                                            <span><i class="far fa-calendar mr-1"></i><?php echo formatDate($article['created_at'], 'Y.m.d'); ?></span>
                                            <span><i class="far fa-eye mr-1"></i><?php echo number_format($article['views']); ?></span>
                                        </div>
                                        
                                        <h3 class="text-xl font-bold text-white mb-3 leading-tight group-hover:text-cyan-400 transition uppercase">
                                            <a href="/EnglishNewsApp/news-detail.php?slug=<?php echo urlencode($article['slug']); ?>">
                                                <?php echo sanitize($article['title']); ?>
                                            </a>
                                        </h3>
                                        
                                        <p class="text-gray-400 text-sm leading-relaxed mb-4">
                                            <?php echo truncateText($article['content'], 100); ?>
                                        </p>
                                        
                                        <a href="/EnglishNewsApp/news-detail.php?slug=<?php echo urlencode($article['slug']); ?>" 
                                           class="inline-flex items-center text-cyan-400 hover:text-cyan-300 font-semibold transition text-sm">
                                            READ MORE
                                            <i class="fas fa-chevron-right ml-2"></i>
                                        </a>
                                    </div>
                                </article>
                            <?php endforeach; ?>
                        </div>
                        
                        <!-- Remaining News (Smaller Grid) -->
                        <?php if (count($news) > 2): ?>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <?php foreach (array_slice($news, 2, 6) as $article): ?>
                                    <article class="cyber-border tech-overlay rounded-lg overflow-hidden holo-effect group">
                                        <a href="/EnglishNewsApp/news-detail.php?slug=<?php echo urlencode($article['slug']); ?>" class="block">
                                            <?php if (!empty($article['image_path'])): ?>
                                                <img src="/EnglishNewsApp/assets/<?php echo sanitize($article['image_path']); ?>" 
                                                     alt="<?php echo sanitize($article['title']); ?>"
                                                     class="w-full h-40 object-cover opacity-80 group-hover:opacity-100 transition">
                                            <?php else: ?>
                                                <div class="w-full h-40 bg-gradient-to-br from-cyan-900 to-blue-900 flex items-center justify-center">
                                                    <i class="fas fa-newspaper text-cyan-400 text-4xl opacity-30"></i>
                                                </div>
                                            <?php endif; ?>
                                        </a>
                                        
                                        <div class="p-4">
                                            <p class="text-xs text-cyan-400 mb-2 font-mono">
                                                <?php echo formatDate($article['created_at'], 'Y.m.d'); ?>
                                            </p>
                                            <h3 class="text-sm font-bold text-white leading-tight group-hover:text-cyan-400 transition uppercase">
                                                <a href="/EnglishNewsApp/news-detail.php?slug=<?php echo urlencode($article['slug']); ?>">
                                                    <?php echo sanitize($article['title']); ?>
                                                </a>
                                            </h3>
                                        </div>
                                    </article>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                </section>

                <!-- SOCIAL MEDIA SECTION -->
                <section class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <?php if (!empty($settings['social_x'])): ?>
                        <a href="<?php echo sanitize($settings['social_x']); ?>" 
                           target="_blank"
                           class="cyber-border cyber-corner tech-overlay p-8 rounded-lg flex flex-col items-center justify-center gap-4 group">
                            <i class="fab fa-x-twitter text-5xl text-cyan-400 group-hover:scale-110 transition"></i>
                            <span class="text-xl font-bold glow-text tracking-wider">TWITTER</span>
                        </a>
                    <?php endif; ?>
                    
                    <?php if (!empty($settings['social_discord'])): ?>
                        <a href="<?php echo sanitize($settings['social_discord']); ?>" 
                           target="_blank"
                           class="cyber-border cyber-corner tech-overlay p-8 rounded-lg flex flex-col items-center justify-center gap-4 group">
                            <i class="fab fa-discord text-5xl text-cyan-400 group-hover:scale-110 transition"></i>
                            <span class="text-xl font-bold glow-text tracking-wider">DISCORD</span>
                        </a>
                    <?php endif; ?>
                    
                    <?php if (!empty($settings['social_telegram'])): ?>
                        <a href="<?php echo sanitize($settings['social_telegram']); ?>" 
                           target="_blank"
                           class="cyber-border cyber-corner tech-overlay p-8 rounded-lg flex flex-col items-center justify-center gap-4 group">
                            <i class="fab fa-telegram text-5xl text-cyan-400 group-hover:scale-110 transition"></i>
                            <span class="text-xl font-bold glow-text tracking-wider">TELEGRAM</span>
                        </a>
                    <?php endif; ?>
                </section>

                <!-- PHOTOS & VIDEOS SECTION -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- PHOTOS -->
                    <section class="cyber-border cyber-corner tech-overlay p-6 rounded-lg">
                        <h2 class="text-2xl font-bold glow-text mb-4 tracking-wider flex items-center gap-3">
                            <i class="fas fa-images text-cyan-400"></i>
                            PHOTOS
                        </h2>
                        <?php if (empty($photos)): ?>
                            <div class="text-center py-10 text-cyan-400">
                                <i class="fas fa-image text-4xl mb-3 opacity-40"></i>
                                <p class="tracking-widest">NO PHOTOS AVAILABLE</p>
                            </div>
                        <?php else: ?>
                            <div class="grid grid-cols-3 gap-2">
                                <?php foreach (array_slice($photos, 0, 6) as $photo): ?>
                                    <div class="cyber-border aspect-square bg-gradient-to-br from-cyan-900/30 to-blue-900/30 rounded overflow-hidden">
                                        <img src="/EnglishNewsApp/assets/<?php echo sanitize($photo['file_path']); ?>" 
                                             alt="<?php echo sanitize($photo['title']); ?>"
                                             class="w-full h-full object-cover">
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </section>
                    
                    <!-- VIDEOS -->
                    <section class="cyber-border cyber-corner tech-overlay p-6 rounded-lg">
                        <h2 class="text-2xl font-bold glow-text mb-4 tracking-wider flex items-center gap-3">
                            <i class="fas fa-video text-cyan-400"></i>
                            VIDEOS
                        </h2>
                        <?php if (empty($videos)): ?>
                            <div class="text-center py-10 text-cyan-400">
                                <i class="fas fa-video-slash text-4xl mb-3 opacity-40"></i>
                                <p class="tracking-widest">NO VIDEOS AVAILABLE</p>
                            </div>
                        <?php else: ?>
                            <div class="grid grid-cols-3 gap-2">
                                <?php foreach (array_slice($videos, 0, 6) as $video): ?>
                                    <div class="cyber-border aspect-square bg-gradient-to-br from-cyan-900/30 to-blue-900/30 rounded overflow-hidden flex items-center justify-center relative">
                                        <video class="w-full h-full object-cover" preload="metadata" controls <?php if (!empty($video['thumbnail_path'])): ?>poster="/EnglishNewsApp/assets/<?php echo sanitize($video['thumbnail_path']); ?>"<?php endif; ?>>
                                            <source src="/EnglishNewsApp/assets/<?php echo sanitize($video['file_path']); ?>" type="video/mp4">
                                            Your browser does not support the video tag.
                                        </video>
                                        <div class="absolute inset-0 pointer-events-none bg-black/20"></div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </section>
                </div>
            </div>

            <!-- Sidebar (Right - 1 column) - STATISTICS -->
            <aside class="xl:col-span-1">
                <div class="cyber-border cyber-corner tech-overlay p-6 rounded-lg sticky top-4 scanline">
                    <h2 class="text-2xl font-bold glow-text mb-6 tracking-wider text-center">
                        PROJECT STATISTICS
                    </h2>
                    
                    <div class="space-y-6">
                        <?php foreach ($statistics as $stat): ?>
                            <div class="cyber-border bg-gradient-to-br from-cyan-900/20 to-blue-900/20 p-4 rounded-lg text-center stat-pulse">
                                <i class="fas fa-<?php echo sanitize($stat['icon'] ?? 'chart-line'); ?> text-3xl text-cyan-400 mb-3 block"></i>
                                <div class="text-4xl font-black glow-text mb-2">
                                    <?php echo sanitize($stat['number_value']); ?>
                                </div>
                                <div class="text-xs text-cyan-300 uppercase tracking-wider font-semibold">
                                    <?php echo sanitize($stat['label_name']); ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </aside>
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
