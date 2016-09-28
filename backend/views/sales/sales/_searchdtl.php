<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\models\master\Branch;

/* @var $this yii\web\View */
/* @var $model backend\models\sales\search\SalesDtl */
/* @var $form yii\widgets\ActiveForm */

/*
 * Create By Mujib Masyhudi <mujib.masyhudi@gmail.com>
 * Create at {date('now')}
 */
?> 
<?php
$form = ActiveForm::begin([
            'method' => 'get',
        ]);
?> 
<div class="sales-dtl-search">
    <div class="col-lg-12">
        <?= $form->field($model, 'branch_id')->dropDownList(Branch::selectOptions(), ['style' => 'width:16%;','prompt'=>'-- All --']) ?>
    </div>
    <div class="col-lg-2">
        <?=
        $form->field($model, 'FrDate')->widget(\yii\jui\DatePicker::className(), [
            'dateFormat' => 'dd-MM-yyyy',
            'options' => ['class' => 'form-control']
        ])
        ?>    
    </div>
    <div class="col-lg-2">
        <?=
        $form->field($model, 'ToDate')->widget(\yii\jui\DatePicker::className(), [
            'dateFormat' => 'dd-MM-yyyy',
            'options' => ['class' => 'form-control']
        ])
        ?>    
    </div>    
    <div class="col-lg-8 form-group"> 
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary','style'=>'margin-top:25px;']) ?> 
    </div> 
</div> 

<?php ActiveForm::end(); ?> 