#!/bin/bash

# Test database connection script

echo "Testing PostgreSQL Database Connection"
echo "======================================="
echo ""

# Load environment variables from .env.example (since .env is gitignored)
export DB_DSN="pgsql:host=dpg-d5begcjuibrs73ce8lag-a.virginia-postgres.render.com;port=5432;dbname=fruitshop_db;sslmode=require"
export DB_USERNAME="fruitadmin"
export DB_PASSWORD="6otaPMavrBeSxd7NayEGdP47NEEeuCaJ"

echo "Database Host: dpg-d5begcjuibrs73ce8lag-a.virginia-postgres.render.com"
echo "Database Name: fruitshop_db"
echo "Database User: fruitadmin"
echo ""

# Create a PHP test script
cat > /tmp/test_db_connection.php << 'EOF'
<?php
$dsn = getenv('DB_DSN') ?: 'pgsql:host=dpg-d5begcjuibrs73ce8lag-a.virginia-postgres.render.com;port=5432;dbname=fruitshop_db;sslmode=require';
$username = getenv('DB_USERNAME') ?: 'fruitadmin';
$password = getenv('DB_PASSWORD') ?: '6otaPMavrBeSxd7NayEGdP47NEEeuCaJ';

try {
    $pdo = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
    
    echo "✓ Database connection successful!\n";
    
    // Get PostgreSQL version
    $version = $pdo->query('SELECT version()')->fetchColumn();
    echo "\nPostgreSQL Version:\n";
    echo "  " . substr($version, 0, 50) . "...\n";
    
    // List existing tables
    $stmt = $pdo->query("
        SELECT table_name 
        FROM information_schema.tables 
        WHERE table_schema = 'public' 
        ORDER BY table_name
    ");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    echo "\nExisting Tables:\n";
    if (empty($tables)) {
        echo "  No tables found. Run migrations to create tables.\n";
    } else {
        foreach ($tables as $table) {
            echo "  - $table\n";
        }
    }
    
    exit(0);
    
} catch (PDOException $e) {
    echo "✗ Database connection failed!\n\n";
    echo "Error: " . $e->getMessage() . "\n\n";
    echo "Troubleshooting:\n";
    echo "  1. Check if the database credentials are correct\n";
    echo "  2. Ensure your IP is allowed to connect to Render.com database\n";
    echo "  3. Verify the database host is reachable\n";
    echo "  4. Check if SSL is properly configured\n";
    exit(1);
}
EOF

# Run the test
php /tmp/test_db_connection.php
TEST_RESULT=$?

# Cleanup
rm /tmp/test_db_connection.php

echo ""
if [ $TEST_RESULT -eq 0 ]; then
    echo "======================================="
    echo "Next Steps:"
    echo "  1. Copy .env.example to .env: cp .env.example .env"
    echo "  2. Run database setup: ./setup-db.sh"
    echo "  3. Start the application: ./start.sh"
    echo "======================================="
else
    echo "======================================="
    echo "Please fix the connection issues above"
    echo "======================================="
fi

exit $TEST_RESULT
