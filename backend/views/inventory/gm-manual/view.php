<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use backend\models\inventory\GoodsMovement;

/* @var $this yii\web\View */
/* @var $model GoodsMovement */

$this->title = $model->number;
$this->params['breadcrumbs'][] = ['label' => 'Goods Movements', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="goods-movement-view">

    <p>
        <?php if ($model->status == GoodsMovement::STATUS_DRAFT): ?>
            <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            <?=
            Html::a('Delete', ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Are you sure you want to delete this item?',
                    'method' => 'post',
                ],
            ])
            ?>
            <?=
            Html::a('Confirm', ['confirm', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Are you sure you want to confirm this item?',
                    'method' => 'post',
                ],
            ])
            ?>
        <?php else: ?>
            <?=
            Html::a('Rollback', ['rollback', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Are you sure you want to rollback this item?',
                    'method' => 'post',
                ],
            ])
            ?>
        <?php endif; ?>


    </p>

    <?=
    DetailView::widget([
        'model' => $model,
        'attributes' => [
            'number',
            'nmType',
            'vendor.name',
            'warehouse.name',
            'Date',
            'reff_type',
            'reff_id',
            'description',
            'nmStatus',
        ],
    ])
    ?>

    <?=
    GridView::widget([
        'dataProvider' => new yii\data\ActiveDataProvider([
            'query' => $model->getItems()->with(['product', 'uom']),
            'pagination' => false,
            'sort' => false,
            ]),
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'product.name',
                'header' => 'Product'
            ],
            'product.name',
            'qty',
            [
                'attribute' => 'uom.name',
                'header' => 'Uom'
            ],
        ]
    ])
    ?>

</div>
