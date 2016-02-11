<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use backend\models\inventory\GoodsMovement;
use backend\models\master\Warehouse;
use yii\jui\JuiAsset;
use yii\helpers\Url;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $model GoodsMovement */
/* @var $form ActiveForm */

JuiAsset::register($this);
$opts = json_encode([
    'product_url' => Url::to(['product-list']),
    'vendor_url' => Url::to(['vendor-list']),
        ]);

$this->registerJs("var biz = $opts;", View::POS_HEAD);
$this->registerJs($this->render('_script.js'));
?>

<div class="goods-movement-form">
    <?= Html::errorSummary($model); ?>
    <?php $form = ActiveForm::begin(); ?>
    <div class="col-md-4">
        <?= $form->field($model, 'number')->textInput(['ReadOnly' => 'ReadOnly', 'style' => 'width:40%;']) ?>
        <?= $form->field($model, 'type')->dropDownList(GoodsMovement::enums('TYPE_'),['style' => 'width:60%;']) ?>
        <?= $form->field($model, 'warehouse_id')->dropDownList(Warehouse::selectOptions(),['style' => 'width:60%;']) ?>
    </div>
    <div class="col-md-4">
        <?=
        $form->field($model, 'Date')->widget('yii\jui\DatePicker', [
            'dateFormat' => 'dd-MM-yyyy',
            'options' => ['class' => 'form-control', 'style' => 'width:40%;']
        ])
        ?>
        <?= $form->field($model, 'vendor_name')->textInput(['required'=>true]) ?>
        <?= Html::activeHiddenInput($model, 'vendor_id') ?>

        <?= $form->field($model, 'description')->textInput(['maxlength' => true]) ?>

    </div>
    <div class="nav-tabs-justified col-lg-12"  style="margin-top: 20px;">
        <ul class="nav nav-tabs">
            <li class="active"><a href="#item" data-toggle="tab" aria-expanded="false">Items</a></li>
            <li><a href="#notes" data-toggle="tab" aria-expanded="false">Notes</a></li>   
            <li class="pull-right">    
                <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
            </li>             
        </ul> 
        <div class="tab-content" >
            <div class="tab-pane active" id="item">
                <?= $this->render('_detail', ['model' => $model]) ?>
            </div>
            <div class="tab-pane" id="notes">

            </div>
        </div> 
    </div>
    <?php ActiveForm::end(); ?>
</div>
