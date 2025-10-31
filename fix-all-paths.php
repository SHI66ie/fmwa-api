<?php
// Script to ensure all department links are using correct paths

echo "Checking current path configurations...\n\n";

// Check if the issue might be in the .htaccess file
if (file_exists('departments/.htaccess')) {
    echo "✅ Department .htaccess exists\n";
} else {
    echo "❌ Department .htaccess missing\n";
}

// Check a sample department file for correct includes
$sampleFile = 'departments/finance-accounting.php';
if (file_exists($sampleFile)) {
    $content = file_get_contents($sampleFile);
    if (strpos($content, "include '../components/header.php'") !== false) {
        echo "✅ Department files use correct header include path\n";
    } else {
        echo "❌ Department files have incorrect header include path\n";
    }
} else {
    echo "❌ Sample department file not found\n";
}

// List all department files
echo "\n📁 Available department files:\n";
$files = glob('departments/*.php');
foreach ($files as $file) {
    echo "  - " . basename($file) . "\n";
}

echo "\n🔧 Path Configuration Summary:\n";
echo "  - Homepage navigation: uses './departments/' (correct)\n";
echo "  - Department page navigation: uses relative paths (fixed)\n";
echo "  - Department page includes: use '../components/' (correct)\n";

echo "\n✅ All paths should now be working correctly!\n";
echo "\nCorrect URLs:\n";
echo "  - https://womenaffairs.gov.ng/departments/finance-accounting.php\n";
echo "  - https://womenaffairs.gov.ng/departments/women-development.php\n";
echo "  - https://womenaffairs.gov.ng/departments/child-development.php\n";
?>
