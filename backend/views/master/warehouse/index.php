<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\master\search\Warehouse */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Warehouses';
$this->params['breadcrumbs'][] = $this->title;
?>
<p class='pull-right'>
    <?= Html::a('Create Warehouse', ['create'], ['class' => 'btn btn-default']) ?>
</p>
<br>

<div class="warehouse-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>


    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'tableOptions' => ['class' => 'table table-hover'],
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            //'id'
            'code',
            'name',
            [
                'header' => 'Branch',
                'attribute' => 'branch_id',
                'value' => 'branch.name',
                'format'=>'raw',
                'filter' => \backend\models\master\Branch::selectOptions()
            ],
            //'created_at',
            // 'created_by',
            // 'updated_at',
            // 'updated_by',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]);
    ?>

</div>
