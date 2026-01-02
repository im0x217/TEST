<?php
/**
 * Settings Manager
 * Template switcher, social media, and site configuration
 * 
 * @version 1.0
 * @date 2026
 */

define('ADMIN_PAGE', true);

require_once '../config/database.php';
require_once '../includes/functions.php';

requireLogin('/EnglishNewsApp/admin/login.php');

$pageTitle = 'Settings';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'update_settings') {
        try {
            updateSetting('site_name', $_POST['site_name'] ?? '');
            updateSetting('site_tagline', $_POST['site_tagline'] ?? '');
            updateSetting('active_template', $_POST['active_template'] ?? 'template_1');
            updateSetting('social_x', $_POST['social_x'] ?? '');
            updateSetting('social_discord', $_POST['social_discord'] ?? '');
            updateSetting('social_telegram', $_POST['social_telegram'] ?? '');
            
            setFlashMessage('success', 'Settings updated successfully!');
            redirect('/EnglishNewsApp/admin/settings.php');
        } catch (Exception $e) {
            error_log("Settings update error: " . $e->getMessage());
            setFlashMessage('error', 'Failed to update settings.');
        }
    }
}

// Fetch current settings
$settings = getAllSettings();

include 'includes/header.php';
?>

<div class="space-y-6">
    <!-- Page Header -->
    <div>
        <h1 class="text-3xl font-bold text-gray-800 mb-2">Settings</h1>
        <p class="text-gray-600">Configure your site settings and theme</p>
    </div>

    <!-- Settings Form -->
    <form method="POST" action="" class="bg-white rounded-xl shadow-lg p-8 space-y-8">
        <input type="hidden" name="action" value="update_settings">
        
        <!-- General Settings -->
        <div>
            <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center gap-2">
                <i class="fas fa-globe text-blue-500"></i>
                General Settings
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Site Name</label>
                    <input type="text" name="site_name" value="<?php echo sanitize($settings['site_name'] ?? ''); ?>" 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Site Tagline</label>
                    <input type="text" name="site_tagline" value="<?php echo sanitize($settings['site_tagline'] ?? ''); ?>" 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                </div>
            </div>
        </div>

        <!-- Template Selection -->
        <div>
            <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center gap-2">
                <i class="fas fa-palette text-purple-500"></i>
                Theme / Template
            </h2>
            
            <p class="text-gray-600 mb-6">Choose the active frontend template for your website</p>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Template 1 -->
                <label class="cursor-pointer">
                    <input type="radio" name="active_template" value="template_1" 
                           <?php echo ($settings['active_template'] ?? '') === 'template_1' ? 'checked' : ''; ?>
                           class="sr-only peer">
                    <div class="border-2 border-gray-300 peer-checked:border-blue-500 peer-checked:ring-4 peer-checked:ring-blue-100 rounded-xl p-6 transition">
                        <div class="w-full h-32 bg-gradient-to-br from-blue-500 to-purple-600 rounded-lg mb-4 flex items-center justify-center">
                            <i class="fas fa-newspaper text-white text-4xl"></i>
                        </div>
                        <h3 class="font-bold text-gray-800 text-lg mb-1">Modern Blue</h3>
                        <p class="text-sm text-gray-600">Stats in top-right, 3-column grid</p>
                    </div>
                </label>
                
                <!-- Template 2 -->
                <label class="cursor-pointer">
                    <input type="radio" name="active_template" value="template_2" 
                           <?php echo ($settings['active_template'] ?? '') === 'template_2' ? 'checked' : ''; ?>
                           class="sr-only peer">
                    <div class="border-2 border-gray-300 peer-checked:border-orange-500 peer-checked:ring-4 peer-checked:ring-orange-100 rounded-xl p-6 transition">
                        <div class="w-full h-32 bg-gradient-to-br from-orange-500 to-red-600 rounded-lg mb-4 flex items-center justify-center">
                            <i class="fas fa-newspaper text-white text-4xl"></i>
                        </div>
                        <h3 class="font-bold text-gray-800 text-lg mb-1">Magazine Orange</h3>
                        <p class="text-sm text-gray-600">Stats in sidebar, 2-column layout</p>
                    </div>
                </label>
                
                <!-- Template 3 -->
                <label class="cursor-pointer">
                    <input type="radio" name="active_template" value="template_3" 
                           <?php echo ($settings['active_template'] ?? '') === 'template_3' ? 'checked' : ''; ?>
                           class="sr-only peer">
                    <div class="border-2 border-gray-300 peer-checked:border-cyan-500 peer-checked:ring-4 peer-checked:ring-cyan-100 rounded-xl p-6 transition">
                        <div class="w-full h-32 bg-gradient-to-br from-gray-900 to-cyan-900 rounded-lg mb-4 flex items-center justify-center">
                            <i class="fas fa-newspaper text-cyan-400 text-4xl"></i>
                        </div>
                        <h3 class="font-bold text-gray-800 text-lg mb-1">Tech Dark</h3>
                        <p class="text-sm text-gray-600">Cyberpunk style, glowing borders</p>
                    </div>
                </label>
            </div>
        </div>

        <!-- Social Media -->
        <div>
            <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center gap-2">
                <i class="fas fa-share-nodes text-green-500"></i>
                Social Media Links
            </h2>
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fab fa-x-twitter mr-2"></i>
                        Twitter/X URL
                    </label>
                    <input type="url" name="social_x" value="<?php echo sanitize($settings['social_x'] ?? ''); ?>" 
                           placeholder="https://x.com/yournews"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fab fa-discord mr-2"></i>
                        Discord Invite URL
                    </label>
                    <input type="url" name="social_discord" value="<?php echo sanitize($settings['social_discord'] ?? ''); ?>" 
                           placeholder="https://discord.gg/yournews"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fab fa-telegram mr-2"></i>
                        Telegram Channel URL
                    </label>
                    <input type="url" name="social_telegram" value="<?php echo sanitize($settings['social_telegram'] ?? ''); ?>" 
                           placeholder="https://t.me/yournews"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="flex items-center justify-end gap-4 pt-6 border-t border-gray-200">
            <a href="/EnglishNewsApp/admin/" class="px-6 py-3 text-gray-600 hover:text-gray-800 font-semibold transition">
                Cancel
            </a>
            <button type="submit" class="bg-gradient-to-r from-blue-500 to-purple-600 hover:from-blue-600 hover:to-purple-700 text-white px-8 py-3 rounded-lg font-semibold shadow-lg transition">
                <i class="fas fa-save mr-2"></i>
                Save Settings
            </button>
        </div>
    </form>
</div>

<?php include 'includes/footer.php'; ?>
