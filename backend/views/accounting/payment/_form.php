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
        <div class="col-md-12">
            <div class="form-group">
                <?=
                Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success'
                            : 'btn btn-primary'])
                ?>
            </div>
        </div>
        <div class="col-md-6">
            <div class="box">
                <div class="box-body">
                    <?= $form->field($model, 'number')->staticControl() ?>
                    <?= $form->field($model, 'vendor_name')->textInput(['required' => true]) ?>
                    <?= Html::activeHiddenInput($model, 'vendor_id') ?>
                    <?=
                    $form->field($model, 'type')->dropDownList(Payment::enums('TYPE_'),['prompt'=>'--Pilih payment typr--'])
                    ?>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="box">
                <div class="box-body">
                    <?=
                    $form->field($model, 'Date')->widget('yii\jui\DatePicker', [
                        'dateFormat' => 'dd-MM-yyyy',
                        'options' => ['class' => 'form-control']
                    ])
                    ?>
                    <?= $form->field($model, 'payment_type')->dropDownList(Payment::enums('METHOD_'),['prompt'=>'--Pilih payment method--']) ?>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <?= $this->render('_detail', ['model' => $model]) ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>

</div>
