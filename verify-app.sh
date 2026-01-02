#!/bin/bash

# Verify application database connectivity

echo "========================================="
echo "FruitShop Application Database Verification"
echo "========================================="
echo ""

# Load environment variables
if [ -f .env ]; then
    set -a
    source .env
    set +a
    echo "✓ Environment variables loaded from .env"
else
    echo "✗ .env file not found"
    echo "  Run: cp .env.example .env"
    exit 1
fi

echo ""
echo "Testing Yii2 database connection..."
echo ""

# Create a temporary PHP script
cat > verify_temp.php << 'EOF'
<?php
require(__DIR__ . '/vendor/autoload.php');
require(__DIR__ . '/vendor/yiisoft/yii2/Yii.php');
require(__DIR__ . '/config/functions.php');

load_env(__DIR__ . '/.env');

$config = require(__DIR__ . '/config/web.php');
$application = new yii\web\Application($config);

try {
    $db = Yii::$app->db;
    $db->open();
    
    echo "✓ Yii2 database connection successful!\n\n";
    
    $productCount = $db->createCommand('SELECT COUNT(*) FROM products')->queryScalar();
    echo "Database Statistics:\n";
    echo "  Products: $productCount\n";
    
    $userCount = $db->createCommand('SELECT COUNT(*) FROM users')->queryScalar();
    echo "  Users: $userCount\n";
    
    $orderCount = $db->createCommand('SELECT COUNT(*) FROM orders')->queryScalar();
    echo "  Orders: $orderCount\n";
    
    echo "\n";
    
    $products = $db->createCommand('SELECT id, name, price, stock FROM products LIMIT 5')->queryAll();
    echo "Sample Products:\n";
    foreach ($products as $product) {
        echo "  - {$product['name']} (\${$product['price']}) - Stock: {$product['stock']}\n";
    }
    
    echo "\n✓ Application is ready to use!\n";
    exit(0);
    
} catch (Exception $e) {
    echo "✗ Database error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
    exit(1);
}
EOF

# Run the verification
php verify_temp.php
RESULT=$?

# Cleanup
rm verify_temp.php

echo ""
if [ $RESULT -eq 0 ]; then
    echo "========================================="
    echo "✓ All checks passed!"
    echo "========================================="
else
    echo "========================================="
    echo "✗ Verification failed"
    echo "========================================="
fi

exit $RESULT
