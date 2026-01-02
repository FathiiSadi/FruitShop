<?php

/**
 * @link https://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license https://www.yiiframework.com/license/
 */

namespace app\modules\assets;

use yii\web\AssetBundle;

class AdminAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/site.css',
        'assets_static/css/all.min.css',
        'assets_static/bootstrap/css/bootstrap.min.css',
        'assets_static/css/owl.carousel.css',
        'assets_static/css/magnific-popup.css',
        'assets_static/css/animate.css',
        'assets_static/css/meanmenu.min.css',
        'assets_static/css/main.css',
        'assets_static/css/responsive.css',
        'vendors/feather/feather.css',
        'vendors/ti-icons/css/themify-icons.css',
        'vendors/css/vendor.bundle.base.css',
        'vendors/mdi/css/materialdesignicons.min.css',
        'vendors/datatables.net-bs4/dataTables.bootstrap4.css',
        'js/select.dataTables.min.css',
        'css/vertical-layout-light/style.css',
        'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css',
    ];
    public $js = [
        'vendors/js/vendor.bundle.base.js',
        'js/off-canvas.js',
        'js/template.js',
        'js/hoverable-collapse.js',
        'js/settings.js',
        'js/todolist.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap5\BootstrapAsset'
    ];
}
