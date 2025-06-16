<?php

use yii\helpers\Url;
use yii\helpers\Html;

?>

<div class="checkout-success">
    <div class="container">
        <div class="row">
            <div class="col-md-8 offset-md-2 text-center">
                <h1>Order Confirmation</h1>
                <div class="alert alert-success">
                    <p>Thank you for your order!</p>
                    <p>Your order ID is: <strong>#<?= $order->order_id ?></strong></p>
                </div>

                <h3>Order Summary</h3>
                <div class="order-summary">
                    <p>Subtotal: $<?= number_format($order->subtotal, 2) ?></p>
                    <p>Tax: $<?= number_format($order->tax_amount, 2) ?></p>
                    <p>Shipping: $<?= number_format($order->shipping_cost, 2) ?></p>
                    <p class="total">Total: $<?= number_format($order->total_amount, 2) ?></p>
                </div>

                <a href="<?= Url::to(['site/index']) ?>" class="btn btn-primary">
                    Continue Shopping
                </a>
            </div>
        </div>
    </div>
</div>