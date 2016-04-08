<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\accounting\Payment */

$this->title = $model->number;
$this->params['breadcrumbs'][] = ['label' => 'Payments', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="col-lg-12">
    <p class="pull-right">
        <?= Html::a('Create New', ['create'], ['class' => 'btn btn-default']) ?>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-default']) ?>
        <?=
        ($model->status == $model::STATUS_DRAFT) ? Html::a('Post', ['post', 'id' => $model->id], [
                'class' => 'btn btn-primary',
                'data' => [
                    'confirm' => 'Are you sure you want to post this Payment?',
                    'method' => 'post',
                ],
            ]) : ''
        ?>
        <?=
        Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ])
        ?>
    </p>
</div>
<div class="col-lg-6">
    <?=
    DetailView::widget([
        'model' => $model,
        'template' => '<tr><th style="width:20%">{label}</th><td>{value}</td></tr>',
        'attributes' => [
            'number',
            'Date',
            [
                'label' => 'Vendor',
                'attribute' => 'vendor.name'
            ]
        ],
    ])
    ?>
</div>
<div class="col-lg-6">
    <?=
    DetailView::widget([
        'model' => $model,
        'template' => '<tr><th style="width:20%">{label}</th><td>{value}</td></tr>',
        'attributes' => [
            'nmType',
            //'nmMethod',
            'nmStatus',
        ],
    ])
    ?>
</div>
<div class="nav-tabs-justified col-lg-12">
    <ul class="nav nav-tabs">
        <li class="active"><a href="#item" data-toggle="tab" aria-expanded="false">Items</a></li>
    </ul>
    <div class="tab-content" >
        <div class="tab-pane active" id="item">
            <?= $this->render('_detail_view', ['model' => $model]) ?>
        </div>
    </div>
</div>
