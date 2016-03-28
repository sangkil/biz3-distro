<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\inventory\StockOpname */

$this->title = 'Create Stock Opname';
$this->params['breadcrumbs'][] = ['label' => 'Stock Opnames', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="stock-opname-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
