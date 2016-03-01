<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\inventory\ProductStock */

$this->title = 'Update Product Stock: ' . ' ' . $model->warehouse_id;
$this->params['breadcrumbs'][] = ['label' => 'Product Stocks', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->warehouse_id, 'url' => ['view', 'warehouse_id' => $model->warehouse_id, 'product_id' => $model->product_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="product-stock-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
