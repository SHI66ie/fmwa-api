<?php
/**
 * Settings Fix Script
 * Resolves duplicate key issues and ensures proper settings table structure
 */

require_once '../../config.php';

header('Content-Type: application/json');

try {
    // Check if settings table exists and has proper structure
    $stmt = $pdo->query("SHOW TABLES LIKE 'settings'");
    if ($stmt->rowCount() === 0) {
        throw new Exception('Settings table does not exist');
    }
    
    // Get table structure
    $stmt = $pdo->query("DESCRIBE settings");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Check if we have the required columns
    $hasId = false;
    $hasKey = false;
    $hasValue = false;
    $hasType = false;
    
    foreach ($columns as $column) {
        switch ($column['Field']) {
            case 'id':
                $hasId = true;
                break;
            case 'setting_key':
                $hasKey = true;
                break;
            case 'setting_value':
                $hasValue = true;
                break;
            case 'setting_type':
                $hasType = true;
                break;
        }
    }
    
    // Fix any existing duplicate keys
    $stmt = $pdo->query("
        SELECT setting_key, COUNT(*) as count 
        FROM settings 
        GROUP BY setting_key 
        HAVING count > 1
    ");
    
    $duplicates = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $fixes = [];
    
    foreach ($duplicates as $duplicate) {
        $key = $duplicate['setting_key'];
        
        // Keep the most recent entry (highest ID)
        $stmt = $pdo->prepare("
            SELECT id FROM settings 
            WHERE setting_key = ? 
            ORDER BY id DESC 
            LIMIT 1
        ");
        $stmt->execute([$key]);
        $keepId = $stmt->fetchColumn();
        
        // Delete all older duplicates
        $stmt = $pdo->prepare("
            DELETE FROM settings 
            WHERE setting_key = ? AND id != ?
        ");
        $stmt->execute([$key, $keepId]);
        
        $fixes[] = "Removed duplicates for '$key', keeping ID $keepId";
    }
    
    // Ensure default settings exist
    $defaultSettings = [
        'site_name' => 'Federal Ministry of Women Affairs',
        'site_description' => 'Official website of Federal Ministry of Women Affairs',
        'site_logo' => 'images/2025_07_14_13_42_IMG_2808.PNG',
        'contact_email' => 'info@womenaffairs.gov.ng',
        'contact_phone' => '+234-9-461-0000',
        'maintenance_mode' => 'false'
    ];
    
    $insertDefaults = [];
    foreach ($defaultSettings as $key => $value) {
        $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM settings WHERE setting_key = ?");
        $stmt->execute([$key]);
        $exists = $stmt->fetchColumn();
        
        if (!$exists) {
            $insertDefaults[] = $key;
            $stmt = $pdo->prepare("
                INSERT INTO settings (setting_key, setting_value, setting_type) 
                VALUES (?, ?, 'string')
            ");
            $stmt->execute([$key, $value]);
        }
    }
    
    echo json_encode([
        'success' => true,
        'message' => 'Settings table fixed successfully',
        'data' => [
            'duplicates_fixed' => $fixes,
            'defaults_added' => $insertDefaults,
            'total_settings' => $pdo->query("SELECT COUNT(*) FROM settings")->fetchColumn(),
            'table_structure' => [
                'has_id' => $hasId,
                'has_key' => $hasKey,
                'has_value' => $hasValue,
                'has_type' => $hasType
            ]
        ]
    ]);
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error fixing settings: ' . $e->getMessage(),
        'debug' => [
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString()
        ]
    ]);
}
?>
