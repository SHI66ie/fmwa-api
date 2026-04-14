<?php
/**
 * Embed Video API
 * Handles embedded video links (iframe, embed codes)
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
            handleGetEmbedVideos();
            break;
        case 'POST':
            handleAddEmbedVideo();
            break;
        case 'DELETE':
            handleDeleteEmbedVideo();
            break;
        default:
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    }
} catch (Exception $e) {
    http_response_code(500);
    error_log('Embed API Error: ' . $e->getMessage() . ' in ' . $e->getFile() . ' on line ' . $e->getLine());
    
    echo json_encode([
        'success' => false, 
        'message' => 'Server error: ' . $e->getMessage(),
        'debug' => [
            'file' => $e->getFile(),
            'line' => $e->getLine()
        ]
    ]);
}

function handleGetEmbedVideos() {
    global $pdo;
    
    $page = $_GET['page'] ?? 1;
    $limit = $_GET['limit'] ?? 20;
    $search = $_GET['search'] ?? '';
    
    $offset = ($page - 1) * $limit;
    
    $whereClause = 'WHERE video_type = "embed"';
    $params = [];
    
    if ($search) {
        $whereClause .= ' AND (title LIKE ? OR description LIKE ?)';
        $params = ["%$search%", "%$search%"];
    }
    
    // Get total count
    $countSql = "SELECT COUNT(*) as total FROM embed_videos $whereClause";
    $stmt = $pdo->prepare($countSql);
    $stmt->execute($params);
    $total = $stmt->fetch()['total'];
    
    // Get videos
    $sql = "SELECT * FROM embed_videos $whereClause ORDER BY created_at DESC LIMIT ? OFFSET ?";
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

function handleAddEmbedVideo() {
    global $pdo, $auth;
    
    $input = json_decode(file_get_contents('php://input'), true);
    
    $embedCode = $input['embed_code'] ?? '';
    $title = $input['title'] ?? '';
    $description = $input['description'] ?? '';
    
    if (empty($embedCode)) {
        echo json_encode(['success' => false, 'message' => 'Embed code is required']);
        return;
    }
    
    // Validate embed code (basic iframe validation)
    if (!preg_match('/<iframe[^>]*>/i', $embedCode)) {
        echo json_encode(['success' => false, 'message' => 'Invalid embed code. Please provide a valid iframe embed code.']);
        return;
    }
    
    // Extract source URL from iframe
    $srcUrl = '';
    if (preg_match('/src=["\']([^"\']+)["\']/', $embedCode, $matches)) {
        $srcUrl = $matches[1];
    }
    
    // Save to database
    $user = $auth->getCurrentUser();
    $stmt = $pdo->prepare("
        INSERT INTO embed_videos (title, description, embed_code, source_url, uploaded_by, created_at) 
        VALUES (?, ?, ?, ?, ?, NOW())
    ");
    
    $stmt->execute([
        $title ?: 'Embedded Video',
        $description ?: '',
        $embedCode,
        $srcUrl,
        $user['id']
    ]);
    
    $videoDbId = $pdo->lastInsertId();
    
    echo json_encode([
        'success' => true,
        'message' => 'Embedded video added successfully',
        'data' => [
            'id' => $videoDbId,
            'title' => $title ?: 'Embedded Video',
            'description' => $description ?: '',
            'embed_code' => $embedCode,
            'source_url' => $srcUrl
        ]
    ]);
}

function handleDeleteEmbedVideo() {
    global $pdo;
    
    $videoId = $_GET['id'] ?? null;
    
    if (!$videoId) {
        echo json_encode(['success' => false, 'message' => 'Video ID required']);
        return;
    }
    
    $stmt = $pdo->prepare("DELETE FROM embed_videos WHERE id = ?");
    $stmt->execute([$videoId]);
    
    echo json_encode([
        'success' => true,
        'message' => 'Embedded video deleted successfully'
    ]);
}
?>
