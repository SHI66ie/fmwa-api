<?php
/**
 * Media API Endpoint
 * Handles media upload, listing, updating, and deletion
 */

require_once '../../config.php';
require_once '../auth.php';

header('Content-Type: application/json');

$auth = new Auth($pdo);
$auth->requireLogin();

$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true);

try {
    switch ($method) {
        case 'GET':
            handleGetMedia();
            break;
        case 'POST':
            handleUploadMedia();
            break;
        case 'PUT':
            handleUpdateMedia();
            break;
        case 'DELETE':
            handleDeleteMedia();
            break;
        default:
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
}

function handleGetMedia() {
    global $pdo;
    
    $page = $_GET['page'] ?? 1;
    $limit = $_GET['limit'] ?? 20;
    $search = $_GET['search'] ?? '';
    
    $offset = ($page - 1) * $limit;
    
    $whereClause = '';
    $params = [];
    
    if ($search) {
        $whereClause = 'WHERE title LIKE ? OR alt_text LIKE ?';
        $params = ["%$search%", "%$search%"];
    }
    
    // Get total count
    $countSql = "SELECT COUNT(*) as total FROM media $whereClause";
    $stmt = $pdo->prepare($countSql);
    $stmt->execute($params);
    $total = $stmt->fetch()['total'];
    
    // Get media files
    $sql = "SELECT * FROM media $whereClause ORDER BY created_at DESC LIMIT ? OFFSET ?";
    $params[] = $limit;
    $params[] = $offset;
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $media = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode([
        'success' => true,
        'data' => $media,
        'pagination' => [
            'page' => (int)$page,
            'limit' => (int)$limit,
            'total' => (int)$total,
            'pages' => ceil($total / $limit)
        ]
    ]);
}

function handleUploadMedia() {
    global $pdo, $auth;
    
    if (!isset($_FILES['media'])) {
        echo json_encode(['success' => false, 'message' => 'No file uploaded']);
        return;
    }
    
    $file = $_FILES['media'];
    
    // Validate file
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/svg+xml', 'video/mp4', 'video/webm', 'application/pdf'];
    if (!in_array($file['type'], $allowedTypes)) {
        echo json_encode(['success' => false, 'message' => 'File type not allowed']);
        return;
    }
    
    // Check file size (10MB max)
    if ($file['size'] > 10 * 1024 * 1024) {
        echo json_encode(['success' => false, 'message' => 'File too large (max 10MB)']);
        return;
    }
    
    // Create upload directory if it doesn't exist
    $uploadDir = '../../images/uploads/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }
    
    // Generate unique filename
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = uniqid() . '_' . time() . '.' . $extension;
    $filepath = $uploadDir . $filename;
    
    if (move_uploaded_file($file['tmp_name'], $filepath)) {
        // Save to database
        $user = $auth->getCurrentUser();
        $stmt = $pdo->prepare("INSERT INTO media (filename, original_name, file_path, file_size, mime_type, uploaded_by, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())");
        $stmt->execute([
            $filename,
            $file['name'],
            'images/uploads/' . $filename,
            $file['size'],
            $file['type'],
            $user['id']
        ]);
        
        $mediaId = $pdo->lastInsertId();
        
        echo json_encode([
            'success' => true,
            'message' => 'File uploaded successfully',
            'data' => [
                'id' => $mediaId,
                'filename' => $filename,
                'url' => 'images/uploads/' . $filename,
                'original_name' => $file['name']
            ]
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to upload file']);
    }
}

function handleUpdateMedia() {
    global $pdo, $input;
    
    if (!isset($input['id'])) {
        echo json_encode(['success' => false, 'message' => 'Media ID required']);
        return;
    }
    
    $id = $input['id'];
    $title = $input['title'] ?? '';
    $alt_text = $input['alt_text'] ?? '';
    $caption = $input['caption'] ?? '';
    
    $stmt = $pdo->prepare("UPDATE media SET title = ?, alt_text = ?, caption = ?, updated_at = NOW() WHERE id = ?");
    $stmt->execute([$title, $alt_text, $caption, $id]);
    
    if ($stmt->rowCount() > 0) {
        echo json_encode(['success' => true, 'message' => 'Media updated successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Media not found or no changes made']);
    }
}

function handleDeleteMedia() {
    global $pdo;
    
    $id = $_GET['id'] ?? null;
    
    if (!$id) {
        echo json_encode(['success' => false, 'message' => 'Media ID required']);
        return;
    }
    
    // Get file path before deletion
    $stmt = $pdo->prepare("SELECT file_path FROM media WHERE id = ?");
    $stmt->execute([$id]);
    $media = $stmt->fetch();
    
    if (!$media) {
        echo json_encode(['success' => false, 'message' => 'Media not found']);
        return;
    }
    
    // Delete from database
    $stmt = $pdo->prepare("DELETE FROM media WHERE id = ?");
    $stmt->execute([$id]);
    
    // Delete physical file
    $filePath = '../../' . $media['file_path'];
    if (file_exists($filePath)) {
        unlink($filePath);
    }
    
    echo json_encode(['success' => true, 'message' => 'Media deleted successfully']);
}
?>
