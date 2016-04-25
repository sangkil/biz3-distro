<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\master\U2Warehouse */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="u2-warehouse-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'warehouse_id')->dropDownList(backend\models\master\Warehouse::selectOptions(),['style'=>'width:40%;']) ?>

    <?= $form->field($model, 'user_id')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
