<?php

use yii\helpers\Html;
use yii\grid\GridView;
use backend\models\master\Vendor;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\master\search\Vendor */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Vendors';
$this->params['breadcrumbs'][] = $this->title;
?>
<p class='pull-right'>
    <?= Html::a('Create Vendor', ['create'], ['class' => 'btn btn-success']) ?>
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
            [
                'attribute'=>'type',
                'value'=>'nmType',
                'filter'=>  Vendor::enums('TYPE_')
            ],
            'code',
            'name',
            'contact_name',
            'contact_number',
            [
                'attribute'=>'status',
                'value'=>'nmStatus',
                'filter'=>  Vendor::enums('STATUS_')
            ],
            // 'created_at',
            // 'created_by',
            // 'updated_at',
            // 'updated_by',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]);
    ?>

</div>
