<?php

use yii\web\View;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use backend\models\inventory\Transfer;
use backend\models\master\Branch;
use yii\jui\JuiAsset;
use yii\helpers\Url;

/* @var $this View */
/* @var $model Transfer */
/* @var $form ActiveForm */

JuiAsset::register($this);
$opts = json_encode([]);

$this->registerJs("var biz = $opts;", View::POS_HEAD);
$this->registerJs($this->render('_script.js'));
$this->registerJsFile(Url::to(['master']));
?>

<div class="transfer-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= Html::errorSummary($model); ?>
    <div class="row">
        <div class="col-md-4">
            <?= $form->field($model, 'number')->textInput(['readonly' => true, 'style' => 'width:40%;']) ?>
            <?= $form->field($model, 'branch_id')->dropDownList(Branch::selectOptions()) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'branch_dest_id')->dropDownList(Branch::selectOptions()) ?>
            <?=
            $form->field($model, 'Date')->widget('yii\jui\DatePicker', [
                'dateFormat' => 'dd-MM-yyyy',
                'options' => ['class' => 'form-control', 'style' => 'width:40%;']
            ])
            ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'value')->textInput(['style' => 'width:40%;']) ?>
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
