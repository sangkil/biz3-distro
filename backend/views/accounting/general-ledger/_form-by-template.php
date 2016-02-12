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
        <?= $form->field($model, 'status')->dropDownList($model::enums('STATUS_'), ['prompt' => '-- select status--', 'style' => 'width:40%']) ?>
        <?= $form->field($model, 'description')->textarea(['maxlength' => true]) ?>
    </div>
    <div class="nav-tabs-justified col-lg-12" style="margin-top: 20px;">
        <ul class="nav nav-tabs">
            <li class="active"><a href="#items" data-toggle="tab" aria-expanded="false">Items</a></li>
            <li><a href="#notes" data-toggle="tab" aria-expanded="false">Notes</a></li>        
            <li class="pull-right">    
                <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
            </li>             
        </ul> 
        <div class="tab-content" >
            <div class="tab-pane active" id="items">
                <?= $this->render('_detail-by-template', ['model' => $model]) ?>
            </div>
            <div class="tab-pane" id="notes">
                <?= '' ?>
            </div>
        </div> 
    </div>

    <?php ActiveForm::end(); ?>

</div>
