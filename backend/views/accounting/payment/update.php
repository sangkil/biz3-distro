<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\accounting\Payment */

$this->title = 'Update Payment: ' . ' ' . $model->number;
$this->params['breadcrumbs'][] = ['label' => 'Payments', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->number, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="payment-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
