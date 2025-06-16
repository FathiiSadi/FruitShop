<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
use yii\bootstrap5\BootstrapAsset;
/* @var $this View */

$this->title = 'FruitShop Admin';
?>

<body>
    <div class="container-scroller">
        <!-- partial:partials/_navbar.html -->

        <!-- partial -->
        <div class="container-fluid page-body-wrapper">



            <!-- partial -->
            <div class="main-panel">
                <div class="content-wrapper">
                    <div class="row">
                        <div class="col-md-12 grid-margin">
                            <div class="row">
                                <div class="col-12 col-xl-8 mb-4 mb-xl-0">
                                    <h3 class="font-weight-bold">Welcome <?= Html::encode(Yii::$app->user->identity->username) ?></h3>
                                    <h6 class="font-weight-normal mb-0">All systems are running smoothly! You have <span class="text-primary">3 unread alerts!</span></h6>
                                </div>
                                <div class="col-12 col-xl-4">
                                    <div class="justify-content-end d-flex">
                                        <div class="dropdown flex-md-grow-1 flex-xl-grow-0">
                                            <button class="btn btn-sm btn-light bg-white dropdown-toggle" type="button" id="dropdownMenuDate2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                                <i class="mdi mdi-calendar"></i> Today (10 Jan 2021)
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuDate2">
                                                <a class="dropdown-item" href="#">January - March</a>
                                                <a class="dropdown-item" href="#">March - June</a>
                                                <a class="dropdown-item" href="#">June - August</a>
                                                <a class="dropdown-item" href="#">August - November</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 grid-margin stretch-card">
                            <div class="card tale-bg">
                                <div class="card-people mt-auto">
                                    <img src="<?= Url::to('@web/images/dashboard/people.svg') ?>" alt="people">
                                    <div class="weather-info">
                                        <div class="d-flex">
                                            <div>
                                                <h2 class="mb-0 font-weight-normal"><i class="icon-clock mr-2"></i><?= date('H:i') ?><sup>UTC</sup></h2>
                                            </div>
                                            <div class="ml-2">
                                                <h4 class="location font-weight-normal">Amman</h4>
                                                <h6 class="font-weight-normal">Jordan</h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 grid-margin transparent">
                            <div class="row">
                                <div class="col-md-6 mb-4 stretch-card transparent">
                                    <div class="card card-tale">
                                        <div class="card-body">
                                            <p class="mb-4">Total Users</p>
                                            <p class="fs-30 mb-2"> <?= $data['userCount'] ?></p>
                                            <!-- <p>10.00% (30 days)</p> -->
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-4 stretch-card transparent">
                                    <div class="card card-dark-blue">
                                        <div class="card-body">
                                            <p class="mb-4">Total Orders</p>
                                            <p class="fs-30 mb-2"><?= count($orders) ?></p>
                                            <!-- <p>22.00% (30 days)</p> -->
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-4 mb-lg-0 stretch-card transparent">
                                    <div class="card card-light-blue">
                                        <div class="card-body">
                                            <p class="mb-4">Total Profit</p>
                                            <p class="fs-30 mb-2">$<?= $data['totalProfit'] ?></p>
                                            <!-- <p>2.00% (30 days)</p> -->
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 stretch-card transparent">
                                    <div class="card card-light-danger">
                                        <div class="card-body">
                                            <p class="mb-4">Total Products Out Of Stock</p>
                                            <p class="fs-30 mb-2"><?= $data['outOfStockCount'] ?></p>
                                            <!-- <p>0.22% (30 days)</p> -->
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="row mt-3">
                                <div class="col-md-6 mb-4 mb-lg-0 stretch-card transparent">
                                    <div class="card card-dark-blue">
                                        <div class="card-body">
                                            <p class="mb-4">Orders this month</p>
                                            <p class="fs-30 mb-2"><?= $data['OrdersThisMonth'] ?> Orders</p>
                                            <!-- <p>2.00% (30 days)</p> -->
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 stretch-card transparent">
                                    <div class="card card-tale">
                                        <div class="card-body">
                                            <p class="mb-4">Total Products</p>
                                            <p class="fs-30 mb-2"><?= count($data['products']) ?></p>
                                            <!-- <p>0.22% (30 days)</p> -->
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="row">

                        <!-- <div class="col-md-6 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <p class="card-title">Sales Report</p>
                                        <a href="#" class="text-info">View all</a>
                                    </div>
                                    <p class="font-weight-500">The total number of sessions within the date range. It is the period time a user is actively engaged with your website, page or app, etc</p>
                                    <div id="sales-legend" class="chartjs-legend mt-4 mb-2"></div>
                                    <canvas id="sales-chart"></canvas>
                                </div>
                            </div>
                        </div> -->
                    </div>
                    <div class="row">
                        <div class="col-md-12 grid-margin stretch-card">
                            <div class="card position-relative">
                                <div class="card-body">
                                    <div id="detailedReports" class="carousel slide detailed-report-carousel position-static pt-2" data-ride="carousel">
                                        <div class="carousel-inner">
                                            <div class="carousel-item active">
                                                <div class="row">
                                                    <div class="col-md-12 col-xl-3 d-flex flex-column justify-content-start">
                                                        <div class="ml-xl-4 mt-3">
                                                            <p class="card-title">Detailed Reports</p>
                                                            <h1 class="text-primary">$<?= $data['totalProfit'] ?></h1>
                                                            <h3 class="font-weight-500 mb-xl-4 text-primary">Jordan</h3>
                                                            <p class="mb-2 mb-xl-0">Lorem ipsum dolor sit amet consectetur adipisicing elit. Repellat fuga ab nesciunt aperiam vel voluptate temporibus, dicta perferendis optio quo molestiae nulla corporis. Repellat, maiores impedit ex illo quod itaque.</p>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12 col-xl-9">
                                                        <div class="row">
                                                            <div class="col-md-6 border-right">
                                                                <div class="table-responsive mb-3 mb-md-0 mt-3">
                                                                    <?php foreach ($cities as $city) : ?>
                                                                        <table class="table table-borderless report-table">
                                                                            <tr>
                                                                                <td class="text-muted"><?= $city['city'] ?></td>
                                                                                <td class="w-100 px-0">
                                                                                    <div class="progress progress-md mx-4">
                                                                                        <div class="progress-bar bg-primary" role="progressbar" style="width: <?= $city['total'] / $cities[0]['total'] * 100 ?>%" aria-valuenow="<?= $city['total'] ?>" aria-valuemin="0" aria-valuemax="<?= $cities[0]['total'] ?> "></div>
                                                                                    </div>
                                                                                </td>
                                                                                <td>
                                                                                    <h5 class="font-weight-bold mb-0"><?= $city['total'] ?> orders</h5>
                                                                                </td>
                                                                            </tr>
                                                                        </table>
                                                                    <?php endforeach; ?>



                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="raw">
                        <div class=" grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                    <p class="card-title mb-0">Top Products</p>
                                    <div class="table-responsive">
                                        <table class="table table-striped table-borderless">
                                            <thead>
                                                <tr>
                                                    <th>Product</th>
                                                    <th> profit</th>
                                                    <th>Date</th>
                                                    <th>#Stocks</th>
                                                </tr>
                                            </thead>
                                            <tbody>


                                                <?php foreach ($data['profitByProduct'] as $productName => $profit): ?>

                                                    <tr>
                                                        <td><?= $productName ?></td>
                                                        <td class="font-weight-bold">$<?= $profit ?></td>
                                                        <td><?= $data['products'][$productName]->createdAt ?></td>
                                                        <td class="font-weight-medium">
                                                            <div class="badge badge-success"><?= $data['products'][$productName]->stock ?> </div>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>



                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="row">
                        <div class=" stretch-card grid-margin">
                            <div class="card">
                                <div class="card-body">
                                    <p class="card-title mb-0">Highest Orders</p>
                                    <div class="table-responsive">
                                        <table class="table table-borderless">
                                            <thead>
                                                <tr>

                                                    <th class="border-bottom pb-2">Total Amount</th>
                                                    <th class="border-bottom pb-2">Users</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($orders as $order) : ?>
                                                    <tr>
                                                        <td>
                                                            <p class="mb-0"><span class="font-weight-bold mr-2">$<?= $order['total_amount'] ?></span></p>
                                                        </td>
                                                        <td class="text-muted"><?= $order['name'] ?></td>
                                                    </tr>
                                                <?php endforeach ?>

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>


            <!-- main-panel ends -->
        </div>
        <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->


</body>

</html>