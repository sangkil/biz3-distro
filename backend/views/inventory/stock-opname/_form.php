<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\inventory\StockOpname */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="stock-opname-form">
    <?php $form = ActiveForm::begin([

    ]); ?>
    <div class="row">
        <div class="col-lg-6">
            <?= $form->field($model, 'number')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'warehouse_id')->textInput() ?>

            <?= $form->field($model, 'date')->textInput() ?>
        </div>
        <div class="col-lg-6">
            <?= $form->field($model, 'description')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'operator')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'file')->fileInput() ?>
        </div>
    </div>
    <div class="form-group">
        <?=
        Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success'
                    : 'btn btn-primary'])
        ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
