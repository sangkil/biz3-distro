<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\inventory\GoodsMovement */

$this->title = 'Update Goods Movement: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Goods Movements', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="goods-movement-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
