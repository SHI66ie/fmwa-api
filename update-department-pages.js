// Update all department pages with the correct Bootstrap JS bundle
const fs = require('fs');
const path = require('path');

const departmentsDir = path.join(__dirname, 'departments');
const bootstrapScript = `
    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" 
            integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" 
            crossorigin="anonymous"></script>
`;

// Function to update a single file
function updateFile(filePath) {
    try {
        let content = fs.readFileSync(filePath, 'utf8');
        
        // Check if Bootstrap JS is already included
        if (content.includes('bootstrap.bundle.min.js')) {
            // Remove any existing Bootstrap JS includes
            content = content.replace(
                /<!-- Bootstrap JS Bundle with Popper -->[\s\S]*?<script[^>]*bootstrap\.bundle\.min\.js[^>]*>[\s\S]*?<\/script>/g,
                ''
            );
        }
        
        // Add the Bootstrap JS bundle before the closing body tag
        content = content.replace(
            /(\s*<\/body>\s*<\/html>)/,
            `${bootstrapScript}$1`
        );
        
        fs.writeFileSync(filePath, content, 'utf8');
        console.log(`Updated: ${filePath}`);
    } catch (error) {
        console.error(`Error updating ${filePath}:`, error);
    }
}

// Process all HTML files in the departments directory
fs.readdir(departmentsDir, (err, files) => {
    if (err) {
        console.error('Error reading departments directory:', err);
        return;
    }
    
    files.forEach(file => {
        if (file.endsWith('.html')) {
            updateFile(path.join(departmentsDir, file));
        }
    });
    
    console.log('All department pages have been updated.');
});
