# Create optimized directory if it doesn't exist
$optimizedDir = "images/optimized"
if (-not (Test-Path $optimizedDir)) {
    New-Item -ItemType Directory -Path $optimizedDir | Out-Null
}

# Load Windows Imaging Component
Add-Type -AssemblyName System.Drawing

# Process all JPG, JPEG, and PNG files
$images = Get-ChildItem -Path "images" -Recurse -Include @("*.jpg", "*.jpeg", "*.png")

foreach ($img in $images) {
    try {
        $outputFile = Join-Path $optimizedDir $img.Name
        
        # Skip if already optimized
        if (Test-Path $outputFile) {
            Write-Host "Skipping $($img.Name) - already optimized"
            continue
        }
        
        Write-Host "Optimizing $($img.Name)..."
        
        # Create a bitmap from the original image
        $image = [System.Drawing.Image]::FromFile($img.FullName)
        
        # Save with quality settings
        if ($img.Extension -eq '.png') {
            $image.Save($outputFile, [System.Drawing.Imaging.ImageFormat]::Png)
        } else {
            $image.Save($outputFile, [System.Drawing.Imaging.ImageFormat]::Jpeg)
        }
        
        # Clean up
        $image.Dispose()
        
        Write-Host "  ✓ Optimized: $($img.Name)"
    } catch {
        Write-Host "  ✗ Error processing $($img.Name): $_" -ForegroundColor Red
    }
}

Write-Host "Optimization complete!" -ForegroundColor Green
