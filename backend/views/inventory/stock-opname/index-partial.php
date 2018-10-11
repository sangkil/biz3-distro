<?php

use yii\helpers\Html;
use yii\grid\GridView;
use backend\models\inventory\StockOpname;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\inventory\search\StockOpname */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Partial Stock Opnames';
$this->params['breadcrumbs'][] = $this->title;
?>
<p class='pull-right'>
    <?= Html::a('Create New', ['create-partial'], ['class' => 'btn btn-default']) ?>
    <?php
    $url = array_filter(Yii::$app->getRequest()->get());
    $url[0] = 'csv-template';
    unset($url['page']);
    echo Html::a('<i class="fa fa-download"></i>', $url, [
        'class' => 'btn btn-default', 'title' => 'Download Template', 'target' => '_blank'])
    ?>
</p>
<br>

<div class="stock-opname-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>


    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'tableOptions' => ['class' => 'table table-hover'],
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            //'id',
            'number',
            'Date',
            'warehouse.name',
            'description',
            'operator',
            'type',
            [
                'attribute' => 'status',
                'value' => 'nmStatus',
                'filter' => StockOpname::enums('STATUS_')
            ],
            // 'created_at',
            // 'created_by',
            // 'updated_at',
            // 'updated_by',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]);
    ?>

</div>
