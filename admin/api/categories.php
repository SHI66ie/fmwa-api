<?php
/**
 * Categories API Endpoint
 * Handles category creation, listing, updating, and deletion
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
            handleGetCategories();
            break;
        case 'POST':
            handleCreateCategory();
            break;
        case 'PUT':
            handleUpdateCategory();
            break;
        case 'DELETE':
            handleDeleteCategory();
            break;
        default:
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    }
} catch (Exception $e) {
    http_response_code(500);
    error_log('Categories API Error: ' . $e->getMessage() . ' in ' . $e->getFile() . ' on line ' . $e->getLine());
    echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage(), 'debug' => [
        'file' => $e->getFile(),
        'line' => $e->getLine()
    ]]);
}

function handleGetCategories() {
    global $pdo;
    
    $id = $_GET['id'] ?? null;
    
    if ($id) {
        // Get single category
        $stmt = $pdo->prepare("SELECT c.*, COUNT(pc.post_id) as post_count FROM categories c LEFT JOIN post_categories pc ON c.id = pc.category_id WHERE c.id = ? GROUP BY c.id");
        $stmt->execute([$id]);
        $category = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($category) {
            echo json_encode(['success' => true, 'data' => $category]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Category not found']);
        }
    } else {
        // Get all categories
        $stmt = $pdo->query("SELECT c.*, COUNT(pc.post_id) as post_count FROM categories c LEFT JOIN post_categories pc ON c.id = pc.category_id GROUP BY c.id ORDER BY c.name");
        $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo json_encode(['success' => true, 'data' => $categories]);
    }
}

function handleCreateCategory() {
    global $pdo, $input;
    
    $name = $input['name'] ?? '';
    $description = $input['description'] ?? '';
    $status = $input['status'] ?? 'active';
    
    if (empty($name)) {
        echo json_encode(['success' => false, 'message' => 'Category name is required']);
        return;
    }
    
    // Check if category already exists
    $stmt = $pdo->prepare("SELECT id FROM categories WHERE name = ?");
    $stmt->execute([$name]);
    if ($stmt->fetch()) {
        echo json_encode(['success' => false, 'message' => 'Category already exists']);
        return;
    }
    
    // Generate slug
    $slug = generateSlug($name);
    
    // Insert category
    $stmt = $pdo->prepare("INSERT INTO categories (name, slug, description, status, created_at, updated_at) VALUES (?, ?, ?, ?, NOW(), NOW())");
    $stmt->execute([$name, $slug, $description, $status]);
    
    $categoryId = $pdo->lastInsertId();
    
    echo json_encode([
        'success' => true,
        'message' => 'Category created successfully',
        'data' => ['id' => $categoryId, 'slug' => $slug]
    ]);
}

function handleUpdateCategory() {
    global $pdo, $input;
    
    $id = $input['id'] ?? null;
    
    if (!$id) {
        echo json_encode(['success' => false, 'message' => 'Category ID required']);
        return;
    }
    
    $name = $input['name'] ?? '';
    $description = $input['description'] ?? '';
    $status = $input['status'] ?? 'active';
    
    if (empty($name)) {
        echo json_encode(['success' => false, 'message' => 'Category name is required']);
        return;
    }
    
    // Check if another category with same name exists
    $stmt = $pdo->prepare("SELECT id FROM categories WHERE name = ? AND id != ?");
    $stmt->execute([$name, $id]);
    if ($stmt->fetch()) {
        echo json_encode(['success' => false, 'message' => 'Category name already exists']);
        return;
    }
    
    // Generate new slug
    $slug = generateSlug($name);
    
    // Update category
    $stmt = $pdo->prepare("UPDATE categories SET name = ?, slug = ?, description = ?, status = ?, updated_at = NOW() WHERE id = ?");
    $stmt->execute([$name, $slug, $description, $status, $id]);
    
    if ($stmt->rowCount() > 0) {
        echo json_encode(['success' => true, 'message' => 'Category updated successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Category not found or no changes made']);
    }
}

function handleDeleteCategory() {
    global $pdo;
    
    $id = $_GET['id'] ?? null;
    
    if (!$id) {
        echo json_encode(['success' => false, 'message' => 'Category ID required']);
        return;
    }
    
    // Check if category has posts
    $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM post_categories WHERE category_id = ?");
    $stmt->execute([$id]);
    $postCount = $stmt->fetch()['count'];
    
    if ($postCount > 0) {
        echo json_encode(['success' => false, 'message' => 'Cannot delete category with posts. Remove posts first.']);
        return;
    }
    
    // Delete category
    $stmt = $pdo->prepare("DELETE FROM categories WHERE id = ?");
    $stmt->execute([$id]);
    
    if ($stmt->rowCount() > 0) {
        echo json_encode(['success' => true, 'message' => 'Category deleted successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Category not found']);
    }
}

function generateSlug($name) {
    $slug = strtolower($name);
    $slug = preg_replace('/[^a-z0-9\s-]/', '', $slug);
    $slug = preg_replace('/[\s-]+/', '-', $slug);
    $slug = trim($slug, '-');
    return $slug;
}
?>
