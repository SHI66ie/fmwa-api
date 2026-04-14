<?php
/**
 * Test Video Upload
 * Simple test to verify video upload functionality
 */

require_once '../config.php';
require_once 'auth.php';

header('Content-Type: application/json');

$auth = new Auth($pdo);
$auth->requireLogin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

try {
    // Check if file was uploaded
    if (!isset($_FILES['video']) || $_FILES['video']['error'] !== UPLOAD_ERR_OK) {
        echo json_encode(['success' => false, 'message' => 'No video file uploaded']);
        exit;
    }
    
    $file = $_FILES['video'];
    
    // Validate file type
    $mimeType = $file['type'] ?? '';
    if (!in_array($mimeType, ['video/mp4', 'video/webm', 'video/ogg', 'video/quicktime'])) {
        echo json_encode(['success' => false, 'message' => 'Invalid video format. Supported: MP4, WebM, OGG']);
        exit;
    }
    
    // Check file size
    if ($file['size'] > 50 * 1024 * 1024) {
        echo json_encode(['success' => false, 'message' => 'Video file too large (max 50MB)']);
        exit;
    }
    
    // Create upload directory
    $uploadDir = '../../images/uploads/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }
    
    // Generate unique filename
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = 'video_' . uniqid() . '_' . time() . '.' . $extension;
    $filepath = $uploadDir . $filename;
    
    // Move uploaded file
    if (move_uploaded_file($file['tmp_name'], $filepath)) {
        // Save to database
        $user = $auth->getCurrentUser();
        $stmt = $pdo->prepare("
            INSERT INTO media (filename, original_filename, file_path, file_url, file_type, file_size, mime_type, uploaded_by, created_at) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())
        ");
        $relativePath = 'images/uploads/' . $filename;
        $stmt->execute([
            $filename,
            $file['name'],
            $relativePath,
            $relativePath,
            'video',
            $file['size'],
            $file['type'],
            $user['id']
        ]);
        
        $mediaId = $pdo->lastInsertId();
        
        echo json_encode([
            'success' => true,
            'message' => 'Test video uploaded successfully',
            'data' => [
                'id' => $mediaId,
                'filename' => $filename,
                'url' => $relativePath,
                'original_name' => $file['name'],
                'size' => $file['size'],
                'mime_type' => $file['type']
            ]
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to upload video file']);
    }
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Upload error: ' . $e->getMessage()]);
}
?>
