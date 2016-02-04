<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use backend\models\accounting\Invoice;

/* @var $this yii\web\View */
/* @var $model Invoice */
/* @var $form ActiveForm */
?>

<div class="invoice-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= Html::errorSummary($model); ?>
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <?=
                Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success'
                            : 'btn btn-primary'])
                ?>
            </div>
        </div>
        <div class="col-md-6">
            <div class="box">
                <div class="box-body">
                    <?= $form->field($model, 'number')->staticControl() ?>
                    <?=
                    $form->field($model, 'type')->dropDownList(Invoice::enums('TYPE_'))
                    ?>
                    <?=
                    $form->field($model, 'Date')->widget('yii\jui\DatePicker', [
                        'dateFormat' => 'dd-MM-yyyy',
                        'options' => ['class' => 'form-control']
                    ])
                    ?>
                    <?=
                    $form->field($model, 'DueDate')->widget('yii\jui\DatePicker', [
                        'dateFormat' => 'dd-MM-yyyy',
                        'options' => ['class' => 'form-control']
                    ])
                    ?>
                    <?= $form->field($model, 'value')->textInput() ?>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="box">
                <div class="box-body">
                    <?= $form->field($model, 'vendor_id')->textInput() ?>
                    <?= $form->field($model, 'tax_type')->textInput() ?>
                    <?= $form->field($model, 'tax_value')->textInput() ?>
                    <?= $form->field($model, 'description')->textInput(['maxlength' => true]) ?>

                </div>
            </div>
        </div>
        <div class="col-md-12">
            <?= $this->render('_detail', ['model' => $model]) ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>

</div>
