<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\inventory\ProductStock */

$this->title = 'Create Product Stock';
$this->params['breadcrumbs'][] = ['label' => 'Product Stocks', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-stock-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
