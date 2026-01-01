<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\AddressSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="addresses-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'user_id') ?>

    <?= $form->field($model, 'recipient_name') ?>

    <?= $form->field($model, 'street_address') ?>

    <?= $form->field($model, 'city') ?>

    <?php echo $form->field($model, 'state') ?>

    <?php echo $form->field($model, 'postal_code') ?>

    <?php echo $form->field($model, 'country') ?>

    <?php echo $form->field($model, 'phone_number') ?>

    <?php echo $form->field($model, 'is_default') ?>

    <?php echo $form->field($model, 'created_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>