const fs = require('fs').promises;
const path = require('path');
const sharp = require('sharp');
const { promisify } = require('util');
const glob = promisify(require('glob'));

// Configuration
const CONFIG = {
  imageFormats: ['jpg', 'jpeg', 'png'],
  quality: 80,
  webpQuality: 75,
  widths: [400, 800, 1200],
  srcDir: 'images',
  distDir: 'images/optimized'
};

// Ensure output directory exists
async function ensureDir(dir) {
  try {
    await fs.mkdir(dir, { recursive: true });
  } catch (err) {
    if (err.code !== 'EEXIST') throw err;
  }
}

// Process a single image
async function processImage(filePath) {
  const parsed = path.parse(filePath);
  const outputDir = path.join(CONFIG.distDir, path.dirname(filePath).replace(CONFIG.srcDir, ''));
  await ensureDir(outputDir);

  const originalSize = (await fs.stat(filePath)).size;
  let totalSavings = 0;
  let processedFiles = [];

  try {
    // Process original format with quality reduction
    const optimizedPath = path.join(outputDir, `${parsed.name}${parsed.ext}`);
    await sharp(filePath)
      .jpeg({ quality: CONFIG.quality, progressive: true, force: false })
      .png({ quality: CONFIG.quality, progressive: true, force: false })
      .toFile(optimizedPath);
    
    const optimizedSize = (await fs.stat(optimizedPath)).size;
    totalSavings += originalSize - optimizedSize;
    processedFiles.push({
      file: path.relative(process.cwd(), optimizedPath),
      originalSize,
      optimizedSize,
      savings: originalSize - optimizedSize,
      format: parsed.ext.replace('.', '')
    });

    // Create WebP version
    const webpPath = path.join(outputDir, `${parsed.name}.webp`);
    await sharp(filePath)
      .webp({ quality: CONFIG.webpQuality })
      .toFile(webpPath);
    
    const webpSize = (await fs.stat(webpPath)).size;
    totalSavings += originalSize - webpSize;
    processedFiles.push({
      file: path.relative(process.cwd(), webpPath),
      originalSize,
      optimizedSize: webpSize,
      savings: originalSize - webpSize,
      format: 'webp'
    });

    // Create responsive sizes
    for (const width of CONFIG.widths) {
      const responsivePath = path.join(outputDir, `${parsed.name}-${width}w${parsed.ext}`);
      const responsiveWebpPath = path.join(outputDir, `${parsed.name}-${width}w.webp`);
      
      const image = sharp(filePath);
      const metadata = await image.metadata();
      
      if (metadata.width > width) {
        // Create responsive size in original format
        await image
          .resize(width)
          .jpeg({ quality: CONFIG.quality, progressive: true, force: false })
          .png({ quality: CONFIG.quality, progressive: true, force: false })
          .toFile(responsivePath);
        
        const responsiveSize = (await fs.stat(responsivePath)).size;
        totalSavings += originalSize - responsiveSize;
        processedFiles.push({
          file: path.relative(process.cwd(), responsivePath),
          originalSize,
          optimizedSize: responsiveSize,
          savings: originalSize - responsiveSize,
          format: parsed.ext.replace('.', ''),
          width
        });

        // Create responsive WebP
        await sharp(filePath)
          .resize(width)
          .webp({ quality: CONFIG.webpQuality })
          .toFile(responsiveWebpPath);
        
        const responsiveWebpSize = (await fs.stat(responsiveWebpPath)).size;
        totalSavings += originalSize - responsiveWebpSize;
        processedFiles.push({
          file: path.relative(process.cwd(), responsiveWebpPath),
          originalSize,
          optimizedSize: responsiveWebpSize,
          savings: originalSize - responsiveWebpSize,
          format: 'webp',
          width
        });
      }
    }

    return { processedFiles, totalSavings };
  } catch (error) {
    console.error(`Error processing ${filePath}:`, error);
    return { processedFiles, totalSavings, error };
  }
}

// Main function
async function main() {
  console.log('Starting image optimization...');
  
  // Create optimized directory if it doesn't exist
  await ensureDir(CONFIG.distDir);
  
  // Find all image files
  const patterns = CONFIG.imageFormats.map(ext => `${CONFIG.srcDir}/**/*.${ext}`);
  const files = [];
  
  for (const pattern of patterns) {
    const matches = await glob(pattern, { nodir: true });
    files.push(...matches);
  }
  
  if (files.length === 0) {
    console.log('No images found to optimize.');
    return;
  }
  
  console.log(`Found ${files.length} images to process.`);
  
  let totalOriginalSize = 0;
  let totalOptimizedSize = 0;
  let processedCount = 0;
  
  // Process each image
  for (const file of files) {
    console.log(`Processing: ${file}`);
    const result = await processImage(file);
    
    if (result.processedFiles.length > 0) {
      const originalSize = result.processedFiles[0].originalSize;
      const optimizedSize = result.processedFiles.reduce((sum, f) => sum + f.optimizedSize, 0);
      
      totalOriginalSize += originalSize;
      totalOptimizedSize += optimizedSize;
      processedCount++;
      
      console.log(`  ✓ Created ${result.processedFiles.length} optimized versions`);
      console.log(`  ✓ Original: ${(originalSize / 1024).toFixed(2)} KB`);
      console.log(`  ✓ Optimized: ${(optimizedSize / 1024).toFixed(2)} KB`);
      console.log(`  ✓ Savings: ${(result.totalSavings / 1024).toFixed(2)} KB`);
    }
  }
  
  // Print summary
  console.log('\nOptimization complete!');
  console.log(`Processed ${processedCount} images`);
  console.log(`Total original size: ${(totalOriginalSize / 1024 / 1024).toFixed(2)} MB`);
  console.log(`Total optimized size: ${(totalOptimizedSize / 1024 / 1024).toFixed(2)} MB`);
  console.log(`Total savings: ${((totalOriginalSize - totalOptimizedSize) / 1024 / 1024).toFixed(2)} MB (${((1 - totalOptimizedSize / totalOriginalSize) * 100).toFixed(2)}% reduction)`);
}

// Run the script
main().catch(console.error);
