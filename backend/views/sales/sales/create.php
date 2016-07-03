<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\sales\Sales */

$this->title = $model->branch->name . ' - Penjualan Retail';
$this->params['breadcrumbs'][] = ['label' => 'Saless', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row sales-create">
    <?=
    $this->render('_form', [
        'model' => $model,
        'payments' => $payments,
        'warehouse' => $warehouse
    ])
    ?>
</div>
