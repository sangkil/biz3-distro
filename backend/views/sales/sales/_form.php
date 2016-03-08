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
    ]);

$this->registerJs("var biz = $opts;", View::POS_HEAD);
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
                <h3><span id="sales-value-text">Rp0</span>&nbsp;</h3>
                <p><span id="sales-qty-text">0 Items</span></p>
                <input type="hidden" id="sales-value">
                <input type="hidden" id="sales-qty">
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
                <h3 class="box-title">Casier : <?= (Yii::$app->user->isGuest) ? 'Guest' : Yii::$app->user->identity->username ?></h3>
                <div class="box-tools pull-right">
                    <div class="button-group">
                        <button type="button" class="btn btn-box-tool dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                            <i class="fa fa-gear"></i></button>
                        <ul class="dropdown-menu" role="menu">
                            <div class="col-lg-12">
                                <li>Active Branch</li>
                                <li>Warehouse</li>
                                <li class="divider"></li>
                                <li>Something else here</li>
                            </div>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="box-body with-border">
                <!--                <div class="col-lg-5">
                <?= $form->field($model, 'number')->textInput(['readonly' => true]) ?>
                <?= ''//$form->field($model, 'branch_id')->dropDownList(Branch::selectOptions())  ?>
                                </div>-->
                <div class="col-lg-12">
                    <?= $form->field($model, 'vendor_name')->textInput([])->label('Customer') ?>
                    <?= $form->field($model, 'vendor_id')->hiddenInput()->label(false) ?>
                </div>
                <div id="payment-form" class="hidden">
                    <?= Html::hiddenInput('payment-value', 0, ['id' => 'payment-value']) ?>
                    <div class="grid-view col-lg-12 hidden" id="payment-grid">
                        <table class="table table-striped">
                            <thead>
                                <tr class="bg-green">
                                    <th>#</th>
                                    <th>Payment Type</th>
                                    <th>Value</th>
                                </tr>
                            </thead>
                            <?=
                            TabularInput::widget([
                                'id' => 'payment-grid-dtl',
                                'allModels' => $payments,
                                'modelClass' => Payment::className(),
                                'options' => ['tag' => 'tbody'],
                                'itemOptions' => ['tag' => 'tr'],
                                'itemView' => '_payment_dtl',
                                'clientOptions' => [
                                ]
                            ])
                            ?>
                        </table>
                    </div>
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
                        <?= Html::buttonInput('Add', ['class' => 'btn btn-primary', 'style' => 'margin-top:24px;',
                            'id' => 'btn-payment-add']) ?>
                    </div>
                </div>
            </div>
            <div class="box-footer box-comments hidden" id="payment-completion">
                <div class="col-lg-12">
                    <?=
                    Html::submitButton($model->isNewRecord ? 'Complete' : '', ['class' => $model->isNewRecord ? 'btn btn-success'
                                : 'btn btn-primary', 'id' => 'submit-btn'])
                    ?></div>
            </div>
        </div>
    </div>
<?php ActiveForm::end(); ?>
</div>