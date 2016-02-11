<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\accounting\search\Coa */
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

    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'tableOptions' => ['class' => 'table table-hover'],
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'code',
                'contentOptions' => ['style' => 'width:10%;'],
            ],
            [
                'header' => 'Name',
                'attribute' => 'name',
                'format' => 'raw',
                'value' => function($model) {
                    $lvl = $i = $plus = 0;
                    $first_nol = false;
                    foreach (str_split($model->code) as $val) {
                        if ($val == '0' && !$first_nol) {
                            $lvl = $i;
                            $first_nol = true;
                        }
                        $plus = ($val !== '0' && $first_nol) ? 5 : 0;
                        $i+=5;
                    }
                    return str_repeat("&nbsp;", $lvl + $plus - 1) . $model->name;
                }
            ],
            //'type',
            //'id',
            //'parent_id',            
            [
                'attribute' => 'normal_balance',
                'value'=>'nmBalance',
                'contentOptions' => ['style' => 'width:10%;'],
                'filter'=>$searchModel::enums('BALANCE_')
            ],
            [
                'attribute' => 'type',
                'value'=>'accType',
                'contentOptions' => ['style' => 'width:10%;'],
                'filter'=>$searchModel::enums('TYPE_')
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
