<?php

use yii\web\View;
use yii\helpers\Html;
use backend\models\accounting\Payment;

/* @var $this View */
/* @var $model Payment */
?>
<td>
    <?= Html::activeHiddenInput($model, "[$key]payment_method", ['id' => false, 'data-field' => 'payment_method']) ?>
    <span data-field="nm_method"><?= Html::getAttributeValue($model, "[$key]nmMethod") ?></span>
</td>
<td>
    <?= Html::activeTextInput($model, "[$key]items[0][value]", ['id' => false,
        'data-field' => 'payment_value', 'class' => 'form-control'])
    ?>
</td>
<td><a class="btn" data-action="delete"><span class="glyphicon glyphicon-minus"></span></a></td>