<?php

/**
 * Database configuration for PostgreSQL
 * Uses environment variables for connection details
 */

return [
    'class' => 'yii\db\Connection',
    'dsn' => getenv('DB_DSN') ?: 'pgsql:host=localhost;port=5432;dbname=fruitshop_db',
    'username' => getenv('DB_USERNAME') ?: 'fruitadmin',
    'password' => getenv('DB_PASSWORD') ?: 'fruitpassword',
    'charset' => 'utf8',
    'enableSchemaCache' => true,
    'schemaCacheDuration' => 3600,
    'schemaCache' => 'cache',
];
