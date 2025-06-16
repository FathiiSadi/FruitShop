<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
use yii\bootstrap5\BootstrapAsset;
use yii\grid\ActionColumn;
use app\models\Products;
/* @var $this View */

$this->title = 'FruitShop Admin';
$this->registerCssFile(Url::to('@web/css/style.css'), ['depends' => [\yii\bootstrap5\BootstrapAsset::className()]]);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <?php
    ?>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title ?? 'FruitShop Admin') ?></title>
    <!-- plugins:css -->
    <link rel="stylesheet" href="<?= Url::to('@web/vendors/feather/feather.css') ?>">
    <link rel="stylesheet" href="<?= Url::to('@web/vendors/ti-icons/css/themify-icons.css') ?>">
    <link rel="stylesheet" href="<?= Url::to('@web/vendors/css/vendor.bundle.base.css') ?>">
    <link rel="stylesheet" href="<?= Url::to('@web/vendors/mdi/css/materialdesignicons.min.css') ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- endinject -->
    <!-- Plugin css for this page -->
    <link rel="stylesheet" href="<?= Url::to('@web/vendors/datatables.net-bs4/dataTables.bootstrap4.css') ?>">
    <link rel="stylesheet" href="<?= Url::to('@web/vendors/ti-icons/css/themify-icons.css') ?>">
    <link rel="stylesheet" type="text/css" href="<?= Url::to('@web/js/select.dataTables.min.css') ?>">
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <link rel="stylesheet" href="<?= Url::to('@web/css/vertical-layout-light/style.css') ?>">
    <!-- endinject -->
    <link rel="shortcut icon" href="<?= Url::to('@web/images/favicon.png') ?>" />
</head>

<body>
    <div class="container-scroller">
        <!-- partial:partials/_navbar.html -->



        <!-- partial -->
        <div class="container-fluid page-body-wrapper">



            <div class="main-panel" style="width:100%;min-height:100vh;">
                <div class="content-wrapper" style="height:100%;display:flex;flex-direction:column;justify-content:center;">


                    <div class="card" style="flex:1;display:flex;flex-direction:column;min-height:0;">

                        <div class="card-body" style="flex:1;display:flex;flex-direction:column;min-height:0;">
                            <h4 class="card-title">Products Management</h4>
                            <p class="card-description">
                            </p>
                            <div class="table-responsive" style="flex:1;min-height:0;">
                                <table class="table" style="width:100%;height:100%;">
                                    <thead>
                                        <tr>
                                            <th>Product name</th>
                                            <th>ID</th>
                                            <th>Category</th>
                                            <th>Price</th>
                                            <th>Quantity</th>
                                            <th>actions</th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($products as $product): ?>
                                            <tr>
                                                <td><?= $product->name ?></td>
                                                <td><?= $product->ProductID ?></td>
                                                <td><?= $product->category ?></td>
                                                <td><?= $product->price ?></td>
                                                <td><?= $product->stock ?></td>

                                                <td>
                                                    <a href="<?= Url::toRoute(['/admin/products/update', 'ProductID' => $product->ProductID]) ?>" class="btn btn-primary btn-sm">Edit</a>
                                                    <?= Html::beginForm(['/admin/products/delete', 'ProductID' => $product->ProductID], 'post', ['style' => 'display:inline']) ?>
                                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this item?');">Delete</button>
                                                    <?= Html::endForm() ?>
                                                </td>



                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="container mb-5 Statistics">
                    <h2 class="text-center mb-4">Dashboard Statistics</h2>

                    <div class="row g-4">
                        <!-- Revenue Card -->
                        <div class="col-xl-3 col-md-6">
                            <div class="card stats-card card-hover-primary shadow-sm">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <div class="text-muted text-uppercase fw-bold small">Expected Revenue</div>
                                            <div class="stat-value text-primary">$<?= number_format($profit, 2) ?></div>
                                            <!-- <div class="stat-change text-success">
                                                <i class="fas fa-arrow-up trend-icon"></i>
                                                <span>8.3% increase</span>
                                            </div> -->
                                        </div>
                                        <div class="icon-circle">
                                            <i class="fas fa-dollar-sign text-primary"></i>
                                        </div>
                                    </div>
                                    <div class="progress mt-4">
                                        <div class="progress-bar bg-primary" role="progressbar" style="width: 75%"></div>
                                    </div>
                                    <div class="mini-chart">
                                        <div class="chart-bar" style="height: 60%"></div>
                                        <div class="chart-bar" style="height: 40%"></div>
                                        <div class="chart-bar" style="height: 80%"></div>
                                        <div class="chart-bar" style="height: 65%"></div>
                                        <div class="chart-bar" style="height: 75%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Users Card -->
                        <div class="col-xl-3 col-md-6">
                            <div class="card stats-card card-hover-success shadow-sm">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <div class="text-muted text-uppercase fw-bold small">products</div>
                                            <div class="stat-value text-success"><?= count($products) ?></div>

                                        </div>
                                        <div class="icon-circle">
                                            <i class="fas fa-solid fa-box text-success"></i>
                                        </div>
                                    </div>
                                    <div class="progress mt-4">
                                        <div class="progress-bar bg-success" role="progressbar" style="width: 85%"></div>
                                    </div>
                                    <div class="mini-chart">
                                        <div class="chart-bar" style="height: 50%"></div>
                                        <div class="chart-bar" style="height: 70%"></div>
                                        <div class="chart-bar" style="height: 85%"></div>
                                        <div class="chart-bar" style="height: 75%"></div>
                                        <div class="chart-bar" style="height: 85%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tasks Card -->
                        <div class="col-xl-3 col-md-6">
                            <div class="card stats-card card-hover-info shadow-sm">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <div class="text-muted text-uppercase fw-bold small">low of stock</div>
                                            <div class="stat-value text-info"><?= $lowStockCount ?></div>

                                        </div>
                                        <div class="icon-circle">
                                            <i class="fas fa-solid fa-arrow-down text-info"></i>
                                        </div>
                                    </div>
                                    <div class="progress mt-4">
                                        <div class="progress-bar bg-info" role="progressbar" style="width: 65%"></div>
                                    </div>
                                    <div class="mini-chart">
                                        <div class="chart-bar" style="height: 80%"></div>
                                        <div class="chart-bar" style="height: 65%"></div>
                                        <div class="chart-bar" style="height: 55%"></div>
                                        <div class="chart-bar" style="height: 65%"></div>
                                        <div class="chart-bar" style="height: 65%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Conversion Card -->
                        <div class="col-xl-3 col-md-6">
                            <div class="card stats-card card-hover-warning shadow-sm">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <div class="text-muted text-uppercase fw-bold small">Out Of stock</div>
                                            <div class="stat-value text-warning"><?= $outOfStockCount ?></div>

                                        </div>
                                        <div class="icon-circle">
                                            <i class="fas fa-triangle-exclamation text-warning"></i>
                                        </div>
                                    </div>
                                    <div class="progress mt-4">
                                        <div class="progress-bar bg-warning" role="progressbar" style="width: 45%"></div>
                                    </div>
                                    <div class="mini-chart">
                                        <div class="chart-bar" style="height: 30%"></div>
                                        <div class="chart-bar" style="height: 45%"></div>
                                        <div class="chart-bar" style="height: 40%"></div>
                                        <div class="chart-bar" style="height: 45%"></div>
                                        <div class="chart-bar" style="height: 45%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>





            </div>

        </div>


    </div>







</html>