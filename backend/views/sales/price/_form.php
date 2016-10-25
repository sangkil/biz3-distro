<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model backend\models\sales\Price */
/* @var $form yii\widgets\ActiveForm */
use yii\jui\JuiAsset;

JuiAsset::register($this);
$this->registerJs($this->render('_script.js'));
$this->registerJsFile(Url::to(['/inventory/gm-manual/master']));
?>

<div class="price-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= ($model->isNewRecord)? $form->field($model, 'product_name')->textInput()->label('Product Name'):$form->field($model, 'product_name')->textInput(['ReadOnly'=>true])->label('Product Name')  ?>
    <?= $form->field($model, 'product_id')->hiddenInput()->label(false) ?>
    <?= $form->field($model, 'price_category_id')->dropDownList(\backend\models\sales\PriceCategory::selectOptions(),['style'=>'width:40%;']) ?>

    <?= ($model->isNewRecord)? $form->field($model, 'price')->textInput(['style'=>'width:60%;']): $form->field($model, 'price')->textInput(['ReadOnly'=>true, 'style'=>'width:60%;']) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
