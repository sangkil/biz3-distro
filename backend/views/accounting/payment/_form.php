<?php

use yii\web\View;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use backend\models\accounting\Payment;
use yii\jui\JuiAsset;
use yii\helpers\Url;

/* @var $this View */
/* @var $model Payment */
/* @var $form ActiveForm */

JuiAsset::register($this);
$opts = json_encode([
    'invoice_url' => Url::to(['invoice-list']),
    'vendor_url' => Url::to(['vendor-list']),
    ]);

$this->registerJs("var biz = $opts;", View::POS_HEAD);
$this->registerJs($this->render('_script.js'));
?>

<div class="payment-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= Html::errorSummary($model); ?>
    <div class="row">
        <div class="col-md-4">
            <?= $form->field($model, 'number')->textInput(['readonly' => true, 'style' => 'width:40%;']) ?>
            <?= $form->field($model, 'type')->dropDownList(Payment::enums('TYPE_'), ['style' => 'width:60%;']) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'vendor_name')->textInput(['required' => true]) ?>
            <?= Html::activeHiddenInput($model, 'vendor_id') ?>
            <?=
            $form->field($model, 'Date')->widget('yii\jui\DatePicker', [
                'dateFormat' => 'dd-MM-yyyy',
                'options' => ['class' => 'form-control', 'style' => 'width:40%;']
            ])
            ?>
        </div>
        <div class="col-md-4">
            <?=
            $form->field($model, 'payment_method')->dropDownList(Payment::enums('METHOD_'), [
                'prompt' => '--Pilih payment method--', 'style' => 'width:60%;'])
            ?>
        </div>
        <div class="nav-tabs-justified col-lg-12"  style="margin-top: 20px;">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#item" data-toggle="tab" aria-expanded="false">Items</a></li>
                <li><a href="#notes" data-toggle="tab" aria-expanded="false">Notes</a></li>
                <li class="pull-right">
                    <?=
                    Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success'
                                : 'btn btn-primary'])
                    ?>
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
    </div>
<?php ActiveForm::end(); ?>

</div>
