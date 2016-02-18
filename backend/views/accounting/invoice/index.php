<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\accounting\search\Invoice */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Invoices';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="col-lg-12">
    <div class='btn-group pull-right'>
        <?= Html::button('New Invoice', ['class' => 'btn btn-default', 'type' => 'button']) ?>        
        <?= Html::button('<span class="caret"></span><span class="sr-only">Toggle Dropdown</span>', ['class' => 'btn btn-default dropdown-toggle', 'aria-expanded' => false, 'type' => 'button', 'data-toggle' => 'dropdown']) ?>
        <ul class="dropdown-menu" role="menu">
            <li><?= Html::a('Incoming', ['create','Invoice[type]'=>$searchModel::TYPE_INCOMING]) ?></li>
            <li><?= Html::a('Outgoing', ['create','Invoice[type]'=>$searchModel::TYPE_OUTGOING]) ?></li>            
        </ul>        
    </div>
</div>

<div class="col-lg-12 invoice-index">

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
                'value'=>'nmType',
                'filter' => $searchModel::enums('TYPE_')
            ],
            [
                'attribute' => 'status',
                'format' => 'raw',
                'value' => function($model) {
                    $temp = '';
                    $bgcolor = ($model->status == $model::STATUS_DRAFT) ? 'bg-yellow' : 'bg-green';
                    $bgcolor = ($model->status == $model::STATUS_CANCELED) ? 'bg-red' : $bgcolor;
                    $temp .= Html::tag('td', Html::tag('span', $model->nmStatus, ['class' => "badge $bgcolor"]), ['style' => 'width:10%']);
                    return $temp;
                },
                'filter' => $searchModel::enums('STATUS_')
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
