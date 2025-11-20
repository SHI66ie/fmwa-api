<?php
// Database configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'womenaffairsgov_fmwa_db');
define('DB_USER', 'womenaffairsgov_admin');
define('DB_PASS', '@vCoLTL27N.gEfF');

// Application paths
define('BASE_PATH', realpath(dirname(__DIR__)));
define('UPLOAD_DIR', BASE_PATH . '/images/uploads/');

// Site settings
define('SITE_NAME', 'Federal Ministry of Women Affairs');
define('SITE_URL', 'http://' . ($_SERVER['HTTP_HOST'] ?? 'localhost'));

// Error reporting (disable in production for security)
error_reporting(E_ALL);
ini_set('display_errors', '1');

// Timezone
date_default_timezone_set('Africa/Lagos');

// Session settings - Fix for cPanel
if (session_status() === PHP_SESSION_NONE) {
    // Set custom session path for cPanel compatibility
    $session_path = sys_get_temp_dir();
    if (!is_writable($session_path)) {
        $session_path = dirname(__FILE__) . '/sessions';
        if (!file_exists($session_path)) {
            @mkdir($session_path, 0755, true);
        }
    }
    
    if (is_writable($session_path)) {
        ini_set('session.save_path', $session_path);
    }
    
    ini_set('session.cookie_httponly', 1);
    ini_set('session.use_only_cookies', 1);
    ini_set('session.cookie_secure', isset($_SERVER['HTTPS']) ? 1 : 0);
    session_start();
}

// Database connection
$pdo = null;
try {
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];
    $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
} catch (PDOException $e) {
    // Log error but don't expose details to users
    error_log("Database connection failed: " . $e->getMessage());
    if (ini_get('display_errors')) {
        die("Database connection failed: " . $e->getMessage());
    } else {
        die("Database connection failed. Please contact support.");
    }
}
?>
