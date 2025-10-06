const fs = require('fs');
const path = require('path');
const CleanCSS = require('clean-css');

// List of CSS files in order of priority/importance
const cssFiles = [
  'css/header-fix.css',
  'css/style.css',
  'css/header.css',
  'css/welcome.css',
  'css/news.css',
  'css/carousel-styles.css',
  'css/general.css',
  'css/footer.css',
  'css/downloads.css',
  'css/leadership.css',
  'css/fix-blue-nav.css',
  'css/nav-styles.css',
  'css/nav-fix.css',
  'css/logo-styles.css',
  'css/fix-navbar-position.css',
  'css/footer-styles.css',
  'css/force-header-fix.css',
  'css/header-lock.css',
  'css/welcome-fix.css'
];

const outputFile = 'css/optimized/styles.min.css';

// Create optimized directory if it doesn't exist
const outputDir = path.dirname(outputFile);
if (!fs.existsSync(outputDir)) {
  fs.mkdirSync(outputDir, { recursive: true });
}

// Combine all CSS files
let combinedCss = '';
cssFiles.forEach(file => {
  try {
    if (fs.existsSync(file)) {
      combinedCss += `/* ${file} */\n` + fs.readFileSync(file, 'utf8') + '\n\n';
      console.log(`✅ Added: ${file}`);
    } else {
      console.warn(`⚠️  Missing: ${file}`);
    }
  } catch (err) {
    console.error(`❌ Error reading ${file}:`, err.message);
  }
});

// Minify the combined CSS
const minifiedCss = new CleanCSS({
  level: 2,
  format: 'keep-breaks'
}).minify(combinedCss).styles;

// Write the minified CSS to file
fs.writeFileSync(outputFile, minifiedCss);
console.log(`\n✅ Successfully created: ${outputFile}`);
console.log(`Original size: ${(combinedCss.length / 1024).toFixed(2)} KB`);
console.log(`Minified size: ${(minifiedCss.length / 1024).toFixed(2)} KB`);
console.log(`Reduction: ${((1 - minifiedCss.length / combinedCss.length) * 100).toFixed(2)}%`);
