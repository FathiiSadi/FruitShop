<?php
$db = require 'config/db.php';
try {
    $pdo = new PDO($db['dsn'], $db['username'], $db['password']);
    $stmt = $pdo->query("SELECT schemaname, tablename FROM pg_catalog.pg_tables WHERE schemaname != 'pg_catalog' AND schemaname != 'information_schema'");
    while ($row = $stmt->fetch()) {
        echo $row['schemaname'] . "." . $row['tablename'] . "\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
