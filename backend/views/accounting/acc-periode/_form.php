<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\accounting\AccPeriode */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="acc-periode-form">
    <div class="col-lg-6">
        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

        <?=
        $form->field($model, 'DateFrom')->widget('yii\jui\DatePicker', [
            'dateFormat' => 'dd-MM-yyyy',
            'options' => ['class' => 'form-control', 'style' => 'width:40%;']
        ])
        ?>

        <?=
        $form->field($model, 'DateTo')->widget('yii\jui\DatePicker', [
            'dateFormat' => 'dd-MM-yyyy',
            'options' => ['class' => 'form-control', 'style' => 'width:40%;']
        ])
        ?>

        <?= ($model->isNewRecord) ? $form->field($model, 'status')->dropDownList($model::enums('STATUS_'), ['prompt' => '-- select status--', 'style' => 'width:40%']) : '' ?>

        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>

</div>
