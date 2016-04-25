<?php

use yii\helpers\Html;
use yii\grid\GridView;
use backend\models\inventory\Transfer;
use backend\models\inventory\search\Transfer as TransferSearch;
use backend\models\master\Branch;

/* @var $this yii\web\View */
/* @var $searchModel TransferSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Transfer';
$this->params['breadcrumbs'][] = $this->title;
?>
<p class='pull-right'>
    <?= Html::a('Create Transfer', ['create'], ['class' => 'btn btn-default']) ?>
</p>
<br>

<div class="transfer-index">

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
                'attribute' => 'branch_id',
                'value' => 'branch.name',
                'filter' => Branch::selectOptions(),
            ],
            [
                'attribute' => 'branch_dest_id',
                'value' => 'branchDest.name',
                'filter' => Branch::selectOptions(),
            ],
            [
                'attribute'=>'Date',
            ],
            [
                'attribute'=>'status',
                'value'=>'nmStatus',
                'filter'=>  Transfer::enums('STATUS_')
            ],
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]);
    ?>

</div>
