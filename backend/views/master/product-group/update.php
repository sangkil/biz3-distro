<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\master\ProductGroup */

$this->title = 'Update Product Group: ' . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Product Groups', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="product-group-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
