<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
?>
<td style="width: 50px">
    <span class="serial"></span>
    <a data-action="delete" title="Delete" href="#"><span class="glyphicon glyphicon-trash"></span></a>
    <?= Html::activeHiddenInput($model, "[$key]invoice_id", ['data-field' => 'invoice_id', 'id' => false]) ?>
</td>
<td>
    <span data-field="invoice"><?= Html::getAttributeValue($model, "[$key]invoice[number]")?></span>
    &nbsp; Sisa: <span data-field="sisa"><?= Html::getAttributeValue($model, "[$key]invoice[sisa]")?></span>
</td>
<td class="items" style="width: 45%">
    <?=
    Html::activeTextInput($model, "[$key]value", [
        'data-field' => 'value',
        'size' => 10, 'id' => false,
        'required' => true])
    ?>
</td>

