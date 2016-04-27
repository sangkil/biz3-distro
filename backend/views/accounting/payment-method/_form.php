<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\accounting\PaymentMethod */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="payment-method-form col-lg-6">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'branch_id')->dropDownList(backend\models\master\Branch::selectOptions(), ['style' => 'width:30%;']) ?>

    <?= $form->field($model, 'method')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'coa_id')->dropDownList(backend\models\accounting\Coa::selectOptions(), ['style' => 'width:80%;']) ?>

    <?= $form->field($model, 'potongan')->textInput(['maxlength' => true,'style' => 'width:30%;'])->label('Nilai Potongan') ?>

    <?= $form->field($model, 'coa_id_potongan')->dropDownList(backend\models\accounting\Coa::selectOptions(), ['style' => 'width:80%;']) ?>

    <div class="form-group">
        <?=
        Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success'
                    : 'btn btn-primary'])
        ?>
    </div>

<?php ActiveForm::end(); ?>

</div>
