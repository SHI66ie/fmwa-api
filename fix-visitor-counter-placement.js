const fs = require('fs');
const path = require('path');

console.log('🔧 Fixing visitor counter placement in all HTML files...\n');

// Function to fix a single file
function fixFile(filePath, relativePath) {
    try {
        let content = fs.readFileSync(filePath, 'utf8');
        let modified = false;
        
        // Check if visitor counter div exists
        if (!content.includes('id="visitor-counter"')) {
            console.log(`⏭️  Skipped ${relativePath} (no visitor counter)`);
            return;
        }
        
        // Pattern 1: Counter after </html> tag (WRONG)
        if (content.match(/<div id="visitor-counter"><\/div>\s*<\/html>/)) {
            content = content.replace(
                /(<div id="visitor-counter"><\/div>)\s*(<\/html>)/,
                '$1\n\n</body>\n$2'
            );
            modified = true;
            console.log(`✅ Fixed ${relativePath} - Added missing </body> tag`);
        }
        
        // Pattern 2: Counter before </html> but no </body> (WRONG)
        else if (content.includes('<div id="visitor-counter"></div>') && 
                 content.includes('</html>') && 
                 !content.includes('</body>')) {
            content = content.replace(
                /(<div id="visitor-counter"><\/div>)\s*(<\/html>)/,
                '$1\n\n</body>\n$2'
            );
            modified = true;
            console.log(`✅ Fixed ${relativePath} - Added missing </body> tag`);
        }
        
        // Pattern 3: Counter after </body> tag (WRONG)
        else if (content.match(/<\/body>\s*<div id="visitor-counter"><\/div>/)) {
            content = content.replace(
                /(<\/body>)\s*(<div id="visitor-counter"><\/div>)/,
                '$2\n\n$1'
            );
            modified = true;
            console.log(`✅ Fixed ${relativePath} - Moved counter before </body>`);
        }
        
        // Pattern 4: Check if properly placed (CORRECT)
        else if (content.match(/<div id="visitor-counter"><\/div>\s*<\/body>/)) {
            console.log(`✓ ${relativePath} - Already correct`);
            return;
        }
        
        // Write back if modified
        if (modified) {
            fs.writeFileSync(filePath, content, 'utf8');
        }
        
    } catch (error) {
        console.error(`❌ Error processing ${relativePath}:`, error.message);
    }
}

// Fix index.html
const indexPath = path.join(__dirname, 'index.html');
if (fs.existsSync(indexPath)) {
    fixFile(indexPath, 'index.html');
}

// Fix all department pages
const departmentsDir = path.join(__dirname, 'departments');
if (fs.existsSync(departmentsDir)) {
    const files = fs.readdirSync(departmentsDir).filter(file => 
        file.endsWith('.html') && !file.includes('.backup')
    );
    
    files.forEach(file => {
        const filePath = path.join(departmentsDir, file);
        fixFile(filePath, `departments/${file}`);
    });
}

// Fix other main pages
const mainPages = ['about.html', 'mandate.html', 'organogram.html', 'womendevelopment.html'];
mainPages.forEach(page => {
    const pagePath = path.join(__dirname, page);
    if (fs.existsSync(pagePath)) {
        fixFile(pagePath, page);
    }
});

console.log('\n✅ Visitor counter placement check complete!');
console.log('\n📝 Summary:');
console.log('- All visitor counter divs should now be placed BEFORE </body> tag');
console.log('- This ensures proper HTML structure and functionality');
console.log('\n🧪 Test: Visit http://localhost:3000 to see the counter in action!');
