<?php

use yii\web\View;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use backend\models\sales\Sales;
use yii\jui\JuiAsset;
use yii\helpers\Url;
use mdm\widgets\TabularInput;
use backend\models\accounting\Payment;
use backend\models\accounting\PaymentMethod;

/* @var $this View */
/* @var $model Sales */
/* @var $form ActiveForm */

JuiAsset::register($this);
$opts = json_encode([
    'price_category' => '1',
    'reloadOnBranchChange' => true
    ]);

$this->registerJs("yii.biz.prop($opts);");
$this->registerJs($this->render('_script.js'));
$this->registerJsFile(Url::to(['master']));
$branch_id = Yii::$app->profile->branch_id;
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
                <?= Html::activeHiddenInput($model, 'value') ?>
                <h3><span id="sales-value-text">Rp0</span>&nbsp;</h3>
                <p><span id="sales-qty-text">0 Items</span></p>
                <input type="hidden" id="sales-qty">
            </div>
            <div class="icon">
                <i class="fa fa-shopping-cart"></i>
            </div>
            <!--<a href="#" class="small-box-footer">-->
<!--                <?= ''//'Sales Date: ' . Html::getAttributeValue($model, 'Date')   ?> <i class="fa fa-calendar"></i>-->
            <!--</a>-->
        </div>
        <div class="box box-info box-comments with-border">
            <div class="box-header with-border">
                <i class="fa fa-shopping-cart text-orange"></i>
                <h3 class="box-title"><p class="text-bold">
                    Casier : <?= (Yii::$app->user->isGuest) ? 'Guest' : Yii::$app->user->identity->username ?>
                </p></h3>
                <div class="pull-right text-bold"><i class="fa fa-map-marker text-green"></i>&nbsp;<?= $warehouse ?></div>
                
            </div>
            <div class="box-body with-border">
                <!--                <div class="col-lg-5">
                <?= $form->field($model, 'number')->textInput(['readonly' => true]) ?>
                <?= ''//$form->field($model, 'branch_id')->dropDownList(Branch::selectOptions())  ?>
                                </div>-->
                <div class="col-lg-6">
                    <?= $form->field($model, 'vendor_name')->textInput([])->label('Customer') ?>
                </div>
                <div class="col-lg-6">
                    <?=
                    $form->field($model, 'Date')->widget('yii\jui\DatePicker', [
                        'dateFormat' => 'dd-MM-yyyy',
                        'options' => ['class' => 'form-control', 'style' => 'width:60%;']
                    ])
                    ?>
                </div>
                <div id="payment-form" class="hidden">
                    <?= Html::hiddenInput('payment-value', 0, ['id' => 'payment-value']) ?>
                    <div class="grid-view col-lg-12<?= count($payments) ? '' : ' hidden' ?>" id="payment-grid">
                        <table class="table table-striped">
                            <thead>
                                <tr class="bg-green">
                                    <th>#</th>
                                    <th style="width:60%;">Payment Type</th>
                                    <th>Value</th>
                                </tr>
                            </thead>
                            <?=
                            TabularInput::widget([
                                'id' => 'payment-grid-dtl',
                                'allModels' => $payments,
                                'model' => Payment::className(),
                                'tag' => 'tbody',
                                'itemOptions' => ['tag' => 'tr'],
                                'itemView' => '_payment_dtl',
                                'clientOptions' => [
                                ]
                            ])
                            ?>
                            <tfoot id="payback-panel" class="hidden">
                                <tr>
                                    <td>&nbsp;</td>
                                    <td>Payback</td>
                                    <td id="payback-value" style="text-align: right;"></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <div id="payment-input-panel">
                        <div class="col-lg-6">
                            <?= Html::label('Method') ?>
                            <?=
                            Html::dropDownList('', '', PaymentMethod::selectOptions($branch_id), [
                                'class' => 'form-control', 'id' => 'inp-payment-method'])
                            ?>
                        </div>
                        <div class="col-lg-4">
                            <?= Html::label('Value') ?>
                            <?=
                            Html::textInput('', '', ['class' => 'form-control',
                                'id' => 'inp-payment-value'])
                            ?>
                        </div>
                        <div class="col-lg-2" style="padding-left: 0px;">
                            <?=
                            Html::buttonInput('Add', ['class' => 'btn btn-primary', 'style' => 'margin-top:24px;',
                                'id' => 'btn-payment-add'])
                            ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="box-footer box-comments hidden" id="payment-completion">
                <div class="col-lg-12">
                    <?=
                    Html::a('Complete', null, ['class' => 'btn btn-success',
                        'id' => 'submit-btn', 'data-method' => 'post'])
                    ?></div>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>
