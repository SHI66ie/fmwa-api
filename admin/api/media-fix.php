<?php
/**
 * Media Upload Fix Script
 * Ensures upload directory exists and is writable
 */

require_once '../../config.php';

header('Content-Type: application/json');

try {
    $uploadDir = '../../images/uploads/';
    
    // Create directory if it doesn't exist
    if (!is_dir($uploadDir)) {
        if (!mkdir($uploadDir, 0755, true)) {
            throw new Exception('Could not create upload directory');
        }
    }
    
    // Check if directory is writable
    if (!is_writable($uploadDir)) {
        // Try to fix permissions
        if (!chmod($uploadDir, 0755)) {
            throw new Exception('Upload directory is not writable and permissions could not be fixed');
        }
    }
    
    // Create .htaccess file if it doesn't exist
    $htaccessFile = $uploadDir . '.htaccess';
    if (!file_exists($htaccessFile)) {
        $htaccessContent = "
# Allow access to uploaded media files
<Files \"*\">
    Order allow,deny
    Allow from all
</Files>

# Set proper content types
<IfModule mod_mime.c>
    AddType image/jpeg .jpg .jpeg
    AddType image/png .png
    AddType image/gif .gif
    AddType image/webp .webp
    AddType image/svg+xml .svg
    AddType video/mp4 .mp4
    AddType video/webm .webm
    AddType application/pdf .pdf
</IfModule>

# Prevent PHP execution in uploads directory
<Files \"*.php\">
    Deny from all
</Files>
";
        if (file_put_contents($htaccessFile, $htaccessContent) === false) {
            throw new Exception('Could not create .htaccess file');
        }
    }
    
    // Create directors directory if it doesn't exist
    $directorsDir = '../../images/directors/';
    if (!is_dir($directorsDir)) {
        if (!mkdir($directorsDir, 0755, true)) {
            throw new Exception('Could not create directors directory');
        }
    }
    
    // Check database connection
    if (!$pdo) {
        throw new Exception('Database connection failed');
    }
    
    // Test media table
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM media");
    $mediaCount = $stmt->fetch()['count'];
    
    echo json_encode([
        'success' => true,
        'message' => 'Media system is ready',
        'data' => [
            'upload_dir' => $uploadDir,
            'writable' => is_writable($uploadDir),
            'htaccess_exists' => file_exists($htaccessFile),
            'directors_dir' => $directorsDir,
            'media_count' => $mediaCount,
            'php_version' => PHP_VERSION,
            'upload_max_filesize' => ini_get('upload_max_filesize'),
            'post_max_size' => ini_get('post_max_size')
        ]
    ]);
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage(),
        'debug' => [
            'upload_dir' => $uploadDir ?? 'not set',
            'exists' => isset($uploadDir) && is_dir($uploadDir),
            'writable' => isset($uploadDir) && is_writable($uploadDir),
            'php_version' => PHP_VERSION
        ]
    ]);
}
?>
