<?php
// Database configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'womenaffairsgov_fmwa_db');
define('DB_USER', 'womenaffairsgov_admin');
define('DB_PASS', 'YOUR_SECURE_PASSWORD');

// Application paths
define('BASE_PATH', realpath(dirname(__DIR__)));
define('UPLOAD_DIR', BASE_PATH . '/images/uploads/');

// Site settings
define('SITE_NAME', 'Federal Ministry of Women Affairs');
define('SITE_URL', 'http://' . ($_SERVER['HTTP_HOST'] ?? 'localhost'));

// Error reporting (disable in production)
error_reporting(E_ALL);
ini_set('display_errors', '1');

// Timezone
date_default_timezone_set('Africa/Lagos');

// Session settings (only if session not started)
if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.cookie_httponly', 1);
    ini_set('session.use_only_cookies', 1);
    ini_set('session.cookie_secure', isset($_SERVER['HTTPS']) ? 1 : 0);
    session_start();
}
?>
