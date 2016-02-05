<?php

use yii\helpers\Html;
use yii\grid\GridView;
use backend\models\purchase\Purchase;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\purchase\search\Purchase */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Purchases';
$this->params['breadcrumbs'][] = $this->title;
?>
<p class='pull-right'>
    <?= Html::a('Create Purchase', ['create'], ['class' => 'btn btn-default']) ?>
</p>
<br>

<div class="purchase-index">

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
                'attribute'=>'supplier_id',
                'value'=>'supplier.name',
            ],
            [
                'attribute'=>'branch_id',
                'value'=>'branch.name',
            ],
            [
                'attribute'=>'Date',
            ],
            [
                'attribute'=>'status',
                'value'=>'nmStatus',
                'filter'=>  Purchase::enums('STATUS_')
            ],
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]);
    ?>

</div>
