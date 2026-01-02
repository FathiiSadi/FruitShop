@echo off
echo.
echo 🍎 FruitShop Quick Start Script
echo ================================
echo.

REM Check if PHP is installed
where php >nul 2>nul
if %ERRORLEVEL% NEQ 0 (
    echo ❌ PHP is not installed. Please install PHP 8.2 or higher.
    echo Visit: https://www.php.net/downloads
    pause
    exit /b 1
)

REM Check PHP version
for /f "tokens=*" %%i in ('php -r "echo PHP_VERSION;"') do set PHP_VERSION=%%i
echo ✓ PHP version: %PHP_VERSION%

REM Check if Composer is installed
where composer >nul 2>nul
if %ERRORLEVEL% NEQ 0 (
    echo ❌ Composer is not installed.
    echo Visit: https://getcomposer.org/download/
    pause
    exit /b 1
)

echo ✓ Composer is installed
echo.

REM Install dependencies
echo 📦 Installing dependencies...
call composer install --no-interaction --prefer-dist

if %ERRORLEVEL% NEQ 0 (
    echo ❌ Failed to install dependencies
    pause
    exit /b 1
)

echo ✓ Dependencies installed
echo.

REM Create runtime directory if it doesn't exist
if not exist "runtime" (
    mkdir runtime
)

REM Create web/assets directory if it doesn't exist
if not exist "web\assets" (
    mkdir web\assets
)

echo ✓ Directories configured
echo.

REM Start the server
echo 🚀 Starting FruitShop...
echo.
echo ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
echo   Application is running at:
echo   👉 http://localhost:8080
echo.
echo   Admin Login:
echo   📧 admin@fruitshop.com
echo   🔑 admin123
echo.
echo   Press CTRL+C to stop the server
echo ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
echo.

php yii serve

pause
