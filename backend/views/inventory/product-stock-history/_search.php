<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\inventory\search\GoodsMovementDtl */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="goods-movement-dtl-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'movement_id') ?>

    <?= $form->field($model, 'product_id') ?>

    <?= $form->field($model, 'uom_id') ?>

    <?= $form->field($model, 'qty') ?>

    <?= $form->field($model, 'value') ?>

    <?php // echo $form->field($model, 'cogs') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
