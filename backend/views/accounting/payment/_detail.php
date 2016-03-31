<?php

use yii\web\View;
use mdm\widgets\TabularInput;
use backend\models\accounting\Payment;
use backend\models\accounting\PaymentDtl;

/* @var $this View */
/* @var $model Payment */
?>

<table class="table table-striped">
    <thead>
        <tr>
            <th style="width: 10%">#</th>
            <th>
                Invoice Number
            </th>
            <th class="items" style="width: 20%">
                Sisa
            </th>
            <th style="width: 20%">
                Value
            </th>
        </tr>
        <tr>
            <td colspan="2">
                <div class="input-group" style="width:100%;">
                    <span class="input-group-addon">
                        <i class="fa fa-search"></i>
                    </span>
                    <input id="input-invoice" class="form-control" placeholder="Search Invoice..">
                </div>
            </td>
        </tr>
    </thead>
    <?=
    TabularInput::widget([
        'id' => 'detail-grid',
        'allModels' => $model->items,
        'model' => PaymentDtl::className(),
        'tag' => 'tbody',
        'itemOptions' => ['tag' => 'tr'],
        'itemView' => '_item_detail',
        'clientOptions' => [
        ]
    ])
    ?>
</table>
