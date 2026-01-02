#!/bin/bash

# FruitShop Database Setup Script
# This script sets up the database and runs migrations

echo "========================================="
echo "FruitShop Database Setup"
echo "========================================="
echo ""

# Check if .env file exists
if [ ! -f .env ]; then
    echo "Creating .env file from .env.example..."
    cp .env.example .env
    echo "✓ .env file created"
    echo ""
    echo "⚠️  IMPORTANT: Please update the .env file with your database credentials if needed."
    echo ""
else
    echo "✓ .env file already exists"
    echo ""
fi

# Load environment variables
if [ -f .env ]; then
    export $(cat .env | grep -v '^#' | xargs)
fi

echo "Database Configuration:"
echo "  DSN: $DB_DSN"
echo "  Username: $DB_USERNAME"
echo ""

# Test database connection
echo "Testing database connection..."
php yii migrate/create test --interactive=0 > /dev/null 2>&1
if [ $? -eq 0 ]; then
    echo "✓ Database connection successful"
    # Clean up test migration
    rm -f migrations/m*_test.php 2>/dev/null
else
    echo "✗ Database connection failed"
    echo ""
    echo "Please check your database credentials in the .env file"
    exit 1
fi

echo ""
echo "Running database migrations..."
php yii migrate --interactive=0

if [ $? -eq 0 ]; then
    echo ""
    echo "========================================="
    echo "✓ Database setup completed successfully!"
    echo "========================================="
    echo ""
    echo "You can now start the application with:"
    echo "  ./start.sh"
    echo ""
else
    echo ""
    echo "✗ Migration failed. Please check the error messages above."
    exit 1
fi
