<?php

use app\components\PaymentComponent;
use yii\helpers\Url;

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
    ],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => env('COOKIE_VALIDATION_KEY', 'R_sbCgfxTckDQ-p0hNpD4Kb4NYYMont5'),
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => \yii\symfonymailer\Mailer::class,
            'viewPath' => '@app/mail',
            'useFileTransport' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db,
        'payment' => [
            'class' => 'app\components\PaymentComponent',
            'apiUrl' => env('API_URL'),
            'publicKey' => env('PUBLIC_KEY'),
            'privateKey' => env('PRIVATE_KEY'),
            'processingId' => env('PROCESSING_ID'),



        ],
        'paymentProcessor' => [
            'class' => 'app\services\PaymentProcessor',
            'paymentComponent' => ['class' => 'app\components\PaymentComponent'],
        ],
        'checkoutManager' => [
            'class' => 'app\services\CheckoutManager',
        ],


        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => true,
            'rules' => [
                // 'orders/success/<id:\d+>' => 'orders/success',
                'admin/orders/index/page=<page:\d+>' => 'admin/orders/index',
                'POST checkout/create-payment-3ds' => 'checkout/create-payment-3ds',
                'POST checkout/check-payment-status' => 'checkout/check-payment-status',
                'checkout/payment-success' => 'checkout/payment-success',
                'checkout/payment-failure' => 'checkout/payment-failure',
                'POST checkout/payment-webhook' => 'checkout/payment-webhook',

            ],
        ],


    ],

    'modules' => [
        'admin' => [
            'class' => 'app\modules\admin',
        ],
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;
