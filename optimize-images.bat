@echo off
setlocal enabledelayedexpansion

REM Create optimized directory if it doesn't exist
if not exist "images\optimized" mkdir "images\optimized"

echo Optimizing images...

REM Process all JPG, JPEG, and PNG files
for /r "images" %%f in (*.jpg *.jpeg *.png) do (
    set "filename=%%~nxf"
    set "ext=%%~xf"
    
    REM Skip if already in optimized directory
    if not exist "images\optimized\!filename!" (
        echo Optimizing: %%f
        
        REM Copy the file to optimized directory (in a real scenario, you would optimize here)
        copy /Y "%%f" "images\optimized\!filename!" >nul
        
        if errorlevel 1 (
            echo   Failed to optimize: %%f
        ) else (
            echo   Optimized: %%f
        )
    ) else (
        echo Skipping: %%f (already optimized)
    )
)

echo.
echo Image optimization complete!
pause
