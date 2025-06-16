<?php

$products = (new \yii\db\Query())
    ->select(['productID', 'name', 'price', 'description', 'category', 'stock', 'ImageURL'])
    ->from('Products')
    ->all();
?>


<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\db\Query;
?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    function addToCart(productId, productName) {
        // Show loading state
        const button = $(`button[data-product-id="${productId}"]`);
        const originalText = button.html();
        button.html('<i class="fas fa-spinner fa-spin"></i> Adding...');
        button.prop('disabled', true);

        $.ajax({
            url: '/site/add-to-cart',
            type: 'POST',
            data: {
                productId: productId,
                quantity: 1,
                _csrf: '<?= Yii::$app->request->getCsrfToken() ?>'
            },
            success: function(response) {
                if (response.success) {
                    // Show success message
                    showNotification(`${productName} added to cart successfully!`, 'success');

                    // Update cart count if you have a cart counter in your header
                    updateCartCount();

                    // Reset button
                    button.html('<i class="fas fa-shopping-cart"></i> Add to Cart');
                    button.prop('disabled', false);
                } else {
                    showNotification(response.message || 'Failed to add item to cart.', 'error');
                    button.html(originalText);
                    button.prop('disabled', false);
                }
            },
            error: function() {
                showNotification('Something went wrong. Please try again.', 'error');
                button.html(originalText);
                button.prop('disabled', false);
            }
        });
    }

    function updateCartCount() {
        $.ajax({
            url: '/site/cart-count',
            type: 'GET',
            success: function(response) {
                if (response.success) {
                    $('.cart-count').text(response.count);
                }
            }
        });
    }

    function showNotification(message, type) {
        // Remove any existing notifications
        $('.notification').remove();

        // Create notification element
        const notificationClass = type === 'success' ? 'alert-success' : 'alert-danger';
        const notification = `
            <div class="notification alert ${notificationClass} alert-dismissible" style="position: fixed; top: 20px; right: 20px; z-index: 9999; min-width: 300px;">
                ${message}
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
        `;

        $('body').append(notification);

        // Auto-hide after 3 seconds
        setTimeout(function() {
            $('.notification').fadeOut();
        }, 3000);
    }
</script>


<body>


    <div class="cart-info" style="display: none;">
        <span class="cart-count">0</span> items in cart
    </div>

    <!-- breadcrumb-section -->
    <div class="breadcrumb-section breadcrumb-bg">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 offset-lg-2 text-center">
                    <div class="breadcrumb-text">
                        <p>Fresh and Organic</p>
                        <h1>Shop</h1>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end breadcrumb section -->

    <!-- products -->
    <div class="product-section mt-150 mb-150">
        <div class="container">

            <div class="row">
                <div class="col-md-12">
                    <div class="product-filters">
                        <ul>
                            <li class="active" data-filter="*">All</li>
                            <li data-filter=".fruits">fruits</li>
                            <li data-filter=".berry">Berry</li>
                            <li data-filter=".citrus">Citrus</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="row product-lists">

                <?php foreach ($products as $product) : ?>
                    <div class="col-lg-4 col-md-6 text-center <?= strtolower($product['category']) ?>">
                        <div class="single-product-item">
                            <a href="/site/single-product?name=<?= urlencode($product['name']) ?>">
                                <div class="product-image">
                                    <img src="<?php echo Yii::$app->request->baseUrl . '/' . $product['ImageURL']; ?>" alt="<?= Html::encode($product['name']) ?>" />
                                </div>
                            </a>
                            <h3><?php echo Html::encode($product['name']); ?></h3>
                            <p class="product-price"><span>Per Kg</span> $<?php echo $product['price']; ?></p>

                            <button
                                type="button"
                                class="cart-btn btn btn-primary"
                                data-product-id="<?= $product['productID'] ?>"
                                onclick="addToCart(<?= $product['productID'] ?>, '<?= Html::encode($product['name']) ?>')">
                                <i class="fas fa-shopping-cart "></i> Add to Cart
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <!-- end products -->



</body>