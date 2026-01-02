#!/bin/bash

echo "🍎 FruitShop Quick Start Script"
echo "================================"
echo ""

# Check if PHP is installed
if ! command -v php &> /dev/null; then
    echo "❌ PHP is not installed. Please install PHP 8.2 or higher."
    echo "Visit: https://www.php.net/downloads"
    exit 1
fi

# Check PHP version
PHP_VERSION=$(php -r 'echo PHP_VERSION;')
echo "✓ PHP version: $PHP_VERSION"

# Check if Composer is installed
if ! command -v composer &> /dev/null; then
    echo "❌ Composer is not installed."
    echo "Visit: https://getcomposer.org/download/"
    exit 1
fi

echo "✓ Composer is installed"
echo ""

# Install dependencies
echo "📦 Installing dependencies..."
composer install --no-interaction --prefer-dist

if [ $? -ne 0 ]; then
    echo "❌ Failed to install dependencies"
    exit 1
fi

echo "✓ Dependencies installed"
echo ""

# Create runtime directory if it doesn't exist
if [ ! -d "runtime" ]; then
    mkdir -p runtime
    chmod 777 runtime
fi

# Create web/assets directory if it doesn't exist
if [ ! -d "web/assets" ]; then
    mkdir -p web/assets
    chmod 777 web/assets
fi

echo "✓ Directories configured"
echo ""

# Start the server
echo "🚀 Starting FruitShop..."
echo ""
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "  Application is running at:"
echo "  👉 http://localhost:8080"
echo ""
echo "  Admin Login:"
echo "  📧 admin@fruitshop.com"
echo "  🔑 admin123"
echo ""
echo "  Press CTRL+C to stop the server"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo ""

php yii serve
