<?php
$db = require 'config/db.php';
try {
    $pdo = new PDO($db['dsn'], $db['username'], $db['password']);
    $stmt = $pdo->query("SELECT column_name FROM information_schema.columns WHERE table_name = 'orders'");
    while ($row = $stmt->fetch()) {
        echo $row['column_name'] . "\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
