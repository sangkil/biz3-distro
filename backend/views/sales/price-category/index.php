<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\sales\search\PriceCategory */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Price Categories';
$this->params['breadcrumbs'][] = $this->title;
?>
<p class='pull-right'>
    <?= Html::a('Create Price Category', ['create'], ['class' => 'btn btn-default']) ?>
</p>
<br>

<div class="price-category-index">

                <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    

            <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'tableOptions' => ['class' => 'table table-hover'],
        'filterModel' => $searchModel,
        'columns' => [
        ['class' => 'yii\grid\SerialColumn'],

                    'id',
            'name',
            'formula',
            'created_at',
            'created_by',
            // 'updated_at',
            // 'updated_by',

        ['class' => 'yii\grid\ActionColumn'],
        ],
        ]); ?>
    
</div>
