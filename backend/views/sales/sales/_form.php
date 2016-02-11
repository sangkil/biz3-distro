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
    'product_url' => Url::to(['list-product']),
    ]);

$this->registerJs("var biz = $opts;", View::POS_HEAD);
$this->registerJs($this->render('_script.js'));
?>

<div class="sales-form">

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
                    <?= $form->field($model, 'vendor_id')->textInput() ?>
                    <?=
                    $form->field($model, 'branch_id')->dropDownList(Branch::selectOptions())
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
                    <?= $form->field($model, 'value')->textInput() ?>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <?= $this->render('_detail', ['model' => $model]) ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>

</div>
