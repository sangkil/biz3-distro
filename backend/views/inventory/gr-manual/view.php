<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model backend\models\inventory\GoodsMovement */

$this->title = $model->number;
$this->params['breadcrumbs'][] = ['label' => 'Goods Movements', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="goods-movement-view">

    <p>
        <?php if ($model->status == 1): ?>
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
            'warehouse_id',
            'Date',
            'type',
            'reff_type',
            'reff_id',
            'vendor_id',
            'description',
            'status',
        ],
    ])
    ?>

<?=
GridView::widget([
    'dataProvider' => new yii\data\ActiveDataProvider([
        'query' => $model->getItems(),
        'pagination' => false,
        ])
])
?>

</div>
