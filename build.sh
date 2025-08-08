@echo off
setlocal enabledelayedexpansion

REM Check if Docker is running
docker info >nul 2>&1
if errorlevel 1 (
    echo.
    echo ERROR: Docker is not running!
    echo.
    echo Please start Docker Desktop and try again.
    echo You can find Docker Desktop in your Start Menu or System Tray.
    echo.
    pause
    exit /b 1
)

REM Build React if needed
if not exist "public\js\bundle.js" (
    echo.
    echo First run detected. Building React components...
    docker run --rm -v "%cd%":/app -w /app node:18-alpine sh -c "npm install && npm run build"
    echo Build complete!
)

REM Start PHP server
cls
echo.
echo ====================================
echo  Starting Queue Management System
echo ====================================
echo.
echo Customer:  http://localhost:8000
echo Admin:     http://localhost:8000/admin.php
echo Display:   http://localhost:8000/display.php
echo.
echo Press Ctrl+C to stop
echo.

docker run -it --rm ^
    -p 8000:8000 ^
    -v "%cd%":/app ^
    -w /app ^
    php:8.1-cli ^
    bash -c "apt-get update -qq && apt-get install -y libsqlite3-dev > /dev/null 2>&1 && docker-php-ext-install pdo_sqlite > /dev/null 2>&1 && php -S 0.0.0.0:8000 -t public"