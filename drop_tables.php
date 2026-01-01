<?php
$db = require 'config/db.php';
try {
    $pdo = new PDO($db['dsn'], $db['username'], $db['password']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec("DROP TABLE IF EXISTS migration CASCADE;");
    $pdo->exec("DROP TABLE IF EXISTS \"user\" CASCADE;");
    $pdo->exec("DROP TABLE IF EXISTS users CASCADE;");
    $pdo->exec("DROP TABLE IF EXISTS products CASCADE;");
    $pdo->exec("DROP TABLE IF EXISTS addresses CASCADE;");
    $pdo->exec("DROP TABLE IF EXISTS \"Cart\" CASCADE;");
    $pdo->exec("DROP TABLE IF EXISTS \"CartItem\" CASCADE;");
    $pdo->exec("DROP TABLE IF EXISTS cart CASCADE;");
    $pdo->exec("DROP TABLE IF EXISTS cart_item CASCADE;");
    $pdo->exec("DROP TABLE IF EXISTS orders CASCADE;");
    $pdo->exec("DROP TABLE IF EXISTS order_items CASCADE;");
    $pdo->exec("DROP TABLE IF EXISTS payments CASCADE;");
    echo "Done dropping tables.";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
