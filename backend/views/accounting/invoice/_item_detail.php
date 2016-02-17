<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
?>
<td>
    <span class="serial"></span>
    <a data-action="delete" title="Delete" href="#"><span class="glyphicon glyphicon-trash"></span></a>
        <?= Html::activeHiddenInput($model, "[$key]item_type", ['data-field' => 'item_type', 'id' => false]) ?>
        <?= Html::activeHiddenInput($model, "[$key]item_id", ['data-field' => 'item_id', 'id' => false]) ?>
</td>
<td><span data-field="item"><?= Html::getAttributeValue($model, "[$key]product[name]") ?></span></td>
<td>
    <?=
    Html::activeInput('number', $model, "[$key]qty", [
        'data-field' => 'qty',
        'size' => 5, 'id' => false,
        'required' => true,
        'class' => 'form-control',
        'style'=>'text-align:right;'])
    ?>
</td>
<td>
    <?=
    Html::activeTextInput($model, "[$key]item_value", [
        'data-field' => 'item_value',
        'size' => 10, 'id' => false,
        'required' => true,
        'class' => 'form-control',
        'style'=>'text-align:right;'])
    ?>
</td>
<td style="text-align:right;"><span data-field="line_total"><?= Html::getAttributeValue($model, "[$key]qty") * Html::getAttributeValue($model, "[$key]item_value") ?></span></td>

