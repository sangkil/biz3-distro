<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
?>
<?php
$inputDtl = Html::tag('td', Html::activeHiddenInput($model, "[$key]coa_id", ['data-field' => 'coa_id', 'id' => false])
        .Html::tag('span', Html::getAttributeValue($model, "[$key]coa[code]"), ['data-field' => 'coa_code', 'id' => false]));
$inputDtl .= Html::tag('td', Html::tag('span', Html::getAttributeValue($model, "[$key]coa[name]"), ['data-field' => 'coa_name', 'id' => false]));
$inputDtl .= Html::tag('td', Html::activeHiddenInput($model, "[$key]debit", ['data-field'=>'idebit'])
        .Html::tag('span', Html::getAttributeValue($model, "[$key]debit"), ['data-field' => 'debit', 'id' => false]),['style'=>'text-align:right;']);
$inputDtl .= Html::tag('td', Html::activeHiddenInput($model, "[$key]credit", ['data-field'=>'icredit'])
        .Html::tag('span', Html::getAttributeValue($model, "[$key]credit"), ['data-field' => 'credit', 'id' => false]),['style'=>'text-align:right;']);
echo $inputDtl;