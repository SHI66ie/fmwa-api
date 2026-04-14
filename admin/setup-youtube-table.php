<?php
/**
 * Setup YouTube Videos Table
 * Creates the youtube_videos table if it doesn't exist
 */

require_once '../config.php';
require_once 'auth.php';

header('Content-Type: application/json');

$auth = new Auth($pdo);
$auth->requireLogin();

try {
    // Read and execute the SQL file
    $sqlFile = '../database/youtube_videos.sql';
    if (file_exists($sqlFile)) {
        $sql = file_get_contents($sqlFile);
        $pdo->exec($sql);
        
        echo json_encode([
            'success' => true,
            'message' => 'YouTube videos table created successfully'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'SQL file not found'
        ]);
    }
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error creating table: ' . $e->getMessage()
    ]);
}
?>
