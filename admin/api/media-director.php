<?php
/**
 * Director Photo Management API
 * Connects media library to department director photos
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
            handleGetDirectorPhotos();
            break;
        case 'POST':
            handleUpdateDirectorPhoto();
            break;
        default:
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
}

function handleGetDirectorPhotos() {
    global $pdo;
    
    // Get all departments and their current director photos
    $departments = [
        'child-development' => 'Child Development',
        'community-development-social-intervention' => 'Community Development & Social Intervention',
        'economic-services' => 'Economic Services',
        'finance-accounting' => 'Finance & Accounting',
        'gender-affairs' => 'Gender Affairs',
        'general-services' => 'General Services',
        'human-resource-management' => 'Human Resource Management',
        'internal-audit' => 'Internal Audit',
        'legal-services' => 'Legal Services',
        'nutrition' => 'Nutrition',
        'planning-research-statistics' => 'Planning, Research & Statistics',
        'press' => 'Press',
        'procurement' => 'Procurement',
        'reform-coordination-service-improvement' => 'Reform Coordination & Service Improvement',
        'special-duties' => 'Special Duties',
        'women-development' => 'Women Development'
    ];
    
    $result = [];
    
    foreach ($departments as $slug => $name) {
        $photoPath = "../images/directors/{$slug}-director.jpg";
        $photoUrl = "../images/directors/{$slug}-director.jpg";
        
        // Check if photo exists
        $exists = file_exists(str_replace('../', __DIR__ . '/../../', $photoPath));
        
        // Get media info if exists in database
        $mediaInfo = null;
        if ($exists) {
            $stmt = $pdo->prepare("SELECT * FROM media WHERE file_url LIKE ?");
            $stmt->execute(["%{$slug}-director.jpg%"]);
            $mediaInfo = $stmt->fetch(PDO::FETCH_ASSOC);
        }
        
        $result[$slug] = [
            'name' => $name,
            'slug' => $slug,
            'photo_path' => $photoPath,
            'photo_url' => $photoUrl,
            'exists' => $exists,
            'media_info' => $mediaInfo
        ];
    }
    
    echo json_encode([
        'success' => true,
        'data' => $result
    ]);
}

function handleUpdateDirectorPhoto() {
    global $pdo;
    
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($input['department']) || !isset($input['media_id'])) {
        echo json_encode(['success' => false, 'message' => 'Department and media ID required']);
        return;
    }
    
    $department = $input['department'];
    $mediaId = $input['media_id'];
    
    // Validate department
    $validDepartments = [
        'child-development', 'community-development-social-intervention', 'economic-services',
        'finance-accounting', 'gender-affairs', 'general-services', 'human-resource-management',
        'internal-audit', 'legal-services', 'nutrition', 'planning-research-statistics',
        'press', 'procurement', 'reform-coordination-service-improvement', 'special-duties', 'women-development'
    ];
    
    if (!in_array($department, $validDepartments)) {
        echo json_encode(['success' => false, 'message' => 'Invalid department']);
        return;
    }
    
    // Get media info
    $stmt = $pdo->prepare("SELECT * FROM media WHERE id = ?");
    $stmt->execute([$mediaId]);
    $media = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$media) {
        echo json_encode(['success' => false, 'message' => 'Media not found']);
        return;
    }
    
    // Copy media file to directors directory
    $sourcePath = __DIR__ . '/../../' . $media['file_url'];
    $targetDir = __DIR__ . '/../../images/directors/';
    $targetFile = $targetDir . $department . '-director.jpg';
    
    // Create directors directory if it doesn't exist
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0755, true);
    }
    
    // Copy file
    if (copy($sourcePath, $targetFile)) {
        // Update media metadata to mark as director photo
        $stmt = $pdo->prepare("UPDATE media SET title = ?, caption = ?, updated_at = NOW() WHERE id = ?");
        $stmt->execute([
            "Director Photo - " . $department,
            "Department director photo for " . $department,
            $mediaId
        ]);
        
        echo json_encode([
            'success' => true,
            'message' => 'Director photo updated successfully',
            'photo_url' => "../images/directors/{$department}-director.jpg"
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to copy photo to directors directory']);
    }
}
?>
