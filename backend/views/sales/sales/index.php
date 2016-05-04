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

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'tableOptions' => ['class' => 'table table-hover'],
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'number',
            [
                'attribute'=>'branch_id',
                'value'=>'branch.name',
                'filter'=>  backend\models\master\Branch::selectOptions()
            ],
            [
                'attribute'=>'vendor_id',
                'value'=>'vendor.name',
            ],
            [
                'attribute'=>'Date',
            ],

            [
                'attribute'=>'value',
                'format'=>['decimal',0]
            ],
//            [
//                'attribute'=>'status',
//                'value'=>'nmStatus',
//                'filter'=>  Sales::enums('STATUS_')
//            ],
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]);
    ?>

</div>
