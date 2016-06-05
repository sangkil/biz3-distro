<?php

use yii\helpers\Html;
use yii\grid\GridView;
use backend\models\sales\Sales;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\sales\search\Sales */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Saless';
$this->params['breadcrumbs'][] = $this->title;
?>
<p class='pull-right'>
    <?= Html::a('Create Sales', ['create'], ['class' => 'btn btn-default']) ?>
</p>
<br>

<div class="sales-index">

    <?php
    // echo $this->render('_search', ['model' => $searchModel]); 
    $totaLine = 0;
    foreach ($dataProvider->getModels() as $key => $val) {
        $totaLine += $val->value;
    }
    ?>

    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'tableOptions' => ['class' => 'table table-hover'],
        'filterModel' => $searchModel,
        'showFooter' => true,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'label' => 'Sales Num',
                'format' => 'html',
                'attribute' => 'number',
                'value' => function ($model) {
                    return Html::a($model->number, ['view', 'id' => $model->id]);
                },
                'headerOptions' => ['style' => 'text-align:right; width:10%;'],
            ],
            [
                'attribute' => 'Date',
                //'value'=>'Date',
                'headerOptions' => ['style' => 'text-align:right; width:10%;'],
                'filter' => \yii\jui\DatePicker::widget([
                        'model'=>$searchModel, 
                        'attribute'=>'Date',
                        'dateFormat' => 'dd-MM-yyyy',
                        'options'=>['class'=>'form-control']
                ]),
                'format' => 'html',
            ],
            [
                'attribute' => 'branch_id',
                'value' => 'branch.name',
                'filter' => backend\models\master\Branch::selectAssignedOptions()
            ],
            [
                'attribute' => 'vendor_id',
                'value' => 'vendor.name',
                'filter' => false
            ],
            [
                'attribute' => 'value',
                'format' => ['decimal', 0],
                'headerOptions' => ['style' => 'text-align:right; width:20%;'],
                'contentOptions' => ['style' => 'text-align:right;'],
                'footerOptions' => ['style' => 'text-align:right;', 'class' => 'text-bold'],
                'footer' => number_format($totaLine, 0),
                'filter' => false
            ],
//            [
//                'attribute'=>'status',
//                'value'=>'nmStatus',
//                'filter'=>  Sales::enums('STATUS_')
//            ],
//            ['class' => 'yii\grid\ActionColumn'],
                ],
            ]);
            ?>

</div>
