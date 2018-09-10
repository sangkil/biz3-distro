<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\inventory\search\ProductStock */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Stocks by Artikel';
$this->params['breadcrumbs'][] = $this->title;
?>
<p class="pull-right">
    <?= ''//Html::a('Create', ['create'], ['class' => 'btn btn-default']) ?>
    <?=
    Html::a('<i class="fa fa-download"></i> Unduh Data-Stok', ['csv-by-artikel', 'params' => $_GET], [
        'class' => 'btn btn-default', 'title' => 'CSV Download', //'target'=>'new',
        'data' => [
            'method' => 'post',
        ],
    ])
    ?>
</p><br>
<div class="product-stock-index">

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
                'headerOptions' => ['style' => 'width:10%;'],
            ],
            [
                'label' => 'Artikel',
                'attribute' => 'artikel',
                'format' => 'html',
            ],
            [
                'attribute' => 'jml',
//                'value' => function ($model) {
//                    return ($model->movement->type == 10) ? $model->qty : (-1 * $model->qty);
//                },
                'filter' => false,
                'headerOptions' => ['style' => 'width:5%;'],
            ],
        ],
    ]);
    ?>

</div>
