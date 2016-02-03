<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
?>
<td style="width: 50px">
    <a data-action="delete" title="Delete" href="#"><span class="glyphicon glyphicon-trash"></span></a>
    <?= Html::activeHiddenInput($model, "[$key]product_id", ['data-field' => 'product_id', 'id' => false]) ?>
</td>
<td>
    <span data-field="product"><?= $model->product_id?></span>
</td>
<td class="items" style="width: 45%">
    <?=
    Html::activeTextInput($model, "[$key]qty", [
        'data-field' => 'qty',
        'size' => 5, 'id' => false,
        'required' => true])
    ?>
</td>
<td>
    <?= Html::activeDropDownList($model, "[$key]uom_id", [1 => 'Pcs', 2 => 'Dz'], ['data-field' => 'uom_id',
        'id' => false])
    ?>

</td>

