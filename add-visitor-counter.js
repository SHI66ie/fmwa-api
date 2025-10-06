const fs = require('fs');
const path = require('path');

// Directory containing department pages
const departmentsDir = path.join(__dirname, 'departments');

// Get all HTML files in the departments directory
const files = fs.readdirSync(departmentsDir).filter(file => 
    file.endsWith('.html') && !file.includes('.backup')
);

console.log(`Found ${files.length} department pages to update...`);

files.forEach(file => {
    try {
        const filePath = path.join(departmentsDir, file);
        let content = fs.readFileSync(filePath, 'utf8');
        let modified = false;
        
        // Check if visitor counter CSS is already added
        if (!content.includes('visitor-counter.css')) {
            // Add CSS link after welcome-banner.css
            content = content.replace(
                /<link rel="stylesheet" href="\.\.\/css\/welcome-banner\.css">/,
                `<link rel="stylesheet" href="../css/welcome-banner.css">
    <link rel="stylesheet" href="../css/visitor-counter.css">`
            );
            console.log(`✓ Added visitor counter CSS to ${file}`);
            modified = true;
        }
        
        // Check if visitor counter JS is already added
        if (!content.includes('visitor-counter.js')) {
            // Add JS script after header.js or components include
            if (content.includes('header.js')) {
                content = content.replace(
                    /<script src="\.\.\/components\/header\.js" defer><\/script>/,
                    `<script src="../components/header.js" defer></script>
    <script src="../js/visitor-counter.js" defer></script>`
                );
            } else if (content.includes('include-components.js')) {
                content = content.replace(
                    /<script src="\.\.\/js\/include-components\.js" defer><\/script>/,
                    `<script src="../js/include-components.js" defer></script>
    <script src="../js/visitor-counter.js" defer></script>`
                );
            }
            console.log(`✓ Added visitor counter JS to ${file}`);
            modified = true;
        }
        
        // Check if visitor counter div is already added
        if (!content.includes('id="visitor-counter"')) {
            // Add visitor counter div before closing body tag
            content = content.replace(
                /<\/body>/,
                `    <!-- Visitor Counter Display -->
    <div id="visitor-counter"></div>

</body>`
            );
            console.log(`✓ Added visitor counter div to ${file}`);
            modified = true;
        }
        
        // Write the updated content back to the file only if modified
        if (modified) {
            fs.writeFileSync(filePath, content, 'utf8');
            console.log(`✅ Successfully updated ${file}\n`);
        } else {
            console.log(`⏭️  Skipped ${file} (already has visitor counter)\n`);
        }
    } catch (error) {
        console.error(`❌ Error processing ${file}:`, error.message);
    }
});

console.log('\n✅ All department pages updated successfully!');
console.log('\nNext steps:');
console.log('1. Start the server: npm start');
console.log('2. Visit your website to see the visitor counter in action');
console.log('3. The counter will appear in the bottom-right corner of each page');
