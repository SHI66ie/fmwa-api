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
    error_log('Media API Error: ' . $e->getMessage() . ' in ' . $e->getFile() . ' on line ' . $e->getLine());
    echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage(), 'debug' => [
        'file' => $e->getFile(),
        'line' => $e->getLine()
    ]]);
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
    
    // Support both 'file' (frontend) and 'media' (legacy) field names
    $field = null;
    if (isset($_FILES['file'])) {
        $field = 'file';
    } elseif (isset($_FILES['media'])) {
        $field = 'media';
    }

    if ($field === null) {
        error_log('Media upload failed: No file field found. FILES: ' . print_r($_FILES, true));
        echo json_encode(['success' => false, 'message' => 'No file uploaded']);
        return;
    }
    
    $file = $_FILES[$field];
    
    // Check for upload errors
    if ($file['error'] !== UPLOAD_ERR_OK) {
        $errorMessages = [
            UPLOAD_ERR_INI_SIZE => 'File exceeds upload_max_filesize in php.ini',
            UPLOAD_ERR_FORM_SIZE => 'File exceeds MAX_FILE_SIZE in HTML form',
            UPLOAD_ERR_PARTIAL => 'File was only partially uploaded',
            UPLOAD_ERR_NO_FILE => 'No file was uploaded',
            UPLOAD_ERR_NO_TMP_DIR => 'Missing temporary upload folder',
            UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk (check permissions)',
            UPLOAD_ERR_EXTENSION => 'Upload stopped by PHP extension'
        ];
        $message = $errorMessages[$file['error']] ?? 'Unknown upload error';
        error_log('Media upload failed: ' . $message . ' (Error code: ' . $file['error'] . ')');
        echo json_encode(['success' => false, 'message' => $message, 'error_code' => $file['error']]);
        return;
    }
    
    // Validate file - allow any image/*, any video/* and PDF
    $mimeType = $file['type'] ?? '';
    $isImage = strpos($mimeType, 'image/') === 0;
    $isVideo = strpos($mimeType, 'video/') === 0;
    $isPdf   = ($mimeType === 'application/pdf');

    if (!$isImage && !$isVideo && !$isPdf) {
        // Log unexpected mime type for debugging
        error_log('Media upload blocked. Mime type: ' . $mimeType . ' Name: ' . ($file['name'] ?? '')); 
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
        if (!mkdir($uploadDir, 0755, true)) {
            error_log('Media upload failed: Could not create directory ' . $uploadDir);
            echo json_encode(['success' => false, 'message' => 'Upload directory does not exist and could not be created']);
            return;
        }
    }
    
    // Check if directory is writable
    if (!is_writable($uploadDir)) {
        error_log('Media upload failed: Directory not writable ' . $uploadDir);
        echo json_encode(['success' => false, 'message' => 'Upload directory is not writable. Please check folder permissions.']);
        return;
    }
    
    // Generate unique filename
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = uniqid() . '_' . time() . '.' . $extension;
    $filepath = $uploadDir . $filename;
    
    // Attempt to move uploaded file
    error_log('Attempting to move uploaded file from ' . $file['tmp_name'] . ' to ' . $filepath);
    
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
        $error = error_get_last();
        $errorMsg = $error ? $error['message'] : 'Unknown error';
        error_log('Media upload failed: move_uploaded_file() failed. From: ' . $file['tmp_name'] . ' To: ' . $filepath . ' Error: ' . $errorMsg);
        echo json_encode([
            'success' => false, 
            'message' => 'Failed to move uploaded file. Check server permissions and paths.',
            'debug' => [
                'tmp_file' => $file['tmp_name'],
                'target_path' => $filepath,
                'upload_dir' => $uploadDir,
                'dir_exists' => is_dir($uploadDir),
                'dir_writable' => is_writable($uploadDir)
            ]
        ]);
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
    global $pdo, $input;
    
    // Allow ID via query string or JSON body
    $id = $_GET['id'] ?? ($input['id'] ?? null);
    
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
