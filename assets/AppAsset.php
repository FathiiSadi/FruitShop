<?php

/**
 * @link https://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license https://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/site.css',
        '@web/assets/img/favicon.png',
        "https://fonts.googleapis.com/css?family=Open+Sans:300,400,700",
        "https://fonts.googleapis.com/css?family=Poppins:400,700&display=swap",

    ];
    public $js = [
        'assets/js/jquery-1.11.3.min.js',
        'assets/bootstrap/js/bootstrap.min.js',
        'assets/js/jquery.countdown.js',
        'assets/js/jquery.isotope-3.0.6.min.js',
        'assets/js/waypoints.js',
        'assets/js/owl.carousel.min.js',
        'assets/js/jquery.magnific-popup.min.js',
        'assets/js/jquery.meanmenu.min.js',
        'assets/js/sticker.js',
        'assets/js/main.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap5\BootstrapAsset'
    ];
}
