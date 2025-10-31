<?php
// Script to create deployment package for production server

echo "🚀 Creating Production Deployment Package...\n\n";

// Create deployment directory
$deployDir = 'deployment-package';
if (!is_dir($deployDir)) {
    mkdir($deployDir, 0755, true);
    echo "✅ Created deployment directory: {$deployDir}/\n";
}

// Create components subdirectory
$componentsDir = $deployDir . '/components';
if (!is_dir($componentsDir)) {
    mkdir($componentsDir, 0755, true);
    echo "✅ Created components directory: {$componentsDir}/\n";
}

// Create departments subdirectory
$departmentsDir = $deployDir . '/departments';
if (!is_dir($departmentsDir)) {
    mkdir($departmentsDir, 0755, true);
    echo "✅ Created departments directory: {$departmentsDir}/\n";
}

// Files to copy
$filesToCopy = [
    // Root files
    'index.php' => $deployDir . '/index.php',
    '.htaccess' => $deployDir . '/.htaccess',
    
    // Component files
    'components/header.php' => $componentsDir . '/header.php',
    'components/footer.php' => $componentsDir . '/footer.php',
    'components/navigation.php' => $componentsDir . '/navigation.php',
    'components/head.php' => $componentsDir . '/head.php',
    'components/main-footer.php' => $componentsDir . '/main-footer.php',
    
    // Department .htaccess
    'departments/.htaccess' => $departmentsDir . '/.htaccess',
];

// Department PHP files
$departmentFiles = [
    'child-development.php',
    'community-development-social-intervention.php',
    'economic-services.php',
    'finance-accounting.php',
    'gender-affairs.php',
    'general-services.php',
    'human-resource-management.php',
    'nutrition.php',
    'planning-research-statistics.php',
    'procurement.php',
    'reform-coordination-service-improvement.php',
    'special-duties.php',
    'women-development.php'
];

foreach ($departmentFiles as $file) {
    $filesToCopy["departments/{$file}"] = $departmentsDir . "/{$file}";
}

// Copy files
$copiedCount = 0;
$errorCount = 0;

foreach ($filesToCopy as $source => $destination) {
    if (file_exists($source)) {
        if (copy($source, $destination)) {
            echo "✅ Copied: {$source} → {$destination}\n";
            $copiedCount++;
        } else {
            echo "❌ Failed to copy: {$source}\n";
            $errorCount++;
        }
    } else {
        echo "⚠️  Source file not found: {$source}\n";
        $errorCount++;
    }
}

// Create deployment instructions file
$instructionsFile = $deployDir . '/DEPLOYMENT_INSTRUCTIONS.txt';
$instructions = "FMWA Website - Production Deployment Instructions
=================================================

📦 UPLOAD THESE FILES TO YOUR WEB SERVER:

Root Directory Files:
- index.php → /public_html/index.php
- .htaccess → /public_html/.htaccess (REPLACE existing)

Components Directory:
- Create /public_html/components/ folder
- Upload all files from components/ folder

Departments Directory:
- Upload all .php files to /public_html/departments/
- Upload .htaccess to /public_html/departments/.htaccess

🔧 AFTER UPLOAD:

1. Set file permissions:
   - PHP files: 644 or 755
   - Directories: 755
   - .htaccess files: 644

2. Test these URLs:
   - https://womenaffairs.gov.ng/ (should serve index.php)
   - https://womenaffairs.gov.ng/index.php
   - https://womenaffairs.gov.ng/departments/women-development.php

3. Verify navigation works between pages

✅ WHAT YOU'LL GET:
- Professional PHP homepage
- 13 consistent department pages
- Unified navigation system
- Director photo placeholders
- Mobile-responsive design
- SEO-optimized structure

🚨 BACKUP FIRST:
- Backup your current index.html and .htaccess
- Keep them as rollback option

📞 SUPPORT:
If issues occur, rename index.php to index.php.backup
and restore original .htaccess to rollback.

Generated: " . date('Y-m-d H:i:s') . "
";

file_put_contents($instructionsFile, $instructions);
echo "✅ Created deployment instructions: {$instructionsFile}\n";

// Summary
echo "\n📋 DEPLOYMENT PACKAGE SUMMARY:\n";
echo "================================\n";
echo "✅ Files copied successfully: {$copiedCount}\n";
if ($errorCount > 0) {
    echo "❌ Errors encountered: {$errorCount}\n";
}
echo "📁 Package location: {$deployDir}/\n";
echo "📄 Instructions file: {$instructionsFile}\n";

echo "\n🚀 READY FOR DEPLOYMENT!\n";
echo "Upload the contents of '{$deployDir}/' to your web server.\n";
echo "Follow the instructions in DEPLOYMENT_INSTRUCTIONS.txt\n";

// List package contents
echo "\n📦 PACKAGE CONTENTS:\n";
echo "===================\n";
$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($deployDir));
foreach ($iterator as $file) {
    if ($file->isFile()) {
        $relativePath = str_replace($deployDir . DIRECTORY_SEPARATOR, '', $file->getPathname());
        echo "  - {$relativePath}\n";
    }
}

echo "\n✨ Deployment package created successfully!\n";
?>
