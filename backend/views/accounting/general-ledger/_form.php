<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\models\master\Branch;

/* @var $this yii\web\View */
/* @var $model backend\models\accounting\GlHeader */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="gl-header-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="col-lg-4">
        <?= $form->field($model, 'number')->textInput(['maxlength' => true, 'style' => 'width:30%;']) ?>
        <?= $form->field($model, 'branch_id')->dropDownList(Branch::selectOptions(), ['style' => 'width:40%;']) ?>
    </div>
    <div class="col-lg-4">      
        <?=
        $form->field($model, 'GlDate')->widget('yii\jui\DatePicker', [
            'dateFormat' => 'dd-MM-yyyy',
            'options' => ['class' => 'form-control', 'style' => 'width:30%;']
        ])
        ?>
        <?= $form->field($model, 'periode_id')->dropDownList(backend\models\accounting\AccPeriode::selectOptions(), ['style' => 'width:40%;']) ?>
    </div>
    <div class="col-lg-4">      
        <?= $form->field($model, 'description')->textarea(['maxlength' => true]) ?>
    </div>

    <div class="col-lg-12 form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
