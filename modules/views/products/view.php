<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\modules\models\Products $model */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Products', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="products-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'ProductID' => $model->ProductID], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'ProductID' => $model->ProductID], [
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
            'ProductID',
            'name',
            'price',
            'Description:ntext',
            'category',
            'stock',
            'ImageURL',
            'createdAt',
            'updatedAt',
        ],
    ]) ?>

</div>