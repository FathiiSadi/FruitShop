<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Addresses $model */

$this->title = 'Update Addresses: ' . $model->address_id;
$this->params['breadcrumbs'][] = ['label' => 'Addresses', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->address_id, 'url' => ['view', 'address_id' => $model->address_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="addresses-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
