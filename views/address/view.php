<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Addresses $model */

$this->title = $model->address_id;
$this->params['breadcrumbs'][] = ['label' => 'Addresses', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="addresses-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'address_id' => $model->address_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'address_id' => $model->address_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'address_id',
            'UserID',
            'recipient_name',
            'street_address',
            'city',
            'state',
            'postal_code',
            'country',
            'phone_number',
            'is_default',
            'created_at',
        ],
    ]) ?>

</div>
