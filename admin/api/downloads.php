<?php
require_once '../../config.php';
require_once '../auth.php';

header('Content-Type: application/json');

$auth = new Auth($pdo);
if (!$auth->isLoggedIn()) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

// Ensure table exists
try {
    $pdo->exec("CREATE TABLE IF NOT EXISTS downloads (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255) NOT NULL,
        description TEXT DEFAULT NULL,
        file_path VARCHAR(500) NOT NULL,
        file_name VARCHAR(255) NOT NULL,
        file_size INT NOT NULL,
        file_type VARCHAR(50) NOT NULL,
        download_count INT DEFAULT 0,
        uploaded_by INT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        INDEX idx_created (created_at)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");
} catch (PDOException $e) {
    // Ignore error if it already exists or keep moving
}

$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';

try {
    switch ($method) {
        case 'GET':
            if ($action === 'single') {
                $id = $_GET['id'] ?? null;
                if (!$id) {
                    throw new Exception('ID is required');
                }
                
                $stmt = $pdo->prepare("SELECT * FROM downloads WHERE id = ?");
                $stmt->execute([$id]);
                $download = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if (!$download) {
                    throw new Exception('Download not found');
                }
                
                echo json_encode(['success' => true, 'download' => $download]);
            } else {
                $stmt = $pdo->query("SELECT d.*, u.full_name as uploader_name FROM downloads d LEFT JOIN users u ON d.uploaded_by = u.id ORDER BY d.created_at DESC");
                $downloads = $stmt->fetchAll(PDO::FETCH_ASSOC);
                echo json_encode(['success' => true, 'downloads' => $downloads]);
            }
            break;

        case 'POST':
            $user = $auth->getCurrentUser();
            
            if (isset($_FILES['file'])) {
                $file = $_FILES['file'];
                $title = $_POST['title'] ?? pathinfo($file['name'], PATHINFO_FILENAME);
                $description = $_POST['description'] ?? '';
                
                if ($file['error'] !== UPLOAD_ERR_OK) {
                    $errorMsg = 'File upload failed: ';
                    switch ($file['error']) {
                        case UPLOAD_ERR_INI_SIZE:
                        case UPLOAD_ERR_FORM_SIZE:
                            $errorMsg .= 'File is too large.';
                            break;
                        case UPLOAD_ERR_PARTIAL:
                            $errorMsg .= 'The file was only partially uploaded.';
                            break;
                        case UPLOAD_ERR_NO_FILE:
                            $errorMsg .= 'No file was uploaded.';
                            break;
                        default:
                            $errorMsg .= 'Internal server error (Code: ' . $file['error'] . ')';
                    }
                    throw new Exception($errorMsg);
                }
                
                $uploadDir = '../../uploads/downloads/';
                if (!is_dir($uploadDir)) {
                    if (!@mkdir($uploadDir, 0755, true)) {
                        throw new Exception('Failed to create upload directory. Please check permissions.');
                    }
                }
                
                $fileExt = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                $allowedExts = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'zip', 'rar', '7z', 'txt', 'csv'];
                
                if (!in_array($fileExt, $allowedExts)) {
                    throw new Exception('File type not allowed. Allowed types: ' . implode(', ', $allowedExts));
                }
                
                $fileName = time() . '_' . preg_replace("/[^a-zA-Z0-9.-]/", "_", basename($file['name']));
                $filePath = $uploadDir . $fileName;
                $relativePath = 'uploads/downloads/' . $fileName;
                
                if (!@move_uploaded_file($file['tmp_name'], $filePath)) {
                    $lastError = error_get_last();
                    $errMsg = $lastError ? $lastError['message'] : 'Check directory permissions.';
                    throw new Exception('Failed to move uploaded file. ' . $errMsg);
                }
                
                $fileType = substr($file['type'] ?: 'application/octet-stream', 0, 50);
                
                $stmt = $pdo->prepare("INSERT INTO downloads (title, description, file_path, file_name, file_size, file_type, uploaded_by) VALUES (?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([
                    $title,
                    $description,
                    $relativePath,
                    $file['name'],
                    $file['size'],
                    $fileType,
                    $user['id']
                ]);
                
                echo json_encode(['success' => true, 'message' => 'File uploaded successfully']);
            } else {
                throw new Exception('No file provided');
            }
            break;

        case 'PUT':
            $data = json_decode(file_get_contents('php://input'), true);
            $id = $data['id'] ?? null;
            $title = $data['title'] ?? '';
            $description = $data['description'] ?? '';
            
            if (!$id) throw new Exception('ID is required');
            
            $stmt = $pdo->prepare("UPDATE downloads SET title = ?, description = ? WHERE id = ?");
            $stmt->execute([$title, $description, $id]);
            
            echo json_encode(['success' => true, 'message' => 'Download updated successfully']);
            break;

        case 'DELETE':
            $data = json_decode(file_get_contents('php://input'), true);
            $id = $data['id'] ?? null;
            
            if (!$id) throw new Exception('ID is required');
            
            $stmt = $pdo->prepare("SELECT file_path FROM downloads WHERE id = ?");
            $stmt->execute([$id]);
            $download = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($download) {
                // remove the file
                $fullPath = '../../' . $download['file_path'];
                if (file_exists($fullPath)) {
                    unlink($fullPath);
                }
                
                $stmt = $pdo->prepare("DELETE FROM downloads WHERE id = ?");
                $stmt->execute([$id]);
            }
            
            echo json_encode(['success' => true, 'message' => 'Download deleted successfully']);
            break;
            
        default:
            throw new Exception('Method not allowed');
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
