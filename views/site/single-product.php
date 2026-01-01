<?php

use yii\helpers\Html;
use yii\helpers\Url;



$products = (new \yii\db\Query())
    ->select(['ProductID', 'name', 'price', 'description', 'category', 'stock', 'ImageURL'])
    ->from('Products')
    ->where(['name' => Yii::$app->request->get('name')])
    ->all();
?>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    function addToCart(productId, productname) {
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
                quantity: 1, // Default quantity
                _csrf: '<?= Yii::$app->request->getCsrfToken() ?>'
            },
            success: function(response) {
                if (response.success) {
                    // Show success message
                    showNotification(`${productname} added to cart successfully!`, 'success');

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


    <!-- breadcrumb-section -->
    <div class="breadcrumb-section breadcrumb-bg">


        <div class="container">
            <a onclick="history.back()"><i class="fa fa-arrow-left ms-5" aria-hidden="true"> go back</i>
            </a>
            <div class="row">
                <div class="col-lg-8 offset-lg-2 text-center">
                    <div class="breadcrumb-text">
                        <p>See more Details</p>
                        <h1>Single Product</h1>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end breadcrumb section -->

    <!-- single product -->


    <div class="single-product mt-150 mb-150">
        <div class="container">
            <div class="row">

                <?php
                foreach ($products as $product) : ?>
                    <div class="col-md-5">
                        <div class="single-product-img">
                            <img src=<?php echo Yii::$app->request->baseUrl . '/' . $product['ImageURL']; ?> alt="">
                        </div>
                    </div>
                    <div class="col-md-7">
                        <div class="single-product-content">
                            <h3><?= $product['name'] ?></h3>
                            <p class="single-product-pricing"><span>Per Kg</span> <?= $product['price'] ?>$</p>
                            <p><?= $product['description'] ?></p>
                            <div class="single-product-form">
                                <!-- <form action="index.html">
                                    <input type="number" placeholder="0">
                                </form> -->
                                <button
                                    type="button"
                                    class="cart-btn btn btn-primary"
                                    data-product-id="<?= $product['ProductID'] ?>"
                                    onclick="addToCart(<?= $product['ProductID'] ?>, '<?= Html::encode($product['name']) ?>')">
                                    <i class="fas fa-shopping-cart "></i> Add to Cart
                                </button>
                                <p><strong>Categories: </strong><?= $product['category'] ?></p>
                            </div>
                            <h4>Share:</h4>
                            <ul class="product-share">
                                <li><a href=""><i class="fab fa-facebook-f"></i></a></li>
                                <li><a href=""><i class="fab fa-twitter"></i></a></li>
                                <li><a href=""><i class="fab fa-google-plus-g"></i></a></li>
                                <li><a href=""><i class="fab fa-linkedin"></i></a></li>
                            </ul>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <!-- end single product -->

    <!-- jquery -->
    <script src="assets_static/js/jquery-1.11.3.min.js"></script>
    <!-- bootstrap -->
    <script src="assets_static/bootstrap/js/bootstrap.min.js"></script>
    <!-- count down -->
    <script src="assets_static/js/jquery.countdown.js"></script>
    <!-- isotope -->
    <script src="assets_static/js/jquery.isotope-3.0.6.min.js"></script>
    <!-- waypoints -->
    <script src="assets_static/js/waypoints.js"></script>
    <!-- owl carousel -->
    <script src="assets_static/js/owl.carousel.min.js"></script>
    <!-- magnific popup -->
    <script src="assets_static/js/jquery.magnific-popup.min.js"></script>
    <!-- mean menu -->
    <script src="assets_static/js/jquery.meanmenu.min.js"></script>
    <!-- sticker js -->
    <script src="assets_static/js/sticker.js"></script>
    <!-- main js -->
    <script src="assets_static/js/main.js"></script>

</body>