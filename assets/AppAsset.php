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
        'css/style.css',
        'css/normalize.css',
        'assets/img/favicon.png',
        "https://fonts.googleapis.com/css?family=Open+Sans:300,400,700",
        "https://fonts.googleapis.com/css?family=Poppins:400,700&display=swap",
        'assets/css/all.min.css',
        'assets/css/owl.carousel.css',
        'assets/css/magnific-popup.css',
        'assets/css/animate.css',
        'assets/css/meanmenu.min.css',
        'assets/css/main.css',
        'assets/css/responsive.css',
    ];
    public $js = [
        'https://cdn.checkout.com/js/framesv2.min.js',
        'assets/js/app.js',
        'assets/js/jquery-1.11.3.min.js',
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
