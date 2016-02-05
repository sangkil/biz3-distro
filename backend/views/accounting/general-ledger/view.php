<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\accounting\GlHeader */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Gl Headers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="gl-header-view">

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
            'number',
            'date',
            'periode_id',
            'branch_id',
            'reff_type',
            'reff_id',
            'description',
            'status',
            'created_at',
            'created_by',
            'updated_at',
            'updated_by',
        ],
    ]) ?>

</div>
