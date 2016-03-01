<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\accounting\Coa */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="coa-form">
    <?php $form = ActiveForm::begin(); ?>
    <div class="col-lg-4">

        <?= $form->field($model, 'code')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'parent_id')->dropDownList($model->selectOptions(),['prompt'=>'--no parent--']) ?>
    </div>
    <div class="col-lg-8">
        <?= $form->field($model, 'type')->dropDownList($model::enums('TYPE_'), ['style' => 'width:20%;']) ?>

        <?= $form->field($model, 'normal_balance')->dropDownList($model::enums('BALANCE_'), ['style' => 'width:40%;']) ?>

        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary', 'style' => 'margin-top:24px;']) ?>
    </div>

    <?php ActiveForm::end(); ?>

    <?php //print_r($model->getHierarchy()) ?>

</div>
