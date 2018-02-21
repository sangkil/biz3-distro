<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\master\search\Cogs */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Cogs';
$this->params['breadcrumbs'][] = $this->title;
?>
<p class='pull-right'>
    <?= Html::a('Create Cogs', ['create'], ['class' => 'btn btn-default']) ?>
    <?= Html::a('<i class="fa fa-download">&nbsp;</i>', ['go-download'], ['class' => 'btn btn-default','target'=>'_blank']) ?>
</p>
<br>
<div class="cogs-index">
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'tableOptions' => ['class' => 'table table-hover'],
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            //'product_id',
            [
                'label' => 'Code',
                'attribute' => 'product.code',
                'filter' => Html::textInput('Cogs[product_code]', $searchModel->product_code, array('class' => 'form-control')),
                'contentOptions' => ['style' => 'width:10%;'],
            ],
            [
                'label' => 'Product Name',
                'format' => 'html',
                'value' => 'product.name',
                'filter' => Html::textInput('Cogs[product_name]', $searchModel->product_name, array('class' => 'form-control'))
            ],
            [
              'attribute'=>'cogs',
              'format'=>['decimal',0],
              'contentOptions' => ['style' => 'text-align:right'],
            ],
            [
              'attribute'=>'last_purchase_price',
              'format'=>['decimal',0],
              'contentOptions' => ['style' => 'text-align:right'],
            ],
            'created_at:datetime',
//            'created_by',
            'updated_at:datetime',
//             'updated_by',
//            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]);
    ?>

</div>
