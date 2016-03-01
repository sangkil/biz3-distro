<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\inventory\GoodsMovementDtl */

$this->title = $model->movement_id;
$this->params['breadcrumbs'][] = ['label' => 'Goods Movement Dtls', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="goods-movement-dtl-view">

    <p class="pull-right">
        <?= Html::a('Create New', ['create'], ['class' => 'btn btn-default']) ?>
        <?= Html::a('Update', ['update', 'movement_id' => $model->movement_id, 'product_id' => $model->product_id], ['class' => 'btn btn-default']) ?>
        <?= Html::a('Delete', ['delete', 'movement_id' => $model->movement_id, 'product_id' => $model->product_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'options' => ['class' => 'table table-hover'],
        'template' => '<tr><th style="width:20%;">{label}</th><td>{value}</td></tr>',
        'model' => $model,
        'attributes' => [
            'movement_id',
            'product_id',
            'uom_id',
            'qty',
            'value',
            'cogs',
        ],
    ]) ?>

</div>
