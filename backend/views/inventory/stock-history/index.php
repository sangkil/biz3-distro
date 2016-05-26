<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\inventory\search\ProductStockHistory */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Product Stock Histories';
$this->params['breadcrumbs'][] = $this->title;
?>
<br>

<div class="product-stock-history-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'tableOptions' => ['class' => 'table table-hover'],
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'label' => 'Warehouse',
                'attribute' => 'warehouse_id',
                'value' => 'warehouse.name',
                'filter' => backend\models\master\Warehouse::selectOptions(),
                'contentOptions' => ['style' => 'width:15%;'],
            ],
            [
                'label' => 'Code',
                'attribute' => 'product.code',
                'filter' => Html::textInput('ProductStockHistory[product_code]', $searchModel->product_code, array('class' => 'form-control')),
                'contentOptions' => ['style' => 'width:10%;'],
            ],
            [
                'label' => 'Product',
                'format' => 'html',
                'value' => 'product.name',
                'filter' => Html::textInput('ProductStockHistory[product_name]', $searchModel->product_name, array('class' => 'form-control'))
            ],
            [
                'attribute' => 'qty_movement',
                'filter' => false,
                'contentOptions' => ['style' => 'width:10%;text-align:center;'],
            ],
            [
                'attribute' => 'qty_current',
                'filter' => false,
                'contentOptions' => ['style' => 'width:10%;text-align:center;'],
            ],
            [
                'attribute' => 'time',
                'format' => 'datetime',
                'filter' => false,
                'contentOptions' => ['style' => 'width:10%;'],
            ],
            [
                'label' => 'Goods Movement Reff',
                'attribute' => 'movement_id',
                'format'=>'raw',
                'value' => function($model) {
                    return \yii\bootstrap\Html::a($model->goodsmovements->number, ['/inventory/gm-manual/view', 'id' => $model->movement_id]);
                },
                'filter' => Html::textInput('ProductStockHistory[goods_movement_number]', $searchModel->goods_movement_number, array('class' => 'form-control'))
            ],
            'goodsmovements.description',
            //['class' => 'yii\grid\ActionColumn'],
            ],
        ]);
        ?>

</div>
