<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Cart $cart */
/** @var app\models\Orders $orderModel */
/** @var app\models\Addresses $addressModel */
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
                <?php $form = ActiveForm::begin([
                    'id' => 'checkout-form',
                    'method' => 'post',
                    'action' => ['checkout/save-address'],
                    'options' => ['enctype' => 'multipart/form-data']
                ]); ?>

                <div class="row">
                    <!-- Left Column - Address Form -->
                    <div class="col-lg-6 mb-4">
                        <h4>Shipping Address</h4>

                        <!-- Hidden field for UserID -->
                        <?= $form->field($addressModel, 'UserID')->hiddenInput(['value' => Yii::$app->user->id])->label(false) ?>

                        <div class="form-group">
                            <?= $form->field($addressModel, 'recipient_name')->textInput([
                                'class' => 'form-control',
                                'placeholder' => 'Recipient Name',
                                'required' => true
                            ]) ?>
                        </div>

                        <!-- Keep all other address fields as they were -->
                        <?= $form->field($addressModel, 'street_address')->textInput(['maxlength' => true]) ?>
                        <?= $form->field($addressModel, 'city')->textInput(['maxlength' => true]) ?>
                        <?= $form->field($addressModel, 'state')->textInput(['maxlength' => true]) ?>
                        <?= $form->field($addressModel, 'postal_code')->textInput(['maxlength' => true]) ?>
                        <?= $form->field($addressModel, 'country')->textInput(['maxlength' => true]) ?>
                        <?= $form->field($addressModel, 'phone_number')->textInput(['maxlength' => true]) ?>

                        <div class="form-group text-center mt-4">
                            <!-- Changed to submit button that submits the form -->
                            <?= Html::submitButton('Place Orders', [
                                'class' => 'btn btn-primary btn-lg',
                                'id' => 'place-order-btn',
                                'name' => 'place-order'
                            ]) ?>
                        </div>

                    </div>

                    <!-- Right Column - Order Summary -->
                    <div class="col-lg-6 mb-4">
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
                    </div>
                </div>

                <?php ActiveForm::end(); ?>

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


    <script>
        $(document).ready(function() {
            $('#place-order-btn').click(function(e) {
                console.log('Place Order button clicked');
                // Form will be submitted automatically since it's a submit button
                // Add client-side validation here if needed

                // Example validation:
                var recipientName = $('input[name="Addresses[recipient_name]"]').val();
                if (!recipientName || recipientName.trim() === '') {
                    alert('Please enter recipient name');
                    e.preventDefault();
                    return false;
                }
            });
        });
    </script>
</body>