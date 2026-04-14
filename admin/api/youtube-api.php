<?php
/**
 * YouTube Video API
 * Handles YouTube video embedding and management
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
            handleGetYouTubeVideos();
            break;
        case 'POST':
            handleAddYouTubeVideo();
            break;
        case 'DELETE':
            handleDeleteYouTubeVideo();
            break;
        default:
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    }
} catch (Exception $e) {
    http_response_code(500);
    error_log('YouTube API Error: ' . $e->getMessage() . ' in ' . $e->getFile() . ' on line ' . $e->getLine());
    
    echo json_encode([
        'success' => false, 
        'message' => 'Server error: ' . $e->getMessage(),
        'debug' => [
            'file' => $e->getFile(),
            'line' => $e->getLine()
        ]
    ]);
}

function handleGetYouTubeVideos() {
    global $pdo;
    
    $page = $_GET['page'] ?? 1;
    $limit = $_GET['limit'] ?? 20;
    $search = $_GET['search'] ?? '';
    
    $offset = ($page - 1) * $limit;
    
    $whereClause = 'WHERE video_type = "youtube"';
    $params = [];
    
    if ($search) {
        $whereClause .= ' AND (title LIKE ? OR description LIKE ?)';
        $params = ["%$search%", "%$search%"];
    }
    
    // Get total count
    $countSql = "SELECT COUNT(*) as total FROM youtube_videos $whereClause";
    $stmt = $pdo->prepare($countSql);
    $stmt->execute($params);
    $total = $stmt->fetch()['total'];
    
    // Get videos
    $sql = "SELECT * FROM youtube_videos $whereClause ORDER BY created_at DESC LIMIT ? OFFSET ?";
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

function handleAddYouTubeVideo() {
    global $pdo, $auth;
    
    $input = json_decode(file_get_contents('php://input'), true);
    
    $youtubeUrl = $input['youtube_url'] ?? '';
    $title = $input['title'] ?? '';
    $description = $input['description'] ?? '';
    
    if (empty($youtubeUrl)) {
        echo json_encode(['success' => false, 'message' => 'YouTube URL is required']);
        return;
    }
    
    // Extract YouTube video ID
    $videoId = extractYouTubeVideoId($youtubeUrl);
    if (!$videoId) {
        echo json_encode(['success' => false, 'message' => 'Invalid YouTube URL']);
        return;
    }
    
    // Get video info from YouTube API (optional)
    $videoInfo = getYouTubeVideoInfo($videoId);
    
    // Save to database
    $user = $auth->getCurrentUser();
    $stmt = $pdo->prepare("
        INSERT INTO youtube_videos (video_id, title, description, youtube_url, thumbnail_url, uploaded_by, created_at) 
        VALUES (?, ?, ?, ?, ?, ?, NOW())
    ");
    
    $stmt->execute([
        $videoId,
        $title ?: ($videoInfo['title'] ?? 'YouTube Video'),
        $description ?: ($videoInfo['description'] ?? ''),
        $youtubeUrl,
        $videoInfo['thumbnail'] ?? '',
        $user['id']
    ]);
    
    $videoDbId = $pdo->lastInsertId();
    
    echo json_encode([
        'success' => true,
        'message' => 'YouTube video added successfully',
        'data' => [
            'id' => $videoDbId,
            'video_id' => $videoId,
            'title' => $title ?: ($videoInfo['title'] ?? 'YouTube Video'),
            'description' => $description ?: ($videoInfo['description'] ?? ''),
            'youtube_url' => $youtubeUrl,
            'thumbnail_url' => $videoInfo['thumbnail'] ?? '',
            'embed_url' => "https://www.youtube.com/embed/$videoId"
        ]
    ]);
}

function handleDeleteYouTubeVideo() {
    global $pdo;
    
    $videoId = $_GET['id'] ?? null;
    
    if (!$videoId) {
        echo json_encode(['success' => false, 'message' => 'Video ID required']);
        return;
    }
    
    $stmt = $pdo->prepare("DELETE FROM youtube_videos WHERE id = ?");
    $stmt->execute([$videoId]);
    
    echo json_encode([
        'success' => true,
        'message' => 'YouTube video deleted successfully'
    ]);
}

function extractYouTubeVideoId($url) {
    $patterns = [
        '/youtube\.com\/watch\?v=([a-zA-Z0-9_-]{11})/',
        '/youtube\.com\/embed\/([a-zA-Z0-9_-]{11})/',
        '/youtu\.be\/([a-zA-Z0-9_-]{11})/',
        '/youtube\.com\/v\/([a-zA-Z0-9_-]{11})/'
    ];
    
    foreach ($patterns as $pattern) {
        if (preg_match($pattern, $url, $matches)) {
            return $matches[1];
        }
    }
    
    return null;
}

function getYouTubeVideoInfo($videoId) {
    // Try to get thumbnail (no API key required for basic info)
    $thumbnailUrl = "https://img.youtube.com/vi/$videoId/mqdefault.jpg";
    
    // Check if thumbnail exists
    $headers = @get_headers($thumbnailUrl);
    if ($headers && strpos($headers[0], '200') !== false) {
        return [
            'thumbnail' => $thumbnailUrl,
            'title' => 'YouTube Video',
            'description' => ''
        ];
    }
    
    return [
        'thumbnail' => '',
        'title' => 'YouTube Video',
        'description' => ''
    ];
}
?>
