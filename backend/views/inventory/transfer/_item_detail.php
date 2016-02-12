<?php

use yii\helpers\Html;
use backend\models\master\Uom;

/* @var $this yii\web\View */
?>
<td>
    <span class="serial"></span>
    <a data-action="delete" title="Delete" href="#"><span class="glyphicon glyphicon-trash"></span></a>
    <?=
    Html::activeHiddenInput($model, "[$key]product_id", ['class' => 'form-control',
        'data-field' => 'product_id', 'id' => false])
    ?>
</td>
<td>
    <span data-field="product"><?= Html::getAttributeValue($model, "[$key]product[name]") ?></span>
</td>
<td class="items">
    <?=
    Html::activeTextInput($model, "[$key]qty", ['class' => 'form-control',
        'data-field' => 'qty', 'size' => 5, 'id' => false, 'required' => true])
    ?>
</td>
<td>
    <?=
    Html::activeDropDownList($model, "[$key]uom_id", Uom::selectOptions(), ['class' => 'form-control',
        'data-field' => 'uom_id', 'id' => false])
    ?>
</td>

