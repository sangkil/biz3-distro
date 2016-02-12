<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\accounting\EntriSheet */
/* @var $form yii\widgets\ActiveForm */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Entri Sheets', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="entri-sheet-form">
    <?php $form = ActiveForm::begin(); ?>
    <div class="col-lg-6">
        <?= $form->field($model, 'id')->dropDownList($model->selectOptions()) ?> 
        <?= $form->field($model, 'amount')->textInput() ?> 
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>

    </div>

    <?php ActiveForm::end(); ?>

</div>
