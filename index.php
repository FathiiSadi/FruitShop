<?php
/**
 * Yii2 Root Entry Point for Shared Hosting.
 * This file replaces the standard web/index.php.
 */

require __DIR__ . '/vendor/autoload.php';

// Define constants from environment variables
defined('YII_DEBUG') or define('YII_DEBUG', (bool) env('YII_DEBUG', true));
defined('YII_ENV') or define('YII_ENV', env('YII_ENV', 'dev'));

require __DIR__ . '/vendor/yiisoft/yii2/Yii.php';

$config = require __DIR__ . '/config/web.php';

(new yii\web\Application($config))->run();
