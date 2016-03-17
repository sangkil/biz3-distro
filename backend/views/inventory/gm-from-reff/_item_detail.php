<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
?>
<td>
    <span class="serial"></span>
    <?= Html::activeHiddenInput($model, "[$key]product_id", ['id' => false]); ?>
</td>
<td>
    <span data-field="product"><?= Html::getAttributeValue($model, "[$key]product[name]") ?></span>
</td>
<td>
    <?= Html::getAttributeValue($model, "[$key]cogs"); ?>
</td>
<td>
    <?= Html::getAttributeValue($model, "[$key]sisa"); ?>
</td>
<td>
    <?=
    Html::activeTextInput($model, "[$key]qty", ['class' => 'form-control',
        'data-field' => 'qty', 'size' => 5, 'id' => false])
    ?>
</td>
<td>
    <?= Html::getAttributeValue($model, "[$key]uom[name]"); ?>
</td>
<td style="text-align: right;">
    <span data-field="totalLine"><?=
        (Html::getAttributeValue($model, "[$key]cogs") * Html::getAttributeValue($model, "[$key]qty") * 1)
        ?></span>
</td>