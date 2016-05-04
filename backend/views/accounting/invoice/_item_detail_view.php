<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
?>
<td>
    <span class="serial"></span>
    <?= Html::activeHiddenInput($model, "[$key]item_type", ['data-field' => 'item_type', 'id' => false]) ?>
    <?= Html::activeHiddenInput($model, "[$key]item_id", ['data-field' => 'item_id', 'id' => false]) ?>
</td>
<td><span data-field="item"><?= Html::getAttributeValue($model, "[$key]product[name]") ?></span></td>
<td style="text-align:right;">
    <span data-field="qty"><?= Html::getAttributeValue($model, "[$key]qty") ?></span>
</td>
<td style="text-align:right;">
    <span data-field="item_value"><?= number_format(Html::getAttributeValue($model, "[$key]item_value") ,0) ?></span>
</td>
<td style="text-align:right;"><span data-field="line_total"><?= number_format(Html::getAttributeValue($model, "[$key]qty") * Html::getAttributeValue($model, "[$key]item_value"),0) ?></span></td>

