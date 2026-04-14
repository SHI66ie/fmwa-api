<?php
/**
 * Test Settings Fix
 * Run this to verify the settings fix works
 */

require_once 'config.php';

header('Content-Type: application/json');

try {
    // Test the UPSERT operation
    $testKey = 'test_setting_' . time();
    $testValue = 'test_value_' . time();
    
    // First insert
    $stmt = $pdo->prepare("
        INSERT INTO settings (setting_key, setting_value, setting_type) 
        VALUES (?, ?, 'string')
        ON DUPLICATE KEY UPDATE 
        setting_value = VALUES(setting_value),
        updated_at = CURRENT_TIMESTAMP
    ");
    $stmt->execute([$testKey, $testValue]);
    
    // Verify it was inserted
    $stmt = $pdo->prepare("SELECT setting_value FROM settings WHERE setting_key = ?");
    $stmt->execute([$testKey]);
    $insertedValue = $stmt->fetchColumn();
    
    // Now test update (same key, different value)
    $newValue = 'updated_value_' . time();
    $stmt->execute([$testKey, $newValue]);
    
    // Verify it was updated
    $stmt->execute([$testKey]);
    $updatedValue = $stmt->fetchColumn();
    
    // Clean up test data
    $stmt = $pdo->prepare("DELETE FROM settings WHERE setting_key = ?");
    $stmt->execute([$testKey]);
    
    echo json_encode([
        'success' => true,
        'message' => 'Settings UPSERT test completed successfully',
        'test_results' => [
            'inserted_value' => $insertedValue === $testValue,
            'updated_value' => $updatedValue === $newValue,
            'test_passed' => ($insertedValue === $testValue && $updatedValue === $newValue)
        ],
        'database_info' => [
            'total_settings' => $pdo->query("SELECT COUNT(*) FROM settings")->fetchColumn(),
            'unique_keys' => $pdo->query("SELECT COUNT(DISTINCT setting_key) FROM settings")->fetchColumn()
        ]
    ]);
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Test failed: ' . $e->getMessage(),
        'debug' => [
            'file' => $e->getFile(),
            'line' => $e->getLine()
        ]
    ]);
}
?>
