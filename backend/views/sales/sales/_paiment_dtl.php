<?php

use yii\web\View;
use backend\models\accounting\Payment;
use yii\helpers\Html;

/* @var $this View */
/* @var $model Payment */
?>
<td >
    <span class="serial"></span>&nbsp;
    <a data-action="delete" title="Delete" href="#"><span class="glyphicon glyphicon-trash"></span></a>
</td>
<td>
    <span data-field="type">Pymnt Type</span>
</td>
<td >
    <span data-field="value">0</span>
</td>