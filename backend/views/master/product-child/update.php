<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\master\ProductChild */

$this->title = 'Update Product Child: ' . ' ' . $model->barcode;
$this->params['breadcrumbs'][] = ['label' => 'Product Children', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->barcode, 'url' => ['view', 'id' => $model->barcode]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="product-child-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
