<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\master\Cogs */

$this->title = $model->product_id;
$this->params['breadcrumbs'][] = ['label' => 'Cogs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cogs-view">

    <p class="pull-right">
        <?= Html::a('Create New', ['create'], ['class' => 'btn btn-default']) ?>
        <?= Html::a('Update', ['update', 'id' => $model->product_id], ['class' => 'btn btn-default']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->product_id], [
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
            'product_id',
            'cogs',
            'last_purchase_price',
            'created_at',
            'created_by',
            'updated_at',
            'updated_by',
        ],
    ]) ?>

</div>
