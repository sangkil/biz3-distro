<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\sales\PriceCategory */

$this->title = 'Update Price Category: ' . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Price Categories', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="price-category-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
