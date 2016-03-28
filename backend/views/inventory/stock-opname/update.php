<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\inventory\StockOpname */

$this->title = 'Update Stock Opname: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Stock Opnames', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="stock-opname-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
