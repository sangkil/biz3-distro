<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\sales\search\Price */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Prices';
$this->params['breadcrumbs'][] = $this->title;
?>
<p class='pull-right'>
    <?= Html::a('Create', ['create'], ['class' => 'btn btn-default']) ?>
    <?=
    Html::a('<i class="fa fa-download"></i>', ['csv-download', 'params' => $_GET], [
        'class' => 'btn btn-default', 'title' => 'CSV Download', //'target'=>'new',
        'data' => [
            'method' => 'post',
        ],
    ])
    ?>
</p>
<br>

<div class="price-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'tableOptions' => ['class' => 'table table-hover'],
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'label' => 'Code',
                'format' => 'html',
                'attribute' => 'product.code',
                'value' => function ($model) {
                        return Html::a($model->product->code, ['/sales/price/view', 'product_id' => $model->product_id, 'price_category_id' => $model->price_category_id]);
                    },
                'filter' => Html::textInput('Price[product_code]', $searchModel->product_code, array('class' => 'form-control')),
                'contentOptions' => ['style' => 'width:10%;'],
            ],
            [
                'label' => 'Product Name',
                'format' => 'html',
                'value' => 'product.name',
                'filter' => Html::textInput('Price[product_name]', $searchModel->product_name, array('class' => 'form-control'))
            ],
            [
                'attribute' => 'price_category_id',
                'value' => 'priceCategory.name',
                'format' => 'raw',
                'filter' => backend\models\sales\PriceCategory::selectOptions(),
                'enableSorting' => false,
            ],
            [
                'attribute' => 'price',
                'format' => ['decimal', 0],
                'contentOptions' => ['style' => 'text-align:right;width:15%;']
            ],
            [
                'attribute' => 'created_at',
                'format' => 'datetime',
                'contentOptions' => ['style' => 'text-align:center; width:20%;'],
                'enableSorting' => false,
                'filter' => false
            ],
                //'created_by',
                // 'updated_at',
                // 'updated_by',
                //['class' => 'yii\grid\ActionColumn'],
            ],
        ]);
        ?>

</div>
