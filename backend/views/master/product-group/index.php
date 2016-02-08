<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\master\search\ProductGroup */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Product Groups';
$this->params['breadcrumbs'][] = $this->title;
?>
<p class='pull-right'>
    <?= Html::a('Create Product Group', ['create'], ['class' => 'btn btn-success']) ?>
</p>
<br>

<div class="product-group-index">

                <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    

            <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'tableOptions' => ['class' => 'table table-hover'],
        'filterModel' => $searchModel,
        'columns' => [
        ['class' => 'yii\grid\SerialColumn'],

                    'id',
            'code',
            'name',
            'created_at',
            'created_by',
            // 'updated_at',
            // 'updated_by',

        ['class' => 'yii\grid\ActionColumn'],
        ],
        ]); ?>
    
</div>