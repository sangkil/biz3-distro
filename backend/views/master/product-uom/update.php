<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\master\ProductUom */

$this->title = 'Update Product Uom: ' . ' ' . $model->product_id;
$this->params['breadcrumbs'][] = ['label' => 'Product Uoms', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->product_id, 'url' => ['view', 'product_id' => $model->product_id, 'uom_id' => $model->uom_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="product-uom-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
