<?php

use yii\web\View;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use backend\models\accounting\Payment;
use mdm\widgets\TabularInput;

/* @var $this View */
/* @var $model Sales */
/* @var $form ActiveForm */
?>
<div class="row">
    <div class="col-md-6">
        <div class="form-group field-sales-value required">
            <label class="control-label">Value</label>
            <input type="text" style="width:40%;" readonly="" class="form-control" id="payment-value">
        </div>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th><?=
                        Html::dropDownList('', null, Payment::enums('METHOD_'), [
                            'class' => 'form-control', 'id' => 'inp-payment-method'
                        ])
                        ?></th>
                    <th><input class="form-control" id="inp-payment-value"></th>
                    <th><a class="btn" id="add-payment"><span class="glyphicon glyphicon-plus"></span></a></th>
                </tr>
            </thead>
        </table>
    </div>
    <div class="col-md-6">
        <div class="form-group field-sales-value required">
            <label class="control-label">Total</label>
            <input type="text" style="width:40%;" readonly="" class="form-control" id="payment-total">
        </div>
        <table class="table table-striped">
            <?=
            TabularInput::widget([
                'id' => 'payment-grid',
                'allModels' => $payments,
                'modelClass' => Payment::className(),
                'options' => ['tag' => 'tbody'],
                'itemOptions' => ['tag' => 'tr'],
                'itemView' => '_payment_detail',
                'clientOptions' => [
                ]
            ])
            ?>
        </table>
    </div>
</div>