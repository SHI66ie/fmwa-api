<?php
/**
 * Helper functions for FMWA Website
 */

/**
 * Get a setting value from the database
 * 
 * @param string $key Setting key
 * @param mixed $default Default value if not found
 * @return mixed
 */
function get_setting($key, $default = null) {
    global $pdo;
    
    if (!$pdo) {
        return $default;
    }
    
    try {
        static $settings_cache = [];
        
        if (empty($settings_cache)) {
            $stmt = $pdo->query("SELECT setting_key, setting_value, setting_type FROM settings WHERE is_autoload = 1");
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $value = $row['setting_value'];
                // Cast based on type
                if ($row['setting_type'] === 'boolean') {
                    $value = ($value === 'true' || $value === '1');
                } elseif ($row['setting_type'] === 'number') {
                    $value = (float) $value;
                } elseif ($row['setting_type'] === 'json') {
                    $value = json_decode($value, true);
                }
                $settings_cache[$row['setting_key']] = $value;
            }
        }
        
        if (isset($settings_cache[$key])) {
            return $settings_cache[$key];
        }
        
        // Fallback for non-autoloaded settings
        $stmt = $pdo->prepare("SELECT setting_value, setting_type FROM settings WHERE setting_key = ?");
        $stmt->execute([$key]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($row) {
            $value = $row['setting_value'];
            if ($row['setting_type'] === 'boolean') {
                $value = ($value === 'true' || $value === '1');
            } elseif ($row['setting_type'] === 'number') {
                $value = (float) $value;
            } elseif ($row['setting_type'] === 'json') {
                $value = json_decode($value, true);
            }
            return $value;
        }
        
        return $default;
    } catch (Exception $e) {
        error_log("Error fetching setting '$key': " . $e->getMessage());
        return $default;
    }
}
?>
