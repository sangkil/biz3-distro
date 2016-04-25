<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\accounting\PaymentMethod */

$this->title = 'Update Payment Method: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Payment Methods', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="payment-method-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
