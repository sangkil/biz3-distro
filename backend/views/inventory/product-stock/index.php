<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\inventory\search\ProductStock */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Product Stocks';
$this->params['breadcrumbs'][] = $this->title;
?>
<p class="pull-right">
    <?= ''//Html::a('Create', ['create'], ['class' => 'btn btn-default']) ?>
    <?=
    Html::a('<i class="fa fa-download"></i> Unduh Data-Stok', ['csv-download', 'params' => $_GET], [
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
                'filter' => backend\models\master\Warehouse::selectOptions()
            ],
            [
                'label' => 'Code',
                'attribute' => 'product.code',
                'filter' => Html::textInput('ProductStock[product_code]', $searchModel->product_code, array('class' => 'form-control'))
            ],
            [
                'label' => 'Product',
                'format' => 'html',
                'value' => 'product.name',
                'filter' => Html::textInput('ProductStock[product_name]', $searchModel->product_name, array('class' => 'form-control'))
            ],
            [
                'attribute' => 'qty',
//                'value' => function ($model) {
//                    return ($model->movement->type == 10) ? $model->qty : (-1 * $model->qty);
//                },
                'filter' => false
            ],
            [
                'label' => 'Value',
                'format' => ['decimal', 0],
                'value' => function ($model) {
                return ($model->cogs->cogs * $model->qty);
            },
                'filter' => false
            ],
//            [
//                'attribute' => 'product.created_at',
//                'format'=>'datetime',
//                'filter' =>false
//            ],
        //'created_by',
        // 'updated_at',
        // 'updated_by',
        //['class' => 'yii\grid\ActionColumn'],
        ],
    ]);
    ?>

</div>
