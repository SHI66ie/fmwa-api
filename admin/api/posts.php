<?php
/**
 * Posts API Endpoint
 * Handles posts/news creation, listing, updating, and deletion
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
            handleGetPosts();
            break;
        case 'POST':
            handleCreatePost();
            break;
        case 'PUT':
            handleUpdatePost();
            break;
        case 'DELETE':
            handleDeletePost();
            break;
        default:
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
}

function handleGetPosts() {
    global $pdo;
    
    $id = $_GET['id'] ?? null;
    
    if ($id) {
        // Get single post
        $stmt = $pdo->prepare("SELECT p.*, u.username as author_name FROM posts p LEFT JOIN users u ON p.author_id = u.id WHERE p.id = ?");
        $stmt->execute([$id]);
        $post = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($post) {
            // Get categories
            $stmt = $pdo->prepare("SELECT c.* FROM categories c JOIN post_categories pc ON c.id = pc.category_id WHERE pc.post_id = ?");
            $stmt->execute([$id]);
            $post['categories'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            echo json_encode(['success' => true, 'data' => $post]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Post not found']);
        }
    } else {
        // Get all posts with pagination
        $page = $_GET['page'] ?? 1;
        $limit = $_GET['limit'] ?? 20;
        $status = $_GET['status'] ?? '';
        $search = $_GET['search'] ?? '';
        
        $offset = ($page - 1) * $limit;
        
        $whereClause = 'WHERE 1=1';
        $params = [];
        
        if ($status) {
            $whereClause .= ' AND p.status = ?';
            $params[] = $status;
        }
        
        if ($search) {
            $whereClause .= ' AND (p.title LIKE ? OR p.content LIKE ?)';
            $params[] = "%$search%";
            $params[] = "%$search%";
        }
        
        // Get total count
        $countSql = "SELECT COUNT(*) as total FROM posts p $whereClause";
        $stmt = $pdo->prepare($countSql);
        $stmt->execute($params);
        $total = $stmt->fetch()['total'];
        
        // Get posts
        $sql = "SELECT p.*, u.username as author_name FROM posts p LEFT JOIN users u ON p.author_id = u.id $whereClause ORDER BY p.created_at DESC LIMIT ? OFFSET ?";
        $params[] = $limit;
        $params[] = $offset;
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo json_encode([
            'success' => true,
            'data' => $posts,
            'pagination' => [
                'page' => (int)$page,
                'limit' => (int)$limit,
                'total' => (int)$total,
                'pages' => ceil($total / $limit)
            ]
        ]);
    }
}

function handleCreatePost() {
    global $pdo, $input, $auth;
    
    $title = $input['title'] ?? '';
    $content = $input['content'] ?? '';
    $excerpt = $input['excerpt'] ?? '';
    $status = $input['status'] ?? 'draft';
    $featured_image = $input['featured_image'] ?? '';
    $categories = $input['categories'] ?? [];
    
    if (empty($title) || empty($content)) {
        echo json_encode(['success' => false, 'message' => 'Title and content are required']);
        return;
    }
    
    $user = $auth->getCurrentUser();
    
    // Generate slug
    $slug = generateSlug($title);
    
    // Insert post
    $stmt = $pdo->prepare("INSERT INTO posts (title, slug, content, excerpt, status, featured_image, author_id, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), NOW())");
    $stmt->execute([$title, $slug, $content, $excerpt, $status, $featured_image, $user['id']]);
    
    $postId = $pdo->lastInsertId();
    
    // Add categories
    if (!empty($categories)) {
        foreach ($categories as $categoryId) {
            $stmt = $pdo->prepare("INSERT INTO post_categories (post_id, category_id) VALUES (?, ?)");
            $stmt->execute([$postId, $categoryId]);
        }
    }
    
    echo json_encode([
        'success' => true,
        'message' => 'Post created successfully',
        'data' => ['id' => $postId, 'slug' => $slug]
    ]);
}

function handleUpdatePost() {
    global $pdo, $input;
    
    $id = $input['id'] ?? null;
    
    if (!$id) {
        echo json_encode(['success' => false, 'message' => 'Post ID required']);
        return;
    }
    
    $title = $input['title'] ?? '';
    $content = $input['content'] ?? '';
    $excerpt = $input['excerpt'] ?? '';
    $status = $input['status'] ?? 'draft';
    $featured_image = $input['featured_image'] ?? '';
    $categories = $input['categories'] ?? [];
    
    if (empty($title) || empty($content)) {
        echo json_encode(['success' => false, 'message' => 'Title and content are required']);
        return;
    }
    
    // Generate new slug if title changed
    $slug = generateSlug($title);
    
    // Update post
    $stmt = $pdo->prepare("UPDATE posts SET title = ?, slug = ?, content = ?, excerpt = ?, status = ?, featured_image = ?, updated_at = NOW() WHERE id = ?");
    $stmt->execute([$title, $slug, $content, $excerpt, $status, $featured_image, $id]);
    
    // Update categories
    $pdo->prepare("DELETE FROM post_categories WHERE post_id = ?")->execute([$id]);
    
    if (!empty($categories)) {
        foreach ($categories as $categoryId) {
            $stmt = $pdo->prepare("INSERT INTO post_categories (post_id, category_id) VALUES (?, ?)");
            $stmt->execute([$id, $categoryId]);
        }
    }
    
    echo json_encode(['success' => true, 'message' => 'Post updated successfully']);
}

function handleDeletePost() {
    global $pdo;
    
    $id = $_GET['id'] ?? null;
    
    if (!$id) {
        echo json_encode(['success' => false, 'message' => 'Post ID required']);
        return;
    }
    
    // Delete categories relationship
    $pdo->prepare("DELETE FROM post_categories WHERE post_id = ?")->execute([$id]);
    
    // Delete post
    $stmt = $pdo->prepare("DELETE FROM posts WHERE id = ?");
    $stmt->execute([$id]);
    
    if ($stmt->rowCount() > 0) {
        echo json_encode(['success' => true, 'message' => 'Post deleted successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Post not found']);
    }
}

function generateSlug($title) {
    $slug = strtolower($title);
    $slug = preg_replace('/[^a-z0-9\s-]/', '', $slug);
    $slug = preg_replace('/[\s-]+/', '-', $slug);
    $slug = trim($slug, '-');
    return $slug;
}
?>
