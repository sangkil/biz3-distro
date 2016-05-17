<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\JsExpression;
use mdm\admin\models\User;
use yii\jui\AutoComplete;

/* @var $this yii\web\View */
/* @var $model app\models\master\U2Branch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="u2-branch-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'branch_id')->dropDownList(\backend\models\master\Branch::selectOptions(), ['style' => 'width:60%;'])->label('Branch') ?>

    <?php
    $data = User::find()
            ->select(['id', 'username'])
            ->asArray()
            ->all();

    echo AutoComplete::widget([
        'name' => 'nm_user',
        'id' => 'dnm_user',
        'clientOptions' => [
            'source' => $data,
            'autoFill' => true,
            'minLength' => '1',
            'select' => new JsExpression("function( event, ui ) {
                alert(ui.item.id);
             }")],
    ]);
    ?>

    <?= $form->field($model, 'user_id')->hiddenInput()->label(false) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
