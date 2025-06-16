<?php

use yii\helpers\Html;
use yii\helpers\Url;
?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    function updateCartquantity(productId, quantity) {
        if (quantity < 1) {
            if (confirm('Remove this item from cart?')) {
                removeFromCart(productId);
            }
            return;
        }

        $.ajax({
            url: '/site/update-cart',
            type: 'POST',
            data: {
                productId: productId,
                quantity: quantity,
                _csrf: '<?= Yii::$app->request->getCsrfToken() ?>'
            },
            success: function(response) {
                if (response.success) {
                    // Update the total for this row
                    updateRowTotal(productId, quantity);
                    // Update cart totals
                    updateCartTotals();
                    showNotification('Cart updated successfully!', 'success');
                } else {
                    showNotification(response.message || 'Failed to update cart.', 'error');
                }
            },
            error: function() {
                showNotification('Something went wrong. Please try again.', 'error');
            }
        });
    }

    function removeFromCart(productId) {
        if (!confirm('Are you sure you want to remove this item from your cart?')) {
            return;
        }

        $.ajax({
            url: '/site/remove-from-cart',
            type: 'POST',
            data: {
                productId: productId,
                _csrf: '<?= Yii::$app->request->getCsrfToken() ?>'
            },
            success: function(response) {
                if (response.success) {
                    // Remove the row from the table
                    $(`tr[data-product-id="${productId}"]`).fadeOut(300, function() {
                        $(this).remove();
                        updateCartTotals();
                        checkEmptyCart();
                    });
                    showNotification('Item removed from cart!', 'success');
                } else {
                    showNotification(response.message || 'Failed to remove item.', 'error');
                }
            },
            error: function() {
                showNotification('Something went wrong. Please try again.', 'error');
            }
        });
    }

    function updateRowTotal(productId, quantity) {
        const row = $(`tr[data-product-id="${productId}"]`);
        const price = parseFloat(row.find('.product-price').text().replace('$', ''));
        const total = (price * quantity).toFixed(2);
        row.find('.product-total').text('$' + total);
    }

    function updateCartTotals() {
        let subtotal = 0;
        $('.product-total').each(function() {
            const amount = parseFloat($(this).text().replace('$', ''));
            subtotal += amount;
        });

        $('#cart-subtotal').text('$' + subtotal.toFixed(2));
        // Add tax calculation if needed
        const tax = subtotal * 0.1; // 10% tax example
        $('#cart-tax').text('$' + tax.toFixed(2));
        $('#cart-total').text('$' + (subtotal + tax).toFixed(2));
    }

    function checkEmptyCart() {
        if ($('.table-body-row').length === 0) {
            $('.cart-table tbody').html(`
                <tr>
                    <td colspan="6" class="text-center">Your cart is empty.</td>
                </tr>
            `);
        }
    }

    function showNotification(message, type) {
        $('.notification').remove();

        const notificationClass = type === 'success' ? 'alert-success' : 'alert-danger';
        const notification = `
            <div class="notification alert ${notificationClass} alert-dismissible" style="position: fixed; top: 20px; right: 20px; z-index: 9999; min-width: 300px;">
                ${message}
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
        `;

        $('body').append(notification);

        setTimeout(function() {
            $('.notification').fadeOut();
        }, 3000);
    }

    // Handle quantity input changes
    $(document).on('change', '.quantity-input', function() {
        const productId = $(this).data('product-id');
        const quantity = parseInt($(this).val());
        updateCartquantity(productId, quantity);
    });
</script>

<style>
    .quantity-input {
        width: 60px;
        text-align: center;
        border: 1px solid #ddd;
        border-radius: 4px;
        padding: 5px;
    }

    .remove-btn {
        color: #dc3545;
        font-size: 18px;
        transition: color 0.3s ease;
    }

    .remove-btn:hover {
        color: #c82333;
        text-decoration: none;
    }

    .cart-summary {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 8px;
        margin-top: 20px;
    }
</style>

<!-- Cart Table -->
<div class="cart-section mt-150 mb-150">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-md-12">
                <div class="cart-table-wrap">
                    <table class="cart-table">
                        <thead class="cart-table-head">
                            <tr class="table-head-row">
                                <th class="product-remove"></th>
                                <th class="product-image">Product Image</th>
                                <th class="product-name">name</th>
                                <th class="product-price">price</th>
                                <th class="product-quantity">quantity</th>
                                <th class="product-total">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($cart && $cart->cartItems): ?>
                                <?php foreach ($cart->cartItems as $item): ?>
                                    <tr class="table-body-row" data-product-id="<?= $item->ProductID ?>">
                                        <td class="product-remove">
                                            <a href="javascript:void(0)"
                                                class="remove-btn"
                                                onclick="removeFromCart(<?= $item->ProductID ?>)">
                                                <i class="far fa-window-close"></i>
                                            </a>
                                        </td>
                                        <td class="product-image">
                                            <?php if ($item->product): ?>
                                                <img src="<?= Yii::getAlias('@web' . '/' . $item->product->ImageURL) ?>"
                                                    alt="<?= Html::encode($item->product->name) ?>"
                                                    <?php else: ?>
                                                    <img src="<?= Yii::getAlias('@web/images/no-image.png') ?>"
                                                    alt="No image available"
                                                    <?php endif; ?>
                                                    style="width: 80px; height: 60px; object-fit: cover;">
                                        </td>
                                        <td class="product-name"><?= Html::encode($item->product->name) ?></td>
                                        <td class="product-price">$<?= number_format($item->price, 2) ?></td>
                                        <td class="product-quantity">
                                            <input type="number"
                                                class="quantity-input"
                                                value="<?= $item->quantity ?>"
                                                min="1"
                                                max="99"
                                                data-product-id="<?= $item->ProductID ?>">
                                        </td>
                                        <td class="product-total">$<?= number_format($item->price * $item->quantity, 2) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center">Your cart is empty.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Cart Summary -->
            <div class="col-lg-4 col-md-12">
                <div class="cart-summary">
                    <h4>Cart Summary</h4>
                    <div class="d-flex justify-content-between">
                        <span>Subtotal:</span>
                        <span id="cart-subtotal">
                            $<?= $cart ? number_format($cart->getSubtotal(), 2) : '0.00' ?>
                        </span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>Tax (10%):</span>
                        <span id="cart-tax">
                            $<?= $cart ? number_format($cart->getSubtotal() * 0.1, 2) : '0.00' ?>
                        </span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between font-weight-bold">
                        <span>Total:</span>
                        <span id="cart-total">
                            $<?= $cart ? number_format($cart->getSubtotal() * 1.1, 2) : '0.00' ?>
                        </span>
                    </div>



                    <div class="mt-3">
                        <a href="/checkout" class="btn btn-primary btn-block">
                            Proceed to Checkout
                        </a>
                        <a href="/site/shop" class="btn btn-outline-secondary btn-block mt-2">
                            Continue Shopping
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>