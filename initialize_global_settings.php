<?php
require_once 'config.php';

$settings = [
    // Contact Info
    ['contact_email', 'info@fmwa.gov.ng', 'string', 'Primary Contact Email'],
    ['contact_phone', '+234-9-461-0000', 'string', 'Primary Contact Phone'],
    ['contact_address', "Federal Secretariat Complex\nShehu Shagari Way, Abuja", 'text', 'Office Address'],
    ['contact_hours', 'Mon - Fri: 8:00 AM - 4:00 PM', 'string', 'Opening Hours'],
    
    // Social Media
    ['social_facebook', 'https://www.facebook.com/FMWAngr', 'string', 'Facebook Page URL'],
    ['social_twitter', 'https://x.com/FMWA_ng', 'string', 'Twitter/X Profile URL'],
    ['social_instagram', 'https://www.instagram.com/FMWAngr', 'string', 'Instagram Profile URL'],
    ['social_youtube', 'http://www.youtube.com/@fmwangr', 'string', 'YouTube Channel URL'],
    
    // Website Text
    ['footer_about_text', "Empowering women and promoting gender equality across Nigeria through comprehensive policies, programs, and initiatives that advance the welfare of women and children.", 'text', 'About text shown in footer'],
];

foreach ($settings as $setting) {
    try {
        $stmt = $pdo->prepare("INSERT INTO settings (setting_key, setting_value, setting_type, description) 
                             VALUES (?, ?, ?, ?) 
                             ON DUPLICATE KEY UPDATE description = VALUES(description)");
        $stmt->execute($setting);
        echo "Set {$setting[0]}\n";
    } catch (Exception $e) {
        echo "Error setting {$setting[0]}: " . $e->getMessage() . "\n";
    }
}

echo "Global settings initialization complete!";
?>
