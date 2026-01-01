<?php

use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var app\models\Orders $order */

$this->title = 'Order Success';
?>

<div class="container mt-100">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body text-center">
                    <div class="mb-4">
                        <i class="fas fa-check-circle text-success" style="font-size: 4rem;"></i>
                    </div>

                    <h2 class="text-success mb-3">Order Placed Successfully!</h2>

                    <div class="order-details mb-4">
                        <p class="lead">Thank you for your order!</p>
                        <div class="order-info">
                            <p><strong>Order ID:</strong> <?= Html::encode($order->id) ?></p>
                            <p><strong>Order Date:</strong> <?= Html::encode(date('F j, Y', strtotime($order->order_date))) ?></p>
                            <p><strong>Total Amount:</strong> $<?= Html::encode(number_format($order->total_amount, 2)) ?></p>
                            <p><strong>Status:</strong> <span class="badge badge-info"><?= Html::encode($order->displayStatus()) ?></span></p>
                        </div>
                    </div>

                    <div class="order-actions">
                        <?= Html::a('View Order Details', ['orders/view', 'id' => $order->id], [
                            'class' => 'btn btn-primary me-2'
                        ]) ?>

                        <?= Html::a('Continue Shopping', Url::to(['site/shop']), [
                            'class' => 'btn btn-outline-primary me-2'
                        ]) ?>

                        <?= Html::a('Return to Homepage', Yii::$app->homeUrl, [
                            'class' => 'btn btn-secondary'
                        ]) ?>
                    </div>


                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .card {
        border: none;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        border-radius: 0.375rem;
    }

    .order-info {
        background-color: #f8f9fa;
        padding: 1rem;
        border-radius: 0.25rem;
        margin: 1rem 0;
    }

    .order-info p {
        margin-bottom: 0.5rem;
    }

    .order-actions .btn {
        margin: 0.25rem;
    }

    @media (max-width: 768px) {
        .order-actions .btn {
            display: block;
            width: 100%;
            margin-bottom: 0.5rem;
        }
    }
</style>