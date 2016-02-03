<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use backend\models\inventory\GoodsMovement;
use backend\models\master\Warehouse;

/* @var $this yii\web\View */
/* @var $model GoodsMovement */
/* @var $form ActiveForm */
?>

<div class="goods-movement-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= Html::errorSummary($model); ?>
    <div class="row">
        <div class="col-md-6">
            <div class="box box-primary">
                <div class="box-body">
                    <?= $form->field($model, 'number')->staticControl() ?>
                    <?=
                    $form->field($model, 'type')->dropDownList(GoodsMovement::enums('TYPE_'))
                    ?>
                    <?=
                    $form->field($model, 'warehouse_id')->dropDownList(Warehouse::selectOptions())
                    ?>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="box box-primary">
                <div class="box-body">
                    <?=
                    $form->field($model, 'Date')->widget('yii\jui\DatePicker', [
                        'dateFormat' => 'dd-MM-yyyy',
                        'options' => ['class' => 'form-control']
                    ])
                    ?>
                    <?= $form->field($model, 'vendor_id')->textInput() ?>

                    <?= $form->field($model, 'description')->textInput(['maxlength' => true]) ?>
                </div>
            </div>
        </div>
    </div>
    <div class="form-group">
        <?=
        Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success'
                    : 'btn btn-primary'])
        ?>
    </div>

    <?= $this->render('_detail', ['model' => $model]) ?>

<?php ActiveForm::end(); ?>

</div>
