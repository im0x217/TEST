<?php
/**
 * Common Functions Library
 * Shared utilities for the entire application
 * 
 * @version 1.0
 * @date 2026
 */

/**
 * Start session if not already started
 */
function initSession(): void {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}

/**
 * Check if user is logged in (admin)
 * 
 * @return bool Login status
 */
function isLoggedIn(): bool {
    initSession();
    return isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
}

/**
 * Redirect to another page
 * 
 * @param string $url Destination URL
 * @param int $statusCode HTTP status code (default: 302)
 */
function redirect(string $url, int $statusCode = 302): void {
    header("Location: $url", true, $statusCode);
    exit;
}

/**
 * Require login (redirect to login page if not authenticated)
 * 
 * @param string $loginUrl Login page URL (default: '/admin/login.php')
 */
function requireLogin(string $loginUrl = '/admin/login.php'): void {
    if (!isLoggedIn()) {
        redirect($loginUrl);
    }
}

/**
 * Display flash message (stored in session)
 * 
 * @param string $type Message type (success, error, info, warning)
 * @param string $message Message text
 */
function setFlashMessage(string $type, string $message): void {
    initSession();
    $_SESSION['flash_message'] = [
        'type' => $type,
        'message' => $message
    ];
}

/**
 * Get and clear flash message
 * 
 * @return array|null Flash message data or null
 */
function getFlashMessage(): ?array {
    initSession();
    if (isset($_SESSION['flash_message'])) {
        $message = $_SESSION['flash_message'];
        unset($_SESSION['flash_message']);
        return $message;
    }
    return null;
}

/**
 * Display flash message HTML
 * 
 * @return string HTML for flash message
 */
function displayFlashMessage(): string {
    $flash = getFlashMessage();
    if (!$flash) {
        return '';
    }
    
    $colors = [
        'success' => 'bg-green-100 border-green-400 text-green-700',
        'error'   => 'bg-red-100 border-red-400 text-red-700',
        'warning' => 'bg-yellow-100 border-yellow-400 text-yellow-700',
        'info'    => 'bg-blue-100 border-blue-400 text-blue-700'
    ];
    
    $colorClass = $colors[$flash['type']] ?? $colors['info'];
    
    return sprintf(
        '<div class="mb-4 border-l-4 p-4 %s" role="alert">
            <p>%s</p>
        </div>',
        $colorClass,
        sanitize($flash['message'])
    );
}

/**
 * Validate file upload (images)
 * 
 * @param array $file $_FILES array element
 * @param int $maxSize Maximum file size in bytes (default: 5MB)
 * @return array ['success' => bool, 'message' => string, 'filename' => string|null]
 */
function validateImageUpload(array $file, int $maxSize = 5242880): array {
    // Check if file was uploaded
    if (!isset($file['tmp_name']) || !is_uploaded_file($file['tmp_name'])) {
        return ['success' => false, 'message' => 'No file uploaded', 'filename' => null];
    }
    
    // Check file size
    if ($file['size'] > $maxSize) {
        return ['success' => false, 'message' => 'File too large (max 5MB)', 'filename' => null];
    }
    
    // Check file type
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mimeType = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);
    
    if (!in_array($mimeType, $allowedTypes)) {
        return ['success' => false, 'message' => 'Invalid file type. Only JPG, PNG, GIF, WebP allowed', 'filename' => null];
    }
    
    // Generate unique filename
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = uniqid('news_', true) . '.' . $extension;
    
    return ['success' => true, 'message' => 'Valid file', 'filename' => $filename];
}

/**
 * Handle file upload to uploads directory
 * 
 * @param array $file $_FILES array element
 * @param string $uploadDir Upload directory path
 * @return array ['success' => bool, 'message' => string, 'path' => string|null]
 */
function handleImageUpload(array $file, string $uploadDir = '../assets/uploads/'): array {
    $validation = validateImageUpload($file);
    
    if (!$validation['success']) {
        return ['success' => false, 'message' => $validation['message'], 'path' => null];
    }
    
    $filename = $validation['filename'];
    $destination = $uploadDir . $filename;

    // Derive asset-relative path (e.g., uploads/filename or media/filename)
    $relativeDir = str_replace(['../assets/', '..\\assets\\'], '', rtrim($uploadDir, '/\\'));
    $relativeDir = trim($relativeDir, '/\\');
    $relativePath = ($relativeDir ? $relativeDir . '/' : '') . $filename;
    
    // Create upload directory if it doesn't exist
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }
    
    // Move uploaded file
    if (move_uploaded_file($file['tmp_name'], $destination)) {
        return [
            'success' => true, 
            'message' => 'File uploaded successfully', 
            'path' => $relativePath
        ];
    }
    
    return ['success' => false, 'message' => 'Failed to move uploaded file', 'path' => null];
}

/**
 * Handle media upload (photos or videos)
 *
 * @param array $file $_FILES entry
 * @param string $type 'photo' or 'video'
 * @param string $uploadDir Upload destination
 * @return array ['success' => bool, 'message' => string, 'path' => string|null]
 */
function handleMediaUpload(array $file, string $type, string $uploadDir = '../assets/media/'): array {
    // Map upload error codes to clearer messages
    $uploadErrors = [
        UPLOAD_ERR_INI_SIZE   => 'File exceeds server limit (upload_max_filesize/post_max_size)',
        UPLOAD_ERR_FORM_SIZE  => 'File exceeds form limit',
        UPLOAD_ERR_PARTIAL    => 'Upload interrupted, please try again',
        UPLOAD_ERR_NO_FILE    => 'Please select a file',
        UPLOAD_ERR_NO_TMP_DIR => 'Server temp folder missing',
        UPLOAD_ERR_CANT_WRITE => 'Server cannot write the file',
        UPLOAD_ERR_EXTENSION  => 'Upload blocked by extension',
    ];

    if (!isset($file['error']) || $file['error'] !== UPLOAD_ERR_OK) {
        $msg = $uploadErrors[$file['error'] ?? UPLOAD_ERR_NO_FILE] ?? 'Upload error';
        return ['success' => false, 'message' => $msg, 'path' => null];
    }

    $type = $type === 'video' ? 'video' : 'photo';

    $allowedMap = [
        'photo' => ['image/jpeg', 'image/png', 'image/gif', 'image/webp'],
        'video' => ['video/mp4', 'video/webm', 'video/ogg', 'video/quicktime', 'video/mpeg']
    ];

    $maxSize = $type === 'video' ? 50 * 1024 * 1024 : 5 * 1024 * 1024; // 50MB video, 5MB photo

    if ($file['size'] <= 0 || $file['size'] > $maxSize) {
        return ['success' => false, 'message' => 'File too large', 'path' => null];
    }

    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mimeType = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);

    if (!in_array($mimeType, $allowedMap[$type], true)) {
        return ['success' => false, 'message' => 'Invalid file type', 'path' => null];
    }

    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    if (!$extension) {
        $fallbackExt = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/gif' => 'gif',
            'image/webp' => 'webp',
            'video/mp4' => 'mp4',
            'video/webm' => 'webm',
            'video/ogg' => 'ogg'
        ];
        $extension = $fallbackExt[$mimeType] ?? 'bin';
    }

    $filename = uniqid('media_', true) . '.' . $extension;
    $destination = rtrim($uploadDir, '/\\') . '/' . $filename;

    // Derive asset-relative path (e.g., media/filename)
    $relativeDir = str_replace(['../assets/', '..\\assets\\'], '', rtrim($uploadDir, '/\\'));
    $relativeDir = trim($relativeDir, '/\\');
    $relativePath = ($relativeDir ? $relativeDir . '/' : '') . $filename;

    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    if (move_uploaded_file($file['tmp_name'], $destination)) {
        return [
            'success' => true,
            'message' => 'File uploaded successfully',
            'path' => $relativePath
        ];
    }

    return ['success' => false, 'message' => 'Failed to move uploaded file', 'path' => null];
}

/**
 * Delete file from filesystem
 * 
 * @param string $path File path
 * @return bool Success status
 */
function deleteFile(string $path): bool {
    $fullPath = '../assets/' . $path;
    if (file_exists($fullPath) && is_file($fullPath)) {
        return unlink($fullPath);
    }
    return false;
}

/**
 * Get client IP address
 * 
 * @return string IP address
 */
function getClientIP(): string {
    $keys = ['HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'REMOTE_ADDR'];
    foreach ($keys as $key) {
        if (isset($_SERVER[$key]) && filter_var($_SERVER[$key], FILTER_VALIDATE_IP)) {
            return $_SERVER[$key];
        }
    }
    return '0.0.0.0';
}

/**
 * Sanitize input data (for form processing)
 * 
 * @param mixed $data Data to sanitize
 * @return mixed Sanitized data
 */
function sanitizeInput($data) {
    if (is_array($data)) {
        return array_map('sanitizeInput', $data);
    }
    return htmlspecialchars(strip_tags(trim($data)), ENT_QUOTES, 'UTF-8');
}

/**
 * Validate CSRF token
 * 
 * @param string|null $token Token to validate
 * @return bool Validation status
 */
function validateCSRFToken(?string $token): bool {
    initSession();
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token ?? '');
}

/**
 * Generate CSRF token
 * 
 * @return string CSRF token
 */
function generateCSRFToken(): string {
    initSession();
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Render CSRF input field
 * 
 * @return string HTML input field
 */
function csrfField(): string {
    $token = generateCSRFToken();
    return sprintf('<input type="hidden" name="csrf_token" value="%s">', $token);
}

/**
 * Get active template name from database
 * 
 * @return string Template name (template_1, template_2, or template_3)
 */
function getActiveTemplate(): string {
    $template = getSetting('active_template');
    return in_array($template, ['template_1', 'template_2', 'template_3']) ? $template : 'template_1';
}

/**
 * Render template file
 * 
 * @param string $templateName Template name (template_1 or template_2)
 * @param string $file File to render (index.php or news-detail.php)
 * @param array $data Data to pass to template
 */
function renderTemplate(string $templateName, string $file, array $data = []): void {
    extract($data);
    $templatePath = __DIR__ . '/../templates/' . $templateName . '/' . $file;
    
    if (file_exists($templatePath)) {
        include $templatePath;
    } else {
        die("Template not found: $templatePath");
    }
}
