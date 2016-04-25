<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\inventory\GoodsMovement */

$this->title = 'Create Goods ' . ($model->nmType ? ucfirst(strtolower($model->nmType)) : 'Movement');
$this->params['breadcrumbs'][] = ['label' => 'Goods Movements', 'url' => ['index']];
if (isset($reff['action'])) {
    $this->params['breadcrumbs'][] = [
        'label' => $reffModel->number,
        'url' => [$reff['action'], 'id' => $reffModel->id]
    ];
}

$this->params['breadcrumbs'][] = $this->title;
?>
<div class="goods-movement-create">
    <?=
    $this->render('_form', [
        'model' => $model,
        'reff' => $reff,
        'reffModel' => $reffModel
    ])
    ?>
</div>
