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
$this->registerJsFile(Url::to(['master']));
?>

<div class="sales-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= Html::errorSummary($model); ?>
    <div class="row">
        <div class="nav-tabs-justified col-lg-12"  style="margin-top: 20px;">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#form1" data-toggle="tab" aria-expanded="false">Header</a></li>
                <li><a href="#form2" data-toggle="tab" aria-expanded="false">Payment</a></li>
            </ul>
            <div class="tab-content" >
                <div class="tab-pane active" id="form1">
                    <?= $this->render('_form1', ['form' => $form, 'model' => $model]) ?>
                </div>
                <div class="tab-pane" id="form2">
                    <?= $this->render('_form2', ['form' => $form, 'model' => $model,'payments'=>$payments,]) ?>
                </div>
            </div>
        </div>
        <div class="nav-tabs-justified col-lg-12"  style="margin-top: 20px;">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#item" data-toggle="tab" aria-expanded="false">Items</a></li>
                <li><a href="#notes" data-toggle="tab" aria-expanded="false">Notes</a></li>
                <li class="pull-right">
                    <?=
                    Html::submitButton('Create', ['class' => 'btn btn-success', 'id' => 'btn-bayar',
                        'data' => ['toggle' => 'modal', 'target' => '#payment-dlg']])
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
