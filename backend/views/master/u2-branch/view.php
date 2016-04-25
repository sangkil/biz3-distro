<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\master\U2Branch */

$this->title = $model->branch_id;
$this->params['breadcrumbs'][] = ['label' => 'U2 Branches', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="u2-branch-view">

    <p class="pull-right">
        <?= Html::a('Create New', ['create'], ['class' => 'btn btn-default']) ?>
        <?= Html::a('Update', ['update', 'branch_id' => $model->branch_id, 'user_id' => $model->user_id], ['class' => 'btn btn-default']) ?>
        <?= Html::a('Delete', ['delete', 'branch_id' => $model->branch_id, 'user_id' => $model->user_id], [
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
            'branch_id',
            'user_id',
            'created_at',
            'created_by',
            'updated_at',
            'updated_by',
        ],
    ]) ?>

</div>
