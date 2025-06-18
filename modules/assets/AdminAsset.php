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
        'css/all.min.css',
        'bootstrap/css/bootstrap.min.css',
        'css/owl.carousel.css',
        'css/magnific-popup.css',
        'css/animate.css',
        'css/meanmenu.min.css',
        'css/main.css',
        'css/responsive.css',
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
