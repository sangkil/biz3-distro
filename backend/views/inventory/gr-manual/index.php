<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\inventory\search\GoodsMovement */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'GR';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="goods-movement-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create GR', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'number',
            'warehouse_id',
            'date',
            'type',
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
    ]); ?>

</div>
