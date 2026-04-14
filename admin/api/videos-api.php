<?php
/**
 * Videos API Endpoint
 * Handles video-specific operations
 */

require_once '../../config.php';
require_once '../auth.php';

header('Content-Type: application/json');

$auth = new Auth($pdo);
$auth->requireLogin();

$method = $_SERVER['REQUEST_METHOD'];

try {
    switch ($method) {
        case 'GET':
            handleGetVideos();
            break;
        case 'POST':
            handleVideoUpload();
            break;
        case 'DELETE':
            handleDeleteVideo();
            break;
        default:
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    }
} catch (Exception $e) {
    http_response_code(500);
    error_log('Videos API Error: ' . $e->getMessage() . ' in ' . $e->getFile() . ' on line ' . $e->getLine());
    
    echo json_encode([
        'success' => false, 
        'message' => 'Server error: ' . $e->getMessage(),
        'debug' => [
            'file' => $e->getFile(),
            'line' => $e->getLine()
        ]
    ]);
}

function handleGetVideos() {
    global $pdo;
    
    $page = $_GET['page'] ?? 1;
    $limit = $_GET['limit'] ?? 20;
    $search = $_GET['search'] ?? '';
    
    $offset = ($page - 1) * $limit;
    
    $whereClause = 'WHERE file_type = "video"';
    $params = [];
    
    if ($search) {
        $whereClause .= ' AND (title LIKE ? OR original_filename LIKE ?)';
        $params = ["%$search%", "%$search%"];
    }
    
    // Get total count
    $countSql = "SELECT COUNT(*) as total FROM media $whereClause";
    $stmt = $pdo->prepare($countSql);
    $stmt->execute($params);
    $total = $stmt->fetch()['total'];
    
    // Get videos
    $sql = "SELECT * FROM media $whereClause ORDER BY created_at DESC LIMIT ? OFFSET ?";
    $params[] = $limit;
    $params[] = $offset;
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $videos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode([
        'success' => true,
        'data' => $videos,
        'pagination' => [
            'page' => (int)$page,
            'limit' => (int)$limit,
            'total' => (int)$total,
            'pages' => ceil($total / $limit)
        ]
    ]);
}

function handleVideoUpload() {
    global $pdo, $auth;
    
    // Check if file was uploaded
    if (!isset($_FILES['video']) || $_FILES['video']['error'] !== UPLOAD_ERR_OK) {
        echo json_encode(['success' => false, 'message' => 'No video file uploaded']);
        return;
    }
    
    $file = $_FILES['video'];
    
    // Validate file type (video only)
    $mimeType = $file['type'] ?? '';
    if (!in_array($mimeType, ['video/mp4', 'video/webm', 'video/ogg', 'video/quicktime'])) {
        echo json_encode(['success' => false, 'message' => 'Invalid video format. Supported: MP4, WebM, OGG']);
        return;
    }
    
    // Check file size (50MB max for videos)
    if ($file['size'] > 50 * 1024 * 1024) {
        echo json_encode(['success' => false, 'message' => 'Video file too large (max 50MB)']);
        return;
    }
    
    // Create upload directory if it doesn't exist
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
            'message' => 'Video uploaded successfully',
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
}

function handleDeleteVideo() {
    global $pdo;
    
    $videoId = $_GET['id'] ?? null;
    
    if (!$videoId) {
        echo json_encode(['success' => false, 'message' => 'Video ID required']);
        return;
    }
    
    // Get video info
    $stmt = $pdo->prepare("SELECT * FROM media WHERE id = ? AND file_type = 'video'");
    $stmt->execute([$videoId]);
    $video = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$video) {
        echo json_encode(['success' => false, 'message' => 'Video not found']);
        return;
    }
    
    // Delete physical file
    $filePath = '../../' . $video['file_url'];
    if (file_exists($filePath)) {
        unlink($filePath);
    }
    
    // Delete from database
    $stmt = $pdo->prepare("DELETE FROM media WHERE id = ?");
    $stmt->execute([$videoId]);
    
    echo json_encode([
        'success' => true,
        'message' => 'Video deleted successfully'
    ]);
}
?>
