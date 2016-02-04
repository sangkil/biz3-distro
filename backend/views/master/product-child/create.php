<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\master\ProductChild */

$this->title = 'Create Product Child';
$this->params['breadcrumbs'][] = ['label' => 'Product Children', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-child-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
