<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\inventory\search\ProductStockHistory */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="product-stock-history-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'time') ?>

    <?= $form->field($model, 'warehouse_id') ?>

    <?= $form->field($model, 'product_id') ?>

    <?= $form->field($model, 'qty_movement') ?>

    <?= $form->field($model, 'qty_current') ?>

    <?php // echo $form->field($model, 'movement_id') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
