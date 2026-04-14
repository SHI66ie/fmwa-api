<?php
/**
 * Media API Diagnostic Tool
 * Diagnoses and fixes media API issues
 */

require_once '../../config.php';

header('Content-Type: application/json');

$diagnostics = [];
$fixes = [];

try {
    // Test 1: Database Connection
    if ($pdo) {
        $diagnostics['database'] = [
            'status' => 'connected',
            'message' => 'Database connection successful'
        ];
    } else {
        $diagnostics['database'] = [
            'status' => 'failed',
            'message' => 'Database connection failed'
        ];
        throw new Exception('Database connection failed');
    }
    
    // Test 2: Media Table Existence
    $stmt = $pdo->query("SHOW TABLES LIKE 'media'");
    if ($stmt->rowCount() > 0) {
        $diagnostics['media_table'] = [
            'status' => 'missing',
            'message' => 'Media table does not exist'
        ];
        
        // Create media table
        $createTableSQL = "
            CREATE TABLE media (
                id INT AUTO_INCREMENT PRIMARY KEY,
                filename VARCHAR(255) NOT NULL,
                original_filename VARCHAR(255) NOT NULL,
                file_path VARCHAR(500) NOT NULL,
                file_url VARCHAR(500) NOT NULL,
                file_type VARCHAR(50) NOT NULL,
                file_size INT NOT NULL,
                mime_type VARCHAR(100) NOT NULL,
                title VARCHAR(255) DEFAULT NULL,
                alt_text TEXT DEFAULT NULL,
                caption TEXT DEFAULT NULL,
                uploaded_by INT NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                INDEX idx_uploaded_by (uploaded_by),
                INDEX idx_file_type (file_type),
                INDEX idx_created_at (created_at)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ";
        
        if ($pdo->exec($createTableSQL)) {
            $fixes[] = 'Created media table successfully';
            $diagnostics['media_table']['status'] = 'created';
        } else {
            throw new Exception('Failed to create media table');
        }
    } else {
        $diagnostics['media_table'] = [
            'status' => 'exists',
            'message' => 'Media table exists'
        ];
    }
    
    // Test 3: Media Table Structure
    $stmt = $pdo->query("DESCRIBE media");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $requiredColumns = ['id', 'filename', 'file_url', 'file_type', 'uploaded_by'];
    $missingColumns = [];
    
    foreach ($requiredColumns as $required) {
        $found = false;
        foreach ($columns as $column) {
            if ($column['Field'] === $required) {
                $found = true;
                break;
            }
        }
        if (!$found) {
            $missingColumns[] = $required;
        }
    }
    
    if (!empty($missingColumns)) {
        $diagnostics['table_structure'] = [
            'status' => 'incomplete',
            'missing_columns' => $missingColumns
        ];
        
        // Add missing columns
        foreach ($missingColumns as $column) {
            $columnType = 'VARCHAR(255)';
            if ($column === 'id') $columnType = 'INT AUTO_INCREMENT PRIMARY KEY';
            elseif ($column === 'uploaded_by') $columnType = 'INT NOT NULL';
            
            $alterSQL = "ALTER TABLE media ADD COLUMN $column $columnType";
            if ($pdo->exec($alterSQL)) {
                $fixes[] = "Added column: $column";
            }
        }
    } else {
        $diagnostics['table_structure'] = [
            'status' => 'complete',
            'message' => 'All required columns exist'
        ];
    }
    
    // Test 4: Upload Directory
    $uploadDir = '../../images/uploads/';
    if (!is_dir($uploadDir)) {
        $diagnostics['upload_directory'] = [
            'status' => 'missing',
            'path' => $uploadDir
        ];
        
        if (mkdir($uploadDir, 0755, true)) {
            $fixes[] = 'Created upload directory';
            $diagnostics['upload_directory']['status'] = 'created';
        }
    } else {
        throw new Exception('Cannot create upload directory');
        }
    } else {
        $diagnostics['upload_directory'] = [
            'status' => 'exists',
            'writable' => is_writable($uploadDir)
        ];
        
        if (!is_writable($uploadDir)) {
            if (chmod($uploadDir, 0755)) {
                $fixes[] = 'Fixed upload directory permissions';
                $diagnostics['upload_directory']['writable'] = 'fixed';
            }
        }
    }
    
    // Test 5: Sample Media Entry
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM media");
    $mediaCount = $stmt->fetchColumn();
    
    $diagnostics['media_count'] = [
        'count' => $mediaCount,
        'status' => $mediaCount > 0 ? 'has_data' : 'empty'
    ];
    
    // Test 6: Authentication
    $sessionStatus = session_status();
    $diagnostics['authentication'] = [
        'session_status' => $sessionStatus,
        'session_id' => session_id(),
        'logged_in' => $_SESSION['logged_in'] ?? false
    ];
    
    echo json_encode([
        'success' => true,
        'message' => 'Media API diagnostic completed',
        'diagnostics' => $diagnostics,
        'fixes_applied' => $fixes,
        'recommendations' => [
            'If database issues persist, check config.php credentials',
            'Ensure images/uploads/ directory is writable by web server',
            'Verify session is properly maintained across requests'
        ]
    ]);
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Diagnostic failed: ' . $e->getMessage(),
        'diagnostics' => $diagnostics,
        'error' => [
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString()
        ]
    ]);
}
?>
