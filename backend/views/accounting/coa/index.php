<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\accounting\search\coa */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Coas';
$this->params['breadcrumbs'][] = $this->title;
?>
<p class='pull-right'>
    <?= Html::a('Create Coa', ['create'], ['class' => 'btn btn-default']) ?>
</p>
<br>

<div class="coa-index">

                <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    

            <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'tableOptions' => ['class' => 'table table-hover'],
        'filterModel' => $searchModel,
        'columns' => [
        ['class' => 'yii\grid\SerialColumn'],

                    'id',
            'parent_id',
            'code',
            'name',
            'type',
            // 'normal_balance',
            // 'created_at',
            // 'created_by',
            // 'updated_at',
            // 'updated_by',

        ['class' => 'yii\grid\ActionColumn'],
        ],
        ]); ?>
    
</div>
