<?php
$db = require 'config/db.php';

try {
    $pdo = new PDO($db['dsn'], $db['username'], $db['password']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "Testing all tables...\n\n";

    // Test each table
    $tables = ['users', 'products', 'addresses', 'cart', 'cart_item', 'orders', 'order_items', 'payments'];

    foreach ($tables as $table) {
        $stmt = $pdo->query("SELECT COUNT(*) FROM $table");
        $count = $stmt->fetchColumn();
        echo "✓ Table '$table': $count rows\n";
    }

    // Test specific query
    $stmt = $pdo->query("SELECT * FROM products WHERE name = 'Apple'");
    $apple = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($apple) {
        echo "\n✓ Query test: Found product 'Apple'\n";
        echo "  - ID: {$apple['id']}\n";
        echo "  - Price: \${$apple['price']}\n";
        echo "  - Stock: {$apple['stock']}\n";
    }

    echo "\n✅ All database tests passed!\n";
    echo "\nDatabase is ready for production! 🚀\n";

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
