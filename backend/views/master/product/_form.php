<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\master\Product */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="product-form">
    <?php
    $form = ActiveForm::begin();
    ?>
    <div class="row">
        <div class="col-lg-4">
            <?= $form->field($model, 'code')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
        </div>
        <div  class="col-lg-4">
            <?= $form->field($model, 'group_id')->dropDownList(backend\models\master\ProductGroup::selectOptions(), ['prompt' => '-- select --', 'style' => 'width:60%']) ?>
            <?= $form->field($model, 'category_id')->dropDownList(\backend\models\master\Category::selectOptions(), ['prompt' => '-- select --', 'style' => 'width:60%']) ?>
        </div>
        <div  class="col-lg-4">   
            <?=
            $form->field($model, 'Edition')->widget('yii\jui\DatePicker', [
                'dateFormat' => 'dd-MM-yyyy',
                'options' => ['class' => 'form-control', 'style' => 'width:40%;']
            ])
            ?>
            <?= $form->field($model, 'stockable')->checkbox() ?>
<?= $form->field($model, 'status')->dropDownList($model::enums('STATUS_'), ['prompt' => '-- select status--', 'style' => 'width:40%']) ?>
        </div>

        <div class="nav-tabs-justified col-lg-12"  style="margin-top: 20px;">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#uom" data-toggle="tab" aria-expanded="false">Uoms</a></li>
                <li><a href="#bcode" data-toggle="tab" aria-expanded="false">Barcodes Alias</a></li>    
                <li><a href="#dprice" data-toggle="tab" aria-expanded="false">Sales Price</a></li>      
                <li class="pull-right">    
<?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
                </li>             
            </ul> 
            <div class="tab-content" >
                <div class="tab-pane active" id="uom">
<?= $this->render('_form-uom', ['product_uom' => $model->productUoms]) ?>
                </div>
                <div class="tab-pane" id="bcode">
<?= $this->render('_form-barcode', ['product_bcode' => $model->productChildren]) ?>
                </div>
                <div class="tab-pane" id="dprice">                    
<?= $this->render('_form-price', ['product_prices' => $model->prices]) ?>
                </div>
            </div> 
        </div>
    </div>
<?php ActiveForm::end(); ?>
</div>
