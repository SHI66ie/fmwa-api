using System;
using System.Drawing;
using System.Drawing.Imaging;
using System.IO;
using System.Linq;

class Program
{
    static void Main()
    {
        string sourceDir = "images";
        string outputDir = Path.Combine("images", "optimized");
        
        if (!Directory.Exists(outputDir))
            Directory.CreateDirectory(outputDir);

        var imageFiles = Directory.GetFiles(sourceDir, "*.jpg", SearchOption.AllDirectories)
                                .Concat(Directory.GetFiles(sourceDir, "*.jpeg", SearchOption.AllDirectories))
                                .Concat(Directory.GetFiles(sourceDir, "*.png", SearchOption.AllDirectories))
                                .ToArray();

        long totalOriginalSize = 0;
        long totalOptimizedSize = 0;

        Console.WriteLine($"Found {imageFiles.Length} images to process.\n");

        foreach (var imagePath in imageFiles)
        {
            try
            {
                string relativePath = Path.GetRelativePath(sourceDir, Path.GetDirectoryName(imagePath));
                string outputPath = Path.Combine(outputDir, relativePath, Path.GetFileNameWithoutExtension(imagePath) + ".webp");
                
                // Create directory if it doesn't exist
                Directory.CreateDirectory(Path.GetDirectoryName(outputPath));

                // Get original file size
                var fileInfo = new FileInfo(imagePath);
                long originalSize = fileInfo.Length;
                totalOriginalSize += originalSize;

                Console.WriteLine($"Processing: {Path.GetFileName(imagePath)}");
                Console.WriteLine($"Original size: {originalSize / 1024f:F2} KB");

                // Skip if already processed
                if (File.Exists(outputPath))
                {
                    var optimizedInfo = new FileInfo(outputPath);
                    Console.WriteLine($"Already optimized: {optimizedInfo.Length / 1024f:F2} KB\n");
                    totalOptimizedSize += optimizedInfo.Length;
                    continue;
                }

                // Load and optimize image
                using (var image = Image.FromFile(imagePath))
                using (var ms = new MemoryStream())
                {
                    // Save as WebP with quality 80
                    var encoder = ImageCodecInfo.GetImageDecoders()
                        .First(c => c.FormatID == ImageFormat.Jpeg.Guid);
                    
                    var encoderParams = new EncoderParameters(1);
                    encoderParams.Param[0] = new EncoderParameter(Encoder.Quality, 80L);
                    
                    // Save as JPEG for now (WebP requires additional libraries)
                    string tempFile = Path.ChangeExtension(outputPath, ".jpg");
                    image.Save(tempFile, encoder, encoderParams);
                    
                    // Get optimized size
                    var optimizedInfo = new FileInfo(tempFile);
                    long optimizedSize = optimizedInfo.Length;
                    totalOptimizedSize += optimizedSize;
                    
                    // Rename to original name
                    File.Move(tempFile, outputPath, true);
                    
                    Console.WriteLine($"Optimized size: {optimizedSize / 1024f:F2} KB");
                    Console.WriteLine($"Savings: {(originalSize - optimizedSize) / 1024f:F2} KB ({(1 - (float)optimizedSize / originalSize) * 100:F2}%)\n");
                }
            }
            catch (Exception ex)
            {
                Console.WriteLine($"Error processing {imagePath}: {ex.Message}");
            }
        }

        // Print summary
        Console.WriteLine("\nOptimization Complete!");
        Console.WriteLine($"Processed {imageFiles.Length} images");
        Console.WriteLine($"Original size: {totalOriginalSize / 1024f / 1024f:F2} MB");
        Console.WriteLine($"Optimized size: {totalOptimizedSize / 1024f / 1024f:F2} MB");
        Console.WriteLine($"Total savings: {(totalOriginalSize - totalOptimizedSize) / 1024f / 1024f:F2} MB ({(1 - (float)totalOptimizedSize / totalOriginalSize) * 100:F2}%)");
    }
}
