<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;


/* @var $this yii\web\View */
/* @var $model backend\models\inventory\GoodsMovement */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="goods-movement-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= Html::errorSummary($model); ?>
    
    <?= $form->field($model, 'number')->textInput(['maxlength' => true,'readonly'=>true]) ?>

    <?= $form->field($model, 'warehouse_id')->dropDownList([
        1=>'Wh1',
        2=>'Wh2'
    ]) ?>

    <?= $form->field($model, 'Date')->widget('yii\jui\DatePicker',[
        'dateFormat' => 'dd-MM-yyyy',
        'options'=>['class'=>'form-control']
    ]) ?>

    <?= $form->field($model, 'reff_type')->textInput() ?>

    <?= $form->field($model, 'reff_id')->textInput() ?>

    <?= $form->field($model, 'vendor_id')->textInput() ?>

    <?= $form->field($model, 'description')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'status')->textInput() ?>

    <?= $this->render('_detail', ['model' => $model]) ?>
    
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    
    <?php ActiveForm::end(); ?>

</div>
