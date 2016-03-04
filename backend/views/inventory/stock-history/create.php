<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\inventory\ProductStockHistory */

$this->title = 'Create Product Stock History';
$this->params['breadcrumbs'][] = ['label' => 'Product Stock Histories', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-stock-history-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
