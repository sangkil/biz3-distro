<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use backend\models\purchase\Purchase;

/* @var $this yii\web\View */
/* @var $model Purchase */

$this->title = $model->number;
$this->params['breadcrumbs'][] = ['label' => 'Purchases', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="purchase-view">

    <p class="pull-right">
        <?= Html::a('Create New', ['create'], ['class' => 'btn btn-default']) ?>
        <?php
        if ($model->status == Purchase::STATUS_DRAFT) {
            echo Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-default']);
        }
        ?>
        <?php
        if ($model->status == Purchase::STATUS_DRAFT) {
            echo Html::a('Delete', ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Are you sure you want to delete this item?',
                    'method' => 'post',
                ],
            ]);
        }
        ?>
        <?php
        if ($model->status == Purchase::STATUS_DRAFT) {
            echo Html::a('Confirm', ['confirm', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Are you sure you want to confirm this item?',
                    'method' => 'post',
                ],
            ]);
        }
        ?>
        <?php
        if ($model->status == Purchase::STATUS_RELEASED) {
            echo Html::a('Receive', ['inventory/gm-from-reff/create','type'=>10, 'id' => $model->id], [
                'class' => 'btn btn-success',
            ]);
        }
        ?>
        <?php
        if ($model->status == Purchase::STATUS_RELEASED) {
            echo Html::a('Cancel', ['reject', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Are you sure you want to cancel this item?',
                    'method' => 'post',
                ],
            ]);
        }
        ?>
    </p>

    <?=
    DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'number',
            'vendor_id',
            'branch_id',
            'date',
            'value',
            'discount',
            'status',
        ],
    ])
    ?>

</div>
