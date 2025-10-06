# Create optimized directory if it doesn't exist
$optimizedDir = "images/optimized"
if (-not (Test-Path $optimizedDir)) {
    New-Item -ItemType Directory -Path $optimizedDir | Out-Null
}

# Install WebP converter if not already installed
if (-not (Get-Command cwebp -ErrorAction SilentlyContinue)) {
    Write-Host "Installing WebP converter..."
    winget install -e --id Google.Imaging.WebpCodec --accept-source-agreements --accept-package-agreements
}

# Process all JPG, JPEG, and PNG files
$images = Get-ChildItem -Path "images" -Recurse -Include @("*.jpg", "*.jpeg", "*.png")
$totalSavings = 0
$totalOriginalSize = 0
$totalOptimizedSize = 0

foreach ($img in $images) {
    $originalSize = (Get-Item $img.FullName).Length
    $outputFile = Join-Path $optimizedDir "$($img.BaseName).webp"
    
    Write-Host "Processing $($img.Name)..."
    
    try {
        # Convert to WebP using cwebp
        & cwebp -q 80 "$($img.FullName)" -o $outputFile
        
        if (Test-Path $outputFile) {
            $optimizedSize = (Get-Item $outputFile).Length
            $savings = $originalSize - $optimizedSize
            $totalSavings += $savings
            $totalOriginalSize += $originalSize
            $totalOptimizedSize += $optimizedSize
            
            Write-Host "  ✓ Optimized: $([math]::Round($originalSize/1KB, 2)) KB -> $([math]::Round($optimizedSize/1KB, 2)) KB"
            Write-Host "  ✓ Savings: $([math]::Round($savings/1KB, 2)) KB ($([math]::Round(($savings / $originalSize) * 100, 2))%)"
        }
    }
    catch {
        Write-Host "  ✗ Error processing $($img.Name): $_" -ForegroundColor Red
    }
}

# Print summary
Write-Host "`nOptimization Complete!" -ForegroundColor Green
Write-Host "Processed $($images.Count) images"
Write-Host "Original size: $([math]::Round($totalOriginalSize/1MB, 2)) MB"
Write-Host "Optimized size: $([math]::Round($totalOptimizedSize/1MB, 2)) MB"
Write-Host "Total savings: $([math]::Round($totalSavings/1MB, 2)) MB ($([math]::Round(($totalSavings / $totalOriginalSize) * 100, 2))%)"
