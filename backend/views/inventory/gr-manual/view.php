<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\inventory\GoodsMovement */

$this->title = $model->number;
$this->params['breadcrumbs'][] = ['label' => 'Goods Movements', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="goods-movement-view">

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
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
            'number',
            'warehouse_id',
            'date',
            'type',
            'reff_type',
            'reff_id',
            'vendor_id',
            'description',
            'status',
        ],
    ]) ?>

</div>
