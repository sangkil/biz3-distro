<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use backend\models\inventory\Transfer;

/* @var $this yii\web\View */
/* @var $model Transfer */

$this->title = $model->number;
$this->params['breadcrumbs'][] = ['label' => 'Transfer', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="transfer-view">

    <p>
        <?php if ($model->status == Transfer::STATUS_DRAFT): ?>
            <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            <?=
            Html::a('Delete', ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Are you sure you want to delete this item?',
                    'method' => 'post',
                ],
            ])
            ?>
            <?=
            Html::a('Confirm', ['confirm', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Are you sure you want to confirm this item?',
                    'method' => 'post',
                ],
            ])
            ?>
        <?php else: ?>
            <?=
            Html::a('Rollback', ['rollback', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Are you sure you want to rollback this item?',
                    'method' => 'post',
                ],
            ])
            ?>
        <?php endif; ?>


    </p>

    <?=
    DetailView::widget([
        'model' => $model,
        'attributes' => [
            'number',
            'branch.name',
            'branchDest.name',
            'Date',
            'nmStatus',
        ],
    ])
    ?>

    <?=
    GridView::widget([
        'dataProvider' => new yii\data\ActiveDataProvider([
            'query' => $model->getItems()->with(['product', 'uom']),
            'pagination' => false,
            'sort' => false,
            ]),
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'product.name',
                'header' => 'Product'
            ],
            'product.name',
            'qty',
            [
                'attribute' => 'uom.name',
                'header' => 'Uom'
            ],
        ]
    ])
    ?>

</div>
