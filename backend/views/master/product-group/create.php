<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\master\ProductGroup */

$this->title = 'Create Product Group';
$this->params['breadcrumbs'][] = ['label' => 'Product Groups', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-group-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
