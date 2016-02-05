<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\jui\DatePicker;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\accounting\search\AccPeriode */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Acc Periodes';
$this->params['breadcrumbs'][] = $this->title;
?>
<p class='pull-right'>
    <?= Html::a('Create Acc Periode', ['create'], ['class' => 'btn btn-default']) ?>
</p>
<br>

<div class="acc-periode-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>


    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'tableOptions' => ['class' => 'table table-hover'],
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            //'id',
            'name',
            [
                'attribute' => 'DateFrom',//'value' => 'DateFrom',
                'filter' => DatePicker::widget([
                    'model' => $searchModel,
                    'attribute' => 'DateFrom',
                    'dateFormat' => 'dd-MM-yyyy',
                    'options' => ['class' => 'form-control']
                ])
            ],
            [
                'attribute' => 'DateTo',//'value' => 'DateFrom',
                'filter' => DatePicker::widget([
                    'model' => $searchModel,
                    'attribute' => 'DateTo',
                    'dateFormat' => 'dd-MM-yyyy',
                    'options' => ['class' => 'form-control']
                ])
            ],
            [
                'attribute' => 'status',
                'value' => 'nmStatus',
                'filter' => $searchModel::enums('STATUS_')
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
