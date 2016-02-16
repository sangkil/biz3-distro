<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\accounting\search\Invoice */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Invoices';
$this->params['breadcrumbs'][] = $this->title;
?>
<p class='pull-right'>
    <?= Html::a('Create Invoice', ['create'], ['class' => 'btn btn-default']) ?>
</p>
<br>

<div class="invoice-index">

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
            'vendor.name',
            'date',
            'due_date',
            [
                'attribute' => 'type',
                //'value'=>'nmType',
                'filter'=>$searchModel::enums('TYPE_')
            ],            
            [
                'attribute' => 'status',
                'value'=>'nmStatus',
                'filter'=>$searchModel::enums('STATUS_')
            ],
            // 'reff_type',
            // 'reff_id',
            // 'description',
            // 'value',
            // 'tax_type',
            // 'tax_value',
            // 'created_at',
            // 'created_by',
            // 'updated_at',
            // 'updated_by',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]);
    ?>

</div>
