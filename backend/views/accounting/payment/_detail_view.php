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
                Number
            </th>
            <th class="items" style="width: 20%">
                Date
            </th>
            <th class="items" style="width: 20%">
                Time to Due
            </th>
            <th style="width: 20%;">
                Invoice Value
            </th>
            <th style="width: 20%;">
                Payment Value
            </th>
            <th class="items">
                Sisa
            </th>
        </tr>
    </thead>
    <?=
    TabularInput::widget([
        'id' => 'detail-grid',
        'allModels' => $model->items,
        'model' => PaymentDtl::className(),
        'tag' => 'tbody',
        'itemOptions' => ['tag' => 'tr'],
        'itemView' => '_item_detail_view',
        'clientOptions' => [
        ]
    ])
    ?>
</table>
