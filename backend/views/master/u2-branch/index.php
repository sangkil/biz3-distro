<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\master\search\U2Branch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'U2 Branches';
$this->params['breadcrumbs'][] = ['label' => 'Branch', 'url' => ['/master/branch/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<p class='pull-right'>
    <?= Html::a('Create', ['create'], ['class' => 'btn btn-default']) ?>
</p>
<br>

<div class="u2-branch-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'tableOptions' => ['class' => 'table table-hover'],
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'label' => 'Branch',
                'attribute' => 'branch.name'
            ],
            [
                'label' => 'User Name',
                'attribute' => 'user.username'
            ],
            'user_id',
            'created_at',
            'created_by',
            'updated_at',
            // 'updated_by',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]);
    ?>

</div>
