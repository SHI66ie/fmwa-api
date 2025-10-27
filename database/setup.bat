@echo off
REM Database Setup Script for FMWA
REM This script will create the database and import the schema

echo ========================================
echo FMWA Database Setup
echo ========================================
echo.

REM Check if MySQL is accessible
where mysql >nul 2>nul
if %ERRORLEVEL% NEQ 0 (
    echo ERROR: MySQL is not found in PATH
    echo Please install MySQL or add it to your PATH
    pause
    exit /b 1
)

echo MySQL found!
echo.

REM Prompt for MySQL credentials
set /p MYSQL_USER="Enter MySQL username (default: root): "
if "%MYSQL_USER%"=="" set MYSQL_USER=root

set /p MYSQL_PASS="Enter MySQL password (press Enter if none): "

echo.
echo Connecting to MySQL...
echo.

REM Create database and import schema
mysql -u %MYSQL_USER% -p%MYSQL_PASS% < schema.sql

if %ERRORLEVEL% EQU 0 (
    echo.
    echo ========================================
    echo Database setup completed successfully!
    echo ========================================
    echo.
    echo Database: fmwa_db
    echo Default admin username: admin
    echo Default admin password: admin123
    echo.
    echo IMPORTANT: Change the admin password after first login!
    echo.
) else (
    echo.
    echo ========================================
    echo ERROR: Database setup failed!
    echo ========================================
    echo.
    echo Please check your MySQL credentials and try again.
    echo.
)

pause
