<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\models\master\Branch;
use backend\models\master\Warehouse;

/* @var $this yii\web\View */
/* @var $model backend\models\sales\Config */
/* @var $form ActiveForm */
?>
<div class="sales-sales-x-config">

    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-lg-4">
            <?= $form->field($model, 'branch_id')->dropDownList(Branch::selectOptions()) ?>
            <?= $form->field($model, 'warehouse_id')->dropDownList(Warehouse::selectOptions()) ?>
        </div>
        <div class="col-lg-12">
            <div class="form-group">
                <?= Html::submitButton('Submit', ['class' => 'btn btn-primary']) ?>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>

</div><!-- sales-sales-x-config -->
