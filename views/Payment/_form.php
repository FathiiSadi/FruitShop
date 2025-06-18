<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Payments $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="payments-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'order_id')->textInput() ?>

    <?= $form->field($model, 'payment_method')->dropDownList(['cash_on_delivery' => 'Cash on delivery', 'visa' => 'Visa',], ['prompt' => '']) ?>

    <!-- <?= $form->field($model, 'amount')->textInput(['maxlength' => true]) ?> -->

    <?= $form->field($model, 'payment_status')->dropDownList(['pending' => 'Pending', 'completed' => 'Completed', 'failed' => 'Failed', 'refunded' => 'Refunded',], ['prompt' => '']) ?>


    <?= $form->field($model, 'cardholder_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'last_four_digits')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'expiry_month')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'expiry_year')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>