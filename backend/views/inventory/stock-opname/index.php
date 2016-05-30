<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\inventory\search\StockOpname */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Stock Opnames';
$this->params['breadcrumbs'][] = $this->title;
?>
<p class='pull-right'>
    <?= Html::a('Create Stock Opname', ['create'], ['class' => 'btn btn-default']) ?>
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
            'id',
            'number',
            'warehouse_id',
            'date',
            'status',
            // 'description',
            // 'operator',
            // 'created_at',
            // 'created_by',
            // 'updated_at',
            // 'updated_by',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]);
    ?>

</div>
