<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\accounting\search\Invoice */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = ucfirst(strtolower($searchModel->nmType)) . ' Invoices';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="col-lg-12">
    <div class='btn-group pull-right'>
        <?= Html::button('New Invoice', ['class' => 'btn btn-default', 'type' => 'button']) ?>        
        <?=
        Html::button('<span class="caret"></span><span class="sr-only">Toggle Dropdown</span>', ['class' => 'btn btn-default dropdown-toggle',
            'aria-expanded' => false, 'type' => 'button', 'data-toggle' => 'dropdown'])
        ?>
        <ul class="dropdown-menu" role="menu">
            <li><?= Html::a('Incoming', ['create', 'Invoice[type]' => $searchModel::TYPE_INCOMING]) ?></li>
            <li><?= Html::a('Outgoing', ['create', 'Invoice[type]' => $searchModel::TYPE_OUTGOING]) ?></li>
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
            [
                'label' => 'Vendor Name',
                'attribute' => 'vendor.name',
            ],
            [
                'label' => 'Invoice Date',
                'attribute' => 'Date',
            ],
            [
                'label' => 'Time to Due',
                'format' => 'raw',
                'value' => function($model) {
                    $d1 = new \DateTime($model->due_date);
                    $d2 = new \DateTime(date('Y-m-d'));
                    return ($model->sisa > 0) ? $d1->diff($d2)->days . ' Days' : '-';
                },
                'filter' => $searchModel::enums('STATUS_')
            ],
            [
                'label' => 'Invoice Value',
                'attribute' => 'value',
                'format' => ['decimal', 0],
                'filter' => false,
                'contentOptions' => ['style' => 'text-align:right;'],
            ],
            [
                'label' => 'Paid',
                'attribute' => 'paid',
                'format' => ['decimal', 0],
                'filter' => false,
                'contentOptions' => ['style' => 'text-align:right;'],
            ],
            [
                'label' => 'Remain',
                'attribute' => 'sisa',
                'format' => ['decimal', 0],
                'filter' => false,
                'contentOptions' => ['style' => 'text-align:right;'],
            ],
            [
                'attribute' => 'type',
                'value' => 'nmType',
                'filter' => $searchModel::enums('TYPE_')
            ],
            [
                'attribute' => 'status',
                'format' => 'raw',
                'value' => function($model) {
                    $temp = '';
                    $bgcolor = ($model->status == $model::STATUS_DRAFT) ? 'bg-yellow' : 'bg-green';
                    $bgcolor = ($model->status == $model::STATUS_CANCELED) ? 'bg-red' : $bgcolor;
                    $temp .= Html::tag('span', $model->nmStatus, ['class' => "badge $bgcolor"]);
                    return $temp;
                },
                    'filter' => $searchModel::enums('STATUS_')
                ],
                // 'reff_type',
                // 'reff_id',
                // 'description',
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
