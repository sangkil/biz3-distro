<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\accounting\Invoice */

$this->title = 'Update Invoice: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Invoices', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="invoice-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
