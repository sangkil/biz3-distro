<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\master\search\Product */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Products';
$this->params['breadcrumbs'][] = $this->title;
?>


<p class="pull-right">
    <?= Html::a('Create', ['create'], ['class' => 'btn btn-default']) ?>
    <?=
    Html::a('<i class="fa fa-download"></i>', ['csv-download', 'id' => $searchModel->id, 'params'=>$_GET], [
        'class' => 'btn btn-default', 'title'=>'CSV Download', //'target'=>'new',
        'data' => [
            'method' => 'post',
        ],
    ])
    ?>
</p><br>
<div class="product-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'tableOptions' => ['class' => 'table table-hover'],
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'code',
            'name',
            [
                'header' => 'Product Group',
                'attribute' => 'group_id',
                'value' => 'group.name',
                'format' => 'raw',
                'filter' => backend\models\master\ProductGroup::selectOptions()
            ],
            [
                'header' => 'Product Category',
                'attribute' => 'category_id',
                'value' => 'category.name',
                'format' => 'raw',
                'filter' => \backend\models\master\Category::selectOptions()
            ],
            [
                'attribute' => 'status',
                'value' => 'nmStatus',
                'filter' => $searchModel::enums('STATUS_')
            ],
            'edition:date',
            [
                'header' => 'stockable',
                'class' => 'yii\grid\CheckboxColumn',
                'checkboxOptions' => function ($model, $key, $index, $column) {
                    return ['value' => $model->id, 'checked' => $model->stockable];
                }
                ],
                //'id',
                // 'created_at',
                // 'created_by',
                // 'updated_at',
                // 'updated_by',
                ['class' => 'yii\grid\ActionColumn'],
            ],
        ]);
        ?>

</div>
