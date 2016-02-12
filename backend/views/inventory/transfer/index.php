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
<div class="transfer-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Transfer', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
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
            'date',
            [
                'attribute' => 'status',
                'value' => 'nmStatus',
                'filter' => Transfer::enums('STATUS_')
            ],
            // 'reff_type',
            // 'reff_id',
            // 'vendor_id',
            // 'description',
            // 'status',
            // 'created_at',
            // 'created_by',
            // 'updated_at',
            // 'updated_by',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]);
    ?>

</div>
