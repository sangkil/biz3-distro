<?php

use yii\helpers\Html;
use backend\models\inventory\GoodsMovement;

/* @var $this yii\web\View */
/* @var $model backend\models\inventory\GoodsMovement */

$this->title = ($model->type == GoodsMovement::TYPE_RECEIVE) ? 'Penerimaan' : 'Mutasi';
$this->title = ($model->type == GoodsMovement::TYPE_ISSUE) ? 'Pengeluaran' : $this->title;
$this->title = 'Update '. $this->title. ' Barang';
$this->params['breadcrumbs'][] = ['label' => 'Goods Movements', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->number, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="goods-movement-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
