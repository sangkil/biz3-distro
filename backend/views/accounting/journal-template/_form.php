<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\accounting\EntriSheet */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="entri-sheet-form">
    <?php $form = ActiveForm::begin(); ?>
    <div class="col-lg-6">
        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="nav-tabs-justified col-lg-12" style="margin-top: 20px;">
        <ul class="nav nav-tabs">
            <li class="active"><a href="#items" data-toggle="tab" aria-expanded="false">Items</a></li>
            <li><a href="#notes" data-toggle="tab" aria-expanded="false">Notes</a></li>
            <li class="pull-right">
                <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord
                            ? 'btn btn-success' : 'btn btn-primary']) ?>
            </li>             
        </ul> 
        <div class="tab-content" >
            <div class="tab-pane active" id="items">
                <?= $this->render('_detail', ['model' => $model]) ?>
            </div>
            <div class="tab-pane" id="notes">
                <?= '' ?>
            </div>
        </div> 
    </div>


    <?php ActiveForm::end(); ?>

</div>
