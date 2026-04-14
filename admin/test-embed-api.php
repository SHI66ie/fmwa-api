<?php
/**
 * Test Embed API
 * Simple test to verify embed video functionality
 */

require_once '../config.php';
require_once 'auth.php';

header('Content-Type: application/json');

$auth = new Auth($pdo);
$auth->requireLogin();

try {
    // Test if embed_videos table exists
    $stmt = $pdo->query("SHOW TABLES LIKE 'embed_videos'");
    if ($stmt->rowCount() === 0) {
        echo json_encode([
            'success' => false,
            'message' => 'embed_videos table does not exist',
            'fix' => 'Visit setup-embed-table.php to create the table'
        ]);
        exit;
    }
    
    // Test API connectivity
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM embed_videos");
    $count = $stmt->fetch()['count'];
    
    echo json_encode([
        'success' => true,
        'message' => 'Embed API is working',
        'data' => [
            'table_exists' => true,
            'embed_count' => $count,
            'api_endpoint' => 'api/embed-api.php',
            'test_embed_code' => '<iframe width="560" height="315" src="https://www.youtube.com/embed/test" title="Test" frameborder="0" allowfullscreen></iframe>'
        ]
    ]);
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error: ' . $e->getMessage()
    ]);
}
?>
