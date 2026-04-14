<?php
/**
 * Main Configuration File
 * 
 * This file contains the core configuration for the Federal Ministry of Women Affairs website.
 * It handles database connections, path definitions, and system settings.
 */

// ------------------------------------------------------------------------
// 1. DATABASE CONFIGURATION
// ------------------------------------------------------------------------
// Database credentials - Ensure these match your cPanel environment
define('DB_HOST', 'localhost');
define('DB_NAME', 'womenaffairsgov_fmwa_db');
define('DB_USER', 'womenaffairsgov_admin');
define('DB_PASS', '@vCoLTL27N.gEfF');

// ------------------------------------------------------------------------
// 2. SYSTEM PATHS
// ------------------------------------------------------------------------
// Use absolute paths to prevent "reverting" behavior due to incorrect path resolution
if (!defined('BASE_PATH')) {
    define('BASE_PATH', __DIR__);
}

if (!defined('UPLOAD_DIR')) {
    define('UPLOAD_DIR', BASE_PATH . '/images/uploads/');
}

if (!defined('DOWNLOAD_DIR')) {
    define('DOWNLOAD_DIR', BASE_PATH . '/uploads/downloads/');
}

// ------------------------------------------------------------------------
// 3. SITE SETTINGS
// ------------------------------------------------------------------------
define('SITE_NAME', 'Federal Ministry of Women Affairs');
define('SITE_URL', 'http://' . ($_SERVER['HTTP_HOST'] ?? 'localhost'));

// ------------------------------------------------------------------------
// 4. ERROR REPORTING & ENVIRONMENT
// ------------------------------------------------------------------------
// Set to 0 for production, 1 for development
$is_development = (($_SERVER['HTTP_HOST'] ?? '') === 'localhost');

if ($is_development) {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
} else {
    error_reporting(E_ALL);
    ini_set('display_errors', '0');
}

// ------------------------------------------------------------------------
// 5. SESSION MANAGEMENT
// ------------------------------------------------------------------------
// Timezone
date_default_timezone_set('Africa/Lagos');

if (session_status() === PHP_SESSION_NONE) {
    $session_path = BASE_PATH . '/sessions';

    // Ensure the directory exists
    if (!is_dir($session_path)) {
        @mkdir($session_path, 0755, true);
    }

    // Use local session directory to avoid cPanel temporary file issues
    if (is_writable($session_path)) {
        session_save_path($session_path);
    }

    ini_set('session.cookie_httponly', 1);
    ini_set('session.use_only_cookies', 1);
    ini_set('session.cookie_secure', (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 1 : 0);
    session_start();
}

// ------------------------------------------------------------------------
// 6. DATABASE CONNECTION (PDO SINGLETON)
// ------------------------------------------------------------------------
// Initialize global PDO object
$pdo = null;
try {
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
        PDO::ATTR_PERSISTENT => true, // Use persistent connections for performance
    ];
    $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
} catch (PDOException $e) {
    error_log("Database connection failed: " . $e->getMessage());
    if (ini_get('display_errors')) {
        die("Database connection failed: " . $e->getMessage());
    } else {
        die("Database connection failed. Please contact support.");
    }
}
?>
