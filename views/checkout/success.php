<?php

use yii\helpers\Url;
?>

<div class="breadcrumb-section breadcrumb-bg">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 offset-lg-2 text-center">
                <div class="breadcrumb-text">
                    <h1>Payment Successful</h1>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="mt-150 mb-150">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 offset-lg-2 text-center">
                <div class="success-message">
                    <i class="fas fa-check-circle success-icon"></i>
                    <h2>Thank You!</h2>
                    <p>Your payment was processed successfully.</p>
                    <p>Order reference: <?= Yii::$app->session->get('checkout_order_id') ?></p>
                    <a href="<?= Url::to(['orders/index']) ?>" class="boxed-btn">View Your Orders</a>
                </div>
            </div>
        </div>
    </div>
</div>