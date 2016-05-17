<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\models\master\Warehouse;
use yii\jui\AutoComplete;
use yii\web\JsExpression;

/* @var $this yii\web\View */
/* @var $model app\models\master\U2Warehouse */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="u2-warehouse-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'warehouse_id')->dropDownList(backend\models\master\Warehouse::selectOptions(), ['style' => 'width:40%;']) ?>
    <?php
    $data = Warehouse::find()
            ->select(['name as value', 'name as  label', 'id as id'])
            ->asArray()
            ->all();

    echo AutoComplete::widget([
        'model' => $model,
        'attribute' => 'whse_name',
        'options' => ['class' => 'form-control'],
        'clientOptions' => [
            'source' => $data,
            'autoFill' => true,
            'minLength' => '1',
            'select' => new JsExpression("function( event, ui ) {
                $('#u2warehouse-user_id').val(ui.item.id);
             }"),
            'search' => new JsExpression("function( event, ui ) {
                $('#u2branch-user_id').val('');
             }")],
    ]);
    ?>
    
    <?= $form->field($model, 'user_id')->hiddenInput()->label(false) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
