<?php

use yii\bootstrap5\Breadcrumbs;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap5\Alert;
use yii\bootstrap5\NavBar;

/** @var yii\web\View $this */
/** @var string $content */

$this->beginPage();

?>

<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">


<head>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <link rel="shortcut icon" type="image/png" href="<?= Url::to('@web/assets/img/favicon.png') ?>">
    <!-- google font -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,700" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Poppins:400,700&display=swap" rel="stylesheet">
    <!-- fontawesome -->
    <link rel="stylesheet" href="<?= \yii\helpers\Url::to('@web/assets/css/all.min.css') ?>">
    <!-- bootstrap -->
    <link rel="stylesheet" href="<?= \yii\helpers\Url::to('@web/assets/bootstrap/css/bootstrap.min.css') ?>">
    <!-- owl carousel -->
    <link rel="stylesheet" href="<?= \yii\helpers\Url::to('@web/assets/css/owl.carousel.css') ?>">
    <!-- magnific popup -->
    <link rel="stylesheet" href="<?= \yii\helpers\Url::to('@web/assets/css/magnific-popup.css') ?>">
    <!-- animate css -->
    <link rel="stylesheet" href="<?= \yii\helpers\Url::to('@web/assets/css/animate.css') ?>">
    <!-- mean menu css -->
    <link rel="stylesheet" href="<?= \yii\helpers\Url::to('@web/assets/css/meanmenu.min.css') ?>">
    <!-- main style -->
    <link rel="stylesheet" href="<?= \yii\helpers\Url::to('@web/assets/css/main.css') ?>">
    <!-- responsive -->
    <link rel="stylesheet" href="<?= \yii\helpers\Url::to('@web/assets/css/responsive.css') ?>">

    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title ?? 'FruitShop') ?></title>
    <!-- plugins:css -->
    <link rel="stylesheet" href="<?= Url::to('@web/vendors/feather/feather.css') ?>">
    <link rel="stylesheet" href="<?= Url::to('@web/vendors/ti-icons/css/themify-icons.css') ?>">
    <link rel="stylesheet" href="<?= Url::to('@web/vendors/css/vendor.bundle.base.css') ?>">
    <link rel="stylesheet" href="<?= Url::to('@web/vendors/mdi/css/materialdesignicons.min.css') ?>">
    <!-- endinject -->
    <!-- Plugin css for this page -->
    <link rel="stylesheet" href="<?= Url::to('@web/vendors/datatables.net-bs4/dataTables.bootstrap4.css') ?>">
    <link rel="stylesheet" href="<?= Url::to('@web/vendors/ti-icons/css/themify-icons.css') ?>">
    <link rel="stylesheet" type="text/css" href="<?= Url::to('@web/js/select.dataTables.min.css') ?>">
    <!-- End plugin css for this page -->
    <link rel="stylesheet" href="<?= Url::to('@web/css/site.css') ?>">

    <!-- inject:css -->
    <link rel="stylesheet" href="<?= Url::to('@web/css/vertical-layout-light/style.css') ?>">
    <!-- endinject -->
    <link rel="shortcut icon" href="<?= Url::to('@web/images/favicon.png') ?>" />
</head>

<body>
    <?php $this->beginBody() ?>


    <?php NavBar::begin(); ?>
    <nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
        <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
            <a class="navbar-brand brand-logo mr-5" href="<?= Url::to(['/admin/default']) ?>"><img src="<?= Url::to('@web/images/logo.svg') ?>" class="mr-2" alt="logo" /></a>
            <a class="navbar-brand brand-logo-mini" href="/admin/default"><img src="<?= Url::to('@web/images/logo-mini.svg') ?>" alt="logo" /></a>
        </div>
        <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">

            <ul class="navbar-nav mr-lg-2">
                <li class="nav-item">
                    <a class="nav-link" href="<?= Url::to(['/admin/default']) ?>">
                        <i class="icon-grid menu-icon"></i>
                        <span class="menu-title">Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= Url::to(['/admin/user-edit']) ?>">
                        <i class="icon-grid menu-icon"></i>
                        <span class="menu-title">User</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= Url::to(['/admin/products']) ?>">
                        <i class="icon-grid menu-icon"></i>
                        <span class="menu-title">Products</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= Url::to(['/admin/orders']) ?>">
                        <i class="icon-grid menu-icon"></i>
                        <span class="menu-title">Orders</span>
                    </a>
                </li>

            </ul>
            <ul class="navbar-nav navbar-nav-right">


                <li class="nav-item nav-profile dropdown">
                    <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" id="profileDropdown">
                        <img src=<?= Url::to('@web/assets/img/default.webp') ?> alt="profile" />
                    </a>
                    <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="profileDropdown">
                        <?= Html::beginForm(['/site/logout'], 'post') ?>
                        <?= Html::submitButton(
                            '<i class="ti-power-off text-primary"></i> Logout',
                            ['class' => 'dropdown-item btn btn-link']
                        ) ?>
                        <?= Html::endForm() ?>
                    </div>
                </li>


            </ul>
            <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
                <span class="icon-menu"></span>
            </button>
        </div>
    </nav>
    <?php NavBar::end(); ?>

    <main id="main" class="flex-shrink-0 mt-5" role="main">
        <div class="">
            <?php if (!empty($this->params['breadcrumbs'])): ?>
                <?= Breadcrumbs::widget(['links' => $this->params['breadcrumbs']]) ?>
            <?php endif ?>
            <?= $content ?>
        </div>
    </main>




    <footer class="footer">
        <div class="d-sm-flex justify-content-center justify-content-sm-between">
            <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">Copyright © 2025. Premium <a href="https://www.bootstrapdash.com/" target="_blank">Fathi Al Sadi</a> from Altibbi. All rights reserved.</span>
            <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center">Hand-crafted & made with <i class="ti-heart text-danger ml-1"></i></span>
        </div>
    </footer>



    <?php $this->endBody() ?>
</body>

</html>
<?php $this->endPage() ?>


<!-- jquery -->
<script src="<?= Url::to('@web/assets/js/jquery-1.11.3.min.js') ?>"></script>
<!-- bootstrap -->
<script src="<?= Url::to('@web/assets/bootstrap/js/bootstrap.min.js') ?>"></script>
<!-- countdown -->
<script src="<?= Url::to('@web/assets/js/countdown.js') ?>"></script>
<!-- isotope -->
<script src="<?= Url::to('@web/assets/js/jquery.isotope-3.0.6.min.js') ?>"></script>
<!-- waypoints -->
<script src="<?= Url::to('@web/assets/js/waypoints.js') ?>"></script>
<!-- owl carousel -->
<script src="<?= Url::to('@web/assets/js/owl.carousel.min.js') ?>"></script>
<!-- magnific popup -->
<script src="<?= Url::to('@web/assets/js/jquery.magnific-popup.min.js') ?>"></script>
<!-- mean menu -->
<script src="<?= Url::to('@web/assets/js/jquery.meanmenu.min.js') ?>"></script>
<!-- sticker js -->
<script src="<?= Url::to('@web/assets/js/sticker.js') ?>"></script>
<!-- main js -->
<script src="<?= Url::to('@web/assets/js/main.js') ?>"></script>