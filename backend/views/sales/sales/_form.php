<?php

use yii\web\View;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use backend\models\sales\Sales;
use backend\models\master\Branch;
use yii\jui\JuiAsset;
use yii\helpers\Url;

/* @var $this View */
/* @var $model Sales */
/* @var $form ActiveForm */

JuiAsset::register($this);
$opts = json_encode([
    'product_url' => Url::to(['product-list']),
    'vendor_url' => Url::to(['vendor-list']),
        ]);

$this->registerJs("var biz = $opts;", View::POS_HEAD);
$this->registerJs($this->render('_script.js'));
?>

<div class="sales-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= Html::errorSummary($model, ['class' => 'alert alert-danger alert-dismissible']) ?>
    <div class="nav-tabs-justified col-lg-8">
        <ul class="nav nav-tabs">
            <li class="active"><a href="#item" data-toggle="tab" aria-expanded="false">Items</a></li>
            <li><a href="#notes" data-toggle="tab" aria-expanded="false">Notes</a></li>
        </ul>
        <div class="tab-content" >
            <div class="tab-pane active" id="item">
                <?= $this->render('_detail', ['model' => $model]) ?>
            </div>
            <div class="tab-pane" id="notes">

            </div>
        </div>
    </div>
    <div class="col-md-4">        
        <div class="small-box bg-aqua box-comments">
            <div class="inner">
                <h3>Rp0</h3>
                <p>0 Items</p>
            </div>
            <div class="icon">
                <i class="fa fa-shopping-cart"></i>
            </div>
            <a href="#" class="small-box-footer">
                <?= 'Sales Date: ' . Html::getAttributeValue($model, 'Date') ?> <i class="fa fa-calendar"></i>
            </a>
        </div>
        <div class="box box-info box-comments with-border">
            <div class="box-header with-border">
                <i class="fa fa-shopping-cart text-orange"></i>
                <h3 class="box-title">Customer & Payments</h3>   
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse">
                        <i class="fa fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="box-body with-border">
                <!--                <div class="col-lg-5">
                <?= $form->field($model, 'number')->textInput(['readonly' => true]) ?>
                <?= ''//$form->field($model, 'branch_id')->dropDownList(Branch::selectOptions()) ?>
                                </div>-->
                <div class="col-lg-12">
                    <?= $form->field($model, 'vendor_name')->textInput([])->label('Customer') ?>
                    <?= $form->field($model, 'vendor_id')->hiddenInput()->label(false) ?>
                </div>
                <div class="col-lg-6">
                    <?= $form->field($payment, 'payment_type')->dropDownList($payment::enums('METHOD_', ['prompt' => '--'])) ?>
                </div>
                <div class="col-lg-4">
                    <?= Html::label('Value') ?>
                    <?= Html::textInput('payment[items][value]', '', ['class' => 'form-control']) ?>
                </div>
                <div class="col-lg-2" style="padding-left: 0px;">
                    <?= Html::buttonInput('<i class="fa fa-plus></i>"', ['class' => 'btn btn-primary', 'style' => 'margin-top:24px;']) ?>
                </div>
            </div>
            <div class="box-footer box-comments">
                <div class="col-lg-12">
                <?=
                Html::submitButton($model->isNewRecord ? 'Complete' : '', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary'])
                ?></div>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>