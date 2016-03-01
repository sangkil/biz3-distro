<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
?>
<?php
$inputDtl = Html::tag('td', Html::activeHiddenInput($model, "[$key]coa_id", ['data-field' => 'coa_id', 'id' => false])
        .Html::tag('span', Html::getAttributeValue($model, "[$key]coa[code]"), ['data-field' => 'coa_code', 'id' => false]));
$inputDtl .= Html::tag('td', Html::tag('span', Html::getAttributeValue($model, "[$key]coa[name]"), ['data-field' => 'coa_name', 'id' => false]));
$inputDtl .= Html::tag('td', Html::activeTextInput($model, "[$key]debit", ['data-field'=>'idebit','class'=>'form-control','style'=>'text-align:right; ']));
$inputDtl .= Html::tag('td', Html::activeTextInput($model, "[$key]credit", ['data-field'=>'icredit','class'=>'form-control','style'=>'text-align:right; ']));
$inputDtl .= Html::tag('td', Html::a('<i class="fa fa-trash"></i>', '#', ['class' => 'btn btn-default text-red btn-minus', 'data-field'=>'btn-minus']));
echo $inputDtl;