<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\inventory\GoodsMovement */

$this->title = 'Update Goods ' . ($model->nmType ? ucfirst(strtolower($model->nmType)) : 'Movement').' '.$model->number;
$this->params['breadcrumbs'][] = ['label' => 'Goods Movements', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->number, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="goods-movement-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
