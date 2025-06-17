    <?php

    use app\models\Orders;
    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\widgets\ActiveForm;

    /** @var yii\widgets\ActiveForm $form */

    ?>
    <div class="container mt-100">
        <div class="row">
            <div class="col-md-6">
                <h2>Order Summary</h2>

                <div class="order-details">
                    <?php foreach ($cart->cartItems as $item) : ?>
                        <div class="detail-row">
                            <h4><?= $item->product->name ?> ($<?= $item->price ?> per unit) :</h4>
                            <h5><?= $item->quantity  ?> * $<?= $item->price ?></h5>
                        </div>
                    <?php endforeach ?>
                    <?php $form = ActiveForm::begin(); ?>
                    <div class="detail-row">
                        <h4>Subtotal:</h4>
                        <h5>$<?= number_format((float)$model->subtotal, 2) ?></h5>
                    </div>

                    <div class="detail-row">
                        <h4>Tax Amount:</h4>
                        <h5>$<?= number_format((float)$model->tax_amount, 2) ?></h5>
                    </div>

                    <div class="detail-row">
                        <h4>Shipping Cost:</h4>
                        <h5>$<?= number_format((float)$model->shipping_cost, 2) ?></h5>
                    </div>

                    <div class="detail-row total">
                        <h4>Total Amount:</h4>
                        <h5>$<?= number_format((float)$model->total_amount, 2) ?></h5>
                    </div>

                    <div class="detail-row">
                        <h4>Status:</h4>
                        <h5><?= $model->status ?></h5>
                    </div>

                    <div class="detail-row">
                        <h4>Order Date:</h4>
                        <h5><?= $model->order_date ?></h5>
                    </div>

                    <?php if ($model->order_id): ?>
                        <div class="detail-row">
                            <h4>Order ID:</h4>
                            <h5><?= $model->order_id ?></h5>
                        </div>
                    <?php else: ?>
                        <div class="detail-row">
                            <h4>Order ID: no</h4>

                        </div>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <?= Html::a('Save', ['success', 'order_id' => $model->order_id], ['class' => 'btn btn-success']) ?>
                </div>

                <?php ActiveForm::end(); ?>

            </div>
        </div>
    </div>