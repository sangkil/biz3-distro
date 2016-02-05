<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\accounting\AccPeriode */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Acc Periodes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="acc-periode-view">

    <p class="pull-right">
        <?= Html::a('Create New', ['create'], ['class' => 'btn btn-default']) ?>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-default']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            'DateFrom',
            'DateTo',
            'nmStatus',
            'created_at:datetime',
            'created_by',
            'updated_at:datetime',
            'updated_by',
        ],
    ]) ?>

</div>
