<?php

use yii\helpers\Html;
use yii\grid\GridView;
use backend\models\inventory\GoodsMovement;
use backend\models\master\Warehouse;

/* @var $this yii\web\View */
/* @var $searchModel GoodsMovement */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Goods Movement';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="col-lg-12">
    <div class='btn-group pull-right'>
        <?= $searchModel->type ? Html::a(($searchModel->type == GoodsMovement::TYPE_RECEIVE)?'New Receive':'New Issue', ['inventory/gm-manual/create','type' => $searchModel->type], ['class' => 'btn btn-default']):'' ?>
    </div>
</div>
<br><br>
<div class="col-lg-12 goods-movement-index">
    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'tableOptions' => ['class' => 'table table-hover'],
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'number',
            [
                'attribute' => 'type',
                'value' => 'nmType',
                'filter' => GoodsMovement::enums('TYPE_')
            ],
            [
                'attribute' => 'warehouse_id',
                'value' => 'warehouse.name',
                'filter' => Warehouse::selectOptions(),
            ],
            [
                'attribute' => 'vendor_id',
                'value' => 'vendor.name',
            ],
            'date',
            [
                'attribute' => 'status',
                'value' => 'nmStatus',
                'filter' => GoodsMovement::enums('STATUS_')
            ],
            [
                'label' => 'Invoice Number',
                'format' => 'raw',
                'value' => function($model) {
                    return ($model->invoice != null) ? Html::a($model->invoice->number, ['/accounting/invoice/view', 'id' => $model->invoice->id])
                            : '';
                }
                ],
                // 'reff_type',
                // 'reff_id',
                // 'vendor_id',
                // 'description',
                // 'status',
                // 'created_at',
                // 'created_by',
                // 'updated_at',
                // 'updated_by',
                ['class' => 'yii\grid\ActionColumn'],
            ],
        ]);
        ?>

</div>
