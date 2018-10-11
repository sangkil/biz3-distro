<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\models\master\Warehouse;

/* @var $this yii\web\View */
/* @var $model backend\models\inventory\StockOpname */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="stock-opname-form">
    <?= Html::errorSummary($model, ['class' => 'alert alert-danger alert-dismissible']); ?>
    <?php
    $form = ActiveForm::begin([
            'options' => ['enctype' => 'multipart/form-data']
    ]);
    ?>
    <div class="row">
        <div class="col-lg-4">
            <?= $form->field($model, 'number')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'warehouse_id')->dropDownList(Warehouse::selectOptions()) ?>

            <?=
            $form->field($model, 'Date')->widget('yii\jui\DatePicker', [
                'dateFormat' => 'dd-MM-yyyy',
                'options' => ['class' => 'form-control', 'style' => 'width:60%;']
            ])
            ?>
        </div>
        <div class="col-lg-4">
            <?= $form->field($model, 'description')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'operator')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'file')->fileInput() ?>
        </div>
    </div>
    <div class="form-group">
        <?=
        Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success'
                    : 'btn btn-primary'])
        ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
