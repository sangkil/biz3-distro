<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\master\search\U2Warehouse */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'U2 Warehouses';
$this->params['breadcrumbs'][] = $this->title;
?>
<p class='pull-right'>
    <?= Html::a('Create U2 Warehouse', ['create'], ['class' => 'btn btn-default']) ?>
</p>
<br>

<div class="u2-warehouse-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'tableOptions' => ['class' => 'table table-hover'],
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'warehouse.name',
            'user.username',
//            'created_at',
//            'created_by',
//            'updated_at',
            // 'updated_by',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]);
    ?>

</div>
