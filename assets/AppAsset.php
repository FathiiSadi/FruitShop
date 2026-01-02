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
        "https://fonts.googleapis.com/css?family=Open+Sans:300,400,700",
        "https://fonts.googleapis.com/css?family=Poppins:400,700&display=swap",
        "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css",
        'assets_static/css/owl.carousel.css',
        'assets_static/css/magnific-popup.css',
        'assets_static/css/animate.css',
        'assets_static/css/meanmenu.min.css',
        'assets_static/css/main.css',
        'assets_static/css/responsive.css',
    ];
    public $js = [
        'https://cdn.checkout.com/js/framesv2.min.js',
        'assets_static/js/jquery-1.11.3.min.js',
        'assets_static/js/jquery.countdown.js',
        'assets_static/js/jquery.isotope-3.0.6.min.js',
        'assets_static/js/waypoints.js',
        'assets_static/js/owl.carousel.min.js',
        'assets_static/js/jquery.magnific-popup.min.js',
        'assets_static/js/jquery.meanmenu.min.js',
        'assets_static/js/sticker.js',
        'assets_static/js/main.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap5\BootstrapAsset'
    ];
}
