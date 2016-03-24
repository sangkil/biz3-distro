<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\accounting\search\EntriSheet */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Journal Templates';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="col-lg-12">
    <p class='pull-right'>
        <?= Html::a('Create New', ['create'], ['class' => 'btn btn-default']) ?>
    </p>
</div>

<div class="col-lg-12 entri-sheet-index">

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
            'created_at',
            'created_by',
            'updated_at',
            // 'updated_by',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]);
    ?>

</div>
