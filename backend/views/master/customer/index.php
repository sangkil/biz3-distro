<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\master\search\Vendor */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = ($searchModel->nmType ? ucfirst(strtolower($searchModel->nmType)) : 'All Vendor');
$this->title .= 's';
$this->params['breadcrumbs'][] = $this->title;
?>
<p class='pull-right'>
    <?= Html::a('Create Vendor', ['create'], ['class' => 'btn btn-default']) ?>
</p>
<br>

<div class="vendor-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>


    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'tableOptions' => ['class' => 'table table-hover'],
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            //'id',
            'code',
            'name',
            [
                'attribute' => 'type',
                'value'=>'nmType',
                'contentOptions' => ['style' => 'width:10%;'],
                'filter'=>$searchModel::enums('TYPE_')
            ],
            'contact_name',
            'contact_number',
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
