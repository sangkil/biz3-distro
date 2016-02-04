<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
?>
<td style="width: 50px">
    <span class="serial"></span>
    <a data-action="delete" title="Delete" href="#"><span class="glyphicon glyphicon-trash"></span></a>
    <?= Html::activeHiddenInput($model, "[$key]item_id", ['data-field' => 'item_id', 'id' => false]) ?>
</td>
<td>
    <span data-field="item"></span>
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
    
</td>

