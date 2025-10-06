# Create optimized directory if it doesn't exist
$optimizedDir = "images/optimized"
if (-not (Test-Path $optimizedDir)) {
    New-Item -ItemType Directory -Path $optimizedDir | Out-Null
}

# Process all JPG, JPEG, and PNG files
$images = Get-ChildItem -Path "images" -Recurse -Include @("*.jpg", "*.jpeg", "*.png")
$totalSavings = 0
$totalOriginalSize = 0
$totalOptimizedSize = 0

# Load Windows Imaging Component
Add-Type -AssemblyName System.Drawing

foreach ($img in $images) {
    try {
        $originalSize = (Get-Item $img.FullName).Length
        $outputFile = Join-Path $optimizedDir $img.Name
        $outputFile = [System.IO.Path]::ChangeExtension($outputFile, ".jpg")
        
        Write-Host "Processing $($img.Name)..."
        
        # Create a bitmap from the original image
        $image = [System.Drawing.Image]::FromFile($img.FullName)
        
        # Create an Encoder object for the Quality parameter
        $encoder = [System.Drawing.Imaging.ImageCodecInfo]::GetImageEncoders() | 
                  Where-Object { $_.FormatDescription -eq "JPEG" }
                  
        $encoderParams = New-Object System.Drawing.Imaging.EncoderParameters(1)
        $encoderParams.Param[0] = New-Object System.Drawing.Imaging.EncoderParameter(
            [System.Drawing.Imaging.Encoder]::Quality, 
            [long]80  # 80% quality
        )
        
        # Save the image with quality settings
        $image.Save($outputFile, $encoder, $encoderParams)
        
        # Clean up
        $image.Dispose()
        
        $optimizedSize = (Get-Item $outputFile).Length
        $savings = $originalSize - $optimizedSize
        $totalSavings += $savings
        $totalOriginalSize += $originalSize
        $totalOptimizedSize += $optimizedSize
        
        Write-Host "  ✓ Optimized: $([math]::Round($originalSize/1KB, 2)) KB -> $([math]::Round($optimizedSize/1KB, 2)) KB"
        Write-Host "  ✓ Savings: $([math]::Round($savings/1KB, 2)) KB ($([math]::Round(($savings / $originalSize) * 100, 2))%)"
    }
    catch {
        Write-Host "  ✗ Error processing $($img.Name): $_" -ForegroundColor Red
    }
}

# Print summary
if ($totalOriginalSize -gt 0) {
    Write-Host "`nOptimization Complete!" -ForegroundColor Green
    Write-Host "Processed $($images.Count) images"
    Write-Host "Original size: $([math]::Round($totalOriginalSize/1MB, 2)) MB"
    Write-Host "Optimized size: $([math]::Round($totalOptimizedSize/1MB, 2)) MB"
    Write-Host "Total savings: $([math]::Round($totalSavings/1MB, 2)) MB ($([math]::Round(($totalSavings / $totalOriginalSize) * 100, 2))%)"
} else {
    Write-Host "No images were processed. Please check the images directory." -ForegroundColor Yellow
}
