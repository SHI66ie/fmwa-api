<?php
/**
 * Page Management API (Admin)
 * Handles reading and writing HTML/PHP pages for the site
 */

require_once '../../config.php';
require_once '../auth.php';

header('Content-Type: application/json');

$auth = new Auth($pdo);
$auth->requireLogin();

// Base directory of the public site (one level above /admin)
if (!defined('PAGE_BASE_DIR')) {
    define('PAGE_BASE_DIR', dirname(__DIR__, 2));
}

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

try {
    switch ($method) {
        case 'GET':
            handleGetPage();
            break;
        case 'POST':
            handleSavePage();
            break;
        default:
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
}

function handleGetPage() {
    $path = $_GET['path'] ?? '';
    
    if (empty($path)) {
        // Optional: return list of available pages
        $pages = getAvailablePages();
        echo json_encode(['success' => true, 'pages' => $pages]);
        return;
    }
    
    // Security check - prevent path traversal and absolute paths
    if (strpos($path, '..') !== false || strpos($path, '/') === 0) {
        echo json_encode(['success' => false, 'message' => 'Invalid path']);
        return;
    }
    
    $fullPath = rtrim(PAGE_BASE_DIR, '/\\') . '/' . $path;
    
    if (!file_exists($fullPath)) {
        echo json_encode(['success' => false, 'message' => 'Page not found']);
        return;
    }
    
    $content = file_get_contents($fullPath);
    echo json_encode(['success' => true, 'content' => $content, 'path' => $path]);
}

function handleSavePage() {
    global $auth, $pdo;
    
    $inputRaw = file_get_contents('php://input');
    $input = json_decode($inputRaw, true);
    if (!is_array($input)) {
        echo json_encode(['success' => false, 'message' => 'Invalid JSON input']);
        return;
    }
    
    $path = $input['path'] ?? '';
    $content = $input['content'] ?? '';
    
    if ($path === '' || $content === '') {
        echo json_encode(['success' => false, 'message' => 'Path and content are required']);
        return;
    }
    
    // Security check - prevent path traversal and absolute paths
    if (strpos($path, '..') !== false || strpos($path, '/') === 0) {
        echo json_encode(['success' => false, 'message' => 'Invalid path']);
        return;
    }
    
    $fullPath = rtrim(PAGE_BASE_DIR, '/\\') . '/' . $path;
    
    // Create backup before saving
    if (file_exists($fullPath)) {
        $backupPath = $fullPath . '.backup.' . date('Y-m-d-H-i-s');
        @copy($fullPath, $backupPath);
    }
    
    // Save the file
    if (file_put_contents($fullPath, $content) !== false) {
        // Log the activity
        try {
            $user = $auth->getCurrentUser();
            $stmt = $pdo->prepare("INSERT INTO activity_log (user_id, action, description, ip_address, created_at) VALUES (?, ?, ?, ?, NOW())");
            $stmt->execute([
                $user['id'],
                'page_edit',
                "Edited page: $path",
                $_SERVER['REMOTE_ADDR'] ?? 'unknown'
            ]);
        } catch (Exception $e) {
            // Log error but don't fail the save operation
            error_log('Failed to log activity in page editor: ' . $e->getMessage());
        }
        
        echo json_encode(['success' => true, 'message' => 'Page saved successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to save page']);
    }
}

function getAvailablePages() {
    $pages = [];
    $base = rtrim(PAGE_BASE_DIR, '/\\') . '/';
    
    // Main pages
    $mainPages = ['index.php', 'about.php', 'mandate.php', 'organogram.php'];
    foreach ($mainPages as $page) {
        if (file_exists($base . $page)) {
            $pages[] = $page;
        }
    }
    
    // Department pages
    $deptDir = $base . 'departments/';
    if (is_dir($deptDir)) {
        $files = scandir($deptDir);
        foreach ($files as $file) {
            if (pathinfo($file, PATHINFO_EXTENSION) === 'php') {
                $pages[] = 'departments/' . $file;
            }
        }
    }
    
    return $pages;
}
