<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\accounting\search\PaymentMethod */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Payment Methods';
$this->params['breadcrumbs'][] = $this->title;
?>
<p class='pull-right'>
    <?= Html::a('Create Payment Method', ['create'], ['class' => 'btn btn-default']) ?>
</p>
<br>

<div class="payment-method-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'tableOptions' => ['class' => 'table table-hover'],
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            //'id',
            //'branch_id',
            [
                'attribute' => 'branch_id',
                'label' => 'Branch',
                'value' => 'branch.name',
                'filter' => backend\models\master\Branch::selectOptions()
            ],
            [
                'label' => 'Paymnt Method',
                'attribute' => 'method',
            ],
            [
                'label' => 'Target Account',
                'value' => function ($model) {
                    return $model->coa->code . ' - ' . $model->coa->name;
                }
            ],
            [
                'label' => 'Potongan',
                'value' => function ($model) {
                    return ($model->potongan > 0 ) ? $model->potongan * 100 . '%' : '-';
                }
            ],
            [
                'label' => 'Target Account Potongan',
                'value' => function ($model) {
                    return ($model->coaPotongan !== null) ? $model->coaPotongan->code . ' - ' . $model->coaPotongan->name
                            : '-';
                }
            ],
            'created_at:datetime',
            // 'coa_id',
            // 'created_by',
            // 'updated_at',
            // 'updated_by',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]);
    ?>

</div>
