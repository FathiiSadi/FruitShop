<?php
$this->title = "Order Details";

use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var app\models\Orders $order */
?>

<style>
    .order-details-card {
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        border-radius: 15px;
        border: none;
    }

    .order-details-table {
        width: 100%;
        margin: 20px 0;
    }

    .order-details-table th {
        padding: 15px;
        color: #6c757d;
        width: 35%;
        font-weight: 600;
        border-bottom: 1px solid darkgrey;
    }

    .order-details-table td {
        padding: 15px;
        color: #343a40;
        border-bottom: 1px solid darkgrey;
    }

    .order-header {
        background-color: #f8f9fa;
        padding: 20px;
        border-radius: 15px 15px 0 0;
        border-bottom: 2px solid darkgrey;
    }

    .table-header {
        color: green;
    }

    .total {
        font-size: 30px;
    }

    /* .total th {
        color: #343a40;
    } */
</style>

<div class="container py-5 mt-100">
    <div class="">
        <a onclick="history.back()"><i class="fa fa-arrow-left ms-5" aria-hidden="true"> go back</i>
        </a>

    </div>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card order-details-card">
                <div class="order-header">
                    <h3 class="table-header mb-0">Order Details</h3>
                </div>
                <div class="card-body">
                    <table class="order-details-table">
                        <tr>
                            <th><i class="fas fa-hashtag me-2"></i>Order ID</th>
                            <td><?= $order->order_id ?></td>
                        </tr>
                        <tr>
                            <th><i class="far fa-calendar-alt me-2"></i>Order Date</th>
                            <td><?= new \DateTime($order->order_date)->format('Y-m-d - D H:mA') ?></td>
                        </tr>
                        <tr>
                            <th><i class="fas fa-info-circle me-2"></i>Order Status</th>
                            <td><span class="badge bg-primary"><?= $order->status ?></span></td>
                        </tr>
                        <tr>
                            <th><i class="far fa-user me-2"></i>Username</th>
                            <td><?= $order->user->username ?></td>
                        </tr>
                        <tr>
                            <th><i class="fas fa-map-marker-alt me-2"></i>Place</th>
                            <td><?= $order->address->country ?> / <?= $order->address->city ?></td>
                        </tr>
                        <tr>
                            <th><i class="fas fa-road me-2"></i>Street Address</th>
                            <td><?= $order->address->street_address ?></td>
                        </tr>
                        <tr>
                            <th><i class="fas fa-phone me-2"></i>Phone Number</th>
                            <td><?= $order->address->phone_number ?></td>
                        </tr>
                        <tr class="total">
                            <th><i class="fas fa-money-bill me-2"></i>Total</th>
                            <td>$<?= $order->total_amount ?></td>

                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>