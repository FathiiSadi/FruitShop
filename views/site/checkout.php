<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Cart $cart */
/** @var app\models\Order $orderModel */
/** @var app\models\Addresses $addressModel */
/** @var app\models\Payments $paymentModel */
?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<body>
    <!-- breadcrumb-section -->
    <div class="breadcrumb-section breadcrumb-bg">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 offset-lg-2 text-center">
                    <div class="breadcrumb-text">
                        <p>Fresh and Organic</p>
                        <h1>Check Out Product</h1>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end breadcrumb section -->

    <!-- check out section -->
    <div class="checkout-section mt-150 mb-150">
        <div class="container">

            <?php if ($cart && !$cart->isEmpty()): ?>
                <div class="row">


                    <!-- Address Form -->
                    <div class="col-lg-6">
                        <h4>Shipping Address</h4>
                        <?php $form = ActiveForm::begin(['id' => 'address-form']); ?>

                        <div class="form-group">
                            <?= $form->field($addressModel, 'recipient_name')->textInput(['class' => 'form-control', 'placeholder' => 'Recipient Name']) ?>
                        </div>
                        <div class="form-group">
                            <?= $form->field($addressModel, 'street_address')->textInput(['class' => 'form-control', 'placeholder' => 'Street Address']) ?>
                        </div>
                        <div class="form-group">
                            <?= $form->field($addressModel, 'city')->textInput(['class' => 'form-control', 'placeholder' => 'City']) ?>
                        </div>
                        <div class="form-group">
                            <?= $form->field($addressModel, 'state')->textInput(['class' => 'form-control', 'placeholder' => 'State']) ?>
                        </div>
                        <div class="form-group">
                            <?= $form->field($addressModel, 'postal_code')->textInput(['class' => 'form-control', 'placeholder' => 'Postal Code']) ?>
                        </div>
                        <div class="form-group">
                            <?= $form->field($addressModel, 'country')->textInput(['class' => 'form-control', 'placeholder' => 'Country']) ?>
                        </div>
                        <div class="form-group">
                            <?= $form->field($addressModel, 'phone_number')->textInput(['class' => 'form-control', 'placeholder' => 'Phone Number']) ?>
                        </div>
                    </div>

                    <!-- Payment Form -->
                    <div class="col-lg-6">
                        <h4>Payment Details</h4>
                        <?= $form->field($paymentModel, 'cardholder_name')->textInput(['class' => 'form-control', 'placeholder' => 'Cardholder Name']) ?>
                        <?= $form->field($paymentModel, 'payment_method')->dropDownList(['Credit Card' => 'Credit Card', 'PayPal' => 'PayPal'], ['class' => 'form-control']) ?>
                        <?= $form->field($paymentModel, 'last_four_digits')->textInput(['class' => 'form-control', 'placeholder' => 'Last Four Digits']) ?>
                        <?= $form->field($paymentModel, 'expiry_month')->textInput(['class' => 'form-control', 'placeholder' => 'Expiry Month (MM)']) ?>
                        <?= $form->field($paymentModel, 'expiry_year')->textInput(['class' => 'form-control', 'placeholder' => 'Expiry Year (YYYY)']) ?>
                    </div>

                    <div class="order-details-wrap mb-5">
                        <table class="order-details">
                            <thead>
                                <tr>
                                    <th>Your Order Details</th>
                                    <th>Price</th>
                                </tr>
                            </thead>
                            <tbody class="order-details-body">
                                <tr>
                                    <td><strong>Product</strong></td>
                                    <td><strong>Total</strong></td>
                                </tr>
                                <?php foreach ($cart->cartItems as $item): ?>
                                    <tr>
                                        <td>
                                            <?= Html::encode($item->product->name) ?>
                                            <span class="product-quantity">× <?= $item->quantity ?></span>
                                        </td>
                                        <td>$<?= number_format($item->price * $item->quantity, 2) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td>Subtotal</td>
                                    <td>$<?= number_format($cart->getSubtotal(), 2) ?></td>
                                </tr>
                                <tr>
                                    <td>Tax</td>
                                    <td>$<?= number_format($cart->getTaxAmount(), 2) ?></td>
                                </tr>
                                <tr>
                                    <td>Shipping</td>
                                    <td>$15</td>
                                </tr>
                                <tr>
                                    <td><strong>Total</strong></td>
                                    <td><strong>$<?= number_format($cart->getTotalWithTax() + 15, 2) ?></strong></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <div class="col-lg-12">
                        <div class="form-group text-center mt-3">
                            <?= Html::submitButton('Place Order', ['class' => 'btn btn-primary']) ?>
                        </div>
                        <?php ActiveForm::end(); ?>
                    </div>
                </div>


            <?php else: ?>
                <!-- Empty Cart Message -->
                <div class="row">
                    <div class="col-lg-12 text-center">
                        <h3>Your cart is empty!</h3>
                        <a href="<?= Url::to(['site/shop']) ?>" class="btn btn-primary">Continue Shopping</a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <!-- end check out section -->
</body>