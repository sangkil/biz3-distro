<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\inventory\GoodsMovementDtl */

$this->title = 'Update Goods Movement Dtl: ' . ' ' . $model->movement_id;
$this->params['breadcrumbs'][] = ['label' => 'Goods Movement Dtls', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->movement_id, 'url' => ['view', 'movement_id' => $model->movement_id, 'product_id' => $model->product_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="goods-movement-dtl-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
