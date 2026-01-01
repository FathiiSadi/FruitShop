<?php
$db = require 'config/db.php';
try {
    $pdo = new PDO($db['dsn'], $db['username'], $db['password']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $pdo->query('SELECT * FROM "orders" LIMIT 1');
    echo "Success!";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
