<?php

use yii\helpers\Html;
use yii\grid\GridView;
use backend\models\inventory\GoodsMovement;
use backend\models\master\Warehouse;

/* @var $this yii\web\View */
/* @var $searchModel GoodsMovement */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Movement';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="goods-movement-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Movement', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
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
            'date',
            [
                'attribute' => 'status',
                'value' => 'nmStatus',
                'filter' => GoodsMovement::enums('STATUS_')
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
