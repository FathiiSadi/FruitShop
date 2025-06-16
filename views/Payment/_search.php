<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\Models\PaymentSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="payments-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'payment_id') ?>

    <?= $form->field($model, 'order_id') ?>

    <?= $form->field($model, 'payment_method') ?>

    <?= $form->field($model, 'amount') ?>

    <?= $form->field($model, 'payment_status') ?>

    <?php // echo $form->field($model, 'payment_date') ?>

    <?php // echo $form->field($model, 'cardholder_name') ?>

    <?php // echo $form->field($model, 'last_four_digits') ?>

    <?php // echo $form->field($model, 'expiry_month') ?>

    <?php // echo $form->field($model, 'expiry_year') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
