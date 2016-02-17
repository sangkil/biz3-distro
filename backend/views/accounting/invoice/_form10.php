<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use backend\models\accounting\Invoice;
use yii\jui\JuiAsset;
use yii\helpers\Url;
use yii\web\View;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model Invoice */
/* @var $form ActiveForm */

JuiAsset::register($this);
$opts = json_encode([
    'product_url' => Url::to(['product-list']),
    'vendor_url' => Url::to(['vendor-list']),
        ]);

$this->registerJs("var biz = $opts;", View::POS_HEAD);
$this->registerJs($this->render('_script.js'));
?>

<div class="invoice-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= Html::errorSummary($model); ?>
    <div class="row">
        <div class="col-md-4">
            <?php
            $bgcolor = ($model->status == $model::STATUS_DRAFT) ? 'bg-yellow' : 'bg-green';
            $bgcolor = ($model->status == $model::STATUS_CANCELED) ? 'bg-red' : $bgcolor;
            ?>
            <?= $form->field($model, 'number')->textInput(['readonly' => true, 'style' => 'width:40%;'])->label('Number &nbsp;' . Html::tag('td', Html::tag('span', $model->nmStatus, ['class' => "badge $bgcolor"]))) ?>
            <?= (!$model->isNewRecord) ? $form->field($model->vendor, 'name')->textInput(['id' => 'invoice-vendor_name', 'required' => true])->label('Vendor Name') :
                    $form->field($model, 'vendor_name')->textInput(['required' => true])
            ?>
<?= Html::activeHiddenInput($model, 'vendor_id') ?>
        </div>
        <div class="col-md-4">
            <?=
            $form->field($model, 'Date')->widget('yii\jui\DatePicker', [
                'dateFormat' => 'dd-MM-yyyy',
                'options' => ['class' => 'form-control', 'style' => 'width:40%;']
            ])
            ?>
            <?=
            $form->field($model, 'DueDate')->widget('yii\jui\DatePicker', [
                'dateFormat' => 'dd-MM-yyyy',
                'options' => ['class' => 'form-control', 'style' => 'width:40%;']
            ])
            ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'value')->textInput(['required' => true, 'style' => 'width:40%;']) ?>
<?= $form->field($model, 'description')->textarea() ?>
        </div>
        <div class="nav-tabs-justified col-lg-12"  style="margin-top: 20px;">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#item" data-toggle="tab" aria-expanded="false">Items</a></li>
                <li><a href="#notes" data-toggle="tab" aria-expanded="false">Notes</a></li>
                <li class="pull-right">
                    <?=
                    Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary'])
                    ?>
                </li>
            </ul>
            <div class="tab-content" >
                <div class="tab-pane active" id="item">
<?= $this->render('_detail', ['model' => $model]) ?>
                </div>
                <div class="tab-pane" id="notes">
                    <?=
                    DetailView::widget([
                        'model' => $model,
                        'options' => ['class' => 'table'],
                        'template' => '<tr><th style="width:20%;">{label}</th><td>{value}</td></tr>',
                        'attributes' => [
                            'created_by',
                            'created_at:datetime',
                            'updated_by',
                            'updated_at:datetime',
                        ],
                    ])
                    ?>
                </div>
            </div>
        </div>
    </div>
<?php ActiveForm::end(); ?>

</div>
