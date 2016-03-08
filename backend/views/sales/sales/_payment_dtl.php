<?php

use yii\web\View;
use backend\models\accounting\Payment;
use yii\helpers\Html;

/* @var $this View */
/* @var $model Payment */
?>
<td >
    <a data-action="delete" title="Delete" href="#"><span class="glyphicon glyphicon-trash"></span></a>
</td>
<td>
    <?=
    Html::activeHiddenInput($model, "[$key]payment_method", [
        'id' => false, 'data-field' => 'method', 'readonly' => true])
    ?>
    <span data-field="t-method"><?= Html::getAttributeValue($model, "[$key]paymentMethod[method]") ?></span>
</td>
<td >
    <?=
    Html::activeTextInput($model, "[$key]items[0][value]", [
        'id' => false, 'data-field' => 'value', 'readonly' => true,
        'style' => ['text-align' => 'right']])
    ?>
</td>