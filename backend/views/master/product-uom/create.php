<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\master\ProductUom */

$this->title = 'Create Product Uom';
$this->params['breadcrumbs'][] = ['label' => 'Product Uoms', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-uom-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
