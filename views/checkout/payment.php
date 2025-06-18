<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Cart $cart */
/** @var app\models\Addresses $addressModel */
/** @var app\models\Payments $paymentModel */

$this->title = 'Payment Details';
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
                        <h1>Payment Details</h1>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end breadcrumb section -->

    <!-- payment section -->
    <div class="checkout-section mt-150 mb-150">
        <div class="container">
            <?php $form = ActiveForm::begin([
                'id' => 'payment-form',
                'method' => 'post',
                'action' => ['checkout/process-payment'],
                'options' => ['enctype' => 'multipart/form-data']
            ]); ?>

            <div class="row">
                <!-- Left Column - Payment Form -->
                <div class="col-lg-6 mb-4">
                    <h4>Payment Information</h4>

                    <!-- Payment Method Selection -->
                    <div class="form-group">
                        <label for="payment-method"><strong>Payment Method</strong></label>
                        <?= $form->field($paymentModel, 'payment_method')->radioList([
                            'cash_on_delivery' => 'Cash on Delivery',
                            'visa' => 'Credit/Debit Card'
                        ], [
                            'item' => function ($index, $label, $name, $checked, $value) {
                                return '<div class="form-check mb-3">' .
                                    Html::radio($name, $checked, [
                                        'value' => $value,
                                        'class' => 'form-check-input payment-method-radio',
                                        'id' => 'payment_method_' . $value
                                    ]) .
                                    '<label class="form-check-label ms-2" for="payment_method_' . $value . '">' .
                                    '<strong>' . $label . '</strong>' .
                                    '</label></div>';
                            }
                        ])->label(false) ?>
                    </div>

                    <!-- Credit Card Details (Hidden by default) -->
                    <div id="card-details" style="display: none;">
                        <h5>Credit Card Information</h5>

                        <div class="form-group">
                            <?= $form->field($paymentModel, 'cardholder_name')->textInput([
                                'class' => 'form-control',
                                'placeholder' => 'Full Name on Card',
                                'id' => 'cardholder_name'
                            ]) ?>
                        </div>

                        <div class="form-group">
                            <label for="card_number">Card Number</label>
                            <input type="text"
                                class="form-control"
                                id="card_number"
                                name="card_number"
                                placeholder="1234 5678 9012 3456"
                                maxlength="19">
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <?= $form->field($paymentModel, 'expiry_month')->dropDownList([
                                    '' => 'Month',
                                    '01' => '01 - January',
                                    '02' => '02 - February',
                                    '03' => '03 - March',
                                    '04' => '04 - April',
                                    '05' => '05 - May',
                                    '06' => '06 - June',
                                    '07' => '07 - July',
                                    '08' => '08 - August',
                                    '09' => '09 - September',
                                    '10' => '10 - October',
                                    '11' => '11 - November',
                                    '12' => '12 - December'
                                ], ['class' => 'form-control']) ?>
                            </div>
                            <div class="col-md-6">
                                <?php
                                $years = [];
                                $currentYear = date('Y');
                                for ($i = $currentYear; $i <= $currentYear + 10; $i++) {
                                    $years[$i] = $i;
                                }
                                ?>
                                <?= $form->field($paymentModel, 'expiry_year')->dropDownList(
                                    array_merge(['' => 'Year'], $years),
                                    ['class' => 'form-control']
                                ) ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="cvv">CVV</label>
                            <input type="text"
                                class="form-control"
                                id="cvv"
                                name="cvv"
                                placeholder="123"
                                maxlength="4"
                                style="max-width: 100px;">
                        </div>
                    </div>

                    <!-- Hidden amount field -->
                    <?= $form->field($paymentModel, 'amount')->hiddenInput()->label(false) ?>

                    <div class="form-group text-center mt-4">
                        <?= Html::submitButton('Complete Order', [
                            'class' => 'btn btn-success btn-lg',
                            'id' => 'complete-order-btn'
                        ]) ?>

                        <?= Html::a('Back to Address', ['checkout/index'], [
                            'class' => 'btn btn-secondary btn-lg ms-2'
                        ]) ?>
                    </div>
                </div>

                <!-- Right Column - Order Summary and Address -->
                <div class="col-lg-6 mb-4">
                    <!-- Shipping Address Summary -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5>Shipping Address</h5>
                        </div>
                        <div class="card-body">
                            <p><strong><?= Html::encode($addressModel->recipient_name) ?></strong></p>
                            <p><?= Html::encode($addressModel->street_address) ?></p>
                            <p><?= Html::encode($addressModel->city) ?>, <?= Html::encode($addressModel->state) ?> <?= Html::encode($addressModel->postal_code) ?></p>
                            <p><?= Html::encode($addressModel->country) ?></p>
                            <p>Phone: <?= Html::encode($addressModel->phone_number) ?></p>
                        </div>
                    </div>

                    <!-- Order Summary -->
                    <div class="order-details-wrap">
                        <table class="order-details">
                            <thead>
                                <tr>
                                    <th>Order Summary</th>
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
                                    <td>$15.00</td>
                                </tr>
                                <tr class="total-row">
                                    <td><strong>Total</strong></td>
                                    <td><strong>$<?= number_format($cart->getTotalWithTax() + 15, 2) ?></strong></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
    <!-- end payment section -->

    <script>
        $(document).ready(function() {
            // Show/hide card details based on payment method
            $('input[name="Payments[payment_method]"]').change(function() {
                if ($(this).val() === 'visa') {
                    $('#card-details').slideDown();
                    // Make card fields required
                    $('#cardholder_name').attr('required', true);
                    $('#card_number').attr('required', true);
                    $('#payments-expiry_month').attr('required', true);
                    $('#payments-expiry_year').attr('required', true);
                    $('#cvv').attr('required', true);
                } else {
                    $('#card-details').slideUp();
                    // Remove required attribute
                    $('#cardholder_name').removeAttr('required');
                    $('#card_number').removeAttr('required');
                    $('#payments-expiry_month').removeAttr('required');
                    $('#payments-expiry_year').removeAttr('required');
                    $('#cvv').removeAttr('required');
                }
            });

            // Format card number input
            $('#card_number').on('input', function() {
                let value = $(this).val().replace(/\s/g, '').replace(/[^0-9]/g, '');
                let formattedValue = value.replace(/(.{4})/g, '$1 ').trim();
                if (formattedValue.length > 19) {
                    formattedValue = formattedValue.substr(0, 19);
                }
                $(this).val(formattedValue);
            });

            // Only allow numbers for CVV
            $('#cvv').on('input', function() {
                $(this).val($(this).val().replace(/[^0-9]/g, ''));
            });

            // Form validation
            $('#payment-form').submit(function(e) {
                let paymentMethod = $('input[name="Payments[payment_method]"]:checked').val();

                if (!paymentMethod) {
                    alert('Please select a payment method.');
                    e.preventDefault();
                    return false;
                }

                if (paymentMethod === 'visa') {
                    let cardholderName = $('#cardholder_name').val().trim();
                    let cardNumber = $('#card_number').val().replace(/\s/g, '');
                    let expiryMonth = $('#payments-expiry_month').val();
                    let expiryYear = $('#payments-expiry_year').val();
                    let cvv = $('#cvv').val();

                    if (!cardholderName) {
                        alert('Please enter the cardholder name.');
                        e.preventDefault();
                        return false;
                    }

                    if (!cardNumber || cardNumber.length < 13) {
                        alert('Please enter a valid card number.');
                        e.preventDefault();
                        return false;
                    }

                    if (!expiryMonth || !expiryYear) {
                        alert('Please select card expiry date.');
                        e.preventDefault();
                        return false;
                    }

                    if (!cvv || cvv.length < 3) {
                        alert('Please enter a valid CVV.');
                        e.preventDefault();
                        return false;
                    }

                    // Check if card is not expired
                    let currentDate = new Date();
                    let currentYear = currentDate.getFullYear();
                    let currentMonth = currentDate.getMonth() + 1;

                    if (parseInt(expiryYear) < currentYear ||
                        (parseInt(expiryYear) === currentYear && parseInt(expiryMonth) < currentMonth)) {
                        alert('Card has expired. Please use a valid card.');
                        e.preventDefault();
                        return false;
                    }
                }

                // Show loading state
                $('#complete-order-btn').prop('disabled', true).text('Processing...');
            });
        });
    </script>

    <style>
        .card {
            border: 1px solid #ddd;
            border-radius: 8px;
        }

        .card-header {
            background-color: #f8f9fa;
            border-bottom: 1px solid #ddd;
            padding: 15px;
        }

        .card-body {
            padding: 15px;
        }

        .form-check {
            padding: 15px;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            margin-bottom: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .form-check:hover {
            border-color: #007bff;
            background-color: #f8f9fa;
        }

        .form-check input[type="radio"]:checked+label {
            color: #007bff;
        }

        .total-row {
            border-top: 2px solid #ddd;
            font-size: 1.1em;
        }

        #card-details {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-top: 15px;
        }
    </style>
</body>