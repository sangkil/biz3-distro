<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
?>
<td>
    <span class="serial"></span>
    <?= Html::activeHiddenInput($model, "[$key]product_id", ['id' => false]); ?>
    <?= Html::activeHiddenInput($model, "[$key]productUom[isi]", ['id' => false, 'data-field' => 'isi']); ?>
    <?= Html::activeHiddenInput($model, "[$key]cogs", ['id' => false, 'data-field' => 'cogs']); ?>
</td>
<td>
    <span data-field="product"><?= Html::getAttributeValue($model, "[$key]product[name]") ?></span>
</td>
<td>
    <span data-field="price"><?= number_format(Html::getAttributeValue($model, "[$key]cogs"), 0); ?></span>
</td>
<td>
    <?= ($is_issue) ? Html::getAttributeValue($model, "[$key]sisa") : Html::getAttributeValue($model, "[$key]issued") ?>
</td>
<td>
    <?=
    Html::activeTextInput($model, "[$key]qty", ['class' => 'form-control', 'data-field' => 'qty', 'size' => 5, 'id' => false,
        'value' => ($is_issue) ? Html::getAttributeValue($model, "[$key]qty") : Html::getAttributeValue($model, "[$key]issued"),
        'readOnly' => ($is_issue && Html::getAttributeValue($model, "[$key]sisa") < 1) ? true : false])
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