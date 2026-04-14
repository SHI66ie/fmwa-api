<?php
/**
 * Test Director Photos Page
 * Debug script to test director photos functionality
 */

require_once 'config.php';
require_once 'auth.php';

header('Content-Type: application/json');

echo json_encode([
    'test' => 'Director Photos API connectivity test',
    'timestamp' => date('Y-m-d H:i:s'),
    'php_version' => PHP_VERSION,
    'server_info' => [
        'REQUEST_METHOD' => $_SERVER['REQUEST_METHOD'] ?? 'not set',
        'SCRIPT_NAME' => $_SERVER['SCRIPT_NAME'] ?? 'not set',
        'REQUEST_URI' => $_SERVER['REQUEST_URI'] ?? 'not set',
        'HTTP_HOST' => $_SERVER['HTTP_HOST'] ?? 'not set'
    ],
    'database_status' => [
        'connected' => $pdo ? true : false,
        'config_loaded' => defined('DB_HOST') ? true : false
    ],
    'auth_status' => [
        'session_status' => session_status(),
        'session_id' => session_id(),
        'logged_in' => $_SESSION['logged_in'] ?? false
    ],
    'api_endpoints' => [
        'director_photos_api' => file_exists('api/media-director.php'),
        'media_api' => file_exists('api/media.php'),
        'director_photos_page' => file_exists('director-photos.php')
    ]
]);
?>
