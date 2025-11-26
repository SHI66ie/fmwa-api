<?php
// TEMP: show errors for debugging this 500
ini_set('display_errors', 1);
error_reporting(E_ALL);

/**
 * Page Management API
 * Handles reading and writing HTML pages
 */

require_once '../config.php';
require_once '../admin/auth.php';

header('Content-Type: application/json');

$auth = new Auth($pdo);
$auth->requireLogin();

$method = $_SERVER['REQUEST_METHOD'];

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
        // Return list of available pages
        $pages = getAvailablePages();
        echo json_encode(['success' => true, 'pages' => $pages]);
        return;
    }
    
    // Security check - prevent path traversal
    if (strpos($path, '..') !== false || strpos($path, '/') === 0) {
        echo json_encode(['success' => false, 'message' => 'Invalid path']);
        return;
    }
    
    $fullPath = '../' . $path;
    
    if (!file_exists($fullPath)) {
        echo json_encode(['success' => false, 'message' => 'Page not found']);
        return;
    }
    
    $content = file_get_contents($fullPath);
    echo json_encode(['success' => true, 'content' => $content, 'path' => $path]);
}

function handleSavePage() {
    $input = json_decode(file_get_contents('php://input'), true);
    
    $path = $input['path'] ?? '';
    $content = $input['content'] ?? '';
    
    if (empty($path) || empty($content)) {
        echo json_encode(['success' => false, 'message' => 'Path and content are required']);
        return;
    }
    
    // Security check - prevent path traversal
    if (strpos($path, '..') !== false || strpos($path, '/') === 0) {
        echo json_encode(['success' => false, 'message' => 'Invalid path']);
        return;
    }
    
    $fullPath = '../' . $path;
    
    // Create backup before saving
    if (file_exists($fullPath)) {
        $backupPath = $fullPath . '.backup.' . date('Y-m-d-H-i-s');
        copy($fullPath, $backupPath);
    }
    
    // Save the file
    if (file_put_contents($fullPath, $content) !== false) {
        // Log the activity
        global $auth, $pdo;
        $user = $auth->getCurrentUser();
        
        try {
            $stmt = $pdo->prepare("INSERT INTO activity_log (user_id, action, description, ip_address, created_at) VALUES (?, ?, ?, ?, NOW())");
            $stmt->execute([
                $user['id'],
                'page_edit',
                "Edited page: $path",
                $_SERVER['REMOTE_ADDR'] ?? 'unknown'
            ]);
        } catch (Exception $e) {
            // Log error but don't fail the save operation
            error_log("Failed to log activity: " . $e->getMessage());
        }
        
        echo json_encode(['success' => true, 'message' => 'Page saved successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to save page']);
    }
}

function getAvailablePages() {
    $pages = [];
    
    // Main pages
    $mainPages = ['index.php', 'about.php', 'mandate.php', 'organogram.php'];
    foreach ($mainPages as $page) {
        if (file_exists('../' . $page)) {
            $pages[] = $page;
        }
    }
    
    // Department pages
    $deptDir = '../departments/';
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
?>
