<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\inventory\search\GoodsMovementDtl */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Goods Movement History';
$this->params['breadcrumbs'][] = $this->title;
?>
<p class='pull-right'>
    <?= Html::a('Create Goods Movement Dtl', ['create'], ['class' => 'btn btn-default']) ?>
</p>
<br>

<div class="goods-movement-dtl-index">

    <?php //echo $this->render('_search', ['model' => $searchModel]); ?>


    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'tableOptions' => ['class' => 'table table-hover'],
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            //'movement_id',
            //'product_id',
            'movement.date:date',
            'product.code',
            'product.name',
            'uom.code',
            [
                'attribute' => 'qty',
                'value' => function ($model) {
                    return ($model->movement->type == 10) ? $model->qty : (-1 * $model->qty);
                }
            ],
            [
                'label' => 'Reff',
                'attribute' => 'movement.number'
            ],
            // 'value',
            // 'cogs',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]);
    ?>

</div>
