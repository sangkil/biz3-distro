<?php

use yii\web\View;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use backend\models\sales\Sales;
use backend\models\master\Branch;

/* @var $this View */
/* @var $model Sales */
/* @var $form ActiveForm */
?>
<div class="row">
    <div class="col-md-4">
        <?= $form->field($model, 'number')->textInput(['readonly' => true, 'style' => 'width:40%;']) ?>
        <?= $form->field($model, 'branch_id')->dropDownList(Branch::selectOptions()) ?>
    </div>
    <div class="col-md-4">
        <?= $form->field($model, 'vendor_name')->textInput([]) ?>
        <?= Html::activeHiddenInput($model, 'vendor_id') ?>
        <?=
        $form->field($model, 'Date')->widget('yii\jui\DatePicker', [
            'dateFormat' => 'dd-MM-yyyy',
            'options' => ['class' => 'form-control', 'style' => 'width:40%;']
        ])
        ?>
    </div>
    <div class="col-md-4">
        <?= $form->field($model, 'value')->textInput(['style' => 'width:40%;']) ?>
    </div>
</div>