<?php
/**
 * Statistics Manager
 */

define('ADMIN_PAGE', true);

require_once '../config/database.php';
require_once '../includes/functions.php';

requireLogin('/EnglishNewsApp/admin/login.php');

$pageTitle = 'Statistics';

$db = getDB();

// Handle add or update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'add_stat') {
        $label = trim($_POST['label_name'] ?? '');
        $number = trim($_POST['number_value'] ?? '');
        $icon = trim($_POST['icon'] ?? 'chart-line');
        $visible = isset($_POST['is_visible']) ? 1 : 0;

        if ($label && $number) {
            $stmt = $db->prepare("INSERT INTO statistics (label_name, number_value, icon, is_visible) VALUES (?, ?, ?, ?)");
            $stmt->execute([$label, $number, $icon, $visible]);
            setFlashMessage('success', 'Statistic added');
        }
    }

    if ($action === 'update_stat') {
        $id = (int)($_POST['id'] ?? 0);
        $label = trim($_POST['label_name'] ?? '');
        $number = trim($_POST['number_value'] ?? '');
        $icon = trim($_POST['icon'] ?? 'chart-line');
        $visible = isset($_POST['is_visible']) ? 1 : 0;

        if ($id && $label && $number) {
            $stmt = $db->prepare("UPDATE statistics SET label_name = ?, number_value = ?, icon = ?, is_visible = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?");
            $stmt->execute([$label, $number, $icon, $visible, $id]);
            setFlashMessage('success', 'Statistic updated');
        }
    }

    if ($action === 'delete_stat') {
        $id = (int)($_POST['id'] ?? 0);
        if ($id) {
            $stmt = $db->prepare("DELETE FROM statistics WHERE id = ?");
            $stmt->execute([$id]);
            setFlashMessage('success', 'Statistic deleted');
        }
    }

    redirect('/EnglishNewsApp/admin/statistics.php');
}

// Fetch all stats
$stmt = $db->query("SELECT * FROM statistics ORDER BY display_order ASC, id ASC");
$stats = $stmt->fetchAll();

include 'includes/header.php';
?>

<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Statistics</h1>
            <p class="text-gray-600">Manage homepage statistics visibility and values</p>
        </div>
    </div>

    <!-- Add New Stat -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4">Add Statistic</h2>
        <form method="POST" action="" class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end">
            <input type="hidden" name="action" value="add_stat">
            <div class="md:col-span-2">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Label</label>
                <input type="text" name="label_name" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Number</label>
                <input type="text" name="number_value" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Icon</label>
                <input type="text" name="icon" placeholder="chart-line" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="flex items-center gap-3">
                <label class="flex items-center gap-2 text-sm font-semibold text-gray-700">
                    <input type="checkbox" name="is_visible" class="w-4 h-4 text-blue-600 border-gray-300 rounded">
                    Visible
                </label>
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-5 py-3 rounded-lg font-semibold transition">
                    <i class="fas fa-plus mr-2"></i>Add
                </button>
            </div>
        </form>
    </div>

    <!-- Existing Stats -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4">Existing Statistics</h2>
        <?php if (empty($stats)): ?>
            <p class="text-gray-500">No statistics yet.</p>
        <?php else: ?>
            <div class="space-y-4">
                <?php foreach ($stats as $stat): ?>
                    <form method="POST" action="" class="grid grid-cols-1 md:grid-cols-7 gap-3 items-end p-4 border border-gray-200 rounded-lg">
                        <input type="hidden" name="action" value="update_stat">
                        <input type="hidden" name="id" value="<?php echo $stat['id']; ?>">
                        <div class="md:col-span-2">
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Label</label>
                            <input type="text" name="label_name" value="<?php echo sanitize($stat['label_name']); ?>" class="w-full px-3 py-2 border border-gray-300 rounded">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Number</label>
                            <input type="text" name="number_value" value="<?php echo sanitize($stat['number_value']); ?>" class="w-full px-3 py-2 border border-gray-300 rounded">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Icon</label>
                            <input type="text" name="icon" value="<?php echo sanitize($stat['icon']); ?>" class="w-full px-3 py-2 border border-gray-300 rounded">
                        </div>
                        <div class="flex items-center gap-2">
                            <label class="flex items-center gap-2 text-xs font-semibold text-gray-600">
                                <input type="checkbox" name="is_visible" <?php echo $stat['is_visible'] ? 'checked' : ''; ?> class="w-4 h-4 text-blue-600 border-gray-300 rounded">
                                Visible
                            </label>
                        </div>
                        <div class="flex items-center justify-end gap-2">
                            <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded font-semibold text-sm transition">
                                <i class="fas fa-save mr-1"></i>Save
                            </button>
                            <button type="submit" name="action" value="delete_stat" class="bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded font-semibold text-sm transition" onclick="return confirm('Delete this statistic?');">
                                <i class="fas fa-trash mr-1"></i>Delete
                            </button>
                        </div>
                    </form>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
