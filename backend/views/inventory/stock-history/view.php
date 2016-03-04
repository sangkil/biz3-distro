<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\inventory\ProductStockHistory */

$this->title = $model->time;
$this->params['breadcrumbs'][] = ['label' => 'Product Stock Histories', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-stock-history-view">

    <p class="pull-right">
        <?= Html::a('Create New', ['create'], ['class' => 'btn btn-default']) ?>
        <?= Html::a('Update', ['update', 'time' => $model->time, 'warehouse_id' => $model->warehouse_id, 'product_id' => $model->product_id], ['class' => 'btn btn-default']) ?>
        <?= Html::a('Delete', ['delete', 'time' => $model->time, 'warehouse_id' => $model->warehouse_id, 'product_id' => $model->product_id], [
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
            'time',
            'warehouse_id',
            'product_id',
            'qty_movement',
            'qty_current',
            'movement_id',
        ],
    ]) ?>

</div>
