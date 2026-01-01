<?php

return [
    'class' => 'yii\db\Connection',
    'dsn' => 'pgsql:host=dpg-d5begcjuibrs73ce8lag-a.virginia-postgres.render.com;port=5432;dbname=fruitshop_db;sslmode=require',
    'username' => 'fruitadmin',
    'password' => '6otaPMavrBeSxd7NayEGdP47NEEeuCaJ',
    'charset' => 'utf8',

    // Essential for Render.com PostgreSQL Production
    'attributes' => [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_PERSISTENT => false,
    ],
];
