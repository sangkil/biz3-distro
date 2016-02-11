<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\inventory\search\ProductStock */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Product Stocks';
$this->params['breadcrumbs'][] = $this->title;
?>
<p class='pull-right'>
    <?= ''//Html::a('Create Product Stock', ['create'], ['class' => 'btn btn-default']) ?>
</p>
<br>

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
                'attribute' => 'product.code'
            ],
            [
                'label' => 'Product',
                'attribute' => 'product.name'
            ],
            [
                'attribute'=>'qty',
                'filter' =>false
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
