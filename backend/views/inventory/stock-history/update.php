<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\inventory\ProductStockHistory */

$this->title = 'Update Product Stock History: ' . ' ' . $model->time;
$this->params['breadcrumbs'][] = ['label' => 'Product Stock Histories', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->time, 'url' => ['view', 'time' => $model->time, 'warehouse_id' => $model->warehouse_id, 'product_id' => $model->product_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="product-stock-history-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
