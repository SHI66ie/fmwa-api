<?php
/**
 * Fix Video Files
 * Script to check and fix missing video files
 */

require_once '../config.php';
require_once 'auth.php';

header('Content-Type: application/json');

$auth = new Auth($pdo);
$auth->requireLogin();

try {
    // Get all videos from database
    $stmt = $pdo->query("SELECT * FROM media WHERE file_type = 'video' ORDER BY created_at DESC");
    $videos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $results = [];
    $missingCount = 0;
    $foundCount = 0;
    
    foreach ($videos as $video) {
        $videoPath = '../' . $video['file_url'];
        $fileExists = file_exists($videoPath);
        
        $results[] = [
            'id' => $video['id'],
            'filename' => $video['filename'],
            'original_filename' => $video['original_filename'],
            'file_url' => $video['file_url'],
            'file_path' => $videoPath,
            'file_exists' => $fileExists,
            'file_size' => $fileExists && file_exists($videoPath) ? filesize($videoPath) : 0,
            'created_at' => $video['created_at']
        ];
        
        if ($fileExists) {
            $foundCount++;
        } else {
            $missingCount++;
        }
    }
    
    echo json_encode([
        'success' => true,
        'message' => 'Video file check completed',
        'summary' => [
            'total_videos' => count($videos),
            'found_files' => $foundCount,
            'missing_files' => $missingCount
        ],
        'videos' => $results
    ]);
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error: ' . $e->getMessage()
    ]);
}
?>
