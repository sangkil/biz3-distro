<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\accounting\search\GlHeader */
/* @var $form yii\widgets\ActiveForm */
?>

<?php
$form = ActiveForm::begin([
            'action' => ['index'],
            'method' => 'get',
        ]);
?>
<td style="width: 10%;"><?= $form->field($model, 'number')->label(false); //Html::textInput('no', '', ['class' => 'form-control'])   ?></td>
<td colspan="2"><?= $form->field($model, 'description')->label(false); //Html::textInput('desc', '', ['class' => 'form-control'])  ?></td>
<td >
    <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
    <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
</td>

<?php ActiveForm::end(); ?>
