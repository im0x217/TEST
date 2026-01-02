<?php
/**
 * Database Configuration & Connection Handler
 * 
 * Modern PDO implementation with error handling
 * Following 2026 security best practices
 * 
 * @version 1.0
 * @date 2026
 */

// Prevent direct access
if (!defined('DB_CONFIG_LOADED')) {
    define('DB_CONFIG_LOADED', true);
}

// Database Configuration Constants (env-first for hosting like Render)
define('DB_HOST', getenv('DB_HOST') ?: 'localhost');
define('DB_NAME', getenv('DB_NAME') ?: 'english_news_cms');
define('DB_USER', getenv('DB_USER') ?: 'root');  // Change this in production!
define('DB_PASS', getenv('DB_PASS') ?: 'admin');      // Change this in production!
define('DB_CHARSET', 'utf8mb4');

/**
 * Database Connection Class
 * Singleton pattern to ensure single connection instance
 */
class Database {
    private static ?PDO $connection = null;
    private static ?Database $instance = null;
    
    /**
     * Private constructor to prevent direct instantiation
     */
    private function __construct() {
        $this->connect();
    }
    
    /**
     * Get singleton instance
     */
    public static function getInstance(): Database {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Establish database connection using PDO
     */
    private function connect(): void {
        try {
            $dsn = sprintf(
                "mysql:host=%s;dbname=%s;charset=%s",
                DB_HOST,
                DB_NAME,
                DB_CHARSET
            );
            
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
                PDO::ATTR_PERSISTENT         => false,
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES " . DB_CHARSET
            ];
            
            self::$connection = new PDO($dsn, DB_USER, DB_PASS, $options);
            
        } catch (PDOException $e) {
            $this->handleConnectionError($e);
        }
    }
    
    /**
     * Get PDO connection instance
     */
    public function getConnection(): PDO {
        if (self::$connection === null) {
            $this->connect();
        }
        return self::$connection;
    }
    
    /**
     * Handle connection errors gracefully
     */
    private function handleConnectionError(PDOException $e): void {
        // Log error (in production, use proper logging)
        error_log("Database Connection Error: " . $e->getMessage());
        
        // Display user-friendly error
        if (php_sapi_name() === 'cli') {
            die("Database connection failed. Check your configuration.\n");
        } else {
            http_response_code(503);
            die("
            <!DOCTYPE html>
            <html lang='en'>
            <head>
                <meta charset='UTF-8'>
                <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                <title>Database Connection Error</title>
                <style>
                    body { 
                        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; 
                        display: flex; 
                        align-items: center; 
                        justify-content: center; 
                        min-height: 100vh; 
                        margin: 0;
                        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                        color: #fff;
                    }
                    .error-container { 
                        text-align: center; 
                        background: rgba(255,255,255,0.1);
                        padding: 3rem;
                        border-radius: 1rem;
                        backdrop-filter: blur(10px);
                        max-width: 500px;
                    }
                    h1 { margin: 0 0 1rem; font-size: 2.5rem; }
                    p { margin: 0; opacity: 0.9; }
                </style>
            </head>
            <body>
                <div class='error-container'>
                    <h1>⚠️ Connection Error</h1>
                    <p>Unable to connect to the database.</p>
                    <p>Please check your configuration and try again.</p>
                </div>
            </body>
            </html>
            ");
        }
    }
    
    /**
     * Prevent cloning of the singleton instance
     */
    private function __clone() {}
    
    /**
     * Prevent unserialization of the singleton instance
     */
    public function __wakeup() {
        throw new Exception("Cannot unserialize singleton");
    }
}

/**
 * Helper function to get database connection
 * 
 * @return PDO Database connection instance
 */
function getDB(): PDO {
    return Database::getInstance()->getConnection();
}

/**
 * Helper function to get a single setting value
 * 
 * @param string $key Setting key
 * @return string|null Setting value or null if not found
 */
function getSetting(string $key): ?string {
    try {
        $db = getDB();
        $stmt = $db->prepare("SELECT setting_value FROM site_settings WHERE setting_key = ?");
        $stmt->execute([$key]);
        $result = $stmt->fetch();
        return $result ? $result['setting_value'] : null;
    } catch (PDOException $e) {
        error_log("Error fetching setting '$key': " . $e->getMessage());
        return null;
    }
}

/**
 * Helper function to update a setting value
 * 
 * @param string $key Setting key
 * @param string $value New value
 * @return bool Success status
 */
function updateSetting(string $key, string $value): bool {
    try {
        $db = getDB();
        $stmt = $db->prepare("
            UPDATE site_settings 
            SET setting_value = ?, updated_at = CURRENT_TIMESTAMP 
            WHERE setting_key = ?
        ");
        return $stmt->execute([$value, $key]);
    } catch (PDOException $e) {
        error_log("Error updating setting '$key': " . $e->getMessage());
        return false;
    }
}

/**
 * Helper function to get all settings as associative array
 * 
 * @return array Key-value pairs of all settings
 */
function getAllSettings(): array {
    try {
        $db = getDB();
        $stmt = $db->query("SELECT setting_key, setting_value FROM site_settings");
        $settings = [];
        while ($row = $stmt->fetch()) {
            $settings[$row['setting_key']] = $row['setting_value'];
        }
        return $settings;
    } catch (PDOException $e) {
        error_log("Error fetching all settings: " . $e->getMessage());
        return [];
    }
}

/**
 * Helper function to sanitize output (prevent XSS)
 * 
 * @param string $data Data to sanitize
 * @return string Sanitized data
 */
function sanitize(string $data): string {
    return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
}

/**
 * Helper function to generate SEO-friendly slugs
 * 
 * @param string $text Text to slugify
 * @return string URL-safe slug
 */
function generateSlug(string $text): string {
    // Convert to lowercase
    $text = strtolower($text);
    
    // Replace non-alphanumeric characters with hyphens
    $text = preg_replace('/[^a-z0-9]+/', '-', $text);
    
    // Remove multiple hyphens
    $text = preg_replace('/-+/', '-', $text);
    
    // Trim hyphens from ends
    $text = trim($text, '-');
    
    return $text;
}

/**
 * Helper function to format date for display
 * 
 * @param string $date Date string
 * @param string $format Desired format (default: 'F j, Y')
 * @return string Formatted date
 */
function formatDate(string $date, string $format = 'F j, Y'): string {
    return date($format, strtotime($date));
}

/**
 * Helper function to truncate text
 * 
 * @param string $text Text to truncate
 * @param int $length Maximum length
 * @param string $suffix Suffix to append (default: '...')
 * @return string Truncated text
 */
function truncateText(string $text, int $length = 150, string $suffix = '...'): string {
    // Strip HTML tags first
    $text = strip_tags($text);
    
    if (mb_strlen($text) <= $length) {
        return $text;
    }
    
    $truncated = mb_substr($text, 0, $length);
    
    // Try to cut at last space
    $lastSpace = mb_strrpos($truncated, ' ');
    if ($lastSpace !== false) {
        $truncated = mb_substr($truncated, 0, $lastSpace);
    }
    
    return $truncated . $suffix;
}

// Initialize database connection on file include
Database::getInstance();
