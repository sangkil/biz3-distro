<?php

use yii\web\View;
use backend\models\accounting\Payment;
use yii\helpers\Html;

/* @var $this View */
/* @var $model Payment */
?>
<div class="col-lg-6">
    <?= $form->field($model, "[$key]payment_method") ?>
</div>
<div class="col-lg-4">
    <?= Html::label('Value') ?>
    <?= Html::textInput('payment[items][value]', '', ['class' => 'form-control']) ?>
</div>
<div class="col-lg-2" style="padding-left: 0px;">
    <?= Html::buttonInput('Del', ['class' => 'btn btn-primary', 'data-action' => 'delete', 'style' => 'margin-top:24px;']) ?>
</div>