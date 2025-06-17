<?php

/**
 * @link https://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license https://www.yiiframework.com/license/
 */

namespace app\modules\asset;

use yii\web\AssetBundle;

class AdminAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/site.css',
        'assets/css/all.min.css',
        'assets/bootstrap/css/bootstrap.min.css',
        'assets/css/owl.carousel.css',
        'assets/css/magnific-popup.css',
        'assets/css/animate.css',
        'assets/css/meanmenu.min.css',
        'assets/css/main.css',
        'assets/css/responsive.css',
        'vendors/feather/feather.css',
        'vendors/ti-icons/css/themify-icons.css',
        'vendors/css/vendor.bundle.base.css',
        'vendors/mdi/css/materialdesignicons.min.css',
        'vendors/datatables.net-bs4/dataTables.bootstrap4.css',
        'js/select.dataTables.min.css',
        'css/vertical-layout-light/style.css',
    ];
    public $js = [
        'assets/js/jquery-1.11.3.min.js',
        'assets/bootstrap/js/bootstrap.min.js',
        'assets/js/ .countdown.js',
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
