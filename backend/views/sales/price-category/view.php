<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\sales\PriceCategory */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Price Categories', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="price-category-view">

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
        'options' => ['class' => 'table table-hover'],
        'template' => '<tr><th style="width:20%;">{label}</th><td>{value}</td></tr>',
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            'formula',
            'created_at',
            'created_by',
            'updated_at',
            'updated_by',
        ],
    ]) ?>

</div>
