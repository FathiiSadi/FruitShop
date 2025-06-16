<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Addresses $model */

$this->title = 'Create Addresses';
$this->params['breadcrumbs'][] = ['label' => 'Addresses', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="addresses-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
