# Create optimized directory if it doesn't exist
$optimizedDir = "images/optimized"
if (-not (Test-Path $optimizedDir)) {
    New-Item -ItemType Directory -Path $optimizedDir | Out-Null
}

# Load Windows Imaging Component
Add-Type -AssemblyName System.Drawing

# Process all JPG, JPEG, and PNG files
$images = Get-ChildItem -Path "images" -Recurse -Include @("*.jpg", "*.jpeg", "*.png")
$totalSavings = 0
$totalOriginalSize = 0
$totalOptimizedSize = 0
$processedCount = 0

foreach ($img in $images) {
    try {
        $originalSize = (Get-Item $img.FullName).Length
        $outputFile = Join-Path $optimizedDir $img.Name
        
        Write-Host "Processing $($img.Name)..."
        
        # Skip if already optimized
        if (Test-Path $outputFile) {
            $optimizedInfo = Get-Item $outputFile
            if ($optimizedInfo.LastWriteTime -gt $img.LastWriteTime) {
                Write-Host "  ✓ Already optimized (skipping)" -ForegroundColor Gray
                continue
            }
        }
        
        # Create a bitmap from the original image
        $image = [System.Drawing.Image]::FromFile($img.FullName)
        
        # Determine image format
        if ($img.Extension -eq '.png') {
            $format = [System.Drawing.Imaging.ImageFormat]::Png
            $quality = 90
        } else {
            $format = [System.Drawing.Imaging.ImageFormat]::Jpeg
            $quality = 80
        }
        
        # Create an Encoder object for the Quality parameter
        $encoder = [System.Drawing.Imaging.ImageCodecInfo]::GetImageEncoders() | 
                  Where-Object { $_.FormatDescription -eq "JPEG" -or $_.FormatDescription -eq "PNG" } |
                  Where-Object { $_.FilenameExtension -like "*$($format.ToString().ToUpper())*" } |
                  Select-Object -First 1
                  
        $encoderParams = New-Object System.Drawing.Imaging.EncoderParameters(1)
        $encoderParams.Param[0] = New-Object System.Drawing.Imaging.EncoderParameter(
            [System.Drawing.Imaging.Encoder]::Quality, 
            [long]$quality
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
        $processedCount++
        
        Write-Host "  ✓ Optimized: $([math]::Round($originalSize/1KB, 2)) KB -> $([math]::Round($optimizedSize/1KB, 2)) KB"
        Write-Host "  ✓ Savings: $([math]::Round($savings/1KB, 2)) KB ($([math]::Round(($savings / $originalSize) * 100, 2))%)"
    } catch {
        Write-Host "  ✗ Error processing $($img.Name): $_" -ForegroundColor Red
    }
}

# Print summary
if ($processedCount -gt 0) {
    Write-Host "`nOptimization Complete!" -ForegroundColor Green
    Write-Host "Processed $processedCount images"
    Write-Host "Original size: $([math]::Round($totalOriginalSize/1MB, 2)) MB"
    Write-Host "Optimized size: $([math]::Round($totalOptimizedSize/1MB, 2)) MB"
    Write-Host "Total savings: $([math]::Round($totalSavings/1MB, 2)) MB ($([math]::Round(($totalSavings / $totalOriginalSize) * 100, 2))%)"
} else {
    Write-Host "`nNo images needed optimization." -ForegroundColor Yellow
}
