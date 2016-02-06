<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use backend\models\purchase\Purchase;
use backend\models\master\Branch;

/* @var $this yii\web\View */
/* @var $model Purchase */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="purchase-form">

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
                    <?= $form->field($model, 'supplier_id')->textInput() ?>
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
