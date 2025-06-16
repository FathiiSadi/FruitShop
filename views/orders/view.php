<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Orders $model */

$this->title = $model->order_id;
$this->params['breadcrumbs'][] = ['label' => 'Orders', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="orders-view">

    <h1><?= Html::encode($model->getUsername()) ?>'s order</h1>



    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'order_id',
            [
                'attribute' => 'Username',
                'value' => $model->getUsername()
            ],
            // 'address_id',
            'order_date',
            'status',
            'subtotal',
            'tax_amount',
            'shipping_cost',
            'total_amount',
            // 'notes:ntext',
        ],
    ]) ?>

</div>