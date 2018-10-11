<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\inventory\StockOpname */

$this->title = 'Partial Opname';
$this->params['breadcrumbs'][] = ['label' => 'Partial Stock Opnames', 'url' => ['index-partial']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="stock-opname-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
