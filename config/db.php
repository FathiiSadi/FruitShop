<?php

return [
    'class' => 'yii\db\Connection',
    'dsn' => env('DB_DSN', 'pgsql:host=dpg-d5begcjuibrs73ce8lag-a;port=5432;dbname=fruitshop_db;sslmode=disable'),
    'username' => env('DB_USERNAME', 'fruitadmin'),
    'password' => env('DB_PASSWORD', '6otaPMavrBeSxd7NayEGdP47NEEeuCaJ'),
    'charset' => 'utf8',

    // Essential for Render.com PostgreSQL internal connections
    'attributes' => [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ],
];
