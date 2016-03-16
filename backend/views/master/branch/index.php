<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\master\search\Branch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Branches';
$this->params['breadcrumbs'][] = $this->title;
?>
<p class='pull-right'>
    <?= Html::a('Create Branch', ['create'], ['class' => 'btn btn-default']) ?>
</p>
<br>

<div class="branch-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>


    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'tableOptions' => ['class' => 'table table-hover'],
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            //'id',
            'code',
            'name',
            [
                'header'=>'Orgn',
                'attribute'=>'orgn_id',
                'value'=>'orgn.name',
                'filter'=>  \backend\models\master\Orgn::selectOptions()
            ],
            //'created_at',
            // 'created_by',
            // 'updated_at',
            // 'updated_by',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]);
    ?>

</div>
