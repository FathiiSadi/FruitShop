<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Orders $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="orders-form">

    <?php $form = ActiveForm::begin(); ?>


    <?= $form->field($model, 'status')->dropDownList([ 'pending' => 'Pending', 'processing' => 'Processing', 'shipped' => 'Shipped', 'delivered' => 'Delivered', 'cancelled' => 'Cancelled', ], ['prompt' => '']) ?>

    <?= $form->field($model, 'subtotal')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'tax_amount')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'shipping_cost')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'total_amount')->textInput(['maxlength' => true]) ?>


    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
