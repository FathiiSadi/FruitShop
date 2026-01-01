<?php
require 'vendor/autoload.php';
require 'vendor/yiisoft/yii2/Yii.php';

$db = require 'config/db.php';

try {
    echo "Testing database connection and queries...\n\n";

    // Test Products table
    $products = \app\models\Products::find()->all();
    echo "✓ Products table: Found " . count($products) . " products\n";

    // Test Users table
    $users = \app\models\User::find()->all();
    echo "✓ Users table: Found " . count($users) . " users\n";

    // Test Addresses table
    $addresses = \app\models\Addresses::find()->all();
    echo "✓ Addresses table: Found " . count($addresses) . " addresses\n";

    // Test Cart table
    $carts = \app\models\Cart::find()->all();
    echo "✓ Cart table: Found " . count($carts) . " carts\n";

    // Test Orders table
    $orders = \app\models\Orders::find()->all();
    echo "✓ Orders table: Found " . count($orders) . " orders\n";

    // Test a specific query
    $apple = \app\models\Products::find()->where(['name' => 'Apple'])->one();
    if ($apple) {
        echo "✓ Query test: Found product 'Apple' with price $" . $apple->price . "\n";
    }

    echo "\n✅ All database tests passed!\n";

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
